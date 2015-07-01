<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/FuncionesEstablecimientos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

@session_start(); 

validarSesion(isset($_SESSION["isCliente"]));

$ACCION = 'INSERT';
$NROESTABLECI = 0;
$CODIGOEWID = 0;
$GENERANOMINA = false;
$SHOWDIALOGUSERDEFAULT = 'NO';

if (isset ($_SESSION['FormulariosNomina']['CODIGO'] )){	
	if( $_SESSION['FormulariosNomina']['CODIGO'] == 'CARGADA' )	$ACCION = 'EDIT';
	if( ISSET($_SESSION['FormulariosNomina']['NROESTABLECI']) ) $NROESTABLECI = $_SESSION['FormulariosNomina']['NROESTABLECI'];
	
	if( ISSET($_SESSION['FormulariosNomina']['CODIGOEWID']) ) {			
		$CODIGOEWID = $_SESSION['FormulariosNomina']['CODIGOEWID'];			
	}	
}

if( !isset($_SESSION['FormulariosNomina']) ){
		echo "<script>					
			window.location.assign('/SeleccionarEstablecimiento');			 
		</script>";	
}

$id = $_SESSION["FormulariosNomina"]["ID"];
$row = DatosGenEstablecimiento($id);
$DesbloqTipoNomina = 'N';

extract( GetDatosNominaWebEstablecimiento($CODIGOEWID)  , EXTR_PREFIX_ALL, "DNWE");	
$_SESSION["IDACTIVIDAD"] = $DNWE_EW_IDACTIVIDAD;		

if($ACCION == 'EDIT'){	
	if($DNWE_EW_TIPOESTAB == 'O'){ 
		//echo '<script type="text/javascript">  DesactivaTipoNominaSeleccion(); </script> ';
	} 	
	$DesbloqTipoNomina = $DNWE_EW_TIPONOMINA;
}
/*busca responsable de HYS*/
extract(GetDatosNominaWebResponsable($CODIGOEWID, 'H')  , EXTR_PREFIX_ALL, "DNWR_H");	
extract(GetDatosNominaWebResponsable($CODIGOEWID, 'R')  , EXTR_PREFIX_ALL, "DNWR_R");	
extract(GetDatosNominaWebResponsable($CODIGOEWID, 'C')  , EXTR_PREFIX_ALL, "DNWR_C");	

$ArrayJsonTel = GetTelefonosJSON($CODIGOEWID);

$ExisteNominaEnProceso = Existe_NominaWebAnualEnProceso($row["ES_NROESTABLECI"], $row["CUIT"]);
$ExisteNominaAnnoAnterior = Existe_NominaWebAnnoAnterior($row["ES_NROESTABLECI"], $row["CUIT"]);

$checked_C_Exp = '';
$checked_S_Exp = '';
if( ($DNWE_EW_TIPOESTAB == 'O') and ($DNWE_EW_TIPONOMINA == 'S') ) $checked_C_Exp = ' checked ';
if( ($DNWE_EW_TIPOESTAB == 'O') and ($DNWE_EW_TIPONOMINA == 'N') ) $checked_S_Exp = ' checked ';

$CODIGOCIUU = 0;
if($DNWE_EW_IDACTIVIDAD > 0)
	$CODIGOCIUU = $DNWE_EW_IDACTIVIDAD;
	
