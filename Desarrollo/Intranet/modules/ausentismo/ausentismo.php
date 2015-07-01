<!--ZOOMSTOP-->
<?
require_once("ausentismo_combos.php");
?>
<link href="/modules/ausentismo/css/ausentismo.css" rel="stylesheet" type="text/css" />
<script src="/modules/ausentismo/js/ausentismo.js" type="text/javascript"></script>
<iframe id="iframeAusentismo" name="iframeAusentismo" src="" style="display:none;"></iframe>
<form action="/modules/ausentismo/procesar_ausentismo.php" id="formAusentismo" method="post" name="formAusentismo" target="iframeAusentismo" onSubmit="return validarFormulario(formAusentismo)">
	<input id="caracteres2" name="caracteres2" size="4" type="hidden" />
	<div align="center" id="ausentismoTitulo">En esta pantalla sólo se deben informar aquellas ausencias totales y no solicitadas previamente a RR.HH.</div>
	<div id="ausentismoLineaHorizontal">
		<img id="imgUsuario" src="/modules/ausentismo/images/usuario.jpg" />
		<span>Usuario Actual</span>
		<span id="spanUsuarioActual"><?= getUserName() ?></span>
<?
$usuario = getWindowsLoginName(true);
if ((getUserSector() == "RRHH") or ($usuario == "ALAPACO") or ($usuario == "AANGIOLILLO") or ($usuario == "JBALESTRINI")) {
?>
		<span id="spanBotonesEspeciales">
			<a href="/ausentismo-gestion"><img src="/modules/ausentismo/images/administrador.jpg" title="Gestión" /></a>
			<a href="/ausentismo-historico"><img src="/modules/ausentismo/images/historico.jpg" title="Histórico" /></a>
		</span>
<?
}
?>
	</div>
	<div class="fila">
		<label id="labelEmpleadoAusente">Empleado Ausente</label>
		<?= $comboEmpleadoAusente->draw();?>
	</div>
	<div class="fila">
		<label>Motivo de Ausencia</label>
		<?= $comboMotivoAusencia->draw();?>
	</div>
	<div class="fila">
		<label id="labelObservaciones">Observaciones</label>
		<textarea id="observaciones" name="observaciones" onKeyDown="valida_longitud(this)" onKeyUp="valida_longitud(this)"></textarea>
		<span id="labelObservacionesDetalle">(Detalle brevemente un comentario de la ausencia)</span>
	</div>
	<div id="trEnviarMedico">
		<label id="labelEnviarMedico">Enviar Médico</label>
		<?= $comboEnviarMedico->draw();?>
	</div>
	<div id="trJustifique">
		<label id="labelJustifique">Justifique</label>
		<textarea id="justifique" name="justifique" onKeyDown="valida_longitud(this)" onKeyUp="valida_longitud(this)"></textarea>
	</div>
	<div id="divBotones">
		<input id="btnEnviar" name="btnEnviar" type="submit" value="" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
	</div>
	<div id="divLineamientos">
		<a href="/modules/ausentismo/lineamientos_ausentismo_empleados.pdf" target="_blank"><img src="/modules/ausentismo/images/lineamientos.jpg" /></a>
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
<!--ZOOMRESTART-->