<?php 
if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Upd.php") ;
ValidarUserSession();

if(isset($_REQUEST['btnAceptar'])){
	UpdateMasDatos();
} 

?>

<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/MasDatosJuicioWebForm.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js" type="text/javascript"></script>

<form name="MasDatosJuicioWebForm" method="post" action="" id="idMasDatosJuicioWebForm" onsubmit="ValidarFormMasDatosJuicioWeb()">

<table class="body_border" align="center" bgcolor="#ffffff" cellpadding="0" cellspacing="0" width="90%">
	<tr>
		<td colspan="2" height="2">
			<?php 					
			    ///print_r($_SESSION);			    
			    if(isset($_REQUEST["NroJuicio"])) 			    
			    	$_SESSION["NroJuicio"] = $_REQUEST["NroJuicio"];
			    $NroJuicio = $_SESSION["NroJuicio"];
	
				//TABLA CON DATOS DE ENCABEZADO
				TablaDatosUsuario($_SESSION["usuario"]);												
							
				extract(ObtenerMasDatosJuicios($NroJuicio) );																	
				
			?>
		</td>
	</tr>	
	
	<tr>
		<td colspan="2">
			<TABLE  cellspacing='0' cellpadding='2' width='100%' border='0' align='center' >
			<TR  height='2px' colspan='4'  > 
			<TR> </TR>
			<TD  colspan='4' bgcolor='#808080' align='left'  > 
			<b>
			<font face='Verdana' style='font-size: 8pt' color='#FFFFFF'>Datos del Juicio</font></b> </TD>
			<TR> </TR>
			<TD  width='6%' bgcolor='#E7E7E7' align='left'  > 
			<font color='#808080' face='Verdana' style='font-size: 8pt'>Nro. Carpeta:</font> </TD>
			<TD  width='33%' bgcolor='#E7E7E7' align='left'  > 
			<font face='Verdana' style='font-size: 8pt'>			
			<span id='DatosEstudioUserControl_txtUsuario'>
			<b>
			<font face='Arial' color='DarkBlue' size='1'>
				<?php echo $_SESSION["NUMEROCARPETA"]; ?></font></b></span></font> </TD>
			<TD width='13%' bgcolor='#E7E7E7' align='right' > 
			<font face='Verdana' style='font-size: 8pt; ' color='#808080'>Caratula:</font> </TD>
			<TD width='65%' bgcolor='#E7E7E7' align='left' > 
			<font face='Verdana' style='font-size: 8pt'>
			<span id='DatosEstudioUserControl_txtEstudio'>
			<b>
			<font face='Arial' color='DarkBlue' size='1'>
				<?php echo $_SESSION["DESCRIPCARATULA"]; ?></font></b></span></font> </TD>
			<TR> </TR>
			<TD height='2px' colspan='4' >  </TD>
			</table>
		</td>
	</tr>
	
	<tr>
		<td colspan="2" class="title_NegroFndGrisClaro">Datos del Juzgado</td>
	</tr>
	<tr>
		<td colspan="2" class="item_grisClaro" height="5"><br></td></tr>	
	<tr>
		<td class="item_grisClaro" width="13%" style="height: 19px">Juzgado:</td>
		<td class="item_grisClaro" width="87%" style="height: 19px">
			<span id="txtJuzgado">
			<font color="DarkBlue" face="Arial" size="1">
				<?php echo $_REQUEST["hiddenJurisdiccion"]; ?>
			</font></span></td></tr>
	<tr><td style="height: 1px" ></td></tr>
	<tr>
		<td class="item_grisClaro" width="13%">Domicilio:</td>
		<td  class="item_grisClaro" width="100%">
		<input name="txtDomicilio" value="<?php echo $JZ_DIRECCION; ?>" 
			id="txtDomicilio" 
			class="input_text" 
			type="text" style="width: 80%"></td></tr>
	<tr><td style="height: 1px" ></td></tr>
	<tr>
		<td class="item_grisClaro" width="13%">Telefonos:</td>
		<td class="item_grisClaro" width="87%">
		<input name="txtTelefonos" value="<?php echo $JZ_TELEFONO; ?>" 
			id="txtTelefonos" class="input_text" type="text" style="width: 80%"></td></tr>
	<tr><td style="height: 1px" ></td></tr>
	<tr>
		<td class="item_grisClaro" width="13%">Fax:</td>
		<td class="item_grisClaro" width="87%">
		<input name="txtFax" value="<?php echo $JZ_FAX; ?>" 
			id="txtFax" class="input_text" type="text" style="width: 80%"></td></tr>
	<tr><td style="height: 1px" ></td></tr>
	<tr>
		<td class="item_grisClaro" width="13%">Email:</td>
		<td class="item_grisClaro" width="87%">
		<input name="txtEmail" value="<?php echo $JZ_EMAIL; ?>" 
			id="txtEmail" class="input_text" type="text" style="width: 80%"></td></tr>
	<tr><td style="height: 1px" ></td></tr>
	<tr>
		<td colspan="2" class="item_grisClaro" height="5" width="100%"><br></td></tr>
	<tr>
		<td colspan="2" height="15" width="100%"><br></td></tr>
	<tr>
		<td colspan="2" width="100%"> 			
			
			<input name="btnAceptar" value="Aceptar" id="idbtnAceptar" type="submit">
				<!--
				onclick="this.form.action='/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Upd.php'; this.form.submit();"
				-->
			
			<input name="MasDatosJuicioCancel" value="Cancelar" onClick="history.back(-1);" type="button">			
			
			<input type="hidden" name="hiddenBtnAceptar" id="idhiddenBtnAceptar" value="N" />			
			<input type="hidden" name="hiddenNroJuicio" id="idhiddenNroJuicio" value="<?php echo $NroJuicio; ?>" />
			
		</td></tr>			
	<tr>
		<td colspan="2" align="center" height="50" width="100%"> 
			<input class="btnVolver" type="button" value="" onClick="history.back(-1);"/>
		</td>
</table>
</form>
