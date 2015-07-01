<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
<html>
	<body>
<?
$sql =
	"SELECT se_id
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
 ORDER BY se_id";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
?>
	<a href="/contacto/<?= $row["SE_ID"]?>">link</a>
<?
}
?>
	</body>
</html>