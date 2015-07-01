f<?php
if(!isset($_SESSION)) { session_start(); } 

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/PeritajesABMWebForm.Grid.php");

ValidarUserSession();
$PeritajeID = '0';	

///////print_r($_REQUEST);

if((isset($_SESSION["TipoPerito"])) && (isset($_SESSION["IDPERITO"]))  ) {
	/*	
		$PJ_IDPERITO = $_SESSION["TipoPerito"]; 
		$PE_NOMBRE = $_SESSION["PeritoApellido"]." ".$_SESSION["PeritoNombre"];
		$PE_NOMBREINDIVIDUAL = $_SESSION["PeritoNombre"];
		$PE_APELLIDO = $_SESSION["PeritoApellido"];		
		$PJ_IDPERITO = $_SESSION["IDPERITO"];
	*/	
}

if(isset($_REQUEST["btnPeritoNuevo"]) ) {

	if( (isset($_REQUEST["cmbTipoPericia"]))  
	&&  (!empty($_REQUEST["cmbTipoPericia"])) ){
	
		$_SESSION["TipoPerito"] = $_REQUEST["cmbTipoPericia"];
		$_SESSION["PeritoApellido"] = $_REQUEST["txtPeritoApellido"];
		$_SESSION["PeritoNombre"] = $_REQUEST["txtPeritoNombre"];
		
		echo "<script type='text/javascript'>						
			window.location.href = '/PeritoABMWebForm';	
		  </script>";
	}
	else{
		echo "<script type='text/javascript'>
			alert('Debe seleccionar un tipo de percia');	
		  </script>";	
	}	
}

if(isset($_REQUEST["EDIT"]) ) {
	$PeritajeID = $_REQUEST["id"];	
}

if(isset($_REQUEST["NroJuicio"])) $_SESSION["NroJuicio"] = $_REQUEST["NroJuicio"];


if(isset($_REQUEST["DELETE"]) ) {

	$PeritajeID = $_REQUEST["id"];
	$usuario = $_SESSION["usuario"];
	
	UpdatePeritajes($PeritajeID, $usuario);
	
	echo "<script type='text/javascript'>
			VolverPagina('El peritaje fue eliminado...', '/PeritajesWebForm');
		  </script>";

}


if(isset($_REQUEST['Aceptar'])){

	$txtFechaAsignacion = ''; 
	$txtFechaPericia = '';
	$txtFVencImpug = ''; 
	$cmbPericia = ''; 
	$txtResultados = ''; 
	
	$nrojuicio = $_SESSION["NroJuicio"]; 
	$usuario = $_SESSION["usuario"];
	
	$incapacidadDemanda = ''; 
	$incapacidadPeritoMedico = ''; 
	$ibmArt = ''; 
	$ibmPericial = ''; 
	$impugnacion = ''; 
	$idperito = ''; 

	if(isset( $_REQUEST['txtFechaAsignacion']) ) $txtFechaAsignacion = $_REQUEST['txtFechaAsignacion'];
	if(isset( $_REQUEST['txtFechaPericia']) ) $txtFechaPericia = $_REQUEST['txtFechaPericia'];
	if(isset( $_REQUEST['txtFVencImpugnacion']) ) $txtFVencImpug = $_REQUEST['txtFVencImpugnacion'];
	if(isset( $_REQUEST['cmbTipoPericia']) ) $cmbPericia = $_REQUEST['cmbTipoPericia'];
	if(isset( $_REQUEST['txtResultados']) ) $txtResultados = $_REQUEST['txtResultados'];
	
	if(isset( $_REQUEST['txtIncapacidadDemandada']) ) $incapacidadDemanda = $_REQUEST['txtIncapacidadDemandada'];
	if(isset( $_REQUEST['txtIncapacidadPerMedico']) ) $incapacidadPeritoMedico = $_REQUEST['txtIncapacidadPerMedico'];
	if(isset( $_REQUEST['txtIBMArt']) ) $ibmArt= $_REQUEST['txtIBMArt'];
	if(isset( $_REQUEST['txtIBMPericial']) ) $ibmPericial= $_REQUEST['txtIBMPericial'];
	
	if(isset( $_REQUEST['chkImpugnacion']) ) $impugnacion = $_REQUEST['chkImpugnacion'];	
	if(isset( $_REQUEST['cmbSeleccionPerito']) ) $idperito = $_REQUEST['cmbSeleccionPerito'];		

	if( $_REQUEST['valPeritajeID']== 0){
		
		if (InsertarPeritajeNuevo(
			$txtFechaAsignacion, $txtFechaPericia,
		    $txtFVencImpug, $cmbPericia, 
		    $txtResultados, $nrojuicio, $usuario,
		    $incapacidadDemanda, $incapacidadPeritoMedico, 
		    $ibmArt, $ibmPericial, $impugnacion, $idperito) ){
		
			echo "<script type='text/javascript'> 
						function goBack() {		
							window.location.href = '/PeritajesWebForm';
						}	
						alert('Los datos fueron Ingresados.....');	
						setTimeout('goBack()', 1000);					
				   </script>";	
		
		}
		else{
			echo "<script type='text/javascript'> alert('Error: Revise los datos.'); </script>";		
		}
	}
	else{

		if(isset( $_REQUEST['valPeritajeID']) ) $PeritajeID = $_REQUEST['valPeritajeID'];
		$nrojuicio = $PeritajeID ;
		if(isset( $_REQUEST['valPeritoID']) ) $idperito = $_REQUEST['valPeritoID'];
		
		if (UpdatePeritajesABM(
			$txtFechaAsignacion, $txtFechaPericia,
		    $txtFVencImpug, $cmbPericia, 
		    $txtResultados, $nrojuicio, $usuario,
		    $incapacidadDemanda, 
		    $incapacidadPeritoMedico, 
		    $ibmArt, 
		    $ibmPericial, 
		    $impugnacion, 
		    $idperito) ){
		
			echo "<script type='text/javascript'> 
						function goBack() {		
							window.location.href = '/PeritajesWebForm';
						}	
						alert('Los datos fueron Actualizados.....');	
						setTimeout('goBack()', 1000);					
				   </script>";	
		
		}
		else{
			echo "<script type='text/javascript'> alert('Error: Revise los datos.'); </script>";		
		}

	}
	
}

