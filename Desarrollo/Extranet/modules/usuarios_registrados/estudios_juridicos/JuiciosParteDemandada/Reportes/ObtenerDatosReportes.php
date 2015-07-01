<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

@session_start(); 


function ObtenerSQL_ReporteResumenSiniestro(){
	$sqlSubSelect = " SELECT                     
					ex_id
				  FROM   sex_expedientes,
						 aem_empresa,
						 aco_contrato,
						 ctj_trabajador,
						 DUAL
				 WHERE   ex_recaida = 0
					 AND ex_orden = 1
					 AND ex_siniestro = :id
					 AND ex_contrato = co_contrato
					 AND ex_cuit = em_cuit
					 AND ex_cuil = tj_cuil
					 AND co_idempresa = em_id";
					 
	/* Reporte Resumen Siniestro */
	$ReturnSQL = " SELECT   EX_ID,
						(SELECT   MAX (EX_RECAIDA) + 1
								FROM   SEX_EXPEDIENTES SEX_1
							   WHERE   SEX_1.EX_SINIESTRO = SEX.EX_SINIESTRO
								   AND SEX_1.EX_ORDEN = SEX.EX_ORDEN)				   AS    OCURRENCIAS   ,
						 ART.SIN.GET_LISTANROSMEDIACIONESEXP (EX_ID) AS    MEDIACIONES   ,
						 /* SINIESTRO CON MÚLTIPLES ORDENES */
						 (SELECT   DISTINCT NVL ('X', '')
							FROM   SEX_EXPEDIENTES SEX_2
						   WHERE   SEX_2.EX_SINIESTRO = SEX.EX_SINIESTRO
							   AND SEX_2.EX_ORDEN > 1)
						   AS    HAY_MULTIPLES   ,
						 /* SINIESTRO CON MÚLTIPLES RECAÍDAS */
						 (SELECT   DISTINCT NVL ('X', '')
							FROM   SEX_EXPEDIENTES SEX_3
						   WHERE   SEX_3.EX_SINIESTRO = SEX.EX_SINIESTRO
							   AND SEX_3.EX_ORDEN = SEX.EX_ORDEN
							   AND SEX_3.EX_RECAIDA > 0)
						   AS    HAY_RECAIDAS   ,                                                                               /* ADICIONALES  */
						 /* SINIESTRO CON PLURIEMPLEO */
						 (SELECT   DISTINCT NVL ('X', '')
							FROM   SEX_EXPEDIENTES SEX_4
						   WHERE   SEX_4.EX_SINIESTRO = SEX.EX_SINIESTRO
							   AND SEX_4.EX_ORDEN = SEX.EX_ORDEN
							   AND SEX_4.EX_RECAIDA = SEX.EX_RECAIDA
							   AND SEX_4.EX_PLURIEMPLEO = 'S')
						   AS    ES_PLURIEMPLEO   ,
						 ART.ACTUALDATE    HOY   ,
						 SE_DESCRIPCION AS    ESTADO   ,
						 TA_DESCRIPCION AS    TIPO   ,
						 TG_DESCRIPCION AS    GRAVEDAD   ,
						 EX_FECHAACCIDENTE    EX_FECHAACCIDENTE   ,
						 NVL (EX_HORAACCIDENTE, '-') AS    EX_HORAACCIDENTE   ,
						 EX_BAJAMEDICA    EX_BAJAMEDICA   ,
						 ART.UTILES.ARMAR_SINIESTRO (EX_SINIESTRO, EX_ORDEN, EX_RECAIDA)    SINIESTRO   ,
						 EX_CUIT    EX_CUIT   ,
						 EX_CUIL    EX_CUIL   ,
						 EX_BREVEDESCRIPCION    EX_BREVEDESCRIPCION   ,
						 EX_OBSERVACIONES    EX_OBSERVACIONES   ,
						 ART.SIN.GET_LISTANROSJUICIOEXP (EX_SINIESTRO, EX_ORDEN, EX_RECAIDA)    JUICIO   ,
						 CT_DESCRIPCION    CAUSAFIN   ,
						 /* EMPRESA */
						 UTILES.ARMAR_DOMICILIO (MP_CALLE_POST,
												 MP_NUMERO_POST,
												 MP_PISO_POST,
												 MP_DEPARTAMENTO_POST)
						      EMPRESA_DOMICILIO   ,
						 MP_NOMBRE    EMPRESA_NOMBRE   ,
						 MP_TELEFONOS    EMPRESA_TELEFONOS   ,
						 MP_LOCALIDAD_POST    EMPRESA_LOCALIDAD   ,
						 MP_CPOSTAL_POST    EMPRESA_CPOSTAL   ,
						 CPV_CMP.PV_DESCRIPCION    EMPRESA_PROVINCIA   ,
						 /* TRABAJADOR */
						 UTILES.ARMAR_DOMICILIO (TJ_CALLE,
												 TJ_NUMERO,
												 TJ_PISO,
												 TJ_DEPARTAMENTO)
						      TRABAJADOR_DOMICILIO   ,
						 TJ_NOMBRE    TJ_NOMBRE   ,
						 ART.TRABAJADOR.GET_TELEFONO (TJ_ID, 4)    TELEFONO   ,
						 TJ_LOCALIDAD    TJ_LOCALIDAD   ,
						 TJ_CPOSTAL    TJ_CPOSTAL   ,
						 CPV_CTJ.PV_DESCRIPCION    TRABAJADOR_PROVINCIA   ,
						 /* DENUNCIA */
						 UTILES.ARMAR_DOMICILIO (UD_CALLE,
												 UD_NUMERO,
												 UD_PISO,
												 UD_DEPARTAMENTO)
						      DENUNCIA_DOMICILIO   ,
						 UD_NOMBRE    DENUNCIA_NOMBRE   ,
						 UD_CPOSTAL    DENUNCIA_CPOSTAL   ,
						 UD_CPOSTALA    DENUNCIA_CPOSTALA   ,
						 UD_LOCALIDAD    DENUNCIA_LOCALIDAD   ,
						 UTILES.ARMAR_TELEFONO (UD_CODAREATELEFONOS, NULL, UD_TELEFONOS)    DENUNCIA_TELEFONOS   ,
						 CPV_SUD.PV_DESCRIPCION    DENUNCIA_PROVINCIA   ,
						 /* PRESTADOR */
						 UTILES.ARMAR_DOMICILIO (CPR.CA_CALLE,
												 CPR.CA_NUMERO,
												 CPR.CA_PISO,
												 CPR.CA_DEPARTAMENTO)
						      PRESTADOR_DOMICILIO   ,
						 CPR.CA_DESCRIPCION    PRESTADOR_NOMBRE   ,
						 CPR.CA_TELEFONO    PRESTADOR_TELEFONO   ,                                                                /* PARTES */
						 PI_DIASBAJAPREVISTOS    PI_DIASBAJAPREVISTOS   ,
						 PI_DIAGNOSTICO    PI_DIAGNOSTICO   ,
						 EX_IDDIAGNOSTICO    EX_IDDIAGNOSTICO   ,
						 PE_DIASBAJATOTALES    PE_DIASBAJATOTALES   ,
						 PE_DIAGNOSTICO    PE_DIAGNOSTICO   ,
						 EX_ALTAMEDICA    EX_ALTAMEDICA   ,
						 LF_DESCRIPCION    FORMA   ,
						 LA_DESCRIPCION    AGENTE   ,
						 LN_DESCRIPCION    NATURALEZA   ,
						 LZ_DESCRIPCION    ZONA   ,
						 EX_BREVEDESCRIPCION    EX_BREVEDESCRIPCION   ,
						 EX_OBSERVACIONES    EX_OBSERVACIONES   ,
						 EX_DIAGNOSTICOOMS    EX_DIAGNOSTICOOMS   ,
						 EX_PRESTADOR    EX_PRESTADOR   ,
						 PA_FECHAAUDITORIA    PA_FECHACONTROL   ,
						 PA_CALIDADPREST    CALIDADAUDITORIA   ,
						 PA_MEDICO    PA_MEDICO   ,
						 EX_PLURIEMPLEO    EX_PLURIEMPLEO   ,
						 CDG.DG_DESCRIPCION    DG_DESCRIPCION   ,
						 DG_INCAPACIDADLEVE    DG_INCAPACIDADLEVE   ,
						 DG_INCAPACIDADMODERADO    DG_INCAPACIDADMODERADO   ,
						 DG_INCAPACIDADGRAVE    DG_INCAPACIDADGRAVE   ,
						 DG_DIASLEVE    DG_DIASLEVE   ,
						 DG_DIASMODERADO    DG_DIASMODERADO   ,
						 DG_DIASGRAVE    DG_DIASGRAVE   ,
						 DECODE (EX_ALTAMEDICA,
								 NULL, PE_DIASBAJATOTALES,
								 DECODE (EX_BAJAMEDICA,
										 NULL, PE_DIASBAJATOTALES,
										 IIF_COMPARA ('>=',
													  TO_CHAR (EX_ALTAMEDICA, 'YYYYMMDD'),
													  TO_CHAR (EX_BAJAMEDICA, 'YYYYMMDD'),
													  TRUNC (EX_ALTAMEDICA - EX_BAJAMEDICA + 1),
													  PE_DIASBAJATOTALES)))
						      DIASBAJA   ,
						 /* INCAPACIDADES */
						 DECODE (SI_GRADO, NULL, DECODE (SI_CARACTER, NULL, 'NO', 'SI'), 'SI')    EXISTEINCAPACIDAD   ,
						 DECODE (SI_GRADO, 'P', 'PARCIAL', DECODE (SI_GRADO, 'T', 'TOTAL', '-'))    GRADO   ,
						 DECODE (SI_CARACTER, 'P', 'PROVISORIO', DECODE (SI_CARACTER, 'D', 'DEFINITIVO', '-'))    CARACTER   ,
						 DECODE (SI_GRANINC, 'S', 'SI', 'NO')    GRANINVALIDEZ   ,
						 DECODE (SINIESTRO.GET_FECHA_HOMOLOGADO (EX_ID), NULL, 'NO', 'SI')    HOMOLOGADO   ,
						 SINIESTRO.GET_FECHA_HOMOLOGADO (EX_ID)    FECHAHOMOLOGADO   ,
						 SI_PORCPROVI    SI_PORCPROVI   ,
						 SI_PORCDEF    SI_PORCDEF   ,
						 DECODE (EX_PRESUPINCAPACIDAD, '   ', ' ', EX_PRESUPINCAPACIDAD)    PRESUPINCAP   ,
						 /* COBRANZAS */
						 ART.COMPDEUDA.GET_CUOTAPROMEDIO (AFILIACION.GET_CONTRATOVIGENTE (EX_CUIT, EX_FECHAACCIDENTE),
														  'E',
														  UTILES.PERIODO_ANTERIOR (COBRANZA.GET_ULTPERIODODEVENGADO ('E'), 12),
														  COBRANZA.GET_ULTPERIODODEVENGADO ('E'),
														  'S')
						      CUOTAPROMEDIO   ,
						 ART.COMPDEUDA.GET_DEUDA (AFILIACION.GET_CONTRATOVIGENTE (EX_CUIT, EX_FECHAACCIDENTE),
												  'D',
												  'S',
												  'N')
						      DEUDA   ,
						 DECODE (ART.AFILIACION.CHECK_COBERTURA (EX_CUIT),
								 1                                                                      /*ART.AFILIACION.ESTADO_ACTIVA*/
								  ,
								 ART.COMPDEUDA.GET_CUOTAPROMEDIO (AFILIACION.GET_CONTRATOVIGENTE (EX_CUIT, EX_FECHAACCIDENTE),
																  'E',
																  UTILES.PERIODO_ANTERIOR (COBRANZA.GET_ULTPERIODODEVENGADO ('E'), 12),
																  COBRANZA.GET_ULTPERIODODEVENGADO ('E'),
																  'S'),
								 0)
						      DEUDAADMITIDA   ,
						 /* DOCUMENTACIÓN */
						 DECODE (EX_FECHARECEPCION, NULL, 'NO', 'SI')    DOC_EXPEDIENTE   ,
						 DECODE (DE_FECHARECEPCION, NULL, 'NO', 'SI')    DOC_DENUNCIA   ,
						 DECODE (PI_FECHARECEPCION, NULL, 'NO', 'SI')    DOC_INGRESO   ,
						 DECODE (PE_FECHARECEPCION, NULL, 'NO', 'SI')    DOC_EGRESO   ,
						 DECODE (SA_FECHARECEPCION, NULL, 'NO', 'SI')    DOC_OTROS   ,
						 DECODE (DG_FECHARECEPCION, NULL, 'NO', 'SI')    DOC_DENUNCIAGRAVE   ,
						 DECODE (PV_FECHARECEPCION, NULL, 'NO', 'SI')    DOC_EVOLUTIVO  
						 
				  FROM   sex_expedientes sex,
						 art.cdg_diagnostico cdg,
						 art.cpr_prestador cpr,
						 cmp_empresas,
						 ctj_trabajador,
						 SIN.sta_tipoaccidente,
						 SIN.sse_siniestroestado,
						 SIN.sct_causaterminacion,
						 cpv_provincias cpv_cmp,
						 cpv_provincias cpv_ctj,
						 cpv_provincias cpv_sud,
						 SIN.sud_ubicaciondenuncia,
						 SIN.stg_tipogravedad,
						 SIN.sla_lesionagente,
						 SIN.slf_lesionforma,
						 SIN.sln_lesionnaturaleza,
						 SIN.slz_lesionzona,
						 SIN.spi_partedeingreso,
						 SIN.spe_partedeegreso,
						 SIN.spa_partedeauditoria,
						 sin_incapacidsin,
						 SIN.sde_denuncia,
						 SIN.ssa_solicitudasistencia,
						 SIN.sdg_denunciagrave,
						 spv_parteevolutivo
						 
				 WHERE   cpv_sud.pv_codigo(+) = ud_provincia
					 AND cpv_cmp.pv_codigo(+) = mp_provincia_post
					 AND cpv_ctj.pv_codigo(+) = tj_provincia
					 AND cpr.ROWID(+) = art.SIN.get_prestadorid (ex_siniestro, ex_orden, ex_recaida)
					 AND se_codigo = ex_estado
					 AND ta_codigo(+) = ex_tipo
					 AND tg_codigo(+) = ex_gravedad
					 AND cdg.dg_codigo(+) = ex_diagnosticooms
					 AND mp_cuit = ex_cuit
					 AND tj_cuil = ex_cuil
					 AND ct_codigo(+) = ex_causafin
					 AND ud_idexpediente(+) = ex_id
					 AND pi_idexpediente(+) = ex_id
					 AND pe_idexpediente(+) = ex_id
					 AND de_idexpediente(+) = ex_id
					 AND sa_idexpediente(+) = ex_id
					 AND dg_idexpediente(+) = ex_id
					 AND la_id(+) = ex_idagente
					 AND lf_id(+) = ex_idforma
					 AND ln_id(+) = ex_idnaturaleza
					 AND lz_id(+) = ex_idzona
					 AND (dg_nroparte = (SELECT   MAX (dg_nroparte)
										   FROM   SIN.sdg_denunciagrave
										  WHERE   dg_idexpediente = ex_id)
					   OR dg_nroparte IS NULL)
					 AND pv_idexpediente(+) = ex_id
					 AND (pv_nroparte = (SELECT   MAX (pv_nroparte)
										   FROM   spv_parteevolutivo
										  WHERE   pv_idexpediente = ex_id)
					   OR pv_nroparte IS NULL)
					 AND pa_idexpediente(+) = ex_id
					 AND (pa_nroparte = (SELECT   MAX (pa_nroparte)
										   FROM   SIN.spa_partedeauditoria
										  WHERE   pa_idexpediente = ex_id)
					   OR pa_nroparte IS NULL)
					 AND si_idexpediente(+) = ex_id 
					 AND  ex_id = (".$sqlSubSelect.")";
					 
	$ReturnSQL = ReemplazaCorchetesQRY($ReturnSQL);	
	return $ReturnSQL;
}

