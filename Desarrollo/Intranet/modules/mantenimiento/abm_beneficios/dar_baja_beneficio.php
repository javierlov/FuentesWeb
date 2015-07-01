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
	if (!hasPermiso(85))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	// Doy de baja el beneficio..
	$params = array(":id" => $_REQUEST["id"], ":usubaja" => getWindowsLoginName(true));
	$sql =
		"UPDATE rrhh.rbn_beneficios
				SET bn_fechabaja = SYSDATE,
						bn_usubaja = :usubaja
			WHERE bn_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);


	// Doy de baja el item del menú que apunta al beneficio..
	$params = array(":id" => $_REQUEST["id"], ":usubaja" => getWindowsLoginName(true));
	$sql =
		"UPDATE web.wmi_menuintranet
				SET mi_fechabaja = SYSDATE,
						mi_usubaja = :usubaja
			WHERE mi_id = (SELECT bn_idmenu
											 FROM rrhh.rbn_beneficios
											WHERE bn_id = :id)";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type='text/javascript'>
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/beneficios-abm-busqueda/0', window.parent);
</script>