<?
if (!hasPermiso(1)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_USUARIO_BUSQUEDA"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_USUARIO_BUSQUEDA"] = array("legajoBusqueda" => "",
																								 "nombreBusqueda" => "",
																								 "ob" => "2",
																								 "pagina" => 1,
																								 "sb" => false);
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formBuscarUsuario').submit();
	}
</script>
<link href="/modules/mantenimiento/css/abm_usuarios.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_usuarios.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_usuarios/buscar_usuario_busqueda.php" id="formBuscarUsuario" method="post" name="formBuscarUsuario" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div id="divCampos">
		<div class="fila">
			<label for="nombreBusqueda">Nombre</label>
			<input autofocus id="nombreBusqueda" maxlength="64" name="nombreBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_USUARIO_BUSQUEDA"]["nombreBusqueda"]?>" />
		</div>
		<div class="fila">
			<label for="legajoBusqueda">Legajo</label>
			<input id="legajoBusqueda" maxlength="8" name="legajoBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_USUARIO_BUSQUEDA"]["legajoBusqueda"]?>" />
		</div>
	</div>
	<div id="divBotones">
		<input id="btnBuscar" name="btnBuscar" type="submit" value="" onClick="submitForm()" />
	</div>

	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-top:8px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img id="imgGrillaCargando" src="/images/grilla/grid_cargando.gif" title="Espere por favor..."></div>
</form>
<script>
<?
if ($buscar) {
?>
	submitForm();
<?
}
?>
</script>