function ObtenerSQL_ReporteLiquidaciones(){
	/* Reporte Resumen Siniestro  :EXID */
	
	$ReturnSQL = " 
			SELECT   NUMLIQUI,
					 DESCRIPCION,					 					 
					 ORIGEN,					 
					 DESDE,
					 HASTA,					 
					 IMPORTE,
					 PROCESO,
					 EMISION,
					 APROBADO
			  FROM   (/* Liquidaciones ILT/ILP */
					  SELECT   'A' AS tipo,
							   le_numliqui AS numliqui,
							   art.liq.get_tipoliquidacion (le_siniestro,
															le_orden,
															le_recaida,
															le_numliqui)
								 AS descripcion,
							   TO_CHAR (NULL) AS descripcion_extra,
							   le_imporperi AS importe,
							   le_fechades AS desde,
							   le_fechahas AS hasta,
							   le_fproceso AS proceso,
							   le_femision AS emision,
							   le_faprobado AS aprobado,
							   'P. Dinerarias' AS origen
						FROM   sle_liquiempsin, art.sex_expedientes
					   WHERE   le_siniestro = ex_siniestro
						   AND le_orden = ex_orden
						   AND le_recaida = ex_recaida
						   AND ex_id = :EXID
					  UNION ALL
					  /* Liquidaciones otros conceptos */
					  SELECT   'B' AS tipo,
							   pr_numpago AS numliqui,
							   NVL (cp_titpago, cp_denpago) || '(' || pr_conpago || ')' AS descripcion,
							   art.liq.get_acreedor (pr_acreedor,
													 pr_cuitcuil,
													 pr_prestadorsecuencia,
													 pr_prestadormutual)
								 AS descripcion_extra,
							   pr_imporpago AS importe,
							   TO_DATE (NULL) AS desde,
							   TO_DATE (NULL) AS hasta,
							   pr_fmodif AS proceso,
							   pr_fmodif AS emision,
							   pr_faprobado AS aprobado,
							   'Otros Pagos' AS origen
						FROM   spr_pagoexpesin, scp_conpago, art.sex_expedientes
					   WHERE   pr_siniestro = ex_siniestro
						   AND pr_orden = ex_orden
						   AND pr_recaida = ex_recaida
						   AND pr_conpago = cp_conpago(+)
						   AND ex_id = :EXID
					  UNION ALL
					  /* Liquidaciones prestaciones médicas */
					  SELECT   'C' AS tipo,
							   iv_numpago AS numliqui,
							   cp_denpago AS descripcion,
							   TO_CHAR (NULL) AS descripcion_extra,
							   iv_impfacturado AS importe,
							   TO_DATE (NULL) AS desde,
							   TO_DATE (NULL) AS hasta,
							   iv_fecalta AS proceso,
							   iv_fecalta AS emision,
							   vo_fechapro AS aprobado,
							   'P. Medicas' AS origen
						FROM   siv_itemvolante,
							   svo_volantes,
							   scp_conpago,
							   art.sex_expedientes
					   WHERE   iv_siniestro = ex_siniestro
						   AND iv_orden = ex_orden
						   AND iv_recaida = ex_recaida
						   AND iv_conpago = cp_conpago(+)
						   AND iv_volante = vo_volante(+)
						   AND NVL (vo_estado, ' ') IN ('E', 'EG', 'EAM', 'EM', 'EAG')
						   AND iv_estado NOT IN ('X', 'Z')
						   AND ex_id = :EXID
					  UNION ALL
					  /* Liquidaciones de legales */
					  SELECT   'D' AS tipo,
							   pl_numpago AS numliqui,
							   NVL (cp_titpago, cp_denpago) || '(' || pl_conpago || ')' AS descripcion,
							   TO_CHAR (NULL) AS descripcion_extra,
							   NVL (pl_importeconretencion, 0) + NVL (pl_importepago, 0) AS importe,
							   TO_DATE (NULL) AS desde,
							   TO_DATE (NULL) AS hasta,
							   TRUNC (NVL (pl_fechamodif, pl_fechaalta)) AS proceso,
							   pl_fechaemision AS emision,
							   TRUNC (pl_fechaaprobado) AS aprobado,
							   'Juicios' AS origen
						FROM   art.scp_conpago,
							   legales.lsj_siniestrosjuicioentramite sj,
							   legales.lod_origendemanda od,
							   legales.ljt_juicioentramite jt,
							   legales.lpl_pagolegal lpl
					   WHERE   od.od_id = sj.sj_idorigendemanda
						   AND jt.jt_id = od.od_idjuicioentramite
						   AND lpl.pl_idjuicioentramite = od.od_idjuicioentramite
						   AND pl_estado <> 'C'
						   AND sj_idsiniestro = :EXID
						   AND pl_conpago = cp_conpago
						   AND od.od_fechabaja IS NULL
						   AND sj.sj_fechabaja IS NULL
					  UNION ALL
					  /* Mediaciones */
					  SELECT   'E' AS tipo,
							   lpm.pm_idliquidacion AS numliqui,
							   NVL (cp_titpago, cp_denpago) AS descripcion,
							   TO_CHAR (NULL) AS descripcion_extra,
							   (NVL (lpm.pm_importepago, 0)) + (NVL (lpm.pm_importeconretencion, 0)) AS importe,
							   TO_DATE (NULL) AS desde,
							   TO_DATE (NULL) AS hasta,
							   TRUNC (NVL (pm_fechamodif, pm_fechaalta)) AS proceso,
							   pm_fechaemision AS emision,
							   TRUNC (pm_fechaaprobado) AS aprobado,
							   'Mediaciones' AS origen
						FROM   legales.lpm_pagomediacion lpm, art.scp_conpago, legales.lme_mediacion
					   WHERE   lpm.pm_conpago = cp_conpago
						   AND lpm.pm_estado IN ('C', 'E')
						   AND pm_idmediacion = me_id
						   AND me_idexpediente = :EXID
					  ORDER BY   tipo, desde, numliqui)
		  ";
					
	return $ReturnSQL;
						 
}

