<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once("buscar_trabajador_combos.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/grid.js" type="text/javascript"></script>
		<script type="text/javascript">
			function submitForm() {
				document.getElementById('divContentGrid').style.display = 'none';
				document.getElementById('divProcesando').style.display = 'block';
			}
		</script>
	</head>
	<body style="margin:0; padding:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/usuarios_registrados/clientes/denuncias_de_siniestros/buscar_trabajador_busqueda.php" id="formBuscarTrabajador" method="post" name="formBuscarTrabajador" target="iframeProcesando" onSubmit="submitForm()">
			<div style="background-color:#ecf5ff; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:12px;">
					<label class="Text5" for="nombre">Nombre</label>
					<input autofocus id="nombre" maxlength="128" name="nombre" style="text-transform:uppercase; width:400px;" type="text" value="" />
					<label class="Text5" for="cuil">C.U.I.L.</label>
					<input id="cuil" maxlength="13" name="cuil" style="width:96px;" type="text" value="" />
				</div>
				<div>
					<label class="Text5" for="establecimiento">Establecimiento</label>
					<?= $comboEstablecimiento->draw();?>
					<div align="right"><input class="btnBuscar" style="margin-right:16px; vertical-align:-3px;" type="submit" value="" /></div>
				</div>
			</div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:320px; overflow:auto;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	</body>
</html>