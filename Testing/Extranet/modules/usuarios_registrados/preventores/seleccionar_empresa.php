<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));
?>
<script type="text/javascript">
<?
$ind = array_search($_REQUEST["id"], $_SESSION["preventores"]["empresas"]);
if (($ind) or ($ind === 0)) {
	unset($_SESSION["preventores"]["empresas"][$ind]);
?>
	parent.document.getElementById('totRegSelec').value--;
<?
}
else {
	$_SESSION["preventores"]["empresas"][] = $_REQUEST["id"];
?>
	parent.document.getElementById('totRegSelec').value++;
<?
}
?>
</script>