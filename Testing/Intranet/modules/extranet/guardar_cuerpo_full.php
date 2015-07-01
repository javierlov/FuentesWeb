<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


if ($_REQUEST["idArticulo"] != -1) {
	$params = array(":cuerpofull" => $_POST["cuerpoFullEditable"], ":id" => $_REQUEST["idArticulo"]);
	$sql =
		"UPDATE web.wax_articulosextranetedicion
				SET ax_cuerpofull = :cuerpofull
			WHERE ax_id = :id";
	DBExecSql($conn, $sql, $params);
}
?>
<script type="text/javascript">
	with (window.parent) {
		document.getElementById('cuerpoFull').value = '<?= eregi_replace("[\n|\r|\n\r]", "", $_POST["cuerpoFullEditable"]);?>';
		cerrarVentanaCuerpoFull();
	}
</script>