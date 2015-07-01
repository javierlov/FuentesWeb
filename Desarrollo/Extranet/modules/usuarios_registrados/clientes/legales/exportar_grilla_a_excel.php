<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/export_query.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 33));

set_time_limit(180);

$sql = $_SESSION["sqlLegales"];
$sql2 = " AND sex_expedientes.ex_contrato = ".$_SESSION["contrato"];

$sql = str_replace("ORDER BY", $sql2." ORDER BY", $sql);

$exportQuery = new ExportQuery($sql, "Legales_".date("dmY"));
$exportQuery->export();
?>