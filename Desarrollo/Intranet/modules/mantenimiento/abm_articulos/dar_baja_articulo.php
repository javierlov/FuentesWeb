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
	if (!hasPermiso(76))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	$params = array(":id" => $_REQUEST["id"], ":usubaja" => getWindowsLoginName(true));
	$sql =
		"UPDATE web.wai_articulosintranet
				SET ai_fechabaja = SYSDATE,
						ai_usubaja = :usubaja
			WHERE ai_id = :id";
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
?>
	<script type="text/javascript">
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/articulos-abm-busqueda/0', window.parent);
</script>