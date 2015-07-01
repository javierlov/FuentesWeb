<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/export_query.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 60));

set_time_limit(180);

$exportQuery = new ExportQuery($_SESSION["sqlCartillaPrestadores"], "Cartilla de Prestadores");
//$exportQuery->setFieldAlignment($_SESSION["fieldsAlignment"]);
$exportQuery->export();
?>