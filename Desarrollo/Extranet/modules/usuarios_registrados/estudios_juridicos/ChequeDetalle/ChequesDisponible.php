<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ChequeDetalle/ChequesDisponible.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
	
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

<form action="" id="formGrid" method="post" name="ChequesDisponible">				
<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral">		

		<?php
			echo "<script> BuscarWGTrue(); </script>";
			echo TablaDatosUsuario($_SESSION["usuario"]);	
		?>			
			<div  align='left' class='divAbajo' >
		<?php
			echo getGridChequesDisponible();			
			echo "<script> BuscarWGFalseInterval(); </script>";			
		?>
			</div>
		<input class="btnVolver"  name="btnVolver" type="button" onClick="window.location.href = '/SeleccionAplicacion';"/>
		</form> 	
</div>