function Is_SiniestroDeGobernacion($iIdExpediente){
    try{
		global $conn;      
	
		$sql = 'SELECT art.siniestro.is_siniestrogobernacion(:idexped) FROM dual';
		$params = array(":idexped" => $iIdExpediente);    
		
		$esdegobernacion = ValorSql($sql, "", $params);
		if($esdegobernacion == 'S')
			return  'Dependencia';
		else 
			return 'Empleador';
		
    }catch (Exception $e) {
        DBRollback($conn);                		
		throw new Exception($e->getMessage());
    }     					
}

function ObtenerSQL_SeguimientoIncapacidades(){
	
	$sql = "SELECT   
		 EV_CODIGO,
         EI_DESCRIPCION EVENTO,
         EV_FECHA FECHA,
         CASE NVL( TO_CHAR(EV_PORCINCAPACIDAD), 'NULL') WHEN 'NULL' THEN '' ELSE EV_PORCINCAPACIDAD || ' %'  END PORCINCA,
         DECODE (EV_GRADO, 'P', 'PARCIAL', 'T', 'TOTAL', NULL) GRADO,
         DECODE (EV_CARACTER, 'P', 'PROVISORIO', 'D', 'DEFINITIVO', NULL) CARACTER,
         CM_DESCRIPCION COMISION,
         EV_EXPEDINCAPACIDAD,
         MI_DESCRIPCION MOTIVO,
         AU_NOMBRE MEDICO,
		 
         EV_FECHA FECHA_1,
         EV_HORA HORA,
         EX_FECHAACCIDENTE,
         EX_ALTAMEDICA
		 
  FROM   art.sev_eventosdetramite,
         art.sex_expedientes,
         SIN.sei_eventoincapacidad,
         SIN.smi_motivoincapacidad,
         SIN.scm_comisionmedica,
         mau_auditores,
         SIN.sui_ubicacionincapacidad,
         cdg_diagnostico d1,
         cdg_diagnostico d2,
         SIN.sta_tipoaccidentedictamen
 WHERE   ev_idexpediente = ex_id
     AND ev_codigo = ei_codigo
     AND ev_motivo = mi_codigo(+)
     AND ev_comision = cm_codigo(+)
     AND ev_medico = au_auditor(+)
     AND ev_codubic = ui_codigo(+)
     AND ev_idaccidentedictamen = ta_id(+)
     AND ev_idcie101 = d1.dg_id(+)
     AND ev_idcie102 = d2.dg_id(+)
     AND ev_siniestro = :psiniestro
     AND ev_orden = :porden
     AND ev_evento <> 0
     AND ev_evento > 0
	 
   ";
 
   return $sql;
}

