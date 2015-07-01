<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 100));

if (!isset($_SESSION["BUSQUEDA_RGRL"]))
	$_SESSION["BUSQUEDA_RGRL"] = array("buscar" => "S",
																		 "ob" => "1",
																		 "pagina" => 1);
?>
<script src="/modules/usuarios_registrados/clientes/js/rgrl.js" type="text/javascript"></script>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formRgrl').submit();
	}
</script>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/rgrl/index_busqueda.php" id="formRgrl" method="post" name="formRgrl" target="iframeProcesando">
	<div class="TituloSeccion" style="display:block; width:728px;">RGRL (Res. 463/09)</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:10px; margin-top:8px; overflow:auto; width:720px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<div style="margin-top:8px;"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></div>
</form>
<script>
	submitForm();
</script>