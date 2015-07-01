<?php
// prevent caching code from php.net
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

// **   INICIO VALIDACIÓN CONEXIÓN CON BASE DE DATOS   **
$sql = "SELECT 1 FROM DUAL";
$stmt = @DBExecSql($conn, $sql);
if (!$stmt) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/mantenimiento.html");
	exit;
}
// **   FIN VALIDACIÓN CONEXIÓN CON BASE DE DATOS   **


$pageid = -1;
if (isset($_REQUEST["pageid"]))
	$pageid = intval($_REQUEST["pageid"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/portada.css" type="text/css" />
<!--		<link rel="stylesheet" href="/styles/reset.css" type="text/css" />-->
		<link rel="stylesheet" href="/styles/style2.css?rnd=20131115" type="text/css" />
<!--		<link rel="apple-touch-icon" sizes="114×114" href="favicon_apple.png" />-->
		<link rel="shortcut icon" type="image/x-icon" href="favicon2.ico" />	

		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Provincia ART es una de las empresas aseguradoras del Grupo Banco Provincia, especializada en la prestación del seguro de cobertura de riesgos del trabajo." />
		<meta name="Language" content="Spanish" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />

		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script src="/js/browser.js" type="text/javascript"></script>
		<script src="/js/functions.js?rnd=20130802" type="text/javascript"></script>
		<script src="/js/grid.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>

		<title>..:: Provincia ART ::..</title>

		<!-- INICIO HINT.. -->
		<script language="JavaScript" src="/js/hint/hints.js"></script>
		<!-- FIN HINT.. -->

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->

		<!-- INICIO SCRIPT ACCESIBILIDAD.. -->
		<!-- 
		<script type="text/javascript">			
			(function(){			
				var i7e_e = document.createElement("script");
				var i7e_t = window.location.host;
				i7e_e.type = "text/javascript";
				return i7e_r = "es-ES", i7e_t=i7e_t.replace(/\./g,"--"), i7e_t+=".accesible.inclusite.com",
																i7e_e.src=("https:"==document.location.protocol?"https://":"http://") + i7e_t + "/inclusite/frameworks_initializer.js?lng=" + i7e_r, document.getElementsByTagName("head")[0].appendChild(i7e_e), i7e_e.src})()		
		</script>
		-->
		<!-- FIN SCRIPT ACCESIBILIDAD.. -->

		<style>
			#divPopup {
				background-color: #0f539c;
				filter: alpha(opacity = 20);
				height: 100%;
				left: 0px;
				opacity: .2;
				position: absolute;
				top: 0px;
				width: 100%;
				z-index: 99;
			}

			#divPopupTexto {
				background-color: #D8D8DA;
				border: 1px solid #808080;
				font-size:10pt;
				height: 590px;
				left: 50%;
				margin-left: -240px;
				padding: 0px;
				position: absolute;
				top: 12px;
				width: 672px;
				z-index: 100;
			}
		</style>
	</head>

	<body link="#807F83" vlink="#807F83" alink="#807F83">
		<div id="divPrincipal">
			
			<div id="divHeader"><? require_once("header.php") ?></div>			
			<div id="divMenu"><? require_once("menu.php") ?></div>						
			<div id="divContent"><div id="divContentIn"><? require_once(getPagePath($pageid))?></div></div>
			<div id="divFooter"><?php require_once("footer.php")?></div>
		</div>
<!--
COMENTADO EL 5.7.2013 A PEDIDO DE BRUSSO..
<?
if ($pageid < 1) {
?>
		<script type="text/javascript">
			function cerrarPopup() {
				with (document) {
					getElementById('divPopupTexto').style.display = 'none';
					getElementById('divPopup').style.display = 'none';
					getElementById('divBanner1HomePage').style.display = 'block';
					getElementById('divBanner2HomePage').style.display = 'block';
					getElementById('divBanner3HomePage').style.display = 'block';
				}
			}

			function enviarEmailPopup() {
				var brw = new Browser();
				if (brw.name == 'firefox')
					window.location.href = 'mailto:info@provart.com.ar?subject=Damnificados por fenómeno meteorológico&body=Razón social:%0ACUIT / Nº de contrato:%0APersona de contacto:%0ATeléfono:%0Ae-Mail:%0ALugar de ocurrencia (domicilio afectado):%0ADescripción del siniestro / daños sufridos por la inundación:';
				else
					window.location.href = 'mailto:info@provart.com.ar?subject=Damnificados por <?= rawurlencode("fenómeno meteorológico")?>&body=<?= rawurlencode("Razón")?> social:%0ACUIT / <?= rawurlencode("Nº")?> de contrato:%0APersona de contacto:%0A<?= rawurlencode("Teléfono")?>:%0Ae-Mail:%0ALugar de ocurrencia (domicilio afectado):%0A<?= rawurlencode("Descripción")?> del siniestro / <?= rawurlencode("daños")?> sufridos por la <?= rawurlencode("inundación")?>:';
				cerrarPopup();
			}
		</script>
		<div id="divPopupTexto">
			<div align="right" style="background-color:#dcdcdc; color:#464646; cursor:pointer; font-family:Verdana; font-size:12pt; padding-right:5px;" onClick="cerrarPopup()">Cerrar <b>X</b></div>
			<a href="#" onClick="enviarEmailPopup()"><img border="0" src="/images/pop_up.jpg"/></a>
		</div>
		<div id="divPopup"></div>
<?
}
?>
-->
	</body>
</html>