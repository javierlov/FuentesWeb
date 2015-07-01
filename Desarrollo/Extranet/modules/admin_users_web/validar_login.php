<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


if (!isset($_SESSION))
	session_start();


function cambiarPassword($user, $pass) {
	if ($pass == "")
		$pass = " ";

	$params = array(":claveprovisoria" => $pass, ":usuario" => $user);
	$sql =
		"SELECT 1
			 FROM web.wue_usuariosextranet
			WHERE ue_idmodulo = 49
				AND ue_estado = 'A'
				AND ue_fechabaja IS NULL
				AND ue_usuario = :usuario
				AND ue_claveprovisoria = art.utiles.md5(:claveprovisoria)
				AND ue_fechavencclaveprovisoria > SYSDATE";
	if (existeSql($sql, $params))		// Si entró con una clave provisoria lo obligo a cambiarla..
		return true;
	else {
		$params = array(":usuario" => $user);
		$sql =
			"SELECT ue_forzarclave
				 FROM web.wue_usuariosextranet
				WHERE ue_idmodulo = 49
					AND ue_estado = 'A'
					AND ue_fechabaja IS NULL
					AND ue_usuario = :usuario";
		return (valorSql($sql, "", $params) == "T");
	}
}

function usuarioClaveOk($user, $pass) {
	$params = array(":clave" => $pass, ":usuario" => $user);
	$sql =
		"SELECT 1
			 FROM web.wue_usuariosextranet
			WHERE ue_idmodulo = 49
				AND ue_estado = 'A'
				AND ue_fechabaja IS NULL
				AND ue_usuario = :usuario
				AND ue_clave = art.utiles.md5(:clave)";
	if (existeSql($sql, $params))
		return true;		// Usuario y clave ok..
	else {
		$params = array(":claveprovisoria" => $pass, ":usuario" => $user);
		$sql =
			"SELECT 1
				 FROM web.wue_usuariosextranet
				WHERE ue_idmodulo = 49
					AND ue_estado = 'A'
					AND ue_fechabaja IS NULL
					AND ue_usuario = :usuario
					AND ue_claveprovisoria = art.utiles.md5(:claveprovisoria)
					AND ue_fechavencclaveprovisoria > SYSDATE";
		return existeSql($sql, $params);		// Usuario y clave provisoria ok..o no..
	}
}


unset($_SESSION["EsAltaAdministrador"]);
unset($_SESSION["AltaAdministradorCuit"]);
unset($_SESSION["UsuarioIdIngresoPrimeraVez"]);


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
	$_POST["sr"] = strtolower(substr($_POST["sr"], 0, 120));
	$_POST["ps"] = substr($_POST["ps"], 0, 60);

	$params = array(":clave" => $_POST["ps"], ":usuario" => $_POST["sr"]);
	$sql =
		"SELECT ue_id
			 FROM web.wue_usuariosextranet
			WHERE ue_idmodulo = 49
				AND ue_estado = 'P'
				AND ue_fechabaja IS NULL
				AND ue_usuario = :usuario
				AND ue_clave = art.utiles.md5(:clave)";
	$idUsuario = valorSql($sql, "0", $params);
	if ((int)$idUsuario > 0) {		// Si entra por acá es porque se logueó por primera vez un usuario que no es admin..
		$_SESSION["UsuarioIdIngresoPrimeraVez"] = $idUsuario;
		echo "<script type='text/javascript'>window.location.href = '".LOCAL_PATH_USERS_WEB."index.php?pageid=1"."'</script>";
		exit;
	}
	else {
		$_SESSION["cambiarPassword"] = cambiarPassword($_POST["sr"], $_POST["ps"]);
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
			if (!usuarioClaveOk($_POST["sr"], $_POST["ps"])) {
				$_SESSION["fieldError"] = "sr";
				$_SESSION["msgError"] = "El Usuario o la Contraseña ingresados es inválido (4).";
				$error = true;
			}
			elseif (isset($_POST["psn"])) {
				if (((isset($_POST["psn"])) and ($_POST["psn"] == "")) or ((isset($_POST["cnf"])) and ($_POST["cnf"] == ""))) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = "Por favor ingrese la Contraseña Nueva y su Confirmación (5).";
					$error = true;
				}
				elseif ($_POST["psn"] != $_POST["cnf"]) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña Nueva (6).";
					$error = true;
				}
				elseif (intval($_POST["cc"]) < 8) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = "La Contraseña Nueva debe tener al menos 8 caracteres (7).";
					$error = true;
				}
				elseif ($_POST["ps"] == $_POST["psn"]) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = "La Contraseña Nueva no puede ser la misma que la Contraseña Actual (8).";
					$error = true;
				}
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
		"SELECT NVL(co_idempresa, -1) idempresa, uc_esadminempresa, uc_esadmintotal, ue_id, ue_usuario
			 FROM web.wue_usuariosextranet LEFT JOIN web.wuc_usuariosclientes ON ue_id = uc_idusuarioextranet
	LEFT JOIN web.wcu_contratosxusuarios ON uc_id = cu_idusuario
	LEFT JOIN aco_contrato ON cu_contrato = co_contrato AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
	LEFT JOIN aem_empresa ON co_idempresa = em_id
			WHERE ue_idmodulo = 49
				AND ue_fechabaja IS NULL
				AND ue_usuario = :usuario
	 ORDER BY cu_default DESC";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$_SESSION["contratos"] = "126428, 126430, 132246";		// Los contratos de Adecco..
	$_SESSION["fieldError"] = "";
	$_SESSION["idEmpresa"] = $row["IDEMPRESA"];
	$_SESSION["idUsuario"] = $row["UE_ID"];
	$_SESSION["isAdmin"] = ($row["UC_ESADMINEMPRESA"] == "S");
	$_SESSION["isAdminTotal"] = ($row["UC_ESADMINTOTAL"] == "S");
	$_SESSION["isCliente"] = true;
	$_SESSION["login"] = true;
	$_SESSION["msgError"] = "";
	$_SESSION["usuario"] = $row["UE_USUARIO"];

	logAccess($_SESSION["idUsuario"], 3, gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER["REMOTE_ADDR"], 49);

	// Actualizo la password si corresponde..
	if (($_SESSION["cambiarPassword"]) and (isset($_POST["psn"]))) {
		$params = array(":clave" => $_POST["psn"], ":id" => $_SESSION["idUsuario"]);
		$sql =
			"UPDATE web.wue_usuariosextranet
					SET ue_clave = art.utiles.md5(:clave),
							ue_forzarclave = 'F'
			  WHERE ue_id = :id";
		DBExecSql($conn, $sql, $params);
	}

	// Registro el último login y blanqueo la clave provisoria..
	$params = array(":id" => $_SESSION["idUsuario"]);
	$sql =
		"UPDATE web.wue_usuariosextranet
				SET ue_claveprovisoria = NULL,
						ue_fechaultimoacceso = SYSDATE,
						ue_fechavencclaveprovisoria = NULL
		  WHERE ue_id = :id";
	DBExecSql($conn, $sql, $params);

	echo "<script type='text/javascript'>window.location.href = '".LOCAL_PATH_USERS_WEB."index.php?pageid=1"."'</script>";
	exit;
}
?>