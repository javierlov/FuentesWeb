<?
session_start();

$_SESSION["RegionesSanitariasEditar"] = ($_REQUEST["modo"] == "e");

header("Location: /modules/regiones_sanitarias/contenido.php");
?>