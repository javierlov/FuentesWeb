<?
if (!hasPermiso(91)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

require_once("estadisticas_combos.php");
?>
<link href="/modules/mantenimiento/css/estadisticas.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/estadisticas.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/estadisticas/ver_estadisticas.php" id="formVerEstadisticas" method="post" name="formVerEstadisticas" target="iframeProcesando">
	<input id="anoDesde2Default" name="anoDesde2Default" type="hidden" value="<?= getYearFromDate(incMonths(date("d/m/Y"), -2))?>" />
	<input id="anoDesde3Default" name="anoDesde3Default" type="hidden" value="<?= getYearFromDate(incMonths(date("d/m/Y"), -5))?>" />
	<input id="anoDesde4Default" name="anoDesde4Default" type="hidden" value="<?= getYearFromDate(incMonths(date("d/m/Y"), -11))?>" />
	<input id="anoHastaDefault" name="anoHastaDefault" type="hidden" value="<?= date("Y")?>" />
	<input id="fechaDesdeDefault" name="fechaDesdeDefault" type="hidden" value="<?= incDays(date("d/m/Y"), -6)?>" />
	<input id="fechaHastaDefault" name="fechaHastaDefault" type="hidden" value="<?= date("d/m/Y")?>" />
	<input id="mesDesde2Default" name="mesDesde2Default" type="hidden" value="<?= getMonthFromDate(incMonths(date("d/m/Y"), -2))?>" />
	<input id="mesDesde3Default" name="mesDesde3Default" type="hidden" value="<?= getMonthFromDate(incMonths(date("d/m/Y"), -5))?>" />
	<input id="mesDesde4Default" name="mesDesde4Default" type="hidden" value="<?= getMonthFromDate(incMonths(date("d/m/Y"), -11))?>" />
	<input id="mesHastaDefault" name="mesHastaDefault" type="hidden" value="<?= date("m")?>" />
	<div id="divEstadisticas">
		<div class="fila">
			<label for="tipo">Tipo</label>
			<?= $comboTipo->draw();?>
		</div>

		<div id="divHoraria">
			<div class="fila">
				<label for="horaDesdeHoraria">Hora Desde</label>
				<?= $comboHoraDesdeHoraria->draw();?>
			</div>
			<div class="fila">
				<label for="horaHastaHoraria">Hora Hasta</label>
				<?= $comboHoraHastaHoraria->draw();?>
			</div>
		</div>

		<div id="divDiaria">
			<div class="fila">
				<label for="fechaDesdeDiaria">Fecha Desde</label>
				<input class="fecha" id="fechaDesdeDiaria" maxlength="10" name="fechaDesdeDiaria" type="text" value="<?= incDays(date("d/m/Y"), -7)?>" />
				<input class="botonFecha" id="btnFechaDesdeDiaria" name="btnFechaDesdeDiaria" type="button" value="" />
			</div>
			<div class="fila">
				<label for="fechaHastaDiaria">Fecha Hasta</label>
				<input class="fecha" id="fechaHastaDiaria" maxlength="10" name="fechaHastaDiaria" type="text" value="<?= date("d/m/Y")?>" />
				<input class="botonFecha" id="btnFechaHastaDiaria" name="btnFechaHastaDiaria" type="button" value="" />
			</div>
		</div>

		<div id="divMensual">
			<div class="fila">
				<label for="mesDesdeMensual">Mes Desde</label>
				<?= $comboMesDesdeMensual->draw();?>
				<label for="anoDesdeMensual">Año Desde</label>
				<input class="fecha" id="anoDesdeMensual" maxlength="4" name="anoDesdeMensual" type="text" value="" />
			</div>
			<div class="fila">
				<label for="mesHastaMensual">Mes Hasta</label>
				<?= $comboMesHastaMensual->draw();?>
				<label for="anoHastaMensual">Año Hasta</label>
				<input class="fecha" id="anoHastaMensual" maxlength="4" name="anoHastaMensual" type="text" value="" />
			</div>
		</div>

		<div id="divAnual">
			<div class="fila">
				<label for="anoDesdeAnual">Año Desde</label>
				<input class="fecha" id="anoDesdeAnual" maxlength="4" name="anoDesdeAnual" type="text" value="" />
			</div>
			<div class="fila">
				<label for="anoHastaAnual">Año Hasta</label>
				<input class="fecha" id="anoHastaAnual" maxlength="4" name="anoHastaAnual" type="text" value="" />
			</div>
		</div>

		<div class="fila" id="divDias">
			<label id="labelDias">Días</label>
			<input checked id="dias" name="dias" type="radio" value="t" />
			<label for="dias" id="labelDiasItem">Todos</label>
			<input id="dias" name="dias" type="radio" value="h" />
			<label for="dias" id="labelDiasItem">Días Hábiles</label>
		</div>

		<div class="fila">
			<label id="labelValor">Valor</label>
			<input checked id="valor" name="valor" type="radio" value="c" />
			<label for="valor" id="labelValorItem">Cantidad de Usuarios</label>
			<input id="valor" name="valor" type="radio" value="t" />
			<label for="valor" id="labelValorItem">Tiempo Promedio</label>
		</div>
	</div>

	<div id="divBusquedasRapidas">
		<div id="divBusquedasRapidasTitulo"><b>Búsquedas rápidas</b></div>
		<a href="#" onClick="busquedaRapida(1)">Última Semana</a>
		<br />
		<a href="#" onClick="busquedaRapida(2)">Últimos 3 Meses</a>
		<br />
		<a href="#" onClick="busquedaRapida(3)">Últimos 6 Meses</a>
		<br />
		<a href="#" onClick="busquedaRapida(4)">Últimos 12 Meses</a>
		<br />
	</div>
	<div id="divNada"></div>

	<div id="divBotones">
		<input id="btnAplicar" name="btnAplicar" type="submit" value="" onClick="aplicar()" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
	</div>

	<div id="divErroresForm">
		<img src="/images/atencion.png" />
		<span>No es posible continuar mientras no se corrijan los siguientes errores:</span>
		<br />
		<br />
		<span id="errores"></span>
		<input id="foco" name="foco" readonly type="checkbox" />
	</div>

	<div id="divResultados"></div>

	<div id="divResultadosArteria"></div>

	<div id="divResultadosArticulos"></div>

	<div id="divResultadosBeneficios"></div>

	<div id="divResultadosComentarios"></div>
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaDesdeDiaria",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesdeDiaria"
	});
	Calendar.setup ({
		inputField: "fechaHastaDiaria",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHastaDiaria"
	});
</script>