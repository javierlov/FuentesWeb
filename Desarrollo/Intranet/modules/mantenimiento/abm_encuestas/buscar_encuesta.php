<?
if (!hasPermiso(48)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"] = array("activa" => false,
																									"detalle" => "",
																									"ob" => "3",
																									"pagina" => 1,
																									"sb" => false,
																									"titulo" => "",
																									"vigenciaDesde" => "",
																									"vigenciaHasta" => "");
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formBuscarEncuesta').submit();
	}
</script>
<link href="/modules/mantenimiento/css/abm_encuestas.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_encuestas.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_encuestas/buscar_encuesta_busqueda.php" id="formBuscarEncuesta" method="post" name="formBuscarEncuesta" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="tituloBusqueda">Título</label>
			<input autofocus id="tituloBusqueda" maxlength="64" name="tituloBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]["titulo"]?>" />
		</div>
		<div class="fila">
			<label for="detalleBusqueda">Detalle</label>
			<input id="detalleBusqueda" maxlength="64" name="detalleBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]["detalle"]?>" />
		</div>
		<div class="fila">
			<label for="activaBusqueda">Encuesta Activa</label>
			<input <?= ($_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]["activa"])?"checked":""?> id="activaBusqueda" name="activaBusqueda" type="checkbox" value="ok" />
		</div>
		<div class="fila">
			<label for="vigenciaDesdeBusqueda">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesdeBusqueda" maxlength="10" name="vigenciaDesdeBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]["vigenciaDesde"]?>" />
			<input class="botonFecha" id="btnVigenciaDesdeBusqueda" name="btnVigenciaDesdeBusqueda" type="button" value="" />
			<label for="vigenciaHastaBusqueda" id="labelVigenciaHastaBusqueda">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHastaBusqueda" maxlength="10" name="vigenciaHastaBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]["vigenciaHasta"]?>" />
			<input class="botonFecha" id="btnVigenciaHastaBusqueda" name="btnVigenciaHastaBusqueda" type="button" value="" />
		</div>
	</div>
	<div id="divBotones">
		<input id="btnAgregar" name="btnAgregar" type="button" value="" onClick="agregar()" />
		<input id="btnBuscar" name="btnBuscar" type="submit" value="" onClick="submitForm()" />
	</div>

	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-top:8px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img id="imgGrillaCargando" src="/images/grilla/grid_cargando.gif" title="Espere por favor..."></div>
</form>
<script type="text/javascript">
	Calendar.setup (
		{
			inputField: "vigenciaDesdeBusqueda",
			ifFormat  : "%d/%m/%Y",
			button    : "btnVigenciaDesdeBusqueda"
		}
	);
	Calendar.setup (
		{
			inputField: "vigenciaHastaBusqueda",
			ifFormat  : "%d/%m/%Y",
			button    : "btnVigenciaHastaBusqueda"
		}
	);

<?
if ($buscar) {
?>
	submitForm();
<?
}
?>
</script>