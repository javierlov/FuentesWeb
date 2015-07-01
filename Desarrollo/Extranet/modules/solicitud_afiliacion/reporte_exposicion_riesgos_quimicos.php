<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idmodulo"]);

$id = substr($_REQUEST["idmodulo"], 1);
$modulo = substr($_REQUEST["idmodulo"], 0, 1);

if (!isset($_REQUEST["ap"]))
	$autoPrint = false;
else
	$autoPrint = ($_REQUEST["ap"] == "t");

$params = array(":id" => $id);
if ($modulo == "C")
	$sql = "SELECT sc_idformulario FROM asc_solicitudcotizacion WHERE sc_id = :id";
else
	$sql = "SELECT sr_idformulario FROM asr_solicitudreafiliacion WHERE sr_id = :id";
$idFormulario = ValorSql($sql, "", $params);

$params = array(":idformulario" => $idFormulario);
$sql =
	"SELECT art.utiles.armar_cuit(sa_cuit) cuit, sa_nombre
		 FROM asa_solicitudafiliacion
		WHERE sa_idformulario = :idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$row2 = DBGetQuery($stmt, 1, false);

$params = array(":idsolicitudestablecimiento" => $_REQUEST["idestablecimiento"]);
$sql =
	"SELECT ciiu.ac_descripcion actividad,
					ciiu.ac_codigo ciiu,
					art.utiles.armar_domicilio(se_calle, se_numero, se_piso, se_departamento) || NVL2(se_cpostal, ' (' || se_cpostal || ')', '') domicilio,
					se_empleados empleados,
					se_fax fax,
					se_localidad localidad,
					se_nroestableci || ' - ' || se_nombre nroestablecimiento,
					pv_descripcion provincia,
					se_telefonos telefonos
		 FROM afi.ase_solicitudestablecimiento, comunes.cac_actividad ciiu, art.cpv_provincias
		WHERE se_idactividad = ciiu.ac_id
			AND se_provincia = pv_codigo
			AND se_id = :idsolicitudestablecimiento";
$stmt = DBExecSql($conn, $sql, $params);
$rowE = DBGetQuery($stmt, 1, false);

if ($autoPrint)
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();

$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/exposicion_riesgos_quimicos.pdf");

$pdf->AddPage("L");
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, null, null, 0, 0, true);
$pdf->SetFont("Arial", "B", 9);

$pdf->Ln(22);
$pdf->Cell(16);
$pdf->Cell(252, 0, $row2["SA_NOMBRE"]);

$pdf->Cell(20);
$pdf->Cell(0, 0, $row2["CUIT"]);

$pdf->Ln(8);
$pdf->Cell(44);
$pdf->Cell(84, 0, $rowE["NROESTABLECIMIENTO"]);

$pdf->Cell(20);
$pdf->Cell(48, 0, $rowE["CIIU"]);

$pdf->Cell(22);
$pdf->Cell(76, 0, $rowE["ACTIVIDAD"]);

$pdf->Cell(68);
$pdf->Cell(48, 0, $rowE["EMPLEADOS"]);

$pdf->Ln(8);
$pdf->Cell(11);
$pdf->Cell(116, 0, $rowE["DOMICILIO"]);

$pdf->Cell(19);
$pdf->Cell(54, 0, $rowE["LOCALIDAD"]);

$pdf->Cell(16.4);
$pdf->Cell(56, 0, $rowE["PROVINCIA"]);

$pdf->Cell(16);
$pdf->Cell(60, 0, $rowE["TELEFONOS"]);

$pdf->Cell(12);
$pdf->Cell(56, 0, $rowE["FAX"]);


if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output();
?>