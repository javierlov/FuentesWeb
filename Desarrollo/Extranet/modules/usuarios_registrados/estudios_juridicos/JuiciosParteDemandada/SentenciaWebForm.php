<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/SentenciaWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 
$PageBase = new PageBase(false);

ValidarUserSession();
AsignarNroJuicioSession();

$IncapacidadVisible = 'N';
$resultadoUpdate = 0;

//$_SESSION["NroJuicio"] = $_REQUEST["NroJuicio"];

function VolverPaginaPrev(){
	echo "<script type='text/javascript'> 
		window.location.href = '".$_SESSION["PagePrevSentencia"]."'; </script>";	
}

if(isset($_REQUEST["btnCancelar"])){
	VolverPaginaPrev();
}


if(isset($_REQUEST["btnAceptarSubmit"])){

	$txtfechasentencia = $_REQUEST["txtFecha"];
	$txtfecharecep = $_REQUEST["txtFechaRecep"];
	$jt_sentencia = $_REQUEST["txtDetalleSentencia"];
	$cmbsentencia = $_REQUEST["cmbSentencia"];
	
	$usuario = $_SESSION["usuario"]; 
	$jt_id = $_SESSION["NroJuicio"];
	
	$txtimportehonorarios = $_REQUEST["txtImporteHonorarios"];
	$txtimporteintereses = $_REQUEST["txtImporteIntereses"];
	$txtimportetasajusticia = $_REQUEST["txtImporteTasaJusticia"];
	
	//extract(ObtenerInstanciaParaSentencia($_SESSION["NroJuicio"]) ,EXTR_PREFIX_ALL, "OIPS");					
	//$instancia = $OIPS_IJ_ID;
	$instancia = 0;
	
	$txtMontoCondena = $_REQUEST["txtMontoCondenaSentencia"];
	
	$txtPorcentajeIncapacidad = '';	
	$IncapacidadVisible = ObtenerIncapacidadVisible($_SESSION["NroJuicio"]);
	if($IncapacidadVisible == 'S')
		if(isset($_REQUEST["txtPorcentajeIncapacidad"]) )
			$txtPorcentajeIncapacidad = $_REQUEST["txtPorcentajeIncapacidad"];
	
	if (UpdateSentencia($txtfechasentencia, $txtfecharecep, 
			$jt_sentencia, $cmbsentencia,  $usuario, 
			$jt_id, $txtimportehonorarios, 
			$txtimporteintereses, $txtimportetasajusticia,
			$instancia, $txtMontoCondena, $txtPorcentajeIncapacidad)){
			
			$resultadoUpdate = 2;
	} 
	else{
		$resultadoUpdate = 1;
		//alert('No se pudo actualizar la Sentencia .. intente nuevamente.') ;							
	}
}

list($JT_NUMEROCARPETA, $DESCRIPCARATULA, $EJ_DESCRIPCION) = ObtenerNroCarpeta($_SESSION["NroJuicio"]);

$_SESSION["NUMEROCARPETA"] = $JT_NUMEROCARPETA;
$_SESSION["DESCRIPCARATULA"] = $DESCRIPCARATULA; 

list($JT_ID, 
	$JT_IDTIPORESULTADOSENTENCIA, 
	$JT_FECHASENTENCIA, 
	$JT_FECHARECEPSENTENCIA, 
	$JT_IMPORTEDEMANDADO, 
	$JT_IMPORTECAPITAL, 
	$JT_IMPORTETASAJUSTICIA, 
	$JT_IMPORTESENTENCIA, 
	$JT_IMPORTEHONORARIOS, 
	$JT_DETALLESENTENCIA, 
	$JT_INTERESESSENTENCIA, 
	$JT_MONTOCONDENA, 
	$JT_PORCENTAJEINCAPACIDAD) = ObtenerSentencia($_SESSION["NroJuicio"]);
	
