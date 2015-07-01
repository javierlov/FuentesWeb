<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/JuiciosParteDemandada.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

@session_start(); 
 
ValidarUserSession();

///////initialization///////
//setear navegacion de paginas
$_SESSION["PagePrevEventosWeb"] = "/JuiciosParteDemandada";
$_SESSION["PagePrevSentencia"] = "/JuiciosParteDemandada";
$_SESSION["PagePrevPeritajes"] = "/JuiciosParteDemandada";
$_SESSION["PagePrevABMWebForm"] = "/JuiciosParteDemandada";
unset($_SESSION['Parte']);

//Esta variable guarda el estado de contrato si es terminado "T" no se debe poder modificar
$pidUsuario = $_SESSION["idUsuario"];
$pCodCaratula = "";
$pNroExpediente = ""; 
$pNroCarpeta = "";
$ptipoJuicio = 0;
$BuscarActivo = false;
$_SESSION["JUICIOTERMINADO"] = false;	
	
///////implementation///////
	
	//-------------------------------------------------------------------------------------
	unset($_SESSION["ArrayJuiciosParteDemandada"]);
	if( isset($_REQUEST["codigoCaratula"]))	 $pCodCaratula = $_REQUEST["codigoCaratula"];		
	if( isset($_REQUEST["NroExpediente"]))	 $pNroExpediente = $_REQUEST["NroExpediente"];		
	if( isset($_REQUEST["NroCarpeta"]))	 $pNroCarpeta = $_REQUEST["NroCarpeta"];		
	if( isset($_REQUEST["cmbTipoJuicio"]))	 $ptipoJuicio = $_REQUEST["cmbTipoJuicio"];		
	if( isset($_REQUEST["btnBuscar"])) $BuscarActivo = true;
	
	$redirectpage = '/JuiciosParteDemandada';	
	//$redirectpage = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?JuiciosParteDemandadaPRG";		

?>
<link href="/styles/style.css" rel="stylesheet" type="text/css" />		
<link href="/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css" rel="stylesheet" type="text/css" />		

<script src="/js/jquery.js" type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/clientesEstudios.js?rnd="<?php echo RandomNumber(); ?> type="text/javascript"></script>
<script src="/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js?rnd="<?php echo RandomNumber(); ?> type="text/javascript"></script>

<script src="/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/JuiciosParteDemandada.js?rnd="<?php echo RandomNumber(); ?> type="text/javascript"></script>

<form action="<?php $redirectpage ?>" method="POST" 
		name="JuiciosParteDemandada" id="idJuiciosParteDemandada" >

	<input name="idUsuario" type="hidden" value="<?= $_SESSION["idUsuario"] ?>" />		
	<input name="Redirect" type="hidden" value="/JuiciosParteDemandada" />		
	<input name="JuiciosParteDemandada" type="hidden" value="Redirect" />		
	
	<table class="table_General" align='center' >
		<tr height="2px" colspan="4" class="celdaFondoBlanco" >	
			<td  colspan="5" class="TituloSeccion" >Juicios Parte Demandada</td>
		</tr>
		<tr height="2px" colspan="4" class="celdaFondoBlanco" >	
			<td  colspan="5" class="ContenidoSeccion" >
			<div> 
				<!--
				Breve descripcion de esta pantalla donde se describe que se puede hacer..
				-->
			</div>
			</td>
		</tr>
		<tr height="40px">
			
			<td   class="item_grisClaroFndBlanco" > Carátula: </td>
			<td>
				<input name="codigoCaratula" type="text" id="idCaratula" class="numerico" 
						value="<?php if(isset($pCodCaratula)) echo $pCodCaratula; ?>" /></td>
			<td   class="item_grisClaroFndBlanco" > Número de expediente: </td>
			<td> <input name="NroExpediente" type="text" id="txtNroExpediente" class="numerico" 
					value="<?php if(isset($pNroExpediente)) echo $pNroExpediente; ?>"  /> </td>
			<td style="margin-left:8px; margin-top:8px;">
				<input type="button" class="btnLimpiarEJ btnHover" value=""  id="botonLimpiar" value="" > 				
				</td>
		</tr>
		<tr>
			<td  class="item_grisClaroFndBlanco" > Nro Carpeta: </td>
			<td> 
				<input name="NroCarpeta" type="text" id="txtNroCarpeta" class="numerico" 
					Value="<?php if(isset($pNroCarpeta)) echo $pNroCarpeta; ?>" />
				<font face="Verdana" style="FONT-SIZE: 8pt; FONT-WEIGHT: 700"> </font> 
				</td>
			<td  class="item_grisClaroFndBlanco" > Tipo Juicio: </td>
			<td><?php echo SelectArrayOptions($ptipoJuicio);	?></td>
			<td style="margin-left:8px; margin-top:8px;">												
				<input type="button" class="btnImprimirEJ btnHover" value="" id="botonImprimir1" onclick="window.location.href = '/ImpresionJuicios'; " />			
			</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td style="margin-left:8px; margin-top:0px;">					
				<input type="submit"  class="btnBuscarEJ btnHover" id="idbtnBuscar" name="btnBuscar" value="">
				</td>
		</tr>
	</table>								
<p>
<!-------------------------------------------------------------------------------------------->
	<div align="center" id="divContentGrid" name="divContentGrid" >		
		<?php 
			if($BuscarActivo){				
				echo getGridJuiciosParteDemada( Trim($pidUsuario), 
							Trim($pCodCaratula), 
							Trim($pNroExpediente), 
							Trim($pNroCarpeta), 
							Trim($ptipoJuicio) );		
			}
		?>		
	</div>
		<input class="btnVolver"  name="btnVolver" type="button" onClick="window.location.href ='/SeleccionAplicacion';"/>
</FORM>		
	<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif"
			title="Espere por favor..."></div>
		
