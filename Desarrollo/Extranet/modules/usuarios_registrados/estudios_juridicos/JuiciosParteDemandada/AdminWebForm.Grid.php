<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

function ObtenerListaJuiciosEnTramite($idAbogado, $where, $ob)
{ 
// jt_id ¿ID?, DECODE(jt_idestado, 2, 'T', '') ¿ESTADO?,  
  $strqry= "SELECT 
				jt_numerocarpeta ¿NUMEROCARPETA?,  
				NVL(jt_demandante, '') || ' C/ ' || NVL(jt_demandado, '') || ' ' || jt_caratula AS ¿ESCRIPCARATULA?,                                
				/*hay que modificar la forma de mostrar los datos en la columna Nro. Expediente. 
					Si el expediente no tiene año solo hay que mostrar el nro de expediente sin la barra. 
					Este cambio se hará en la pantalla principal y en la pantalla de edición. */
				NVL2(jt_anioexpediente, jt_nroexpediente || '/' || jt_anioexpediente, jt_nroexpediente) ¿EXPEDIENTE?, 
				1 ¿JUICIO?, 
				1 ¿PERICIAS?,  
				1 ¿EVENTOS?, 
				1 ¿SENTENCIA?
		   FROM legales.ljt_juicioentramite, legales.lnu_nivelusuario  
		  WHERE (   jt_idabogado = nu_idabogado  
				 OR nu_usuariogenerico = 'S')  
			AND jt_fechabaja IS NULL  
			AND nu_id =  :idAbogado
			AND jt_estadomediacion = 'J'  
			AND NVL(jt_bloqueado, 'N') = 'N' ";

    return $strqry.$where.$ob;  
  
}

function getGridDemandas($nrojuicio) {	
	
	$showProcessMsg = false;	
		
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];
	
	$sql = ObtenerDemandasGrid();	
	$params = array();
	$params[":nrojuicio"] = $nrojuicio;
		
	$grilla = new GridDos(10, 10);
	
	
	$grilla->addColumn(new Column("Origen", 	0, true, false, 	-1, 	"", "", "", -1, false));	
	$grilla->addColumn(new Column("Descripción", 		0, true, false, 	-1, 	"", "", "", -1, false));			
	//CAMBIO PAG 116=123 -->
	$grilla->addColumn(new Column("Siniestros", 0, true, false, -1, "btnLink","/index.php?pageid=123&nrojuicio=".$nrojuicio, "", -1, true, -1, "Siniestros"));		
	
	$grilla->DefaultConfiguration();			
	$grilla->setNameParamGET("OrigenDemanda");	
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
		    
	return $grilla->Draw(false);
}

function getGridReclamos($nrojuicio) {	
	
	$showProcessMsg = false;	
		
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];
	
	$sql = ObtenerReclamosGrid();	
	$params = array();
	$params[":nrojuicio"] = $nrojuicio;
		
	$grilla = new GridDos(10, 10);
	
	$grilla->addColumn(new Column("Descripción",	0, true, false, 	-1, 	"", "", "", -1, false));	
	$grilla->addColumn(new Column("M.Demandado", 0, true, false, 	-1, 	"", "", "", -1, false));		
	$grilla->addColumn(new Column("Inc.Demanda",	0, true, false, 	-1, 	"", "", "", -1, false));		
	$grilla->addColumn(new Column("M.Sentencia",0, true, false, 	-1, 	"", "", "", -1, false));		
	$grilla->addColumn(new Column("Porc.Sentencia",	0, true, false, 	-1, 	"", "", "", -1, false));		
	
	$grilla->DefaultConfiguration();			
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
		
	return $grilla->Draw(false);
}

function ObtenerDemandasGrid(){
/*
LRE_RECLAMANTE.RE_DESCRIPCION SINIESTROS  				
*/    
	$strqry = "SELECT ORIGEN ¿ORIGEN?, DESCRIPCION ¿DESCRIPCION?, SINIESTROS ¿SINIESTROS?
			FROM (SELECT			
				LRE_RECLAMANTE.RE_DESCRIPCION ORIGEN,  
				LEGALES.GET_DESCRIPCIONORIGENDEMANDA (LOD_ORIGENDEMANDA.OD_ID) DESCRIPCION,
				LOD_ORIGENDEMANDA.OD_ID SINIESTROS
				--NVL((SELECT COUNT (*) FROM LEGALES.V_LDS_SINIESTROJUICIOENTRAMITE WHERE DS_IDORIGENDEMANDA = OD_ID), 0 ) SINIESTROS
			  FROM legales.lod_origendemanda, legales.lre_reclamante 
			 WHERE lre_reclamante.re_id = lod_origendemanda.od_idreclamante 
			   AND lod_origendemanda.od_fechabaja IS NULL
			   AND OD_IDJUICIOENTRAMITE = :nrojuicio	)";  

	return $strqry;  
} 

function ObtenerReclamosGrid(){
/* campos en la query original delphi
lrt_reclamojuicioentramite.rt_id, 
lrt_reclamojuicioentramite.rt_idjuicioentramite, 
lrt_reclamojuicioentramite.rt_idreclamo,                 
lrt_reclamojuicioentramite.RT_IMPORTENOMINAL, 
lrt_reclamojuicioentramite.RT_INTERESES        
*/	
	$strqry="SELECT 
			lrc_reclamo.rc_descripcion ¿Descripcion?, 
			TO_CHAR(lrt_reclamojuicioentramite.rt_montodemandado, '".DB_FORMATMONEY."') ¿MDemandado?,      
			NVL2(lrt_reclamojuicioentramite.rt_porcentajeincapacidad,
				concat(TO_CHAR(lrt_reclamojuicioentramite.rt_porcentajeincapacidad, '".DB_FORMATPERCENT."'), ' %'), '' ) ¿IncDemanda?,
			TO_CHAR(lrt_reclamojuicioentramite.rt_montosentencia, '".DB_FORMATMONEY."') ¿MSentencia?,         
			NVL2(lrt_reclamojuicioentramite.rt_porcentajesentencia, 
				concat(TO_CHAR(lrt_reclamojuicioentramite.rt_porcentajesentencia, ".DB_FORMATPERCENT."), ' %' ),  '') ¿PorcSentencia?
	   FROM legales.lrt_reclamojuicioentramite, legales.lrc_reclamo 
	  WHERE lrc_reclamo.rc_id = lrt_reclamojuicioentramite.rt_idreclamo 
		AND lrt_reclamojuicioentramite.rt_fechabaja IS NULL 
		AND rt_idjuicioentramite = :nrojuicio";
		
	return $strqry;  		
}