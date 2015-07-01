<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/AdminWebForm.Grid.php");

@session_start(); 

ValidarUserSession();

$PageBase = new PageBase(true);


$entrar = false;
$resultadoGuardar = '';
$_SESSION['Parte'] = 'D';

//seteo navegacion de paginas
$_SESSION["PagePrevEventosWeb"] = $_SERVER['REQUEST_URI'];
$_SESSION["PagePrevSentencia"] = $_SERVER['REQUEST_URI'];
$_SESSION["PagePrevPeritajes"] = $_SERVER['REQUEST_URI'];

AsignarNroJuicioSession();

if (isset($_SESSION["AdminWebFormGuardarResultado"])){
	
	if($_SESSION["AdminWebFormGuardarResultado"] == 'YES'){
		echo "<script type='text/javascript'>
				MostrarVentana('La instancia fue actualizada.') ;							
		  </script>";
	}else{
		echo "<script type='text/javascript'>
			MostrarVentana('Error: Expediente ya existente');												
		  </script>";
		  
		$resultadoGuardar = 'Error: Expediente ya existente'; 
	}
	
	unset($_SESSION["AdminWebFormGuardarResultado"]);
	$entrar = true;
}


if(isset($_REQUEST['btnAceptar']) ) $entrar = true;
if(isset($_REQUEST['btnCancelar']) )$entrar = true;
if(!$_POST) $entrar = true;

$entrar = true;

if($entrar){	
	//VALORES UTILIZADOS EN EL FORMULARIO	
	extract(ObtenerDatosDeJuicio($_SESSION["NroJuicio"], $_SESSION["IDESTUDIOJURIDICO"], $_SESSION["usuario"]));		
	
	$_SESSION["NUMEROCARPETA"] = $JT_NUMEROCARPETA;
	
	if(isset($DESCRIPCARATULA))	
		$_SESSION["DESCRIPCARATULA"] = $DESCRIPCARATULA;  				    	
	else
		$_SESSION["DESCRIPCARATULA"] = ""; 

	$CargarJurisdiccion = CargarJurisdiccion($JT_IDJURISDICCION); 	
	
	$_SESSION["IDJURISDICCION"] = $JT_IDJURISDICCION;
	$_SESSION["JURISDICCION_DESCRIPCION"] = $JU_DESCRIPCION;
	
	//Esto se carga con ajax
	$CargarJuzgado = CargarJuzgado($JT_IDJURISDICCION, $JT_IDFUERO, $JT_IDJUZGADO);
	$CargarFuero = CargarFuero($JT_IDJURISDICCION, $JT_IDFUERO);	
	$CargarSecretaria = CargarSecretaria($JT_IDJUZGADO, $JT_IDSECRETARIA); 	
}

list($JT_NUMEROCARPETA, $DESCRIPCARATULA, $EJ_DESCRIPCION) = ObtenerNroCarpeta($_SESSION["NroJuicio"]);	
BloqueaControlesJS();	
	
$nrojuicio = $_SESSION["NroJuicio"];
$usuario = $_SESSION["usuario"]; 
echo "<script type='text/javascript'> 
				var NroJuicio = '".$nrojuicio."'; 				
				var usuario = '".$usuario."'; 				
	 </script>";


$PageBase->AgregarEncabezadoJS(true,true,true,true);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/AdminWebForm.js?rnd=".RandomNumber());
$PageBase->AgregarEncabezadoCSS(true,true,true);
$PageBase->AgregarDivProcesando(); 
$PageBase->CrearVentanaMensajeOculta("Eventos","mensaje","ACEPTAR");
$PageBase->CrearVentanaMensajeOKCancel("Eventos","mensaje");

?>
<form name="AdminWebForm" method="post" 
	action="/modules/usuarios_registrados/estudios_juridicos/Redirect.php"	
	id="idAdminWebForm" onsubmit="return ValidarAdminWebForm();">

<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral1" style="overflow-x:hidden; height:390px;">		

<!-- //CAMBIO PAG 99=106 -->
<input name="Redirect" type="hidden" value="/index.php?pageid=106" />		
<input name="AdminWebFormGuardar" type="hidden" value="Redirect" />
<input type="hidden" value="<?php echo $_SESSION["NroJuicio"]; ?>" name="NroJuicio" id="idNroJuicio" />

<input type="hidden" readonly id="JT_IDESTADO" name="JT_IDESTADO" value="<?php echo $JT_IDESTADO; ?>" >

<?php 	
	$PageBase->ActivarGifProcesando();
	echo TablaDatosUsuario($_SESSION["usuario"]);		
