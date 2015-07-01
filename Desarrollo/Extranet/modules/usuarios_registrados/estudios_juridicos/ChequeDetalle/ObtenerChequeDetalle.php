<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ChequeDetalle/ObtenerChequeDetalle.Grid.php");
	
if ((isset($_SESSION["isAbogado"])) and (!$_SESSION["isAbogado"])) {
	echo "<script type='text/javascript'>window.location.href = '/logout.php'</script>";	
	exit;
}	
	
if (!isset($_SESSION["idUsuario"])) {
?>
	<script type="text/javascript">
		window.location.href = '/acceso-exclusivo-usuarios-registrados';			
	</script>
<?php
	exit;
	}
?>
<link href="/styles/style.css" rel="stylesheet" type="text/css" />	
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		

<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js" type="text/javascript"></script>

<div align="center" id="divProcesando" name="divProcesando" style="display:none">
		<img border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>

<div align="left" id="divContentGrid" name="divContentGrid"  class="divContenedorGeneral" >		
	<?php 
		echo "<script> BuscarWGTrue(); </script>";
		echo TablaDatosUsuario($_SESSION["usuario"]);	
	?>			
			<div  align='left' class='divAbajo' >
	<?php
		echo "<div  style='overflow:hidden; width:100%;' >".getGridChequeDetalle()."</div>";
		echo "<div class='alineaIzq gridHeader' style='font-size: 14px; height:24px; margin: 1px 1px;'  > Total Importes: ".SumaImporteChequeDetalle()."</div>";
		
		echo "<a href='".$_SERVER['HTTP_REFERER']."'><input class='btnVolver' type='button' value=''></a>"; 	
		echo "<script> BuscarWGFalseInterval(); </script>";			
	?>
		</div>

</div>