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
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php");
	exit;
}

if (!$_SESSION["cambiarPassword"])
	if ((!isset($_POST["ps"])) or ($_POST["ps"] == "")) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña.";
		header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php?sr=".$_POST["sr"]);
		exit;
	}

$sql =
	"SELECT pl_password
		FROM rrhh.dpl_login
	 WHERE pl_mail = :mail
		  AND pl_fechabaja IS NULL";
$params = array(":mail" => $_POST["sr"]);
$pass = ValorSql($sql, "", $params);
if ($pass == "") {
	$_SESSION["fieldError"] = "sr";
	$_SESSION["msgError"] = "El Usuario ingresado es inexistente.";
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php?sr=".$_POST["sr"]);
	exit;
}

if (!$_SESSION["cambiarPassword"]) {
	if ($pass != $_POST["ps"]) {
		$_SESSION["fieldError"] = "ps";
		$_SESSION["msgError"] = "La Contraseña ingresada es errónea.";
		header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php?sr=".$_POST["sr"]);
		exit;
	}

	$sql =
		"SELECT pl_cambiopassword
			FROM rrhh.dpl_login
		 WHERE pl_mail = :mail
			  AND pl_fechabaja IS NULL";
	$params = array(":mail" => $_POST["sr"]);
	if (ValorSql($sql, "", $params) == 0) {
		$_SESSION["cambiarPassword"] = true;
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "Por favor cambie la Contraseña.";
		header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php?sr=".$_POST["sr"]);
		exit;
	}
}

if ($_SESSION["cambiarPassword"]) {
	if ((!isset($_POST["psn"])) or ($_POST["psn"] == "") or (!isset($_POST["cnf"])) or ($_POST["cnf"] == "")) {
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "Por favor ingrese la Contraseña nueva y su Confirmación.";
		header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php?sr=".$_POST["sr"]);
		exit;
	}

	if ($_POST["psn"] != $_POST["cnf"]) {
		$_SESSION["fieldError"] = "psn";
		$_SESSION["msgError"] = "La Confirmación no coincide con la Contraseña nueva.";
		header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php?sr=".$_POST["sr"]);
		exit;
	}
}
// **********************************
// *******  Fin validaciones  *******
// **********************************


// Hago el cambio de password..
if ($_SESSION["cambiarPassword"]) {
	$sql =
		"UPDATE rrhh.dpl_login
				SET pl_password = :password,
						pl_cambiopassword = 1
		  WHERE pl_mail = :email
				AND pl_fechabaja IS NULL";
	$params = array(":password" => $_POST["psn"], ":email" => $_POST["sr"]);
	$stmt = DBExecSql($conn, $sql, $params);
}

$sql = 
	"SELECT *
		 FROM rrhh.dpl_login
 		WHERE pl_mail = :email
			AND pl_fechabaja IS NULL";
$params = array(":email" => $_POST["sr"]);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$_SESSION["cambiarPassword"] = (($row["PL_CAMBIOPASSWORD"] == 0)?true:false);
$_SESSION["email"] = $row["PL_MAIL"];
$_SESSION["esAdministrador"] = ($row["PL_ADMINISTRADOR"] == "T");
$_SESSION["fieldError"] = "";
$_SESSION["idEmpresa"] = $row["PL_EMPRESA"];
$_SESSION["idEvaluado"] = $row["PL_ID"];
$_SESSION["idUsuario"] = $row["PL_ID"];
$_SESSION["msgError"] = "";

header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."index.php");
?>