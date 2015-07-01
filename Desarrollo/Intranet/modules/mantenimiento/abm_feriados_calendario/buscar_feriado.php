<?
if (!hasPermiso(81)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este m�dulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"] = array("delegacion" => -1,
																										"fechaDesde" => "",
																										"fechaHasta" => "",
																										"ob" => "2",
																										"pagina" => 1,
																										"sb" => false);
require_once("buscar_feriado_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_feriados_calendario.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_feriados_calendario.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_feriados_calendario/buscar_feriado_busqueda.php" id="formBuscarFeriado" method="post" name="formBuscarFeriado" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div id="divCampos">
		<div class="fila">
			<label for="delegacionBusqueda">Delegaci�n</label>
			<?= $comboDelegacionBusqueda->draw();?>
		</div>
		<div class="fila">
			<label for="fechaDesdeBusqueda">Fecha Feriado Desde</label>
			<input class="fecha" id="fechaDesdeBusqueda" maxlength="10" name="fechaDesdeBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"]["fechaDesde"]?>" />
			<input class="botonFecha" id="btnFechaDesdeBusqueda" name="btnFechaDesdeBusqueda" type="button" value="" />
			<label for="fechaHastaBusqueda" id="labelFechaHastaBusqueda">Fecha Feriado Hasta</label>
			<input class="fecha" id="fechaHastaBusqueda" maxlength="10" name="fechaHastaBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"]["fechaHasta"]?>" />
			<input class="botonFecha" id="btnFechaHastaBusqueda" name="btnFechaHastaBusqueda" type="button" value="" />
		</div>
	</div>
	<div id="divBotones">
		<input id="btnAgregar" name="btnAgregar" type="button" value="" onClick="agregar()" />
		<input id="btnBuscar" name="btnBuscar" type="submit" value="" onClick="submitFormBusqueda()" />
	</div>

	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-top:8px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img id="imgGrillaCargando" src="/images/grilla/grid_cargando.gif" title="Espere por favor..."></div>
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaDesdeBusqueda",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesdeBusqueda"
	});
	Calendar.setup ({
		inputField: "fechaHastaBusqueda",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHastaBusqueda"
	});

<?
if ($buscar) {
?>
	submitFormBusqueda();
<?
}
?>
</script>