<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/EventosWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();

function VolverPaginaPrev(){
	echo "<script type='text/javascript'> 
			window.location.href = '".$_SESSION["PagePrevEventosWeb"]."';
	   </script>";	
}

AsignarNroJuicioSession();
	
list($JT_NUMEROCARPETA, $DESCRIPCARATULA, $EJ_DESCRIPCION) = ObtenerNroCarpeta($_SESSION["NroJuicio"]);

$_SESSION["NUMEROCARPETA"] = $JT_NUMEROCARPETA;
$_SESSION["DESCRIPCARATULA"] = $DESCRIPCARATULA; 

//de donde sale esta variable global??? por ahora vale D
$_SESSION["Parte"] = 'D';
$_SESSION["PagePrevEventosABMWeb"] = $_SERVER['REQUEST_URI'];

$HEADjquery=true;
$HEADComunes=false;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=false;
$HEADvalidations=false;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);


$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/EventosWebForm.js?rnd=".RandomNumber());
$PageBase->AgregarArchivoJS("/js/rar/JQUIDialog.js?rnd=".RandomNumber());
$PageBase->AgregarArchivoJS("/js/ajax.js?rnd=".RandomNumber());
$PageBase->AgregarEncabezadoCSS(true,true,true);

$PageBase->AgregarEncabezadoJQUERYUI();


$PageBase->CrearVentanaMensajeOculta("Eventos","mensaje","ACEPTARCANCELAR");
$PageBase->CrearVentanaMensajeResultado("Eventos","mensaje");
$PageBase->CrearVentanaMensajeSoloOK("Eventos","mensaje");


if(isset($_REQUEST["DELETE"])){

	$_ID = $_REQUEST["id"];

	list($ET_FECHAVENCIMIENTO, $ET_FECHAEVENTO, $ET_IDTIPOEVENTO, $ET_OBSERVACIONES, 
			$ET_IDJUICIOENTRAMITE, $ET_USUALTA ) = ObtenerEventosABM($_ID);
			
	if( strtoupper($ET_USUALTA) != strtoupper($_SESSION["usuario"]) ){
		echo "<script type='text/javascript'>			
				MostrarVentanaEliminar('Usted no tiene Permisos para Eliminar este Evento');						
				AsignarBotones();
		</script>";
	}else{
			
		echo "<script type='text/javascript'>			
			var id = ".$_ID.";
			MostrarVentana('¿Está seguro de que desea eliminar este Evento?');						
			AsignarBotones();
		</script>";
	}
} 

if(isset($_SESSION["EventosEliminar"])){
   $mensaje =  $_SESSION["EventosEliminar"]["mensaje"];
   $resultado = $_SESSION["EventosEliminar"]["resultado"];

   /*
	MostrarVentanaResultadoOK(mensajeResultado, resultadoEstado);			
	EventosEliminar = false;
			
   */
	echo "<script type='text/javascript'>
			var mensajeResultado = '".$mensaje."'; 
			var resultadoEstado = ".$resultado."; 				
		</script>";		
		
	unset($_SESSION["EventosEliminar"]);
}else{
	echo "<script type='text/javascript'>
			var mensajeResultado = ''; 
			var resultadoEstado = ''; 							
		</script>";		
}

?>

<div align="center" id="divProcesando" name="divProcesando" style="display:none">
	<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>

<form name="EventosWebForm" method="post" action="/EventosWebForm" id="idEventosWebForm">
<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow:hidden;">		
	
<?php			
	echo "<script> BuscarWGTrue(); </script>";
	echo TablaDatosUsuario( $_SESSION["usuario"] );				
	echo TablaDatosJuicioEstado(); 
	echo "<div class='divContenedorGeneral' id='idContenedorGeneral' >";
	echo getGridEventos($_SESSION["NroJuicio"]);	
	echo "</div>";	
?>		

	<div class="divContenedorGeneral" >				
		<input class='btnNuevoEJ btnHover' name='btnNuevo' type="button" value="" 
				onclick=" window.location.href = '/EventosABMWebForm';"  />
	</div>
<?php 

	echo "<script> BuscarWGFalseInterval(); </script>";			 
?>		
<a class="btnVolver"  href="/AdminWebForm"></a>
</div>	
</form>


<?php
	$parametros = array("idDialog" => "dialogElimEvento",
						"idTitulo" => "idTitulo",
						"idDivInfo" => "idDivInfo",
						"idMotivo" => "idMotivo",
						"idDivLoading" => "idDivLoading",
						"txtDialog" => "Eventos",
						"txtTitulo" => "Elimina Evento Dialog",
						"txtDivInfo" => "¿Está seguro de que desea eliminar este Evento?",
						"displayLoading" => true		);
						
	$PageBase->DialogJqueryUI(	$parametros );
 ?>