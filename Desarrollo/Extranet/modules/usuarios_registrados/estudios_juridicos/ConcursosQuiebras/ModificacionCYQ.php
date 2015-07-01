<?php 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/AcuerdosWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
@session_start(); 
$PageBase = new PageBase(false);
//ModificacionCYQ

ValidarUserSession();	

$_SESSION["PagePrevModificacionCYQ"] = $_SERVER['REQUEST_URI'];

///print_r($_SESSION["ModificacionCYQ"]);

$nroorden = 0;
if(isset($_SESSION["ModificacionCYQ"])){
	$nroorden = $_SESSION["ModificacionCYQ"]["nroorden"];}

extract(ObtenerDatosCYQ($nroorden), EXTR_PREFIX_ALL, "ODCYQ");	
extract(ObtenerMontosCYQ($nroorden), EXTR_PREFIX_ALL, "OMCYQ");	
extract(ObtenerEmpresa($ODCYQ_CQ_CUIT), EXTR_PREFIX_ALL, "OECYQ");
extract(ObtenerEstado($ODCYQ_CQ_ESTADO), EXTR_PREFIX_ALL, "OEstCYQ");

$comboFueroCYQ = CargarFueroCYQ($ODCYQ_CQ_FUERO);
$comboJurisdiccionCYQ = CargarJurisdiccionCYQ($ODCYQ_CQ_JURISDICCION);

if(isset($_REQUEST['id'])){ $_SESSION['nroorden'] = $_REQUEST['id']; }	
if(isset($_SESSION['nroorden'])) $nroorden = $_SESSION['nroorden']; else $nroorden = 0;
//--------------------------------------------------------------
$usuario = $_SESSION["usuario"]; 
echo "<script type='text/javascript'> 
		var usuario = '".$usuario."'; 		
		var nroorden = ".$nroorden."; 		
		var Accion = 'EDIT'; 				
		</script>";
		
//-----------------------------------------------------------------------------
$PageBase->AgregarEncabezadoCSS(true,false,true);

$HEADjquery=true;
$HEADComunes=false;
$HEADEstudiosJuridicos=true;
$HEADGrabaDatos=true;
$HEADvalidations=false;
$HEADAutocompletar=false;
$PageBase->AgregarEncabezadoJS($HEADjquery, $HEADComunes, $HEADEstudiosJuridicos, $HEADGrabaDatos, $HEADvalidations, $HEADAutocompletar);
$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/ConcursosQuiebras/js/ModificacionCYQ.js");

$PageBase->AgregarDivProcesando(); 
$PageBase->CrearVentanaMensajeOculta("Concursos y Quiebras","mensaje","ACEPTARCANCELAR");
$PageBase->CrearVentanaMensajeOKCancel("Concursos y Quiebras","mensaje");		
//-----------------------------------------------------------------------------