?>
	<table class="table_General" align='left'>
		<tr>			
			<td colspan="2" class='celdaFondoTituloEJ'>
			<b><font class='TextoTituloTablaEJ' >Datos del Juicio</font></b></td>
			</td>			
		</tr>		
		<tr>
			<td colspan="2" class="title_NegroFndAzul" >Datos Generales</td>					
			</tr>
		<tr>
			<td colspan="2" style="height: 3px"></td></tr>
		<tr>
			<td style="width:120px" class="item_Blanco">Nro Carpeta:</td>
			<td ><span id="txtidCarpeta" class="valor_azulOscuro" style="Z-INDEX: 1">
				<?php echo $_SESSION["NUMEROCARPETA"];  ?></span></td></tr>
		<tr>
			<td class="item_Blanco" >Tipo Juicio:</td>
			<td  ><span id="txtTipoJuicio" class="valor_azulOscuro">
				<?php echo $TIPOJUICIO;  ?>
				</span></td></tr>
		<tr>
			<td class="item_Blanco" valign="top">Carátula:</td>
			<td ><span id="txtCaratula" class="valor_azulOscuro">
				<?php echo $_SESSION["DESCRIPCARATULA"];  ?></span></td></tr>
		<tr>
			<td class="item_Blanco">Abogado:</td>
			<td ><span id="txtAbogado" class="valor_azulOscuro">
				<?php echo $BO_NOMBRE;  ?></span></td></tr>
		<!--
		Ticket 65770 (Se solicita que el campo F.Asignación deje de estar visible en la web.)
		-->
		<tr>
			<td class="item_Blanco" >F. Alta:</td>
			<td align="left" >
				<!-- Ticket 65772 se agrega el campo fecha alta -->
				<span id="txtFechaAsignacion" class="valor_azulOscuro">	<?php echo $JT_FECHAALTA;  ?></span></td>
			</tr>
		<tr>
			<td class="item_Blanco">F. Notificación:</td>
			<td ><span id="txtFechaNotificacion" class="valor_azulOscuro">
				<?php echo $JT_FECHANOTIFICACIONJUICIO;  ?></span></td></tr>
		<tr>
			<td class="item_Blanco" >F. Fin:</td>
			<td  ><span id="txtFechaFin" class="valor_azulOscuro">
				<?php echo $JT_FECHAFINJUICIO;  ?></span></td></tr>
		<tr>
			<td colspan="2" valign="top" class="item_Blanco"></td></tr>
		<tr> 
			<td colspan="2" class="title_NegroFndAzul" >Detalle </td></tr>	
		<tr>
			<td width="120px" class="item_Blanco" >Estado:</td>
			<td width="81%" >
				<div id="iddivEstado">
					<span id="txtEstado" class="valor_azulOscuro"><?php echo $EJ_DESCRIPCION;  ?></span>
				</div>
				<div class="input_textError" id="ErrorestxtEstado"></div>
			</td></tr>			
		<tr>
			<td valign="top" class="item_Blanco">Res. Probable:</td>
			<td>
			<textarea readonly name="txtResProbable" id="txtResProbable" rows="9" class="text_area"><?php echo trim($JT_RESULTADO); ?></textarea>
			<div class="input_textError" id="ErrorestxtResProbable"></div>
			</td></tr>
		<tr>
			<td colspan="2" style="height: 5px"></td></tr>
		<tr>
			<td colspan="2" class="title_NegroFndAzul" >Juzgado</td></tr>			    		
		<tr>
			<td colspan="2" class="item_Blanco" style="height: 5px"></td></tr>
		<tr>
			<td class="item_Blanco28">Jurisdicción:</td>
			<td class="item_Blanco28">
				<select name="cmbJurisdiccion" id="idcmbJurisdiccion"  disabled="disabled" class="combo" >
					<?php 
						echo $CargarJurisdiccion;
					?>
				</select>
				<div class="input_textError" id="ErrorescmbJurisdiccion"></div>
				<input type="hidden" name="hiddenJurisdiccion" id="idHJuzgadoComp" value="<?php echo $JT_IDJURISDICCION; ?>" />
				</td></tr>
		
		<tr>
			<td class="item_Blanco28">Fuero:</td>
			<td align="left" class="item_Blanco28" width="84%">
				<select name="cmbFuero" id="idcmbFuero" disabled="disabled"   						
						class="combo" >
					<?php echo $CargarFuero; ?>
				</select>
				<div class="input_textError" id="ErrorescmbFuero"></div>
				</td></tr>
				
		<tr>
			<td class="item_Blanco28">Juzgado Nro:</td>
			<td class="item_Blanco28">
				<select name="cmbJuzgadoNro" id="idcmbJuzgadoNro"  disabled="disabled" class="combo" >
					<?php echo $CargarJuzgado; ?>
				</select>
				<div class="input_textError" id="ErroresidcmbJuzgadoNro"></div>
				</td></tr>		
		
		<tr>			
			<td class="item_Blanco28">Secretaría:</td>
			<td class="item_Blanco28">
				<select name="cmbSecretaria" id="cmbSecretaria"  disabled="disabled" class="combo">
                    <?php echo $CargarSecretaria;?> 
				</select>
				<div class="input_textError" id="ErrorescmbSecretaria"></div>
				</td></tr>

		<tr>
			<td class="item_Blanco28">Instancia: </td>
			<td class="item_Blanco28">
				<div name="txtInstancia" id="idtxtInstancia" class="input_text_form_block" 
					style="width:490px; height:13px;" ><?php echo $IN_DESCRIPCION; ?></div> 
				<div class="input_textError" id="ErrorestxtInstancia"></div>
				</td></tr>		

		<tr>
			<td class="item_Blanco28">Nro. Exp.:</td>
			<td align="left" class="item_Blanco28" width="84%">			
				<input name="txtNroExp" type="text" value="<?php echo $JT_NROEXPEDIENTE; ?>"						
						maxlength="10" id="txtNroExp" class="input_text_right" readonly /> / 
						<input name="txtAnioExp" type="text" value="<?php echo $JT_ANIOEXPEDIENTE; ?>"
						maxlength="2" id="txtAnioExp" class="input_text" style="width:24px;" readonly  />
				<div class="input_textError" id="ErrorestxtNroExp"></div>
				</td></tr>
		<tr>
			<td  colspan="2" align="center">
				<input type="button" name="MasDatosJuicioWebForm" value="" class="btnMasDatosJuiciosEJ btnHover"
					onclick="window.location.href = '/MasDatosJuicioWebForm';" >		
					
				<input type="button" name="btnInstancias btnHover" value="" class="btnInstanciasEJ btnHover"
					onclick="window.location.href = '/InstanciasWebForm';" >		
				</td></tr>			
		
		<tr>
			<td colspan="2"></td></tr>
		<tr>
			<td colspan="2" class="title_NegroFndAzul">Observaciones</td></tr>
		<tr>
			<td colspan="2" class="bordeGris_freetext">
				<span id="txtDetalle" class="valor_azulOscuro" style="Z-INDEX: 1"></span>
				<font color="#ffffff">.</font></td></tr>
		<tr>
			<td colspan="2"></td></tr>	
	
		<tr>
			<td colspan="2" class="title_NegroFndAzul">Origen Demanda</td></tr>
		<tr>	
			<td colspan="2" >
				<?php 
					echo getGridDemandas($_SESSION["NroJuicio"]);
				?>
			</td>			
		</tr>		
		
		<tr>
			<td colspan="2" class="title_NegroFndAzul">Reclamos</td></tr>
		<tr>	
			<td colspan="2" >
			<?php 
				echo getGridReclamos($_SESSION["NroJuicio"]); 
				$PageBase->DesactivarGifProcesando();
			?>
			</td>			
		</tr>			
		<td align="center" colspan="2"><br>			
			<input type="button" name="btnPericias" id="idPericias" value="" class="btnPericiasEJ btnHover"
					onclick="window.location.href = '/PeritajesWebForm'; ">					
			<input type="button" name="btnEventos" id="idEventos" value="" class="btnEventosEJ btnHover"
					onclick="window.location.href = '/EventosWebForm'; ">					
			<input type="button" name="btnSentencia" id="idSentencia" value="" class="btnSentenciaEJ btnHover"
					onclick="window.location.href = '/SentenciaWebForm'; ">		
		</td>
		</tr>				
