<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(($_SESSION["isAdminTotal"]) or (validarPermisoClienteXModulo($_SESSION["idUsuario"], 66)));
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
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
		<form action="/modules/usuarios_registrados/clientes/administracion_responsables_contrato/buscar_empresa_busqueda.php" id="formBuscarEmpresa" method="post" name="formBuscarEmpresa" target="iframeProcesando" onSubmit="submitForm()">
			<input id="a" name="a" type="hidden" value="<?= $_REQUEST["a"]?>">
			<div style="background-color:#49bdec; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:12px;">
					<label class="Text5" for="codigo">Nombre</label>
					<input id="nombre" maxlength="255" name="nombre" style="width:192px;" type="text" value="" />
					<label class="Text5" for="descripcion" style="margin-left:16px;">C.U.I.T.</label>
					<input id="cuit" maxlength="13" name="cuit" style="width:88px;" type="text" value="" />
					<label class="Text5" for="descripcion" style="margin-left:16px;">Contrato</label>
					<input id="contrato" maxlength="8" name="contrato" style="width:64px;" type="text" value="" />
					<input class="btnBuscar" style="margin-left:32px; vertical-align:-5px;" type="submit" value="" />
				</div>
			</div>
		</form>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:320px; overflow:auto;"></div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:16px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
		<script type="text/javascript">
			document.getElementById('nombre').focus();
		</script>
	</body>
</html>