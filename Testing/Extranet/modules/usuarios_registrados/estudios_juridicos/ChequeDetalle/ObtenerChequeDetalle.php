<?php
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

<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>

	<div align="left" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:20px; margin-top:8px; overflow:auto; width:712px;">		
	<?php
		echo getGrid();
		echo "<a href='".$_SERVER['HTTP_REFERER']."'><input class='btnVolver' type='button' value=''></a>"; 	
	?>
	</div>
		<!--/div-->
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
	
