<?
if (!hasPermiso(96)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$isAlta = ($_REQUEST["id"] == 0);

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT *
			 FROM comunes.cfd_feriadosdelegaciones
			WHERE fd_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}

require_once("feriado_combos.php");
?>
<link href="/modules/mantenimiento/css/abm_feriados_calendario.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_feriados_calendario.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/abm_feriados_calendario/guardar_feriado.php" id="formAbmFeriado" method="post" name="formAbmFeriado" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<div>
		<div class="fila">
			<label for="fecha">Fecha Feriado</label>
			<input autofocus class="fecha" id="fecha" maxlength="10" name="fecha" type="text" value="<?= ($isAlta)?"":$row["FD_FECHA"]?>" />
			<input class="botonFecha" id="btnFecha" name="btnFecha" type="button" value="" />
		</div>
		<div class="fila">
			<label for="destino">Delegación</label>
			<?= $comboDelegacion->draw();?>
		</div>
		<div class="fila">
			<label for="descripcion">Descripción</label>
			<input id="descripcion" maxlength="255" name="descripcion" type="text" value="<?= ($isAlta)?"":$row["FD_DESCRIPCION"]?>">
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
</script>