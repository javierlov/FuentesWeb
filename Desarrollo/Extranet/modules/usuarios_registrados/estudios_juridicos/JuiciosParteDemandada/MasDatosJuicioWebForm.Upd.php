<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");

@session_start();
$PageBase = new PageBase(false);
ValidarUserSession();
	
  function UpdateMasDatos(){
	try{
			
			///ValidarUserSession();
			
			$Domicilio = $_REQUEST["txtDomicilio"];
			$Telefonos = $_REQUEST["txtTelefonos"];
			$Fax = $_REQUEST["txtFax"];
			$Email = $_REQUEST["txtEmail"];
			$usuario = $_SESSION["usuario"];
			$idJuicio = $_REQUEST["hiddenNroJuicio"];
						
			UpdateMasDatosJuicios($Domicilio, $Telefonos, $Fax, $Email, $usuario, $idJuicio);		
		}
		catch (Exception $e) {		
			echo rawurlencode($e->getMessage());			
		}
	?>		
	<script type="text/javascript">
		function goBack() {		
			//window.parent.location.href = '/AdminWebForm';
			window.history.go(-2);
		}
		alert('Los datos fueron actualizados.....');
		//esperar unos seg...
		setTimeout('goBack()', 1000);
		
	</script>
<?php	
  }
