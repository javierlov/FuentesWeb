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
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script src="/js/grid.js" type="text/javascript"></script>
	</head>
	<body style="margin:0; padding:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/usuarios_registrados/clientes/nomina_de_trabajadores/agregar_establecimiento_busqueda.php" id="formBuscarEstablecimiento" method="post" name="formBuscarEstablecimiento" target="iframeProcesando">
			<input id="rl" name="rl" type="hidden" value="<?= $_REQUEST["rl"]?>">
			<div style="background-color:#ecf5ff; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:12px;">
					<label class="Text5" for="codigo">Nombre</label>
					<input id="nombre" maxlength="255" name="nombre" style="width:400px;" type="text" value="" />
					<input class="btnBuscar" style="margin-left:16px; vertical-align:-3px;" type="submit" value="" />
				</div>
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:320px; overflow:auto;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			document.getElementById('nombre').focus();
		</script>
	</body>
</html>