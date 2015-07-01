<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/EventosCYQWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();

if(isset( $_SESSION["ArrayEventosCYQWebForm"] )){
	$nroorden = $_SESSION["ArrayEventosCYQWebForm"]["nroorden"];
	//unset($_SESSION["ArrayEventosCYQWebForm"]);
}
else{
	echo "<script type='text/javascript'>
			alert('Primero debe seleccionar un número de orden en Concursos y Quiebras (CYQ)');
			window.location.href = '/SeleccionAplicacion'
		</script>";			
	exit;			 		
}
if(isset($_SESSION['nroorden'])) $nroorden = $_SESSION['nroorden']; else $nroorden = 0;
//------------------------------------
$PageBase->AgregarEncabezadoCSS(true,false,true);

$HEADjquery=true;
$HEADComunes=false;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=false;
$HEADvalidations=false;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/js/EventosCYQWebForm.js");

$PageBase->AgregarDivProcesando(); 
$PageBase->CrearVentanaMensajeOculta("Eventos","mensaje","ACEPTARCANCELAR");
$PageBase->CrearVentanaMensajeResultado("Eventos","mensaje");		
//------------------------------------
$nroevento = 0;

if(isset($_REQUEST['id']))
	$nroevento = $_REQUEST['id'];
	
echo "<script type='text/javascript'>
	var Nro_orden = ".$nroorden.";
	var Nro_evento = ".$nroevento.";							
</script>";


if(isset($_SESSION["CYQEliminar"])){
	$_SESSION["ACCION"] = "DELETE";
	

	if ($_SESSION["CYQEliminar"]["resultado"] == true){
		echo "<script type='text/javascript'> 									
				MostrarVentanaResultado('".$_SESSION["CYQEliminar"]["mensaje"]."');
				$('#idbtnAceptarVentanaResultado').click( function(){ RedirectPageEventosCYQWebForm(); }  );					
		      </script>";			
	}
	else{
		echo "<script type='text/javascript'> 				
				MostrarVentanaResultado('".$_SESSION["CYQEliminar"]["mensaje"]."');				
				$('#idbtnAceptarVentanaResultado').click( function(){ RedirectPageEventosCYQWebForm(); }  );	
			  </script>";		
	}	
	unset($_SESSION["CYQEliminar"]);
}

?>
<title>Eventos CYQ</title>

<div align="center" id="divProcesando" name="divProcesando" style="display:none">
		<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>
	
<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral">		
<form name="EventosCYQ" method="POST" action="/EventosCYQWebForm" id="idEventosCYQ" onsubmit="return ValidarEventosCYQABM();" >
<input type="hidden" name="nroorden" value="<?php echo $nroorden; ?>" >

<?php 
	echo "<script> BuscarWGTrue(); </script>";
	echo TablaDatosUsuario($_SESSION["usuario"]);	
?>

<table class="table_General" align="left">	
	<tr>
		<td class="title_NegroFndAzul"></td>
	</tr>
	<tr>
		<td class="title_NegroFndAzul">Eventos</td>
	</tr>
	<tr>
		<td class="item_Blanco" colspan="6" height="5"></td>
	</tr>
</table>

<div align="left" id="divContentGrid" name="divContentGrid" 
	style="height:100%; margin-left:0px; margin-top:8px; overflow:auto; width:100%;">		
	<?php	
		echo getGrid($nroorden);	
		echo "<script> BuscarWGFalseInterval(); </script>";
	?>		
</div>

<div align="left">					
	<!-- //CAMBIO PAG 112=119 -->
	<input class="btnNuevoEJ"  name="btnNuevo" type="button" value=""
		onclick="window.location.href = 'index.php?pageid=119&ALTA&nroorden=<?php echo $nroorden; ?>'" />
	</div>

<div align="center" colspan="2" height="50">				
	<input class="btnVolver"  name="btnVolver" type="button" 
		onClick="window.location.href = '<?php echo $_SESSION["PagePrevModificacionCYQ"]; ?>'" />				
</div>

</form>	
</div>
<?php $PageBase->DesactivarGifProcesando(); ?>