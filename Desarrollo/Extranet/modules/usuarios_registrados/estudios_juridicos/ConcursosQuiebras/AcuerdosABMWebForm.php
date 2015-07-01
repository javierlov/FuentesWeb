<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 

$PageBase = new PageBase(true);

ValidarUserSession();
//AcuerdosABMWebForm

///////initialization///////
$accion = 'ALTA';

$NroPago = 0;
$cmbTipo = 0;
$CA_TIPO = 0; 
$nroorden = 0;

///////implementation///////

	if(isset($_SESSION["ArrayAcuerdosABMWeb"])){
		if(isset($_SESSION["ArrayAcuerdosABMWeb"]["nroorden"])){					
			$_SESSION["AcuerdosWebFormAccion"] = 'ALTA';			
			$nroorden = $_SESSION["ArrayAcuerdosABMWeb"]["nroorden"];		
			
			if(isset($_SESSION["ArrayAcuerdosABMWeb"]["NroPago"] )){
				$NroPago = $_SESSION["ArrayAcuerdosABMWeb"]["NroPago"];
				
				$_SESSION["AcuerdosWebFormAccion"] = 'EDIT';	
				extract(ObtenerAcuerdosABM($nroorden, $NroPago),EXTR_PREFIX_ALL, "OAABM");				
				$CA_TIPO = $OAABM_CA_TIPO; 				
			}	
		}
		
		if(isset($_SESSION["AcuerdosWebFormAccion"])){		
			$accion = $_SESSION["AcuerdosWebFormAccion"];
		}
		
	}
	
if(isset($_REQUEST['cmbTipo'])) $cmbTipo = $_REQUEST['cmbTipo'];
$comboTipo = CargarTipo(false, '', $CA_TIPO);  

$usuario = $_SESSION["usuario"];
if(isset($_SESSION['nroorden'])) $nroorden = $_SESSION['nroorden']; else $nroorden = 0;
//--------------------------------------------------------------
if(isset($_REQUEST["NroPago"])) $NroPago = $_REQUEST["NroPago"];
	
echo "<script type='text/javascript'> 
		var usuario = '".$usuario."'; 		
		var Accion = '".$accion."'; 		
		var nroorden = ".$nroorden."; 		
		var NroPago = ".$NroPago."; 				
		</script>";
//--------------------------------------------------------------
$PageBase->AgregarEncabezadoCSS(true,false,true);

$HEADjquery=true;
$HEADComunes=true;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=true;
$HEADvalidations=true;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);

$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/js/AcuerdosABMWebForm.js");
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/js/ComunesValida.js");
$PageBase->AgregarDivProcesando(); 
$PageBase->CrearVentanaMensajeOculta("Acuerdos","mensaje","ACEPTAR");
$PageBase->CrearVentanaMensajeOKCancel("Acuerdos","mensaje");		


if (isset($_REQUEST['DELETECONFIRM'])){    
	
	$nroorden = $_REQUEST["nroorden"];
	$NroPago = $_REQUEST["NroPago"];
	$usuario = $_SESSION["usuario"];			 
		   
	if(UpdateAcuerdos($nroorden, $NroPago, $usuario) ){
		echo "<script type='text/javascript'> 									
			MostrarVentana('Acuerdo Eliminado.');									
			$('#idbtnAceptarVentana').click( function(){ redirectpageElim(); });
			
			function redirectpageElim(){				
				window.location.href = '/AcuerdosWebForm';
				return true;	
			  }
		   </script>";		 
		}
		else{
		echo "<script type='text/javascript'> 
				MostrarVentana('Error: No se pudo eliminar el acuerdo.');
			$('#idbtnAceptarVentana').click( function(){ redirectpageElimNo(); });
			
			function redirectpageElimNo(){								
				window.location.href = '/AcuerdosWebForm';
				return true;	
			  }				
			</script>";					
		}
}

include_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/js/AgregaEncabezadoCalendarJS.html");
?>

<title>Acuerdos</title>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow-x:hidden;" >		

<form name="AcuerdosABMWebForm" method="POST" action="/AcuerdosABMWebForm" id="idAcuerdosABMWebForm" 
		onsubmit="return ValidarAcuerdosABMWeb();">

