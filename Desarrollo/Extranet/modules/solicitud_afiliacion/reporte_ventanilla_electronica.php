<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


SetDateFormatOracle("DD/MM/YYYY");

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

$params = array(":sa_idformulario" => $idFormulario);
$sql =
	"SELECT art.utiles.armar_cuit(sa_cuit) cuit, sa_nombre
		 FROM asa_solicitudafiliacion
		WHERE sa_idformulario = :sa_idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$row2 = DBGetQuery($stmt, 1, false);

if ($autoPrint)
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();

$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/ventanilla_electronica.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "B", 9);

$pdf->Ln(34.5);
$pdf->Cell(38);
$pdf->Cell(136, 0, $row2["SA_NOMBRE"]);

$pdf->Ln(5.2);
$pdf->Cell(31);
$pdf->Cell(32, 0, $row2["CUIT"]);


if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output();
?>