<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ax_cuerpofull
		 FROM web.wax_articulosextranetedicion
		WHERE ax_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt)
?>
<script type="text/javascript">
	window.parent.CKEDITOR.instances.cuerpoFullEditable.setData('<?= eregi_replace("[\n|\r|\n\r]", "", $row["AX_CUERPOFULL"]->load());?>');
	with (window.parent.document) {
		getElementById('idArticulo').value = <?= $_REQUEST["id"]?>;
		getElementById('divFondo').style.display = 'block';
		getElementById('btnGuardarCuerpoFull').style.display = 'inline';
		getElementById('imgGuardandoCuerpoFull').style.display = 'none';
		getElementById('divCuerpoFull').style.display = 'block';
	}
</script>