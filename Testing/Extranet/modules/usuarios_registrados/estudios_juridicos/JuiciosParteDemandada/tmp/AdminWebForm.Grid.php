<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
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
	
	$grilla = new Grid(1, 15);
		
	$grilla->addColumn(new Column("Carpeta ", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Caratula ", 0, true, false, -1, "", "", "", -1, false));	
	$grilla->addColumn(new Column("Expediente ", 0, true, false, -1, "", "", "", -1, false));		
	//$grilla->addColumn(new Column("ID", 0, true, false, -1, "", "", "", -1, false));
	//$grilla->addColumn(new Column("ESTADO", 0, true, false, -1, "", "", "", -1, false));		
	
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnPdf ", "/Admin-Web-Form", "", -1, true, -1, "Juicio"));		
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnPdf", "/Admin-Web-Form", "", -1, true, -1, "Pericias"));	
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnPdf ", "/Admin-Web-Form", "", -1, true, -1, "Eventos"));		
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnPdf", "/Admin-Web-Form", "", -1, true, -1, "Sentencia"));	
			
	$grilla->setColsSeparator(false);
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setRowsSeparatorColor("#c0c0c0");	
	$grilla->setShowTotalRegistros(true);	
	$grilla->setSql($sql);	
	$grilla->setUseTmpIframe(true);	
	
	$grilla->addColOpenExtWindows("CR");	
	$grilla->addColOpenExtWindows("TEX");	
	
	return $grilla->Draw(false);
}

function ObtenerListaJuiciosEnTramite($idAbogado, $where, $ob)
{ 
// jt_id ¿ID?, DECODE(jt_idestado, 2, 'T', '') ¿ESTADO?,  
  $strqry= "
		 SELECT 
				jt_numerocarpeta ¿NUMEROCARPETA?,  
				NVL(jt_demandante, '') || ' C/ ' || NVL(jt_demandado, '') || ' ' || jt_caratula AS ¿DESCRIPCARATULA?,
				NVL2(jt_nroexpediente, jt_nroexpediente || '/' || jt_anioexpediente, '') ¿EXPEDIENTE?, 								
				1 ¿Juicio?, 1 ¿Pericias?,  1 ¿Eventos?, 1 ¿Sentencia?
		   FROM legales.ljt_juicioentramite, legales.lnu_nivelusuario  
		  WHERE (   jt_idabogado = nu_idabogado  
				 OR nu_usuariogenerico = 'S')  
			AND jt_fechabaja IS NULL  
			AND nu_id =  :idAbogado
			AND jt_estadomediacion = 'J'  
			AND NVL(jt_bloqueado, 'N') = 'N' ";

  EscribirLogTxt1("ObtenerListaJuiciosEnTramite", $strqry.$where.$ob);			  
  return $strqry.$where.$ob;  
  
}

?>