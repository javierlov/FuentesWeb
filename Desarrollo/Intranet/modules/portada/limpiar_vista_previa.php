<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


try {
	$sql = "UPDATE web.wai_articulosintranet SET ai_vistaprevia = 'N'";
	DBExecSql($conn, $sql, array(), OCI_DEFAULT);

	$sql = "UPDATE rrhh.rbr_banners SET br_vistaprevia = 'N'";
	DBExecSql($conn, $sql, array(), OCI_DEFAULT);

	$sql = "UPDATE rrhh.rcl_calendario SET cl_vistaprevia = 'N'";
	DBExecSql($conn, $sql, array(), OCI_DEFAULT);

	$sql = "UPDATE rrhh.rnp_novedadespersonales SET np_vistaprevia = 'N'";
	DBExecSql($conn, $sql, array(), OCI_DEFAULT);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	showErrorIntranet("", rawurlencode($e->getMessage()));
	exit;
}
?>
<script type="text/javascript">
	window.location.href = '/';
</script>