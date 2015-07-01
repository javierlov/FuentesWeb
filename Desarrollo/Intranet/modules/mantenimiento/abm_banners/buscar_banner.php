<?
if (!hasPermiso(88)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_BANNER_BUSQUEDA"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_BANNER_BUSQUEDA"] = array("ob" => "2",
																								"pagina" => 1,
																								"sb" => false,
																								"vigenciaDesde" => "",
																								"vigenciaHasta" => "");
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formBuscarBanner').submit();
	}
</script>
<link href="/modules/mantenimiento/css/abm_banners.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_banners.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_banners/buscar_banner_busqueda.php" id="formBuscarBanner" method="post" name="formBuscarBanner" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input autofocus class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= $_SESSION["BUSQUEDA_BANNER_BUSQUEDA"]["vigenciaDesde"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
		</div>
		<div class="fila">
			<label for="vigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= $_SESSION["BUSQUEDA_BANNER_BUSQUEDA"]["vigenciaHasta"]?>" />
			<input class="botonFecha" id="btnVigenciaHasta" name="btnVigenciaHasta" type="button" value="" />
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
	Calendar.setup (
		{
			inputField: "vigenciaDesde",
			ifFormat  : "%d/%m/%Y",
			button    : "btnVigenciaDesde"
		}
	);
	Calendar.setup (
		{
			inputField: "vigenciaHasta",
			ifFormat  : "%d/%m/%Y",
			button    : "btnVigenciaHasta"
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