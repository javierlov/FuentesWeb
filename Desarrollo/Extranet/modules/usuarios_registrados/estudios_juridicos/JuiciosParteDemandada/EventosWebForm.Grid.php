<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");

@session_start(); 

function getGridEventos($NroJuicio) {	

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
	
	$params[":NroJuicio"] = $NroJuicio;	
	$sql = ObtenerEventos();
	
	$grilla = new GridDos(10, 10);
		
	$grilla->addColumn(new Column("Evento", 200, true, false, -1, "", "", "", -1, false, -1));	
	$grilla->addColumn(new Column("Fecha", 80, true, false, -1, "", "", "", -1, false, -1));	
	$grilla->addColumn(new Column("Fecha Venc.", 80, true, false, -1, "", "", "", -1, false, -1));	
	
	//-------------  cantidad de adjuntos		
	$columnCantidad = new ColumnDos("Cant. Adjuntos", 25, true, false, -1, "", "", "", -1, false, -1,"Cantidad Adjuntos");
	$columnCantidad->SetStyleCssRow("text-align:center;");
	$grilla->addColumn($columnCantidad);	
	//-------------  cantidad de adjuntos		
		
	$visible = true;
	$TituloColumnViewEdit = 'Mod';
	$MsjColumnViewEdit = 'Modifica';
	$visibleElim = true;
	$visibleAdj = true;
	$btnClass = 'btnEditItem';
/*
	if( $_SESSION["JUICIOTERMINADO"] ){
		
		if( strtoupper($_SESSION["usuario"]) != "GESTIONINTERNA" ){
			$visibleAdj = false;
		}
		
		$visibleElim = false;
		$TituloColumnViewEdit = 'Ver';
		$MsjColumnViewEdit = 'Ver Item';
		$btnClass = 'btnLink';
	}	
*/	
	//-------------- Nuevo boton Adjuntos
	$btnClassAdj = 'btnCarpeta';
	$TituloColumnAdj = 'Ver Adjuntos';
	$columnaAdj = new Column($TituloColumnAdj, 40, $visibleAdj, false, -1, $btnClassAdj, "/index.php?pageid=134", "", -1, true, -1, $TituloColumnAdj);
	$grilla->addColumn($columnaAdj);
	//-------------- Nuevo boton Adjuntos
	
	
	//CAMBIO PAG 106=113 -->
	$grilla->addColumn(new Column($TituloColumnViewEdit, 40, $visible, false, -1, $btnClass, "/index.php?pageid=113", "", -1, true, -1, $MsjColumnViewEdit));

	//----------- Boton Elimina item
	//CAMBIO PAG 101=108 -->
	$pathDelete ="/index.php?pageid=108&DELETE";	
	$pathDelete .= "&usuario=".$_SESSION["usuario"];

	$columnElim = new ColumnDos("Elim", 40, $visibleElim, false, -1, "btnDeleteItem", $pathDelete, "", -1, true, -1, "Elimina");
	
	$columnElim->SetStyleCssRow("text-align:center;");
	$columnElim->SetIDdivHeader("idElim");		
	$columnElim->SetOnClickFunction("EliminarEvento");		
	$grilla->addColumn($columnElim);			
	//----------- Boton Elimina item
	

	//-------------  	txt_grid_center
	
	$grilla->DefaultConfiguration();
	$grilla->setColsSeparator(false);	
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
		
	return $grilla->Draw(false);
}

function ObtenerEventos(){ 
				
  $strqry= " select   ¿evento?, 
				¿et_fechaevento?,
				¿et_fechavencimiento?, 
				¿cantidad?,
				¿et_id1?,
				¿et_id2?,
				¿et_id3?

			From ( SELECT 
				ev.te_descripcion evento, 
				ejt.et_fechaevento et_fechaevento,
				ejt.et_fechavencimiento et_fechavencimiento, 

				( SELECT   COUNT(*) 
					  FROM   legales.lea_eventoarchivoasociado lea
					 WHERE   lea.ea_ideventojuicioentramite = ejt.et_id
						 AND lea.ea_fechabaja IS NULL ) cantidad,
						 
				ejt.et_id  et_id1,
				ejt.et_id  et_id2,
				ejt.et_id  et_id3
				  
			 FROM legales.let_eventojuicioentramite ejt, legales.lte_tipoevento ev 
			WHERE ejt.et_idtipoevento = ev.te_id 
			  AND ejt.et_fechabaja IS NULL 
			  AND te_visibleweb = 'S' 
			  AND ejt.et_idjuicioentramite =:NroJuicio 
			  ORDER BY et_fechaevento DESC)	";
/*  
  $strqry= " select * from LEGALES.LTE_TIPOEVENTO where TE_VISIBLEWEB = 'S' and TE_FECHABAJA is null;";
*/
	
  return $strqry;
  
}
