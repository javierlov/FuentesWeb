<?php
///PeritajesAdjuntosWebForm.php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");

@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();
$_SESSION["NUMEROJUICIO"] = GetNumJuicioPorCarpeta($_SESSION["NUMEROCARPETA"]);


	$ERRORESSUBIRARCHIVO = '';
	if( isset($_SESSION['ERRORESSUBIRARCHIVO']) ){
		$ERRORESSUBIRARCHIVO = $_SESSION['ERRORESSUBIRARCHIVO'];		
		unset($_SESSION['ERRORESSUBIRARCHIVO']);
	}

	echo "
		<script type='text/javascript'>
			var ERRORESSUBIRARCHIVO = '".$ERRORESSUBIRARCHIVO."';			
		  </script> ";
		  
	$ERRORESSUBIRARCHIVO = '';
		
$PeritajeID = '0';	
$PageID = '0';
if(isset($_REQUEST["id"])){	$PeritajeID = $_REQUEST["id"]; }
if(isset($_REQUEST["pageid"])){	$PageID = $_REQUEST["pageid"]; }
$eaID = '0';

	$PageBase->AgregarEncabezadoJS(true,false,true,true, true, false);
	$PageBase->AgregarEncabezadoCSS(true,true,true);
	$PageBase->AgregarEncabezadoJQUERYUI();
	
	$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/PeritajesAdjuntosWebForm.js?rnd=".RandomNumber());	
	extract(DatosTipoPeritaje($PeritajeID) ,EXTR_PREFIX_ALL, "DTP");
	
	$bloquearPericia = false;
	
?>

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow-x: hidden; height:auto; width:auto;">		
<form name="PericiasABMWebForm" method="post" action="/index.php" id="PericiasAdjuntosWebForm" style="overflow: hidden;" >
<input type="hidden" value="<?php echo $PeritajeID; ?>" name="PeritajeID" id="id1">	
<input type="hidden" value="<?php echo $PageID; ?>" name="PageID" id="id2">	

<?php	
echo TablaDatosUsuario($_SESSION["usuario"]);		
echo TablaDatosJuicio($_SESSION["NUMEROCARPETA"], $_SESSION['DESCRIPCARATULA']); 

?>	
	<table class='table_General' align='left'  >
		<tr>
			<td colspan='6' class='celdaFondoTituloEJ'>
				<b><font class='TextoTituloTablaEJ' >Datos de Pericia</font></b>						
			</td>
		</tr>		
		<tr>
			<td height="16" class="item_Blanco" style="width:90px;" align="left"> <font class="celda_titulogrisClaroFndBlanco">Pericia :</font></td>			
			<td height="16" width="40%" class="item_Blanco"> <span id="UserControl1_txtNroCarpeta"><b><font class="TextoTablaEJ"><?= $DTP_DESCRIPCION; ?></font></b></span>   </td>
			  
			<td height="16" width="auto" class="item_Blanco" align="right"> <font class="celda_titulogrisClaroFndBlanco">Fecha:</font></td>
			<td height="16" width="40%" class="item_Blanco" align="left"> <span id="UserControl1_txtCaratula"><b><font class="TextoTablaEJ"><?= $DTP_FECHAPERITAJE; ?> </font></b></span> </td>
		</tr>
	</table>	


	<table class='table_General' align='left'  >
		<tr>
			<td colspan='6' class='celdaFondoTituloEJ'>
				<b><font class='TextoTituloTablaEJ' >Adjuntos</font></b>						
		</td></tr>
		<? echo DatosArchivosAdjPeritje($PeritajeID, $bloquearPericia); ?>		
		<tr><td class="title_NegroFndAzul">Adjuntar Archivo:</td></tr>
	</table>	
</form>
					
<?php if(!$bloquearPericia){ ?>					
	<div class='item_Blanco' >					
		<form action='modules/usuarios_registrados/estudios_juridicos/UploadFile.php?ADJUNTAPERICIA&id=<?= $PeritajeID ?>' method='post' enctype='multipart/form-data' onsubmit='return ValidarArchAdjPericia()'>
		
			<div class='format_insertfile' style='padding-top:1pc;' >					
					<label class='item_Blanco' style='width:100px; background-color:#fff; display: inline-block' >Descripción :</label>
					<input  type='text' class="txt-disabled  input_textUpper" maxlength="100" id='textdescripcion' name='textdescripcion' value='' style='width:360px; ' disabled />
			</div>
				
			<div class='format_insertfile'  style='padding-top:3px;'>
					<div class='item_Blanco' style='width:100px; background-color:#fff; display: inline-block' >Asociar doc.:   </div>
					<input name='uploadedfilePericia' id='uploadedfilePericia' type='file' style='width:360px; background-color:#fff; ' onchange='CargarArchivoPericia()' />
			</div>
				
			<div colspan='1' class='format_insertfile'  style='padding-top:10px; width:480px; text-align: right;' >
					<input  type='submit' class='btnAceptar btnHover'  id='AceptaAdj'  value='' />
					<input  type='reset' class='btnCancelarEJ btnHover' id='CancelaAdj' value='' />																		
			</div>		
			
		</form>
	</div>				
<?php } ?>		

	<div id='bloquearPericia' style='display:block; color:red; font-size:15px; padding:15px; '><? 
		if($bloquearPericia)
			echo "Usted No puede modificar esta Pericia."; 
		else echo ""; 
		?>
	</div>
	<br>	
	<a class="btnVolver" href="/PeritajesWebForm"></a>

</div>

<div id="dialogMensajesAdjuntosPericias" title="Info">
	<b class="txt-msj-Aviso-Titulo" id='tituloInfo'>Info Adjuntos:</b>		
	<p>
		<div align="center" id="divInfo" name="divInfo" style="display:none">
		<i id="motivoInfo" >Info</i>
		</div>
		
		<div align="center" id="divSubiendoImg" name="divSubiendoImg" style="display:none">
			<img border="0" src="/images/loading.gif" title="Espere por favor...">
		</div>
	<p>	
</div>

