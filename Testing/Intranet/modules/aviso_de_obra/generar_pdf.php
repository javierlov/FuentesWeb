<?
class PDF_AutoPrint extends PDF_Javascript {
	function AutoPrint($dialog = false) {
		// Launch the print dialog or start printing immediately on the standard printer..
		$param = ($dialog?"true":"false");
		$script = "print($param);";
		$this->IncludeJS($script);
	}

	function AutoPrintToPrinter($server, $printer, $dialog = false) {
		// Print on a shared printer (requires at least Acrobat 6)..
		$script = "var pp = getPrintParams();";
		if($dialog)
			$script.= "pp.interactive = pp.constants.interactionLevel.full;";
		else
			$script.= "pp.interactive = pp.constants.interactionLevel.automatic;";
		$script.= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
		$script.= "print(pp);";
		$this->IncludeJS($script);
	}
}


if ($_REQUEST["accion"] == "i")
	$pdf = new PDF_AutoPrint();
else
	$pdf = new FPDI();

$pdf->setSourceFile(DATA_AVISO_OBRA_PATH.$_REQUEST["filename"].".".$_REQUEST["extension"]);
$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

$_REQUEST["x"] = $_REQUEST["x"] * 0.686;
$_REQUEST["y"] = $_REQUEST["y"] * 0.728;

if ($_REQUEST["tipoSello"] == "n") {
	$pdf->SetFont("Arial", "B", 14);
	$pdf->SetTextColor(0, 0, 0);

	$pdf->SetXY($_REQUEST["x"] + 8, $_REQUEST["y"] + 18);
	$pdf->Cell(0, 0, "NO CORRESPONDE PRESENTACIÓN");
	$pdf->SetXY($_REQUEST["x"] + 11, $_REQUEST["y"] + 24);
	$pdf->Cell(0, 0, "POR ACTIVIDAD DESARROLLADA");
}
else {
	$pdf->SetFont("Arial", "B", 16);
	switch ($_REQUEST["tipoSello"]) {
		case "e":
			$pdf->SetDrawColor(0, 0, 196);
			$pdf->SetTextColor(0, 0, 196);
			break;
		case "h":
			$pdf->SetDrawColor(196, 0, 0);
			$pdf->SetTextColor(196, 0, 0);
			break;
		case "i":
			$pdf->SetDrawColor(0, 0, 196);
			$pdf->SetTextColor(0, 0, 196);
			break;
		case "s":
			$pdf->SetDrawColor(0, 0, 196);
			$pdf->SetTextColor(0, 0, 196);
			break;
	}
	
	$pdf->SetLineWidth(0.8);
	$pdf->Rect($_REQUEST["x"], $_REQUEST["y"], (64 * 0.8289), (52 * 0.6745), "D");
	$pdf->Rect($_REQUEST["x"] + 5, $_REQUEST["y"] + 9, (64 * 0.8289) - 10, (52 * 0.6745) - 18, "D");

	$pdf->SetXY($_REQUEST["x"] + 0.4, $_REQUEST["y"] + 5);
	$pdf->Cell(0, 0, "Provincia ART S.A.");

	$pdf->SetXY($_REQUEST["x"] + 8, $_REQUEST["y"] + 18);
	$pdf->Cell(0, 0, $arrFecha[0]." ".strtoupper(substr(GetMonthName($arrFecha[1]), 0, 3))." ".$arrFecha[2]);

	$pdf->SetFont("Arial", "B", 18);
	$pdf->SetXY($_REQUEST["x"] + 3.4, $_REQUEST["y"] + 31);
	$pdf->Cell(46, 0, getLeyendaSello($_REQUEST["tipoSello"]), 0, 1, "C");
}

if ($_REQUEST["accion"] == "g")
	$pdf->Output("Aviso de Obra.pdf", "D");
if ($_REQUEST["accion"] == "i") {
	$pdf->AutoPrint(false);
	$pdf->Output();
}
if ($_REQUEST["accion"] == "v")
	$pdf->Output();
?>