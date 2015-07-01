<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/net.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/login_agente_comercial.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");

// *************************************
// *******  Inicio validaciones  *******
// *************************************
$control = 0;
$error = false;
$setCredentials = false;
//try{
	if ((isset($_POST["captcha"])) and ($_POST["captcha"] != $_SESSION["captcha"])) {
		$_SESSION["fieldError"] = "captcha";
		$_SESSION["msgError"] = "Por favor, ingrese el captcha correcto. (1).";
		$error = true;
	}
	elseif ((!isset($_POST["sr"])) or ($_POST["sr"] == "")) {
		$_SESSION["fieldError"] = "sr";
		$_SESSION["msgError"] = "Estudio Jurídico - Por favor ingrese el Usuario (2).";
		$error = true;
		$setCredentials = true;
	}
	else {
		$_POST["sr"] = substr($_POST["sr"], 0, 20);
		$_POST["ps"] = substr($_POST["ps"], 0, 60);

		$params = array(":usuario" => $_POST["sr"], ":password" => $_POST["ps"]);
		$sql =
			"SELECT nu_id
				 FROM legales.lnu_nivelusuario, legales.lbo_abogado, legales.lej_estudiojuridico
				WHERE nu_usuario = upper(:usuario)
					AND nu_claveweb = :password
					AND nu_idabogado = bo_id
					AND bo_idestudiojuridico = ej_id";

		$sqlBuscaPass =
			"SELECT nu_claveweb
				 FROM legales.lnu_nivelusuario
				WHERE nu_fechabaja IS NULL
					AND nu_usuario = upper(:usuario)";

		$sqlForzarClave =
			"SELECT nu_forzarclave
				 FROM legales.lnu_nivelusuario
				WHERE nu_fechabaja IS NULL
					AND nu_usuario =  upper(:usuario)";
		$control = valorSql($sql, "", $params);

		if ($control == 1) {
			$_SESSION["cambiarPassword"] = (valorSql($sql, "", $params) == 1);
			if ((!isset($_POST["ps"])) and ($_SESSION["cambiarPassword"])) {
				$_SESSION["fieldError"] = "ps";
				$_SESSION["msgError"] = "Por favor ingrese la Contraseña Actual, la Contraseña Nueva y su Confirmación (1.8).";
				$error = true;
			}
			elseif ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
				$_SESSION["fieldError"] = "ps";
				$_SESSION["msgError"] = "Por favor ingrese la Contraseña (1.9).";
				$error = true;
			}
			else {
				$params = array(":usuario" => $_POST["sr"]);
				$pass = valorSql($sqlBuscaPass, "", $params);

				if ($pass == "") {
					$_SESSION["fieldError"] = "sr";
					$_SESSION["msgError"] = "El Usuario ingresado es inexistente (1.10).";
					$error = true;
				}
				elseif ($pass != $_POST["ps"]) {
					$_SESSION["fieldError"] = "ps";
					$_SESSION["msgError"] = "La Contraseña ingresada es incorrecta (1.11).";
					$error = true;
				}
				elseif (isset($_POST["psn"])) {
					if (((isset($_POST["psn"])) and ($_POST["psn"] == "")) or ((isset($_POST["cnf"])) and ($_POST["cnf"] == ""))) {
						$_SESSION["fieldError"] = "psn";
						$_SESSION["msgError"] = "Por favor ingrese la Contraseña Nueva y su Confirmación (1.12).";
						$error = true;
					}
					elseif ($_POST["psn"] != $_POST["cnf"]) {
						$_SESSION["fieldError"] = "psn";
						$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña Nueva (1.13).";
						$error = true;
					}
					elseif ($_POST["psn"] == $_POST["psn"]) {
						$_SESSION["fieldError"] = "psn";
						$_SESSION["msgError"] = "La Contraseña Nueva no puede ser la misma que la Contraseña Actual (1.14).";
						$error = true;
					}
				}
			}
		}
		else {		// Validaciones a usuarios comunes..
			//$params = array(":usuario" => $_POST["sr"]);							
			//$_SESSION["cambiarPassword"] = (ValorSql($sqlForzarClave, "", $params) == 1);

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
				$params = array(":usuario" => $_POST["sr"]);
				$pass = valorSql($sqlBuscaPass, "", $params);

				if ($pass == "") {
					$_SESSION["fieldError"] = "sr";
					$_SESSION["msgError"] = "El Usuario ingresado es inexistente (2.10).";
					$error = true;
				}
				elseif (trim($pass) != trim($_POST["ps"])) {
					$_SESSION["fieldError"] = "ps";
					$_SESSION["msgError"] = "La Contraseña ingresada es incorrecta (2.11).";
					$error = true;
				}
				elseif (isset($_POST["psn"])) {
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
	}
	/*
}catch(Exception $e) {
    echo $e->getMessage();
}
*/

// **********************************
// *******  Fin validaciones  *******
// **********************************

if ($error) {
	$_SESSION["intentosLogin"]++;
}
else {
	unset($_SESSION["intentosLogin"]);

	$params = array(":usuario" => $_POST["sr"]);
	$sql =
		"SELECT LNU.NU_ID, LNU.NU_USUARIO, LNU.NU_IDNIVELSEGURIDAD, LNU.NU_TIPO, LNU.NU_IDABOGADO, LEJ.EJ_NOMBREESTUDIO, LBO.BO_IDESTUDIOJURIDICO, ARTUSU.SE_NOMBRE
			 FROM legales.lnu_nivelusuario lnu, legales.lbo_abogado lbo, legales.lej_estudiojuridico lej,
					art.use_usuarios artusu
			WHERE lnu.nu_idabogado = lbo.bo_id
				AND lbo.bo_idestudiojuridico = lej.ej_id
				AND artusu.se_usuario(+) =  lnu.nu_usuario
				AND lnu.nu_fechabaja is null
				AND lnu.nu_usuario = upper(:usuario)";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$_SESSION["idUsuario"] = $row["NU_ID"];
	$_SESSION["usuario"] = $row["NU_USUARIO"];
	$_SESSION["usuarionombre"] = $row["SE_NOMBRE"];
	$_SESSION["nivel"] = $row["NU_IDNIVELSEGURIDAD"];
	$_SESSION["idAbogado"] = $row["NU_IDABOGADO"];
	$_SESSION["tipo"] = $row["NU_TIPO"];
	$_SESSION["IDESTUDIOJURIDICO"] = $row["BO_IDESTUDIOJURIDICO"];

	$_SESSION["fieldError"] = "";
	$_SESSION["idEmpresa"] = 0;
	$_SESSION["contrato"] = 0;
	$_SESSION["cuit"] = "";
	$_SESSION["isAgenteComercial"] = false;
	$_SESSION["isAbogado"] = true;
	$_SESSION["login"] = true;
	$_SESSION["msgError"] = "";
	$_SESSION["empresa"] = "";
	$_SESSION["certificadoCobertura"] = array();
	
	$_SESSION["entidadReal"] = "";
	$_SESSION["canal"] = "";
	$_SESSION["entidad"] = "";
	$_SESSION["sucursal"] = "";
	$_SESSION["vendedor"] = "";
	$_SESSION["comisiones"] = "";
//-------------------------------------------------------------------
	//$_SESSION["NOMBREESTUDIO"] = $row["EJ_NOMBREESTUDIO"];		
	$DATA_usuario = $_POST["sr"];
	$DATA_password = $_POST["ps"];
	extract(ObtenerNivelUsuario($DATA_usuario, $DATA_password) ,EXTR_PREFIX_ALL, "ONU");

	$_SESSION["NOMBREESTUDIO"] = $ONU_BO_NOMBRE;

/********************************** **********************************
	$_SESSION["altaCotizaciones"] = ($row["UW_COTIZACION"] == 1);
	$_SESSION["autoCotizacion"] = $row["UW_AUTOCOTIZACION"];
	$_SESSION["canal"] = $row["nu_idCANAL"];	
	$_SESSION["comisiones"] = ($row["UW_LIQUIDACION"] == 1);	
	$_SESSION["cuitSuscripcion"] = $row["UW_CUITSUSCRIPCION"];
	$_SESSION["email"] = $row["UW_MAIL"];
	$_SESSION["emailAvisoArt"] = $row["UW_MAILAVISOART"];	
	$_SESSION["entidad"] = $row["nu_idENTIDAD"];
	
	$_SESSION["sucursal"] = $row["nu_idSUCURSAL"];	
	$_SESSION["vendedor"] = $row["nu_idVENDEDOR"];
********************************** **********************************/	
	logAccess($_SESSION["idUsuario"], 2, gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER["REMOTE_ADDR"], 25);

	// Actualizo la password si corresponde..
	if (isset($_POST["psn"])) {
		$params = array(":password" => $_POST["psn"], ":id" => $_SESSION["idUsuario"]);
		$sql =
			"UPDATE legales.lnu_nivelusuario
					SET nu_claveweb = :password,
							nu_forzarclave = 'N'
				WHERE nu_id = :id
					AND nu_forzarclave = 'S'";
		DBExecSql($conn, $sql, $params);
	}

	if (($setCredentials) and (!$servidorContingenciaActivo))
		echo "<script type='text/javascript'>window.location.href = '/denuncia-siniestros'</script>";		
	else
		echo "<script type='text/javascript'>window.location.href = '/SeleccionAplicacion'</script>";	

	exit;
}
?>