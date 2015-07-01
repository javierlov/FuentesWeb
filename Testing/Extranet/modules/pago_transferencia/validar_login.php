<?
@session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


// *************************************
// *******  Inicio validaciones  *******
// *************************************
if ((!isset($_POST["sr"])) or ($_POST["sr"] == "")) {
	$_SESSION["fieldError"] = "sr";
	$_SESSION["msgError"] = "Por favor ingrese el Usuario.";
	@header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php");
	validarParametro(false);
	exit;
}

if (!$_SESSION["cambiarPassword"])
	if ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña.";
		header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php?sr=".$_POST["sr"]);
		exit;
	}

$sql =
	"SELECT ue_clave
		FROM web.wue_usuariosextranet
	 WHERE ue_idmodulo = 4
		  AND ue_usuario = :usuario";
$params = array(":usuario" => $_POST["sr"]);
$pass = ValorSql($sql, "", $params);
if ($pass == "") {
	$_SESSION["fieldError"] = "sr";
	$_SESSION["msgError"] = "El Usuario ingresado es inexistente.";
	header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php?sr=".$_POST["sr"]);
	exit;
}

if (!$_SESSION["cambiarPassword"]) {
	if ($pass != $_POST["ps"]) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "La Contraseña ingresada es errónea.";
		header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php?sr=".$_POST["sr"]);
		exit;
	}

	$sql =
		"SELECT ue_id
			FROM web.wue_usuariosextranet
		 WHERE ue_idmodulo = 4
			  AND ue_usuario = :usuario";
	$params = array(":usuario" => $_POST["sr"]);
	$id = ValorSql($sql, "", $params);

	if (!hasPermiso(3, $id)) {
		$_SESSION["fieldError"] = "sr";
		$_SESSION["msgError"] = "Usted no tiene permiso para ingresar a este módulo.";
		header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php?sr=".$_POST["sr"]);
		exit;
	}

	if ($pass == md5($_POST["sr"])) {
		$_SESSION["cambiarPassword"] = true;
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "Por favor cambie la Contraseña.";
		header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php?sr=".$_POST["sr"]);
		exit;
	}
}

if ($_SESSION["cambiarPassword"]) {
	if ((!isset($_POST["psn"])) or ($_POST["psn"] == "") or (!isset($_POST["cnf"])) or ($_POST["cnf"] == "")) {
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña nueva y su Confirmación.";
		header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php?sr=".$_POST["sr"]);
		exit;
	}

	if ($_POST["psn"] != $_POST["cnf"]) {
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña nueva.";
		header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php?sr=".$_POST["sr"]);
		exit;
	}
}

// **********************************
// *******  Fin validaciones  *******
// **********************************


// Hago el cambio de password..
if ($_SESSION["cambiarPassword"]) {
	$sql =
		"UPDATE web.wue_usuariosextranet
				SET ue_clave = :clave
		  WHERE ue_idmodulo = 4
			   AND ue_usuario = :usuario";
	$params = array(":clave" => $_POST["psn"], ":usuario" => $_POST["sr"]);
	$stmt = DBExecSql($conn, $sql, $params);
}

// Actualizo datos de logueo..
$sql =
	"UPDATE web.wue_usuariosextranet
			SET ue_fechaultimoacceso = SYSDATE,
					ue_ip = :ip
	  WHERE ue_idmodulo = 4
		  AND ue_usuario = :usuario";
$params = array(":ip" => $_SERVER["REMOTE_ADDR"], ":usuario" => $_POST["sr"]);
$stmt = DBExecSql($conn, $sql, $params);

$sql = 
	"SELECT *
		FROM web.wue_usuariosextranet
	 WHERE ue_idmodulo = 4
		  AND ue_usuario = :usuario";
$params = array(":usuario" => $_POST["sr"]);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$_SESSION["cambiarPassword"] = (($row["UE_CLAVE"] == "")?true:false);
$_SESSION["fieldError"] = "";
$_SESSION["idUsuario"] = $row["UE_ID"];
$_SESSION["msgError"] = "";
$_SESSION["usuario"] = $row["UE_USUARIO"];

header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."index.php");
?>