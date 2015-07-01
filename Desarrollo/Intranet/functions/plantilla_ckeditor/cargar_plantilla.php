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


try {
	if ($_REQUEST["id"] == -1)
		throw new Exception("Debe seleccionar una plantilla.");
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
	</script>
<?
	exit;
}


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT pn_contenido
		 FROM web.wpn_plantillasintranet
		WHERE pn_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<script src="/js/ckeditor/ckeditor.js" type="text/javascript"></script>
<script type="text/javascript">
	function ocultar() {
		window.parent.document.getElementById('imgPlantillaOk').style.display = 'none';
	}

	with (window.parent.document) {
		getElementById('cuerpoPlantilla').value = '<?= preg_replace("/[\n|\r|\n\r]/i", "", $row["PN_CONTENIDO"]->load())?>';
		window.parent.CKEDITOR.instances.html.setData(getElementById('cuerpoPlantilla').value);
		getElementById('imgPlantillaOk').style.display = 'inline';
	}
	setTimeout('ocultar()', 500);
</script>