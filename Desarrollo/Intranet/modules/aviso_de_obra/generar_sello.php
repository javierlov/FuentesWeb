<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

ob_start();
setcookie("AvisoDeObra_X", $_REQUEST["x"], time() + (60 * 60 * 24 * 365), "/");
setcookie("AvisoDeObra_Y", $_REQUEST["y"], time() + (60 * 60 * 24 * 365), "/");
ob_end_flush();

session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


function getLeyendaSello($tipoSello) {
	switch ($tipoSello) {
		case "e":
			return "EXTENDIDO";
		case "h":
			return "RECHAZADO";
		case "i":
			return "RECIBIDO";
		case "s":
			return "SUSPENDIDO";
	}
}


$arrFecha = explode("/", $_REQUEST["fecha"]);

if ($_REQUEST["extension"] == "pdf")
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/aviso_de_obra/generar_pdf.php");
else
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/aviso_de_obra/generar_imagen.php");
?>