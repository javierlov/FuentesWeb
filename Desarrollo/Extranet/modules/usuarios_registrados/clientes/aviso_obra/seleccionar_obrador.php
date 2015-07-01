<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<style type="text/css"> 
			* {margin:0; padding:0;}
			html, body {background-color:#fff; overflow:hidden;}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/grid.js" type="text/javascript"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
	</head>
	<body style="margin:0; padding:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/usuarios_registrados/clientes/aviso_obra/seleccionar_obrador_busqueda.php" id="formSeleccionarObrador" method="post" name="formSeleccionarObrador" target="iframeProcesando">
			<div class="Text5" style="background-color:#216B94; color:#fff; padding:4px;">
				Seleccione el obrador haciendo clic sobre el ícono de la primer columna.
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:416px; overflow:auto;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:8px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..." /></div>
	</body>
	<script type="text/javascript">
		with (document) {
			getElementById('divContentGrid').style.display = 'none';
			getElementById('divProcesando').style.display = 'block';
			getElementById('formSeleccionarObrador').submit();
		}
	</script>
</html>