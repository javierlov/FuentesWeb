<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/JuiciosParteDemandada.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/JuiciosParteDemandada.Form.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

ValidarUserSession();

	$pidUsuario = $_SESSION["idUsuario"];
	$pCodCaratula = "";
	$pNroExpediente = ""; 
	$pNroCarpeta = "";
	$ptipoJuicio = "";

	if($_POST){		
		if (isset($_REQUEST["codigoCaratula"]) and (trim($_REQUEST["codigoCaratula"]) != "") ){
			$pCodCaratula = clearString($_REQUEST["codigoCaratula"]);
			//$pCodCaratula = specialchars($_REQUEST['codigoCaratula']);
		}			
		if (isset($_REQUEST["NroExpediente"]) and (trim($_REQUEST["NroExpediente"]) != "") ){
			$pNroExpediente = clearString($_REQUEST["NroExpediente"]);
		}			
		if (isset($_REQUEST["NroCarpeta"]) and (trim($_REQUEST["NroCarpeta"]) != "") ){
			$pNroCarpeta = clearString($_REQUEST["NroCarpeta"]);
		}
		if (isset($_REQUEST["cmbTipoJuicio"]) and (trim($_REQUEST["cmbTipoJuicio"]) != "") ){
			$ptipoJuicio = clearString($_REQUEST["cmbTipoJuicio"]);
		}	
	}
	

?>


<title>Seguimiento de Juicios y Concursos</title>
<link href="/styles/style.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		

<script type="text/javascript" src="/js/jquery.js"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/JuiciosParteDemandada.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/clientes.js" type="text/javascript"></script>

<!-------------------------------------------------------------------------------------------->		
<script type="text/javascript"> addEventListener('load',inicio,false);	</script>			
<!-------------------------------------------------------------------------------------------->		

	
<FORM action="/Juicios-Parte-Demandada" method="post">
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>

<div align="left" id="divContentGrid" name="divContentGrid" >					
	<!-------------------------------------------------------------------------------------------->		
	<input id="idUsuario" name="idUsuario" type="hidden" value="<?= $_SESSION["idUsuario"] ?>" />

	<div align="left" id="divContentGrid" name="divContentGrid" >					
		<?php 
			//style="height:100%; margin-left:8px; margin-top:8px; overflow:auto; width:100%;"
			Get_Formulario( $_SESSION["usuario"], $pCodCaratula, $pNroExpediente, $pNroCarpeta, $ptipoJuicio);			
		?>
		<hr>
	</div>
	<!-------------------------------------------------------------------------------------------->                                           
	<div align="left" id="divContentGrid" name="divContentGrid" >		
		<?php                                  
			if($_POST){
				if(ValidarCampos($pCodCaratula, $pNroExpediente, $pNroCarpeta, $ptipoJuicio) ) 	
					echo getGrid( clearString($pidUsuario), 
						clearString($pCodCaratula), 
						clearNumber($pNroExpediente), 
						clearNumber($pNroCarpeta), 
						clearNumber($ptipoJuicio));                                                                                                                   
				else 
					echo "<h4 style='color: #FF0000;' >Los valores para algunos Campos son incorrectos<h4>";
					
				echo "<a href='".$_SERVER['HTTP_REFERER']."'><input class='btnVolver' type='button' value=''></a>"; 			
			}
		?>
	</div>  
	<!-------------------------------------------------------------------------------------------->								
	<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
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
	<!-------------------------------------------------------------------------------------------->							
</div>
</FORM>
