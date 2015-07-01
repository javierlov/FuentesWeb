<?php
// header("Content-Type: text/html;charset=utf-8");

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/FuncionesEstablecimientos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

//R:\Development\Extranet\modules\usuarios_registrados\clientes\RAR\NominaPersonalExpuesto.Grid.php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/NominaPersonalExpuesto.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/CargaESOP.Grid.php");

@session_start(); 

validarSesion(isset($_SESSION["isCliente"]));
$isNominaAnterior = 'false';
$resultado = '';

$hiddenCODIGOCIUU = $_SESSION["IDACTIVIDAD"];

if( !isset($_SESSION['FormulariosNomina']) ){
		echo "<script>					
			window.location.assign('/SeleccionarEstablecimiento');			 
		</script>";	
}

if (isset($_SESSION['NOMINAPERSONALEXPUESTO']['IDESTABLECIMIENTO'])){

	$isNominaAnterior = 'true';
	$idEstablecimiento = $_SESSION['NOMINAPERSONALEXPUESTO']['IDESTABLECIMIENTO'];
	$cuitEmpresa = $_SESSION['NOMINAPERSONALEXPUESTO']['CUITEMPRESA'];
	$usualta = $_SESSION['NOMINAPERSONALEXPUESTO']['USUALTA'];
	
	unset($_SESSION['NOMINAPERSONALEXPUESTO']);
}
	
$CODIGOEWID = 0;	
if( isset($_SESSION['FormulariosNomina']) and $_SESSION['FormulariosNomina']['CODIGOEWID'] > 0){
	$idEstablecimiento = $_SESSION['FormulariosNomina']['CODIGOEWID'];
	$CODIGOEWID =  $_SESSION['FormulariosNomina']['CODIGOEWID'];
	
}else{
	
	$idEstablecimiento = $_SESSION['FormulariosNomina']['NROESTABLECI'];
	$CuitEmpresa = trim($_SESSION['cuit']);	
	$idEstablecimiento = Existe_EstablecimientoWEB($idEstablecimiento, $CuitEmpresa);	
	
	$_SESSION['FormulariosNomina']['NROESTABLECI'] = $idEstablecimiento;
	$_SESSION['FormulariosNomina']['CODIGOEWID'] = $idEstablecimiento;
	$_SESSION['FormulariosNomina']['CODIGO'] = $idEstablecimiento;
}

	$idactividad = 0;
	if( isset($_SESSION["IDACTIVIDAD"]) and $_SESSION["IDACTIVIDAD"] != ''){
		$idactividad = $_SESSION["IDACTIVIDAD"];
	}	
	
	$errorXLS = '';
	
	if( isset($_SESSION['arrayXLSReportOK']) )
		$errorXLS .= '<div style="color:blue;" > '.$_SESSION['arrayXLSReportOK'].' </div>';		
		
	if( isset($_SESSION['arrayXLSReport']) )
		$errorXLS .= '<div style="color:red;" > Errores: <p/>'.$_SESSION['arrayXLSReport'].' </div>';

	unset($_SESSION['arrayXLSReport']);
	unset($_SESSION['arrayXLSReportOK']);
	
	
	if($CODIGOEWID	> 0){		
		SetResponsableDefault($CODIGOEWID);
	}
		
echo "
	<script type='text/javascript'> 		
		var idEstablecimiento = '".$idEstablecimiento."'; 
		var codigoactividad = '".$idactividad."'; 
						
		var param_CONTRATO = ".$_SESSION["contrato"].";		
		var param_CUILEMPRESA = ".$_SESSION["cuit"].";		
		var param_ERRORXLS = '".$errorXLS."';		
		
		var isNominaAnterior = '".$isNominaAnterior."';		
		var resultadoInsertNNdeA = '".$resultado."';		
		var param_idEmpresa = '".$_SESSION["idEmpresa"]."';		
		
		".GetJS_ArrayPuestosNomina("arrayjsPuestosNomina").";		
		".GetJS_ArrayESOPsoloActivos("seleccionDatosESOPActividad", $hiddenCODIGOCIUU ).";
		".GetJS_ArrayEmpleados("arrayjsEmpleados", $_SESSION["idEmpresa"], 'CUIL' ).";				
						
	</script>";
	
