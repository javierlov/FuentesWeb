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


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT zg_idprovincia
		 FROM afi.azg_zonasgeograficas
		WHERE zg_id = :id";
$idProvincia = valorSql($sql, "", $params);
require_once("cambia_provincia_establecimiento_combos.php");
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('localidad').parentNode.innerHTML = '<?= $comboLocalidad->draw();?>';
		getElementById('imgLoadingProvincia').style.visibility = 'hidden';
	}
</script>