<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


// *************************************
// *******  Inicio validaciones  *******
// *************************************
if ((!isset($_POST["sr"])) or ($_POST["sr"] == "")) {
	$_SESSION["fieldError"] = "sr";
	$_SESSION["msgError"] = "Por favor ingrese el Usuario.";
	header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."login.php");
	exit;
}

if (!$_SESSION["cambiarPassword"])
	if ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña.";
		header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."login.php?sr=".$_POST["sr"]);
		exit;
	}

$params = array(":mail" => $_POST["sr"]);
$sql =
	"SELECT ui_password
		 FROM rrhh.rui_usuarioincentivo
		WHERE ui_mail = :mail
			AND ui_fechabaja IS NULL";
$pass = ValorSql($sql, "", $params);
if ($pass == "") {
	$_SESSION["fieldError"] = "sr";
	$_SESSION["msgError"] = "El Usuario ingresado es inexistente.";
	header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."login.php?sr=".$_POST["sr"]);
	exit;
}

if (!$_SESSION["cambiarPassword"]) {
	if ($pass != $_POST["ps"]) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "La Contraseña ingresada es errónea.";
		header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."login.php?sr=".$_POST["sr"]);
		exit;
	}

	$params = array(":mail" => $_POST["sr"]);
	$sql =
		"SELECT ui_cambiopassword
			 FROM rrhh.rui_usuarioincentivo
			WHERE ui_mail = :mail
				AND ui_fechabaja IS NULL";
	if (ValorSql($sql, "", $params) == "S") {
		$_SESSION["cambiarPassword"] = true;
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "Por favor cambie la Contraseña.";
		header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."login.php?sr=".$_POST["sr"]);
		exit;
	}
}

if ($_SESSION["cambiarPassword"]) {
	if ((!isset($_POST["psn"])) or ($_POST["psn"] == "") or (!isset($_POST["cnf"])) or ($_POST["cnf"] == "")) {
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña nueva y su Confirmación.";
		header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."login.php?sr=".$_POST["sr"]);
		exit;
	}

	if ($_POST["psn"] != $_POST["cnf"]) {
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña nueva.";
		header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."login.php?sr=".$_POST["sr"]);
		exit;
	}
}
// **********************************
// *******  Fin validaciones  *******
// **********************************


// Hago el cambio de password..
if ($_SESSION["cambiarPassword"]) {
	$params = array(":password" => $_POST["psn"], ":email" => $_POST["sr"]);
	$sql =
		"UPDATE rrhh.rui_usuarioincentivo
				SET ui_password = :password,
						ui_cambiopassword = 'N'
			WHERE ui_mail = :email
				AND ui_fechabaja IS NULL";
	DBExecSql($conn, $sql, $params);
}

$params = array(":email" => $_POST["sr"]);
$sql = 
	"SELECT *
		 FROM rrhh.rui_usuarioincentivo
		WHERE ui_mail = :email
			AND ui_fechabaja IS NULL";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$_SESSION["cambiarPassword"] = (($row["UI_CAMBIOPASSWORD"] == "S")?true:false);
$_SESSION["email"] = $row["UI_MAIL"];
$_SESSION["fieldError"] = "";
$_SESSION["idEmpresa"] = $row["UI_IDEMPRESA"];
$_SESSION["idUsuario"] = $row["UI_ID"];
$_SESSION["msgError"] = "";

header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."puntos.php");
?>