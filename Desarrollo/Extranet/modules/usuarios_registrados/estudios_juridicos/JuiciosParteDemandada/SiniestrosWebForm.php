<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/SiniestrosWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

@session_start(); 

ValidarUserSession();

if(isset($_REQUEST["NroJuicio"])) 			    
AsignarNroJuicioSession();	
$NroJuicio = $_SESSION["NroJuicio"];

//SiniestrosWebForm
?>

<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/jquery.js"></script>
<!--
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/MasDatosJuicioWebForm.js" type="text/javascript"></script>
-->
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js?rnd="<?php echo RandomNumber(); ?>  type="text/javascript"></script>

<div align="center" id="divProcesando" name="divProcesando" style="display:none">
	<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>

<div align="center" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral">		

<?php 	
	echo "<script> BuscarWGTrue(); </script>";
	echo TablaDatosUsuario($_SESSION["usuario"]);												
	echo TablaDatosJuicio($_SESSION["NUMEROCARPETA"], $_SESSION['DESCRIPCARATULA']); 
?>
<table class="table_General" style="width:100%;">

	<tr>
		<td class="title_NegroFndAzul" >Siniestros</td>		
	</tr>	
	<tr>	
		<td>
		<?php 		
			echo getGridSiniestros($_SESSION["NUMEROCARPETA"]);			
			echo "<script> BuscarWGFalseInterval(); </script>";			
		?>
		</td>			
	</tr>		
</table>		

<input class="btnVolver"  name="btnVolver" type="button" value="" onclick="window.location.href='/AdminWebForm'; " />	

</div>