<?
if (!hasPermiso(81)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_CALENDARIO"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_CALENDARIO"] = array("fechaDesde" => "",
																					 "fechaHasta" => "",
																					 "ob" => "2",
																					 "pagina" => 1,
																					 "sb" => false,
																					 "texto" => "",
																					 "vigenciaDesde" => "",
																					 "vigenciaHasta" => "");
?>
<link href="/modules/mantenimiento/css/abm_calendario.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_calendario.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_calendario/buscar_evento_busqueda.php" id="formBuscarEvento" method="post" name="formBuscarEvento" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div id="divCampos">
		<div class="fila">
			<label for="textoBusqueda">Texto Evento</label>
			<input autofocus id="textoBusqueda" maxlength="50" name="textoBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_CALENDARIO"]["texto"]?>" />
		</div>
		<div class="fila">
			<label for="fechaDesdeBusqueda">Fecha Evento Desde</label>
			<input class="fecha" id="fechaDesdeBusqueda" maxlength="10" name="fechaDesdeBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_CALENDARIO"]["fechaDesde"]?>" />
			<input class="botonFecha" id="btnFechaDesdeBusqueda" name="btnFechaDesdeBusqueda" type="button" value="" />
			<label for="fechaHastaBusqueda" id="labelFechaHastaBusqueda">Fecha Evento Hasta</label>
			<input class="fecha" id="fechaHastaBusqueda" maxlength="10" name="fechaHastaBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_CALENDARIO"]["fechaHasta"]?>" />
			<input class="botonFecha" id="btnFechaHastaBusqueda" name="btnFechaHastaBusqueda" type="button" value="" />
		</div>
		<div class="fila">
			<label for="vigenciaDesdeBusqueda">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesdeBusqueda" maxlength="10" name="vigenciaDesdeBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_CALENDARIO"]["vigenciaDesde"]?>" />
			<input class="botonFecha" id="btnVigenciaDesdeBusqueda" name="btnVigenciaDesdeBusqueda" type="button" value="" />
			<label for="vigenciaHastaBusqueda" id="labelVigenciaHastaBusqueda">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHastaBusqueda" maxlength="10" name="vigenciaHastaBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_CALENDARIO"]["vigenciaHasta"]?>" />
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
		inputField: "fechaDesdeBusqueda",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesdeBusqueda"
	});
	Calendar.setup ({
		inputField: "fechaHastaBusqueda",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHastaBusqueda"
	});
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