<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

//ObtenerChequeDetalle
function paramIdCheque($defaulValue){
	if (isset($_REQUEST["idCheque"]))
		$idCheque = $_REQUEST["idCheque"];
	else
		$idCheque = $defaulValue;
	
	return $idCheque;
}

function getGridChequeDetalle() {
	
	$Contrato = $_SESSION["idUsuario"];	
	$idCheque = paramIdCheque("0");
	
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;

	$params = array();
	$where = "";
		
	$sql = ObtenerChequeDetalle($idCheque);
	
	$grilla = new GridDos(10, 10);
		
	$grilla->addColumn(new Column("Tipo", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("Número ", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("Conpago", 0, true, false, -1, "", "", "", -1, false));	
	$grilla->addColumn(new Column("Demandante", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("Demandado", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("NroFactura", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("Liquidacion", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("Importe", 0, true, false, -1, "", "", "alineaIzq", -1, false));
	
	$grilla->DefaultConfiguration();			
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setSql($sql);	
		    	
	return $grilla->Draw(false);
}

function  SumaImporteChequeDetalle(){
	  
  $sql= " SELECT  TO_CHAR( NVL(SUM(importe), 0) , '".DB_FORMATMONEY."') importe
		FROM 
		(
		SELECT  SUM (NVL(pl_importepago, 0) + NVL(pl_importeconretencion, 0) )  importe 						
       FROM art.scp_conpago, legales.lbo_abogado, legales.ljt_juicioentramite, 
            legales.lpl_pagolegal 
      WHERE jt_id = pl_idjuicioentramite 
        AND bo_id = jt_idabogado 
        AND pl_conpago = cp_conpago 
        AND pl_idchequeemitido = :idCheque
		
     UNION 
	 
     SELECT  SUM( NVL(pm_importepago, 0) + NVL(pm_importeconretencion, 0)  ) importe			
       FROM art.scp_conpago, legales.lbo_abogado, legales.lme_mediacion, 
            legales.lpm_pagomediacion 
      WHERE me_id = pm_idmediacion 
        AND bo_id = me_idabogado 
        AND pm_conpago = cp_conpago 
        AND pm_idchequeemitido = :idCheque
		)
		";
	
	$idCheque = paramIdCheque("0");
	$params = array(":idCheque" => $idCheque);
	
	$sumaImportes = ValorSql($sql, "0", $params);		
	
	return $sumaImportes;  
}

function  ObtenerChequeDetalle($idCheque) {  
	//TO_CHAR( NVL(pl_importepago, 0) + NVL(pl_importeconretencion, 0) , '".DB_FORMATMONEY."') ¿importe?   
	$strqry= "SELECT  
			tipo ¿tipo?, 
			numero ¿numero?, 
			conpago ¿conpago?, 
            demandante ¿demandante?, 
			demandado ¿demandado?, 
			NroFactura ¿NroFactura?,            			
            Liquidacion ¿Liquidacion?,
			TO_CHAR( importe, '".DB_FORMATMONEY."')  ¿importe? 
			FROM (
				SELECT  
					'JUICIO' tipo, 
					jt_numerocarpeta numero, 
					cp_denpago conpago, 
					jt_demandante demandante, 
					jt_demandado demandado, 
					pl_letrafactura || '-' || pl_situacionfactura || '-' || pl_numerofactura NroFactura,            			
					pl_idliquidacion Liquidacion,
					NVL(pl_importepago, 0) + NVL(pl_importeconretencion, 0)  importe,
					pl_fechaalta fecha					
			
				   FROM art.scp_conpago, legales.lbo_abogado, legales.ljt_juicioentramite, 
						legales.lpl_pagolegal 
				  WHERE jt_id = pl_idjuicioentramite 
					AND bo_id = jt_idabogado 
					AND pl_conpago = cp_conpago 
					AND pl_idchequeemitido = $idCheque
					
				 UNION 
				 
				 SELECT 
					'MEDIACION' tipo, 
					me_numerofolio numero, 
					cp_denpago conpago, 
					me_demandante demandante, 
					me_demandado demandado, 
					pm_letrafactura || '-' || pm_situacionfactura || '-' || pm_numerofactura NroFactura,            			
					pm_idliquidacion Liquidacion,
					NVL(pm_importepago, 0) + NVL(pm_importeconretencion, 0) importe,
					pm_fechaalta fecha
						
				   FROM art.scp_conpago, legales.lbo_abogado, legales.lme_mediacion, 
						legales.lpm_pagomediacion 
				  WHERE me_id = pm_idmediacion 
					AND bo_id = me_idabogado 
					AND pm_conpago = cp_conpago 
					AND pm_idchequeemitido = $idCheque
			)
			where importe != 0
			order by Liquidacion, fecha asc ";
	
	return $strqry;  
}

?>