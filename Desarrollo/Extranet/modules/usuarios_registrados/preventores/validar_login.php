<?
// *************************************
// *******  Inicio validaciones  *******
// *************************************
$error = false;

$params = array(":usuario" => $_POST["sr"], ":clave" => $_POST["ps"]);
$sql = "SELECT art.hys.is_usuarioweb(:usuario, :clave) FROM DUAL";
if (valorSql($sql, -1, $params) != 0) {
	$_SESSION["msgError"] = "El usuario o la contraseña no son correctos.";
	$error = true;
}

if  (!$error)
{
	$sqlForzarClave =
			"SELECT IT_FORZARCLAVE
				 FROM art.pit_firmantes
				WHERE IT_USUBAJA IS NULL
				  AND IT_USUARIO =  upper(:usuario)";
	$params = array(":usuario" => $_POST["sr"]);
	$cambiarPassword = (valorSql($sqlForzarClave, "", $params) == 'S');
	$_SESSION["cambiarPassword"] = $cambiarPassword;
	if ((!isset($_POST["psn"])) and ($_SESSION["cambiarPassword"])) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña Actual, la Contraseña Nueva y su Confirmación (2.8).";
		$error = true;
	}
	elseif ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña (2.9).";
		$error = true;
	}
	else {
		if (isset($_POST["psn"])) {
			if (strlen($_POST["psn"]) < 8) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "La Contraseña Nueva debe tener al menos 8 caracteres (2.7).";
				$error = true;
			}
			elseif (((isset($_POST["psn"])) and ($_POST["psn"] == "")) or ((isset($_POST["cnf"])) and ($_POST["cnf"] == ""))) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "Por favor ingrese la Contraseña Nueva y su Confirmación (2.12).";
				$error = true;
			}
			elseif ($_POST["psn"] != $_POST["cnf"]) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña Nueva (2.13).";
				$error = true;
			}
			elseif ($_POST["ps"] == $_POST["psn"]) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "La Contraseña Nueva no puede ser la misma que la Contraseña Actual (2.14).";
				$error = true;
			}
		}
	}	
}
// **********************************
// *******  Fin validaciones  *******
// **********************************


if (!$error) {
	
	$params = array(":usuario" => $_POST["sr"]);
	$sql = 
		"SELECT it_id, it_usuario, NULL pc_id
  		   FROM pit_firmantes
		  WHERE it_fechabaja IS NULL AND UPPER (it_usuario) = UPPER (:usuario)
		 UNION ALL
		 SELECT pc_idpreventor, pc_usuario, pc_id
		   FROM hys.hpc_preventorconsultora
		  WHERE pc_fechabaja IS NULL AND UPPER (pc_usuario) = UPPER (:usuario)				
		  ";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt); 
	

	$_SESSION["idUsuario"] = $row["IT_ID"];
	$_SESSION["isPreventor"] = true;
	$_SESSION["login"] = true;
	$_SESSION["msgError"] = "";
	$_SESSION["preventores"]["empresas"] = array();
	$_SESSION["usuario"] = $row["IT_USUARIO"];
	$_SESSION["idTercerizado"] = $row["PC_ID"];
	
	if (isset($_POST["psn"])) {
		$params = array(":password" => $_POST["psn"], ":id" => $_SESSION["idUsuario"]);
		$sql =
			"UPDATE art.pit_firmantes
				SET IT_CLAVE = :password,
					IT_FORZARCLAVE = 'N'
			  WHERE it_id = :id
				AND IT_FORZARCLAVE = 'S'";
		DBExecSql($conn, $sql, $params);
	}
	
	echo '<meta http-equiv="refresh" content="0; url=/bienvenida-preventores">';
	exit;
}
?>