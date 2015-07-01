<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");

function getTipoReporte($idSolicitudCotizacion, $soloPCP, $suscribePolizaRC) {
	if ($soloPCP)
		return 3;
	else {
		$params = array(":idsolicitudcotizacion" => $idSolicitudCotizacion);
		$sql =
			"SELECT SUM(cp_canttrabajador)
				 FROM afi.acp_cotizacion_pcp
				WHERE cp_idsolicitudcotizacion = :idsolicitudcotizacion";
		$pcpCargado = (valorSql($sql, 0, $params) > 0);

		if ($pcpCargado) {
			if ($suscribePolizaRC)
				return 5;
			else
				return 4;
		}
		else {
			if ($suscribePolizaRC)
				return 2;
			else
				return 1;
		}
	}
}


$fromDelphi = ((isset($_REQUEST["delphi"])) and ($_REQUEST["delphi"] == "t"));

validarParametro(isset($_REQUEST["id"]));

$id = intval(substr($_REQUEST["id"], 1));
$modulo = substr($_REQUEST["id"], 0, 1);

// INICIO Validaciones - Que el que quiere ver esta cotización sea del mismo canal - entidad - sucursal - vendedor..
if (!$fromDelphi)
	validarSesion(isset($_SESSION["isAgenteComercial"]));

if ($modulo == "R") {		// Si es una revisión de precio..
	$nombre = "carta_cotizacion_reafiliacion_";
	$sql =
		"SELECT sr_idcanal canal, sr_identidad entidad, sr_nrosolicitud nrosolicitud, sr_idsucursal sucursal, sr_idvendedor vendedor
			 FROM asr_solicitudreafiliacion
			WHERE sr_id = :id";
}
else {		// Sino es una solicitud de cotización..
	$nombre = "carta_cotizacion_";
	$sql =
		"SELECT sc_canal canal, sc_identidad entidad, sc_nrosolicitud nrosolicitud, sc_idsucursal sucursal, sc_idvendedor vendedor
			 FROM asc_solicitudcotizacion
			WHERE sc_id = :id";
}
$params = array(":id" => $id);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt, 1, false);

if (!$fromDelphi)
	validarAccesoCotizacion($_REQUEST["id"]);
// FIN Validaciones..


