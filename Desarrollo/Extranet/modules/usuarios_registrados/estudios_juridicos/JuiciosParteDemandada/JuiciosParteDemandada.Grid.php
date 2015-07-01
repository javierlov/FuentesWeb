<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
//ObtenerChequeDetalle

function getGridJuiciosParteDemada($idUsuario, $CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio) {	

	$idCheque = "0";
	if (isset($_REQUEST["id"]))
		$idCheque = $_REQUEST["id"];

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
	
	$idAbogado = $idUsuario;
				 
	$params = array();
	$params[":idAbogado"] = $idAbogado;
	$where = "";
	$ob = " ORDER BY jt_numerocarpeta ";
	
	if ($CodCaratula != "") {
		$params[":CodCaratula"] = str_replace("-", "", $CodCaratula);		
		$where.= " AND  NVL(jt_demandante, '') || 'C/' || NVL(jt_demandado, '') || ' ' || jt_caratula
                LIKE '%' || UPPER(:CodCaratula) ||'%' ";
	}
	
	if ($NroExpediente != ""){
		/*Implementacion Búsqueda por nroexp - Diseño Funcional.doc
		· El número de expediente está conformado por el campo número 
			(JT_NROEXPEDIENTE) y el campo año (JT_ANIOEXPEDIENTE) de la tabla 
			LJT_JUICIOENTRAMITE. 
			Actualmente la búsqueda sólo se está realizando por JT_NROEXPEDIENTE. Si el 
			usuario ingresa un valor con el formato xxxxx/xx, el sistema deberá realizar la búsqueda 
			por ambos campos, por lo que hay que modificar el query de la pantalla principal para que 
			traiga resultados tanto si ingreso solo el expediente como si ingreso expediente/año. 
		*/
		$params[":NroExpediente"] = str_replace("-", "/", $NroExpediente);		
		$where.= "  AND NVL2(jt_anioexpediente, jt_nroexpediente || '/' || jt_anioexpediente, jt_nroexpediente) = :NroExpediente ";
	}
	
	 if($NroCarpeta != ""){
		$params[":NroCarpeta"] = str_replace("-", "", $NroCarpeta);		
		$where.= "  AND JT_NUMEROCARPETA = :NroCarpeta ";
	}
	
	if(is_numeric($tipoJuicio)) {
		if(($tipoJuicio != "") and ($tipoJuicio > "0")){
			$params[":tipoJuicio"] = str_replace("-", "/", $tipoJuicio);		
			$where.= "  AND JT_IDTIPO = :tipoJuicio ";		
		}
	}
	
	$obord = '1';
	if (isset($_REQUEST["ob"]))	$obord = $_REQUEST["ob"];
	
	$sql = ObtenerListaJuiciosEnTramiteGrid($idAbogado, $where, $ob);

	$grilla = new GridDos(10, 8);
	//------------------------------------------------------------------------------------------------------
								//titulo		an	vis		delet	colhi	but	act	cls	mx	ust	
	$columnaDos = new ColumnDos("Carp.", 	40, true, false, 	-1, 	"", "", "", -1, false, -1, "Carpeta");
	$columnaDos->SetClassData('linkJuicios');
	$grilla->addColumn($columnaDos);	
	//-----------------NUEVO-TIPO-DE-COLUMNA----------------------------------------------------------------
	//CAMBIO PAG 99=106 -->
	$JuicioRedirect = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=106";	
	$columnaDos = new ColumnDos("Carátula",	0, true, false,	-1, "", $JuicioRedirect, "", -1, true, -1, "Carátula");
	$columnaDos->SetIsLink(true);	
	$columnaDos->SetClassData('linkCaratula');		
	$columnaDos->SetColumnIDRefernce(5);	
	$grilla->addColumn($columnaDos);	
	//------------------------------------------------------------------------------------------------------
	$columnaDos = new ColumnDos("Exp.", 50, true, false, 	-1, "", "", "", -1, false, -1, "Expediente");
	$columnaDos->SetClassData('linkJuicios');
	$grilla->addColumn($columnaDos);		
	//------------------------------------------------------------------------------------------------------
	$columnaDos = new ColumnDos("Estado", 40, true, false, -1, "", "", "gridTextCenter", -1, false, -1, "Estado");
	$columnaDos->SetClassData('linkJuicios');
	$grilla->addColumn($columnaDos);				
	//------------------------------------------------------------------------------------------------------	
	//CAMBIO PAG 104=111 -->
	$PeritajesRedirect = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=111";
	$grilla->addColumn(new ColumnDos("P", 30, true, false, -1, "btnPericiasJPD", $PeritajesRedirect, "", -1, true, -1, "Pericias"));	
		
	//CAMBIO PAG 101=108 -->
	$EventosRedirect = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=108";
	$grilla->addColumn(new ColumnDos("E", 30, true, false, -1, "btnEventosJPD", $EventosRedirect, "", -1, true, -1, "Eventos"));		
	
	//CAMBIO PAG 107=114 -->	
	$SentenciaRedirect = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php?pageid=114";	
	$grilla->addColumn(new ColumnDos("S", 30, true, false, -1, "btnSentenciasJPD", $SentenciaRedirect, "", -1, true, -1, "Sentencia"));	
	
	$grilla->DefaultConfiguration();
	$grilla->setNameParamGET("NroJuicio");			
	
	//setea el numero de columna seleccionada para ordenar
	$grilla->setOrderBy($obord);
	//muestra una animacion en la busqueda
	$grilla->setShowProcessMessage(true);
	//le asigna a las colunmnas el ordenado az za	
	$grilla->setAsignEventColumn(true);	
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
	
	return $grilla->Draw(false);
}

