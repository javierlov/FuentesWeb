<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/pdf/fpdf/fpdf_js.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");


	try{
		
		if (!isset($_REQUEST["op"])) {
			echo "La consulta no devolvió datos.";
			exit;
		}

		SetDateFormatOracle("DD/MM/YYYY");

		define("MAX_LINEAS_POR_HOJA", 10);		
		
 //$_REQUEST["op"] = 4856;

		$_REQUEST["op"] = intval($_REQUEST["op"]);
		$paramsMain = array(":op" => $_REQUEST["op"]);
		$sqlMain = " SELECT   CH.CE_ID CHECK_ID,
						 CH.CE_ORDENPAGO OP,
						 CH.CE_FECHAOP FECHA_OP,
						 CH.CE_BENEFICIARIO BENEFICIARIO,
						 RE.CE_NUMERO CHEQUEREEMP,
						 RE.CE_FECHACHEQUE FECHA_CHEQUE,
						 'IMPUTACION' IMPUTACION,
						 'REEMPLAZO DE CHEQUE' DESCRIPCION,
						 TO_CHAR(RE.CE_MONTO, '".DB_FORMATMONEY."')   MONTOREEMP,
						 CH.CE_METODOPAGO DESCRIPTION,
						 BA.BA_NOMBRE BANK_NAME,
						 CTA.CB_NUMERO BANK_ACCOUNT_NUM,
						 CH.CE_NUMERO CHEQUE,
						 TO_CHAR(CH.CE_MONTO, '".DB_FORMATMONEY."')  MONTO,
						 CH.CE_MONTO  MONTOSUM,
						 RE.CE_ORDENPAGO OPAGO
				  FROM   rce_chequeemitido ch,
						 rce_chequeemitido re,
						 zcb_cuentabancaria cta,
						 zba_banco ba
				 WHERE   ch.ce_idchequereemp = re.ce_id
					 AND ch.ce_idcuentabancaria = cta.cb_id
					 AND cta.cb_idbanco = ba.ba_id
					 AND ch.ce_ordenpago = :op ";
				
		$stmt = DBExecSql($conn, $sqlMain, $paramsMain);
		$rowCabecera = DBGetQuery($stmt, 1, false);
//--------------------------------------------------------------------------------------------
			$sql= "SELECT   RC_IDSUCPROVEEDOR
					  FROM   rrc_reemplazocheque
					 WHERE   rc_id = :op";
					 
			$IDSUCPROVEEDOR = valorSql($sql, "", $paramsMain);	
			
			$sql = "SELECT   PV.VENDOR_NAME,
							 PVS.ADDRESS_LINE1,
							 ADDRESS_LINE2,
							 CITY,
							 STATE,
							 ZIP
					  FROM   po_vendors@REALFCL pv, po_vendor_sites_all@REALFCL pvs
					 WHERE   pvs.vendor_id = pv.vendor_id
						 AND pvs.vendor_site_id = :IDSUCPROVEEDOR";
			
			$params = array(":IDSUCPROVEEDOR" => $IDSUCPROVEEDOR );				 
			$stmt = DBExecSql($conn, $sql, $params);
			$rowCabecera2 = DBGetQuery($stmt, 1, false);	

//--------------------------------------------------------------------------------------------
			$sql= "SELECT   varios.get_occurs2 ('|', tc_camposbusqueda) + 1 TC_CAMPOSBUSQUEDA
					  FROM   rtc_tipoclave, rta_tipoarchivo
					 WHERE   ta_formulario = tc_clave
						 AND ta_id = 8";
						 
			$params = array();				 			 
			$CAMPOSBUSQUEDA = valorSql($sql, "", $params);
			
			$params = array(":op" => $_REQUEST["op"]);	

			$sql= " SELECT   MIN (da_secuenciatrazabil) DA_SECUENCIATRAZABIL
						FROM   archivo.rar_archivo, archivo.rda_detallearchivo, archivo.rtd_tipodocumento
					   WHERE   ar_tipo = 8
						   AND ar_clave = :op
						   AND da_idarchivo = ar_id
						   AND da_fechabaja IS NULL
						   AND da_idtipodocumento = td_id
						   AND da_secuenciatrazabil IS NOT NULL
						   AND td_codigo = 'OPT'
					GROUP BY   da_secuenciatrazabil
					  HAVING   COUNT ( * ) = 1";
					  
			$SECUENCIATRAZABIL = valorSql($sql, "", $params);
			
			$sql= "SELECT   AR_TIPO
					  FROM   archivo.rda_detallearchivo, archivo.rar_archivo
					 WHERE   da_idarchivo = ar_id
						 AND da_secuenciatrazabil = :SECUENCIATRAZABIL";
						 
			$params = array(":SECUENCIATRAZABIL" => $SECUENCIATRAZABIL);				 			 
			$arTIPO = valorSql($sql, "", $params);
			
			$params = array(":arTIPO" => $arTIPO);				 			 
			$ISUSARSECUENCIATRAZABILIDAD = valorSql("SELECT ART.ARCHIVO.IS_ISUSARSECUENCIATRAZABILIDAD(:arTIPO) FROM DUAL", "", $params);
			
//--------------------------------------------------------------------------------------------
				$sql = " SELECT   TRIM (ar_clave) ADIC1, 
								  TRIM (ta_codigo) || ' - ' || TRIM (td_codigo) ADIC2
						  FROM   rtd_tipodocumento,
								 rta_tipoarchivo,
								 rar_archivo,
								 rda_detallearchivo
						 WHERE   da_idarchivo = ar_id
							 AND ar_tipo = ta_id
							 AND da_idtipodocumento = td_id
							 AND da_secuenciatrazabil = :SECUENCIATRAZABIL ";
			
			$params = array(":SECUENCIATRAZABIL" => $SECUENCIATRAZABIL );				 
			$stmt = DBExecSql($conn, $sql, $params);
			$rowCabecera3 = DBGetQuery($stmt, 1, false);	
			
			$params = array(":op" => $_REQUEST["op"] );				 
			$CODBARRASCLAVE = valorSql("SELECT ARCHIVO.GET_CODBARRASCLAVE('OPT', :op ,'','','') FROM DUAL", "", $params);

//-------------------------------------------------------------------------------------------- 
//--------------------------------------------------------------------------------------------
		
		$pdf = new FPDI();
		$pdf->setSourceFile($_SERVER["DOCUMENT_ROOT"]."/modules/varios/tesoreria/templates/reporte_reemplazo.pdf");


		$pdf->AddPage();
		$tplIdx = $pdf->importPage(1);
		$pdf->SetAuthor('ART');
		$pdf->useTemplate($tplIdx);

		// Dibujo la cabecera..
		$pdf->SetFont("Arial", "", 16);
		$pdf->Text(75, 15, $rowCabecera["OP"]);

		$pdf->SetFont("Arial", "", 11);
		$pdf->Text(40, 21, $rowCabecera["BENEFICIARIO"]);

		$pdf->SetFont("Arial", "", 7);
		$pdf->Text(32, 34, $rowCabecera2["VENDOR_NAME"]);
//---------------------------------------------------------------------------				
		$pdf->Text(32, 38, $rowCabecera2["ADDRESS_LINE1"]);
		$pdf->Text(32, 41, $rowCabecera2["ADDRESS_LINE2"]);
		$pdf->Text(32, 44, $rowCabecera2["CITY"]);
		$pdf->Text(32, 47, $rowCabecera2["STATE"]);				
		$pdf->Text(33, 57, $rowCabecera2["ZIP"]);
//---------------------------------------------------------------------------				
		$pdf->Text(146, 47, $rowCabecera["FECHA_OP"]);
		$pdf->Text(146, 53, 'PES');				
//---------------------------------------------------------------------------
		$pdf->Text(20, 73, $rowCabecera["CHEQUEREEMP"]);
		$pdf->Text(58, 73, $rowCabecera["FECHA_CHEQUE"]);
		$pdf->Text(83, 73, $rowCabecera["OPAGO"]);
		$pdf->Text(105, 73, $rowCabecera["DESCRIPCION"]);
		$pdf->Text(170, 73, $rowCabecera["MONTOREEMP"]);
//---------------------------------------------------------------------------				
		$i = 1;
		$netoAPagar = 0;
		$posX = 0;
		
		$stmt = DBExecSql($conn, $sqlMain, $paramsMain);
		while ($row = DBGetQuery($stmt, 1, false)) {
			if ($i == 1 && $netoAPagar > 0) {	$pdf->AddPage();}
			
			$posX = (93 + ($i*4));
			$pdf->SetXY(20, $posX);			
			$pdf->Cell(20, 0, $row["DESCRIPTION"], 0, 0, 'L' );
			
			$pdf->SetX(40);			
			$pdf->Cell(50, 0, $row["BANK_NAME"], 0, 0, 'L' );
			
			$pdf->SetX(88);			
			$pdf->Cell(20, 0, $row["BANK_ACCOUNT_NUM"], 0, 0, 'L' );
			
			$pdf->SetX(120);			
			$pdf->Cell(20, 0, $row["CHEQUE"], 0, 0, 'L' );
			
			$pdf->SetX(146);			
			$pdf->Cell(20, 0, $row["FECHA_OP"], 0, 0, 'L' );
			
			$pdf->SetX(170);			
			$pdf->Cell(20, 0, $row["MONTO"], 0, 0, 'R' );
			
			$i++;
			if ($i > MAX_LINEAS_POR_HOJA) {
				$i = 1;
			}
			
			$netoAPagar += $row["MONTOSUM"];
		}
//---------------------------------------------------------------------------								
		$pdf->SetXY(10, 168);
		$pdf->Cell(60, 0, $_SESSION["usuario"], 0, 0, 'C' );

		$pdf->SetFont("Arial", "B", 8);	
		
		$pdf->SetXY(130, $posX+8);		
		$pdf->Cell(60, 0, "Neto a Pagar:  $".$netoAPagar, 0, 0, 'R' );
		
		$pdf->Output();
	}catch (Exception $e){				
		echo "ERROR: ".$e->getMessage();		
	}	
?>