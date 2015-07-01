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


if ((getUserSector() != "RRHH") and (getWindowsLoginName() != "alapaco")) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	exit;
}

$params = array(":id" => $_REQUEST["id"], ":usubaja" => getWindowsLoginName(true));
$sql =
	"UPDATE rrhh.rhd_delivery
			SET hd_fechabaja = SYSDATE,
					hd_usubaja = :usubaja
		WHERE hd_id = :id";
DBExecSql($conn, $sql, $params);
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/delivery', window.parent);
</script>