<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
?>
<script type="text/javascript">
<?
$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ac_ruta
		 FROM web.wac_archivoschat
		WHERE ac_id = :id";
$filename = valorSql($sql, "", $params);

if (file_exists($filename)) {
?>
	window.open('<?= getFile($filename)?>', '', 'width=800,height=600,left=50,top=50,toolbar=yes');
<?
}
else {
	echo "alert('Archivo no disponible.');";
}
?>
</script>