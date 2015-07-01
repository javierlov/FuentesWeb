<?php
if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/PeritajesWebForm.Grid.php");

ValidarUserSession();

if(isset($_REQUEST["NroJuicio"])) 
	$_SESSION["NroJuicio"] = $_REQUEST["NroJuicio"];

$NroJuicio = $_SESSION["NroJuicio"];

list($JT_NUMEROCARPETA, $DESCRIPCARATULA, $EJ_DESCRIPCION) = ObtenerNroCarpeta($NroJuicio);

$_SESSION["NUMEROCARPETA"] = $JT_NUMEROCARPETA;
$_SESSION["DESCRIPCARATULA"] = $DESCRIPCARATULA; 

///print_r($_SESSION); 
?>

<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/PeritajesWebForm.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js" type="text/javascript"></script>

<form name="PeritajesWebForm" method="post" action="/PeritajesABMWebForm" id="idPeritajesWebForm">


<div align="left" id="divContentGrid" name="divContentGrid" 
	style="height:100%; margin-left:20px; 
	margin-top:8px; 
	overflow:auto; 
	width:90%;">		

	<table cellspacing="0" cellpadding="0" width="100%" border="0" align="center">
		<tr>
			<td></td></tr>
		<tr>
			<td>
	<?php
		TablaDatosUsuario( $_SESSION["usuario"] );		
		?>
	</td></tr>
	<tr><td>

<input name="Language" value="English" type="hidden">			
</td></tr>
		<tr>
			<td>
	<table datasrc="#oXMLESTUDIO" cellspacing="0" cellpadding="0" width="100%" border="0" align="center">
		<tr>
			<td colspan="6" bgcolor="#808080" style="height: 16px">
				<b><font face="Verdana" style="FONT-SIZE: 8pt" color="#ffffff">Datos del Juicio</font></b></td></tr>
		<tr>
			<td width="11%" bgcolor="#e7e7e7" align="left" style="height: 16px">
				<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Nro. Carpeta:</font></td>
			<td width="6%" bgcolor="#e7e7e7" style="height: 16px">
				<p align="left">
				<span id="txtNroCarpeta1">
				<b><font face="Arial" color="DarkBlue" size="1">
				<?php echo $_SESSION["NUMEROCARPETA"] ?></font></b></span>
				</td>
			<td width="5%" bgcolor="#e7e7e7" align="right" style="height: 16px">
				<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Caratula:</font></td>
			<td width="25%" bgcolor="#e7e7e7" align="left" style="height:auto">
				<span id="txtCaratula"><b><font face="Arial" color="DarkBlue" size="1">
				<?php echo $_SESSION["DESCRIPCARATULA"] ?></font></b></span></td>
			<td width="5%" bgcolor="#e7e7e7" align="right" style="height: 16px">
				<font face="Verdana" style="FONT-SIZE: 8pt" color="#808080">Estado:</font></td>
			<td width="28%" bgcolor="#e7e7e7" align="left" style="height: 16px">
				<span id="txtCaratula"><b>
				<font face="Arial" color="DarkBlue" size="1"> 
					<?php echo $EJ_DESCRIPCION; ?></font></b></span></td>		</tr>
		<tr>
			<td height="3" colspan="6"></td>
	
			</tr></table></td></tr>
	<tr>
		<td>
			<div align="left" id="divContentGrid" name="divContentGrid" style="height:100%; 
				margin-left:0px; margin-top:8px; overflow:auto; width:100%;">		
			<?php
					echo getGrid($NroJuicio);
			?>		
			</div>
		</td>
	</tr>			
	<tr>
		<td>
			<input class='btnNuevo' name='btnNuevo' type="submit" value="" />
		</td>	</tr>
	<tr>
		<td align="center" colspan="2" height="50">				
			<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
		</td></tr>

	

</table>


</div>	
					
</form>