if( isset($_SESSION['FormulariosNomina']['RESPONSABLEDEFAULT']) ){
		
	if($_SESSION['FormulariosNomina']['RESPONSABLEDEFAULT'] == 'SI' 
		and isset($_SESSION['Responsable']) 
		and isset($_SESSION['FormulariosNomina']['CODIGO']) 
		and $_SESSION['FormulariosNomina']['CODIGO'] == 'NOGENERADA' ){
						
			$DNWR_H_RW_CARGO = $_SESSION['Responsable']['DNWR_H_RW_CARGO'];
			$DNWR_H_RW_TIPODOCUMENTO = $_SESSION['Responsable']['DNWR_H_RW_TIPODOCUMENTO'];
			$DNWR_H_RW_NUMERODOCUMENTO = $_SESSION['Responsable']['DNWR_H_RW_NUMERODOCUMENTO'];
			$DNWR_H_RW_SEXO = $_SESSION['Responsable']['DNWR_H_RW_SEXO'];
			$DNWR_H_RW_NOMBRE = $_SESSION['Responsable']['DNWR_H_RW_NOMBRE'];
			$DNWR_H_RW_APELLIDO = $_SESSION['Responsable']['DNWR_H_RW_APELLIDO'];
			$DNWR_H_CARGO_DESCRIPCION = $_SESSION['Responsable']['DNWR_H_CARGO_DESCRIPCION'];
			$DNWR_H_RW_EMAIL = $_SESSION['Responsable']['DNWR_H_RW_EMAIL'];
			
			$DNWR_R_RW_ID = $_SESSION['Responsable']['DNWR_R_RW_ID'];
			$DNWR_R_RW_IDRELEVNOMINA = $_SESSION['Responsable']['DNWR_R_RW_IDRELEVNOMINA'];
			$DNWR_R_RW_TIPODOCUMENTO = $_SESSION['Responsable']['DNWR_R_RW_TIPODOCUMENTO'];
			$DNWR_R_RW_NUMERODOCUMENTO = $_SESSION['Responsable']['DNWR_R_RW_NUMERODOCUMENTO'];
			$DNWR_R_RW_SEXO = $_SESSION['Responsable']['DNWR_R_RW_SEXO'];
			$DNWR_R_RW_NOMBRE = $_SESSION['Responsable']['DNWR_R_RW_NOMBRE'];
			$DNWR_R_RW_APELLIDO = $_SESSION['Responsable']['DNWR_R_RW_APELLIDO'];
			$DNWR_R_RW_CODAREA = $_SESSION['Responsable']['DNWR_R_RW_CODAREA'];
			$DNWR_R_RW_TELEFONO = $_SESSION['Responsable']['DNWR_R_RW_TELEFONO'];
			$DNWR_R_RW_TIPOTELEFONO = $_SESSION['Responsable']['DNWR_R_RW_TIPOTELEFONO'];
			$DNWR_R_RW_EMAIL = $_SESSION['Responsable']['DNWR_R_RW_EMAIL'];
			
			$DNWR_C_RW_ID = $_SESSION['Responsable']['DNWR_C_RW_ID'];
			$DNWR_C_RW_IDRELEVNOMINA = $_SESSION['Responsable']['DNWR_C_RW_IDRELEVNOMINA'];
			$DNWR_C_RW_NOMBRE = $_SESSION['Responsable']['DNWR_C_RW_NOMBRE'];
			$DNWR_C_RW_APELLIDO = $_SESSION['Responsable']['DNWR_C_RW_APELLIDO'];
			$DNWR_C_RW_CODAREA = $_SESSION['Responsable']['DNWR_C_RW_CODAREA'];
			$DNWR_C_RW_TELEFONO = $_SESSION['Responsable']['DNWR_C_RW_TELEFONO'];
			$DNWR_C_RW_TIPOTELEFONO = $_SESSION['Responsable']['DNWR_C_RW_TIPOTELEFONO'];
			$DNWR_C_RW_EMAIL = $_SESSION['Responsable']['DNWR_C_RW_EMAIL'];
			$DNWR_C_RW_IGUALARESP = $_SESSION['Responsable']['DNWR_C_RW_IGUALARESP'];
			
			$CODIGOEWID = $_SESSION['Responsable']['CODIGOEWID'];		
			$ArrayJsonTel = GetTelefonosJSON($CODIGOEWID);
	}
}

echo "
	<script type='text/javascript'> 				
		var showDialogExp = ".($GENERANOMINA?'true':'false').";		
		
		var TipoEstabSeleccionado = '".$DNWE_EW_TIPOESTAB."';
		var TipoNominaSeleccExp = '".$DNWE_EW_TIPONOMINA."';
		var CODIGOEWID = '".$CODIGOEWID."';
		var CODIGOCIUU = '".$CODIGOCIUU."';
		
		var ExisteNominaEnProceso = '".$ExisteNominaEnProceso."';
		var ExisteNominaAnnoAnterior = '".$ExisteNominaAnnoAnterior."';
		
		var ArrayJsonTelEdit = '".$ArrayJsonTel."';
		var DesbloqTipoNomina = '".$DesbloqTipoNomina."';
		var empresaESTABLECI = '".$row["ES_NROESTABLECI"]."';
		var empresaCUIT = '".$row["CUIT"]."';
		var empresaCUITSINGUION = '".CuitExtractGuion($row["CUIT"])."';
		var empresaUSUARIO = '".substr($_SESSION["usuario"], 0, 20)."';		
			
		var idActividad = '".$_SESSION["IDACTIVIDAD"]."';
	</script>
	";
//<!doctype html>

