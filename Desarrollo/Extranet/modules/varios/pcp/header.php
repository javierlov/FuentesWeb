<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$_SESSION["isNuevoPCP"] = true;
if (!isset($_SESSION["usuario"])) {
	$_SESSION["usuario"] = rand().date("siHdmY");
	$_SESSION["pcpId"] = -1;
}

// **   INICIO VALIDACIÓN CONEXIÓN CON BASE DE DATOS   **
$sql = "SELECT 1 FROM DUAL";
$stmt = @DBExecSql($conn, $sql);
if (!$stmt) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/mantenimiento.html");
	exit;
}
// **   FIN VALIDACIÓN CONEXIÓN CON BASE DE DATOS   **
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="shortcut icon" type="image/x-icon" href="favicon2.ico" />	

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Provincia ART es una de las empresas aseguradoras del Grupo Banco Provincia, especializada en la prestación del seguro de cobertura de riesgos del trabajo." />
		<meta name="Language" content="Spanish" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

		<title>..:: Provincia ART ::..</title>

		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		
		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->

		<script src="/js/functions.js?rnd=<?= date("Ymdhni")?>" type="text/javascript"></script>

		<link href="/modules/varios/pcp/css/pcp.css" rel="stylesheet" type="text/css" />
		<script src="/modules/varios/pcp/js/pcp.js" type="text/javascript"></script>
	</head>

	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div>
			<div id="header">
				<div id="headerLogo"><a href="/" target="_blank"><img border="0" src="/modules/varios/pcp/images/logo.png" /></a></div>
				<div id="headerTitulo">
					RÉGIMEN ESPECIAL PARA EMPLEADORES DE<br />
					PERSONAL DE CASAS PARTICULARES
				</div>
				<div id="divNada"></div>
			</div>
			<div id="datos">