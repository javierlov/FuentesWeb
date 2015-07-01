<?
if (!hasPermiso(24)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"] = array("ob" => "3",
																									"habilitarComentarios" => false,
																									"mostrarEnPortada" => false,
																									"pagina" => 1,
																									"sb" => false,
																									"titulo" => "",
																									"vigenciaDesde" => "",
																									"vigenciaHasta" => "",
																									"volanta" => "");
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formBuscarArticulo').submit();
	}
</script>
<link href="/modules/mantenimiento/css/abm_articulos.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_articulos.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_articulos/buscar_articulo_busqueda.php" id="formBuscarArticulo" method="post" name="formBuscarArticulo" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="titulo">Título</label>
			<input autofocus id="titulo" maxlength="50" name="titulo" type="text" value="<?= $_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["titulo"]?>" />
		</div>
		<div class="fila">
			<label for="volanta">Volanta</label>
			<input id="volanta" maxlength="30" name="volanta" type="text" value="<?= $_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["volanta"]?>" />
		</div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= $_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["vigenciaDesde"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
			<label for="vigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= $_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["vigenciaHasta"]?>" />
			<input class="botonFecha" id="btnVigenciaHasta" name="btnVigenciaHasta" type="button" value="" />
		</div>
		<div class="fila">
			<label for="mostrarEnPortada">Mostrar en Portada</label>
			<input <?= ($_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["mostrarEnPortada"])?"checked":""?> id="mostrarEnPortada" name="mostrarEnPortada" type="checkbox" value="ok" />
			<label for="habilitarComentarios" id="labelHabilitarComentariosBusqueda">Habilitar Comentarios</label>
			<input <?= ($_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["habilitarComentarios"])?"checked":""?> id="habilitarComentarios" name="habilitarComentarios" type="checkbox" value="ok" />
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