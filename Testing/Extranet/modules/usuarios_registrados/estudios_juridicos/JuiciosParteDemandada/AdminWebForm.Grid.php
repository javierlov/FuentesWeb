<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");

function getGrid1($idUsuario, $CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio) {	

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
		
	$grilla->addColumn(new Column("Carpeta ", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Caratula ", 0, true, false, -1, "", "", "", -1, false));	
	$grilla->addColumn(new Column("Expediente ", 0, true, false, -1, "", "", "", -1, false));		
	
	
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnpdf ","/MasDatosJuicioWebForm", "", -1, true, -1, "Juicio"));		
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnPdf", "/PeritajesWebForm", "", -1, true, -1, "Pericias"));	
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnPdf ","/EventosWebForm", "", -1, true, -1, "Eventos"));		
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnPdf", "/SentenciaWebForm", "", -1, true, -1, "Sentencia"));	
			
	$grilla->setColsSeparator(false);
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setRowsSeparatorColor("#c0c0c0");	
	$grilla->setShowTotalRegistros(true);	
	$grilla->setSql($sql);	
	$grilla->setUseTmpIframe(false);	
	$grilla->setTableStyle("GridTableCiiu");
	
	return $grilla->Draw(false);
}

function ObtenerListaJuiciosEnTramite($idAbogado, $where, $ob)
{ 
// jt_id ¿ID?, DECODE(jt_idestado, 2, 'T', '') ¿ESTADO?,  
  $strqry= "
		 SELECT 
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
	
	$sql = ObtenerDemandas();	
	$params = array();
	$params[":nrojuicio"] = $nrojuicio;
		
	$grilla = new GridDos(1, 15);
	
	
	$grilla->addColumn(new Column("Origen", 	0, true, false, 	-1, 	"", "", "", -1, false));	
	$grilla->addColumn(new Column("Desc", 		0, true, false, 	-1, 	"", "", "", -1, false));		
	$grilla->addColumn(new Column("Siniestros", 	0, true, false, 	-1, 	"", "", "", -1, false));		
	
	//$grilla->addColumn(new Column("Siniestros", 0, true, false, -1, "btnPdf", "/index.php?pageid=99", "", -1, true, -1, "Siniestros"));
        
    $grilla->setColsSeparator(false);
    $grilla->setPageNumber($pagina);
    $grilla->setParams($params);    
    $grilla->setRowsSeparatorColor("#c0c0c0");  
    $grilla->setShowTotalRegistros(true);   
    $grilla->setSql($sql);  
    $grilla->setUseTmpIframe(false);
    $grilla->setTableStyle("GridTableCiiu");
    
	return $grilla->Draw(false);
}

function ObtenerDemandas(){
    
	$strqry = "
		SELECT origen ¿Origen?, descripcion ¿Descripcion?, siniestros ¿Siniestros?
		 FROM (
			SELECT			
				lre_reclamante.re_descripcion Origen,  
				legales.get_descripcionorigendemanda (lod_origendemanda.od_id) Descripcion,
				NVL((SELECT COUNT (*) FROM legales.v_lds_siniestrojuicioentramite WHERE ds_idorigendemanda = od_id), 0 ) Siniestros
			  FROM legales.lod_origendemanda, legales.lre_reclamante 
			 WHERE lre_reclamante.re_id = lod_origendemanda.od_idreclamante 
			   AND lod_origendemanda.od_fechabaja IS NULL
			   AND OD_IDJUICIOENTRAMITE =:nrojuicio
			)";  

	return $strqry;  
} 


function getGridReclamos($nrojuicio) {	
	
	$showProcessMsg = false;	
		
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];
	
	$sql = ObtenerReclamos();	
	$params = array();
	$params[":nrojuicio"] = $nrojuicio;
		
	$grilla = new GridDos(1, 15);
	
	$grilla->addColumn(new Column("Descripcion",	0, true, false, 	-1, 	"", "", "", -1, false));	
	$grilla->addColumn(new Column("M.Demandado", 0, true, false, 	-1, 	"", "", "", -1, false));		
	$grilla->addColumn(new Column("Inc.Demanda",	0, true, false, 	-1, 	"", "", "", -1, false));		
	$grilla->addColumn(new Column("M.Sentencia",0, true, false, 	-1, 	"", "", "", -1, false));		
	$grilla->addColumn(new Column("Porc.Sentencia",	0, true, false, 	-1, 	"", "", "", -1, false));		
	
    $grilla->setColsSeparator(false);
    $grilla->setPageNumber($pagina);
    $grilla->setParams($params);    
    $grilla->setRowsSeparatorColor("#c0c0c0");  
    $grilla->setShowTotalRegistros(true);   
    $grilla->setSql($sql);  
    $grilla->setUseTmpIframe(false);
    $grilla->setTableStyle("GridTableCiiu");
    
	return $grilla->Draw(false);
}

function ObtenerReclamos(){
/* campos en la query original delphi
lrt_reclamojuicioentramite.rt_id, 
lrt_reclamojuicioentramite.rt_idjuicioentramite, 
lrt_reclamojuicioentramite.rt_idreclamo,                 
lrt_reclamojuicioentramite.RT_IMPORTENOMINAL, 
lrt_reclamojuicioentramite.RT_INTERESES        
*/
	$strqry=" 
		SELECT 
			lrc_reclamo.rc_descripcion ¿Descripcion?, 
			lrt_reclamojuicioentramite.rt_montodemandado ¿MDemandado?,        
			lrt_reclamojuicioentramite.rt_porcentajeincapacidad ¿IncDemanda?,
			lrt_reclamojuicioentramite.rt_montosentencia ¿MSentencia?,         
			lrt_reclamojuicioentramite.rt_porcentajesentencia ¿PorcSentencia?			
	   FROM legales.lrt_reclamojuicioentramite, legales.lrc_reclamo 
	  WHERE lrc_reclamo.rc_id = lrt_reclamojuicioentramite.rt_idreclamo 
		AND lrt_reclamojuicioentramite.rt_fechabaja IS NULL 
		AND rt_idjuicioentramite = :nrojuicio";
		
	return $strqry;  		
}
