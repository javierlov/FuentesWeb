<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
SetDateFormatOracle("DD/MM/YYYY");

$params = array(":idestablecimiento" => $_REQUEST["idEstablecimiento"], ":iditem" => $_REQUEST["id"]);
$sql = "SELECT art.hys.get_fregularizaciondefault(:idestablecimiento, :iditem) FROM DUAL";
?>
<script type="text/javascript">
	parent.document.getElementById('fecha_<?= $_REQUEST["id"]?>').value = '<?= ValorSql($sql, "", $params)?>';
</script>