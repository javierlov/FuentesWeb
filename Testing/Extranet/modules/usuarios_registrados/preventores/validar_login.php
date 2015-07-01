<?
// *************************************
// *******  Inicio validaciones  *******
// *************************************
$error = false;

$params = array(":usuario" => $_POST["sr"], ":clave" => $_POST["ps"]);
$sql = "SELECT art.hys.is_usuarioweb(:usuario, :clave) FROM DUAL";
if (ValorSql($sql, -1, $params) != 0) {
	$_SESSION["msgError"] = "El usuario o la contraseña no son correctos.";
	$error = true;
}
// **********************************
// *******  Fin validaciones  *******
// **********************************


if (!$error) {
	$params = array(":usuario" => $_POST["sr"]);
	$sql = 
		"SELECT *
			 FROM pit_firmantes
			WHERE it_fechabaja IS NULL
				AND it_usuario = :usuario";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$_SESSION["idUsuario"] = $row["IT_ID"];
	$_SESSION["isPreventor"] = true;
	$_SESSION["login"] = true;
	$_SESSION["msgError"] = "";
	$_SESSION["preventores"]["empresas"] = array();
	$_SESSION["usuario"] = $row["IT_USUARIO"];

	echo '<meta http-equiv="refresh" content="0; url=/index.php?pageid=89">';
	exit;
}
?>