<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");

@session_start();
$PageBase = new PageBase(false);

ValidarUserSession();

AsignarNroJuicioSession();	
extract(ObtenerMasDatosJuicios($_SESSION["NroJuicio"]) ,EXTR_PREFIX_ALL, "OMDJ");

BloqueaControlesJS();

$usuario = $_SESSION["usuario"];
$idJuicio = $_SESSION["NroJuicio"];
echo "<script type='text/javascript'> var usuarioSESSION = '".$usuario."'; </script>";
echo "<script type='text/javascript'> var idJuicioSESSION = ".$idJuicio."; </script>";

$PageBase->AgregarEncabezadoJS(true,false,true,true, false, false);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/MasDatosJuicioWebForm.js?rnd=".RandomNumber());
$PageBase->AgregarEncabezadoCSS(true,true,true);
$PageBase->AgregarDivProcesando();
$PageBase->ActivarGifProcesando();
$PageBase->CrearVentanaMensajeOculta("Mas Datos","mensaje","ACEPTAR");	
?>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow: hidden;">		
<form name="MasDatosJuicioWebForm" method="post" action="" id="idMasDatosJuicioWebForm" onsubmit="return ValidarFormMasDatosJuicioWeb();">
	
<?php 								        	
	echo TablaDatosUsuario($_SESSION["usuario"]);																
	echo TablaDatosJuicio($_SESSION["NUMEROCARPETA"], $_SESSION['DESCRIPCARATULA'] ); 
?>

<table class="table_General" align='left' >	
	<tr>
		<td colspan="2" class="title_NegroFndAzul">Datos del Juzgado</td>
	</tr>
	<tr>
		<td colspan="2" class="item_Blanco" height="5"><br></td></tr>	
	<tr>
		<td class="item_Blanco" width="13%" style="height: 19px">Juzgado:</td>
		<td class="item_Blanco" width="87%" style="height: 19px">
			<span id="txtJuzgado">
			<font color="DarkBlue" face="Arial" size="1">
				<?php echo $_SESSION["JURISDICCION_DESCRIPCION"]; ?>
			</font></span></td></tr>
	
	<tr>
		<td class="item_Blanco" width="13%">Domicilio:</td>
		<td  class="item_Blanco" width="100%">
			<input name="txtDomicilio" maxlength="200" value="<?php echo $OMDJ_JZ_DIRECCION; ?>" 
				id="txtDomicilio" class="input_text" type="text" style="width: 80%">
		<div class="input_textError" id="ErrorestxtDomicilio"></div>
		</td></tr>				
	<tr>
		<td class="item_Blanco" width="13%">Teléfonos :</td>
		<td class="item_Blanco" width="87%">
		<input name="txtTelefonos" maxlength="20" value="<?php echo $OMDJ_JZ_TELEFONO; ?>" 
			id="txtTelefonos" class="input_text" type="text" style="width: 80%">
		<div class="input_textError" id="ErrorestxtTelefonos"></div>
		</td></tr>	
	<tr>
		<td class="item_Blanco" width="13%">Fax:</td>
		<td class="item_Blanco" width="87%">
		<input name="txtFax" maxlength="20" value="<?php echo $OMDJ_JZ_FAX; ?>" 
			id="txtFax" class="input_text" type="text" style="width: 80%">
		<div class="input_textError" id="ErrorestxtFax"></div>
		</td></tr>	
	<tr>
		<td class="item_Blanco" width="13%">Email:</td>
		<td class="item_Blanco" width="87%">
		<input name="txtEmail" maxlength="70" value="<?php echo $OMDJ_JZ_EMAIL; ?>" 
			id="txtEmail" class="input_text" type="text" style="width: 80%">
		<div class="input_textError" id="ErrorestxtEmail"></div>
		</td>
		</tr>	
</table>
		<div class="input_textError" id="lblErrores"></div>		

<?php if(!$_SESSION["JUICIOTERMINADO"] ) {  ?>				
			<input type="button"  name="btnAceptar" value="" id="idbtnAceptar" class="btnAceptarEJ btnHover">
			<input type="button" name="MasDatosJuicioCancel" value="" class="btnCancelarEJ btnHover" onClick="history.back(-1);" >			
<?php }  ?>							
			<input type="hidden" name="hiddenBtnAceptar" id="idhiddenBtnAceptar" value="N" />			
			<input type="hidden" name="hiddenNroJuicio" id="idhiddenNroJuicio" value="<?php echo $_SESSION["NroJuicio"]; ?>" />
			<br>	
			<input class="btnVolver" type="button" value="" onClick="history.back(-1);"/>
</form>
</div>
<?php $PageBase->DesactivarGifProcesando(); ?>