if(isset($_REQUEST['Cancelar'])){
	echo "<script type='text/javascript'> 
				window.location.href = '/PeritajesWebForm';
		   </script>";	
}

if (isset($_REQUEST["PeritajeID"])) $PeritajeID= $_REQUEST["PeritajeID"];	

if($PeritajeID > 0){
	list($PJ_ID, $PJ_FECHAPERITAJE, $PJ_IDJUICIOENTRAMITE, 
		$PJ_RESULTADOPERITAJE, $PJ_FECHANOTIFICACION, 
		$PJ_IDTIPOPERICIA, $PJ_FECHAVENCIMPUGNACION, 
		$PJ_INCAPACIDADDEMANDA, $PJ_USUALTA, 
		$PJ_INCAPACIDADPERITOMEDICO, $PJ_IBMART, 
		$PJ_IBMPERICIAL, $PJ_IMPUGNACION, $IMPUGNACION, 
		$PJ_IDPERITO, $PE_NOMBRE, 
		$PE_NOMBREINDIVIDUAL, $PE_APELLIDO) = ObtenerPeritajesABM($PeritajeID);
}

?>

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/PeritajesABMWebForm.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js" type="text/javascript"></script>

<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<form name="PeritajesABMWebForm" method="post" action="/PeritajesABMWebForm" id="idPeritajesABMWebForm">

<input id="PeritajeID" name="valPeritajeID" type="hidden" value="<?= $PeritajeID; ?>">
<input id="PeritoID" name="valPeritoID" type="hidden" value="<?= $PJ_IDPERITO; ?>   ">
  	
<div align="left" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:20px; margin-top:8px; overflow:auto; width:96%;">		

<table cellspacing="1" cellpadding="0"  width="100%" align="center" bgcolor="#ffffff" class="body_border">
	<tr>
		<td colspan="4" align="left">
			<?php 					
				//TABLA CON DATOS DE ENCABEZADO
				TablaDatosUsuario($_SESSION["usuario"]);												
			?>			
		</td>
	</tr>
	<tr> <td height="5px" colspan="4" bgcolor="#ffffff" >
	</td></tr>
	<tr><td> 
		<?php
			echo TablaDatosJuicio( $_SESSION['NUMEROCARPETA'], $_SESSION['DESCRIPCARATULA'] );
		?>

	</td></tr>

</table>	

