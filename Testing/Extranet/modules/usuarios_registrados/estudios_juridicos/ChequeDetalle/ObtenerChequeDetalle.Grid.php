<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");

//ObtenerChequeDetalle

function getGrid() {
	
	$Contrato = $_SESSION["idUsuario"];
	
	$idCheque = "0";
	if (isset($_REQUEST["idCheque"]))
		$idCheque = $_REQUEST["idCheque"];
	
	//echo "<br> cont ".$Contrato."<br> che ".$idCheque."<br>";
	
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;

	$params = array();
	$where = "";
		
	$sql = ObtenerChequeDetalle($idCheque);
	
	$grilla = new GridDos(10, 15);
		
	$grilla->addColumn(new Column("tipo", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("numero", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("conpago", 0, true, false, -1, "", "", "", -1, false));	
	$grilla->addColumn(new Column("demandante", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("demandado", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("importe", 0, true, false, -1, "", "", "", -1, false));
		
	$grilla->setColsSeparator(false);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setRowsSeparatorColor("#c0c0c0");	
	$grilla->setShowTotalRegistros(true);	
	$grilla->setSql($sql);	
	$grilla->setUseTmpIframe(true);	
	return $grilla->Draw(false);
}

function  ObtenerChequeDetalle($idCheque) 
{  
  
  $strqry= "SELECT  'JUICIO' ¿tipo?, jt_numerocarpeta ¿numero?, cp_denpago ¿conpago?, 
            jt_demandante ¿demandante?, jt_demandado ¿demandado?, 
            NVL(pl_importepago, 0) + NVL(pl_importeconretencion, 0) ¿importe? 
       FROM art.scp_conpago, legales.lbo_abogado, legales.ljt_juicioentramite, 
            legales.lpl_pagolegal 
      WHERE jt_id = pl_idjuicioentramite 
        AND bo_id = jt_idabogado 
        AND pl_conpago = cp_conpago 
        AND pl_idchequeemitido = ($idCheque)
     UNION 
     SELECT 'MEDIACION' ¿tipo?, me_numerofolio ¿numero?, cp_denpago ¿conpago?, 
            me_demandante ¿demandante?, me_demandado ¿demandado?, 
            NVL(pm_importepago, 0) + NVL(pm_importeconretencion, 0) ¿importe?
       FROM art.scp_conpago, legales.lbo_abogado, legales.lme_mediacion, 
            legales.lpm_pagomediacion 
      WHERE me_id = pm_idmediacion 
        AND bo_id = me_idabogado 
        AND pm_conpago = cp_conpago 
        AND pm_idchequeemitido = $idCheque";

  return $strqry;  
}

?>