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
	if (!hasPermiso(100))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 14) == "posicionPadre_") {		// Guardo al item padre..
			$params = array(":id" => substr($key, 14),
											":orden" => $value,
											":usumodif" => getWindowsLoginName(true));
			$sql =
				"UPDATE rrhh.rde_descargables
						SET de_fechamodif = SYSDATE,
								de_orden = :orden,
								de_usumodif = :usumodif
					WHERE de_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}

		if (substr($key, 0, 13) == "posicionItem_") {		// Guardo al item hijo..
			$params = array(":id" => substr($key, 13),
											":idpadre" => $_POST["padreItem_".substr($key, 13)],
											":orden" => $value,
											":usumodif" => getWindowsLoginName(true));
			$sql =
				"UPDATE rrhh.rde_descargables
						SET de_fechamodif = SYSDATE,
								de_idpadre = :idpadre,
								de_orden = :orden,
								de_usumodif = :usumodif
					WHERE de_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
	}

	DBCommit($conn);
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
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/mantenimiento-descargables', window.parent);
</script>