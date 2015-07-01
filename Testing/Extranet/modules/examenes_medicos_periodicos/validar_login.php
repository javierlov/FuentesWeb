<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


if ((isset($_REQUEST["al"])) and ($_REQUEST["al"] == "t")) {		// Si es un autologin..
	$_POST["cuit"] = $_REQUEST["cuit"];
	$_POST["contrato"] = $_REQUEST["contrato"];
	$_SESSION["lote"] = $_REQUEST["lote"];
}


// **************************************
// ***********  Inicio validaciones  ***********
// **************************************
if ((!isset($_POST["cuit"])) or ($_POST["cuit"] == "")) {
	$_SESSION["fieldError"] = "cuit";
	$_SESSION["msgError"] = "Por favor ingrese el Nº de C.U.I.T.";
	header("Location: /index.php?pageid=47");
	exit;
}

if ((!isset($_POST["contrato"])) or ($_POST["contrato"] == "")) {
	$_SESSION["fieldError"] = "contrato";
	$_SESSION["msgError"] = "Por favor ingrese el Nº de Contrato.";
	header("Location: /index.php?pageid=47&cuit=".$_POST["cuit"]);
	exit;
}

$_POST["cuit"] = substr($_POST["cuit"], 0, 11);

$params = array(":cuit" => $_POST["cuit"]);
$sql =
	"SELECT co_contrato
		 FROM aco_contrato, aem_empresa
		WHERE co_idempresa = em_id
			AND em_cuit = :cuit
			AND afiliacion.check_cobertura(co_contrato) = 1";
$contrato = ValorSql($sql, "", $params);
if ($contrato == "") {
	$_SESSION["fieldError"] = "cuit";
	$_SESSION["msgError"] = "No hay ningún contrato activo para ese Nº de C.U.I.T.";
	header("Location: /index.php?pageid=47&cuit=".$_POST["cuit"]);
	exit;
}

if ($contrato != $_POST["contrato"]) {
	$_SESSION["fieldError"] = "contrato";
	$_SESSION["msgError"] = "El contrato ingresado no está activo.";
	header("Location: /index.php?pageid=47&cuit=".$_POST["cuit"]);
	exit;
}
// **********************************
// *******  Fin validaciones  *******
// **********************************


$_SESSION["contrato"] = $contrato;
$_SESSION["fieldError"] = "";
$_SESSION["msgError"] = "";

LogAccess($contrato, 1, gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER["REMOTE_ADDR"], 47);

header("Location: /modules/examenes_medicos_periodicos/index.php");
?>