?>
<head>	
	<title>Nomina de Personal Expuesto</title>	
	<link href="/styles/style2.css" rel="stylesheet">	
	<link href="/styles/style.css" rel="stylesheet">		
	<link href="/styles/gridAjax.css" rel="stylesheet" type="text/css">
	<link href="/styles/rar/jquery-ui-custom.css<?php echo RandonNumberParameter(); ?>" rel="stylesheet">	
	<link href="/modules/usuarios_registrados/clientes/RAR/css/rar.css<?php echo RandonNumberParameter(); ?>" rel="stylesheet">	
	
<?php
	echo JSjqueryVersion();
	echo JSjqueryUIVersion();
?>		
	<script src="/js/rar/Comunes.js<?php echo RandonNumberParameter(); ?>"></script>	
	<script src="/js/rar/ComunesJQ.js<?php echo RandonNumberParameter(); ?>"></script>	
	<script src="/js/rar/ProcesarDatos.js<?php echo RandonNumberParameter(); ?>"></script>	
	<script src="/modules/usuarios_registrados/clientes/RAR/js/NPersonalExpuesto.js" ></script>
	<script src="/modules/usuarios_registrados/clientes/RAR/js/AutocompletarRAR.js" ></script>
	<script src="/modules/usuarios_registrados/clientes/RAR/js/comunesRAR.js<?php echo RandonNumberParameter(); ?>" ></script>
	<script src="/js/rar/UserInterface.js<?php echo RandonNumberParameter(); ?>"></script>

</head>

<body>
<form action="" id="formGrid" method="post" name="NominaPersonalExpuesto" onSubmit="return ValidarNominaPersonal()" >

<div style="width:100%; float:left;">
	<div id="DatosGeneralesPersonalExpuesto" title=""> 
		<h3 class="ui-dialog-title">Nomina de Personal Expuesto</h3>
		<div >	
			<div class="ui-TitulosEtiquetas" style="width:100%;">Cargué la Nómina del Personal Expuesto a Riesgos Laborales. Puede descargarse una breve guía de como cargar los datos de los trabajadores y los códigos de riesgos de trabajo, haciendo <a href="./rar/video" target='black' ><b>click  aquí</b></a>. Una vez completados los datos, deberá grabar los mismos. </div>
			
			<div style="width:100%; border: 10px; ">
				<div > 					
					<input type="button" text='' class="btnImportExcel" id="botonImportExcel" onclick=" showDialogImportarExel(); " >   					
					Para descargarse el modelo hacer click en 
					<a href="/modules/varios/templates/PlanillaRAR.xls">
					<input type="button" text='' class="btnXLSdescarga" id="btndescargaxlsA" >  </a>
				</div>
			</div>
			
			<p>	
			
			<hr> 			
			<div style="padding:3px 0;">				
				<b class="ui-TitulosHead" >  Trabajador:  </b><input type="text" style="width:200px;" id="txtBuscarTrabajador" > <input type="button" class="btnBuscar" id="btnBuscarTrabajador"><b class="ui-TitulosHead" >  Buscar por: </b> <input type="radio" id="radioNombre" name="radioBuscarPor" value="0" checked /><label for="radioNombre" class="ui-label" > Nombre y Apellido </label> <input type="radio" id="radioCUIL" name="radioBuscarPor" value="1" /><label for="radioCUIL"  class="ui-label" > CUIL </label>
				<img src="/images/loading.gif" style="vertical-align:-4px; display:none;" id="loadingTrabajador" />
			</div>
				
			<div style='overflow:scroll;' >
				<div id="grillaPersonalExpuesto"  >	</div>				
				<img src="/images/loading.gif" style="vertical-align:-4px; display:none;" id="loading" />
				<div id="grillaPersExpMsj" class='msj-info' style="width:100%; text-align:left; font: 10px Verdana; display:none;" ></div>
			</div>
				
			<input type="button" class="btnConfirmar2" value=""  style="margin:5px;" name="btnGuardarNomina" id="btnGuardarNomina" >
			<input type="button" class="btnVolver" value=""  onclick="window.location.assign('FormulariosNomina');" >
			
		</div>		
	</div>
	
</div>

<div id="resultadoProceso" style="overflow:scroll; display:none;" ></div>

<div align="center" id="divProcesando" name="divProcesando" style="display:none;">
		<img style="padding-top:10px;" border="0" src="/images/waiting.gif" title="Espere por favor...">
