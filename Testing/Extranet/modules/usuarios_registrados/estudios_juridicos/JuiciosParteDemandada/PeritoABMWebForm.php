<?php
if(!isset($_SESSION)) { session_start(); } 

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/PeritajesABMWebForm.Grid.php");

ValidarUserSession();

///var_dump($_SESSION);

$PeritoApellido = '';
if(isset($_SESSION["PeritoApellido"])){
	$PeritoApellido = $_SESSION["PeritoApellido"];	
	unset($_SESSION["PeritoApellido"]);
} 

$PeritoNombre = '';
if(isset($_SESSION["PeritoNombre"])){
	$PeritoNombre = $_SESSION["PeritoNombre"];
	unset($_SESSION["PeritoNombre"]);
} 


if(isset($_REQUEST['btnCancelar'])){
	echo "<script type='text/javascript'> 
		window.location.href = '/PeritajesABMWebForm';
	</script>";	
}

if(isset($_REQUEST['btnAceptar'])){
/////InsertarPeritoNuevo($nombre, $apellido, $cuil, $tipoperito, $parteoficio, $usuario, $direccion){	

	$nombre = $_REQUEST['txtNombre']; 
	$apellido = $_REQUEST['txtApellido'];
	$cuil = $_REQUEST['txtcuil1'].$_REQUEST['txtcuil2'].$_REQUEST['txtcuil3']; 

	$tipoperito = 0;
	if(isset($_SESSION["TipoPerito"])) 
		$tipoperito = $_SESSION["TipoPerito"]; 
	
	
	$parteoficio = $_REQUEST['cmbDesignacion']; 
	$usuario = $_SESSION["usuario"]; 
	$direccion = $_REQUEST['txtDireccion']; 

	$secuencia = InsertarPeritoNuevo($nombre, $apellido, $cuil, $tipoperito, $parteoficio, $usuario, $direccion);
	
	if($secuencia > 0){

		$_SESSION["TipoPerito"] = $tipoperito;
		$_SESSION["PeritoApellido"] = $apellido;
		$_SESSION["PeritoNombre"] = $nombre;
		$_SESSION["IDPERITO"] = $secuencia;

		echo "<script type='text/javascript'>
			alert('Se ingreso el perito...');	
			window.location.href = '/PeritajesABMWebForm';	
   	   </script>";
	}	
	else{
		echo "<script type='text/javascript'>
			alert('Error Insertando Perito. Revise los datos...');	
		  </script>";	
	}	
}

?>

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/PeritajesABMWebForm.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js" type="text/javascript"></script>

<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<form name="PeritoABMWebForm" method="post" action="/PeritoABMWebForm" id="idPeritoABMWebForm">
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


<table cellspacing="1" cellpadding="0"  width="100%" align="center" bgcolor="#ffffff" class="body_border">
	<tr>
		<td colspan="2"></td>
	</tr>

	<tr>
		<td class="title_NegroFndGrisClaro" colspan="2">Agregar Perito</td>
	</tr>

	<tr>
		<td class="item_grisClaro" colspan="2" height="5"></td>
	</tr>

	<tr>
		<td class="item_grisClaro">Designacion:</td>
		<td class="item_grisClaro">
			<select name="cmbDesignacion" id="cmbDesignacion" class="combo">
				<option value=""></option>
				<option value="O">Oficio</option>
				<option value="P">Parte</option>
				<option value="C">Parte Opositora</option>
			</select>
		</td>
	</tr>

	<tr>
		<td class="item_grisClaro">Apellido:</td>
		<td class="item_grisClaro">			
			<input name="txtApellido" type="text" id="txtApellido" class="input_textUpper"
				value="<?php echo $PeritoApellido; ?>" />
		</td>
	</tr>

	<tr>
		<td class="item_grisClaro">Nombre:</td>
		<td class="item_grisClaro">			
			<input name="txtNombre" type="text" id="txtNombre" class="input_textUpper"
					value="<?php echo $PeritoNombre; ?>" />
		</td>
	</tr>
	
	<tr>
		<td class="item_grisClaro">Direccion:</td>
		<td class="item_grisClaro">			
			<input name="txtDireccion" type="text" maxlength="100" id="txtDireccion" class="input_text" />
		</td>
	</tr>
	
	<tr>
		<td class="item_grisClaro">Cuit/Cuil:</td>
		<td class="item_grisClaro">			
			<input name="txtcuil1" type="text" maxlength="2" id="txtcuil1" class="input_text" style="width:20px" />
			<input name="txtcuil2" type="text" maxlength="8" id="txtcuil2" class="input_text" />
			<input name="txtcuil3" type="text" maxlength="1" id="txtcuil3" class="input_text" style="width:20px" />
		</td>
	</tr>
	
	<tr>
		<td class="item_grisClaro"></td>
		<td class="item_grisClaro"></td>
	</tr>
	
	<tr>
		<td height="100%" colspan="2">	</td>
	</tr>
	
	<tr>
		<td align="right"></td>
	</tr>
	
	<tr>
		<td>
			<input type="submit" name="btnAceptar" value="Aceptar" id="btnAceptar" class="submit" />
			<input type="submit" name="btnCancelar" value="Cancelar" id="btnCancelar" class="submit" />
	</td>
	</tr>
	
	<tr>
		<td align="center" height="50" colspan="2" >
			<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
	</td>
	</tr>
</table>

</div>
</form>