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

$params = array(":idformulario" => $idFormulario);
$sql =
	"SELECT TO_CHAR(sa_fecharecepcion, 'yyyy') ano, tb_descripcion cargo, TO_CHAR(sa_fecharecepcion, 'dd') dia,
				  sa_documento_titular documento, sa_lugarsuscripcion lugarsuscripcion, TO_NUMBER(TO_CHAR(sa_fecharecepcion, 'mm')) mes,
				  sa_titular nombre, fo_formulario numero, sa_nombre razonsocial
		 FROM asa_solicitudafiliacion, afo_formulario, ctb_tablas
		WHERE sa_idformulario = fo_id
			AND tb_clave(+) = 'CARGO'
			AND sa_cargo_titular = tb_codigo(+)
			AND sa_idformulario = :idformulario";
$stmt = DBExecSql($conn, $sql, $params);
$row2 = DBGetQuery($stmt, 1, false);

if ($autoPrint)
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();

$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_afiliacion/templates/peps.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "B", 9);

$pdf->Ln(15);
$pdf->Cell(24);
$pdf->Cell(20, 0, $row2["NUMERO"]);

$pdf->Ln(8);
$pdf->Cell(20);
$pdf->Cell(64, 0, $row2["NOMBRE"]);

$pdf->Ln(17.6);
$pdf->Cell(20);
$pdf->Cell(62, 0, "D.N.I.  ".$row2["DOCUMENTO"]);

$pdf->Ln(4);
$pdf->Cell(15);
$pdf->Cell(68, 0, $row2["CARGO"]);

$pdf->Ln(4);
$pdf->Cell(12);
$pdf->Cell(72, 0, $row2["LUGARSUSCRIPCION"].", ".$row2["DIA"]." de ".GetMonthName($row2["MES"])." de ".$row2["ANO"]);

if ($autoPrint)
	$pdf->AutoPrint(false);
$pdf->Output();
?>