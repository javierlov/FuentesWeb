<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/busqueda_empleados/busqueda_empleados.php");


if (!hasPermiso(10)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_NOVEDAD"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_NOVEDAD"] = array("empleadoLista" => "",
																				"ob" => "2",
																				"pagina" => 1,
																				"sb" => false,
																				"tipoMovimiento" => -1,
																				"vigenciaDesde" => "",
																				"vigenciaHasta" => "");
require_once("buscar_novedad_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_novedades.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_novedades.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_novedades/buscar_novedad_busqueda.php" id="formBuscarNovedad" method="post" name="formBuscarNovedad" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div id="divCampos">
		<div class="fila">
			<label for="empleadoLista">Usuario</label>
			<? busquedaEmpleadosAgregarCodigo($_SESSION["BUSQUEDA_NOVEDAD"]["empleadoLista"], "left:97px; width:400px;")?>
		</div>
		<div class="fila">
			<label for="tipoMovimientoBusqueda">Tipo Movimiento</label>
			<?= $comboTipoMovimientoBusqueda->draw();?>
		</div>
		<div class="fila">
			<label for="vigenciaDesdeBusqueda">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesdeBusqueda" maxlength="10" name="vigenciaDesdeBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_NOVEDAD"]["vigenciaDesde"]?>" />
			<input class="botonFecha" id="btnVigenciaDesdeBusqueda" name="btnVigenciaDesdeBusqueda" type="button" value="" />
			<label for="vigenciaHastaBusqueda" id="labelVigenciaHastaBusqueda">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHastaBusqueda" maxlength="10" name="vigenciaHastaBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_NOVEDAD"]["vigenciaHasta"]?>" />
			<input class="botonFecha" id="btnVigenciaHastaBusqueda" name="btnVigenciaHastaBusqueda" type="button" value="" />
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
		inputField: "vigenciaDesdeBusqueda",
		ifFormat  : "%d/%m/%Y",
		button    : "btnVigenciaDesdeBusqueda"
	});
	Calendar.setup ({
		inputField: "vigenciaHastaBusqueda",
		ifFormat  : "%d/%m/%Y",
		button    : "btnVigenciaHastaBusqueda"
	});

<?
if ($buscar) {
?>
	submitFormBusqueda();
<?
}
?>
</script>