</div>	
</form>


<div id="dialogCargaEsop" title="Carga de ESOP" style="heigth:500px; min-width:700px;">

	<div class="ui-TitulosEtiquetas dialogTitulo" style="width:100%; float:left;" >Datos generales del trabajador.</div>		
		<div style="width:100%; float:left;">
		
			<table style="width:100%; text-align:left;" border=0 >
				<tr style="width:50%; text-align:left;">
					<td style="width:120px; text-align:left;" >Nombre y Apellido</td>
					<td style="width:auto; text-align:left;" ><div id="dialogCargaEsopNomApe" >APELLIDO  NOMBRE</div></td>
					<td style="width:120px;" >Sector de Trabajo</td>
					<td><div id="dialogCargaEsopSector">SECTOR NNNNNN</div></td>
				</tr>
				
				<tr style="width:50%; text-align:left;">
					<td>CUIL</td>
					<td><div id="dialogCargaEsopCUIL">00000000000</div></td>
					<td>Puesto de Trabajo</td>
					<td><div id="dialogCargaEsopPuesto">PUESTO DE TRABAJO</div></td>
				</tr>
			</table>
			
		</div>		
	<p/>
	<div class="ui-TitulosEtiquetas dialogTitulo" >Detalles de ESOP seleccionado.</div>	
	<p/>
	<div style="width:98%; padding-right:10px; float:left;">
		<table style="width:100%">
			<tr style="width:100%; text-align:left;">
				<td style="padding-top:5px; width:55%;" >ESOP 
					<input type="text" id="codigoBuscar" style="width:40px;" title='Ingrese el codigo a buscar'> 
					<input type="text" id="textoBuscar" style="width:160px;" title='Ingrese el texto a buscar' >  
					<input type="button" id="botonBuscar" class="btnBuscar" > 
					<img src="/images/loading.gif" style="vertical-align:-4px; display:none;" id="loadingESOP" />
				</td>					
					
				<td style="padding-top:5px; padding-right:15px; width:45%; text-align:right; font: italic normal 10px Verdana;" > <input id='soloRiesgosSelecc' type="checkbox" > Mostrar solo los seleccionados para el trabajador</td>
			</tr>
			<tr>				
				<td colspan="2" > 
					<div style="width:100%; float:left;"> 
						<form name="formularioGrid" >							
							<div id="idGridDatosESOP" ><?php echo getGridDatosESOP(1, $hiddenCODIGOCIUU, '', '', ''); ?> </div> 
						</form>
					</div>
				</td>
			</tr>
		</table>			
			<div style="float:right; text-align: right;">
				<div style="float:left;" class="boxEjemploCIIU" ></div>			
				<label style="float:left; padding:5px;">  Riesgos sugeridos según CIIU </label>
			</div>
			<div style="float:left; text-align: left;">
				<label style="float:right; padding:5px; font-style: italic;"> En el caso de optar por los Riesgos sugeridos según su Actividad (CIIU) debe seleccionar solo los que corresponda para cada trabajador.</label>
			</div>
	</div>				
	<div id="dialogListaErrores" class="txt-msj-Aviso" ></div>
	
</div>

<div id="dialogPersonalExpuestoNNdeA" title="Resultado importacion ">
	<b class="txt-msj-Aviso" >Nueva nomina importada de Año Anterior:</b>		
	<p>
	<i id="tituloTrabajadores" >Trabajadores No importados</i>
	<p>	
	<div id="listaTrabajadores" ></div>	
</div>

<div id="dialogMostrarMsjGrid" title="Aviso" >
	<b class="txt-msj-Aviso" id="MostrarMsjGridTit" >Aviso</b>			
	<div id="MostrarMsjGridText" style="max-height:200px; padding-top:3px;" >Mensaje</div>	
</div>

<div id="dialogMensajeValidacion" title="Aviso" >
	<b class="txt-msj-Aviso" id="encabezadoAV" ></b>			
	<p>	
	<div id="msjAValid" style="max-height:200px; padding-top:3px;" >texto</div>	
	
</div>