<table cellspacing="1" cellpadding="5" width="100%" align="center" bgcolor="#ffffff" class="body_border">
	<tr>
		<td class="title_NegroFndGrisClaro" colspan="4">Peritajes</td>
	</tr>
	<tr>
		<td class="item_grisClaro" colspan="4" style="HEIGHT: 13px"></td>
	</tr>
	<tr>
		<td class="item_grisClaro" style="width: 202px">Pericia		</td>
		<td class="item_grisClaro" colspan="3"> <?php 
				if( !isset($PJ_IDTIPOPERICIA) )
					$PJ_IDTIPOPERICIA = 0;
				echo CargarTipoPericia( $PJ_IDTIPOPERICIA ); 
			?>	
		</td>
	</tr>
	<tr>
		<td class="item_grisClaro" style="width: 202px" >	<p>Filtrar Perito</p>		</td>
		<td class="item_grisClaro" colspan="3"> 
			<label>Apellido:</label>
			<input name="txtPeritoApellido" type="text" maxlength="100"  id="txtPeritoApellido" class="input_textUpper"
					value="<?php 
					if(isset($_SESSION["PeritoApellido"])){
						echo $_SESSION["PeritoApellido"];
						
						//unset($_SESSION["PeritoApellido"]);
					} ?>" />						
			<input type="button" name="btnPeritoBuscar" id="btnPeritoBuscar" class="BotonBuscar" border="0" 
					title="Busca Perito por Apellido o Nombre" value="Buscar" />
			<br>
			<label>Nombre:</label>
			<input name="txtPeritoNombre" type="text" maxlength="100" id="txtPeritoNombre" class="input_textUpper"
					value="<?php 
					if(isset($_SESSION["PeritoNombre"])){
						echo $_SESSION["PeritoNombre"];
						
						//unset($_SESSION["PeritoNombre"]);
					} ?>" />
			<input type="submit" name="btnPeritoNuevo" id="idbtnPeritoNuevo" title="Crear Nuevo Perito" 
					value="Crear Perito"alt="" border="0"/></td>
	</tr>
	<tr>
		<td class="item_grisClaro" valign="top" style="width: 202px">
			<p>Seleccionar Perito</p>		</td>
		<td class="item_grisClaro" colspan="3" >
			<select  name='cmbSeleccionPerito' id='idcmbPeritosNombre' class='input_text'>
				<option id="0"></option>
				<?php if(isset($PE_NOMBRE)) echo "<option id='".$PJ_IDPERITO."' selected='selected'>".$PE_NOMBRE."</option>"; ?>
			</select>		
			
			<input type="button" name="btnLimpiarPerito"  
					id="idbotonLimpiar"  title="Limpiar Seleccion Perito" value="Limpiar"
				class="btnLimpiar1" /></td>
	</tr>
	<tr>
		<td class="item_grisClaro" style="width: 202px">F. Notificacion:		</td>
		<td width="215" class="item_grisClaro" colspan="3">
			<input name="txtFechaAsignacion" type="text" maxlength="10" 
					id="txtFechaAsignacion" class="input_text" 
					value="<?php if(isset($PJ_FECHANOTIFICACION)) echo $PJ_FECHANOTIFICACION; ?>"/>		
			<input type="button" name="btnFechaAsignacion" id="btnFechaAsignacion"
					border="0" class="BotonInformacion1" value="..." /></td>
	</tr>
	<tr>
		<td width="62" class="item_grisClaro">F. Pericia:		</td>
		<td width="468" class="item_grisClaro">
			<input name="txtFechaPericia" type="text" maxlength="10" id="txtFechaPericia" class="input_text" 
				value="<?php if(isset($PJ_FECHAPERITAJE)) echo $PJ_FECHAPERITAJE; ?>" />
			<input type="button" name="btnFechaPericia" language="javascript" id="btnFechaPericia" 
					alt="" border="0" class="BotonInformacion1" value="..." /></td>
	</tr>
	<tr>
		<td valign="top" class="item_grisClaro" style="width: 202px">F. Venc. Impug.:		</td>
		<td colspan="3" class="item_grisClaro">
			<input name="txtFVencImpugnacion" type="text" maxlength="10" id="txtFVencImpugnacion" class="input_text" 
					value="<?php if(isset($PJ_FECHAVENCIMPUGNACION)) echo $PJ_FECHAVENCIMPUGNACION; ?>" />
			<input type="button" name="btnFVencImpugnacion" id="btnFVencImpugnacion" alt="" border="0" 
				class="BotonInformacion1" value="..." /></td>
	</tr>
	
	