<input type="hidden" name="Validacion" id="Validacion" value="">
<input type="hidden" name="NroPago" id="NroPago" value="<?php echo $NroPago; ?>">
<input type="hidden" name="nroorden" id="nroorden" value="<?php if(isset($nroorden)) echo $nroorden; ?>">

<?php echo TablaDatosUsuario($_SESSION["usuario"]);	?>
	
<table class="table_General" align='left' >
			<tr><td colspan="2"></td></tr>
			<tr>
				<td colspan="2" class="title_NegroFndAzul">Acuerdos</td>
			</tr>
			<tr>
				<td colspan="2" height="5" class="item_Blanco"></td>
			</tr>
			<tr>
				<td class="item_Blanco" width="52px" >Monto:</td>
				<td class="item_Blanco" width="242px">
					<input type="text" id="txtMonto" name="txtMonto" class="input_text" width="62px" maxlength="14"
						value="<?php if(isset($OAABM_CA_MONTO))echo SoloformatearDinero($OAABM_CA_MONTO); ?>">
					<div class="input_textError" id="ErrorestxtMonto" ></div>
				</td>
			</tr>
			<tr>
				<td class="item_Blanco" width="52px">Tipo:</td>
				<td class="item_Blanco" >
					<select id="cmbTipo" name="cmbTipo" width="52px">
						<?php echo $comboTipo; ?>
					</select>
					<div class="input_textError" id="ErrorescmbTipo" ></div>
				</td>
			</tr>
			<tr>
				<td class="item_Blanco">Fecha de Vto:</td>
				<td class="item_Blanco">
					<input type="text" id="txtFechadeVto" name="txtFechadeVto" maxlength="10" class="input_text_Fecha" 
						value="<?php if(isset($OAABM_CA_FECHAVENC))echo $OAABM_CA_FECHAVENC;  ?>" >
					<input type="button" id="btnFechadeVto" value="..." class="BotonFechaEstudio" > 					
					<div class="input_textError" id="ErrorestxtFechadeVto" ></div>
				</td>
			</tr>
			<tr>	
				<td class="item_Blanco">Fecha de Pago:</td>
				<td class="item_Blanco">
					<input type="text" id="txtFechadePago" name="txtFechadePago" maxlength="10" class="input_text_Fecha"  
						value="<?php if(isset($OAABM_CA_FECHAPAGO))echo $OAABM_CA_FECHAPAGO; ?>" >
					<input type="button" id="btnFechadePago" value="..." class="BotonFechaEstudio">
					<div class="input_textError" id="ErrorestxtFechadePago" ></div>
				</td>
			</tr>
			<tr>
				<td valign="top" class="item_Blanco">Observaciones</td>
				<td colspan="1" class="item_Blanco">
					<textarea id="txtObservaciones" name="txtObservaciones" maxlength="250" 
						rows="5" class="text_area"><?php if(isset($OAABM_CA_OBSERVACIONES))echo $OAABM_CA_OBSERVACIONES; ?></textarea>
					<div class="input_textError" id="ErrorestxtObservaciones" ></div>
					</td>
			</tr>
			<tr>
				<td class="item_Blanco">F. Caducidad del acuerdo:</td>
				<td colspan="1" class="item_Blanco">
					<input type="text" id="txtFechaExtincion" name="txtFechaExtincion" maxlength="10" class="input_text_Fecha"			
						value="<?php if(isset($OAABM_CA_FECHAEXTINCION))echo $OAABM_CA_FECHAEXTINCION; ?>" >
					<input type="button" id="btnFechaExtincion"	value="..." class="BotonFechaEstudio" >					
					<div class="input_textError" id="ErrorestxtFechaExtincion" ></div>
				</td>
			</tr>
			<tr>
				<td class="item_Blanco" height="5" colspan="2"></td>
			</tr>						
			</table>
<div style="position:fixed; left:50%;">
	<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
</div>
<div align="left">				
	<div class="input_textError" id="lblErrores" ></div>	
	<input type="button" id="idAceptarAjax" name="btnAceptar" class="btnAceptarEJ" value="" >
	<input type="button" id="btnCancelar" name="btnCancelar" class="btnCancelarEJ" value="" 
			onClick="window.location.href = '/AcuerdosWebForm';" >
</div>				
	<br>
	<input class="btnVolver"  name="btnVolver" type="button" onClick="window.location.href = '/AcuerdosWebForm' " />					
</form>
</div>
<?php $PageBase->DesactivarGifProcesando(); ?>