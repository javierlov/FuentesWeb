<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");

@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();
$EventoID = '0';	
$Accion = "";
$msjEditable = '';	 
$ET_USUALTA = strtoupper($_SESSION["usuario"]);
$muestrav = 0;
$msj = '';
	   
function VolverPaginaPrev($msj = ''){
	$muestrav = 1;	
}
	
if( isset($_REQUEST["id"]) ) $EventoID = $_REQUEST["id"];
if( isset($_REQUEST["EventoID"]) ) $EventoID = $_REQUEST["EventoID"];

if(isset($_REQUEST["btnAceptarSubmit"]) ) {
	$muestrav = 1;	
	$txtfecha = $_REQUEST["txtFecha"]; 
	$txtfechavencimiento = $_REQUEST["txtFechaVencimiento"]; 
	$etid = $EventoID; 
	$idEvento = $EventoID;
	
	/*	Solucion ticket 68816
		codificacion de símbolos . 	*/
	$txtobservaciones = htmlspecialchars_decode($_REQUEST["txtObservaciones"]); 
	$txtobservaciones = htmlspecialcharsDecodeUpper($txtobservaciones);
		
	$usuario = $_SESSION["usuario"]; 
	$cmbEventos = $_REQUEST["cmbEventos"]; 
		
	if( $idEvento == 0){
		$msj = 'Evento Ingresado correctamente. ';
		$nrojuicio = $_SESSION["NroJuicio"];
		$idEvento = InsertarEventoNuevo($txtfecha, $txtfechavencimiento, $txtobservaciones , $nrojuicio, $usuario, $cmbEventos);
		$EventoID = $idEvento;
		VolverPaginaPrev("Datos ingresados correctamente.");
	}
	else
	{
		$msj = 'Evento Actualizado correctamente. ';
		UpdateEventosABM($txtfecha, $txtfechavencimiento, $etid, $txtobservaciones, $usuario, $cmbEventos);
		VolverPaginaPrev("Datos actualizados correctamente");
	}
}

	if( $EventoID > 0 ){		
		$_SESSION["NroJuicioEvento"] = $_REQUEST["id"];
		$_SESSION["idEvento"] = $_REQUEST["id"];
			
		$Accion = "EDIT";
		$volver = False;	
		list($ET_FECHAVENCIMIENTO, $ET_FECHAEVENTO, $ET_IDTIPOEVENTO, $ET_OBSERVACIONES, 
				$ET_IDJUICIOENTRAMITE,$ET_USUALTA ) = ObtenerEventosABM( $EventoID );
	}else{
		$volver = False;	
		$Accion = "ALTA";
		$_SESSION["idEvento"] = 0;
	}

	if(isset($_REQUEST['btnCancelar'])){
		$volver = False;
		VolverPaginaPrev();
	}

	$tipoevento = 0;
	if(isset($ET_IDTIPOEVENTO)) $tipoevento = $ET_IDTIPOEVENTO;
	
	$_SESSION["Parte"] = 'D';
	
	//Selecciona que tipo de combo llenar .. ver esto	
	if ((isset($_SESSION["Parte"])) && ($_SESSION["Parte"] == 'A')){ 				
		$cmboptions = CargarEventosActora($tipoevento);
	}
	
	if((!isset($_SESSION["Parte"]) ) || ($_SESSION["Parte"] == 'D')){				
		$cmboptions = CargarEventos($tipoevento);
	}
	
	$FechadeNotificacion = ObtenerFechadeNotificacion($_SESSION["NroJuicio"]);	
	
