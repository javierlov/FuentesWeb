<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");
//ObtenerChequeDetalle

function getGrid($idUsuario, $CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio) {	

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
		$params[":NroExpediente"] = str_replace("-", "", $NroExpediente);		
		$where.= "  AND JT_NROEXPEDIENTE = :NroExpediente ";
	}
	
	 if($NroCarpeta != ""){
		$params[":NroCarpeta"] = str_replace("-", "", $NroCarpeta);		
		$where.= "  AND JT_NUMEROCARPETA = :NroCarpeta ";
	}
	
	if(is_numeric($tipoJuicio)) {
		if(($tipoJuicio != "") and ($tipoJuicio > "0")){
			$params[":tipoJuicio"] = str_replace("-", "", $tipoJuicio);		
			$where.= "  AND JT_IDTIPO = :tipoJuicio ";		
		}
	}
	
	$sql = ObtenerListaJuiciosEnTramite($idAbogado, $where, $ob);

	$grilla = new GridDos(1, 15);
								//titulo		an	vis		delet	colhi	but	act	cls	mx	ust	
	$grilla->addColumn(new Column("Carpeta", 	0, true, false, 	-1, 	"", "", "", -1, false));	
	$grilla->addColumn(new Column("Caratula", 	0, true, false, 	-1, 	"", "", "", -1, false));	
	$grilla->addColumn(new Column("Expediente", 0, true, false, 	-1, 	"", "", "", -1, false));		
	
	$grilla->addColumn(new Column("J", 0, true, false, -1, "btnpdf","/index.php?pageid=99", "", -1, true, -1, "Juicio"));		
	$grilla->addColumn(new Column("P", 0, true, false, -1, "btnPdf","/index.php?pageid=104", "", -1, true, -1, "Pericias"));	
	$grilla->addColumn(new Column("E", 0, true, false, -1, "btnPdf","/EventosWebForm", "", -1, true, -1, "Eventos"));		
	$grilla->addColumn(new Column("S", 0, true, false, -1, "btnPdf","/SentenciaWebForm", "", -1, true, -1, "Sentencia"));	
			
	$grilla->setColsSeparator(false);
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setRowsSeparatorColor("#c0c0c0");	
	$grilla->setShowTotalRegistros(true);	
	$grilla->setSql($sql);	
	$grilla->setUseTmpIframe(false);	
	$grilla->setTableStyle("GridTableCiiu");
	
	$grilla->setNameParamGET("NroJuicio");		
	
	return $grilla->Draw(false);
}

function ObtenerListaJuiciosEnTramite($idAbogado, $where, $ob)
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
El objetivo es no mostrar los juicios con estado ERROR DE CARGA en el portal WEB de seguimiento de juicios.*/
  $strqry= "
		 SELECT 
				jt_numerocarpeta ¿NUMEROCARPETA?,  
				NVL(jt_demandante, '') || ' C/ ' || NVL(jt_demandado, '') || ' ' || jt_caratula AS ¿DESCRIPCARATULA?,				
				NVL2(jt_anioexpediente, jt_nroexpediente || '/' || jt_anioexpediente, jt_nroexpediente) ¿EXPEDIENTE?, 
				JT_ID ¿NROJUICIO?, 
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
			AND legales.ljt_juicioentramite.JT_IDESTADO NOT IN (3)";
			
  return $strqry.$where.$ob;  
  
}

