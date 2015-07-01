<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/PeritajesABMWebForm.Grid.php");
@session_start(); 

$PageBase = new PageBase(false);

ValidarUserSession();

$PeritoNombre = '';
$PeritoApellido = '';
$PeritoParteoficio = '';

$PeritoDireccion = '';
$PeritoEMail = '';
$PeritoTelefono = '';

$PeritoCuit1 = '';
$PeritoCuit2 = '';
$PeritoCuit3 = '';

$IdPeritoEdit = '0';
$Accion =  'ALTA'; 
$cmbTipoPericiaValor = '';

//LimpiarConstPeritajes();

if(isset($_REQUEST['btnCancelar'])){
	
	$redirectpage = $_SERVER["HTTP_REFERER"];
	
	echo "<script type='text/javascript'> 
		window.location.href = '".$redirectpage."';
	</script>";	
}

if(isset(  $_SESSION["PeritajesABMWebForm"]["cmbTipoPericia"]))	
	$_SESSION["TipoPerito"] =  $_SESSION["PeritajesABMWebForm"]["cmbTipoPericia"];

if(isset( $_SESSION["PeritajesABMWebForm"]["cmbTipoPericiaValor"]))	
	$cmbTipoPericiaValor =  $_SESSION["PeritajesABMWebForm"]["cmbTipoPericiaValor"]; else $cmbTipoPericiaValor = '';

if(isset( $_SESSION["PeritajesABMWebForm"]["Accion"]))	
	$Accion =  $_SESSION["PeritajesABMWebForm"]["Accion"]; 


$usuario = $_SESSION["usuario"]; 
if(isset($_SESSION["TipoPerito"])) 	
	$tipoperito = $_SESSION["TipoPerito"]; else $tipoperito = 0;
	
echo "<script type='text/javascript'> 			
			var tipoperito = ".$tipoperito.";
			var usuario = '".$usuario."';
			var Accion = '".$Accion."'; 
			var RedirectPageAnt = '".$_SERVER["HTTP_REFERER"]."'; 
		</script>";
		
if(isset( $_SESSION["PeritajesABMWebForm"]["IdPeritoEdit"])){ 
	$IdPeritoEdit =  $_SESSION["PeritajesABMWebForm"]["IdPeritoEdit"];
	$Accion =  'EDIT'; 
}

if($Accion == 'EDIT' and $IdPeritoEdit > 0){
	echo "<script type='text/javascript'> 
			Accion = '".$Accion."'; 			
		  </script>";
	$jsonresult = BuscarPeritosListado('', false, '', '', $IdPeritoEdit);	
	
	$obj = json_decode($jsonresult, true);	
	foreach($obj as $value){			
		$PeritoApellido = utf8_decode($value["apellido"]);				
		$PeritoNombre = utf8_decode($value["nombreindividual"]);
		$PeritoDireccion = utf8_decode($value["direccion"]);
		$PeritoEMail = utf8_decode($value["email"]);
		$PeritoTelefono = utf8_decode($value["telefono"]);
		$PeritoParteoficio = utf8_decode($value["parteoficio"]);
		
		$PeritoCuit1 = substr($value["cuit"],0,2);
		$PeritoCuit2 = substr($value["cuit"],2,8);
		$PeritoCuit3 = substr($value["cuit"],10,1);		
	}
}

$PageBase->AgregarEncabezadoJS(true,true,true,true, true, true);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/PeritoABMWebForm.js?rnd=".RandomNumber());
$PageBase->AgregarEncabezadoJQUERYUI();
$PageBase->AgregarEncabezadoCSS(true,true,true);
$PageBase->AgregarDivProcesando();
?>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral">		
<form name="PeritoABMWebForm" method="post" action="/PeritoABMWebForm" id="idPeritoABMWebForm" onsubmit="return ValidarPeritoWebForm()"
style="overflow:hidden;">

<input type='hidden' id='idperito' value=<?php echo $IdPeritoEdit; ?> />

<?php 					
	$PageBase->ActivarGifProcesando();
	$PageBase->CrearVentanaMensajeOculta("Perito","¿Desea guardar <p> los cambios?","ACEPTAR");
	echo TablaDatosUsuario($_SESSION["usuario"]);												
	echo TablaDatosJuicio( $_SESSION["NUMEROCARPETA"], $_SESSION['DESCRIPCARATULA'] );	
?>

