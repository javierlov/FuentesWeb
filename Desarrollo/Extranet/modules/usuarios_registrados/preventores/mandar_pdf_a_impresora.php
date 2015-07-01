<?
session_start();
//header("Content-Transfer-Encoding", "binary");
//header('Cache-Control: maxage=3600'); //Adjust maxage appropriately
//header('Pragma: public');
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


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


validarSesion(isset($_SESSION["isPreventor"]));

$params = array(":id" => $_REQUEST["id"]);
if ((isset($_REQUEST["b"])) and ($_REQUEST["b"] == "s"))		// Es un formulario en blanco..
	$sql = "SELECT tf_patharchivoenblanco FROM hys.htf_tipoformulario WHERE tf_id = :id";
else
	$sql = "SELECT fg_archivo FROM hys.hfg_formulariogenerado WHERE fg_id = :id";

$pdf = new PDF_AutoPrint();

// Agrego el archivo pdf..
$pdf->setSourceFile(DATA_PREVENCION.ValorSql($sql, "", $params));
$pdf->AddPage();
$tplIdx = $pdf->importPage(1);
$pdf->useTemplate($tplIdx);

$pdf->AutoPrint(false);
$pdf->Output();
?>