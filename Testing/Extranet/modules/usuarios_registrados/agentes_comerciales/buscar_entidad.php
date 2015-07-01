<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));
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
			function buscarEntidad() {
				with (document) {
					if ((getElementById('codigo').value.length == 0) &&
							(getElementById('nombre').value.length == 0)) {
						alert('Por favor ingrese el código o parte del nombre de la entidad para comenzar la búsqueda.');
						return false;
					}
					else {
						getElementById('divContentGrid').style.display = 'none';
						getElementById('divProcesando').style.display = 'block';
						return true;
					}
				}
			}
		</script>
	</head>
	<body style="margin:0; padding:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/usuarios_registrados/agentes_comerciales/buscar_entidad_busqueda.php" id="formBuscarEntidad" method="post" name="formBuscarEntidad" target="iframeProcesando" onSubmit="return buscarEntidad()">
			<input id="c" name="c" type="hidden" value="<?= $_REQUEST["c"]?>">
			<div style="background-color:#49bdec; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:4px;">
					<label class="Text5" for="codigo">Código</label>
					<input id="codigo" maxlength="10" name="codigo" style="width:96px;" type="text" value="" />
					<label class="Text5" for="nombre">Nombre</label>
					<input id="nombre" maxlength="200" name="nombre" style="text-transform:uppercase; width:312px;" type="text" value="" />
					<input class="btnBuscar" style="vertical-align:-3px;" type="submit" value="" />
				</div>
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:520px; overflow:auto;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:8px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			document.getElementById('codigo').focus();
		</script>
	</body>
</html>