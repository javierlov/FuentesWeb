<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 95));


$ids = explode("_", $_REQUEST["id"]);		// [0] = ao_id, [1] = es_id..

$params = array(":idavisoobra" => $ids[0], ":idestablecimiento" => $ids[1]);
$sql = "SELECT art.hys_avisoobraweb.get_pathavisoobra(:idavisoobra, :idestablecimiento) FROM DUAL";

$file = ValorSql($sql, "", $params);
$file = str_replace("\\", "/", $file);
?>
<script type="text/javascript">
	window.location.href = '<?= getFile($file, "a")?>';
</script>