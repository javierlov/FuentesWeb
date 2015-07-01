<?
if (!hasPermiso(84)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_BENEFICIO_BUSQUEDA"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_BENEFICIO_BUSQUEDA"] = array("ob" => "2",
																									 "nombre" => "",
																									 "pagina" => 1,
																									 "sb" => false);
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formBuscarBeneficio').submit();
	}
</script>
<link href="/modules/mantenimiento/css/abm_beneficios.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_beneficios.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_beneficios/buscar_beneficio_busqueda.php" id="formBuscarBeneficio" method="post" name="formBuscarBeneficio" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="nombreBusqueda">Nombre</label>
			<input autofocus id="nombreBusqueda" maxlength="255" name="nombreBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_BENEFICIO_BUSQUEDA"]["nombre"]?>" />
		</div>
	</div>
	<div id="divBotones">
		<input id="btnAgregar" name="btnAgregar" type="button" value="" onClick="agregar()" />
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