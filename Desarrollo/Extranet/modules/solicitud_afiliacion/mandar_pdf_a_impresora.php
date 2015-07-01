<?
//header("Content-Transfer-Encoding", "binary");
//header('Cache-Control: maxage=3600'); //Adjust maxage appropriately
//header('Pragma: public');
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


class PDF_AutoPrint extends PDF_Javascript {
	function AutoPrint($dialog = false) {
		// Launch the print dialog or start printing immediately on the standard printer..
		$param = ($dialog?"true":"false");
		$script = "print($param);";
		$this->IncludeJS($script);
	}

	function AutoPrintToPrinter($server, $printer, $dialog = false) {
		// Print on a shared printer (requires at least Acrobat 6)..
		$script = "var pp = getPrintParams();";
		if($dialog)
			$script.= "pp.interactive = pp.constants.interactionLevel.full;";
		else
			$script.= "pp.interactive = pp.constants.interactionLevel.automatic;";
		$script.= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
		$script.= "print(pp);";
		$this->IncludeJS($script);
	}
}


$params = array(":id" => $_REQUEST["id"]);
$sql = "SELECT ir_cantidadhojas, ir_idestablecimiento, ir_idtipopdf, ir_rutaarchivo FROM web.wir_impresionesrgrl WHERE ir_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt, 1, false);
$_REQUEST["idestablecimiento"] = $row["IR_IDESTABLECIMIENTO"];

$_REQUEST["ap"] = true;
switch ($row["IR_IDTIPOPDF"]) {
	case 1:
		$id = substr($_REQUEST["idmodulo"], 1);
		$params = array(":id" => $id);
		$sql =
			"SELECT sc_cotizacion_pcp
				 FROM asc_solicitudcotizacion
				WHERE sc_id = :id";
		if ((valorSql($sql, 0, $params) == "S") and (substr($_REQUEST["idmodulo"], 0, 1) == "C"))
			require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_solicitud_afiliacion_pcp.php");
		else
			require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_solicitud_afiliacion.php");
		break;
	case 2:
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_ubicacion_riesgo.php");
		break;
	case 3:
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_rgrl.php");
		break;
	case 4:
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_addenda.php");
		break;
	case 5:
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_responsabilidad_civil.php");
		break;
	case 6:
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_peps.php");
		break;
	case 7:
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_exposicion_riesgos_quimicos.php");
		break;
	case 8:
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_ventanilla_electronica.php");
		break;
	case 9:
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/reporte_nomina_personal_expuesto.php");
		break;
	default:
		$pdf = new PDF_AutoPrint();
 
		// Agrego el archivo pdf..
		$pdf->setSourceFile(DATA_SOLICITUD_AFILIACION.$row["IR_RUTAARCHIVO"]);

		for ($i=1; $i<=$row["IR_CANTIDADHOJAS"]; $i++) {
			// Agrego una página..
			$pdf->AddPage();

			// Selecciono la primer página..
			$tplIdx = $pdf->importPage($i);

			// Uso la primer página..
			$pdf->useTemplate($tplIdx);
		}
		$pdf->AutoPrint(false);
		$pdf->Output();
}


// Actualizo el registro..
$params = array(":id" => $_REQUEST["id"],
								":usuimpresion" => $_SESSION["usuario"],
								":usureimpresion" => $_SESSION["usuario"]);
$sql =
	"UPDATE web.wir_impresionesrgrl
			SET ir_estado = 'I',
					ir_fechaimpresion = DECODE(ir_fechaimpresion, NULL, SYSDATE, ir_fechaimpresion),
					ir_fechareimpresion = DECODE(ir_fechaimpresion, NULL, NULL, SYSDATE),
					ir_usuimpresion = DECODE(ir_usuimpresion, NULL, :usuimpresion, ir_usuimpresion),
					ir_usureimpresion = DECODE(ir_usuimpresion, NULL, :usureimpresion, SYSDATE)
	  WHERE ir_id = :id";
DBExecSql($conn, $sql, $params);
?>