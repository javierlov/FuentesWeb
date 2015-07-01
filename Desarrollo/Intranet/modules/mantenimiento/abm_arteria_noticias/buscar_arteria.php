<?
if (!hasPermiso(64)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"] = array("ano" => "",
																								 "fechaPublicacionDesde" => "",
																								 "fechaPublicacionHasta" => "",
																								 "numero" => "",
																								 "ob" => "2",
																								 "pagina" => 1,
																								 "sb" => false,
																								 "vigenciaDesde" => "",
																								 "vigenciaHasta" => "");
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formBuscarArteria').submit();
	}
</script>
<link href="/modules/mantenimiento/abm_arteria_noticias/css/abm_arteria.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/abm_arteria_noticias/js/boletin.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_arteria_noticias/buscar_arteria_busqueda.php" id="formBuscarArteria" method="post" name="formBuscarArteria" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="ano">Año</label>
			<input autofocus id="ano" maxlength="4" name="ano" type="text" value="<?= $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["ano"]?>" />
		</div>
		<div class="fila">
			<label for="numero">Número</label>
			<input id="numero" maxlength="4" name="numero" type="text" value="<?= $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["numero"]?>" />
		</div>
		<div class="fila">
			<label for="fechaPublicacionDesde">Fecha Publicación Desde</label>
			<input class="fecha" id="fechaPublicacionDesde" maxlength="10" name="fechaPublicacionDesde" type="text" value="<?= $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["fechaPublicacionDesde"]?>" />
			<input class="botonFecha" id="btnFechaPublicacionDesde" name="btnFechaPublicacionDesde" type="button" value="" />
		</div>
		<div class="fila">
			<label for="fechaPublicacionHasta">Fecha Publicación Hasta</label>
			<input class="fecha" id="fechaPublicacionHasta" maxlength="10" name="fechaPublicacionHasta" type="text" value="<?= $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["fechaPublicacionHasta"]?>" />
			<input class="botonFecha" id="btnFechaPublicacionHasta" name="btnFechaPublicacionHasta" type="button" value="" />
		</div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["vigenciaDesde"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
		</div>
		<div class="fila">
			<label for="vigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["vigenciaHasta"]?>" />
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
	Calendar.setup ({
		inputField: "fechaPublicacionDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaPublicacionDesde"
	});
	Calendar.setup ({
		inputField: "fechaPublicacionHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaPublicacionHasta"
	});
	Calendar.setup ({
		inputField: "vigenciaDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnVigenciaDesde"
	});
	Calendar.setup ({
		inputField: "vigenciaHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnVigenciaHasta"
	});

<?
if ($buscar) {
?>
	submitForm();
<?
}
?>
</script>