<div id="dialogMensajeDatosDefault" title="Aviso" >
	<b class="txt-msj-Aviso" id="encabezadoAviso" >Riesgos seleccionados por Defecto</b>			
	<p>	
	<div id="mensajeAviso" style="text-align:left;">¿Desea mantener los ESOP seleccionados para los siguientes trabajadores a cargar en la nómina? .</div>	
	
	<div id="mensajeAviso2" style="text-align:left; padding-top:5px;"><i>De esta forma, al cargarse un nuevo registro, no tendrá que volver a seleccionar los mismos códigos de riesgos.</i></div>	
	
	<div id="mensajeAviso3" style="text-align:left; padding-top:5px;" ><i>Cancelar elimina la selección por defecto y se tomaran los riesgos de la actividad como riesgos por defecto.</i></div>	
	
	<div id="mensajeAviso3" style="text-align:left; padding-top:5px;" ><i>Si no selecciono ningun ESOP de la lista se tomaran los riesgos de la actividad como riesgos por defecto.</i></div>	
</div>

<div id="dialogDetalleRiesgo" title="Detalle de ESOP ">
	
	<b class="txt-msj-Aviso" ></b>		
	
	<div style="text-align:cener; width:98%" >
		<table width="100%" border='0' >
		<tr>
			<td width='200px' ><div class="EsopColumnRight" >CODIGO : </div></td>
			<td><div class="EsopColumnLeft" id="detaESOP_Codigo" >PRIMERO</div></td>
		</tr>
		<tr>
			<td><div class="EsopColumnRight" >AGENTE RIESGO : </div></td>
			<td><div class="EsopColumnLeft" id="detaESOP_AgenteRiesgo" >PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO </div></td>
		</tr>
		<tr>
			<td><div class="EsopColumnRight">GRUPO : </div></td>
			<td><div class="EsopColumnLeft" id="detaESOP_Grupo" >PRIMERO</div></td>
		</tr>
		<tr>
			<td><div class="EsopColumnRight">CRITERIO DE EXPOSICION 1 : </div></td>
			<td><div class="EsopColumnLeft" id="detaESOP_Criterio1" >PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO PRIMERO </div></td>
		</tr>
		<tr>
			<td><div class="EsopColumnRight">CRITERIO DE EXPOSICION 2 : </div></td>
			<td><div class="EsopColumnLeft" id="detaESOP_Criterio2" >PRIMERO</div></td>
		</tr>
		<tr>
			<td><div class="EsopColumnRight"  >OBSERVACIONES : </div></td>
			<td><div class="EsopColumnLeft" id="detaESOP_Observaciones" >PRIMERO</div></td>
		</tr>	
		<tr>
			<td><div class="EsopColumnRight"  >LIMITE 295/03 : </div></td>
			<td><div class="EsopColumnLeft" id="detaESOP_Limite" >PRIMERO</div></td>
		</tr>
		<tr>
			<td><div class="EsopColumnRight"  >ACGIH : </div></td>
			<td><div class="EsopColumnLeft" id="detaESOP_ACGIH" >PRIMERO</div></td>
		</tr>
		</table>
	</div>
	<p>		
</div>

<div id="dialogImportarExel" title="Importar" >
	<b class="txt-msj-Aviso" id="encabezadoXLS" ></b>			
	<p>	
	
	<div id="mensajeAV" style="padding-top:3px;" >
		
		<form action="/modules/usuarios_registrados/clientes/RAR/procesar_excel.php" 
				enctype="multipart/form-data" 
				id="formImportarXLS" 
				method="post" 
				name="formImportarXLS" 
				target="_self">
			
			<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="10485760" />
			<table>
				<tr>					
					<td>
						<input class="InputText" id="archivoxls" name="archivoxls" style="width:310px;" type="file" value="" />
					</td>					
				</tr>
				<tr>
					<td>
						<div style="padding:10;" >
						<span>El archivo debe ser menor a 10Mb. El archivo a importar debe tener la extensión xls</span>
						</div>
						<!--
						<input class="btnCargar" style="margin-left:328px;" type="submit" value="" />
						-->
					</td>
				</tr>
			</table>
			
		</form>
	</div>	
</div>

<div id="procesandoArchivo" name="procesandoArchivo" class="ui-widget-overlay ui-front" style="z-index: 100; display:none" >
	<img style="padding-top:10%; padding-left:45%; " 
		src="/images/ProcesandoArchivo.gif" 
		title="Importando xls Espere por favor...">
</div>

</body>