$IncapacidadVisible = ObtenerIncapacidadVisible($_SESSION["NroJuicio"]);

extract(ObtenerInstanciaParaSentencia($_SESSION["NroJuicio"]) ,EXTR_PREFIX_ALL, "OIPS");					

$sumaCapital = formatearDinero(sumaCapital($_SESSION["NroJuicio"], $OIPS_IJ_ID));
$sumaHonorarios = formatearDinero(sumaHonorarios($_SESSION["NroJuicio"], $OIPS_IJ_ID));
$sumaIntereses = formatearDinero(sumaIntereses($_SESSION["NroJuicio"], $OIPS_IJ_ID));
$sumaTasas = formatearDinero(sumaTasas($_SESSION["NroJuicio"], $OIPS_IJ_ID));
$sumaSentencia = formatearDinero(sumaSentencia($_SESSION["NroJuicio"], $OIPS_IJ_ID));

/*********************************************************/
$cmbsentencia = $JT_IDTIPORESULTADOSENTENCIA;

if($cmbsentencia == '') $cmbsentencia = 0;
$OptTipoResultadoSentencia = CargarTipoResultadoSentencia($cmbsentencia);

//BloqueaControlesJS();

$usuario = $_SESSION["usuario"]; 
$jt_id = $_SESSION["NroJuicio"];
$txtPorcentajeIncapacidad = '';
	if($IncapacidadVisible == 'S') 
		if(isset($_REQUEST["txtPorcentajeIncapacidad"]) )
			$txtPorcentajeIncapacidad = $_REQUEST["txtPorcentajeIncapacidad"];
	
					
echo "<script type='text/javascript'> 
			var jt_id = '".$jt_id."'; 				
			var usuario = '".$usuario."'; 	
			var txtPorcentajeIncapacidad = '".$txtPorcentajeIncapacidad."'; 	
			var instancia = 0; 	 
			var MuestraMensajeProceso = '".$resultadoUpdate."';
			</script>";

$PageBase->AgregarEncabezadoJS(true,false,true,true, false, false);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/SentenciaWebForm.js?rnd=".RandomNumber());
$PageBase->AgregarEncabezadoJQUERYUI();
$PageBase->AgregarEncabezadoCSS(true,true,true);
$PageBase->AgregarDivProcesando();
$PageBase->ActivarGifProcesando();
$PageBase->CrearVentanaMensajeOculta("Peritaje","mensaje","ACEPTAR");

