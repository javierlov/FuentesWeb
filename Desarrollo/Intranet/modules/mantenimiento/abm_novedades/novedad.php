<?
if (!hasPermiso(79)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM rrhh.rhn_novedades
			WHERE hn_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}

require_once("novedad_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_novedades.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_novedades.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_novedades/guardar_novedad.php" id="formAbmNovedad" method="post" name="formAbmNovedad" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="usuario">Usuario</label>
			<?= $comboUsuario->draw();?>
		</div>
		<div class="fila">
			<label for="tipoMovimiento">Tipo Movimiento</label>
			<?= $comboTipoMovimiento->draw();?>
		</div>
		<div class="fila" id="divSectorDesde">
			<label for="sectorDesde">Sector Desde</label>
			<?= $comboSectorDesde->draw();?>
		</div>
		<div class="fila" id="divSectorHasta">
			<label for="sectorHasta">Sector Hasta</label>
			<?= $comboSectorHasta->draw();?>
		</div>
		<div class="fila">
			<label for="vigenciaDesde">Vigencia Desde</label>
			<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" type="text" value="<?= ($isAlta)?"":$row["HN_FECHAVIGENCIADESDE"]?>" />
			<input class="botonFecha" id="btnVigenciaDesde" name="btnVigenciaDesde" type="button" value="" />
			<label for="vigenciaHasta" id="labelVigenciaHasta">Vigencia Hasta</label>
			<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" type="text" value="<?= ($isAlta)?"":$row["HN_FECHAVIGENCIAHASTA"]?>" />
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

	cambiarTipoMovimiento();
</script>