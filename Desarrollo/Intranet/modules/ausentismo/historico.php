<!--ZOOMSTOP-->
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


$buscar = isset($_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"]);
if (!$buscar)
	$_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"] = array("empleado" => -1,
																										 "fechaAvisoDesde" => valorSql("SELECT TO_CHAR(actualdate - 30, 'dd/mm/yyyy') FROM DUAL"),
																										 "fechaAvisoHasta" => "",
																										 "ob" => "3_D_",
																										 "motivo" => -1,
																										 "pagina" => 1);
require_once("historico_combos.php");
?>
<script type="text/javascript">
	function submitForm() {
		document.getElementById('divContentGrid').style.display = 'none';
		document.getElementById('divProcesando').style.display = 'block';
		document.getElementById('formHistorico').submit();
	}
</script>
<link href="/modules/ausentismo/css/ausentismo_historico.css" rel="stylesheet" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/ausentismo/historico_busqueda.php" id="formHistorico" method="post" name="formHistorico" target="iframeProcesando" onSubmit="return ValidarForm(formHistorico)">
	<input id="buscar" name="buscar" type="hidden" value="yes" />
	<div class="fila">
		<label id="labelFechaAvisoDesde">Fecha Aviso Desde</label>
		<input autofocus class="fecha" id="fechaAvisoDesde" maxlength="10" name="fechaAvisoDesde" title="Fecha Aviso Desde" type="text" value="<?= $_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"]["fechaAvisoDesde"]?>" />
		<input class="botonFecha" id="btnFechaAvisoDesde" name="btnFechaAvisoDesde" type="button" value="" />
		<label id="labelFechaAvisoHasta">Fecha Aviso Hasta</label>
		<input class="fecha" id="fechaAvisoHasta" maxlength="10" name="fechaAvisoHasta" title="Fecha Aviso Hasta" type="text" value="<?= $_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"]["fechaAvisoHasta"]?>" />
		<input class="botonFecha" id="btnFechaAvisoHasta" name="btnFechaAvisoHasta" type="button" value="" />
	</div>
	<div class="fila">
		<label>Empleado Ausente</label>
		<?= $comboEmpleado->draw();?>
		<label id="labelMotivo">Motivo</label>
		<?= $comboMotivo->draw();?>
	</div>
	<div id="divBotones">
		<input class="btnBuscar" id="btnBuscar" name="btnBuscar" type="submit" value="" />
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-top:8px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img id="imgGrillaCargando" src="/images/grilla/grid_cargando.gif" title="Espere por favor..."></div>
</form>
<script>
	Calendar.setup ({
		inputField: "fechaAvisoDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaAvisoDesde"
	});
	Calendar.setup ({
		inputField: "fechaAvisoHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaAvisoHasta"
	});

	submitForm();
</script>
<!--ZOOMRESTART-->