<table class="table_General" align='left' >
	<tr>
		<td colspan="2">			
		</td>
	</tr>

	<tr>
		<td class="title_NegroFndAzul" colspan="2"><label id="TituloPerito">Agregar Perito</<label></td>
	</tr>

	<tr>
		<td class="item_Blanco" colspan="2" height="5">
		</td>
	</tr>

	<tr>
		<td class="item_Blanco">Pericia:</td>
		<td class="item_Blanco">						
			<div class="TextoTablaEJ" id="idPericia"><?php echo $cmbTipoPericiaValor; ?></div>
		</td>
	</tr>
		
	<tr>
		<td class="item_Blanco">Designacion:</td>
		<td class="item_Blanco">
			<select name="cmbDesignacion" id="cmbDesignacion" class="combo">
				<option value=""></option>
				<option value="O" <?php if($PeritoParteoficio == 'O') echo "selected"; ?>  >Oficio</option>
				<option value="P" <?php if($PeritoParteoficio == 'P') echo "selected"; ?>  >Parte</option>
				<option value="C" <?php if($PeritoParteoficio == 'C') echo "selected"; ?>  >Parte Opositora</option>
			</select>
			<div class="input_textError" id="ErrorescmbDesignacion"></div>
		</td>
	</tr>
	
	<tr>
		<td class="item_Blanco">Cuit/Cuil:</td>
		<td class="item_Blanco">			
			<input name="txtcuil1" type="text" maxlength="2" id="txtcuil1" class="input_text" style="width:20px"	
				value="<?php echo $PeritoCuit1; ?>" />
			<input name="txtcuil2" type="text" maxlength="8" id="txtcuil2" class="input_text" 
				value="<?php echo $PeritoCuit2; ?>"  />			
			<input name="txtcuil3" type="text" maxlength="1" id="txtcuil3" class="input_text" style="width:20px" 
				value="<?php echo $PeritoCuit3; ?>" title="Complete CUIL. Enter para buscar"  
				onfocus="EstaCuilCompleto()" />
			
			<div class="item_Blanco" style="display:none; position: absolute; opacity:0.8; z-index:10; width:300px;" id="listaitems"></div>
			<div class="input_textError" id="Errorestxtcuil1"></div>			
		</td>
	</tr>
	
	<tr>
		<td class="item_Blanco">Apellido:</td>
		<td class="item_Blanco">			
			<input name="txtApellido" type="text" id="txtApellido" class="input_textUpper" style="width:270px;" 
				value="<?php echo $PeritoApellido; ?>" />
			<div class="input_textError" id="ErrorestxtApellido"></div>
		</td>
	</tr>

	<tr>
		<td class="item_Blanco">Nombre:</td>
		<td class="item_Blanco">			
			<input name="txtNombre" type="text" id="txtNombre" class="input_textUpper" style="width:270px;" 
				value="<?php echo $PeritoNombre; ?>" />
			<div class="input_textError" id="ErrorestxtNombre"></div>
		</td>
	</tr>
	
	<tr>
		<td class="item_Blanco">Direccion:</td>
		<td class="item_Blanco">			
			<input name="txtDireccion" type="text" maxlength="100" id="txtDireccion" class="input_text" style="width:370px;"
				value="<?php echo $PeritoDireccion; ?>" />
			<div class="input_textError" id="ErrorestxtDireccion"></div>
		</td>
	</tr>
	
	<tr>
		<td class="item_Blanco">E-Mail:</td>
		<td class="item_Blanco">			
			<input name="txtEMail" type="text" maxlength="50" id="txtEMail" class="input_text" style="width:270px;"
					value="<?php echo $PeritoEMail; ?>" />			
		</td>
	</tr>
	
	<tr>
		<td class="item_Blanco">Telefono:</td>
		<td class="item_Blanco">			
			<input name="txtTelefono" type="text" maxlength="50" id="txtTelefono" class="input_text" style="width:270px;" 
					value="<?php echo $PeritoTelefono; ?>" />			
		</td>
	</tr>
	
	
	<tr>
		<td class="item_Blanco"></td>
		<td class="item_Blanco"></td>
	</tr>	
	<tr><td height="100%" colspan="2"></td></tr>	
</table>

	<div class="input_textError" id="lblErrores"></div>
	<input type="button" name="btnAceptar" value="" id="idAceptarAjax" class="btnAceptarEJ btnHover" />
	<input type="button" name="btnCancelar1" value="" id="btnCancelar1" class="btnCancelarEJ btnHover" onclick="window.location.href = '<?=$_SERVER["HTTP_REFERER"]?>'"  />
	
<div style="position:fixed; left:50%;">
	<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
</div>	
	<br>			
	<input id="btnVolver" class="btnVolver" type="button" value="" onclick="window.location.href = '<?=$_SERVER["HTTP_REFERER"]?>'" />
</form>
</div>
<?php 
$PageBase->CrearVentanaDialogJQUI("Perito","Perito Actualizado / Ingresado."); 
$PageBase->DesactivarGifProcesando(); 
?>