include($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/js/AgregaEncabezadoCalendarJS.html");

?>
<script type="text/javascript"> 
<?php
$FechadeNotificacion = ObtenerFechadeNotificacion($_SESSION["NroJuicio"]);	
echo " var FechadeNotificacion = '".$FechadeNotificacion."'; ";

$MontoDemandadoLista = ObtenerMontoDemandadoObligatorioLista();
echo $MontoDemandadoLista;

$EsFederal = ObtenerEsFederal($_SESSION["NroJuicio"]);
echo " var EsFederal = ".$EsFederal."; ";
echo " var IncapacidadVisible = '".$IncapacidadVisible."'; ";

?>
</script>

<form name="SentenciaWebForm" method="POST" action="/SentenciaWebForm" id="idSentenciaWebForm" 
	onsubmit="return ValidarSentenciaWebForm();"  >
<div align="center" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow-x:hidden; height:390px;" >		

<input type="hidden" value="<?php echo $JT_ID ?>" name="IJ_ID">
<input type="hidden" value="<?php if(isset($sumaIntereses)) echo $sumaIntereses; else '00,0' ?>" name="txtImporteIntereses" id="idtxtImporteIntereses">
<input type="hidden" value="<?php if(isset($sumaHonorarios)) echo $sumaHonorarios;  else '00,0' ?>" name="txtImporteHonorarios" id="idtxtImporteHonorarios">
<input type="hidden" value="<?php if(isset($sumaTasas)) echo $sumaTasas;  else '00,0' ?>" name="txtImporteTasaJusticia" id="idtxtImporteTasaJusticia">

<?php 
	echo TablaDatosUsuario( $_SESSION["usuario"] ); 
	echo TablaDatosJuicioEstado(); 
?>
	
	<table class="table_General" align="left" id="idTablaGeneral">
		<tr>
			<td colspan="2" class="title_NegroFndAzul">Sentencia</td>
		</tr>
		<tr>
			<td colspan="2" class="title_NegroFndAzul">Datos Generales</td>
		</tr>
		<tr>
			<td class="item_Blanco" colspan="2" height="0"></td>
		</tr>
		<tr>
			<td width="163" align="left" class="item_Blanco">Sentencia:</td>
			<td width="734" align="left" class="item_Blanco">
				<select name="cmbSentencia" id="cmbSentencia" class="combo"><?php echo $OptTipoResultadoSentencia; ?></select>
				<div class="input_textError" id="ErrorcmbSentencia"></div>
				</td>
		</tr>
		<tr>
			<td align="left" class="item_Blanco">Fecha Sentencia:</td>
			<td align="left" class="item_Blanco">
				<input id="idtxtFecha" name="txtFecha" type="text" 
					value="<?php if(isset($JT_FECHASENTENCIA)) echo $JT_FECHASENTENCIA; ?>" 
					maxlength="10" class="input_text_Fecha" />
				<input id="idbtnFecha" type="button" name="btnFecha"  alt="" border="0" value="..." class="BotonFechaEstudio" />				
				<div class="input_textError" id="ErrorcmbFecha"></div>
				</td>
		</tr>
		<tr>
			<td align="left" class="item_Blanco">Fecha Notificación:</td>
			<td align="left" class="item_Blanco">
				<input name="txtFechaRecep" id="idtxtFechaRecep" type="text" 
						value="<?php if(isset($JT_FECHARECEPSENTENCIA)) echo $JT_FECHARECEPSENTENCIA; ?>" 
						maxlength="10" class="input_text_Fecha" />
				<input type="button" name="btnFechaRecep" id="idbtnFechaRecep" alt="" border="0" value="..."  class="BotonFechaEstudio"/>
				<div class="input_textError" id="ErrorcmbFechaNotificacion"></div></td>
		</tr>
		<tr>
			<td align="left" class="item_Blanco">Importe Demandado:</td>
			<td align="left" class="item_Blanco">
				<span id="txtImporteDemandado" class="valor_azulOscuro"><?php if(isset( $JT_IMPORTEDEMANDADO )) echo formatearDinero($JT_IMPORTEDEMANDADO); ?></span></td>
		</tr>
		<tr>
			<td align="left" class="item_Blanco">Importe Capital:</td>
			<td align="left" class="item_Blanco">
				<span id="txtImporteCapital" name="txtImporteCapital" class="valor_azulOscuro"><?php echo $sumaCapital; ?></span></td>
		</tr>
		<tr>
			<td align="left" class="item_Blanco">Honorarios:</td>
			<td align="left" class="item_Blanco">			 
				<label id="txtImporteHonorarios" class="valor_azulOscuro"><?php echo $sumaHonorarios; ?></label				
				</td>
		</tr>
		<tr>
			<td align="left" class="item_Blanco">Importe Intereses:</td>
			<td align="left" class="item_Blanco">
				<span id="txtImporteIntereses" class="valor_azulOscuro"><?php echo $sumaIntereses; ?></span				
				</td>
		</tr>
		<tr>
			<td align="left" class="item_Blanco">Importe Tasa de Justicia:</td>
			<td align="left" class="item_Blanco">
				<span id="txtImporteTasaJusticia" class="valor_azulOscuro"><?php echo $sumaTasas; ?></span></td>
		</tr>
		<tr>
			<td align="left" class="item_Blanco">Importe Sentencia:</td>
			<td align="left" class="item_Blanco">
				<span id="txtImporteSentencia" class="valor_azulOscuro"><?php echo $sumaSentencia ; ?></span></td>	
		</tr>    
		
		<tr>
			<td align="left" class="item_Blanco">Monto de Condena:</td>
			<td align="left" class="item_Blanco">
				<span class="item_Blanco">
				<input name="txtMontoCondenaSentencia" type="text" maxlength="10" 
					value="<?php if(isset($JT_MONTOCONDENA)) echo SoloformatearDinero($JT_MONTOCONDENA); ?>" 
					id="txtMontoCondenaSentencia" class="numerico" /></span>
				<div class="input_textError" id="ErrorestxtMonto"></div></td>
		</tr>   		
		
<?php if($IncapacidadVisible == 'S'){ ?>		
		<tr>
			<td align="left" class="item_Blanco">Porcentaje Incapacidad:</td>
			<td align="left" class="item_Blanco">
				<span class="item_Blanco">
				<input type="text" value="<?php  if(isset($JT_PORCENTAJEINCAPACIDAD)) echo SoloformatearDinero($JT_PORCENTAJEINCAPACIDAD);   else '0' ?>" 
						maxlength="6" name="txtPorcentajeIncapacidad" id="idtxtPorcentajeIncapacidad" class="numerico" > % 
				</span>
				<div class="input_textError" id="ErrorestxtPorcentajeIncapacidad"></div></td>
		</tr>   				
<?php } ?>		
		<tr><td height="2" colspan="2"></td></tr>		
		<tr><td colspan="2" class="title_NegroFndAzul">Detalle</td></tr>
		<tr><td height="2" colspan="2"></td></tr>
		<tr><td colspan="2" height="100%" width="100%" class="item_Blanco" >
				<textarea name="txtDetalleSentencia" id="txtDetalleSentencia" maxlength="100000" title="Detalle Sentencia" 
					rows="5" style="width:95%;" 					
					onclick="ContarCaracteres();" 
					onchange="ContarCaracteres();" 					
					onkeyup="ContarCaracteres();"  					
					class="text_area"><?php if(isset($JT_DETALLESENTENCIA)) echo trim($JT_DETALLESENTENCIA); ?></textarea>					
				<div class="celda_titulogrisClaroFndBlanco" id="idcontarcaracteres"></div>				
				<div class="input_textError" id="ErroresDetalle"></div>
				</td></tr>
		<tr><td height="2" colspan="2">		
		</td></tr>
	</table>	

	<table class="table_General" align='left' >
		<tr><td colspan="2" class="title_NegroFndAzul">Sentencia a Reclamos</td></tr>		
		<tr><td colspan="2">
		<?php 			
			echo getGridSentencia($_SESSION["NroJuicio"]);
		?>
		</td></tr>		
		<tr><td colspan="2"></td></tr>
		<tr><td colspan="2"></td></tr>		
	</table>

</div>
<div style="position:fixed; left:50%;">
	<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
</div>
<div style="overflow-x:hidden; white-space:nowrap;">		
	<div align="left">	
		<?php 
		//if(!$_SESSION["JUICIOTERMINADO"] ) {  
		?>									
		<input name="btnAceptarSubmit" value="" id="btnAceptarSubmit" type="submit" class="btnAceptarEJ btnHover">				
		
		<input name="btnCancelar" value="" id="idbtnCanclear" type="button" class="btnCancelarEJ btnHover" 
				onClick="window.location.href ='/AdminWebForm';">
		<label class="input_textError" id="lblErrores"></label>
		<?php 
		//}  
		?>
	</div>				
	<a class="btnVolver" href="/AdminWebForm" ></a>	
</div>
</form>

<?php 
$PageBase->CrearVentanaDialogJQUI('Titulo','Texto'); 
$PageBase->DesactivarGifProcesando(); 
?>
