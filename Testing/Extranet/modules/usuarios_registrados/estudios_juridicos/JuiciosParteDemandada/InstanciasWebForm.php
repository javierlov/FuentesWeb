<?php 
if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/InstanciasWebForm.Grid.php");
ValidarUserSession();
?>

<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/MasDatosJuicioWebForm.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js" type="text/javascript"></script>


<title>Seguimiento de Juicios y Concursos</title>
		
<form name="nameInstanciasWebForm" method="post" action="/InstanciasWebForm" id="idInstanciasWebForm">

<div align="left" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:20px; margin-top:8px; overflow:auto; width:96%;">		
<table cellspacing="0" cellpadding="0" width="90%" align="center" bgcolor="#ffffff" class="body_border">
	<tr>
		<td colspan="4" align="left">
			<?php 					
				//print_r($_REQUEST);								
				//TABLA CON DATOS DE ENCABEZADO
				TablaDatosUsuario($_SESSION["usuario"]);												
			?>			
		</td>
	</tr>
	<tr> <td height="5px" colspan="4" bgcolor="#ffffff" ></td></tr>
	
	<tr>
		<td height="16" colspan="4" bgcolor="#808080">
			<b>
			<font face="Verdana" style="FONT-SIZE: 8pt" color="#ffffff">Datos del Juicio</font></b></td></tr>
	<tr>
		<td height="16" width="11%" bgcolor="#e7e7e7" align="left">
			<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Nro. Carpeta:</font></td>
		<td height="16" bgcolor="#e7e7e7" style="width: 31%">
			<p align="left">
			<span id="UserControl1_txtNroCarpeta">			
			<b>
			<font face="Arial" color="DarkBlue" size="1">
				<?php $_SESSION["NUMEROCARPETA"]; ?>
			</font></b></span></td>
		<td height="16" width="6%" bgcolor="#e7e7e7" align="right">
			<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Caratula:</font></td>
		<td height="16" width="60%" bgcolor="#e7e7e7" align="left">
			<span id="UserControl1_txtCaratula">			<b>
			<font face="Arial" color="DarkBlue" size="1">
				<?php echo $_SESSION["DESCRIPCARATULA"]; ?> 
			</font></b></span></td></tr>
	<tr><td height="2"></td></tr></td></tr>
	<tr> <td height="5px" colspan="4" bgcolor="#ffffff" ></td></tr>
	<tr>
		<td colspan="4" class="title_NegroFndGrisClaro">Siniestros</td></tr>
	<tr>
		<td height="5"></td></tr>
	<tr  >
		<td  colspan="4"  style="width:100%">			
		</td></tr>
	<tr>
		<td align="center" colspan="4" height="10">							
		</td></tr>	
</table>

<div align="left" id="divContentGrid" name="divContentGrid" 
	style="height:100%; margin-left:20px; margin-top:8px; overflow:auto; width:96%;">		
	<?php 
		echo getGrid($_SESSION["NroJuicio"] );
	?>
</div>

<input class="btnVolver" type="button" value="" onClick="window.location.href = '/AdminWebForm';"/>				

</div>
</form>