<tr>
	<td valign="top" class="item_grisClaro" style="width: 202px">Inc. Demanda:</td>
	<td colspan="3" class="item_grisClaro">	
		<input name="txtIncapacidadDemandada" type="text" maxlength="6" id="idtxtIncapacidadDemandada" class="input_text" 
			value="<?php 
						if(isset($PJ_INCAPACIDADDEMANDA)) 
							if(is_numeric($PJ_INCAPACIDADDEMANDA))
								echo formatearDinero($PJ_INCAPACIDADDEMANDA);
								//echo $PJ_INCAPACIDADDEMANDA; 
							else echo "0,00"; 
					?>"  /></td>
</tr>
<tr>
	<td valign="top" class="item_grisClaro" style="width: 202px">Inc. Perito Medico:</td>
	<td colspan="3" class="item_grisClaro">
		<input name="txtIncapacidadPerMedico" type="text" maxlength="6" id="idtxtIncapacidadPerMedico" class="input_text" 
			value="<?php if(isset($PJ_INCAPACIDADPERITOMEDICO)) 
							if(is_numeric($PJ_INCAPACIDADPERITOMEDICO))								
								echo formatearDinero($PJ_INCAPACIDADPERITOMEDICO); 
							else echo "0,00"; 
					?>" /></td>
</tr>
<tr>
	<td valign="top" class="item_grisClaro" style="width: 202px">IBM. Art:</td>
	<td colspan="3" class="item_grisClaro">
		<input name="txtIBMArt" type="text" maxlength="6" id="idtxtIBMArt" class="input_text" 
			value="<?php if(isset($PJ_IBMART)) 
							if(is_numeric($PJ_IBMART))								
								echo formatearDinero($PJ_IBMART); 
							else echo "0,00";  ?>" /></td>
</tr>
<tr>
	<td valign="top" class="item_grisClaro" style="width: 202px">IBM. Pericial:</td>
	<td colspan="3" class="item_grisClaro">
		<input name="txtIBMPericial" type="text" maxlength="6" id="idtxtIBMPericial" class="input_text" 
			value="<?php if(isset($PJ_IBMPERICIAL)) 
							if(is_numeric($PJ_IBMPERICIAL))								
								echo formatearDinero($PJ_IBMPERICIAL); 
							else echo "0,00";  ?>" /></td>
</tr>	
	<tr>
		<td class="item_grisClaro" style="width: 202px">		</td>
		<td class="item_grisClaro" colspan="3"> </td>
	</tr>
	<tr>
		<td class="item_grisClaro" style="width: 202px">		</td>
		<td class="item_grisClaro" colspan="3"></td>
	</tr>
	<tr>
		<td class="item_grisClaro" style="width: 202px">Impugnacion:</td>
		<td class="item_grisClaro" colspan="3"></td>
	<tr>
		<td>
			<input id="chkImpugnacion_0" type="radio" name="chkImpugnacion" value="S"
				<?php if(isset($PJ_IMPUGNACION)) 
						if($PJ_IMPUGNACION == 'S') echo "checked";
						else echo "unchecked"; ?> />
			<label for="chkImpugnacion_0">Si</label>		</td>				
		<td>
			<input id="chkImpugnacion_1" type="radio" name="chkImpugnacion" value="N" 
							<?php if(isset($PJ_IMPUGNACION)) 
						if($PJ_IMPUGNACION == 'N') echo "checked";
						else echo "unchecked"; ?> />
			<label for="chkImpugnacion_1">No</label>		</td>
	</tr>
	<tr>
		<td valign="top" class="item_grisClaro" style="width: 202px">Resultados:</td>
		<td colspan="3" class="item_grisClaro" height="150px">
			<textarea name="txtResultados" id="txtResultados" class="text_area" style="width: 92%; height: 173px">
				<?php if(isset($PJ_RESULTADOPERITAJE)) echo chop($PJ_RESULTADOPERITAJE); else echo "Resultado"; ?>
			</textarea></td>
	</tr>	
		<tr>
		<td class="item_grisClaro" style="width: 202px">		</td>
		<td class="item_grisClaro" colspan="3"></td>
	</tr>

	<tr>
		<td>
			<input type="submit" class="btnAceptar1" value="Aceptar" name="Aceptar"   />
			<input type="submit" class="btnCancelar1" value="Cancelar" name="Cancelar" /></td>	
	</tr>
	<tr>
		<td class="item_grisClaro" style="width: 202px"> </td>
		<td class="item_grisClaro" colspan="3">
			<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
		</td>
	</tr>
	

</table>		

</div>

</form>