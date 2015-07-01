<?php 
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/InstanciasWebForm.Grid.php");

@session_start(); 
ValidarUserSession();

if(isset($_REQUEST["SentenciaWebForm"])){	

	echo "<script type='text/javascript'>
			var r = confirm('Debe completar la sentencia antes de hacer el cambio.');								
			if(r == true){				
				window.location.href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?SentenciaWebFormCompletar';				
			}else{
				window.history.back(-1);				
			}			
		</script>";
}	

?>

<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css" rel="stylesheet" type="text/css" />		
<link href="/styles/style.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="/js/jquery.js" ></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js?rnd="<?php echo RandomNumber(); ?> type="text/javascript"></script>

<title>Seguimiento de Juicios y Concursos</title>		

<div align="center" id="divProcesando" name="divProcesando" style="display:none">
	<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" >		
<form name="nameInstanciasWebForm" method="post" action="/InstanciasWebForm" id="idInstanciasWebForm" style="overflow: scroll;">
	
<?php 
	echo "<script> BuscarWGTrue(); </script>";
	echo TablaDatosUsuario($_SESSION["usuario"]); 
	echo TablaDatosJuicioEstado(); 	
	echo "<table class='table_General' ><tr class='title_NegroFndAzul' height='18px'><td> Siniestros</td></tr></table>";
	echo getGridInstancias($_SESSION["NroJuicio"] );
	echo "<script> BuscarWGFalseInterval(); </script>";			
?>

<input class="btnVolver" type="button" value="" onClick="window.location.href = '/AdminWebForm';"/>				

</form>
</div>