/*	
		".GetJS_ArrayCIUU("listaciuu").";   
		".GetJS_ArrayCIUUCodigos("listaciuuCodigos")."; 		
		".GetJS_ArrayCargo("listaCargos").";
*/
?>
<head>	
	<title>Formulario Nomina</title>	
	<link href="/styles/style2.css" rel="stylesheet">	
	<link href="/styles/style.css" rel="stylesheet">		
	<link href="/styles/rar/jquery-ui-custom.css<?php echo RandonNumberParameter(); ?>" rel="stylesheet">	
	<link href="/modules/usuarios_registrados/clientes/RAR/css/rar.css<?php echo RandonNumberParameter(); ?>" rel="stylesheet">	
	
	
<?php		
	echo JSjqueryVersion();		
	echo JSjqueryUIVersion();
?>		
	<script src="/js/rar/Comunes.js<?php echo RandonNumberParameter(); ?>"></script>	
	<script src="/js/rar/ComunesJQ.js<?php echo RandonNumberParameter(); ?>"></script>	
	<script src="/js/rar/ProcesarDatos.js<?php echo RandonNumberParameter(); ?>"></script>	
	<script src="/modules/usuarios_registrados/clientes/RAR/js/FormulariosNomina.js<?php echo RandonNumberParameter(); ?>" ></script>
	<script src="/modules/usuarios_registrados/clientes/RAR/js/comunesRAR.js<?php echo RandonNumberParameter(); ?>" ></script>
	<script src="/js/rar/UserInterface.js<?php echo RandonNumberParameter(); ?>"></script>

</head>

<body>

<form name="FormulariosNomina" id="FormulariosNomina" method="POST" action="/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php" id="idFormulariosNomina" onsubmit="return BotonValidarForm()" >

<input type='hidden' name="InsertNominaNuevadeAnterior" id="InsertNominaNuevadeAnterior" value='<?= $DNWR_H_RW_CARGO ?>' />
<input type='hidden' name="hiddenCargoRespHYS" id="hiddenCargoRespHYS" value='<?= $DNWR_H_RW_CARGO ?>' />

<input type='hidden' name="hidden_R_RW_ID" id="hidden_R_RW_ID" value='<?= $DNWR_R_RW_ID ?>' />
<input type='hidden' name="hidden_R_RW_IDRELEVNOMINA" id="hidden_R_RW_IDRELEVNOMINA" value='<?= $DNWR_R_RW_IDRELEVNOMINA ?>' />

<input type='hidden' name="hidden_C_RW_ID" id="hidden_C_RW_ID" value='<?= $DNWR_C_RW_ID ?>' />
<input type='hidden' name="hidden_C_RW_IDRELEVNOMINA" id="hidden_C_RW_IDRELEVNOMINA" value='<?= $DNWR_C_RW_IDRELEVNOMINA ?>' />

<input type='hidden' name="hiddenCODIGOEWID" id="hiddenCODIGOEWID" value='<?= $CODIGOEWID ?>' />
<input type='hidden' name="hiddenACCION" id="hiddenACCION" value='<?= $ACCION ?>' />
<input type='hidden' name="hiddenCUIT" id="hiddenCUIT" value='<?= $row["CUIT"] ?>' />
<input type='hidden' name="hiddenES_NROESTABLECI" id="hiddenES_NROESTABLECI" value='<?= $row["ES_NROESTABLECI"] ?>' />
<input type='hidden' name="hiddenCODIGOCIUU" id="hiddenCODIGOCIUU"  value='<?= $DNWE_EW_IDACTIVIDAD ?>' />

<input type='hidden' name="hiddenArrayTelefonos" id="hiddenArrayTelefonos" value='' />


