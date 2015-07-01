<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/net.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

if (!isset($_SESSION))
	session_start();


function cambiarPassword($user, $pass) {
	if ($pass == "")
		$pass = " ";
	$user = strtolower($user);

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

function contratoVigente($usuario) {
	// 26.12.2013 - Lo comentado de abajo es porque se dio de baja un contrato antes de que venza y el cliente queria cobertura hasta la fecha de vencimiento..
	$params = array(":usuario" => $usuario);
	$sql =
		"SELECT 1
			 FROM web.wue_usuariosextranet, web.wuc_usuariosclientes, web.wcu_contratosxusuarios, aco_contrato, aem_empresa
			WHERE ue_id = uc_idusuarioextranet
				AND uc_id = cu_idusuario(+)
				AND cu_contrato = co_contrato(+)
				AND co_idempresa = em_id(+)
				AND (uc_esadmintotal = 'S' OR art.afi.check_cobertura(em_cuit, SYSDATE) = 1)
/*				AND (uc_esadmintotal = 'S' OR co_estado <> 6)*/
				AND ue_usuario = :usuario";
	$result = existeSql($sql, $params);

	if (!$result) {
		$params = array(":usuario" => $usuario);
		$sql =
			"SELECT 1
				 FROM web.wue_usuariosextranet, web.wuc_usuariosclientes, web.wcu_contratosxusuarios, art.sex_expedientes
				WHERE ue_id = uc_idusuarioextranet
					AND uc_id = cu_idusuario
					AND cu_contrato = ex_contrato
					AND NVL(ex_causafin, ' ') NOT IN('02', '99', '95')
					AND ex_altamedica IS NULL
					AND ue_usuario = :usuario";
		$result = existeSql($sql, $params);
	}

	return $result;
}

function usuarioClaveOk($user, $pass) {
	$user = strtolower($user);

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

// ********************************************
// **********  Inicio validaciones  ***********
// ********************************************

$error = false;
$sendToSiniestros = false;

if ((isset($_GET["sr"])) and (isset($_GET["ps"]))){
	$_POST["sr"] = $_GET["sr"];
	$_POST["ps"] = $_GET["ps"];
	$sendToSiniestros = true;
}

if ((isset($_POST["captcha"])) and ($_POST["captcha"] != $_SESSION["captcha"])) {
	$_SESSION["fieldError"] = "captcha";
	$_SESSION["msgError"] = "Por favor, ingrese el captcha correcto. (1).";
	$error = true;
}
elseif ((!isset($_POST["sr"])) or ($_POST["sr"] == "")) {
	$_SESSION["fieldError"] = "sr";
	$_SESSION["msgError"] = "Por favor, ingrese el Usuario (2).";
	$error = true;
}
else {
	$_POST["sr"] = trim(strtolower(substr($_POST["sr"], 0, 120)));
	$_POST["ps"] = trim(substr($_POST["ps"], 0, 60));

	if (!strpos($_POST["sr"], "@")) {		// Si no tiene una @ creemos que está logueándose por primera vez..
		$params = array(":cuit" => $_POST["sr"]);
		$sql = "SELECT art.webart.get_cuit_encriptado(:cuit) FROM DUAL";
		$cuitEncriptado = valorSql($sql, "", $params);

		if ($cuitEncriptado == $_POST["ps"]) {		// Si el CUIT ingresado es correcto..
			$params = array(":cuit" => $_POST["sr"]);
			$sql =
				"SELECT 1
					 FROM aem_empresa, aco_contrato
					WHERE em_id = co_idempresa
						AND art.afi.check_cobertura(em_cuit, SYSDATE) = 1
						AND em_cuit = :cuit";
			if (!existeSql($sql, $params)) {		// Si el CUIT ingresado no existe en la base de datos..
				$_SESSION["fieldError"] = "sr";
				$_SESSION["msgError"] = "El Usuario ingresado es inválido (3).";
				$error = true;
			}
			else {
				$params = array(":cuit" => $_POST["sr"]);
				$sql =
					"SELECT 1
						 FROM web.wcu_contratosxusuarios, aco_contrato, aem_empresa
						WHERE cu_contrato = co_contrato
							AND co_idempresa = em_id
							AND art.afi.check_cobertura(em_cuit, SYSDATE) = 1
							AND em_cuit = :cuit";
				if (existeSql($sql, $params)) {		// Si ya existe algún usuario para esa empresa es porque NO es la primera vez que entran..
					$_SESSION["fieldError"] = "sr";
					$_SESSION["msgError"] = "Ya existe un administrador para esta cuenta, por favor contactarse con Provincia ART (4).";
					$error = true;
				}
				else {
					$_SESSION["EsAltaAdministrador"] = true;
					$_SESSION["AltaAdministradorCuit"] = $_POST["sr"];
					echo "<script type='text/javascript'>window.location.href = '/mi-perfil'</script>";
					exit;
				}
			}
		}
		else {
			$_SESSION["fieldError"] = "sr";
			$_SESSION["msgError"] = "El Usuario ingresado es inválido (5).";
			$error = true;
		}
	}
	else {
		$params = array(":clave" => $_POST["ps"], ":usuario" => strtolower($_POST["sr"]));
		$sql =
			"SELECT ue_id
				 FROM web.wue_usuariosextranet
				WHERE ue_idmodulo = 49
					AND ue_estado = 'P'
					AND ue_fechabaja IS NULL
					AND ue_usuario = :usuario
					AND (ue_clave = art.utiles.md5(:clave)
							 OR ue_claveprovisoria = art.utiles.md5(:clave))";
		$idUsuario = valorSql($sql, "0", $params);
		if ((int)$idUsuario > 0) {		// Si entra por acá es porque se logueó por primera vez un usuario que no es admin..
			$_SESSION["UsuarioIdIngresoPrimeraVez"] = $idUsuario;
			echo "<script type='text/javascript'>window.location.href = '/mi-perfil'</script>";
			exit;
		}
		else {
			$_SESSION["cambiarPassword"] = cambiarPassword($_POST["sr"], $_POST["ps"]);
			if ((!isset($_POST["psn"])) and ($_SESSION["cambiarPassword"])) {
				$_SESSION["fieldError"] = "ps";
				$_SESSION["msgError"] = "Por favor, ingrese la Contraseña Actual, la Contraseña Nueva y su Confirmación (6).";
				$error = true;
			}
			elseif ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
				$_SESSION["fieldError"] = "ps";
				$_SESSION["msgError"] = "Por favor, ingrese la Contraseña (7).";
				$error = true;
			}
			else {
				if (!usuarioClaveOk($_POST["sr"], $_POST["ps"])) {
					$_SESSION["fieldError"] = "sr";
					$_SESSION["msgError"] = "El Usuario o la Contraseña ingresados es inválido (8).";
					$error = true;
				}
				elseif (!contratoVigente($_POST["sr"])) {
					$_SESSION["fieldError"] = "sr";
					$_SESSION["msgError"] = "El contrato no se encuentra vigente (9).";
					$error = true;
				}
				elseif (isset($_POST["psn"])) {
					if (((isset($_POST["psn"])) and ($_POST["psn"] == "")) or ((isset($_POST["cnf"])) and ($_POST["cnf"] == ""))) {
						$_SESSION["fieldError"] = "psn";
						$_SESSION["msgError"] = "Por favor, ingrese la Contraseña Nueva y su Confirmación (10).";
						$error = true;
					}
					elseif ($_POST["psn"] != $_POST["cnf"]) {
						$_SESSION["fieldError"] = "psn";
						$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña Nueva (11).";
						$error = true;
					}
					elseif (intval($_POST["cc"]) < 8) {
						$_SESSION["fieldError"] = "psn";
						$_SESSION["msgError"] = "La Contraseña Nueva debe tener al menos 8 caracteres (12).";
						$error = true;
					}
					elseif ($_POST["ps"] == $_POST["psn"]) {
						$_SESSION["fieldError"] = "psn";
						$_SESSION["msgError"] = "La Contraseña Nueva no puede ser la misma que la Contraseña Actual (13).";
						$error = true;
					}
				}
			}
		}
	}
}
// ****************************************
// *********  Fin validaciones  ***********
// ****************************************


if ($error) {
	$_SESSION["intentosLogin"]++;
}
else {
	unset($_SESSION["intentosLogin"]);

	$params = array(":usuario" => strtolower($_POST["sr"]));
	$sql = 
		"SELECT co_contrato contrato,
						art.afi.check_cobertura(em_cuit, SYSDATE) contratovigente,
						em_cuit,
						em_suss,
						NVL(em_nombre, '-') empresa,
						NVL(co_idempresa, -1) idempresa,
						uc_esadminempresa,
						uc_esadmintotal,
						ue_id,
						ue_usuario
			 FROM web.wue_usuariosextranet
	LEFT JOIN web.wuc_usuariosclientes ON ue_id = uc_idusuarioextranet
	LEFT JOIN web.wcu_contratosxusuarios ON uc_id = cu_idusuario
	LEFT JOIN aco_contrato ON cu_contrato = co_contrato
	LEFT JOIN aem_empresa ON co_idempresa = em_id
			WHERE ue_idmodulo = 49
				AND ue_fechabaja IS NULL
				AND ue_usuario = :usuario
	 ORDER BY cu_default DESC";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$_SESSION["certificadoCobertura"] = array();
	$_SESSION["contrato"] = $row["CONTRATO"];
	$_SESSION["contratoVigente"] = ($row["CONTRATOVIGENTE"] == 1);
	$_SESSION["cuit"] = $row["EM_CUIT"];
	$_SESSION["empresa"] = $row["EMPRESA"];
	$_SESSION["fieldError"] = "";
	$_SESSION["idEmpresa"] = $row["IDEMPRESA"];
	$_SESSION["idUsuario"] = $row["UE_ID"];
	$_SESSION["isAdmin"] = ($row["UC_ESADMINEMPRESA"] == "S");
	$_SESSION["isAdminTotal"] = ($row["UC_ESADMINTOTAL"] == "S");
	$_SESSION["isCliente"] = true;
	$_SESSION["login"] = true;
	$_SESSION["msgError"] = "";
	$_SESSION["suss"] = $row["EM_SUSS"];
	$_SESSION["usuario"] = $row["UE_USUARIO"];

	if (!$servidorContingenciaActivo) {		// Si el servidor activo es el primario puedo guardar en la base..
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
	}
	EscribirLogTxtLogin($_POST["sr"]);
	if (($sendToSiniestros) and (!$servidorContingenciaActivo))
		echo "<script type='text/javascript'>window.location.href = '/denuncia-siniestros'</script>";
	else
		echo "<script type='text/javascript'>window.location.href = '/bienvenida-cliente'</script>";

	exit;
}
?>