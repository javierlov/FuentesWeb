<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


try {
	$sql =
		"SELECT 1
			 FROM rrhh.rno_notificaciones
			WHERE no_idusuario = :idusuario";
	$params = array(":idusuario" => GetUserID());

	if (!ExisteSql($sql, $params)) {		// Alta..
		$params = array(":idusuario" => GetUserID(), ":pccodigoetica" => GetPCName());
		$sql =
			"INSERT INTO rrhh.rno_notificaciones (no_codigoetica, no_idusuario, no_pccodigoetica)
																		VALUES (SYSDATE, :idusuario, :pccodigoetica)";
		DBExecSql($conn, $sql, $params);
	}
	else {		// Modificación..
		$params = array(":idusuario" => GetUserID(), ":pccodigoetica" => GetPCName());
		$sql =
			"UPDATE rrhh.rno_notificaciones
					SET no_codigoetica = SYSDATE,
							no_pccodigoetica = :pccodigoetica
				WHERE no_idusuario = :idusuario";
		DBExecSql($conn, $sql, $params);
	}
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	window.parent.location.reload();
</script>