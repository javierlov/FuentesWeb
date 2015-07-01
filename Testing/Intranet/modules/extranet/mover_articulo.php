<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


try {
	$params = array(":posicion1" => $_REQUEST["pos1"], ":usuario" => GetWindowsLoginName(true));
	$sql =
		"UPDATE web.wax_articulosextranetedicion
				SET ax_posicion = -7
			WHERE ax_baja = 'F'
				AND ax_posicion = :posicion1
				AND ax_usuario = :usuario";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":posicion1" => $_REQUEST["pos1"], ":posicion2" => $_REQUEST["pos2"], ":usuario" => GetWindowsLoginName(true));
	$sql =
		"UPDATE web.wax_articulosextranetedicion
				SET ax_posicion = :posicion1
			WHERE ax_baja = 'F'
				AND ax_posicion = :posicion2
				AND ax_usuario = :usuario";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":posicion2" => $_REQUEST["pos2"], ":usuario" => GetWindowsLoginName(true));
	$sql =
		"UPDATE web.wax_articulosextranetedicion
				SET ax_posicion = :posicion2
			WHERE ax_baja = 'F'
				AND ax_posicion = -7
				AND ax_usuario = :usuario";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	window.parent.location.reload(true);
</script>