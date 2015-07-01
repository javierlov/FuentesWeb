<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":numerodocumento" => $_REQUEST["d"]);
$sql =
	"SELECT NVL(ct_cargo, -1) cargo, ct_contacto contacto, ct_direlectronica direlectronica, NVL(ct_sexo, -1) sexo, NVL(ct_tipodocumento, -1) tipodocumento
		 FROM afi.act_contacto
		WHERE ct_numerodocumento = :numerodocumento
 ORDER BY ct_fechabaja DESC";
$stmt = DBExecSql($conn, $sql, $params);
if (DBGetRecordCount($stmt) > 0) {
	$row = DBGetQuery($stmt);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('cargoHYS').value = '<?= $row["CARGO"]?>';
		getElementById('emailHYS').value = '<?= $row["DIRELECTRONICA"]?>';
		getElementById('nombreApellidoHYS').value = '<?= $row["CONTACTO"]?>';
		getElementById('sexoHYS').value = '<?= $row["SEXO"]?>';
		getElementById('tipoDocumentoHYS').value = '<?= $row["TIPODOCUMENTO"]?>';
	}
</script>
<?
}
?>