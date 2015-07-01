<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridAjax.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
@session_start(); 


function getGridDatosNominaWeb($pagina = -1, $idEstablecimiento = 0, $buscaNombre = '', $buscaCuil = '') {	

	validarSessionServer(isset($_SESSION["isCliente"]));
	
	if($pagina == -1 ) 
		$pagina = 1;
		
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = '';
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];		
		
	$showProcessMsg = false;	
	$sql = ObtenerDatosNominaWeb($buscaNombre, $buscaCuil);
	$params = array();	
	
	$params[":IDCABECERANOMINA"] = $idEstablecimiento;	
	
	if($buscaNombre != '') $params[":buscaNombre"] = "%".$buscaNombre."%";		
	if($buscaCuil != '') $params[":buscaCuil"] = $buscaCuil;	

	$grilla = new gridAjax(10, 10);				
/*
$title; $width = 0; $visible = true; $deletedRow = false; 
$colHint = -1; $buttonClass = ""; $actionButton = ""; $cellClass = ""; $maxChars = -1; 
$useStyleForTitle = true; $numCellHide = -1; $titleHint = ""; $mostrarEspera = false;	
$msgEspera = ""; $inputType = "button"; $colChecked = -1; $colButtonClass = -1;
*/
	$titSeleccionar = "Seleccionar";
	$titCUIL = "CUIL";
	$titNomApe = "Nombre Apellido";
	$titFecIng = "Fecha de ingreso a la empresa";
	$titFecIni = utf8_encode("Fecha de inicio de la exposición");
	$titSector = "Sector de Trabajo";
	$titPuesto = "Puesto de Trabajo";
	$titIdentif = utf8_encode("Identificación de riesgo según código ESOP");
	
	$columnaFunc = new columnAjax($titSeleccionar, 0, true, false, -1, "btnQuitar", "", "gridColAlignCenter", -1, false, -1, "", false, "", "button", 0);
	$columnaFunc->setFunctionAjax('eliminarItem');
	
	$grilla->addColumn($columnaFunc);
	//-------columna cuil-------------------
	$columnCUIL = new columnAjax($titCUIL, 0, true, false, -1, "", "", "gridColAlignCenter gridTitWhite", -1, false, -1, "", false, "");
	$columnCUIL->setEventHTMLdblclick("mensajenomodificar", 1);
	$grilla->addColumn($columnCUIL);
	//-------columna nombre apellido-------------------
	$columnNOMAPE = new columnAjax($titNomApe, 0, true, false, -1, "", "", "gridColAlignLeft ", -1, false);
	$columnNOMAPE->setEventHTMLdblclick("HabilitaColumnaNombApe", 1);
	$grilla->addColumn($columnNOMAPE);
	//-------columna fecha ingreso-------------------
	$columnFECING = new columnAjax($titFecIng, 0, true, false, -1, "", "", "gridColAlignLeft ", -1, false);
	$columnFECING->setEventHTMLdblclick("HabilitaColumnaFecIng", 1);
	$grilla->addColumn($columnFECING);
	//-------columna fecha inicio-------------------
	$columnFECINI = new columnAjax($titFecIni, 0, true, false, -1, "", "", "gridColAlignLeft ", -1, false);
	$columnFECINI->setEventHTMLdblclick("HabilitaColumnaFecIni", 1);
	$grilla->addColumn($columnFECINI);
	//-------columna sector-------------------
	$columnSectorTrab =  new columnAjax($titSector, 0, true, false, -1, "", "", "gridColAlignLeft ", -1, false);
	$columnSectorTrab->setEventHTMLdblclick("HabilitaColumnaSector", 1);
	$grilla->addColumn($columnSectorTrab);
	//-------columna puesto-------------------	
	$columnPUESTO = new columnAjax($titPuesto, 0, true, false, -1, "", "", "gridColAlignLeft ", -1, false);
	$columnPUESTO->setEventHTMLdblclick("HabilitaColumnaPuesto", 1);
	$grilla->addColumn( $columnPUESTO );
	//-------columna ESOP-------------------	
	$columnESOP = new columnAjax( $titIdentif, 0, true, false, -1, "", "", "gridColAlignLeft ", -1, false);	
	$columnESOP->setEventHTMLdblclick("HabilitaColumnaESOP", 1);
	$grilla->addColumn( $columnESOP );
	//--------------------------	
			
	/* setShowMessageNoResults(false) : no se muestra el msj de que no se encontraron itmes 
		es necesario para mostrar un unico registro para insertar nuevos items */
	$grilla->setShowMessageNoResults(false); 
	
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setShowProcessMessage(true);
	$grilla->setShowTotalRegistros(true);
	$grilla->setSql($sql);
	$grilla->setTableStyle("GridTableCiiu");
	$grilla->setUseTmpIframe(false);
	
	
	$controlsHTML[1] = "<input class='btnMas' id='nuevoRegistro' onclick='HabilitarEdicion()' type='button'> <input class='btnSave' style='display:none;'  id='saveRegistro' onclick='GrabarRegistroNomina()' type='button'> ";	
	//onBlur='ValidarGrabarRegistroNuevo() '
	$controlsHTML[2] = "<input type='text' style='width:77px; height:auto; display:block;' value='' id='input_CUIT' class='txt-enabled' maxlength='20' onKeypress='BuscarTrabajadorKey(event)' title=' Ingrese al menos 2 numeros para iniciar la busqueda de cuil.' />";
	$controlsHTML[3] = "<input id='id_nrNomApe' type='text' style='display:block; width:150px; height:auto;' value='' class='txt-enabled' maxlength='50' />";
	
	$controlsHTML[4] = "<input id='id_nrFecIng' type='text' style='display:block; width:80px; height:auto;' value='' class='txt-enabled' maxlength='12'  />";
	
	$controlsHTML[5] = "<input type='text' id='id_nrFecIni' style='display:block; width:80px; height:auto;' value='' class='txt-enabled' maxlength='12' row='1'  /> ";
	
	$controlsHTML[6] = "<input id='id_nrSecTra' type='text' style='display:block; width:150px; height:auto; ' value='' class='txt-enabled' maxlength='100'  onblur='VerificaryGrabarRegistro(true);' />";
	
	$textLista = 'Ingese texto, seleccione de la lista';
	$controlsHTML[7] = "<input id='id_nrPueTra' type='text' style='display:block; width:120px; height:auto;' value='' class='txt-enabled' maxlength='120'  	placeholder='".$textLista."' title='".$textLista."' onblur='VerificaryGrabarRegistro(true);' />";
	
	$controlsHTML[8] = " <div style='width:150px; max-width:150px; ' id='nrIdeRie' > 
								<div id='id_nrIdeRie' type='text' style='overflow: hidden; max-width:134px; width:134px;  max-height: 13px;' value='' class='txt-disabled XLSfloatLeft' > </div>
								<div style='display:none;' class='btnEditar XLSfloatRight'  onclick='verCargaESOPNuevo()' id='idbtnActualizar' > </div>
						</div>   
							";
	
	$grilla->setAddRecordInsertData($controlsHTML);		
	
	$grilla->setFuncionAjaxJS("Grilla_NominaPersonalExpuesto");		
	/*
		//funciones gridajax	
		$grilla->setFuncionAjaxOrderByJS("BuscarGrillaEstabOrderBy");		
	*/
	return $grilla->Draw(false);	
		
}

