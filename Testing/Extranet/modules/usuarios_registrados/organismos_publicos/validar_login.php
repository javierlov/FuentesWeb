<?
// *************************************
// *******  Inicio validaciones  *******
// *************************************
$error = false;
if ((!isset($_POST["sr"])) or ($_POST["sr"] == "")) {
	$_SESSION["fieldError"] = "sr";
	$_SESSION["msgError"] = "Por favor ingrese el Usuario (1).";
	$error = true;
}
else {
	$_POST["sr"] = substr($_POST["sr"], 0, 20);
	$_POST["ps"] = substr($_POST["ps"], 0, 20);

	$params = array(":usuario" => $_POST["sr"]);
	$sql =
		"SELECT we_forzarclave
			 FROM emi.iwe_usuariowebemision
			WHERE we_fechabaja IS NULL
				AND we_usuario = :usuario";

	$_SESSION["cambiarPassword"] = (ValorSql($sql, "", $params) == 'S');
	if ((!isset($_POST["psn"])) and ($_SESSION["cambiarPassword"])) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña Actual, la Contraseña Nueva y su Confirmación (2).";
		$error = true;
	}
	elseif ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña (3).";
		$error = true;
	}
	else {
		$params = array(":usuario" => $_POST["sr"]);
		$sql =
			"SELECT we_clave
				 FROM emi.iwe_usuariowebemision
				WHERE we_fechabaja IS NULL
					AND we_usuario = :usuario";
		$pass = ValorSql($sql, "", $params);
		if ($pass == "") {
			$_SESSION["fieldError"] = "sr";
			$_SESSION["msgError"] = "El Usuario ingresado es inexistente (4).";
			$error = true;
		}
		elseif ($pass != $_POST["ps"]) {
			$_SESSION["fieldError"] = "ps";
			$_SESSION["msgError"] = "La Contraseña ingresada es incorrecta (5).";
			$error = true;
		}
		elseif (isset($_POST["psn"])) {
			if (((isset($_POST["psn"])) and ($_POST["psn"] == "")) or ((isset($_POST["cnf"])) and ($_POST["cnf"] == ""))) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "Por favor ingrese la Contraseña Nueva y su Confirmación (6).";
				$error = true;
			}
			elseif (strlen($_POST["psn"]) < 4) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "La Contraseña Nueva debe tener al menos 4 caracteres (7).";
				$error = true;
			}
			elseif ($_POST["psn"] != $_POST["cnf"]) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña Nueva (8).";
				$error = true;
			}
			elseif ($_POST["ps"] == $_POST["psn"]) {
				$_SESSION["fieldError"] = "psn";
				$_SESSION["msgError"] = "La Contraseña Nueva no puede ser la misma que la Contraseña Actual (9).";
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
		"SELECT *
			 FROM emi.iwe_usuariowebemision
			WHERE we_fechabaja IS NULL
				AND we_usuario = :usuario";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$_SESSION["contrato"] = $row["WE_CONTRATO"];
	$_SESSION["email"] = $row["WE_MAIL"];
	$_SESSION["fieldError"] = "";
	$_SESSION["idUsuario"] = $row["WE_ID"];
	$_SESSION["isOrganismoPublico"] = true;
	$_SESSION["login"] = true;
	$_SESSION["msgError"] = "";
	$_SESSION["usuario"] = $row["WE_USUARIO"];

	// Actualizo la password si corresponde..
	if (isset($_POST["psn"])) {
		$params = array(":clave" => $_POST["psn"], ":id" => $_SESSION["idUsuario"]);
		$sql =
			"UPDATE emi.iwe_usuariowebemision
					SET we_clave = :clave,
							we_forzarclave = 'N'
			  WHERE we_id = :id
				 	AND we_forzarclave = 'S'";
		DBExecSql($conn, $sql, $params);
	}

	// Registro el último login..
	$params = array(":id" => $_SESSION["idUsuario"]);
	$sql =
		"UPDATE emi.iwe_usuariowebemision
				SET we_ultimologin = SYSDATE
			WHERE we_id = :id";
	DBExecSql($conn, $sql, $params);

	echo '<meta http-equiv="refresh" content="0; url=/index.php?pageid=46">';
	exit;
}
?>