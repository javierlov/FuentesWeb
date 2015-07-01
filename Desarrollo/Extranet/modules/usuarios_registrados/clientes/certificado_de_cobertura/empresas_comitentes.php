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
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
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
	</head>
	<body style="margin:0; padding:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/usuarios_registrados/clientes/certificado_de_cobertura/empresas_comitentes_busqueda.php" id="formEmpresa" method="post" name="formEmpresa" target="iframeProcesando">
			<div style="background-color:#49bdec; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:4px;">
					<label class="Text5" for="razonSocial">Razón Social</label>
					<input id="razonSocial" name="razonSocial" style="width:224px;" type="text" value="" />
					<input class="btnBuscar" style="vertical-align:-3px;" type="submit" value="" />
				</div>
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; overflow:auto;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			document.getElementById('razonSocial').focus();
		</script>
	</body>
</html>