function Encabezado_SeguimientoIncapacidades($idsiniestro, $orden){
    try{
		global $conn;      
	
		$sql = 'select distinct ev_idexpediente
					 FROM   art.sev_eventosdetramite
					where  ev_siniestro = :siniestro
						 AND ev_orden = :orden
						 AND ev_evento <> 0		 AND ev_evento > 0';
						 
		$params = ARRAY(":SINIESTRO" => $idsiniestro, ":ORDEN" => $orden);    
		
		$idexpediente = ValorSql($sql, "", $params);
		
		$sqlEncabezado = "SELECT   DISTINCT
									UTILES.ARMAR_SINIESTRO(:SINIESTRO, :ORDEN, 0) SINIESTRO,
									 TJ_ID AS ID,
									  TJ_CUIL AS CUIL,
									  TJ_NOMBRE AS NOMBRE,
									  NULL AS FECHA_BAJA,
									  INITCAP (DECODE (EX_GRAVEDAD,
													   '1',
													   'LEVE',
													   '2',
													   'MODERADO SIN INTERNACION',
													   '3',
													   'MODERADO CON INTERNACION',
													   '4',
													   'GRAVE',
													   '5',
													   'MORTAL'))
										GRAVEDAD,
									  EX_TIPO,
									  INITCAP (DECODE (EX_TIPO,
													   '1',
													   'LUGAR DE TRABAJO',
													   '2',
													   'IN ITINERE',
													   '3',
													   'ENFERMEDAD PROFESIONAL'))
										TIPO,
									  EX_FECHARECAIDA,
									  EX_FECHAACCIDENTE,
									  EX_ALTAMEDICA,
									  CO_CONTRATO AS CONTRATO,
									  EM_ID,
									  EM_CUIT,
									  EM_NOMBRE,
									  EX_ID,
									  EX_SINIESTRO,
									  EX_ORDEN,
									  EX_RECAIDA,
									  EX_CUIT
					  FROM   sex_expedientes,
							 aem_empresa,
							 aco_contrato,
							 ctj_trabajador,
							 DUAL
					 WHERE   ex_contrato = co_contrato
						 AND ex_cuit = em_cuit
						 AND ex_cuil = tj_cuil
						 AND co_idempresa = em_id
						 AND 1 = 1
						 AND NULL IS NULL
						 AND ex_id = :idexpediente";
						 
		$params[":idexpediente"] = $idexpediente;    
		
		$stmt = DBExecSql($conn, $sqlEncabezado, $params);		
		$row = DBGetQuery($stmt);
		return $row;		
		
    }catch (Exception $e) {
        DBRollback($conn);                		
		throw new Exception($e->getMessage());
    }     					
}