<div style="width:100%; overflow-x:hidden; ">

	<div id="DatosGenerales" title=""> 
		<h3 class="ui-TitulosHead" >Datos Generales del Establecimiento</h3>

		<div >	
			<div class="ui-TitulosEtiquetas" style="width:100%;"  ><b>Nombre de la empresa: </b><i><?= $row["EM_NOMBRE"]?></i></div>
			<div class="ui-TitulosEtiquetas"><b>C.U.I.T./C.U.I.L.: </b><i><?= $row["CUIT"]?></i></div>				
		<p>
			<div class="ui-TitulosEtiquetas"><b>Nº de establecimiento: </b><?php echo utf8_decode("<i>".$row["ES_NROESTABLECI"]."</i>"); ?></div>
			<div class="ui-TitulosEtiquetas"><b>CIUU:</b> (Actividad económica revisión 3) <i><?= $row["CIIUESTABLECIMIENTO"]?></i></div>	
		</p>

		<p>
			<div class="ui-TitulosEtiquetas"><b>Superficie del establecimiento en m2: </b> <i><?= $row["ES_SUPERFICIE"]?></i> </div>	
			<div class="ui-TitulosEtiquetas"></div>	
		</p>
		<p>
			<div class="ui-TitulosEtiquetas" style="width:100%;" >Código de actividad según Clasificador de Actividades Económicas (CLAE) - Formulario Nº 883 (Resolución A.F.I.P. Nº 3537): <i><?= $row["CIIUEMPRESA"]?></i></div>	
			<div class="ui-TitulosEtiquetas"></div>	
		</p>
		<p>
			<div class="ui-TitulosEtiquetas">(resolución  A.F.I.P. Nº 3537): 4521200</div>	
			<div class="ui-TitulosEtiquetas">Cantidad de trabajadores: <i><?= $row["ES_EMPLEADOS"]?></i></div>	
		</p>		
		<p>
			<div class="ui-TitulosEtiquetas" style="width:100%;"  >Breve descripción de la actividad:<i><?= $row["ES_DESCRIPCIONACTIVIDAD"]?></i> </div>	
			<div class="ui-TitulosEtiquetas"></div>	
		</p>
		<p>
			<div class="ui-TitulosEtiquetas" style="width:100%;" ><b>Domicilio: </b> <i><?= $row["DOMICILIO"]?></i></div>	
			<div class="ui-TitulosEtiquetas"></div>	
		</p>		
		<p>
			<div class="ui-TitulosEtiquetas" style="width:30%;" ><b>Provincia: </b> <i><?= $row["PV_DESCRIPCION"]?></i></div>	
			<div class="ui-TitulosEtiquetas" style="width:40%;" ><b>Código Postal Argentino: </b><i><?= $row["ES_CPOSTALA"]?></i></div>	
			<div class="ui-TitulosEtiquetas" style="width:80%;" ><b>Localidad: </b> <i><?= $row["ES_LOCALIDAD"]?></i></div>
		</p>
		<p>
			<div class="ui-TitulosEtiquetas" style="width:100%;" ><b>Teléfono: </b><i><?= $row["TELEFONO"]?></i></div>	
		</p>
		</div>
	</div>
	<p>
	<div id="Establecimientos" title=""> 
		<h3 class="ui-TitulosHead" >Selección del tipo de Establecimiento.</h3>
		<div>			
			<div class="ui-TitulosEtiquetas" style="width:100%;" >
				<input type="radio" id="radioAdmin" name="TipoEstablecimiento" value="A"  <?php if($DNWE_EW_TIPOESTAB == 'A') echo 'checked'; ?> /><label for="radioAdmin" > Administrativo </label> 			
				<label id="msjradioAdmin" class="msj-info"></label>
			</div>	
			<p>			
				<div class="ui-TitulosEtiquetas" style="width:100%;" >
					<input type="radio" id="radioComMino" name="TipoEstablecimiento" value="CM"   <?php if($DNWE_EW_TIPOESTAB == 'CM') echo 'checked'; ?> /><label for="radioComMino" > Comercio Minorista </label> 
					<label id="msjradioComMino" class="msj-info"></label>					
				</div>	
			</p>
			<p>
				<div class="ui-TitulosEtiquetas" style="width:100%;" >
					<input type="radio" id="radioObraConst" name="TipoEstablecimiento" value="OC"   <?php if($DNWE_EW_TIPOESTAB == 'OC') echo 'checked'; ?> /><label for="radioObraConst" > Obras en construcción</label>					
					<label id="msjradioObraConst" class="msj-info"></label>						
				</div>	
			</p>
			<p>
				<div class="ui-TitulosEtiquetas" style="width:100%;" >
					<input type="radio" id="radioVehi" name="TipoEstablecimiento" value="V"  <?php if($DNWE_EW_TIPOESTAB == 'V') echo 'checked'; ?> /><label for="radioVehi" > Vehículo (Taxi, remises, flete)</label> 			
					<label id="msjradioVehi" class="msj-info"></label>	
				</div>	
			</p>			
			<p>
				<div class="ui-TitulosEtiquetas" style="width:100%;" >
					<input type="radio" id="radioOtros" name="TipoEstablecimiento" value="O"  
						<?php if($DNWE_EW_TIPOESTAB == 'O') echo 'checked'; ?> /><label for="radioOtros" > Otros </label> 
				</div>	
			</p>							
			<p>
				<div class="ui-TitulosEtiquetasP10" style="width:100%;" >
					<div class="ui-TitulosEtiquetasP10" style="width:100%;" ><b>Tipo Nomina: </b> 
						<input type="radio" id="TipoNominaCE" name="TipoNomina" value="S" 
							<?php  echo $checked_C_Exp; ?>  /><label for="TipoNominaCE" > Con Expuesto </label> 
						<input type="radio" id="TipoNominaSE" name="TipoNomina" value="N"
							<?php echo $checked_S_Exp; ?>  /><label for="TipoNominaSE" > Sin Expuesto </label> 
					</div>	
				</div>	
			</p>			
						
			<p>
			<div class="ui-TitulosEtiquetas" style="width:100%;">					
			
			<table>
			<tr>	
				<td width="160px" >	
					<label style="width:auto; font-weight: bold;">CIIU (Cod. Act.) <div  name="TipoEstOblig" id="TipoEstOblig" >(*) </div></label>
					<label id="labelcodigoCIUU" /></label>
				</td>			
				<td>			
					<input style="width:60px;" type="text"  id="codigoCIUU" name="codigoCIUU"  maxlength="10" value="<?php echo $DNWE_AC_CODIGO; ?>"  title="Ingrese al menos 2 caracteres, seleccione un item de la lista" placeholder="Ingrese al menos 2 caracteres, seleccione un item de la lista" /> 
					<input id="autocompleteCIUU" type="text" style="width:400px; padding-bottom: 1px;padding-left: 4px;padding-right: 4px;padding-top: 1px;" title="Ingrese al menos 4 caracteres" placeholder="Ingrese al menos 4 caracteres, seleccione un item de la lista"  maxlength="250" value="<?php echo $DNWE_AC_DESCRIPCION; ?>" /> 				
				</td>			
			</tr>			
			<tr>					
				<td style="vertical-align: text-top">									
					<label style="font-weight: bold;">Desc. de Actividad <div  name="TipoEstOblig" id="TipoEstOblig" >(*) </div></label>
				</td>			
				<td>			
					<textarea rows="3" style="width:500px; text-transform: uppercase;" id="idTexActividad" name="idTexActividad" maxlength='100' ><?php echo $DNWE_EW_DESCRIPCIONESTAB; ?></textarea>												
				</td>			
			</tr>			
			</table>						
			</div>
				
		</div>	
	</div>	
	<p>
	<div id="ResponsableHYS" title=""> 
		<h3 class="ui-TitulosHead" >Responsable HYS</h3>
		<div>				
			<div style="width:100%; float:left;"> 
				<div class="ui-TitulosEtiquetasP10" style="width:345px; border:1px; ">
					<b>Tipo Doc. <div  name="RespHYSOblig"  id="RespHYSOblig" >(*) </div></b>
						<?php echo GetSelectTipoDocumentos('tipoDocRespHYS', 'width:250px;', $DNWR_H_RW_TIPODOCUMENTO); ?>
				</div>
							
				<div class="ui-TitulosEtiquetasP10" style="width:170px;">
					<b> Nº Doc. <div  name="RespHYSOblig"  id="RespHYSOblig" >(*) </div></b><input id="RespNumDoc" name="RespNumDoc" type="text" style="width:80px;" onKeypress="KeySoloNumeros()" maxlength="11" value="<?php echo $DNWR_H_RW_NUMERODOCUMENTO; ?>" />
				</div>	
				
				<div class="ui-TitulosEtiquetasP10" style="width:170px;">
					<b> Sexo <div  name="RespHYSOblig"  id="RespHYSOblig" >(*) </div></b><?php echo GetSelectSexos("RespTiposexo", 'width:110px;', $DNWR_H_RW_SEXO); ?>
				</div>	
			</div>	
		<p/>
			<div style="width:100%;  float:left;"> 
				<div class="ui-TitulosEtiquetasP10" style="width:345px; ">				
					<b>Nombre <div  name="RespHYSOblig"  id="RespHYSOblig" >(*) </div></b>
					<input type="text" style="width:250px; text-transform: uppercase;" maxlength="50" id="RespNombre" name="RespNombre" value="<?php echo $DNWR_H_RW_NOMBRE; ?>" />			
				</div>		
				
				<div class="ui-TitulosEtiquetasP10" style="width:345px; ">
					<b>Apellido <div  name="RespHYSOblig"  id="RespHYSOblig" >(*) </div></b>
					<input type="text" style="width:250px; text-transform: uppercase;"  maxlength="50" id="RespApellido" name="RespApellido" value="<?php echo $DNWR_H_RW_APELLIDO; ?>" />
				</div>		
			</div>					
		<p/>
			<div style="width:100%;  float:left;"> 
				<div class="ui-TitulosEtiquetasP10" style="width:345px;" >
					<b>Cargo <div  name="RespHYSOblig"  id="RespHYSOblig" >(*) </div></b>
					<input type="text" style="width:265px; text-transform: uppercase;" id="ResplistaCargos" name="ResplistaCargos" title="Ingrese al menos 3 caracteres, seleccione una opcion del listado." placeholder="Ingrese al menos 3 caracteres, seleccione una opcion del listado." maxlength="200" value="<?php echo $DNWR_H_CARGO_DESCRIPCION; ?>" />				
				</div>				
				<div class="ui-TitulosEtiquetasP10" style="width:345px;">
					<b>e-Mail <div  name="RespHYSOblig"  id="RespHYSOblig" >(*) </div></b>
					<input type="text" style="width:255px; text-transform: uppercase;" maxlength="100" id="RespEMail" name="RespEMail" value="<?php echo $DNWR_H_RW_EMAIL; ?>" />
				</div>		
			</div>		
		<p/>		
			<div style="width:100%;  float:left;"> 
				<div class="ui-TitulosEtiquetasP10" style="width:100%; text-align:left; ">				
					<input type="button" style="height:15px; width:132px; " id="AgregaTelefono" value="" />
				</div>							
				
				<div id="SinTelefonos" class="ui-TitulosEtiquetasP10"> 
					<b style="width:100%; color:red; font: Italic bold 16px Arial; padding:3px; font-align:center;" > No hay teléfonos cargados. <div  name="RespHYSOblig"  id="RespHYSOblig" >(*) </div></b>
				</div>				
			</div>		
		<p/>				
			<div class="ui-TitulosEtiquetasP10" style="width:100%; text-align:left; ">				
				<div id="listaTelefonos"></div>											
			</div>											
			
		<p/>
		</div>
	</div>
