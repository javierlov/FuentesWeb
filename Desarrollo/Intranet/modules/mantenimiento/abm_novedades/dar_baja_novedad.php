<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":id" => $_REQUEST["id"], ":usubaja" => GetWindowsLoginName(true));
$sql =
	"UPDATE rrhh.rhn_novedades
			SET hn_fechabaja = SYSDATE,
					hn_usubaja = :usubaja
		WHERE hn_id = :id";
DBExecSql($conn, $sql, $params);
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/novedades-abm-busqueda/0', window.parent);
</script>