function ObtenerSQL_ReporteEvolutivodeSiniestro(){
	
	$sql = "SELECT   EV_SINIESTRO,
					   EV_ORDEN,
					   EV_DOCU,
					   EV_RECAIDA,
					   EV_NUMERO,
					   EV_PRESTADOR,
					   EV_ESTADO,
					   EV_DIAGNOSTICO,
					   EV_DETALLE,
					   EV_OBSERVACIONES,
					   EV_PROXIMOCONTROL,
					   EV_FECHAALTA,
					   EV_IMPORTE,
					   NVL (GP_NOMBRE, SINIESTRO.GET_USUARIOGESTOR (EX_ID)) GTRABAJOGEST,
					   EV_FORMAPAGO
				FROM   sex_expedientes, mgp_gtrabajo, mev_evolucion
			   WHERE   ex_siniestro = ev_siniestro
				   AND ex_orden = ev_orden
				   AND ex_recaida = ev_recaida
				   AND ex_gtrabajo = gp_codigo
				   AND ev_siniestro = :siniestro
				   AND ev_orden = :orden
				   AND ev_emiformu = 'S'
				   AND ev_tipo IN ('B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'A')
			ORDER BY   ev_tipo, ev_recaida, ev_numero ";
			
	return $sql;
}

function Encabezado_ReporteEvolutivodeSiniestro($idsiniestro, $orden){
    try{
		global $conn;      
		
		$sqlEncabezado = "SELECT   NVL (EX_FECHARECAIDA, EX_FECHAACCIDENTE) FECHA_ACCREC,
									 EX_BAJAMEDICA,
									 EX_ALTAMEDICA,
									 A.TB_DESCRIPCION GRAVEDAD,
									 B.TB_DESCRIPCION TIPO,
									 C.TB_DESCRIPCION ESTADO,
									 AMEBPBA.GET_CAUSAFIN (EX_CAUSAFIN) CAUSAFIN,
									 EX_DIAGNOSTICO,
									 EX_DIAGNOSTICOOMS,
									 DG_DESCRIPCION,
									 EX_CAUSAFIN,
									 AMEBPBA.ARMAR_SINIESTRO (EX_SINIESTRO, EX_ORDEN) SINIESTRO,
									 UTILES.ARMAR_CUIT (EX_CUIL) CUIL,
									 TJ_NOMBRE,
									 UTILES.ARMAR_CUIT (EX_CUIT) CUIT,
									 MP_NOMBRE,
									 EX_SINIESTRO,
									 EX_ORDEN,
									 EX_RECAIDA
							  FROM   sex_expedientes,
									 ctb_tablas a,
									 ctb_tablas b,
									 ctb_tablas c,
									 cdg_diagnostico,
									 ctj_trabajadores,
									 cmp_empresas
							 WHERE   a.tb_clave(+) = 'SGRAV'
								 AND NVL (LTRIM (ex_gravedad), 1) = a.tb_codigo
								 AND b.tb_clave(+) = 'STIPO'
								 AND NVL (LTRIM (ex_tipo), 1) = b.tb_codigo
								 AND c.tb_clave(+) = 'SIEST'
								 AND ex_estado = c.tb_codigo
								 AND ex_diagnosticooms = dg_codigo(+)
								 AND ex_cuil = tj_cuil
								 AND ex_cuit = mp_cuit
								 AND ex_siniestro = :SINIESTRO
								 AND ex_orden = :ORDEN
								 AND ex_recaida = 0";
								
		$params = ARRAY(":SINIESTRO" => $idsiniestro, ":ORDEN" => $orden);    		
		$stmt = DBExecSql($conn, $sqlEncabezado, $params);		
		$row = DBGetQuery($stmt);
		return $row;		
	
	}catch (Exception $e) {
        DBRollback($conn);                		
		throw new Exception($e->getMessage());
    }     
}

function ObtenerSQL_ReporteFichaTrabajador(){

	$sql = "SELECT   DISTINCT 
					TJ_ID AS ID,
					TJ_CUIL AS CUIL,
					TJ_NOMBRE AS NOMBRE,
					NULL AS FECHA_BAJA,
					INITCAP (DECODE (ex_gravedad,
									'1',
									'LEVE',
									'2',
									'MODERADO SIN INTERNACION',
									'3',
									'MODERADO CON INTERNACION',
									'4',
									'GRAVE',
									'5',
									'MORTAL'))
					GRAVEDAD,
					EX_TIPO,
					INITCAP (DECODE (ex_tipo,
							'1',
							'LUGAR DE TRABAJO',
							'2',
							'IN ITINERE',
							'3',
							'ENFERMEDAD PROFESIONAL'))
					TIPO,
					EX_FECHARECAIDA,
					EX_FECHAACCIDENTE,
					EX_ALTAMEDICA,
					CO_CONTRATO AS CONTRATO,
					EM_ID,
					EM_CUIT,
					EM_NOMBRE,
					EX_ID,
					EX_SINIESTRO,
					EX_ORDEN,
					EX_RECAIDA,
					EX_CUIT
		  FROM   sex_expedientes,
				 aem_empresa,
				 aco_contrato,
				 ctj_trabajador,
				 DUAL
		 WHERE   ex_contrato = co_contrato
			 AND ex_cuit = em_cuit
			 AND ex_cuil = tj_cuil
			 AND co_idempresa = em_id
			 AND 1 = 1
			 AND NULL IS NULL
			 AND ex_siniestro = :exid 
			 ";
			 
			 //923901
			 
			 return $sql; 
}

function ObtenerSQL_DetalleDatosTrabajador(){
	
	$sql = "  
	SELECT * FROM (
		SELECT   ET_MOVIMIENTO,
           MT_IDTRABAJADOR,
           MT_CUIL,
           MT_NOMBRE,
           MT_SEXO,
		   SEXOS.TB_DESCRIPCION SEXOS_DESCRIPCION,
		   
           MT_FNACIMIENTO,
           MT_ESTADOCIVIL,
		   ESTAD.TB_DESCRIPCION ESTAD_DESCRIPCION,
           
		   MT_LATERALIDADDOMINANTE,
		   LATDO.TB_DESCRIPCION LATDO_DESCRIPCION,
		   
           MT_IDNACIONALIDAD,
		   NACIONALIDAD.NA_DESCRIPCION NACIONALIDAD_DESCRIPCION,
		   
           MT_CALLE,
           MT_NUMERO,
           MT_PISO,
           MT_DEPARTAMENTO,
           MT_CPOSTAL,
           MT_CPOSTALA,
           MT_LOCALIDAD,
           PV_DESCRIPCION,
		   
		   DECODE(art.trabajador.get_telefono (Mt_idtrabajador, 4), NULL, art.trabajador.get_telefono (Mt_idtrabajador, 1), art.trabajador.get_telefono (Mt_idtrabajador, 4) )TELEFONO_TRABAJADOR,
		   
           MT_CODAREATELEFONO,
           MT_TELEFONO,
           MT_USUALTA,
           MT_FECHAALTA,
           MT_USUMODIF,
           MT_FECHAMODIF,
           MT_DOCUMENTO,
           MT_DOMICILIO,
           MT_OTRANACIONALIDAD,
           MT_EDIFICIO,
           DOMDE.TB_DESCRIPCION DOMIDEL,
           ML_IDRELACIONLABORAL,
           ML_CONTRATO,
           ME_IDESTABLECIMIENTO,
           ML_FECHAINGRESO,
           ML_FECHARECEPCION,
           ML_IDMODALIDADCONTRATACION,
           ML_TAREA,
           ML_CIUO,
           ML_SECTOR,
           ML_ULTIMANOMINA,
           ML_SUELDO,
           ML_CATEGORIA,
           ML_USUALTA,
           ML_FECHAALTA,
           ML_USUMODIF,
           ML_FECHAMODIF,
           ML_ESTADO,
           ML_PREOCUPACIONAL,
           ML_PREEXISTENTE,
           ML_IDORIGENDATO,
           EM_CUIT,
           ES_NROESTABLECI,
           ML_CONFIRMAPUESTO,
           MT_MAIL,
           RP_IDLUGARTRABAJO,
           MP_RANGOHSTRABAJA	
		   
    FROM   cet_endosotrabajador,
           cmt_movitrabajador,
           cml_movirelacionlaboral,
           cme_movirelaestablecimiento,
           aes_establecimiento,
           aco_contrato,
           aem_empresa,
           cpv_provincias,
           ctb_tablas domde,
           comunes.cmp_movirelalugartrabajo,
           comunes.crp_relalugartrabajo,
           afi.alt_lugartrabajo_pcp,
		   CTB_TABLAS SEXOS,
		   CTB_TABLAS ESTAD,
		   CTB_TABLAS LATDO,           
		   cna_nacionalidad nacionalidad
		   
   WHERE    et_idmovitrabajador = mt_id(+)
       AND et_idmovirelacionlaboral = ml_id(+)
       AND et_idmoviestablecimiento = me_id(+)
       AND me_idestablecimiento = es_id(+)
       AND et_idmovilugartrabajo = mp_id(+)
       AND mp_idrelalugartrabajo = rp_id(+)
       AND rp_idlugartrabajo = lt_id(+)
       AND et_contrato = co_contrato(+)
       AND co_idempresa = em_id(+)
       AND mt_provincia = pv_codigo(+)
       AND domde.tb_clave(+) = 'DOMDE'
       AND domde.tb_codigo(+) = mt_domiciliodel
       AND ml_contrato(+) = et_contrato
       AND et_idtrabajador = :idtrabajador
	   
        AND SEXOS.TB_CODIGO(+) = MT_SEXO
        AND SEXOS.TB_FECHABAJA(+) IS NULL
        AND SEXOS.TB_CLAVE(+) = 'SEXOS'

        AND ESTAD.TB_CODIGO(+) = MT_ESTADOCIVIL
        AND ESTAD.TB_FECHABAJA(+) IS NULL
        AND ESTAD.TB_CLAVE(+) = 'ESTAD'
        
        AND LATDO.TB_CODIGO(+) = MT_LATERALIDADDOMINANTE
        AND LATDO.TB_FECHABAJA(+) IS NULL
        AND LATDO.TB_CLAVE(+) = 'LATDO'
        
		
		AND nacionalidad.na_id(+) = MT_IDNACIONALIDAD
		AND nacionalidad.na_fechabaja IS NULL 
	          
		ORDER BY   et_contrato, et_movimiento DESC, NVL (mt_fechamodif, TO_DATE ('01/01/1996', 'dd/mm/yyyy')) DESC	
	)
	 WHERE            ROWNUM <= 1
 ";
	
	return $sql; 
}




function ObtenerSQL_DatosdelaEmpresa(){
	
	$sql = " SELECT   
					 CO_CONTRATO,
					 CO_VIGENCIADESDE,
					 CO_VIGENCIAHASTA,
					 ESTADO.TB_DESCRIPCION ESTADO,
					 CO_FECHABAJA,
					 MOTIVO.TB_DESCRIPCION MOTIVO
					 
					 
			  FROM   sex_expedientes, ctb_tablas motivo,
					 ctb_tablas estado,
					 aco_contrato,
					 aem_empresa
					 
			 WHERE   co_idempresa = em_id
				 AND co_motivobaja = motivo.tb_codigo(+)
				 AND motivo.tb_clave(+) = 'MOTIB'
				 AND co_estado = estado.tb_codigo
				 AND estado.tb_clave = 'AFEST'			
				 
				  AND ex_cuit = em_cuit
				  AND ex_siniestro = :siniestro
				  AND ex_orden = :orden
				  AND ex_recaida = 0
				  ";
								
		return $sql;		
	  
}


function ObtenerSQL_HistoricoLaboral(){
	
	$sql = "SELECT   AEM.EM_CUIT,
					   CO_CONTRATO,
					   AEM.EM_NOMBRE,
					   CHL.HL_TAREA,
					   CIU.TB_DESCRIPCION,
					   
					   CHL.HL_CATEGORIA,
					   CHL.HL_SUELDO,
					   CHL.HL_SECTOR
					   /*
					   ,
					   CHL.HL_FECHAINGRESO,
					   CHL.HL_FECHAEGRESO,
					   CHL.HL_FECHARECEPCION,
					   CHL.HL_USUALTA,
					   CHL.HL_FECHAALTA,
					   HL_BAJAEMPRESA,
					   HL_MOTIVOBAJA,
					   MOTIB.TB_DESCRIPCION MOTIVO_BAJA
					   */
				FROM   chl_historicolaboral chl,
					   aco_contrato aco,
					   aem_empresa aem,
					   ctb_tablas ciu,
					   ctb_tablas motib
			   WHERE   chl.hl_contrato = aco.co_contrato
				   AND aco.co_idempresa = aem.em_id
				   AND chl.hl_ciuo = ciu.tb_codigo(+)
				   AND ciu.tb_clave(+) = 'TAREA'
				   AND chl.hl_idtrabajador = :idtrabajador
				   AND hl_motivobaja = motib.tb_codigo(+)
				   AND motib.tb_clave(+) = 'MOTIB'
			ORDER BY   NVL (chl.hl_fechaingreso, chl.hl_fechaegreso)	";
	
	return $sql;		
}

function ObtenerSQL_DatosEmpresaTrabajador(){
	
	$sql = "   SELECT   EM_ID AS ID,
				   EM_CUIT AS CUIT,
				   EM_NOMBRE AS NOMBRE,
				   CO_CONTRATO AS CONTRATO,
				   TR_CODIGO AS CODREG,
				   CO_CONTRATO AS CONTRATOEXT,
				   CO_IDTIPOREGIMEN_ORIG AS IDTIPOREGIMEN_ORIG,
				   ART.AFILIACION.IS_EMPRESAVIP (CO_CONTRATO) AS VIP,
				   ART.AFILIACION.CHECK_COBERTURA (CO_CONTRATO) AS CHECKCOBERTURA,
				   DECODE (ART.AFILIACION.CHECK_COBERTURA (CO_CONTRATO), 1, 1, 2) AS ORDENESTADO,
				   CO_FECHABAJA AS FECHA_BAJA
			FROM   afi.atr_tiporegimen, aem_empresa, aco_contrato
		   WHERE   em_id = co_idempresa
			   AND co_idtiporegimen_orig = tr_id
			   AND co_contrato = :contrato
		ORDER BY   nombre,
				   ordenestado,
				   contrato,
				   idtiporegimen_orig";
		
		return $sql;						   
}

function ObtenerSQL_TipoContrato(){
	
	$sql = "  SELECT   MC_DESCRIPCION AS DESCRIPCION
			FROM   cmc_modalidadcontratacion
		   WHERE   mc_id = :id
			   AND mc_fechabaja IS NULL";
		
	return $sql;						   
}

function ObtenerSQL_CIUO(){
	
	$sql = " SELECT    CI_DESCRIPCION AS DESCRIPCION
				FROM   CCI_CIUO
			   WHERE   CI_CODIGO = :CODIGO
				   AND LENGTH (ci_codigo) = 4
				   AND NULL IS NULL";
		
	return $sql;						   
}

function Retorna_DatosQuery($sql, $params){
		
		global $conn;
		
		SetDateFormatOracle("DD/MM/YYYY");		
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt, 1, false);
		return $row;		
}	