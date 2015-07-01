<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
<script type="text/javascript">
<?
$params = array(":calle" => "%".$_REQUEST["c"]."%", ":localidad" => $_REQUEST["l"]);
$sql =
	"SELECT ub_cpostal, ub_provincia
		 FROM cub_ubicacion
		WHERE ub_localidad = :localidad
			AND ub_calle LIKE :calle";
$stmt = DBExecSql($conn, $sql, $params);
if (DBGetRecordCount($stmt) == 0) {
	$params = array(":localidad" => $_REQUEST["l"]);
	$sql =
		"SELECT ub_cpostal, ub_provincia
			 FROM cub_ubicacion
			WHERE ub_localidad = :localidad";
	$stmt = DBExecSql($conn, $sql, $params);
}
$row = DBGetQuery($stmt);

if ($_REQUEST["cp"] == "") {
?>
	window.parent.document.getElementById('codigoPostal').value = '<?= $row["UB_CPOSTAL"]?>';
<?
}

if ($_REQUEST["p"] == -1) {
?>
	window.parent.document.getElementById('provincia').value = '<?= $row["UB_PROVINCIA"]?>';
<?
}
?>
</script>