<p>	
	<div id="ResponsableEmpresa" title=""> 
		<h3 class="ui-TitulosHead" >Responsable  de la Empresa</h3>
		<div>		
			<div class="ui-TitulosEtiquetasP10" style="width:350px;">
				<b>Tipo Doc. <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b><?php echo GetSelectTipoDocumentos('TipoDocRespEmpresa', 'width:260px;', $DNWR_R_RW_TIPODOCUMENTO); ?>
			</div>
						
			<div class="ui-TitulosEtiquetasP10" style="width:170px;">
				<b> Nº Doc. <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b>
				<input type="text" style="width:70px; text-transform: uppercase;" onKeypress="KeySoloNumeros()" maxlength="11" id="RespEmpNumDoc" name="RespEmpNumDoc" value="<?php echo $DNWR_R_RW_NUMERODOCUMENTO; ?>"/>
			</div>	
			
			<div class="ui-TitulosEtiquetasP10" style="width:170px;">
				<b> Sexo <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b><?php echo GetSelectSexos("RespEmpTiposexo", 'width:110px;', $DNWR_R_RW_SEXO); ?>
			</div>			
		<p>
			<div class="ui-TitulosEtiquetasP10" style="width:345px; ">				
				<b>Nombre <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b>
				<input type="text" style="width:250px; text-transform: uppercase;" maxlength="50" id="RespEmpNombre" name="RespEmpNombre" value="<?php echo $DNWR_R_RW_NOMBRE; ?>" />			
			</div>		
			
			<div class="ui-TitulosEtiquetasP10" style="width:345px; ">
				<b>Apellido <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b>
				<input type="text" style="width:250px; text-transform: uppercase;"  maxlength="50" id="RespEmpApellido" name="RespEmpApellido" value="<?php echo $DNWR_R_RW_APELLIDO; ?>" />
			</div>		
			
		</P>	
		<p>
			<div class="ui-TitulosEtiquetasP10" style="width:170px; ">
				<b>Código Área <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b>
				<input type="text" style="width:40px; text-transform: uppercase;"  maxlength="10" id="RespEmpCodArea" name="RespEmpCodArea" value="<?php echo $DNWR_R_RW_CODAREA; ?>" />	
			</div>		
			
			<div class="ui-TitulosEtiquetasP10" style="width:300px; ">
				<b>Teléfono <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b>
				<input type="text" style="width:190px; text-transform: uppercase;"  maxlength="20" id="RespEmpTelefono" name="RespEmpTelefono" value="<?php echo $DNWR_R_RW_TELEFONO; ?>" />
			</div>						
				
			<div class="ui-TitulosEtiquetasP10" style="width:210px;">
				<b>Tipo Tel. <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b><?php echo GetSelectTipoTelefono('ResptipoTelefono', 'width:110px;', $DNWR_R_RW_TIPOTELEFONO); ?>				
			</div>			
		</P>	
		<P>	
			<div class="ui-TitulosEtiquetasP10" style="width:680px;">
				<b>e-Mail <div  name="RespEmpOblig"  id="RespEmpOblig" >(*) </div></b>
				<input type="text" style="width:580px; text-transform: uppercase;"  maxlength="100" id="RespEmpEMail" name="RespEmpEMail" value="<?php echo $DNWR_R_RW_EMAIL; ?>" />
			</div>	
		</P>			
		</div>		
	</div>		