<!-- COMENTARIOS -->
	</table>	

<div class="input_textError" id="lblErrores1"><? if(isset($resultadoGuardar)) echo $resultadoGuardar; ?></div>	
	<div style="position:fixed; left:50%;">
	<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
	</div>
</div>		

<div style="overflow-x:hidden; white-space:nowrap; padding:2px;">		
	<?php if(!$_SESSION["JUICIOTERMINADO"] ) {  ?>				
		<div id="panelbotones" style="white-space:nowrap;">
			<input type="button" name="btnModificar" value="" id="idbtnModificar" class="btnModificarEJ btnHover" />
		</div>	
		
		<div id="panelbotonesAceptar" style="white-space:nowrap; display:none">
			<input type='button' name='btnAceptar' value='' id='idAceptarAjax' class='btnAceptarEJ btnHover' /> 
			<input type='button' name='btnCancelar' value='' id='idbtnCancelar' class='btnCancelarEJ btnHover'  onclick='MostrarVentanaResultadoOK();' /> <label class='input_textError' style='white-space:nowrap;' id='lblErrores'></label>
		</div>	
	<?php } ?>										
		<input class="btnVolver"  name="btnVolver" type="button" value="" onclick="window.location.href='/JuiciosParteDemandada';"/>
</div>		
	
</form>
