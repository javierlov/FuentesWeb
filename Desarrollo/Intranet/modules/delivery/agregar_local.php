<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/delivery/guardar_local.php" id="formAgregarLocal" method="post" name="formAgregarLocal" target="iframeProcesando">
	<input id="id" name="id" type="hidden" value="" />
	<input id="tmpNombre" name="tmpNombre" type="hidden" value="" />
	<div id="divFormAgregarLocal">
		<div class="fila titulo" id="divTitulo">NUEVO ESTABLECIMIENTO</div>
		<div class="fila fondo1">
			<label for="nombre">NOMBRE</label>
			<input autofocus id="nombre" maxlength="255" name="nombre" type="text" value="" />
		</div>
		<div class="fila fondo2">
			<label for="direccion">DIRECCIÓN</label>
			<input id="direccion" maxlength="255" name="direccion" type="text" value="" />
		</div>
		<div class="fila fondo1">
			<label for="telefono">TELÉFONO</label>
			<input id="telefono" maxlength="255" name="telefono" type="text" value="" />
		</div>
		<div class="fila fondo2">
			<label for="link">LINK</label>
			<input id="link" maxlength="255" name="link" type="text" value="" />
		</div>
<?
if ((getUserSector() == "RRHH") or (getWindowsLoginName() == "alapaco")) {
?>
		<div class="fila fondo1">
			<label for="autorizado">AUTORIZADO</label>
			<input id="autorizado" name="autorizado" type="checkbox" value="ok" />
		</div>
<?
}
?>
		<div id="divBotones">
			<input id="btnDarBaja" name="btnDarBaja" type="button" onClick="darBaja()" />
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
	</div>
</form>