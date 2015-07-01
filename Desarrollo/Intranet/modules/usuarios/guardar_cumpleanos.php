<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


try {
	if (getUserID() != $_POST["id"])
		throw new Exception("Usuario inválido.");

	$params = array(":id" => $_POST["id"]);
	$sql =
		"SELECT TO_CHAR(se_fechacumple, 'yyyy') anocumple, TO_CHAR(se_fechacumple, 'dd/mm') fechacumple
			 FROM use_usuarios
			WHERE se_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	if (!isFechaValida($_POST["cumpleaños"]."/".$row["ANOCUMPLE"]))
		throw new Exception("La fecha ingresada es inválida.");

	$params = array(":fechacumple" => $_POST["cumpleaños"]."/".$row["ANOCUMPLE"], ":id" => $_POST["id"], ":usumodif" => getWindowsLoginName(true));
	$sql =
		"UPDATE use_usuarios
				SET se_fechamodif = SYSDATE,
						se_fechacumple = :fechacumple,
						se_usumodif = :usumodif
			WHERE se_id = :id";
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
		parent.document.getElementById('spanCumpleañosPropio').innerHTML = '<?= $row["FECHACUMPLE"]?>';
	</script>
<?
	exit;
}
?>