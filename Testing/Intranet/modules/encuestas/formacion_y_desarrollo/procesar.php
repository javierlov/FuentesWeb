<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


$otros = "";
if (isset($_REQUEST["Otros"]))
	$otros = $_REQUEST["Otros"];

$user = GetWindowsLoginName();
$params = array(":usuario" => $user);
$sql =
	"SELECT se_id
		 FROM rrhh.erg_encuestareuniongte, use_usuarios
		WHERE rg_idusuario = se_id
			AND UPPER(se_usuario) = UPPER(:usuario)";
$userId = ValorSql($sql, -1, $params);

if ($userId == -1) {		// Alta..
	$params = array(":usuario" => $user);
	$sql =
		"SELECT se_id
			 FROM use_usuarios
			WHERE UPPER(se_usuario) = UPPER(:usuario)";
	$id = ValorSql($sql, "", $params);

	$params = array(":idusuario" => $id,
									":idopcion1" => $_REQUEST["Tema1"],
									":idopcion2" => $_REQUEST["Tema2"],
									":idopcion3" => $_REQUEST["Tema3"],
									":otros" => $otros);
	$sql =
		"INSERT INTO rrhh.erg_encuestareuniongte (rg_idusuario, rg_idopcion1, rg_idopcion2, rg_idopcion3, rg_otros, rg_fechaalta)
																			VALUES (:idusuario, :idopcion1, :idopcion2, :idopcion3, :otros, SYSDATE)";
	DBExecSql($conn, $sql, $params);
}
else {		// Modificación..
	$params = array(":idopcion1" => $_REQUEST["Tema1"],
									":idopcion2" => $_REQUEST["Tema2"],
									":idopcion3" => $_REQUEST["Tema3"],
									":otros" => $otros,
									":idusuario" => $userId);
	$sql =
		"UPDATE rrhh.erg_encuestareuniongte
				SET rg_idopcion1 = :idopcion1,
						rg_idopcion2 = :idopcion2,
						rg_idopcion3 = :idopcion3,
						rg_otros = :otros
			WHERE rg_idusuario = :idusuario";
	DBExecSql($conn, $sql, $params);
}
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		<title>Encuesta</title>
		<script>
<?
if ($dbError["offset"]) {
?>
	alert('<?= $dbError["message"]?>');
<?
	exit;
}
else {
?>
	setTimeout("window.close();", 3000);
<?
}
?>
		</script>
	</head>
	<body bgcolor="#C0C0C0">
		<table border="0" width="100%" height="100%">
			<tr>
				<td style="padding-left: 4px; padding-right: 4px"><p align="center"><b><font face="Verdana" color="#FFFFFF" size="3">Muchas gracias por participar!</font></b></td>
			</tr>
		</table>
	</body>
</html>