<p>	
	<div id="Contacto" title=""> 
		<h3 class="ui-TitulosHead" >Contacto</h3>
		<div>
			<div class="ui-TitulosEtiquetasP10" style="width:345px; ">				
				<b>Nombre <div  name="ContactoOblig"  id="ContactoOblig" >(*) </div></b><input type="text" style="width:255px; text-transform: uppercase;" maxlength="50" id="ContactoNombre" name="ContactoNombre" value="<?php echo $DNWR_C_RW_NOMBRE; ?>" />				
			</div>		
			
			<div class="ui-TitulosEtiquetasP10" style="width:345px; ">
				<b>Apellido <div  name="ContactoOblig"  id="ContactoOblig" >(*) </div></b><input type="text" style="width:255px; text-transform: uppercase;"  maxlength="50" id="ContactoApellido" name="ContactoApellido" value="<?php echo $DNWR_C_RW_APELLIDO; ?>" />
			</div>					
		</P>	
		<p>
			<div class="ui-TitulosEtiquetasP10" style="width:170px; ">
				<b>Codigo Area <div  name="ContactoOblig"  id="ContactoOblig" >(*) </div></b><input type="text" style="width:50px; text-transform: uppercase;"  maxlength="10" id="ContactoCodArea" name="ContactoCodArea" value="<?php echo $DNWR_C_RW_CODAREA; ?>" />	
			</div>		
			
			<div class="ui-TitulosEtiquetasP10" style="width:300px; ">
				<b>Telefono <div  name="ContactoOblig"  id="ContactoOblig" >(*) </div></b><input type="text" style="width:190px; text-transform: uppercase;"  maxlength="20" id="ContactoTelefono" name="ContactoTelefono" value="<?php echo $DNWR_C_RW_TELEFONO; ?>" />
			</div>						
				
			<div class="ui-TitulosEtiquetasP10" style="width:220px;">
				<b>Tipo Tel. <div  name="ContactoOblig"  id="ContactoOblig" >(*) </div></b><?php echo GetSelectTipoTelefono('ContactoTipoTelefono', 'width:100px;', $DNWR_C_RW_TIPOTELEFONO); ?>				
			</div>			
		</P>	
		<P>	
			<div class="ui-TitulosEtiquetasP10" style="width:680px;">
				<b>e-Mail <div  name="ContactoOblig"  id="ContactoOblig" >(*) </div></b><input type="text" style="width:600px; text-transform: uppercase;"  maxlength="100" id="ContactoEMail" name="ContactoEMail" value="<?php echo $DNWR_C_RW_EMAIL; ?>" />
			</div>	
		</P>
		<P>				
			<div class="ui-TitulosEtiquetasP10" style="width:100%;">				
				<input type="checkbox" name="ContactoIgualaResp" value="Y" id="ContactoIgualaResp"  <?php if($DNWR_C_RW_IGUALARESP == 'Y') echo 'checked'; ?> > <label for="ContactoIgualaResp" title="Se copian los datos de Responsable de la Empresa como Contacto" >El contacto es Responsable de la Empresa</label><br>
			</div>	
		</P>
		</div>		
	</div>				
	<div class="ui-TitulosEtiquetasP10" style="width:99%;">				
		<input type="button" style="height:15px; width:78px;" id="Siguiente" name="btnSiguiente" value="" class="btnSiguiente" />	
		<input type="button" style="width:60px; height:17px; float:right;" id="Volveratras" value="" />					
		<div id='resultadoProceso' ></div>	
	</div>	


