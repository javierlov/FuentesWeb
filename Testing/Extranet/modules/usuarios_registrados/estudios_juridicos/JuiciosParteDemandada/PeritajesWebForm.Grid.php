<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");

function getGrid($NroJuicio) {	

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
				 
	$params = array();
	$params[":NroJuicio"] = $NroJuicio;
	$sql = ObtenerPeritajes();
	
	$grilla = new GridDos(10, 10);
		
	$grilla->addColumn(new Column("Pericia", 		0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("F. Notificacion", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("F. Pericia", 		0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("F. Venc. Impugnacion", 0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Impugnacion", 	0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("Accion", 		0, true, false, -1, "", "", "", -1, false -1));	
	$grilla->addColumn(new Column("idjuicioentramite", 		0, true, false, -1, "", "", "", -1, false -1));	
	
	$grilla->addColumn(new Column("Edit", 0, true, false, -1, "btnPdf","/index.php?pageid=105&EDIT", "", -1, true, -1, "Edita"));		
	$grilla->addColumn(new Column("Del", 0, true, false, -1, "btnPdf","/index.php?pageid=105&DELETE", "", -1, true, -1, "Elimina"));	
		
	$grilla->setColsSeparator(true);
	
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

function ObtenerPeritajes(){ 

  $strqry= "
	 SELECT ltp.tp_descripcion ¿tipopericia?, 
	        lpj.pj_fechanotificacion ¿fechanotificacion?, 
	        lpj.pj_fechaperitaje ¿fechapericia?, 
	        lpj.pj_fechavencimpugnacion ¿fvencimpugnacion?, 
	        lpj.pj_impugnacion ¿impugnacion?, 
	        lpj.pj_id ¿ID?, 
	        lpj.pj_idjuicioentramite ¿idjuicioentramite?,
	        lpj.pj_id ¿EDITAR?, 
	        lpj.pj_id ¿ELIMINAR?  
	   FROM legales.lpj_peritajejuicio lpj, legales.ltp_tipopericia ltp 
	  WHERE lpj.pj_idtipopericia = ltp.tp_id
	    AND lpj.pj_fechabaja IS NULL 
	    AND lpj.pj_idjuicioentramite =:NroJuicio ";
  
  return $strqry;
  
}