include($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/js/AgregaEncabezadoCalendarJS.html");
?>

<title>Modificacion CyQ</title>

<form name="ModificacionCYQ" method="POST" action="/ModificacionCYQ" id="idModificacionCYQ" onsubmit="return ValidarModificacionCYQ();" >
<div align="left" id="divContentGrid" name="divContentGrid" class="divContenedorGeneral" style="overflow-x:hidden; height:390px;">
<input name="nroorden" type="hidden" value="<?php echo $nroorden; ?>" />

<?php echo TablaDatosUsuario($_SESSION["usuario"]);	?>

<table class="table_General" align="left" border="0">	
	<tr>		
		<td colspan="4" class="title_NegroFndAzul">Modificacion</td>
	</tr>
	<tr>
		<td colspan="4" class="title_NegroFndAzul">Datos Generales</td>
	</tr>
	<tr>
		<td class="item_Blanco" style="width:140px;" >Nro. de Orden:</td>
		<td class="item_Blanco" style="width:140px;">
			<span id="TxtNroOrden" class="valor_azulOscuro"><?php echo $ODCYQ_CQ_NROORDEN; ?></span></td>		
		
		<td class="item_Blanco" style="width:100px;">Contrato:</td>
		<td class="item_Blanco">
			<span id="txtContrato" class="valor_azulOscuro"><?php echo $OECYQ_CONTRATO; ?></span></td>		
	</tr>
	<tr>
		<td class="item_Blanco">Empresa:</td>
		<td class="item_Blanco">
			<span id="txtEmpresa" class="valor_azulOscuro"><?php echo $ODCYQ_CQ_CUIT; ?></span></td>
		<td class="item_Blanco" >R. Social:</td>
		<td  class="item_Blanco">
			<span id="txtRSocial" class="valor_azulOscuro"><?php echo $ODCYQ_MP_NOMBRE; ?></span></td>		
	</tr>
		<tr>
		<td class="item_Blanco">Legajo:</td>
		<td class="item_Blanco">	<span id="txtLegajo" class="valor_azulOscuro" style="Z-INDEX: 1"><?php echo $ODCYQ_CQ_LEGAJO; ?></span>	</td>
		<td class="item_Blanco">Abogado:</font>	</td>
		<td  class="item_Blanco"> 
			<span id="txtAbogadoID" class="valor_azulOscuro" style="Z-INDEX: 1"><?php echo $ODCYQ_CQ_ABOGADO; ?></span>
			<span id="txtAbogado" class="valor_azulOscuro" style="Z-INDEX: 1"><?php echo $ODCYQ_BO_NOMBRE; ?></span></td>
	</tr>
	<tr class="title_NegroFndAzul">
		<td class="title_NegroFndAzul" colspan="4"></td>
	</tr>
	<tr>
		<td class="item_Blanco">Sindico:</td>
		<td colspan="3" class="item_Blanco">
			<input name="txtSindic" type="text" value="<?php echo $ODCYQ_CQ_SINDICO; ?>" 
					id="txtSindic" class="input_text" style="width:80%" maxlength="60" />	
			<div class="input_textError" id="ErrorestxtSindic"></div>
		</td>
	</tr>
	<tr>
		<td class="item_Blanco">Direccion:</td>
		<td colspan="3" class="item_Blanco">
			<input name="txtDireccion" type="text" value="<?php echo $ODCYQ_CQ_DIRECCIONSIND; ?>" 
					id="txtDireccion" class="input_text" style="width:80%" maxlength="60"/>
			<div class="input_textError" id="ErrorestxtDireccion"></div>
			</td>
	</tr>
	<tr>
		<td class="item_Blanco">Localidad:</td>
		<td colspan="3" class="item_Blanco">
			<input name="txtLocalidad" type="text" value="<?php echo $ODCYQ_CQ_LOCALIDADSIND; ?>" 
				id="txtLocalidad" class="input_text" style="width:80%" maxlength="60"/>
			<div class="input_textError" id="ErrorestxtLocalidad"></div>
			</td>
	</tr>
	<tr>
		<td class="item_Blanco">Telefonos:</td>
		<td colspan="3" class="item_Blanco">
			<input name="txtTelefonos" type="text" id="txtTelefonos" class="input_text" maxlength="20" 
				value="<?php echo $ODCYQ_CQ_TELEFONOSIND; ?>"	style="width:200px" />	
			<div class="input_textError" id="ErrorestxtTelefonos"></div>
			</td>
	</tr>
	<tr>
		<td class="item_Blanco">Fuero:</td>
		<td class="item_Blanco" colspan="3">
			<input name="txtFueroId" type="text" value="<?php echo $ODCYQ_CQ_FUERO; ?>" 
				readonly id="txtFueroId" class="input_text" style="width:40px" />
		
			<select name="cmbFuero" id="cmbFuero" class="combo" style="width:300px" >
				<?php echo $comboFueroCYQ; ?>
			</select>	
			<div class="input_textError" id="ErrorescmbFuero"></div>
			
	</tr>
	<tr>
		<td class="item_Blanco" >Juzgado Nro:</td>
		<td class="item_Blanco" colspan="3">
			<input name="txtJuzgadoID" type="text" value="<?php echo $ODCYQ_CQ_JUZGADO; ?>" 
				id="txtJuzgadoID" maxlength="3" class="input_text" style="width:80px" />
			<div class="input_textError" id="ErrorestxtJuzgadoID"></div>
			</td>
	</tr>
	
	<tr>
		<td class="item_Blanco">Secretaria:</td>
		<td class="item_Blanco" colspan="3" > 
			<input name="txtSecretaria" type="text" value="<?php echo $ODCYQ_CQ_SECRETARIA; ?>" 
					id="txtSecretaria" maxlength="3" class="input_text" style="width:80px" />
			<div class="input_textError" id="ErrorestxtSecretaria"></div>
			</td>
	</tr>
	<tr>
		<td class="item_Blanco">Jurisdiccion:</td>
		<td class="item_Blanco" colspan="3" >
			<input name="txtJurisdiccionID" type="text" value="<?php echo $ODCYQ_CQ_JURISDICCION; ?>" 
					readonly id="txtJurisdiccionID" class="input_text" style="width:40px" />
			<select name="cmbJurisdiccion" id="cmbJurisdiccion" class="combo" style="width:300px" >	
				<?php echo $comboJurisdiccionCYQ; ?>
			</select>
			<div class="input_textError" id="ErrorescmbJurisdiccion"></div>
			</td>
	</tr>
	<tr>
		<td class="item_Blanco">Estado:</td>
		<td class="item_Blanco">
			<span id="txtEstadoId" class="valor_azulOscuro" style="Z-INDEX: 1"><?php echo $ODCYQ_CQ_ESTADO; ?></span>	</td>
		<td colspan="2" class="item_Blanco">
			<span id="txtEstado" class="valor_azulOscuro" style="Z-INDEX: 1"><?php echo $OEstCYQ_DESCRIPCION; ?></span>	</td>
	</tr>
	<tr>
		<td class="item_Blanco" colspan="4" height="5"></td>
	</tr>
	
</table>
	
<table class="table_General" >
	<tr>
		<td colspan="2" class="title_NegroFndAzul">Fechas</td>
	</tr>			
	<tr>
		<td class="item_Blanco" colspan="2" height="6"></td>
	</tr>			
	<tr>
		<td class="item_Blanco" style="width:140px;" >Presentacion en Concurso:</td>
		<td class="item_Blanco">
			<input name="txtfechaconcurso" type="text" maxlength="10" id="txtfechaconcurso" class="input_text_Fecha" 
					value="<?php if(isset($ODCYQ_FECHACONCURSO)) echo $ODCYQ_FECHACONCURSO; ?>" />
			<input type="button" name="btnFechaConcurso" id="btnFechaConcurso" value="..."  class="BotonFechaEstudio" />	
			<div class="input_textError" id="Errorestxtfechaconcurso"></div>
			</td>	
	</tr>
	<tr>
		<td class="item_Blanco">Vto.Art32:</td>
		<td class="item_Blanco">
			<input name="TxtVtoArt32" type="text" maxlength="10" id="TxtVtoArt32" class="input_text_Fecha" 
				value="<?php echo $ODCYQ_CQ_FECHAVTOART32; ?>" />
			<input type="button" name="btnVtoArt32"id="btnVtoArt32" value="..."  class="BotonFechaEstudio" />			
			<div class="input_textError" id="ErroresTxtVtoArt32"></div>
			</td>
	</tr>
	<tr>
		<td class="item_Blanco">Declaracion de quiebra:</td>
		<td class="item_Blanco">
			<input name="txtQuiebra" type="text" value="<?php if(isset($ODCYQ_FECHAQUIEBRA)) echo $ODCYQ_FECHAQUIEBRA; ?>" maxlength="10" id="txtQuiebra" 
					class="input_text_Fecha" />
			<input type="button" name="btnQuiebra"  id="btnQuiebra" value="..."  class="BotonFechaEstudio" />
			<div class="input_textError" id="ErrorestxtQuiebra"></div>
			</td>
	</tr>
	<tr>
		<td class="item_Blanco">Vto.Art200:</td>
		<td class="item_Blanco">
			<input name="txtVtoArt200" type="text" value="<?php echo $ODCYQ_CQ_FECHAVTOART200; ?>" 
					maxlength="10" id="txtVtoArt200" class="input_text_Fecha" />
			<input type="button" name="btnVtoArt200" id="btnVtoArt200" value="..." class="BotonFechaEstudio"  />	
			<div class="input_textError" id="ErrorestxtVtoArt200"></div>
			</td>
	</tr>
	<tr>
		<td class="item_Blanco">Verificacion de credito:</td>				
		<td class="item_Blanco">
			<input name="txtVerificacioncredito" type="text" maxlength="10" id="txtVerificacioncredito" 
				class="input_text_Fecha" value="<?php echo $ODCYQ_CQ_FECHAVERIFICACIONCREDITO; ?>"  />
			<input type="button" name="btnVerificacioncredito" id="btnVerificacioncredito" value="..." class="BotonFechaEstudio" /> 
			<div class="input_textError" id="ErrorestxtVerificacioncredito"></div>
			</td>	
	</tr>
	<tr>
		<td class="item_Blanco">Asignacion:</td>
		<td class="item_Blanco">
			<span id="txtAsignacion" class="valor_azulOscuro"><?php echo $ODCYQ_CQ_FECHAASIGN; ?></span>	</td>
	</tr>				
	<tr>
		<td class="item_Blanco">Ult. Per. Concurso:</td>
		<td class="item_Blanco">
			<span id="txtultConcurso" class="valor_azulOscuro"><?php echo $ODCYQ_CQ_ULTPERCONCURSO; ?></span>	</td>
	</tr>
	<tr>
		<td class="item_Blanco">Ult. Per. Quiebra:</td>
		<td class="item_Blanco">
			<span id="txtUltQuiebra"><font face="Verdana" color="DarkBlue" size="1"><?php echo $ODCYQ_CQ_ULTPERQUIEBRA; ?></font></span>	</td>
	</tr>
	<tr>
		<td class="item_Blanco" colspan="2" height="6"></td>
	</tr>			
	
</table>		

<table class="table_General" >
	<tr>
		<td class="title_NegroFndAzul" colspan="2">Importes</td>			
	</tr>				
	<tr>
		<td class="item_Blanco" colspan="2" height="6"></td>
	</tr>
	<tr>		
		<td class="item_Blanco"  style="width:140px;">Deuda Nominal:</td>		
		<td class="item_Blanco">
			<span id="txtDeudaNominal" class="valor_azulOscuro"><?php echo formatearDinero($ODCYQ_CQ_DEUDANOMINAL); ?></span>		</td>
	</tr>				
	<tr>					
		<td class="item_Blanco">Deuda Total:</td>
		<td align="left" class="item_Blanco">
			<span id="txtDeudaTotal" class="valor_azulOscuro"><?php echo formatearDinero($ODCYQ_CQ_DEUDATOTAL); ?></span>	</td>
	</tr>
	<tr></tr>
	<tr>		
		<td class="item_Blanco">Monto de privilegio:</td>
		<td class="item_Blanco">
			<input name="txtMontoPrivilegio" type="text" value="<?php echo SoloformatearDinero($ODCYQ_CQ_MONTOPRIVILEGIO); ?>" 
					id="txtMontoPrivilegio" class="input_text" maxlength="14" />
			<div class="input_textError" id="ErrorestxtMontoPrivilegio"></div>
			</td>
	</tr>				
	<tr>					
		<td class="item_Blanco">Monto quirografario:</td>
		<td class="item_Blanco">
			<input name="txtMontoQuirografario" type="text" value="<?php echo SoloformatearDinero($ODCYQ_CQ_MONTOQUIROG); ?>" 
				id="txtMontoQuirografario" class="input_text" maxlength="14" />	
			<div class="input_textError" id="ErrorestxtMontoQuirografario"></div>
			</td>
	</tr>	
	<tr>
		<td class="item_Blanco" colspan="2" height="6"></td>
	</tr>	
	<tr>
		<td align="center" class="item_Blanco"  colspan="2" >
			<div align="center">
				<!-- //CAMBIO PAG 111=118 -->
				<input type="button" name="Eventos" value="" class="btnEventosEJ"
					onclick="window.location.href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?pageid=118&nroorden=<?php echo $nroorden ?>'; ">		
				<!-- //CAMBIO PAG 113=120 -->
				<input type="button" name="Acuerdos" value="" class="btnAcuerdosEJ"
					onclick="window.location.href = '/modules/usuarios_registrados/estudios_juridicos/redirect.php?pageid=120&nroorden=<?php echo $nroorden ?>';">					
				</div>	
			</td></tr>			
</table>
</div>		
<div style="position:fixed; left:50%;">
<img id="imgAplicandoCambios" src="/images/loading.gif" style="display:none;" title="Aplicando, aguarde un instante por favor..." />
</div>
<div style="overflow-x:hidden; white-space:nowrap;">		
	<input type="button" name="btnAceptar" value="" id="idAceptarAjax" class="btnAceptarEJ" />                                
	<input type="button" name="btnCancelar" value="" id="btnCancelar" class="btnCancelarEJ" onClick="window.location.href = '/ConcursosQuiebras';"/>
	<label class="input_textError" id="lblErrores"></label>

	<input class="btnVolver"  name="btnVolver" type="button" onClick="window.location.href = '/ConcursosQuiebras';"/>				
</div>		

</form>		
<?php $PageBase->DesactivarGifProcesando(); ?>