<div id="dialogNominaMensajes" title="Mensaje">
	<div id="TituloMensaje" >Titulo</div>		
	<p><div id="TextoMensaje" >Texto</div>		
</div>
	
<div id="dialogSiguienteNominaExiste" title="Siguiente">
	<p>¿Desea generar una Nómina a partir de la ultima existente?</p>	
	<I>Para generar una nómina desde cero se deberá hacer clic en el botón NO.</I>
</div>

<div id="dialogExpuesto" title="Sin personal Expuesto">
		<p>El establecimiento <b>no presenta</b> personal expuesto, la nomina se generará sin riesgos. ¿Está seguro de generar la misma? </p>
	<p>	
	<input class="btnVolver" id="idVolverDExp" style="width: 60px; height: 17px; float: right;" type="button" value="" >
</div>

<div id="dialogObraConstruccion" title="Obra en Construccion">
	<p>Para completar la nómina de personal expuesto para el personal asignado al establecimiento, comunicarse al teléfono 4335-5100 Int. 5208 </p>		
</div>

<div id="dialogNuevaNomina" title="Nueva Nomina">
	<p>Se va a generar una nueva nómina. </p>		
</div>

<div id="dialogNominaEnProceso" title="Nomina en Proceso">
	<p> Continuará cargando una nómina ya iniciada… </p>		
