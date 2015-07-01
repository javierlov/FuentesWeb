<?
$params = array(":id" => $_SESSION["pcpId"]);
$sql =
	"SELECT ART.UTILES.ARMAR_CUIT(vp_cuit) cuit, SUBSTR(vp_cuit, 3, 8) dni, vp_contrato, vp_nombreapellido
		 FROM afi.avp_valida_pcp
		WHERE vp_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$pdf = new FPDI();
$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/pcp/templates/peps.pdf");

$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);
$pdf->SetFont("Arial", "B", 9);

$pdf->SetFillColor(255, 255, 255);
$pdf->Rect(4, 24, 29.4, 3, "F");

$pdf->SetFont("Arial", "", 9);
$pdf->Ln(15);
$pdf->Cell(4);
$pdf->Cell(20, 0, "N° Contrato:");

$pdf->SetFont("Arial", "B", 9);
$pdf->Cell(20, 0, $row["VP_CONTRATO"]);

$pdf->Ln(8);
$pdf->Cell(18);
$pdf->Cell(65, 0, $row["VP_NOMBREAPELLIDO"]);

$pdf->Ln(17.4);
$pdf->Cell(19);
$pdf->Cell(0, 0, "DNI: ".$row["DNI"]);

$pdf->Ln(4);
$pdf->Cell(16);
$pdf->Cell(80, 0, "TITULAR");

$pdf->Cell(32);
$pdf->Cell(0, 0, $row["CUIT"]);

$pdf->Ln(4);
$pdf->Cell(66);
$pdf->Cell(0, 0, date("d/m/Y"));

$pdf->Output();
?>