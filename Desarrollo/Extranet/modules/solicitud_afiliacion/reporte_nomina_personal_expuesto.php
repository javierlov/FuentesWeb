<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


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
$idFormulario = valorSql($sql, "", $params);

$params = array(":idformulario" => $idFormulario);
$sql =
	"SELECT art.utiles.armar_cuit(sa_cuit) cuit, sa_nombre
		 FROM asa_solicitudafiliacion
		WHERE sa_idformulario = :idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$row2 = DBGetQuery($stmt, 1, false);

if ($autoPrint)
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();

$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/nomina_personal_expuesto.pdf");

$pdf->AddPage("L");
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx, null, null, 0, 0, true);
$pdf->SetFont("Arial", "B", 9);

$pdf->Ln(17);
$pdf->Cell(14);
$pdf->Cell(226, 0, $row2["SA_NOMBRE"]);

$pdf->Cell(16);
$pdf->Cell(0, 0, $row2["CUIT"]);


if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output();
?>