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


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));

$ind = array_search($_REQUEST["id"], $_SESSION["certificadoCobertura"]["trabajadores"]);
if (($ind) or ($ind === 0))
	unset($_SESSION["certificadoCobertura"]["trabajadores"][$ind]);
else
	$_SESSION["certificadoCobertura"]["trabajadores"][] = $_REQUEST["id"];
?>
<script type="text/javascript">
	window.parent.document.getElementById('cantidadTrabajadoresSeleccionados').innerHTML = '<?= count($_SESSION["certificadoCobertura"]["trabajadores"])?>';
</script>