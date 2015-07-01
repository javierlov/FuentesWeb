<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

// R:\Testing\Extranet\modules\usuarios_registrados\estudios_juridicos\ChequeDetalle\ChequesDisponible.php
function getGridChequesDisponible() {

	$Contrato =  $_SESSION["usuario"];
		
	$ob = "2";
	if (isset($_REQUEST["ob"]))	$ob = $_REQUEST["ob"];
		
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))	$pagina = $_REQUEST["pagina"];

	$showProcessMsg = false;
	$params = array();
	$where = "";

	$sql = ObtenerChequesDisponible($Contrato);
	
	$grilla = new GridDos(10, 10);
	
	//CAMBIO PAG 96=103
	$grilla->addColumn(new Column("", 0, true, false, -1, "btnBuscarItem", "/index.php?pageid=103", "", -1, true, -1, "Mostrar Detalle"));	
	$grilla->addColumn(new Column("Fecha", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("Cheque", 0, true, false, -1, "", "", "", -1, false));
	$grilla->addColumn(new Column("Importe", 0, true, false, -1, "", "", "alineaIzq", -1, false));	
	$grilla->addColumn(new Column("Beneficiario", 0, true, false, -1, "", "", "", -1, false));	
	
	$pdfCertificado = "/modules/usuarios_registrados/estudios_juridicos/redirect.php?CertificadoRetencionReport";		
	$grilla->addColumn(new Column("CR", 0, true, false, -1, "btnPdf", $pdfCertificado, "", -1, true, -1, "Certificado Retencion"));	
	
	$pdfOrdenPago = "/modules/usuarios_registrados/estudios_juridicos/redirect.php?OrdenPagoReport";
	$grilla->addColumn(new Column("OP", 0, true, false, -1, "btnPdf", $pdfOrdenPago, "", -1, true, -1, "Orden Pago"));	
	
	$grilla->DefaultConfiguration();		
	
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);	
	$grilla->setSql($sql);	
	///////METODOS NUEVOS///////	
	$grilla->setNameParamGET("idCheque");	
	$grilla->addColOpenExtWindows("CR");	
	$grilla->addColOpenExtWindows("OP");	
		
	return $grilla->Draw(false);
}

function ObtenerChequesDisponible($Abogado){	
	//----------------------------------------------------------------------			
	$vSql = "SELECT NU_IDABOGADO
			  FROM legales.lnu_nivelusuario
			  WHERE nu_usuario = UPPER(:Abogado)";
		
	$params = array(":Abogado" => $Abogado);
	
	$idAbogado = ValorSql($vSql, "0", $params);		
	//----------------------------------------------------------------------			
	$vSql = "SELECT NU_USUARIOGENERICO
			 FROM legales.lnu_nivelusuario
  			 WHERE nu_usuario = UPPER(:Abogado)";
	
	$params = array(":Abogado" => $Abogado);
	$generico = ValorSql($vSql, 'X', $params);		
	//----------------------------------------------------------------------	
//Modificacion filtro AND ce_situacion IN ('01', '14', '19', '20', '21')=  VER MAIL RV: Validaciones de datos. (Montero, Melina <mmontero@provart.com.ar> martes 12/08/2014 11:22)	
        $strqry = "SELECT CE_ID ¿IDCHEQUE?, 
					CE_FECHACHEQUE ¿FECHA?, 
					CE_NUMERO ¿CHEQUE?, 
										
					TO_CHAR(CE_MONTO, '".DB_FORMATMONEY."') ¿IMPORTE?,      
					
					CE_BENEFICIARIO ¿BENEFICIARIO?,
					CE_ORDENPAGO ¿CR?,
					CE_ORDENPAGO ¿OP?
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
								AND ce_situacion IN ('01', '14', '19', '20', '21')
								AND ce_cuenta IS NULL  
								AND ce_debitado = 'N' 
								AND CE_MONTO != 0
								ORDER BY 1, 2";	 
	//----------------------------------------------------------------------			
	return $strqry;		
}
