<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");

function getGridPeritajes($NroJuicio) {	

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
				 
	$params = array();
	$params[":NroJuicio"] = $NroJuicio;
	$sql = ObtenerPeritajes();
	
	$grilla = new GridDos(10, 10);
		
	//-------------  campo Pericia
	$columnPericia = new ColumnDos("Pericia", 130, true, false, -1, "", "", "", -1, false, -1, "Pericia");		
	$columnPericia->SetIDdivHeader("idPericia");		
	$grilla->addColumn($columnPericia);	
	//-------------  campo Pericia
	
	//-------------  campo Fecha Notif
	$columnFechaNotif = new ColumnDos("Fecha Notif.", 80, true, false, -1, "", "", "", -1, false, -1, "Fecha Notificación");		
	$columnFechaNotif->SetIDdivHeader("idFechaNotif");		
	$grilla->addColumn($columnFechaNotif);	
	//-------------  campo Fecha Notif
	
	//-------------  campo Fecha Pericia	
	$columnFechaPericia = new ColumnDos("Fecha Pericia", 80, true, false, -1, "", "", "", -1, false, -1, "Fecha Pericia");		
	$columnFechaPericia->SetIDdivHeader("idFechaPericia");		
	$grilla->addColumn($columnFechaPericia);	
	//-------------  campo Fecha Pericia
	
	//-------------  campo Fecha Pericia		
	$columnFechaVencImpug = new ColumnDos("Fecha Venc. Impug.", 80, true, false, -1, "", "", "", -1, false, -1, "Fecha Vencimiento Impugnación.");		
	$columnFechaVencImpug->SetIDdivHeader("idFechaVencImpug");		
	$grilla->addColumn($columnFechaVencImpug);	
	//-------------  campo Fecha Pericia	
	
	//-------------  campo impugnacion
	$columnImpug = new ColumnDos("Impug.", 40, true,  false, -1, "", "", "", -1, false, -1, 'Impugnación');	
	$columnImpug->SetStyleCssRow("text-align:center;");	
	$columnImpug->SetIDdivHeader("idImpug");		
	$grilla->addColumn($columnImpug);	
	//-------------  campo impugnacion
	
	//-------------  cantidad de adjuntos		
	$columnCantidad = new ColumnDos("Cant.", 25, true, false, -1, "", "", "", -1, false, -1, "Cantidad de Adjuntos");
	$columnCantidad->SetStyleCssRow("text-align:center;");
	$columnCantidad->SetIDdivHeader("idCant");		
	$grilla->addColumn($columnCantidad);	
	//-------------  cantidad de adjuntos		
	
	//$visible = (!$_SESSION["JUICIOTERMINADO"] );	
	$visible = true;
	$visibleTrue = true;
	
	//----------- Boton adjunta
	$redirectAdj = "/index.php?pageid=135";
	$grilla->addColumn(new Column("Adj.", 40, $visibleTrue, false, -1, "btnCarpeta", $redirectAdj, "", -1, true, -1, "Adjuntar"));	
	//----------- Boton adjunta
	
	//CAMBIO PAG 105=112 -->
	if($visible){
		$btnClass = 'btnEditItem';
		$titleModif = "Modif.";
		$captionModif = "Modificar";
	}else{
		$btnClass = 'btnLink';
		$titleModif = "Ver";
		$captionModif = "Ver";
	}
	
	$redirect112 = "/PeritajesABMWebForm";		
	$columnEdit = new ColumnDos($titleModif, 40, $visibleTrue, false, -1, $btnClass, $redirect112, "", -1, true, -1, $captionModif);
	$columnEdit->SetParamFriendly(true);	
	$grilla->addColumn($columnEdit);
	
	//----------- Boton Elimina item
	//CAMBIO PAG 104=111 -->
	$redirectDELETE = "/index.php?pageid=111&DELETE";
	$columnElim = new ColumnDos("Elim.", 40, $visible, false, -1, "btnDeleteItem", $redirectDELETE, "", -1, true, -1, "Eliminar");
	$columnElim->SetStyleCssRow("text-align:center;");
	$columnElim->SetIDdivHeader("idElim");		
	$columnElim->SetOnClickFunction("EliminarPericia");		
	$grilla->addColumn($columnElim);	
	//----------- Boton Elimina item
		
	$grilla->DefaultConfiguration();			
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
			
	return $grilla->Draw(false);
}

function ObtenerPeritajes(){ 
/*campos eliminados de la query
	--lpj.pj_id ¿ID?, 
	--lpj.pj_idjuicioentramite ¿idjuicioentramite?,
	
*/
  $strqry= "
	 SELECT tipopericia ¿tipopericia?, 
	        fechanotificacion ¿fechanotificacion?, 
	        fechapericia ¿fechapericia?, 
	        fvencimpugnacion ¿fvencimpugnacion?, 
	        impugnacion ¿impugnacion?, 
			cantidad ¿cantidad?,
	        ADJUNTOS ¿ADJUNTOS?,
	        EDITAR ¿EDITAR?, 
	        ELIMINAR ¿ELIMINAR?  
	 From (
		 SELECT ltp.tp_descripcion ¿tipopericia?, 
				lpj.pj_fechanotificacion ¿fechanotificacion?, 
				lpj.pj_fechaperitaje ¿fechapericia?, 
				lpj.pj_fechavencimpugnacion ¿fvencimpugnacion?, 
				lpj.pj_impugnacion ¿impugnacion?, 
				(	SELECT   count(*) cont
					  FROM   legales.lpa_periciaarchivoasociado lpa
					 WHERE   lpa.pa_idpericias = lpj.pj_id
						 AND lpa.pa_fechabaja IS NULL
						 ) ¿cantidad?,
				lpj.pj_id ¿ADJUNTOS?,
				lpj.pj_id ¿EDITAR?, 
				lpj.pj_id ¿ELIMINAR?
				
		   FROM legales.lpj_peritajejuicio lpj, legales.ltp_tipopericia ltp 
		  WHERE lpj.pj_idtipopericia = ltp.tp_id
			AND lpj.pj_fechabaja IS NULL 
			AND lpj.pj_idjuicioentramite =:NroJuicio ) ";
  
  return $strqry;
  
}
