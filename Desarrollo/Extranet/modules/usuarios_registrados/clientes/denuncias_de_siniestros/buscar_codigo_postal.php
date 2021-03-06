<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once("buscar_codigo_postal_combos.php");

validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<style type="text/css">
			* {margin:0; padding:0;}
			html, body {background-color:#fff; overflow: hidden;}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/grid.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script type="text/javascript">
			function buscarLocalidad() {
				if (document.getElementById('localidad').value.length < 2) {
					alert('La localidad debe tener al menos 2 caracteres.');
					return false;
				}
				if (document.getElementById('provincia').value == -1) {
					alert('Por favor, seleccione la provincia.');
					return false;
				}

				document.getElementById('divContentGrid').style.display = 'none';
				document.getElementById('divProcesando').style.display = 'block';
				return true;
			}
		</script>
	</head>
	<body style="margin:0; padding:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/usuarios_registrados/clientes/denuncias_de_siniestros/buscar_codigo_postal_busqueda.php" id="formBuscarLocalidad" method="post" name="formBuscarLocalidad" target="iframeProcesando" onSubmit="return buscarLocalidad()">
			<div style="background-color:#ecf5ff; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:4px;">
					<label class="Text5" for="localidad">Localidad</label>
					<input autofocus id="localidad" name="localidad" style="margin-right:8px; text-transform:uppercase; width:304px;" type="text" value="" />
					<label class="Text5" for="provincia">Provincia</label>
					<?= $comboProvincia->draw();?>
					<input class="btnBuscar" style="margin-left:8px; vertical-align:-3px;" type="submit" value="" />
				</div>
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:520px; overflow:auto;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img src="/images/waiting.gif" title="Espere por favor..." /></div>
	</body>
</html>