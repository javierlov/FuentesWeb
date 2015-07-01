<?php
//EventosCYQABMWebForm
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/AcuerdosWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();

$nroorden = 0;
$nroevento = 0;
$CODEVENTO = 0;
$OBSERVACIONES = "";
$FECHA = "";
$urlVolver = "/EventosCYQWebForm";		

if(isset($_REQUEST['nroorden'])){
	$nroorden = $_REQUEST['nroorden'];
}
else{
	echo "<script type='text/javascript'>
			alert('Primero debe seleccionar un número  de orden en Concursos y Quiebras (ABM)');
			window.location.href = '/SeleccionAplicacion'
		</script>";			
	exit;			 		
}

if(isset($_REQUEST['id'])){
	$_SESSION["ACCION"] = "EDIT";
	$nroorden = $_REQUEST['nroorden'];
	$nroevento = $_REQUEST['id'];
	
	extract(ObtenerEventosCYQABM($nroorden, $nroevento),EXTR_PREFIX_ALL, "OEABM");
	$CODEVENTO = $OEABM_CE_CODEVENTO;	
	$OBSERVACIONES = $OEABM_CE_OBSERVACIONES;
	$FECHA = $OEABM_CE_FECHA;
}

if(isset($_REQUEST['ALTA'])){	
	$_SESSION["ACCION"] = "ALTA";	
}

$comboEventosCYQ = CargarEventosCYQ($CODEVENTO);

if(isset($_SESSION['nroorden'])) $nroorden = $_SESSION['nroorden']; else $nroorden = 0;
if(isset($_REQUEST['id'])) $nroevento = $_REQUEST['id']; else $nroevento = 0;
//echo $nroorden."<br>";
//echo $nroevento."<br>";

echo "<script type='text/javascript'> 
		var nroorden = ".$nroorden."; 
		var nroevento = ".$nroevento."; 
		var usuario = '".$_SESSION["usuario"]."'; 
		var Accion = '".$_SESSION["ACCION"]."'; 
		</script>";
//------------------------------------
$PageBase->AgregarEncabezadoCSS(true,false,true);

$HEADjquery=true;
$HEADComunes=false;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=true;
$HEADvalidations=false;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/js/EventosCYQABMWebForm.js");

$PageBase->AgregarDivProcesando(); 
$PageBase->CrearVentanaMensajeOculta("Eventos","mensaje","ACEPTARCANCELAR");
$PageBase->CrearVentanaMensajeOKCancel("Eventos","mensaje");		
//------------------------------------
include_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/js/AgregaEncabezadoCalendarJS.html");
?>
<title>Eventos CYQ ABM</title>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow:hidden;"  >		
<form name="EventosCYQABM" method="POST" action="/EventosCYQABMWebForm" id="idEventosCYQABM" onsubmit="return ValidarEventosCYQABM();" >

<input type="hidden" name="nroorden" value="<?php echo $nroorden; ?>" >
<input type="hidden" name="nroevento" value="<?php echo $nroevento; ?>" >
<input type="hidden" name="urlVolver" value="<?php echo $urlVolver; ?>" >

<?php echo TablaDatosUsuario($_SESSION["usuario"]);	?>

<table class="table_General" align='left' >
<tr><td colspan="2"></td></tr>
<tr>
	<td class="title_NegroFndAzul" colspan="2">Eventos</td>
</tr>
<tr>
	<td class="item_Blanco" HEIGHT="5" colspan="2"></td>
</tr>
<tr>
	<td class="item_Blanco" width="106">Evento:</td>
	<td class="item_Blanco">
		<select name="cmbEventos" id="cmbEventos" class="combo">
			<?php echo $comboEventosCYQ; ?>
		</select>
		<div class="input_textError" id="ErrorescmbEventos"></div>
	</td></tr>
	<tr>
			<td width="106" class="item_Blanco">Fecha:</td>
			<td class="item_Blanco">
				<input name="txtFecha" type="text" maxlength="10" id="txtFecha" class="input_text_Fecha"					
					value="<?php echo $FECHA; ?>" />
				<input type="button" name="btnFecha" id="btnFecha" value="..." class="BotonFechaEstudio" />				
				<div class="input_textError" id="ErrorestxtFecha"></div>
			</td>
		</tr>
		<tr>
			<td class="item_Blanco">Observaciones:</td>
			<td class="item_Blanco">
				<textarea name="txtObservaciones" rows="5" id="txtObservaciones" 
					class="text_area"><?php echo trim($OBSERVACIONES); ?></textarea>
				<div class="input_textError" id="ErrorestxtObservaciones"></div>
			</td>
		</tr>
		<tr>
			<td class="item_Blanco" height="1px" colspan="2"></td>
		</tr>
</table>		
<div style="position:fixed; left:50%;">
	<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
</div>
<td colspan="2"><div class="input_textError" id="lblErrores" ></div>	</td>
<input type="button" name="btnAceptar" value="" id="idAceptarAjax" class="btnAceptarEJ" />                                
<input type="button" name="btnCancelar" value="" id="idbtnCancelar" class="btnCancelarEJ"
	onClick="window.location.href = '<?php echo $urlVolver; ?>';"/>

<div align="center" colspan="2" height="50">				
	<input class="btnVolver"  name="btnVolver" type="button" 
		onClick="window.location.href = '<?php echo $urlVolver; ?>';" />				
</div>
</form>		
</div>		
<?php $PageBase->DesactivarGifProcesando(); ?>