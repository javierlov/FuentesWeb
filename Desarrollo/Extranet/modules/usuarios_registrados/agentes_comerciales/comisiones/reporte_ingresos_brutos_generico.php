<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);

$params = array(":id" => $_REQUEST["id"], ":idil" => $_REQUEST["idil"], ":provincia" => $_REQUEST["p"]);
$sql =
	"SELECT ib_inscripcion agenteretencion,
					(art.comision.get_tasaib(il_provincia, il_concepto, en_id, NULL, il_fecha) * 100) || ' %' alicuota,
					to_char(il_fecha, 'YY') anofecha,
					to_char(il_fecha, 'YYYY') anofechafull,
					to_char(lc_fechaliq, 'YYYY') anoretencion,
					tb_descripcion concepto,
					DECODE(en_convenio, 'S', 'Convenio Multilateral', 'Local') convenio,
					en_cpostal cpostal,
					art.utiles.armar_cuit(en_cuit) cuit,
					to_char(il_fecha, 'DD') diafecha,
					to_char(lc_fechaliq, 'DD') diaretencion,
					art.utiles.armar_domicilio(en_calle, en_numero, en_piso, en_departamento, NULL) domicilio,
					TRUNC(lc_fechaliq) fechaactual,
					TRUNC(lc_fechaliq) fecharetencion,
					il_fecha,
					lc_facturanro,
					lc_fechaaprobado,
					lc_id,
					to_char(il_fecha, 'MM') mesfecha,
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
		 FROM xen_entidad, cpv_provincias, xil_ibliquidacion, oib_ingresosbrutos, xlc_liqcomision, ctb_tablas
		WHERE lc_identidad = en_id
			AND ib_provincia = pv_codigo
			AND il_concepto = tb_codigo
			AND tb_clave = 'RETIB'
			AND il_idliquidacion = lc_id
			AND pv_codigo = il_provincia
			AND il_retencion <> 0
			AND lc_estado <> 'C'
			AND ib_concepto = il_concepto
			AND pv_codigo = :provincia
			AND lc_id = :id
			AND il_id = :idil
UNION ALL
	 SELECT ib_inscripcion agenteretencion,
					(art.comision.get_tasaib(il_provincia, il_concepto, NULL, ev_id, il_fecha) * 100) || ' %' alicuota,
					to_char(il_fecha, 'YY') anofecha,
					to_char(il_fecha, 'YYYY') anofechafull,
					to_char(lc_fechaliq, 'YYYY') anoretencion,
					tb_descripcion concepto,
					DECODE(ve_convenio, 'S', 'Convenio Multilateral', 'Local') convenio,
					ve_cpostal cpostal,
					art.utiles.armar_cuit(ve_cuit) cuit,
					to_char(il_fecha, 'DD') diafecha,
					to_char(lc_fechaliq, 'DD') diaretencion,
					art.utiles.armar_domicilio(ve_calle, ve_numero, ve_piso, ve_departamento, NULL) domicilio,
					TRUNC(lc_fechaliq) fechaactual,
					TRUNC(lc_fechaliq) fecharetencion,
					il_fecha,
					lc_facturanro,
					lc_fechaaprobado,
					lc_id,
					to_char(il_fecha, 'MM') mesfecha,
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
		 FROM xve_vendedor, xen_entidad, xil_ibliquidacion, cpv_provincias, oib_ingresosbrutos, xev_entidadvendedor, xlc_liqcomision, ctb_tablas
		WHERE lc_identidadvendedor = ev_id
			AND ib_provincia = pv_codigo
			AND il_concepto = tb_codigo
			AND tb_clave = 'RETIB'
			AND ev_idvendedor = ve_id
			AND ev_identidad = en_id
			AND il_idliquidacion = lc_id
			AND pv_codigo = il_provincia
			AND il_retencion <> 0
			AND lc_estado <> 'C'
			AND ib_concepto = il_concepto
			AND pv_codigo = :provincia
			AND lc_id = :id
			AND il_id = :idil
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
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/agentes_comerciales/comisiones/templates/ingresos_brutos_generico.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "", 13);

$numeroComprobante = str_pad($row["NROCOMPROBANTE"], 12, "0", STR_PAD_LEFT);
$pdf->Ln(4.6);
$pdf->Cell(148);
$pdf->Cell(0, 0, substr($numeroComprobante, 0, 4)."-".substr($numeroComprobante, 4));

$pdf->SetFont("Arial", "B", 12);
$pdf->Ln(14);
$pdf->Cell(2);
$pdf->Cell(0, 0, "Provincia Aseguradora de Riesgos de Trabajo S.A.");

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(0, 0, "Carlos Pellegrini 91 1º Piso - Capital Federal");

$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(0, 0, "C.P. 1009");

$pdf->Ln(6);
$pdf->Cell(2);
$pdf->Cell(0, 0, "Tel: 4819-2800 Fax: 0800-999-1829");

$pdf->SetFont("Arial", "B", 10);
$pdf->Ln(28.6);
$pdf->Cell(80);
$pdf->Cell(0, 0, "30-68825409-0");

$pdf->Ln(5.4);
$pdf->Cell(80);
$pdf->Cell(0, 0, "901-183258-4");

$pdf->Ln(6);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["AGENTERETENCION"]);

$pdf->Ln(6.4);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["PROVINCIA"]);

$pdf->Ln(6.4);
$pdf->SetFont("Arial", "", 10);
$pdf->Cell(60.6);
$pdf->Cell(16, 0, "Concepto:");
$pdf->SetFont("Arial", "B", 10);
$pdf->Cell(3);
$pdf->Cell(0, 0, $row["CONCEPTO"]);

$pdf->Ln(27.4);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["FECHARETENCION"]);

$pdf->Ln(5.6);
$pdf->Cell(80);
$pdf->Cell(112, 0, $row["NOMBREENTIDAD"]);

$pdf->Ln(5.4);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["CUIT"]);

$pdf->Ln(5.4);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["NIB"]);

$pdf->Ln(8);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["NROFACTURA"]);

$pdf->Ln(5.8);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["MONTOIMPONIBLE"]);

$pdf->Ln(5.8);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["ALICUOTA"]);

$pdf->Ln(5.2);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["RETENCION"]);

$pdf->Ln(5.6);
$pdf->Cell(80);
$pdf->Cell(0, 0, $row["RETENCION"]);

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(40);
$pdf->Cell(150);
$pdf->Cell(0, 0, $rowFirmante["FI_FIRMANTE"]);

$pdf->Ln(5);
$pdf->Cell(148.4);
$pdf->Cell(0, 0, $rowFirmante["FI_CARACTER"]);

$pdf->Image("http://extranet-desa.artprov.com.ar/modules/usuarios_registrados/agentes_comerciales/comisiones/images/firma.jpg", 156, 176, 34, 31); //336, 313

$pdf->Output();
?>