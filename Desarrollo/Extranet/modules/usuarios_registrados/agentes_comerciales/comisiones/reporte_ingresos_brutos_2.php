<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

SetDateFormatOracle("DD/MM/YYYY");

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ib_inscripcion agenteretencion,
					(art.comision.get_tasaib(il_provincia, '01', en_id, NULL, il_fecha) * 100) || ' %' alicuota,
					to_char(lc_fechaliq, 'YYYY') anoretencion,
					DECODE(en_convenio, 'S', 'Convenio Multilateral', 'Local') convenio,
					en_cpostal cpostal,
					art.utiles.armar_cuit(en_cuit) cuit,
					to_char(lc_fechaliq, 'DD') diaretencion,
					art.utiles.armar_domicilio(en_calle, en_numero, en_piso, en_departamento, NULL) domicilio,
					TRUNC(lc_fechaliq) fechaactual,
					TRUNC(lc_fechaliq) fecharetencion,
					il_fecha,
					lc_facturanro,
					lc_fechaaprobado,
					lc_id,
					to_char(lc_fechaliq, 'MM') mesretencion,
					TRIM(TO_CHAR(il_neto, '$9,999,999,990.00')) montoimponible,
					en_numingbrutos nib,
					en_nombre nombreentidad,
					NVL(il_comprobante, 0) nrocomprobante,
					comision.get_nrofactura(lc_id) nrofactura,
					pv_descripcion provincia,
					pv_codigodgi,
					TRIM(TO_CHAR(il_retencion, '$9,999,999,990.00')) retencion,
					NULL ve_nombre,
					NULL ve_vendedor
		 FROM xen_entidad, cpv_provincias, xil_ibliquidacion, oib_ingresosbrutos, xlc_liqcomision
		WHERE lc_identidad = en_id
			AND ib_provincia = pv_codigo
			AND ib_concepto = '01'
			AND il_idliquidacion = lc_id
			AND pv_codigo = il_provincia
			AND il_retencion <> 0
			AND lc_estado <> 'C'
			AND pv_codigodgi = '01'
			AND lc_id = :id
UNION ALL
	 SELECT ib_inscripcion agenteretencion,
					(art.comision.get_tasaib(il_provincia, '01', NULL, ev_id, il_fecha) * 100) || ' %' alicuota,
					to_char(lc_fechaliq, 'YYYY') anoretencion,
					DECODE(ve_convenio, 'S', 'Convenio Multilateral', 'Local') convenio,
					ve_cpostal cpostal,
					art.utiles.armar_cuit(ve_cuit) cuit,
					to_char(lc_fechaliq, 'DD') diaretencion,
					art.utiles.armar_domicilio(ve_calle, ve_numero, ve_piso, ve_departamento, NULL) domicilio,
					TRUNC(lc_fechaliq) fechaactual,
					TRUNC(lc_fechaliq) fecharetencion,
					il_fecha,
					lc_facturanro,
					lc_fechaaprobado,
					lc_id,
					to_char(lc_fechaliq, 'MM') mesretencion,
					TRIM(TO_CHAR(il_neto, '$9,999,999,990.00')) montoimponible,
					ve_numingbrutos nib,
					ve_nombre nombreentidad,
					NVL(il_comprobante, 0) nrocomprobante,
					comision.get_nrofactura(lc_id) nrofactura,
					pv_descripcion provincia,
					pv_codigodgi,
					TRIM(TO_CHAR(il_retencion, '$9,999,999,990.00')) retencion,
					ve_nombre,
					ve_vendedor
		 FROM xve_vendedor, xen_entidad, xil_ibliquidacion, cpv_provincias, oib_ingresosbrutos, xev_entidadvendedor, xlc_liqcomision
		WHERE lc_identidadvendedor = ev_id
			AND ib_provincia = pv_codigo
			AND ib_concepto = '01'
			AND ev_idvendedor = ve_id
			AND ev_identidad = en_id
			AND il_idliquidacion = lc_id
			AND pv_codigo = il_provincia
			AND il_retencion <> 0
			AND lc_estado <> 'C'
			AND pv_codigodgi = '01'
			AND lc_id = :id
 ORDER BY 1";
