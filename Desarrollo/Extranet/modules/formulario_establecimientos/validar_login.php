<?
@session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$firstPage = "conten.php";
if ((isset($_REQUEST["al"])) and ($_REQUEST["al"] == "t")) {
	$_POST["cuit"] = $_REQUEST["cuit"];
	$_POST["contrato"] = $_REQUEST["contrato"];
	$firstPage = $_REQUEST["p"].".php";
}

// *************************************
// *******  Inicio validaciones  *******
// *************************************
if ((!isset($_POST["cuit"])) or ($_POST["cuit"] == "")) {
	$_SESSION["fieldError"] = "cuit";
	$_SESSION["msgError"] = "Por favor ingrese el Nº de C.U.I.T.";
	header("Location: ".LOCAL_PATH_FORMULARIO_ESTABLECIMIENTOS."login.php");
	validarParametro(false);
	exit;
}

if ((!isset($_POST["contrato"])) or ($_POST["contrato"] == "")) {
	$_SESSION["fieldError"] = "contrato";
	$_SESSION["msgError"] = "Por favor ingrese el Nº de Contrato.";
	header("Location: ".LOCAL_PATH_FORMULARIO_ESTABLECIMIENTOS."login.php?cuit=".$_POST["cuit"]);
	exit;
}

$sql =
	"SELECT co_contrato
		FROM aco_contrato, aem_empresa
	 WHERE co_idempresa = em_id
		  AND em_cuit = :cuit
		  AND afiliacion.check_cobertura(co_contrato) = 1";
$params = array(":cuit" => $_POST["cuit"]);
$contrato = ValorSql($sql, "", $params);
if ($contrato == "") {
	$_SESSION["fieldError"] = "cuit";
	$_SESSION["msgError"] = "No hay ningún contrato activo para ese Nº de C.U.I.T.";
	header("Location: ".LOCAL_PATH_FORMULARIO_ESTABLECIMIENTOS."login.php?cuit=".$_POST["cuit"]);
	exit;
}

if ($contrato != $_POST["contrato"]) {
	$_SESSION["fieldError"] = "contrato";
	$_SESSION["msgError"] = "El contrato ingresado no está activo.";
	header("Location: ".LOCAL_PATH_FORMULARIO_ESTABLECIMIENTOS."login.php?cuit=".$_POST["cuit"]);
	exit;
}
// **********************************
// *******  Fin validaciones  *******
// **********************************


$_SESSION["contrato"] = $contrato;
$_SESSION["fieldError"] = "";
$_SESSION["msgError"] = "";

logAccess($contrato, 1, gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER["REMOTE_ADDR"], 1);

if ((isset($_REQUEST["o"])) and ($_REQUEST["o"] == "ehys")) {		// Si se entro desde un e-mail enviado desde HYS, se corre este update..
	$_SESSION["origen"] = "ehys";
	$params = array(":contrato" => $_POST["contrato"]);
	$sql =
		"UPDATE hys.hrg_relevgestion
				SET rg_fechaingresomail = SYSDATE
		  WHERE rg_contrato = :contrato
				AND TO_NUMBER(rg_vigencia) = (SELECT MAX(TO_NUMBER(rg_vigencia))
																		  FROM hys.hrg_relevgestion
																		WHERE rg_contrato = :contrato
																			 AND rg_fechabaja IS NULL)
				AND rg_fechaingresomail IS NULL
				AND rg_fechabaja IS NULL";
	DBExecSql($conn, $sql, $params);
}

header("Location: ".LOCAL_PATH_FORMULARIO_ESTABLECIMIENTOS."index.php?fp=".$firstPage);
?>