try {
	SetDateFormatOracle("DD/MM/YYYY");

	//	*******  INICIO - Armado del reporte..  *******
	$numeroSolicitud = $row["NROSOLICITUD"];
	$path = DATA_CARTA_COTIZACION.armPathFromNumber($numeroSolicitud);
	if (!makeDirectory($path))
		throw new Exception("ERROR: No se puede crear la carpeta.");
	$file = $path.$nombre.$numeroSolicitud.".pdf";

	// Armo el sql principal..
	$params = array(":id" => $id);
	if ($modulo == "R") {		// Si es una revisión de precio..
		$sql =
			"SELECT NULL artactual,
							DECODE(NVL(sr_canttrabajadores, 0), 0, hc_totempleadosmayorcero, sr_canttrabajadores) cantidadtrabajadores,
						  ac_codigo ciiu,
							art.afiliacion.get_clausulapenal clausulapenal,
						  art.utiles.armar_cuit(sr_cuit) cuit,
						  ((sr_costofijocotizado * 12 * DECODE(NVL(sr_canttrabajadores, 0), 0, hc_totempleadosmayorcero, sr_canttrabajadores)) + (sr_porcentajevariablecotizado / 100 * 13) * DECODE(NVL(sr_masasalarial, 0), 0, hc_masatotalmayorcero, sr_masasalarial)) cuotaanual,
						  DECODE(NVL(sr_canttrabajadores, 0), 0, hc_totempleadosmayorcero, sr_canttrabajadores) * 0 cuotainicialrc,
						  DECODE(NVL(sr_canttrabajadores, 0), 0, hc_totempleadosmayorcero, sr_canttrabajadores) * sr_costofinalcotizado cuotamensual,
						  sr_costofinalcotizado cuotatrabajador,
						  sr_actividadreal descripcionciiu,
							NULL difanualahorro,
							NULL difmensual,
							NULL difporcentual,
							100 difporcentualsinformato,
						  sr_fechanotificacioncomercial fecha,
							'N' iltempleador,
						  TO_CHAR(DECODE(NVL(sr_masasalarial, 0), 0, hc_masatotalmayorcero, sr_masasalarial), '$9,999,999,990.00') masasalarial,
						  sr_nrosolicitud nrosolicitud,
						  TO_CHAR(sr_porcentajevariablecotizado, '990.000') ||'%' porcentajevariabletrabajador,
							NULL porcvarcomp,
							NULL porcvariable,
							NULL primaanual,
							NULL primaanualcomp,
							NULL primaanualsinformato,
							NULL primaanualcompsinformato,
							NULL primamensual,
							NULL primamensualcomp,
							NULL primaxcapita,
							NULL primaxcapitacomp,
						  hm_nombre razonsocial,
							'N' solopcp,
						  NULL sumaaseguradarc,
							NULL sumafija,
							NULL sumafijacomp,
						  TO_CHAR(sr_costofijocotizado, '$9,999,999,990.00') sumafijatrabajador,
						  'N' suscribepolizarc,
						  NULL valorrc
				 FROM asr_solicitudreafiliacion asr, cac_actividad, ahc_historicocontrato, ahm_historicoempresa
				WHERE ac_id = NVL(sr_idactividad1, hc_idactividad)
					AND hc_id = sr_idhistoricocontrato
					AND hm_id = sr_idhistoricoempresa
					AND sr_id = :id";
	}
	else {		// Sino es una solicitud de cotización..
		$sql =
			"SELECT ar_nombre artactual,
							CASE WHEN NVL(co_orden, -1) = 0 THEN NVL(co_canttrabajador, sc_canttrabajador) ELSE sc_canttrabajador END cantidadtrabajadores,
						  NVL(cac_aco.ac_codigo, cac_asc.ac_codigo) ciiu,
							art.afiliacion.get_clausulapenal clausulapenal,
						  art.utiles.armar_cuit(sc_cuit) cuit,
						  NULL cuotaanual,
						  TO_CHAR(NVL(co_masasalarial, sc_masasalarial) * sc_valor_rc / 100, '$9,999,999,990.00') cuotainicialrc,
						  NULL cuotamensual,
						  NULL cuotatrabajador,
						  sc_actividadreal descripcionciiu,
							TO_CHAR(art.cotizacion.get_prima_anual(sc_id) - art.cotizacion.get_prima_anual_competencia(sc_id), '$9,999,999,990') difanualahorro,
							TO_CHAR(art.cotizacion.get_prima_mensual(sc_id) - art.cotizacion.get_prima_mensual_competencia(sc_id), '$9,999,999,990') difmensual,
							TO_CHAR(DECODE(art.cotizacion.get_prima_anual_competencia(sc_id), 0, 0, (art.cotizacion.get_prima_anual(sc_id) - art.cotizacion.get_prima_anual_competencia(sc_id)) * 100 / art.cotizacion.get_prima_anual_competencia(sc_id)), '00') || '%' difporcentual,
							DECODE(art.cotizacion.get_prima_anual_competencia(sc_id), 0, 0, (art.cotizacion.get_prima_anual(sc_id) - art.cotizacion.get_prima_anual_competencia(sc_id)) * 100 / art.cotizacion.get_prima_anual_competencia(sc_id)) difporcentualsinformato,
						  sc_fechavigencia fecha,
							co_chek_iltempleador iltempleador,
						  CASE WHEN NVL(co_orden, -1) = 0 THEN TO_CHAR(NVL(co_masasalarial, sc_masasalarial), '$9,999,999,990.00') ELSE TO_CHAR(sc_masasalarial, '$9,999,999,990.00') END masasalarial,
						  sc_nrosolicitud nrosolicitud,
						  NULL porcentajevariabletrabajador,
							TO_CHAR(art.cotizacion.get_porcentaje_variable_comp(sc_id), '990.000') || '%' porcvarcomp,
							TO_CHAR(art.cotizacion.get_porcentaje_variable(sc_id), '990.000') || '%' porcvariable,
							TO_CHAR(art.cotizacion.get_prima_anual(sc_id), '$9,999,999,990') primaanual,
							TO_CHAR(art.cotizacion.get_prima_anual_competencia(sc_id), '$9,999,999,990') primaanualcomp,
							ROUND(art.cotizacion.get_prima_anual(sc_id)) primaanualsinformato,
							ROUND(art.cotizacion.get_prima_anual_competencia(sc_id)) primaanualcompsinformato,
							TO_CHAR(art.cotizacion.get_prima_mensual(sc_id), '$9,999,999,990') primamensual,
							TO_CHAR(art.cotizacion.get_prima_mensual_competencia(sc_id), '$9,999,999,990') primamensualcomp,
							TO_CHAR(art.cotizacion.get_prima_x_capita(sc_id), '$9,999,999,990.00') primaxcapita,
							TO_CHAR(art.cotizacion.get_prima_x_capita_competencia(sc_id), '$9,999,999,990.00') primaxcapitacomp,
							NVL(sc_razonsocial, co_razonsocial) razonsocial,
							sc_cotizacion_pcp solopcp,
						  TO_CHAR(sc_sumaasegurada_rc, '$9,999,999,990.00') sumaaseguradarc,
							TO_CHAR(art.cotizacion.get_suma_fija(sc_id), '$9,999,999,990.00') sumafija,
							TO_CHAR(art.cotizacion.get_suma_fija_competencia(sc_id), '$9,999,999,990.00') sumafijacomp,
						  NULL sumafijatrabajador,
						  sc_poliza_rc suscribepolizarc,
						  TO_CHAR(sc_valor_rc, '990.000') ||'%' valorrc
				 FROM asc_solicitudcotizacion sc
		LEFT JOIN aco_cotizacion ON (co_id = sc_idcotizacion)
		LEFT JOIN cac_actividad cac_asc ON (cac_asc.ac_id = sc_idactividad)
		LEFT JOIN cac_actividad cac_aco ON (cac_aco.ac_id = co_idactividad)
		LEFT JOIN aca_canal ON (ca_id = sc_canal)
		LEFT JOIN aar_art ON (sc_idartanterior = ar_id)
				WHERE sc_id = :id";
	}
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt, 1, false);

	if ($modulo == "C") {		// Si es una solicitud de cotización, llamo al SP para tomar los valores de la carta..
		$curs = null;
		$params = array(":nrosolicitud" => $row["NROSOLICITUD"]);
		$sql = "BEGIN art.cotizacion.get_valor_carta(:nrosolicitud, :data); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params);
		$rowValorFinal = DBGetSP($curs, false);
		if ($rowValorFinal["COSTOANUAL"] == "")
			$rowValorFinal["COSTOANUAL"] = 0;
		if ($rowValorFinal["COSTOCAPITAS"] == "")
			$rowValorFinal["COSTOCAPITAS"] = 0;

		// Hago el query de abajo para formatear 2 campos que salian mal..
		$sql =
			"SELECT TO_CHAR(".str_replace(array("$", ","), array("", "."), $rowValorFinal["COSTOANUAL"]).", '$9,999,999,990.00') costoanual,
							TO_CHAR(".str_replace(array("$", ","), array("", "."), $rowValorFinal["COSTOCAPITAS"]).", '$9,999,999,990.00') costocapitas,
							TO_CHAR(".str_replace(array("$", ","), array("", "."), $rowValorFinal["PORCVARIABLE"]).", '$9,999,999,990.00') porcvariable
				 FROM DUAL";
		$stmt = DBExecSql($conn, $sql, array());
		$row2 = DBGetQuery($stmt, 1, false);

		$row["CUOTAANUAL"] = $row2["COSTOANUAL"];
		$row["CUOTAMENSUAL"] = $rowValorFinal["COSTOMENSUAL"];
		$row["CUOTATRABAJADOR"] = $row2["COSTOCAPITAS"];
		$row["PORCENTAJEVARIABLETRABAJADOR"] = $row2["PORCVARIABLE"];
		$row["SUMAFIJATRABAJADOR"] = $rowValorFinal["SUMAFIJA"];
	}


	$tipoReporte = getTipoReporte($id, ($row["SOLOPCP"] == "S"), ($row["SUSCRIBEPOLIZARC"] == "S"));

	$pdf = new FPDI("P", "mm", array(216, 280));

	// Dibujo la hoja de análisis comparativo de costos..
	if (($tipoReporte != 3) and ($row["DIFPORCENTUALSINFORMATO"] < -5)) {
		// INICIO - Generación de gráfico que va incrustado en el reporte..
		try {
			$_REQUEST["actual"] = $row["PRIMAANUALCOMPSINFORMATO"];
			$_REQUEST["archivo"] = "W_".((isset($_SESSION["usuario"]))?$_SESSION["usuario"]:"D")."_".date("YmdHis").".png";
			$_REQUEST["provart"] = $row["PRIMAANUALSINFORMATO"];

			require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_cotizacion/generar_grafico_comparativo.php");
			$graficoOk = true;
		}
		catch (Exception $e) {
			$graficoOk = false;
		}
		// FIN - Generación de gráfico que va incrustado en el reporte..

		if ($graficoOk) {
			$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_cotizacion/plantillas/analisis_comparativo_costos.pdf");
			$pdf->SetDrawColor(255, 255, 255);
			$pdf->SetFillColor(255, 255, 255);

			$pdf->AddPage();
			$tplIdx = $pdf->importPage(1);
			$pdf->useTemplate($tplIdx);
			$pdf->SetFont("Arial", "B", 10);

			$pdf->Ln(33);
			$pdf->Cell(40);
			$pdf->Cell(144, 0, $row["RAZONSOCIAL"]);

			$pdf->Ln(6.6);
			$pdf->Cell(28);
			$pdf->Cell(36, 0, $row["CUIT"]);

			$pdf->Cell(16);
			$pdf->Cell(80, 0, $row["CIIU"]);

			$pdf->Ln(6.6);
			$pdf->Cell(38);
			$pdf->Cell(146, 0, $row["DESCRIPCIONCIIU"]);

			$pdf->Ln(6);
			$pdf->Cell(32);
			$pdf->Cell(40, 0, $row["CANTIDADTRABAJADORES"]);

			$pdf->Cell(28);
			$pdf->Cell(80, 0, $row["MASASALARIAL"]);

			$pdf->Ln(24);
			$pdf->Cell(28);
			$pdf->Cell(66, 0, $row["ARTACTUAL"], 0, 0, "C");

			$pdf->Ln(16);
			$pdf->Cell(28);
			$pdf->Cell(28, 0, $row["SUMAFIJACOMP"], 0, 0, "R");

			$pdf->Cell(6);
			$pdf->Cell(28, 0, $row["PORCVARCOMP"], 0, 0, "R");

			$pdf->Cell(12);
			$pdf->Cell(26, 0, $row["SUMAFIJA"], 0, 0, "R");

			$pdf->Cell(8);
			$pdf->Cell(28, 0, $row["PORCVARIABLE"], 0, 0, "R");

			$pdf->Ln(14);
			$pdf->Cell(28);
			$pdf->Cell(28, 0, $row["PRIMAXCAPITACOMP"], 0, 0, "R");

			$pdf->Cell(6);
			$pdf->Cell(28, 0, $row["PRIMAMENSUALCOMP"], 0, 0, "R");

			$pdf->Cell(12);
			$pdf->Cell(26, 0, $row["PRIMAXCAPITA"], 0, 0, "R");

			$pdf->Cell(8);
			$pdf->Cell(28, 0, $row["PRIMAMENSUAL"], 0, 0, "R");

			$pdf->Ln(13);
			$pdf->Cell(28);
			$pdf->Cell(66, 0, $row["PRIMAANUALCOMP"], 0, 0, "C");

			$pdf->Cell(8.6);
			$pdf->Cell(65.4, 0, $row["PRIMAANUAL"], 0, 0, "C");

			$pdf->Ln(8.4);
			$pdf->Cell(100);
			$pdf->Cell(40, 0, $row["DIFMENSUAL"], 0, 0, "R");

			$pdf->Ln(5.6);
			$pdf->Cell(100);
			$pdf->Cell(40, 0, $row["DIFANUALAHORRO"], 0, 0, "R");

			$pdf->SetTextColor(255, 255, 255);
			$pdf->Ln(5.6);
			$pdf->Cell(100);
			$pdf->Cell(40, 0, $row["DIFPORCENTUAL"], 0, 0, "R");
			$pdf->SetTextColor(0, 0, 0);

			$pdf->Image(GRAFICO_CARTA_COTIZACION.$_REQUEST["archivo"], 50, 156, 120, 77);		// 400, 256
		}
	}

	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_cotizacion/generar_carta_intencion_".$tipoReporte.".php");
	//	*******  FIN - Armado del reporte..  *******
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<iframe id="iframePdf" name="iframePdf" src="<?= getFile($file)?>" style="height:376px; width:752px;"></iframe>
<p style="margin-left:696px;">
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</p>