function ObtenerListaJuiciosEnTramiteGrid($idAbogado, $where, $ob)
{ 
// jt_id ¿ID?, DECODE(jt_idestado, 2, 'T', '') ¿ESTADO?,  

/*
Modificacion: 1
NVL2(jt_nroexpediente, jt_nroexpediente || '/' || jt_anioexpediente, '') ¿EXPEDIENTE?, 								

hay que modificar la forma de mostrar los datos en la columna Nro. Expediente. 
Si el expediente no tiene año solo hay que mostrar el nro de expediente sin la barra. 
Este cambio se hará en la pantalla principal y en la pantalla de edición. 
*/
/* 
Modificacion: 2
JT_IDESTADO NOT IN (3):
Doc : Omitir los juicios con estado ERROR DE CARGA - Diseño Funcional.doc
El objetivo es no mostrar los juicios con estado ERROR DE CARGA en el portal WEB de seguimiento de juicios

				---JT_ID ¿NROJUICIO?, 
.*/
  $strqry= "SELECT 
				jt_numerocarpeta ¿NUMEROCARPETA?,				
				NVL(jt_demandante, '') || ' C/ ' || NVL(jt_demandado, '') || ' ' || jt_caratula AS ¿DESCRIPCARATULA?,				
				NVL2(jt_anioexpediente, jt_nroexpediente || '/' || jt_anioexpediente, jt_nroexpediente) ¿EXPEDIENTE?,
				DECODE(jt_idestado, 2, 'T', '') ¿ESTADO?, 								
				JT_ID ¿PERICIAS?,  
				JT_ID ¿EVENTOS?, 
				JT_ID ¿SENTENCIA?
		   FROM legales.ljt_juicioentramite, legales.lnu_nivelusuario  
		  WHERE (   jt_idabogado = nu_idabogado  
				 OR nu_usuariogenerico = 'S')  
			AND jt_fechabaja IS NULL  
			AND nu_id =  :idAbogado
			AND jt_estadomediacion = 'J'  
			AND NVL(jt_bloqueado, 'N') = 'N' 			
			AND legales.ljt_juicioentramite.JT_IDESTADO NOT IN (3) ";
			
  return $strqry.$where.$ob;  
  
}