$stmt = DBExecSql($conn, $sql, $params);
validarSesion((DBGetRecordCount($stmt) > 0));
$row = DBGetQuery($stmt, 1, false);

$sql =
	"SELECT fi_caracter, fi_firmante
		 FROM cfi_firma
		WHERE fi_id = 444";		// Queda fijo Ana Bordachar..
$stmt = DBExecSql($conn, $sql, array());
$rowFirmante = DBGetQuery($stmt, 1, false);

$pdf = new FPDI();
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/agentes_comerciales/comisiones/templates/ingresos_brutos_2.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "", 14);

$numeroComprobante = str_pad($row["NROCOMPROBANTE"], 12, "0", STR_PAD_LEFT);
$pdf->Ln(23.2);
$pdf->Cell(130);
$pdf->Cell(0, 0, substr($numeroComprobante, 0, 4)."-".substr($numeroComprobante, 4));

$pdf->SetFont("Arial", "B", 10);
$pdf->Ln(26);
$pdf->Cell(64);
$pdf->Cell(40, 0, $row["AGENTERETENCION"]);

$pdf->SetFont("Arial", "", 10);
$pdf->Cell(4);
$pdf->Cell(0, 0, "Provincia Aseguradora de Riesgos de Trabajo S.A.");

$pdf->SetFont("Arial", "B", 10);
$pdf->Ln(4.8);
$pdf->Cell(64);
$pdf->Cell(40, 0, "30-68825409-0");

$pdf->SetFont("Arial", "", 10);
$pdf->Cell(4);
$pdf->Cell(0, 0, "Carlos Pellegrini 91 1º Piso - Capital Federal");

$pdf->Ln(4.8);
$pdf->Cell(108);
$pdf->Cell(0, 0, "C.P. 1009");

$pdf->Ln(4.8);
$pdf->Cell(108);
$pdf->Cell(0, 0, "Tel: 4819-2800 Fax: 0800-999-1829");

$pdf->Ln(30);
$pdf->Cell(-0.4);
$pdf->Cell(38, 0, $row["CUIT"]);

$pdf->Cell(2);
$pdf->Cell(31.6, 0, $row["NIB"]);

$pdf->Cell(2);
$pdf->Cell(32, 0, $row["PROVINCIA"]);

$pdf->Cell(2);
$pdf->Cell(86, 0, $row["NOMBREENTIDAD"]);

$pdf->Ln(7.6);
$pdf->Cell(22);
$pdf->Cell(86, 0, $row["DOMICILIO"]);

$pdf->Cell(22);
$pdf->Cell(0, 0, $row["CPOSTAL"]);

$pdf->Ln(33);
$pdf->Cell(8);
$pdf->Cell(24, 0, $row["FECHARETENCION"]);

$pdf->Cell(18);
$pdf->Cell(28, 0, $row["MONTOIMPONIBLE"]);

$pdf->Cell(15);
$pdf->Cell(24, 0, $row["ALICUOTA"]);

$pdf->Cell(2);
$pdf->Cell(24, 0, $row["RETENCION"]);

$pdf->Cell(16);
$pdf->Cell(36, 0, $row["NROFACTURA"]);

$pdf->Ln(107);
$pdf->Cell(2);
$pdf->Cell(0, 0, "Buenos Aires, ".$row["DIARETENCION"]." de ".GetMonthName($row["MESRETENCION"])." de ".$row["ANORETENCION"]);

$pdf->Ln(6);
$pdf->Cell(148);
$pdf->Cell(0, 0, $rowFirmante["FI_FIRMANTE"]);

$pdf->Ln(5);
$pdf->Cell(146.4);
$pdf->Cell(0, 0, $rowFirmante["FI_CARACTER"]);

$pdf->Image("http://extranet-desa.artprov.com.ar/modules/usuarios_registrados/agentes_comerciales/comisiones/images/firma.jpg", 156, 222, 34, 31); //336, 313

$pdf->Output();
?>