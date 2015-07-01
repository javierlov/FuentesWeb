<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/AcuerdosWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();
//AcuerdosWebForm
///////initialization///////
$cmbTipo = 0;
$nroorden = 0;

///////implementation///////
if(isset($_SESSION["ArrayAcuerdosWeb"]["nroorden"])) $nroorden = $_SESSION["ArrayAcuerdosWeb"]["nroorden"];
if(isset($_SESSION["ArrayAcuerdosWeb"]["cmbTipo"])) $cmbTipo = $_SESSION["ArrayAcuerdosWeb"]["cmbTipo"];

if(!$_POST){	
	$urlaction = $_SERVER['REQUEST_URI'];	
}
$cmbTipoFiltro = CargarTipoFiltro();

if(isset($_SESSION['nroorden'])) $nroorden = $_SESSION['nroorden']; else $nroorden = 0;


$PageBase->AgregarEncabezadoCSS(true,false,true);

$HEADjquery=true;
$HEADComunes=false;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=false;
$HEADvalidations=false;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);

$PageBase->AgregarDivProcesando(); 
$PageBase->CrearVentanaMensajeOculta("Acuerdos","mensaje","ACEPTARCANCELAR");
$PageBase->CrearVentanaMensajeOKCancel("Acuerdos","mensaje");		



if(isset($_REQUEST["DELETE"]))
{
	$NroPago = $_REQUEST["NroPago"];
			
	echo "<script type='text/javascript'>
			var Nro_Orden =".$nroorden.";				
			var Nro_Pago = ".$NroPago.";
			MostrarVentana('¿Está seguro de que desea eliminar este Acuerdo?');								
			AsignarBotones();
					
			function AsignarBotones(){
				$('#idbtnCancelarVentana').click( function(){ RedirectCancelar(); } );
				$('#idbtnAceptarVentana').click( function(){ RedirectPage(); });				
			}

			function RedirectCancelar(){
				window.location.href = '/AcuerdosWebForm';  
				return true;
			}
			/*CAMBIO PAG 114=121 */
			function RedirectPage(){					
				window.location.href = '/index.php?pageid=121&DELETECONFIRM&nroorden='+Nro_Orden+'&NroPago='+Nro_Pago;
				return true;
			}  			
		</script>";
} 
?>

<title>Acuerdos</title>

<div align="center" id="divProcesando" name="divProcesando" style="display:none">
		<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>

<!-- //CAMBIO PAG 113=120-->
<form name="AcuerdosWebForm" method="POST" 
		action="/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=120" id="idAcuerdosWebForm">
		
<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral">		

	<input type="hidden" name="cmbTipo1" id="idcmbTipo1" value="<?php echo $cmbTipo ?>" />
	<input type="hidden" name="nroorden" id="idnroorden" value="<?php echo $nroorden ?>" />
	<input type="hidden" name="urlaction" value="<?php echo $urlaction; ?>" />
	
	<?php 
		echo "<script> BuscarWGTrue(); </script>";
		echo TablaDatosUsuario($_SESSION["usuario"]);	
	?>
	
	<table class="table_General" width="100%" >
		<tr>
			<td colspan="4" class="title_NegroFndAzul">Acuerdos</td>
		</tr>
		<tr>
			<td colspan="4" height="5"></td>
		</tr>
		<tr>
			<td class="item_grisClaroFndBlanco">Tipo:</td>
			<td align="left" colspan="2">
				<select name="cmbTipo" id="cmbTipo" class="combo">
					<?php echo $cmbTipoFiltro; ?>
				</select>
			</td>
			<td align="right">
				<input type="reset" name="btnEliminar" class="btnLimpiarEJ" id="btnEliminar" title="Limpiar Filtros"  value="" />
				<input type="submit" name="btnBusqueda" class="btnBuscarEJ" id="btnBusqueda" title="Buscar" value="" />
			</td>
		</tr>
		<tr>
			<td colspan="4"></td>
		</tr>
		<tr>
			<td colspan="4">
				
			</td>
		</tr>	
	</table>

	<div align="left" id="divContentGrid" name="divContentGrid" 
			style="height:100%; margin-left:0px; margin-top:8px; overflow:auto; width:100%;">		
		<?php	
			if($nroorden > 0){							
				echo getGridAcuerdos($nroorden, $cmbTipo);	
				unset($_SESSION["ArrayAcuerdosWeb"]["cmbTipo"]);
			} 
			echo "<script> BuscarWGFalseInterval(); </script>";
		?>		
	</div>

	<div align="left">				
		<!-- //CAMBIO PAG 114=121-->
		<input class="btnNuevoEJ"  name="btnNuevo" type="button" value=""
			onclick="window.location.href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?pageid=121&nroorden=<?php echo $nroorden; ?>'" />
			
		<!-- //CAMBIO PAG 115=122-->
		<input class="btnCuotasEJ"  name="btnCuotas" type="button" value=""
			onclick="window.location.href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?pageid=122&nroorden=<?php echo $nroorden; ?>'" />
	</div>
	
	<div align="center" colspan="2" height="50">				
		<input class="btnVolver"  name="btnVolver" type="button" 
			onClick="window.location.href = '<?php echo $_SESSION["PagePrevModificacionCYQ"]; ?>'" />				
	</div>

</div>
</form>
