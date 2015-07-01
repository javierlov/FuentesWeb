<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<style type="text/css"> 
			* {
				margin: 0;
				padding: 0;
			}

			html, body {
				background-color: #FFF;
				overflow: hidden;
			}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/grid.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script type="text/javascript">
			function buscarLocalidad() {
				if (document.getElementById('localidad').value.length >= 2) {
					document.getElementById('divContentGrid').style.display = 'none';
					document.getElementById('divProcesando').style.display = 'block';
					return true;
				}
				else {
					alert('Por favor ingrese al menos 2 caracteres para comenzar la búsqueda.');
					return false;
				}
			}
		</script>
	</head>
	<body style="margin:0; padding:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/functions/buscar_localidad_busqueda.php" id="formBuscarLocalidad" method="post" name="formBuscarLocalidad" target="iframeProcesando" onSubmit="return buscarLocalidad()">
			<input id="p" name="p" type="hidden" value="<?= $_REQUEST["p"]?>">
			<div style="background-color:#ecf5ff; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:4px;">
					<label class="Text5" for="localidad">Localidad</label>
					<input id="localidad" name="localidad" style="text-transform:uppercase; width:320px;" type="text" value="" />
					<input class="btnBuscar" style="vertical-align:-3px;" type="submit" value="" />
				</div>
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:520px; overflow:auto;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			document.getElementById('localidad').focus();
		</script>
	</body>
</html>