<?
session_start();
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


switch ($_REQUEST["obj"]) {
	case "ejecutiva":
		$_SESSION["permisosControlGestion"][$_REQUEST["usr"]][0] = $_REQUEST["chk"];
		break;
	case "gestion":
		$_SESSION["permisosControlGestion"][$_REQUEST["usr"]][2] = $_REQUEST["chk"];
		break;
	case "operativa":
		$_SESSION["permisosControlGestion"][$_REQUEST["usr"]][3] = $_REQUEST["chk"];
		break;
	case "informesGestion":
		$_SESSION["permisosControlGestion"][$_REQUEST["usr"]][4] = $_REQUEST["chk"];
		break;
}
?>