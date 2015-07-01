<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


function dibujarParrafo($texto, $salto) {
	global $pdf;

	$pdf->Ln($salto);
	$pdf->WordWrap($texto, 176);
	$texto = explode("\n", $texto);
	for ($j=0; $j<count($texto); $j++) {
		$str = trim($texto[$j]);

		$pdf->Cell(30);
		$pdf->Cell(176, 0, $str);
		$pdf->Ln(3.8);
	}
}


SetDateFormatOracle("DD/MM/YYYY");

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT TRIM(TO_CHAR(rs_base, '$9,999,999,990.00')) base,
					'Liquidación Nro. ' || LPAD(lc_id, 12, '0') comprobante,
					utiles.armar_cuit(en_cuit) cuit,
					art.utiles.armar_domicilio(en_calle, en_numero, en_piso, en_departamento, NULL) || ' ' || art.utiles.armar_localidad(en_cpostal, NULL, en_localidad, en_provincia) domicilio,
					rs_fecha fecha,
					imp.tb_descripcion impuesto,
					en_nombre nombre,
					rs_numero numero,
					reg.tb_descripcion regimen,
					TRIM(TO_CHAR(rs_retencion, '$9,999,999,990.00')) retencion
		 FROM ctb_tablas reg, ctb_tablas imp, xen_entidad, xlc_liqcomision, xrs_retencionsicore
		WHERE lc_id = rs_idliquidacion
			AND en_id = lc_identidad
			AND imp.tb_codigo = rs_impuesto
			AND imp.tb_clave = 'CSIMP'
			AND reg.tb_codigo = rs_regimen
			AND reg.tb_clave = 'CSREG'
			AND lc_id = :id
UNION ALL
	 SELECT TRIM(TO_CHAR(rs_base, '$9,999,999,990.00')) base,
					'Liquidación Nro. ' || LPAD(lc_id, 12, '0') comprobante,
					utiles.armar_cuit(ve_cuit) cuit,
					art.utiles.armar_domicilio(ve_calle, ve_numero, ve_piso, ve_departamento, NULL) || ' ' || art.utiles.armar_localidad(ve_cpostal, NULL, ve_localidad, ve_provincia) domicilio,
					rs_fecha fecha,
					imp.tb_descripcion impuesto,
					ve_nombre nombre,
					rs_numero numero,
					reg.tb_descripcion regimen,
					TRIM(TO_CHAR(rs_retencion, '$9,999,999,990.00')) retencion
		 FROM ctb_tablas reg, ctb_tablas imp, xve_vendedor, xev_entidadvendedor, xlc_liqcomision, xrs_retencionsicore
		WHERE lc_id = rs_idliquidacion
			AND ev_id = lc_identidadvendedor
			AND ve_id = ev_idvendedor
			AND imp.tb_codigo = rs_impuesto
			AND imp.tb_clave = 'CSIMP'
			AND reg.tb_codigo = rs_regimen
			AND reg.tb_clave = 'CSREG'
			AND lc_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt, 1, false);

$sql =
	"SELECT fi_caracter, fi_firmante
		 FROM cfi_firma
		WHERE fi_id = 444";		// Queda fijo Ana Bordachar..
$stmt = DBExecSql($conn, $sql, array());
$rowFirmante = DBGetQuery($stmt, 1, false);

$pdf = new FPDI();
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/agentes_comerciales/comisiones/templates/afip.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "", 10);

$pdf->Ln(27.4);
$pdf->Cell(138);
$pdf->Cell(56, 0, $row["NUMERO"]);

$pdf->Ln(5.4);
$pdf->Cell(124);
$pdf->Cell(56, 0, $row["FECHA"]);

$pdf->Ln(25.2);
$pdf->Cell(76);
$pdf->Cell(0, 0, "PROVINCIA ART S.A.");

$pdf->Ln(6.2);
$pdf->Cell(32);
$pdf->Cell(0, 0, "30-68825409-0");

$pdf->Ln(6.2);
$pdf->Cell(30);
$pdf->Cell(0, 0, "Carlos Pellegrini 91 1º Piso (1009) - CAPITAL FEDERAL");

$pdf->Ln(27.6);
$pdf->Cell(76);
$pdf->Cell(120, 0, $row["NOMBRE"]);

$pdf->Ln(6);
$pdf->Cell(32);
$pdf->Cell(0, 0, $row["CUIT"]);

dibujarParrafo($row["DOMICILIO"], 6.6);

$pdf->Ln(23.6);
$pdf->Cell(32);
$pdf->Cell(0, 0, $row["IMPUESTO"]);

$pdf->Ln(5.4);
$pdf->Cell(30);
$pdf->Cell(160, 0, $row["REGIMEN"]);

$pdf->Ln(10.6);
$pdf->Cell(84);
$pdf->Cell(0, 0, $row["COMPROBANTE"]);

$pdf->Ln(5.6);
$pdf->Cell(97);
$pdf->Cell(0, 0, ":   ".$row["BASE"]);

$pdf->Ln(5.6);
$pdf->Cell(56);
$pdf->Cell(0, 0, $row["RETENCION"]);

$pdf->Ln(43);
$pdf->Cell(32);
$pdf->Cell(0, 0, $rowFirmante["FI_FIRMANTE"]);

$pdf->Ln(5.4);
$pdf->Cell(24);
$pdf->Cell(0, 0, $rowFirmante["FI_CARACTER"]);

$pdf->Image("http://extranet-desa.artprov.com.ar/modules/usuarios_registrados/agentes_comerciales/comisiones/images/firma.jpg", 32, 180, 27, 25); //336, 313

$pdf->Output();
?>