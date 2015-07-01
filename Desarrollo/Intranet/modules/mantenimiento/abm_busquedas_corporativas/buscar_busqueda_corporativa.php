<?
if (!hasPermiso(63)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$buscar = isset($_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]);
if (!$buscar)
	$_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"] = array("empresaBusqueda" => -1,
																										 "estadoBusqueda" => -1,
																										 "ob" => "2",
																										 "pagina" => 1,
																										 "puestoBusqueda" => "",
																										 "sb" => false,
																										 "vigenciaDesde" => "",
																										 "vigenciaHasta" => "");
require_once("buscar_busqueda_corporativa_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_busquedas_corporativas.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_busquedas_corporativas.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_busquedas_corporativas/buscar_busqueda_corporativa_busqueda.php" id="formBuscarBusquedaCorporativa" method="post" name="formBuscarBusquedaCorporativa" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div id="divCampos">
		<div class="fila">
			<label for="puestoBusqueda">Puesto</label>
			<input autofocus id="puestoBusqueda" maxlength="64" name="puestoBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]["puestoBusqueda"]?>" />
		</div>
		<div class="fila">
			<label for="empresaBusqueda">Empresa</label>
			<?= $comboEmpresaBusqueda->draw();?>
		</div>
		<div class="fila">
			<label for="estadoBusqueda">Estado</label>
			<?= $comboEstadoBusqueda->draw();?>
		</div>
		<div class="fila">
			<label for="vigenciaDesdeBusqueda">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesdeBusqueda" maxlength="10" name="vigenciaDesdeBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]["vigenciaDesde"]?>" />
			<input class="botonFecha" id="btnVigenciaDesdeBusqueda" name="btnVigenciaDesdeBusqueda" type="button" value="" />
			<label for="vigenciaHastaBusqueda" id="labelVigenciaHastaBusqueda">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHastaBusqueda" maxlength="10" name="vigenciaHastaBusqueda" type="text" value="<?= $_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]["vigenciaHasta"]?>" />
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
	submitFormBusqueda();
<?
}
?>
</script>