<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ChequesDisponible.Grid.php");
	
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

	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="" id="formGrid" method="post" name="formGrid">				
		<?php
			echo getGrid();	
			echo "<a href='/Seleccion-Aplicacion'><input class='btnVolver' type='button' value=''></a>"; 				
		?>
		</form> 		
		<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif"
			title="Espere por favor..."></div>
		<script type="text/javascript">
			function CopyContent() {
				try {
					window.parent.document.getElementById('divContent').inner = document.getElementById('divContent').inner;
				}
				catch(err) {
					//
				}
			}
			CopyContent();
		</script>

