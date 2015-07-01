<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 

$PageBase = new PageBase(true);

ValidarUserSession();
//CuotasWebForm
///////initialization///////
$cmbTipo = 0;
$Accion = "";
$id = 0;
$nroorden = 0;

///////implementation///////
if(isset($_REQUEST["cmbTipo"])) $cmbTipo = $_REQUEST["cmbTipo"];

if(isset($_REQUEST["btnAceptar"])){
	
	$txtfecha1 = $_REQUEST["txtFecha"];
	$cantcuota = $_REQUEST["txtcantcuotas"];
	$tiempo = $_REQUEST["txtperiodicidadCuotas"];
	$txtmonto = $_REQUEST["txtMonto"];
	$usuario = $_SESSION["usuario"];
	$nroorden = $_SESSION["ArrayCuotasWebForm"]["nroorden"];
	$cmbTipo = $_REQUEST["cmbTipo"];
	
	if($_REQUEST["Accion"] == "ALTA"){				
		if(InsertarCuotas($txtfecha1, $cantcuota, $tiempo, $txtmonto, $usuario, $nroorden, $cmbTipo) ){
			echo "<script type='text/javascript'> 					
					alert('Registro Insertado.');				
					window.location.href = '/AcuerdosWebForm';
			   </script>";			
			}
			else{
			echo "<script type='text/javascript'> 
					alert('Error: No se pudo insertar el registro.'); 			
					history.go(-1);				
				</script>";		
			}	
	}			
}

if(isset($_SESSION["ArrayCuotasWebForm"]["nroorden"] )){
	$Accion = "ALTA";	
	$nroorden = $_SESSION["ArrayCuotasWebForm"]["nroorden"];
}	

$cmbTipo = CargarTipo(false, '', 0);  
$usuario = $_SESSION["usuario"];
if(isset($_SESSION['nroorden'])) $nroorden = $_SESSION['nroorden']; else $nroorden = 0;

echo "<script type='text/javascript'> 
		var usuario = '".$usuario."'; 				
		var nroorden = ".$nroorden."; 						
		var Accion = '".$Accion."'; 						
		</script>";
		
$PageBase->AgregarEncabezadoCSS(true,false,true);

$HEADjquery=true;
$HEADComunes=true;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=true;
$HEADvalidations=false;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);

$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/js/ComunesValida.js");
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/js/CuotasWebForm.js");
$PageBase->AgregarDivProcesando(); 
$PageBase->CrearVentanaMensajeOculta("Cuotas","mensaje","ACEPTAR");
$PageBase->CrearVentanaMensajeOKCancel("Cuotas","mensaje");		
		
include_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/js/AgregaEncabezadoCalendarJS.html");
?>

<title>Acuerdos</title>

<body onload="inicio();">

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow:hidden;">		

<form name="CuotasWebForm" method="post" action="/CuotasWebForm" id="idCuotasWebForm" onsubmit="return ValidarCuotasWebForm();" >

<input type="hidden" name="Validacion" id="Validacion" value="">
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="nroorden" id="nroorden" value="<?php echo $nroorden; ?>">
<input type="hidden" name="Accion" value="<?php echo $Accion; ?>">

<?php echo TablaDatosUsuario($_SESSION["usuario"]);	?>

<table class="table_General" width="100%" >
	<tr>
		<td colspan="2" align="left">	</td>
	</tr>
	<tr>
		<td colspan="2" class="title_NegroFndAzul">Carga Automatica de Cuotas</td>
	</tr>
	<tr>			
		<td class="item_Blanco" colspan="2" height="5"></td>
	</tr>
	<tr>
		<td width="162" class="item_Blanco">Fecha 1ra. Cuota:</td>
		<td class="item_Blanco">
			<input name="txtFecha" type="text" maxlength="10" id="txtFecha" class="input_text_Fecha"  />
			<input type="button" name="btnFecha" id="btnFecha"  value="..." class="BotonFechaEstudio" />
			<div class="input_textError" id="ErrorestxtFecha"></div>
	</td>
	</tr>
	<tr>
		<td class="item_Blanco">Cantidad de Cuotas:</td>
		<td class="item_Blanco">
			<input name="txtcantcuotas" type="text" value="0" id="txtcantcuotas" class="input_text" maxlength="2" />
			<div class="input_textError" id="Errorestxtcantcuotas"></div>
	</td>
	</tr>
	<tr>
		<td class="item_Blanco">Periodicidad de Cuotas:</td>
		<td class="item_Blanco">
			<input name="txtperiodicidadCuotas" type="text" value="0" id="txtperiodicidadCuotas" class="input_text" maxlength="2"/>
			<div class="input_textError" id="ErrorestxtperiodicidadCuotas"></div>
	</td>
	</tr>
	<tr>
		<td class="item_Blanco">Monto:</td>
		<td class="item_Blanco">
			<input name="txtMonto" type="text" value="0,00" id="txtMonto" class="input_text" maxlength="14"/>
			<div class="input_textError" id="ErrorestxtMonto"></div>
	</td>
	</tr>
	<tr>
		<td class="item_Blanco">Tipo:</td>
		<td class="item_Blanco">
			<select name="cmbTipo" id="cmbTipo" class="input_text" style="width:80%" >
				<?php echo $cmbTipo; ?>
			</select>
			<div class="input_textError" id="ErrorescmbTipo"></div>
		</td>
	</tr>
	<tr>
		<td class="item_Blanco" height="5" colspan="2"></td>
	</tr>		
</table>
<div style="position:fixed; left:50%;">
	<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
</div>
<div align="left" >				
	<div class="input_textError" id="lblErrores"></div>
	<input type="button" name="btnAceptar" value="" id="idAceptarAjax" class="btnAceptarEJ" />
	<input type="button" name="btnCancelar" value="" id="btnCancelar" class="btnCancelarEJ"	onClick="window.location.href = '/AcuerdosWebForm'" />
</div>
	<br>
		<input class="btnVolver"  name="btnVolver" type="button" onClick="window.location.href = '/AcuerdosWebForm'" />				
</form>
</div>

</body>
	
<?php $PageBase->DesactivarGifProcesando(); ?>