<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION[$_REQUEST["s"]]));
if ($_REQUEST["s"] == "isCliente")
	if (!isset($_REQUEST["idModulo"]))
		$_REQUEST["idModulo"] = -1;


if (intval($_REQUEST["idTablaPadre"]) == 0)
	$_REQUEST["idTablaPadre"] = -1;

if (!isset($_REQUEST["r"]))
	$_REQUEST["r"] = "f";

if (!isset($_REQUEST["tipo"]))
	$_REQUEST["tipo"] = "L";

$url = "/functions/telefonos/abrir_ventana_edicion.php";
$url.= "?s=".$_REQUEST["s"];
$url.= "&campoClave=".$_REQUEST["campoClave"];
$url.= "&idModulo=".$_REQUEST["idModulo"];
$url.= "&idTablaPadre=".$_REQUEST["idTablaPadre"];
$url.= "&prefijo=".$_REQUEST["prefijo"];
$url.= "&tablaTel=".$_REQUEST["tablaTel"];
$url.= "&tipo=".$_REQUEST["tipo"];
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<style type="text/css">
			.input {background-color:#fff; border-bottom:1px solid #808080; border-left:1px solid #fff; border-right:1px solid #fff; border-top:1px solid #fff; color:#000080; font-size:9;
							padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; text-transform:uppercase;}
			.gridEmpty {border:none; background-image:url(/images/tel_not_found.gif); height:50px; width:237px;}
			#divBtnAgregarTelefono {margin-left:2px; position:relative;}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/grid.js" type="text/javascript"></script>
	</head>
	<body onLoad="formTelefonos.submit()">
		<iframe id="iframeABM" name="iframeABM" src="" style="display:none;"></iframe>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/functions/telefonos/telefonos_busqueda.php" id="formTelefonos" method="post" name="formTelefonos" target="iframeProcesando">
			<input id="campoClave" name="campoClave" type="hidden" value="<?= $_REQUEST["campoClave"]?>" />
			<input id="idModulo" name="idModulo" type="hidden" value="<?= $_REQUEST["idModulo"]?>" />
			<input id="idTablaPadre" name="idTablaPadre" type="hidden" value="<?= $_REQUEST["idTablaPadre"]?>" />
			<input id="prefijo" name="prefijo" type="hidden" value="<?= $_REQUEST["prefijo"]?>" />
			<input id="r" name="r" type="hidden" value="<?= $_REQUEST["r"]?>" />
			<input id="s" name="s" type="hidden" value="<?= $_REQUEST["s"]?>" />
			<input id="tablaTel" name="tablaTel" type="hidden" value="<?= $_REQUEST["tablaTel"]?>" />
			<input id="tipo" name="tipo" type="hidden" value="<?= $_REQUEST["tipo"]?>" />
<?
if ($_REQUEST["r"] != "t") {		// Si no es readonly..
?>
			<div id="divBtnAgregarTelefono">
				<input class="btnAgregarTelefono" type="button" value="" onClick="document.getElementById('iframeABM').src = '<?= $url?>&id=-1'" />
			</div>
<?
}
?>
			<div align="center" id="divContentGrid" name="divContentGrid"></div>
			<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		</form>
	</body>
</html>