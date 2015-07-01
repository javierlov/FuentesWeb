<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion($_SESSION["comisiones"]);


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT en_cuit cuit,
					lc_fechaliq fecharetencion,
					lc_id,
					TRIM(TO_CHAR(lc_comision, '$9,999,999,990.00')) montoimponible,
					en_nombre nombreentidad,
					en_numosocial nos,
					comision.get_nrofactura(lc_id) nrofactura,
					TRIM(TO_CHAR(lc_obrasocial, '$9,999,999,990.00')) retencion,
					NULL ve_nombre,
					NULL ve_vendedor
		 FROM xen_entidad, xlc_liqcomision
		WHERE lc_identidad = en_id
			AND lc_identidad IS NOT NULL
			AND lc_obrasocial <> 0
			AND lc_estado <> 'C'
			AND lc_id = :id
UNION ALL
	 SELECT ve_cuit cuit,
					lc_fechaliq fecharetencion,
					lc_id,
					TRIM(TO_CHAR(lc_comision, '$9,999,999,990.00')) montoimponible,
					ve_nombre nombreentidad,
					ve_numosocial nos,
					comision.get_nrofactura(lc_id) nrofactura,
					TRIM(TO_CHAR(lc_obrasocial, '$9,999,999,990.00')) retencion,
					ve_nombre,
					ve_vendedor
		 FROM xve_vendedor, xen_entidad, xev_entidadvendedor, xlc_liqcomision
		WHERE lc_identidadvendedor = ev_id
			AND ev_idvendedor = ve_id
			AND ev_identidad = en_id
			AND lc_identidadvendedor IS NOT NULL
			AND lc_obrasocial <> 0
			AND lc_estado <> 'C'
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
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/agentes_comerciales/comisiones/templates/obra_social.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

$pdf->SetFont("Arial", "", 12);
$pdf->Ln(4);
$pdf->Cell(8);
$pdf->Cell(0, 0, "Provincia Aseguradora de Riesgos de Trabajo S.A.");

$pdf->SetFont("Arial", "", 10);
$pdf->Ln(6);
$pdf->Cell(8);
$pdf->Cell(0, 0, "Carlos Pellegrini 91 1º Piso - Capital Federal");

$pdf->Ln(6);
$pdf->Cell(8);
$pdf->Cell(0, 0, "C.P. 1009");

$pdf->Ln(6);
$pdf->Cell(8);
$pdf->Cell(0, 0, "Tel: 4819-2800 Fax: 0800-999-1829");

$pdf->SetFont("Arial", "B", 10);
$pdf->Ln(16.4);
$pdf->Cell(64);
$pdf->Cell(0, 0, "30-68825409-0");

$pdf->Ln(6.4);
$pdf->Cell(70);
$pdf->Cell(0, 0, "1910/1");

$pdf->Ln(43);
$pdf->Cell(64);
$pdf->Cell(0, 0, $row["FECHARETENCION"]);

$pdf->Ln(6.4);
$pdf->Cell(64);
$pdf->Cell(128, 0, $row["NOMBREENTIDAD"]);

$pdf->Ln(7.6);
$pdf->Cell(64);
$pdf->Cell(0, 0, $row["CUIT"]);

$pdf->Ln(7.6);
$pdf->Cell(64);
$pdf->Cell(0, 0, $row["NROFACTURA"]);

$pdf->Ln(5.8);
$pdf->Cell(64);
$pdf->Cell(0, 0, $row["MONTOIMPONIBLE"]);

$pdf->Ln(6.4);
$pdf->Cell(64);
$pdf->Cell(0, 0, $row["RETENCION"]);

$pdf->Ln(6.4);
$pdf->Cell(64);
$pdf->Cell(0, 0, $row["RETENCION"]);

$pdf->Ln(7);
$pdf->Cell(64);
$pdf->Cell(0, 0, $row["NOS"]);

$pdf->Ln(106);
$pdf->Cell(148);
$pdf->Cell(0, 0, $rowFirmante["FI_FIRMANTE"]);

$pdf->Ln(5);
$pdf->Cell(146.4);
$pdf->Cell(0, 0, $rowFirmante["FI_CARACTER"]);

$pdf->Image("http://extranet-desa.artprov.com.ar/modules/usuarios_registrados/agentes_comerciales/comisiones/images/firma.jpg", 156, 216, 34, 31); //336, 313

$pdf->Output();
?>