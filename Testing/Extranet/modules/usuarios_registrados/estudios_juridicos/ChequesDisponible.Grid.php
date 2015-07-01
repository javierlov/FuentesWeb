<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");

function getGrid() {
	$Contrato =  $_SESSION["usuario"];
		
	$ob = "2";
	if (isset($_REQUEST["ob"]))	$ob = $_REQUEST["ob"];
		
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))	$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
	$params = array();
	$where = "";

	$sql = ObtenerChequesDisponible($Contrato);
	$grilla = new GridDos(1, 15);
		
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnLupa ", "/index.php?pageid=96", "", -1, true, -1, "Mostrar Detalle"));	
	$grilla->addColumn(new Column("fecha", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("cheque", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("importe", 0, true, false, -1, "", "", "", -1, false));	
	$grilla->addColumn(new Column("beneficiario", 0, true, false, -1, "", "", "", -1, false));	
		
	$pdfCertificado = "/PDFCertificadoRetencionReport"; // http://legales.provinciart.com.ar:8080/CertificadoRetencionReport.aspx
	$grilla->addColumn(new Column("CR", 0, true, false, -1, "btnPdf", $pdfCertificado, "", -1, true, -1, "Detalle"));	
	
	$pdfOrdenPago = "/PDFOrdenPagoReport"; //"http://legales.provinciart.com.ar:8080/OrdenPagoReport.aspx";
	$grilla->addColumn(new Column("OP", 0, true, false, -1, "btnPdf", $pdfOrdenPago, "", -1, true, -1, "Detalle"));	
	
	//$grilla->addColumn(new Column("ordenpago", 0, true, false, -1, "", "", "", -1, false));
		
	$grilla->setColsSeparator(false);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);	
	$grilla->setRowsSeparatorColor("#c0c0c0");	
	$grilla->setShowTotalRegistros(true);	
	$grilla->setSql($sql);	
	$grilla->setUseTmpIframe(false);
	$grilla->setTableStyle("GridTableCiiu");
	///////METODOS NUEVOS///////
	$grilla->setNameParamGET("idCheque");	
	$grilla->addColOpenExtWindows("CR");	
	$grilla->addColOpenExtWindows("OP");	
	
	return $grilla->Draw(false);
}

function ObtenerChequesDisponible($Abogado) 
{	
	//----------------------------------------------------------------------			
	$vSql = "SELECT NU_IDABOGADO
			  FROM legales.lnu_nivelusuario
			  WHERE nu_usuario = UPPER(:Abogado)";
		
	$params = array(":Abogado" => $Abogado);
	
	$idAbogado = ValorSql($vSql, "0", $params);		
	//----------------------------------------------------------------------			
	$vSql = "SELECT nu_usuariogenerico
			 FROM legales.lnu_nivelusuario
  			 WHERE nu_usuario = UPPER(:Abogado)";
	
	$params = array(":Abogado" => $Abogado);
	$generico = ValorSql($vSql, 'X', $params);		
	//----------------------------------------------------------------------		
	$strqry = "SELECT ce_id �idCheque?, ce_fechacheque �fecha?, 
						ce_numero �cheque?, ce_monto �importe?, 
						ce_beneficiario �beneficiario?,								
						ce_ordenpago �ordenpago?,
						ce_ordenpago �ordenpago1?
				FROM rce_chequeemitido 
				WHERE ce_id IN(SELECT pl_idchequeemitido 
                           FROM legales.ljt_juicioentramite, legales.lpl_pagolegal 
                          WHERE jt_id = pl_idjuicioentramite 
	                        AND (jt_idabogado = $idAbogado)
	                        OR 'S' = UPPER('".$generico."')
	                     UNION 
	                     SELECT pm_idchequeemitido 
	                       FROM legales.lme_mediacion, legales.lpm_pagomediacion 
	                      WHERE me_id = pm_idmediacion 
	                        AND (me_idabogado = $idAbogado)
	                        OR 'S' = UPPER('".$generico."')
							)
	        AND ce_estado = '01'
	        AND ce_situacion IN('01', '14','19')  
	        AND ce_cuenta IS NULL  
	        AND ce_debitado = 'N' 
			ORDER BY 1, 2";	 
	//----------------------------------------------------------------------			
	return $strqry;		
}
?>