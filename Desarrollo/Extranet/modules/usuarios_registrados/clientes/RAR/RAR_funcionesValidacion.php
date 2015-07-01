<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid_columnAjax.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/gridAjax.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/SeleccionarEstablecimiento.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/FuncionesEstablecimientos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/CargaESOP.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/NominaPersonalExpuesto.Grid.php");
//
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CommonFunctions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");
//............\Common\miscellaneous\date_utils.php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");

function Obtener_ID_TABLA($IDRELEVAMIENTO, $annoACTUAL){
	try{		
		global $conn;
		
		$params = array(":IDRELEVAMIENTO" => $IDRELEVAMIENTO);		
		
		$sql = ObtenerSQL_ID_TABLA($annoACTUAL);	
		
		$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){					
			return $row;
		}				
		return array("FECHA"=>'', "IDRELEV"=>'', "TABLA"=>''); 
	}
	catch (Exception $e) {		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		throw new Exception("Error ".$e->getMessage() ); 
	}
}

function ObtenerSQL_ID_TABLA($annoACTUAL){
	
	$menosUno = ' '.ANNOANTERIOR.' ';
	if( $annoACTUAL == 'ACTUAL' ) 	$menosUno = '  ';		
	
	$ReturnSQL = " SELECT   fecha, idrelev, tabla
				  FROM   (SELECT   r.sr_fecha fecha, r.sr_id idrelev, 'ART.PSR_SINRIESGO' tabla
							FROM   art.psr_sinriesgo r
						   WHERE   TO_CHAR (r.sr_fecha, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
							   AND NOT EXISTS (SELECT   cn_fecharelevamiento fecha, cn_id idrelev
												 FROM   hys.hcn_cabeceranomina
												WHERE   cn_id = :IDRELEVAMIENTO
													AND cn_idestado NOT IN (3, 6)
													AND TO_CHAR (cn_fecharelevamiento, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
													AND sr_fecha < cn_fecharelevamiento)
						  UNION
						  SELECT   c.cn_fecharelevamiento fecha, c.cn_id idrelev, 'HYS.HCN_CABECERANOMINA' tabla
							FROM   hys.hcn_cabeceranomina c
						   WHERE   c.cn_idestado NOT IN (3, 6)
							   AND TO_CHAR (c.cn_fecharelevamiento, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
							   AND NOT EXISTS (SELECT   sr_fecha fecha, sr_id idrelev
												 FROM   art.psr_sinriesgo
												WHERE   sr_id = :IDRELEVAMIENTO
													AND TO_CHAR (sr_fecha, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
													AND sr_fecha > cn_fecharelevamiento)
													
						   UNION 
                            SELECT   ew_fechaalta fecha, ew_id idrelev, 'HYS.HEW_ESTABLECIMIENTOWEB' tabla
                              FROM   hys.hew_establecimientoweb                               
                                  where ew_id = :IDRELEVAMIENTO
                                  AND TO_CHAR (ew_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy')                                          
                                  )													
				 WHERE   idrelev = :IDRELEVAMIENTO   
				 AND ROWNUM = 1
				 ";
	
	return $ReturnSQL;
}

function ObtenerSQL_AnnoAnteriorActual($annoACTUAL){
	/* Listado Año Actual / Año Anterior */
	$menosUno = ' '.ANNOANTERIOR.' ';
	if( $annoACTUAL == 'ACTUAL' ) 	$menosUno = '  ';		

	$ReturnSQL = " SELECT  distinct  1 ID, 
					d.RT_CUIL CUIL,
					d.rt_nombre NOMBRE,
					TO_CHAR( rl_fechaingreso, 'DD-MM-YYYY')   FECHAINGRESO,
					TO_CHAR( decode( nvl(dw_fechainiexpo, ''), '', rt_fechainiexpo, dw_fechainiexpo) , 'DD-MM-YYYY') FECHAINIEXPO,
					rl_sector SECTOR,
					
					CASE WHEN 
						NVL(    (SELECT MAX(DW_ID)
								  FROM   hys.hdw_detallenominaweb
								 WHERE   dw_cuil = rt_cuil
									 AND TO_CHAR (dw_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
									 AND dw_fechabaja IS NULL), 0) = 0
					 THEN  rl_tarea
					 ELSE
						(SELECT   MAX (pn_descripcion)
						FROM   hys.hdw_detallenominaweb
						inner join HYS.HPN_PUESTONOMINA on  DW_PUESTOTRAB = pn_id
					   WHERE   dw_puestotrab = (SELECT   dw_puestotrab
												 FROM   hys.hdw_detallenominaweb
												WHERE   dw_cuil = rt_cuil
													AND dw_fechabaja IS NULL
													and rownum = 1))  
					END PUESTO,		
													
					(SELECT LISTAGG (rg_esop || ' ' || rg_sufijoesop, ',') WITHIN GROUP (ORDER BY rg_esop, rg_sufijoesop)
						  FROM art.prg_riesgos, art.prt_riestrab
						 WHERE     rg_id = rt_idrg
							   AND rg_fechabaja IS NULL
							   AND RT_IDCABECERANOMINA = C.CN_ID
							   AND RT_CUIL = D.RT_CUIL
					) LISTAESOP

						FROM  hys.hcn_cabeceranomina c
				  INNER JOIN  prt_riestrab d  ON c.cn_id = d.rt_idcabeceranomina
				  INNER JOIN  ctj_trabajador t  ON t.tj_cuil = rt_cuil
				  INNER JOIN  crl_relacionlaboral r  ON t.tj_id = r.rl_idtrabajador
				  LEFT JOIN  hys.hdw_detallenominaweb hdw on hdw.dw_cuil = rt_cuil AND TO_CHAR (dw_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
				  
				 WHERE  cn_id = :IDCABECERA
					 AND c.cn_idestado NOT IN (3, 6)
					 AND TO_CHAR (c.cn_fecharelevamiento, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
					 
					 AND NOT EXISTS (SELECT   sr_fecha fecha, sr_id idrelev
									   FROM   art.psr_sinriesgo
									  WHERE   sr_cuit = c.cn_cuit
										  AND sr_estableci = c.cn_estableci
										  AND TO_CHAR (sr_fecha, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') ".$menosUno."
										  AND sr_fecha > cn_fecharelevamiento)												
									ORDER BY   rt_nombre ";

	$ReturnSQL = ReemplazaCorchetesQRY($ReturnSQL);
	
	return $ReturnSQL;
						 
}

function ObtenerDatosNominaWeb($buscaNombre = '', $buscaCuil = '', $conOrderBy = true){

	$ReturnSQL = " SELECT   
					HDW.DW_ID [ID], 
					HDW.DW_CUIL [CUIL], 
					HDW.DW_NOMBRE [NOMBRE], 
					TO_CHAR( HDW.DW_FECHAINGRESO, 'DD/MM/YYYY') [FECING],  
					TO_CHAR( HDW.DW_FECHAINIEXPO, 'DD/MM/YYYY') [FECINI], 
					HDW.DW_SECTORTRAB [SECTOR], 
					HPN.PN_DESCRIPCION [PUESTO], 
				 (   SELECT LISTAGG( DECODE (PRG1.rg_sufijoesop, '', PRG1.rg_esop, PRG1.rg_esop || ' ' || PRG1.rg_sufijoesop) , ', ' ) WITHIN GROUP (ORDER BY PRG1.RG_ESOP )
						FROM HYS.hrt_riestrabweb HRT1 
						INNER JOIN ART.prg_riesgos PRG1 ON PRG1.RG_ID = HRT1.RT_IDRIESGO
						WHERE HRT1.RT_IDDETALLENOMINA = HDW.DW_ID         
					) [LISTAESOP]
					
				  FROM           hys.hdw_detallenominaweb HDW
					INNER JOIN hys.hcw_cabeceranominaweb hcw ON hcw.cw_id = hdw.dw_idcabeceranomina
					LEFT JOIN hys.hpn_puestonomina hpn ON hdw.dw_puestotrab = hpn.pn_id
					INNER JOIN hys.hew_establecimientoweb hew ON hew.ew_id = hcw.cw_idestablecimientoweb
					INNER JOIN afi.aem_empresa aem ON aem.em_cuit = hew.ew_cuit
				 WHERE  HCW.CW_IDESTABLECIMIENTOWEB = :IDCABECERANOMINA ";
	
	if($buscaNombre != '') $ReturnSQL .= " AND upper(HDW.DW_NOMBRE)  LIKE upper(:buscaNombre)";		
	if($buscaCuil != '')  $ReturnSQL .= " AND HDW.DW_CUIL  = :buscaCuil";		
	
	if($conOrderBy) $ReturnSQL .= " ORDER BY HDW.DW_NOMBRE ";
	
	$ReturnSQL = ReemplazaCorchetesQRY($ReturnSQL);
	
	return $ReturnSQL;
}

function ValidarFechas($fechaIngreso, $fechaInicio){
	// $fechaIngreso = formatDateSeparador("d/m/Y", $fechaIngreso, '-' );
	// $fechaInicio = formatDateSeparador("d/m/Y", $fechaInicio, '-' );		
	
	// date_format($fechaInicio,"d/m/Y");	
	if( !isFechaValida($fechaIngreso) ) return 'Fecha Ingreso Invalida '.$fechaIngreso;
	if( !isFechaValida($fechaInicio) ) return 'Fecha Inicio Invalida '.$fechaInicio;
	
	$dias = dateDiff($fechaIngreso, $fechaInicio);
	if($dias < 0) return 'Fecha Inicio de la exposicion, Debe ser mayor/igual a la fecha de Ingreso a la empresa. ';
	
	$hoy = date("d/m/Y");
	$dias = dateDiff($fechaInicio, $hoy);
	if($dias < 0) return 'Fecha Inicio de la exposicion, Debe ser menor/igual a la fecha actual. ';
	
	return '';
	
}

function Confirma_NominaWeb($IDESTABLECIWEB){
	/*Esta funcion valida y confirma la nomina web 
		se van a validar todos los registros de la nomina que esten completos para el envio
		se va a generar un nuevo numero de version y se actualiza la fecha de versionado
	*/
	$resultadoText = '';
	try{
		global $conn;
		
		$params = array(":IDCABECERANOMINA" => $IDESTABLECIWEB );								
		
		$sql = ObtenerDatosNominaWeb('', '', false);		
		$sql = ReemplazaCaracterStr($sql, '?', '' );
		$sql = ReemplazaCaracterStr($sql, '¿', '' );
		
		$stmt = DBExecSql($conn, $sql, $params);	
		$contador = 0;		
		
		if (DBGetRecordCount($stmt) == 0) {
			return utf8_encode("Nómina vacia.");			
		}
		
		
		while ($row = DBGetQuery($stmt)){		
			$faltantes = '';
			foreach($row as $key=>$value){					
				if( !isset($value) ) $value = '';
				if($value == '') {
					if($faltantes != '') $faltantes .= ', ';
					
					switch ($key) {
						case "NOMBRE":
							$faltantes .= 'Nombre ';
							break;
						case "FECING":
							$faltantes .= "Fecha ingreso ";
							break;
						case "FECINI":
							$faltantes .= "Fecha inicio";
							break;
						case "SECTOR":
							$faltantes .= "Sector ";
							break;
						case "PUESTO":
							$faltantes .= "Puesto ";
							break;
						case "LISTAESOP":
							$faltantes .= "ESOP ";
							break;
					}
					 // $faltantes .= $key;			
				}				
			}
			
			
			if($faltantes != ''){
				$contador ++;	 					
				$resultadoText .= " CUIL ".$row['CUIL'].". Completar: ".$faltantes." <p>";
			}else{
				$fechaIngreso = $row['FECING']; 
				$fechaInicio = $row['FECINI']; 
				$resultFecha = ValidarFechas($fechaIngreso, $fechaInicio);
				if(  $resultFecha != '' ){
					$contador ++;	 					
					$resultadoText .= " CUIL ".$row['CUIL'].". Error: ".$resultFecha." <p>";
				}
			}

		}		
		
		if($resultadoText != '')
			return $resultadoText." Total registros incompletos ".$contador;
		else{
			GrabarEstadoNomina($IDESTABLECIWEB, 'L', false, $conn);
			DBCommit($conn);						
		}
				
		return $resultadoText;
		
	}catch (Exception $e){			
		DBRollback($conn);		
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		RetronaXML($e->getMessage());						
	}
	
}


function qryTiene_Riesgo_AgentesQuimicos(){
/*[Grupo Agentes Químicos] ? Con cualquier ESOP que se cargue perteneciente a este grupo, el sistema permitirá el procesamiento de la nómina, pero saldrá el siguiente mensaje informativo:*/

	$ReturnSQL = " SELECT   NVL (COUNT ( * ), 0) riesgos
					  FROM  hys.hrt_riestrabweb hrt1
					  INNER JOIN art.prg_riesgos prg1 ON prg1.rg_id = hrt1.rt_idriesgo
					  INNER JOIN hys.hdw_detallenominaweb hdw ON hdw.dw_id = hrt1.rt_iddetallenomina
					  INNER JOIN hys.hcw_cabeceranominaweb hcw ON hcw.cw_id = hdw.dw_idcabeceranomina
					  INNER JOIN hys.hew_establecimientoweb hew ON hew.ew_id = hcw.cw_idestablecimientoweb
					  
					 WHERE hew.EW_ID = :IDESTABLECIMIENTOWEB					 					 			 
					 AND hrt1.rt_idriesgo IN (SELECT   rg_id
													FROM hys.hdr_detalleriesgoesop
													INNER JOIN   art.prg_riesgos  r ON dr_riesgoesop = DECODE (rg_sufijoesop, '', r.rg_esop, r.rg_esop || ' ' || rg_sufijoesop)
													where upper(dr_grupo) = 'Q'
													AND rg_fechabaja IS NULL) ";
	return $ReturnSQL;
}

function qryTiene_RiesgoCancerigenos(){
	/*[Grupo Cancerígenos] ? Con cualquier ESOP que se cargue perteneciente al grupo cancerígeno, el sistema permitirá el procesamiento de la nómina, pero saldrá el siguiente mensaje informativo:*/
	$ReturnSQL = "SELECT   NVL (COUNT ( * ), 0) riesgos
				  FROM           hys.hrt_riestrabweb hrt1
				  INNER JOIN art.prg_riesgos prg1 ON prg1.rg_id = hrt1.rt_idriesgo
				  INNER JOIN hys.hdw_detallenominaweb hdw ON hdw.dw_id = hrt1.rt_iddetallenomina
				  INNER JOIN hys.hcw_cabeceranominaweb hcw ON hcw.cw_id = hdw.dw_idcabeceranomina
				  INNER JOIN hys.hew_establecimientoweb hew ON hew.ew_id = hcw.cw_idestablecimientoweb
				 
				 WHERE   hew.EW_ID = :IDESTABLECIMIENTOWEB					 					 		 
					 AND hrt1.rt_idriesgo IN (SELECT   rg_id
												FROM   prg_riesgos
											   WHERE   rg_cancerigeno = 'S'
												   AND rg_fechabaja IS NULL) ";
	return $ReturnSQL;
}

function qryTiene_ESOP_NoRelacionados(){
/*[ESOP no recomendados para la actividad] ? Cada CIIU tiene por defecto relacionado códigos ESOP, pero el sistema igualmente permite la selección de cualquier ESOP. Si existiera alguno fuera de esa relación, el sistema, al momento de grabar, mostrará el siguiente cartel informativo:*/

	$ReturnSQL = "SELECT   nvl(count(*), 0) riesgos
				  FROM       hys.hrt_riestrabweb hrt1
				  INNER JOIN  art.prg_riesgos prg1  ON prg1.rg_id = hrt1.rt_idriesgo
				  INNER JOIN  hys.hdw_detallenominaweb hdw  ON hdw.dw_id = hrt1.rt_iddetallenomina
				  inner join  hys.hcw_cabeceranominaweb hcw on hcw.cw_id = hdw.dw_idcabeceranomina
				  inner join  hys.hew_establecimientoweb hew on hew.ew_id = hcw.CW_IDESTABLECIMIENTOWEB
				  
				 WHERE   hew.EW_ID = :IDESTABLECIMIENTOWEB					 					 
				 AND HRT1.RT_IDRIESGO not in (
						  SELECT   r.rg_id
							FROM     hys.hec_esopporciiu ec
						  INNER JOIN   prg_riesgos r ON ec.ec_idesop = r.rg_id
						   WHERE   ec_idactividad =    hew.ew_idactividad
							   AND r.rg_fechabaja IS NULL
							   AND ec.ec_fechabaja IS NULL 
							   ) ";
	return $ReturnSQL;
}

function qryTiene_ESOPCodigo(){
	/* [Declaración de ESOP 60005 - Mycobacterium Tuberculosis] ? Permite el procesamiento de la nómina, pero saldrá el siguiente mensaje informativo */
	/* [ESOP 80004 GR - Posiciones forzadas y gestos repetitivos en el trabajo I (extremidad SUPERIOR)] ? El sistema permitirá el procesamiento de la nómina, pero saldrá el siguiente mensaje informativo: */
	/*	[Se declaró ESOP  80006 - Sobrecarga del uso de la voz  / Y el puesto declarado, no es Docente al frente de un aula, con 18 o más horas cátedra (ó 13,5 reales) de dedicación semanal./Operador de Call Center/Telefonista] ? El sistema no procesará la nómina, volverá a la pantalla de carga de puestos para que el usuario cambie el mismo. */
	/*	[Grupo Bifenilos Policlorados] ? Con cualquier ESOP que se cargue perteneciente a este grupo, el sistema permitirá el procesamiento de la nómina, pero saldrá el siguiente mensaje informativo:  (40043)*/
	
	$ReturnSQL = "SELECT   NVL (COUNT ( * ), 0) riesgos
					  FROM   hys.hrt_riestrabweb hrt1
					  INNER JOIN art.prg_riesgos prg1 ON prg1.rg_id = hrt1.rt_idriesgo
					  INNER JOIN hys.hdw_detallenominaweb hdw ON hdw.dw_id = hrt1.rt_iddetallenomina
					  INNER JOIN hys.hcw_cabeceranominaweb hcw ON hcw.cw_id = hdw.dw_idcabeceranomina					  
					 WHERE   hcw.CW_IDESTABLECIMIENTOWEB = :IDESTABLECIMIENTOWEB					 					 
					 AND   hrt1.rt_idriesgo IN (SELECT   r.rg_id
														FROM   prg_riesgos r
													   WHERE   UPPER (rg_esop || ' ' || rg_sufijoesop) LIKE UPPER (:IDESOP) ) ";
	
	return $ReturnSQL;
}

function Questions_ConfirmaNominaV1($IDESTABLECIMIENTOWEB){
	
	try{
		global $conn;
		
		$arrayResultado = array();			
		
		$params = array(":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB );										
		$sql = qryTiene_ESOP_NoRelacionados();		
		$resultCount = ValorSql($sql, "", $params);				
		$arrayResultado['ESOPNORELACIONADOS']  = $resultCount;
		
		$params = array(":IDESOP" => '%60005%', ":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB  );										
		$sql = qryTiene_ESOPCodigo();	
		$resultCount = ValorSql($sql, "", $params);				
		$arrayResultado['ESOP60005']  = $resultCount;
		
		$params = array(":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB );										
		$sql =  qryTiene_RiesgoCancerigenos();		
		$resultCount = ValorSql($sql, "", $params);				
		$arrayResultado['RIESGOCANCERIGENO']  = $resultCount;
	
		$params = array(":IDESOP" => '%40043%', ":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB  );										
		$sql = qryTiene_ESOPCodigo();	
		$resultCount = ValorSql($sql, "", $params);						
		$arrayResultado['BIFENILOSPOLICLORADOS']  = 1;
		
		$params = array(":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB );										
		$sql = qryTiene_Riesgo_AgentesQuimicos();		
		$resultCount = ValorSql($sql, "", $params);				
		$arrayResultado['AGENTESQUIMICOS']  = $resultCount;
		
		$params = array(":IDESOP" => '%80004 GR%', ":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB  );										
		$sql = qryTiene_ESOPCodigo();		
		$resultCount = ValorSql($sql, "", $params);				
		$arrayResultado['ESOP80004GR']  = $resultCount;
		
		$params = array(":IDESOP" => '%80006%', ":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB  );										
		$sql = qryTiene_ESOPCodigo();		
		$resultCount = ValorSql($sql, "", $params);				
		$arrayResultado['ESOP80006']  = $resultCount;
		
		return $arrayResultado;		
				
	}catch (Exception $e){								
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		return false;
	}
}

function GetIDEstablecimientoCurrentYear($IDESTABLECIMIENTOWEB, $CUITEMPRESA){
	try{
		$params = array(":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB, ":CUITEMPRESA" => $CUITEMPRESA );										
		
		$sql =  "SELECT   max(nvl(ew_id, 0)) id
				  FROM   hys.hew_establecimientoweb
				 WHERE   ew_estableci = :IDESTABLECIMIENTOWEB
					 AND ew_cuit = :CUITEMPRESA
					 AND TO_CHAR (ew_fechaalta, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy')";
					 
		$ID = ValorSql($sql, "", $params);				
		return $ID; 
	
	}catch (Exception $e){						
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		return false;
	}		
}

function GetLastVersionEstableci($IDESTABLECIMIENTOWEB){
	try{
		$params = array(":IDESTABLECIMIENTOWEB" => $IDESTABLECIMIENTOWEB);										
		
		$sql =  "SELECT   MAX (NVL (ew_versionnomina, 0)) id
				  FROM   hys.hew_establecimientoweb
				 WHERE   ew_id = :IDESTABLECIMIENTOWEB";
					 
		$ID = ValorSql($sql, "", $params);				
		return $ID; 
	
	}catch (Exception $e){						
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		return false;
	}		
}

function GrabarEstadoNomina($IDESTABLECIMIENTOWEB, $ESTADONOMINA,  $conectar = false, $conn = null){
	/*
	$ESTADONOMINA VALORES:
		Null: no está generada
		C: está generada y puede continuar editándose. No puede ser importada por hys.
		L: está generada. Cuando se edita pasa a estado ‘C’ nuevamente. En estado ‘L’ puede ser importada por hys. 
		A: Aprobada. No puede editarse. No se puede generar otra nómina en el año vía web.
		R: Rechazada: Mostrará la leyenda ‘RECHAZADA’ (tipo link) que al hacer sobre el mismo abrirá un cartel con el motivo de rechazo y la observación. Si la nómina del año actual está rechazada, el usuario podrá cargar una nueva nómina (no editar la rechazada), por lo que el ícono de ‘Columna Nueva Presentación’ volverá al estado ‘No generada’ (Null)
	*/
	try{	
		if( $conectar ){			
			global $conn;
		}
		
		$sqlParamVersion =  " ";
		
		$params = array(":ID" => $IDESTABLECIMIENTOWEB,						
						":ESTADONOMINA" => $ESTADONOMINA);

		if($ESTADONOMINA == 'L'){
			$VERSIONNOMINA = (GetLastVersionEstableci($IDESTABLECIMIENTOWEB) + 1);
			$params[":VERSIONNOMINA"] = $VERSIONNOMINA;
			$sqlParamVersion .= "  EW_VERSIONNOMINA = :VERSIONNOMINA,  ";
			$sqlParamVersion .= "  EW_FECHAIMPRESIONNOMINA = SYSDATE,  ";
		}else{
			$sqlParamVersion .= "  EW_FECHAIMPRESIONNOMINA = '',  ";
		}
		
		$sql = "UPDATE HYS.HEW_ESTABLECIMIENTOWEB			
				SET
					".$sqlParamVersion."
					EW_ESTADO = :ESTADONOMINA 					
					WHERE EW_ID = :ID ";		
			
		
			DBExecSql($conn, $sql, $params);	
		if( $conectar ){
			DBCommit($conn);						
		}
		
		return true; 
	}
	catch (Exception $e) {		
		DBRollback($conn);				
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );		
		throw new Exception("Error ".$e->getMessage() ); 
	}
}

function Nomina_AnnoAnteriorData($idrelevamiento){
	/* --Para ver si es con nómina con o sin riesgo: */
	$params = array(":IDRELEVAMIENTO" => $idrelevamiento);
	$sql = " SELECT   FECHA, IDRELEV, TABLA
			  FROM   (SELECT   r.sr_fecha fecha, r.sr_id idrelev, 'ART.PSR_SINRIESGO' tabla
						FROM   art.psr_sinriesgo r
					   WHERE   TO_CHAR (r.sr_fecha, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') - 1
						   AND NOT EXISTS (SELECT   cn_fecharelevamiento fecha, cn_id idrelev
											 FROM   hys.hcn_cabeceranomina
											WHERE   cn_id = :IDRELEVAMIENTO                                                  
												AND cn_idestado NOT IN (3, 6)
												AND TO_CHAR (cn_fecharelevamiento, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') - 1
												AND sr_fecha < cn_fecharelevamiento)
					  UNION
					  --con riesgo
					  SELECT   c.cn_fecharelevamiento fecha, c.cn_id idrelev, 'HYS.HCN_CABECERANOMINA' tabla
						FROM   hys.hcn_cabeceranomina c
					   WHERE   c.cn_idestado NOT IN (3, 6)
						   AND TO_CHAR (c.cn_fecharelevamiento, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') - 1
						   AND NOT EXISTS (SELECT   sr_fecha fecha, sr_id idrelev
											 FROM   art.psr_sinriesgo
											WHERE   sr_id = :IDRELEVAMIENTO                                                  
												AND TO_CHAR (sr_fecha, 'yyyy') = TO_CHAR (SYSDATE, 'yyyy') - 1
												AND sr_fecha > cn_fecharelevamiento))
			 WHERE   idrelev = :IDRELEVAMIENTO;   ";
			 
			
	try{
		global $conn;
		
		$stmt = DBExecSql($conn, $sql, $params);	
		
		while ($row = DBGetQuery($stmt)){		
			return $row;		
		}
		
		$row = array( "FECHA" => '', "IDRELEV" => '0', "TABLA" => '' );
					
		return $row;		
		
	}catch (Exception $e){				
		RetronaXML($e->getMessage());						
	}
				 
}
//---------------------------------------------------------------------------
function ValidarMsjESOPRestrictivo($IDCABECERANOMINA){
	try{	
		global $conn;	
		$sqlValida = "SELECT   DISTINCT art.hys_rarweb.get_valorrestrictivo (dw_idcabeceranomina)
						  FROM     hys.hdw_detallenominaweb d
						INNER JOIN hys.hcw_cabeceranominaweb f ON d.dw_idcabeceranomina = f.cw_id
						 WHERE   f.cw_idestablecimientoweb = :IDCABECERANOMINA";
						 
		$params = array( ":IDCABECERANOMINA" => $IDCABECERANOMINA );
		$RESTRICTESOP = ValorSql($sqlValida, "", $params);		
		
		$resultMsj = '';
		
		if($RESTRICTESOP != ''){
			$sqlMsj = "select ART.HYS_RARWEB.get_mensaje(:RESTRICTESOP, 'S', NULL) from dual";
			$params = array( ":RESTRICTESOP" => $RESTRICTESOP );
			$resultMsj = ValorSql($sqlMsj, "", $params);		
		}
		
		return utf8_encode($resultMsj);
		
	}catch (Exception $e){			
		return "<b style='color:red;' >fallo ValidarMsjESOPRestrictivo: </b>".$e->getMessage();
	}
}

function ValidarMsjESOP($IDCABECERANOMINA){
	try{
		global $conn;
		
		$sqlValida = " SELECT 'ESOP_' || VALOR ESOP,  HYS_RARWEB.get_mensaje(VALOR, 'N', null) MSJ
						FROM (
						SELECT distinct E.DR_GRUPO VALOR
						  FROM HYS.HDW_DETALLENOMINAWEB D
							   INNER JOIN  hys.hcw_cabeceranominaweb f  ON d.dw_idcabeceranomina = f.cw_id
							   INNER JOIN HYS.HRT_RIESTRABWEB R ON D.DW_ID = R.RT_IDDETALLENOMINA
							   INNER JOIN ART.PRG_RIESGOS P ON P.RG_ID = R.RT_IDRIESGO
							   INNER JOIN HYS.HDR_DETALLERIESGOESOP E
								  ON E.DR_RIESGOESOP =
										DECODE (RG_SUFIJOESOP,
												'', RG_ESOP,
												RG_ESOP || ' ' || RG_SUFIJOESOP)
						WHERE   f.cw_idestablecimientoweb = :IDCABECERANOMINA
							   AND D.DW_FECHABAJA IS NULL
							   AND R.RT_FECHABAJA IS NULL
							   AND E.DR_FECHABAJA IS NULL
							   AND P.RG_FECHABAJA IS NULL
							   
						UNION ALL
						
						SELECT DISTINCT
							   DECODE (P.RG_SUFIJOESOP,
										'', P.RG_ESOP,
										P.RG_ESOP || ' ' || P.RG_SUFIJOESOP)
								  VALOR
						  FROM HYS.HDW_DETALLENOMINAWEB D
							   INNER JOIN  hys.hcw_cabeceranominaweb f  ON d.dw_idcabeceranomina = f.cw_id
							   INNER JOIN HYS.HRT_RIESTRABWEB R ON D.DW_ID = R.RT_IDDETALLENOMINA
							   INNER JOIN ART.PRG_RIESGOS P ON P.RG_ID = R.RT_IDRIESGO
						WHERE   f.cw_idestablecimientoweb = :IDCABECERANOMINA
							   AND D.DW_FECHABAJA IS NULL
							   AND R.RT_FECHABAJA IS NULL
							   AND P.RG_FECHABAJA IS NULL )

						WHERE  HYS_RARWEB.get_mensaje(VALOR, 'N', null) IS NOT NULL ";
						
		$params = array(":IDCABECERANOMINA" => $IDCABECERANOMINA );
		
		$stmt = DBExecSql($conn, $sqlValida, $params);
				
		$rowsEsop = array();
		while ($row = DBGetQuery($stmt)){				
			$rowsEsop[$row['ESOP']] = utf8_encode($row['MSJ']);
		}
		
		return $rowsEsop;
	
	}catch (Exception $e){			
		return "<b style='color:red;' >fallo ValidarMsjESOP: </b>".$e->getMessage();
	}
}

function Questions_ConfirmaNomina($IDESTABLECIMIENTOWEB){

	try{					
		$arrayResultado = array();			
		
		$arrayResultado['RESTRICTIVO'] = ValidarMsjESOPRestrictivo($IDESTABLECIMIENTOWEB);
		
		if($arrayResultado['RESTRICTIVO'] == ''){
			$arrayResultado = ValidarMsjESOP($IDESTABLECIMIENTOWEB);
		}
				
		return $arrayResultado;		
				
	}catch (Exception $e){								
		SalvarErrorTxt( __FILE__, __FUNCTION__ , __LINE__,  $e->getMessage() );
		return false;
	}
}