</div>

<div id="dialogDatosIncomp" title="Nueva Nomina" style="max-heigth:500px;">
	<b class="txt-msj-Aviso-Titulo" >Datos obligatorios incompletos.</b>		
	<div id="dialogListaErrores" class="txt-msj-Aviso-Titulo" ></div>
</div>

<div id="dialogAltaTelefono" title="Alta de Telefono">
	<P>	
	<!-- <fieldset> -->	
		<div style="width:100%; overflow:hidden;" >
			<table>
			<tr><td>
				<div class="ui-TitulosEtiquetasP10Right" style="width:180px;"><b>Tipo de telefono (*):</b></div>
				<div class="ui-TitulosEtiquetasP10" style="width:160px; text-align:left;">				
					<?php echo GetSelectTipoTelefono('ATtipoTelefono', 'width:100%;'); ?>			
				</div>	
			</td></tr>
			<tr><td>		
				<div class="ui-TitulosEtiquetasP10Right" style="width:180px;"  ><b>Area (*):</b></div>
				<div class="ui-TitulosEtiquetasP10" style="width:40px; text-align:left;">				
					<input type="text" maxlength="5" style="width:100%; text-transform: uppercase;" id="ATarea" />
				</div>	
			</td></tr>
			<tr><td>		
				<div class="ui-TitulosEtiquetasP10Right" style="width:180px;" ><b>Número (*):</b></div>
				<div class="ui-TitulosEtiquetasP10" style="width:160px; text-align:left;">				
					<input type="text" maxlength="20"  style="width:100%; text-transform: uppercase;" id="ATnumero" />
				</div>	
			</td></tr>
			<tr><td>		
				<div class="ui-TitulosEtiquetasP10Right" style="width:180px;" ><b>Interno:</b></div>
				<div class="ui-TitulosEtiquetasP10" style="width:160px; text-align:left;">				
					<input type="text" maxlength="10" style="width:100%; text-transform: uppercase;" id="ATinterno" />
				</div>	
			</td></tr>
			<tr><td>		
				<div class="ui-TitulosEtiquetasP10Right" style="width:180px;" ><b>Principal:</b></div>
				<div class="ui-TitulosEtiquetasP10" style="width:160px; text-align:left;">				
					<input type="checkbox" name="principalchk" value="Y" id="ATprincipal" > 
					<br>
				</div>	
			</td></tr>
			<tr><td>		
				<div class="ui-TitulosEtiquetasP10Right" style="width:180px;"><b>Observaciones:</b></div>
				<div class="ui-TitulosEtiquetasP10" style="width:160px; text-align:left;">				
					<input type="text" maxlength="50"  style="width:160px; text-transform: uppercase;" value="" id="ATobservaciones" />
				</div>			
			</td></tr>
			</table>
		</div>			
		<i>
			<div class="ui-TitulosEtiquetasP10" style="width:100%; color:red;" id="ATerrores" ></div>
		</i>
	<!-- </fieldset> -->
	</p>	
  </div>
</div>

<div id="dialogSinPersonalExpuesto" title="Personal Expuesto">
	<b class="txt-msj-Aviso-Titulo" >Sin Personal Expuesto:</b>		
	<p>
	<i id="motivoYaPresentada" >El tipo de establecimiento seleccionado no presenta personal expuesto</i>
	<p>	
</div>

<div id="redirectNomina" title="Resultados Importacion">
	<b class="txt-msj-Aviso-Titulo" >Personal Expuesto No importado:</b>		
	<p>
	<div id="motivoNoImporado" ></div>
	<p>	
</div>

</form>
</body>
<?php
	echo "
	<script type='text/javascript'> 
		".GetJS_ArrayCIUU("listaciuu").";   
		".GetJS_ArrayCIUUCodigos("listaciuuCodigos")."; 		
		".GetJS_ArrayCargo("listaCargos").";
	</script>
	";
?>