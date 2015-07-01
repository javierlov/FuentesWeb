<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/get_grid.php");
	
print_r($_REQUEST);	
$pidUsuario = $_REQUEST["idUsuario"];
$pCodCaratula = $_REQUEST["codigoCaratula"];
$pNroExpediente = $_REQUEST["NroExpediente"]; 

$pNroCarpeta = $_REQUEST["NroCarpeta"];
$ptipoJuicio = $_REQUEST["cmbTipoJuicio"];
	
if ((isset($_SESSION["isAbogado"])) and (!$_SESSION["isAbogado"]) and (!isset($pidUsuario))) {	
	echo "<script type='text/javascript'>window.location.href = '/logout.php'</script>";	
	exit;
}	
?>	
	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<!--div align="center" id="divContent" name="divContent"-->
	<div align="left" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:20px; margin-top:8px; overflow:auto; width:712px;">		
<?php
	///////function getGrid($idUsuario, $CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio) {	
	echo getGrid($pidUsuario, $pCodCaratula, $pNroExpediente, $pNroCarpeta, $ptipoJuicio);
	echo "<br/><a href=/Juicios-Parte-Demandada><input class='btnVolver' type='button' value=''></a>"; 	
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
	