include($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/js/AgregaEncabezadoCalendarJS.html");

$nrojuicio = $_SESSION["NroJuicio"];
$usuario = $_SESSION["usuario"]; 

echo "<script type='text/javascript'> 
				var nrojuicio = '".$nrojuicio."'; 				
				var usuario = '".$usuario."'; 
				var EventoID = '".$EventoID."'; 
				var Accion = '".$Accion."'; 
				var mensaje = '".$msj."';
				var muestraventana = '".$muestrav."';
	 </script>";
 
$mostrarControl = true; 
/*
if( $_SESSION["JUICIOTERMINADO"] and ($Accion != 'ALTA') ) {
	$mostrarControl = false;
}
*/
	
$PageBase->AgregarEncabezadoJS(true,false,true,true, true, false);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/EventosABMWebForm.js?rnd=".RandomNumber());

$PageBase->AgregarEncabezadoJQUERYUI();
$PageBase->AgregarEncabezadoCSS(true,true,true);
$PageBase->AgregarDivProcesando();
$PageBase->ActivarGifProcesando();
$PageBase->CrearVentanaMensajeOculta("Eventos","mensaje","ACEPTAR");

?>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral">		
<form name="EventosABMWebForm" method="post" action="/EventosABMWebForm" id="idEventosABMWebForm" style="overflow: hidden;" onsubmit="return ValidarEventosABMWebForm();">

<input type="hidden" value="<?php echo $EventoID; ?>" name="EventoID" id="EventoID">	
<input type="hidden" value="<?php echo $FechadeNotificacion; ?>" name="FechadeNotificacion" id="idFechadeNotificacion">	

<input type="hidden" value="<?php echo $EventoID; ?>" name="id" id="id">	
<input type="hidden" value="<?php echo $nrojuicio; ?>" name="NroJuicio" id="NroJuicio">	

<?php	
echo TablaDatosUsuario($_SESSION["usuario"]);		
echo TablaDatosJuicio($_SESSION["NUMEROCARPETA"], $_SESSION['DESCRIPCARATULA']); 
?>	
	<table class="table_General" id="idtable_General" >		
		<tr class="title_NegroFndAzul" style="width:auto;"  ><td colspan="4"  >Eventos <?=$Accion?>	</td></tr>		
		<tr><td class="item_Blanco" colspan="2">	</td></tr>		
		<tr>
			<td class="item_Blanco"  style="width:40px;" >Fecha:</td>
			<td width="325" class="item_Blanco">
				<input name="txtFecha" type="text" maxlength="10" id="txtFecha" class="input_text_Fecha"
					value="<?php if(isset($ET_FECHAEVENTO)) echo $ET_FECHAEVENTO; ?>"  />					
				<input type="button" name="btnFecha" id="btnFecha" alt="" border="0" value="..." class="BotonFechaEstudio" />
				<div class="input_textError" id="ErrorestxtFecha"></div>
			</td>
		</tr>
		<tr>
			<td class="item_Blanco">F. Vencimiento:</td>
			<td width="307" class="item_Blanco">
				<input name="txtFechaVencimiento" type="text" maxlength="10" id="txtFechaVencimiento" 
						class="input_text_Fecha" 
						value="<?php if(isset($ET_FECHAVENCIMIENTO)) echo $ET_FECHAVENCIMIENTO; ?>" />
						
				<input type="button" name="btnFechaVencimiento" id="btnFechaVencimiento" alt="" border="0" value="..."  class="BotonFechaEstudio"/>
				<div class="input_textError" id="ErrorestxtFechaVencimiento"></div>
			</td>
		</tr>
		<tr>
			<td class="item_Blanco">Evento:	</td>
			<td colspan="3" align="left" class="item_Blanco">
				<select name="cmbEventos" id="cmbEventos" class="combo" style="width:90%"><?php echo $cmboptions; ?></select>
				<div class="input_textError" id="ErrorescmbEventos"></div>
			</td>
		</tr>
		<tr>
			<td valign="top" class="item_Blanco">Observaciones:	</td>
			<td colspan="3" class="item_Blanco" >
				<textarea name="txtObservaciones" rows="10" id="txtObservaciones" maxlength="100000"
					onclick="ContarCaracteres();" 
					onchange="ContarCaracteres();" 					
					onkeyup="ContarCaracteres();"  
					onselect="ContarCaracteres();" 					
					class="text_area"><?php if(isset($ET_OBSERVACIONES)) echo $ET_OBSERVACIONES; ?></textarea>
				<div class="celda_titulogrisClaroFndBlanco" id="idcontarcaracteres">. </div>
				<div class="input_textError" id="ErrorestxtObservaciones"></div>
			</td>
		</tr>		
	</table>	
	<div class="input_textError" id="lblErrores"></div>
	
	
<?php
////validacion de usuario =  VER MAIL Validaciones de datos (Montero, Melina <mmontero@provart.com.ar> martes 12/08/2014 11:22) 	
	if( $mostrarControl ) { 
		if( strtoupper($ET_USUALTA) == strtoupper($_SESSION["usuario"]) ){
?>												
	<input type='submit' name='btnAceptarSubmit' value='' id='btnAceptarSubmit' class='btnAceptarEJ btnHover' /> 	
<?php  }else { $msjEditable = "Usted No puede modificar este Evento."; 
   ?>							
	<div class="input_textError" id="AvisotxtObservaciones"><?php echo $msjEditable;  ?></div>
<?php	}   ?>							
	<input type='button' name='btnCancelar' value='' id='btnCancelar' class='btnCancelarEJ btnHover' 
		onClick="window.location.href='<?php echo $_SESSION['PagePrevEventosABMWeb'] ?>' " />
<?php  
	}   ?>												
	<br>	
	<a class="btnVolver" href="<? echo $_SESSION["PagePrevEventosABMWeb"];?>"></a>
	
</form>
</div>

<?php 

if( !$mostrarControl ) { 
	echo "<script type='text/javascript'> 		
			BloquearControlesForm('idEventosABMWebForm');
			document.getElementById('idcontarcaracteres').style.display = 'none';
		</script>";
}

$PageBase->CrearVentanaDialogJQUI("Evento","Evento Actualizado / Ingresado."); 
$PageBase->DesactivarGifProcesando(); 

?>
