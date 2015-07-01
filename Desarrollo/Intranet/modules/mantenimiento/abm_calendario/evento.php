<?
if (!hasPermiso(82)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM rrhh.rcl_calendario
			WHERE cl_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}

require_once("evento_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_calendario.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_calendario.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_calendario/guardar_evento.php" id="formAbmEvento" method="post" name="formAbmEvento" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="texto">Texto Evento</label>
			<input autofocus id="texto" maxlength="255" name="texto" type="text" value="<?= ($isAlta)?"":$row["CL_TEXTOEVENTO"]?>">
		</div>
		<div class="fila">
			<label for="fecha">Fecha Evento</label>
			<input class="fecha" id="fecha" maxlength="10" name="fecha" type="text" value="<?= ($isAlta)?"":$row["CL_FECHAEVENTO"]?>" />
			<input class="botonFecha" id="btnFecha" name="btnFecha" type="button" value="" />
		</div>
		<div class="fila">
			<label for="link">Link</label>
			<input id="link" maxlength="255" name="link" type="text" value="<?= ($isAlta)?"":$row["CL_LINK"]?>">
		</div>
		<div class="fila">
			<label for="destino">Destino</label>
			<?= $comboDestino->draw();?>
		</div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= ($isAlta)?"":$row["CL_FECHAVIGENCIADESDE"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
			<label for="vigenciaHasta" id="labelVigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= ($isAlta)?"":$row["CL_FECHAVIGENCIAHASTA"]?>" />
			<input class="botonFecha" id="btnVigenciaHasta" name="btnVigenciaHasta" type="button" value="" />
		</div>
		<div class="fila">
			<label for="vistaPrevia">Vista Previa</label>
			<input <?= ($isAlta)?"":(($row["CL_VISTAPREVIA"] == "S")?"checked":"")?> id="vistaPrevia" name="vistaPrevia" type="checkbox" value="ok" />
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
		inputField: "fecha",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFecha"
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

	cambiarTipoMovimiento();
</script>