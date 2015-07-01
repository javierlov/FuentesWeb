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
	foreach ($_POST as $key => $value) {
		if (substr($key, 0, 3) == "oi_") {
			// Guardo el "De Acuerdo"..
			$deAcuerdo = ((isset($_POST["deAcuerdo_".$value]))?$_POST["deAcuerdo_".$value]:"N");

			$params = array(":id" => $value,
											":deacuerdo" => $deAcuerdo,
											":usumodif" => getWindowsLoginName(true));
			$sql =
				"UPDATE rrhh.roi_objetivos_individuales
						SET oi_deacuerdo = :deacuerdo,
								oi_fechamodif = SYSDATE,
								oi_usumodif = :usumodif
					WHERE oi_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			// Guardo el "Notificado"..
			$notificado = ((isset($_POST["notificado_".$value]))?"S":"N");

			$params = array(":id" => $value,
											":notificado" => $notificado);
			$sql =
				"UPDATE rrhh.roi_objetivos_individuales
						SET oi_notificado = :notificado
					WHERE (oi_notificado = 'N' OR oi_notificado IS NULL)
						AND oi_id = :id";
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
	showMsgOk('/programa-incentivos', window.parent);
</script>