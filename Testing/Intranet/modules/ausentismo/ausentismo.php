<script>
function cuenta(obj) {
	document.getElementById('caracteres2').value = obj.value.length;
} 

function mostrarEnviarMedico() {
	if (document.getElementById('MotivoAusencia').value == 1)
		document.getElementById('trEnviarMedico').style.visibility = 'visible';
	else {
		document.getElementById('trEnviarMedico').style.visibility = 'hidden';
		document.getElementById('enviarMedico').value = -1;
		document.getElementById('trJustifique').style.visibility = 'hidden';
	}
}

function mostrarJustificacion() {
	if (document.getElementById('enviarMedico').value == 'F')
		document.getElementById('trJustifique').style.visibility = 'visible';
	else {
		document.getElementById('trJustifique').style.visibility = 'hidden';
		document.getElementById('justifique').value = '';
	}
}

function ocultarMensajeOk() {
	document.getElementById('spanMensaje').style.display = 'none';
}

function valida_longitud(obj) {
	num_caracteres2 = obj.value.length;

	if (num_caracteres2 > num_caracteres2_permitidos)
		obj.value = contenido_textarea;
	else
		contenido_textarea = obj.value;

	if (num_caracteres2 >= num_caracteres2_permitidos)
		obj.style.color = "#f00";
	else
		obj.style.color = "#000";

	cuenta(obj);
}

function validarFormulario(formu) {
	if (!ValidarForm(formu))
		return false;

	if (document.getElementById('trEnviarMedico').style.visibility == 'visible') {
		field = document.getElementById('enviarMedico');
		if (field.value == -1) {
			alert('Por favor indique si hay que enviar médico o no.');
			field.focus();
			return false;
		}
	}

	if (document.getElementById('trJustifique').style.visibility == 'visible') {
		field = document.getElementById('justifique');
		if (field.value == '') {
			alert('Por favor indique porque no quiere enviar médico.');
			field.focus();
			return false;
		}
	}

	return true;
}

contenido_textarea = "";
num_caracteres2_permitidos = 255;

showTitle(true, 'PARTE DIARIO');
</script>
<iframe id="iframeAusentismo" name="iframeAusentismo" src="" style="display:none;"></iframe>
<form action="/modules/ausentismo/procesar_ausentismo.php" id="formAusentismo" method="post" name="formAusentismo" target="iframeAusentismo" onSubmit="return validarFormulario(formAusentismo)">
	<input id="caracteres2" name="caracteres2" size="4" type="hidden" />
	<div align="center" class="FormLabelBlanco11" style="background-color:#807f84; font-size:15px;">En esta pantalla sólo se deben informar aquellas ausencias totales y no solicitadas previamente a RR.HH.</div>
	<div class="LineaHorizontal" style="margin-top:8px;">
		<img border="0" src="/modules/ausentismo/images/usuario.jpg" style="vertical-align:-4px;" />
		<span class="FormLabelGrisChico">Usuario Actual</span>
		<span style="color:#00539b; font-size:14px;"><?= GetUserName() ?></span>
<?
$user = GetWindowsLoginName(true);
if ((GetUserSector() == "RRHH") or ($user == "ALAPACO") or ($user == "AANGIOLILLO")) {
?>
		<span style="float:right;">
			<a href="index.php?pageid=9"><img alt="Gestión" border="0" height="25" src="/modules/ausentismo/images/administrador.jpg" width="27"></a>
			<a href="index.php?pageid=8"><img alt="Histórico" border="0" height="25" src="/modules/ausentismo/images/historico.jpg" width="27"></a>
		</span>
<?
}
?>
	</div>
	<div style="margin-top:16px;">
		<img border="0" src="/modules/ausentismo/images/viñeta.jpg" />
		<label class="FormLabelGris10">Empleado Ausente</label>
		<select class="Combo" id="EmpleadoAusente" name="EmpleadoAusente" style="margin-left:12px;" title="Empleado Ausente" validar="true"></select>
	</div>
	<div style="margin-top:8px;">
		<img border="0" src="/modules/ausentismo/images/viñeta.jpg" />
		<label class="FormLabelGris10">Motivo de Ausencia</label>
		<select class="Combo" id="MotivoAusencia" name="MotivoAusencia" style="margin-left:8px;" title="Motivo de Ausencia" validar="true" onChange="mostrarEnviarMedico()"></select>
	</div>
	<div style="margin-top:8px;">
		<img border="0" src="/modules/ausentismo/images/viñeta.jpg" style="vertical-align:66px;" />
		<label class="FormLabelGris10" style="vertical-align:64px;">Observaciones</label>
		<textarea class="FormTextArea" id="Observaciones" name="Observaciones" style="height:80px; margin-left:34px; width:376px;" title="Observaciones" validar="true" onKeyDown="valida_longitud(this)" onKeyUp="valida_longitud(this)"></textarea>
		<span style="vertical-align:64px;">(Detalle brevemente un comentario de la ausencia)</span>
	</div>
	<div id="trEnviarMedico" style="visibility:hidden; margin-top:8px;">
		<img border="0" src="/modules/ausentismo/images/viñeta.jpg" />
		<label class="FormLabelGris10">Enviar Médico</label>
		<select class="Combo" id="enviarMedico" name="enviarMedico" style="margin-left:39px;" onChange="mostrarJustificacion()"></select>
	</div>
	<div id="trJustifique" style="visibility:hidden; margin-top:8px;">
		<img border="0" src="/modules/ausentismo/images/viñeta.jpg" style="vertical-align:66px;" />
		<label class="FormLabelGris10" style="vertical-align:66px;" valign="top">Justifique</label>
		<textarea class="FormTextArea" id="justifique" name="justifique" style="height:80px; margin-left:64px; width:376px;" onKeyDown="valida_longitud(this)" onKeyUp="valida_longitud(this)"></textarea>
	</div>
	<div style="margin-left:132px; margin-top:16px;">
		<input class="BotonBlanco" name="btnEnviar" type="submit" value="ENVIAR" />
		<span class="Mensaje" id="spanMensaje" name="spanMensaje" style="display:none;" onMouseMove="ocultarMensajeOk()"><p>Los datos se guardaron correctamente.</span>
	</div>
	<div style="float:right; margin-right:56px; margin-top:-176px;">
		<a href="/modules/ausentismo/Lineamientos_Ausentismo_Empleados.pdf" target="_blank"><img border="0" src="/modules/ausentismo/images/lineamientos.jpg"></a>
	</div>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "EmpleadoAusente";
$RCparams = array();
$RCquery =
	"SELECT   se_id ID, se_nombre detalle
   		 FROM use_usuarios
 			WHERE se_fechabaja IS NULL
   			AND se_usuariogenerico = 'N'
	 ORDER BY se_buscanombre";
$RCselectedItem = -1;
FillCombo();

$RCfield = "MotivoAusencia";
$RCparams = array();
$RCquery =
	"SELECT   ma_id ID, ma_detalle DETALLE
   		 FROM rrhh.rma_motivosausencia
 			WHERE ma_fechabaja IS NULL
	 ORDER BY 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "enviarMedico";
$RCparams = array();
$RCquery =
	"SELECT 'T' ID, 'Sí' DETALLE
   		FROM DUAL
UNION ALL
	SELECT 'F' ID, 'No' DETALLE
   		FROM DUAL";
$RCselectedItem = -1;
FillCombo();
?>

	document.getElementById('EmpleadoAusente').focus();
</script>