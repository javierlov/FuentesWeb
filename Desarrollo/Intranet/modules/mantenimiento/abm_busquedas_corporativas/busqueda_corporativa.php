<?
if (!hasPermiso(80)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM rrhh.rbc_busquedascorporativas
			WHERE bc_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	if ($row["BC_NOMBREARCHIVO"] != "") {
		$partesFile = pathinfo($row["BC_NOMBREARCHIVO"]);
		$file = base64_encode(DATA_BUSQUEDAS_CORPORATIVAS_PATH.$_REQUEST["id"].".".$partesFile["extension"]);
	}
}

require_once("busqueda_corporativa_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_busquedas_corporativas.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_busquedas_corporativas.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_busquedas_corporativas/guardar_busqueda_corporativa.php" enctype="multipart/form-data" id="formAbmBusquedaCorporativa" method="post" name="formAbmBusquedaCorporativa" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="20000000">
	<div>
		<div class="fila">
			<label for="numero">Número</label>
			<input id="numero" maxlength="4" name="numero" readonly type="text" value="<?= ($isAlta)?"":$row["BC_ID"]?>" />
		</div>
		<div class="fila">
			<label for="puesto">Puesto</label>
			<input autofocus id="puesto" maxlength="128" name="puesto" type="text" value="<?= ($isAlta)?"":$row["BC_PUESTO"]?>" />
		</div>
		<div class="fila">
			<label for="empresa">Empresa</label>
			<?= $comboEmpresa->draw();?>
		</div>
		<div class="fila">
			<label for="estado">Estado</label>
			<?= $comboEstado->draw();?>
		</div>
		<div class="fila">
			<label for="archivo">Archivo</label>
			<input id="archivo" name="archivo" type="file" />
<?
if ((!$isAlta) and ($row["BC_NOMBREARCHIVO"] != "")) {
?>
			<a href="<?= "/archivo/".$file?>" id="aVerArchivoBusquedaCorporativa">Ver archivo "<?= $row["BC_NOMBREARCHIVO"]?>"</a>
			
<?
}
?>
		</div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= ($isAlta)?"":$row["BC_FECHAVIGENCIADESDE"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
			<label for="vigenciaHasta" id="labelVigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= ($isAlta)?"":$row["BC_FECHAVIGENCIAHASTA"]?>" />
			<input class="botonFecha" id="btnVigenciaHasta" name="btnVigenciaHasta" type="button" value="" />
		</div>
	</div>
	<div id="divBotones">
<?
if (!$isAlta) {
?>
		<input id="btnDarBaja" name="btnDarBaja" type="button" onClick="darBaja(<?= $_REQUEST["id"]?>)" />
<?
}
?>
		<input id="btnGuardar" name="btnGuardar" type="button" onClick="guardar()" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
		<input id="btnCancelar" name="btnCancelar" type="button" onClick="cancelar()" />
	</div>
	<div id="divErroresForm">
		<img src="/images/atencion.png" />
		<span>No es posible continuar mientras no se corrijan los siguientes errores:</span>
		<br />
		<br />
		<span id="errores"></span>
		<input id="foco" name="foco" readonly type="checkbox" />
	</div>
</form>

<div id="divFondo"></div>

<script type="text/javascript">
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
</script>