<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function ObtenerDatosDeJuicio($nrojuicio,$idestudio, $usuarioweb) 
{
  //LogMessage('ObtenerDatosDeJuicio - Inicio.',EVENTLOG_INFORMATION_TYPE,0,0);
  $nro= StrToInt($nrojuicio);
  $strqry= (
    ' SELECT ju_descripcion, fu_descripcion, jz_descripcion, jz_idinstancia, sc_descripcion, '.
    '        in_descripcion, ej_descripcion, bo_nombre, jt_caratula, jt_idfuero, '.
    '        jt_idjuzgado, jt_idsecretaria, jt_deminterruptiva, jt_idjurisdiccion, '.
    '        jt_idabogado, jt_idestado, jt_fechainijuicio, jt_registracion, '.
    '        jt_idtipo, jt_fechafinjuicio, jt_resultado, nvl2(jt_nroexpediente,jt_nroexpediente || ''/''|| jt_anioexpediente,'''') jt_expediente,jt_nroexpediente, '.
    '        jt_anioexpediente, '.
    '        jt_fechaasign, jt_fechanotificacionjuicio, jt_descripcion, '.
    '        jt_fechaalta, jt_usualta, jt_fechamodif, jt_usumodif, jt_fechabaja, '.
    '        jt_usubaja, jt_fechainijuicio, $jt_id, jt_bloqueado, '.
    '        tj_descripcion AS tipojuicio, jt_numerocarpeta, jt_fechaingresoraj, '.
    '        jt_condiciondenoseguro, jt_numeroordenraj, ej_id, '.
    '           NVL (jt_demandante, '''') '.
    '        || ''C/'' '.
    '        || NVL (jt_demandado, '''') '.
    '        || '' '' '.
    '        || jt_caratula AS descripcaratula, '.
    '        (SELECT MAX (ij_id) '.
    '           FROM legales.lij_instanciajuicioentramite '.
    '          WHERE ij_idjuicioentramite = $jt_id) ij_id '.
    '   FROM legales.ljz_juzgado, '.
    '        legales.lju_jurisdiccion, '.
    '        legales.lin_instancia, '.
    '        legales.lfu_fuero, '.
    '        legales.lsc_secretaria, '.
    '        legales.ljt_juicioentramite, '.
    '        legales.lej_estadojuicio, '.
    '        legales.lbo_abogado, '.
    '        legales.ltj_tipojuicio '.
    '  WHERE jz_idfuero = fu_id '.
    '    AND jz_idjurisdiccion = ju_id '.
    '    AND jz_idinstancia = in_id '.
    '    AND jz_id = sc_idjuzgado '.
    '    AND ju_id = jt_idjurisdiccion '.
    '    AND fu_id = jt_idfuero '.
    '    AND sc_id = jt_idsecretaria '.
    '    AND jt_idestado = ej_id '.
    '    AND jt_idabogado = bo_id '.
    '    AND tj_id = jt_idtipo '.
    '    AND jt_fechabaja IS NULL '.
    '    AND (art.weblegales.pertencealestudio (jt_idabogado, '.SqlNumber($idestudio).','.
             SqlValue($usuarioweb).' ) = ''S'' )'.
    '    AND (jt_estadomediacion LIKE ''%J%'' OR jt_estadomediacion LIKE ''%A%'') '.
    '    AND jt_id = '.SqlInt($nro) );

  $sXml= CrearXml1('LJT_JUICIOENTRAMITE',$strqry);
  return $sXml;
  //LogMessage('ObtenerDatosDeJuicio - Finalizo.',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerDatosCYQ($nroorden) 
{
  //LogMessage('ObtenerDatosCYQ - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT LCQ.CQ_NROORDEN, LCQ.CQ_CUIT, CMP.MP_NOMBRE, LCQ.CQ_DEUDANOMINAL, '.
    '        LCQ.CQ_DEUDATOTAL, LCQ.CQ_FECHAVERIFICACIONCREDITO, '.
    '        LCQ.CQ_DEUDAVERIFICADA, LCQ.CQ_FECHACONCURSO as fechaconcurso, '.
    '        LCQ.CQ_FECHAQUIEBRA as fechaquiebra, CQ_FECHAASIGN, CQ_FECHAVTOART32, '.
    '        CQ_FECHAVTOART200, '.
    '        CQ_FECHATOMACONCONCURSO, CQ_FECHATOMACONQUIEBRA, LCQ.CQ_MONTOPRIVILEGIO, '.
    '        LCQ.CQ_MONTOQUIROG, LCQ.CQ_SINDICO, '.
    '        LCQ.CQ_DIRECCIONSIND, LCQ.CQ_LOCALIDADSIND, LCQ.CQ_TELEFONOSIND, '.
    '        LCQ.CQ_JUZGADO, LCQ.CQ_SECRETARIA, '.
    '        LCQ.CQ_FUERO, FUE.TB_DESCRIPCION FUE_DESCRIPCION, LCQ.CQ_JURISDICCION, '.
    '        ju_descripcion JUR_DESCRIPCION, '.
    '        LCQ.CQ_ABOGADO, bo_nombre, LCQ.CQ_MONTOHOMOLOG, '.
    '        LCQ.CQ_ESTADO, LCQ.CQ_AUTORIZACION, LCQ.CQ_ULTPERCONCURSO, '.
    '        LCQ.CQ_ULTPERQUIEBRA, LCQ.CQ_LEGAJO '.
    '   FROM ART.CTB_TABLAS FUE, LEGALES.LJU_JURISDICCION, ART.CMP_EMPRESAS CMP, '.
    '        LEGALES.LBO_ABOGADO LBO, ART.LCQ_CONCYQUIEBRA LCQ '.
    '  WHERE LCQ.CQ_FUERO = FUE.TB_CODIGO (+) '.
    '    AND LCQ.CQ_JURISDICCION = ju_id(+) '.
    '    AND LCQ.CQ_CUIT = CMP.MP_CUIT '.
    '    AND LCQ.CQ_ABOGADO = LBO.BO_ID (+) '.
    '    AND FUE.TB_CLAVE (+) = ''FUERO'' '.
    '    AND LCQ.CQ_NROORDEN = '.SqlValue($nroorden) );

  $sXml= CrearXml1('DATOSCYQ',$strqry);
  return $sXml;
  //LogMessage('ObtenerDatosCYQ - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerEventosCYQ($pag, $nroorden) 
{
  //LogMessage('ObtenerEventosCYQ - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT ce_nroorden, ce_nroevento, tb_descripcion, ce_fecha, ce_observaciones, ce_codevento '.
    '   FROM ctb_tablas, lce_eventocyq '.
    '  WHERE tb_codigo = ce_codevento '.
    '    AND tb_clave = ''EVCYQ'' '.
    '    AND ce_nroorden = '.SqlValue($nroorden).
    '    AND ce_nroevento > 0 ');

  $sXml=CrearXmlTabla('EVENTOSCYQ','dtsEventosCYQ', 'http://www.changeme.now/dtsEventosCYQ.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerEventosCYQ - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerAcuerdos($pag, $nroorden, $filtro) 
{
  //LogMessage('ObtenerAcuerdos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $sSQL=      ' SELECT ca_nroorden, ca_nropago, ca_monto, ca_fechavenc, ca_fechapago,  '.
                '        ca_observaciones, tb_descripcion, ca_fechaextincion '.
                '   FROM lca_acuerdocyq, CTB_TABLAS'.
                '  WHERE ca_nroorden = '.SqlValue($nroorden).
                '    AND ca_nropago > 0 '.
                '    AND TB_CODIGO(+) = CA_TIPO '.
                '    AND TB_CLAVE(+) = ''TACYQ''';
    if($filtro <> '') then
      $sSQL= $sSQL + ' AND CA_TIPO = '.SqlValue($filtro);

    //LogMessage('ObtenerAcuerdos - Query - '.$sSQL,EVENTLOG_INFORMATION_TYPE,0,0);
    $qry=$dmConn.GetQuery($sSQL);
    //LogMessage('ObtenerAcuerdos - Despues GetQuery ',EVENTLOG_INFORMATION_TYPE,0,0);

    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';
    $sXml  = '';

    $x=0;
    $sXml  = $sXml  + '<'.'dtsAcuerdos'.' xmlns="'.'http://www.changeme.now/dtsAcuerdos.xsd'.'">';
    if $pag<>0 then
    {
      $y=0;
      while not $qry.Eof and ($y<$pag*9) do
      {
        $qry.Next;
        $y=$y+1;
      }
    }
    while not $qry.Eof and ($x<9) do
    {
      $sXml  = $sXml+ '<'.'ACUERDOS'.'>';
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
        if ($qry.Fields[$i].DataType = ftFloat) and ($qry.Fields[$i].FieldName='CA_MONTO')then
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
        else
        {
          $auxString = $qry.Fields[$i].AsString;
          ReplaceString($auxString,'&','&amp;');
          ReplaceString($auxString,'<','&lt;');
          ReplaceString($auxString,'>','&gt;');
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
        }
      }
      $qry.Next;
      $sXml  = $sXml+ '</'.'ACUERDOS'.'>';
      $x=$x+1;
    }
    $sXml  = $sXml  + '</'.'dtsAcuerdos'.'>';
    //LogMessage('ObtenerAcuerdos - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('ObtenerAcuerdos - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ObtenerAcuerdosMod($nroorden) 
{
  //LogMessage('ObtenerAcuerdosMod - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.Getquery(
     '   SELECT TB_DESCRIPCION TIPO_ACUERDO, '.
	   '          COUNT(*) CUOTAS, SUM(DECODE(CA_FECHAPAGO,NULL,1,0)) CUOTAS_PENDIENTES, '.
	   '          SUM(CA_MONTO) MONTO_TOTAL, SUM(DECODE(CA_FECHAPAGO,NULL,CA_MONTO,0)) MONTO_PENDIENTE '.
     '     FROM CTB_TABLAS, LCA_ACUERDOCYQ '.
     '    WHERE TB_CODIGO(+) = CA_TIPO '.
     '      AND TB_CLAVE(+) = ''TACYQ'' '.
     '      AND CA_NROORDEN = '.SqlValue($nroorden) +
     '      AND CA_NROPAGO > 0 '.
     ' GROUP BY TB_DESCRIPCION ');

    //LogMessage('ObtenerAcuerdosMod - Query - '.$qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';
    $sXml  = '';

    $x=0;
    $sXml  = $sXml  + '<'.'dtsAcuerdosmod'.' xmlns="'.'http://www.changeme.now/dtsAcuerdosmod.xsd'.'">';
    while not $qry.Eof and ($x<9) do
    {
      $sXml  = $sXml+ '<'.'ACUERDOS'.'>';
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
        if ($qry.Fields[$i].DataType = ftFloat) and ($qry.Fields[$i].FieldName='CA_MONTO')then
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
        else
        {
          $auxString = $qry.Fields[$i].AsString;
          ReplaceString($auxString,'&','&amp;');
          ReplaceString($auxString,'<','&lt;');
          ReplaceString($auxString,'>','&gt;');
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
        }
      }
      $qry.Next;
      $sXml  = $sXml+ '</'.'ACUERDOS'.'>';
      $x=$x+1;
    }
    $sXml  = $sXml  + '</'.'dtsAcuerdosmod'.'>';
    //LogMessage('ObtenerAcuerdosMod - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('ObtenerAcuerdosMod - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ObtenerEmpresa(Cuil  ) 
{
  //LogMessage('ObtenerEmpresa - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    '  SELECT  em_id AS id, em_cuit AS CUIT, em_nombre AS NOMBRE, '.
    '          co_contrato AS CONTRATO, '.
    '          art.afiliacion.check_cobertura (co_contrato) AS CHECKCOBERTURA, '.
    '          DECODE '.
    '                (art.afiliacion.check_cobertura (co_contrato), '.
    '                 1, 1, '.
    '                 2 '.
    '                ) AS ORDENESTADO, '.
    '          co_fechabaja AS FECHA_BAJA, em_fechaconcurso, em_fechaquiebra '.
    '     FROM aem_empresa, aco_contrato '.
    '    WHERE em_id = co_idempresa AND em_cuit = '.SqlValue(cuil).
    ' ORDER BY ordenestado, CONTRATO DESC, NOMBRE ');

  $sXml= CrearXml1('EMPRESA',$strqry);
  return $sXml;
  //LogMessage('ObtenerEmpresa - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerEstado(codigo  ) 
{
  //LogMessage('ObtenerEstado - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT tb_codigo AS id, tb_codigo AS codigo, tb_descripcion AS descripcion, '.
    '        tb_fechabaja AS baja, tb_clave, tb_especial1, tb_especial2 '.
    '   FROM ctb_tablas '.
    '  WHERE tb_codigo <> ''0'' '.
    '    AND tb_fechabaja IS NULL '.
    '    AND tb_clave = ''ESTCQ'' '.
    '    AND ctb_tablas.tb_codigo = '.SqlValue(codigo));

  $sXml= CrearXml1('ESTADO',$strqry);
  return $sXml;
  //LogMessage('ObtenerEstado - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerDemandas($nrojuicio) 
{
  //LogMessage('ObtenerDemandas - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $nro= StrToInt($nrojuicio);
  $strqry=(
    ' SELECT lod_origendemanda.od_id, lod_origendemanda.od_idreclamante, '.
    '        lre_reclamante.re_descripcion, od_idjuicioentramite, '.
    '        legales.get_descripcionorigendemanda (lod_origendemanda.od_id) AS descripciondemanda, '.
    '        NVL ((SELECT COUNT (*) '.
    '                FROM legales.v_lds_siniestrojuicioentramite '.
    '               WHERE ds_idorigendemanda = od_id), 0 ) AS tienesiniestros '.
    '   FROM legales.lod_origendemanda, legales.lre_reclamante '.
    '  WHERE lre_reclamante.re_id = lod_origendemanda.od_idreclamante '.
    '    AND lod_origendemanda.od_fechabaja IS NULL '.
    '    AND od_idjuicioentramite = ' + SqlInt($nro) );

  $sXml=CrearXmlTabla('origendemanda','dstDemanda', 'http://www.changeme.now/dstDemanda.xsd',$strqry);
  return $sXml;
  //LogMessage('ObtenerDemandas - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerSiniestros($pag,  $origendemanda) 
{
  //LogMessage('ObtenerSiniestros - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $od= StrToInt($origendemanda);
  $strqry=(
    ' SELECT sj_idorigendemanda, ex_siniestro, ex_orden, ex_recaida, ex_siniestrored, mp_contrato, '.
    '        mp_cuit, mp_nombre, tj_cuil, tj_nombre, ex_diagnostico, '.
    '        ex_fechaaccidente, ex_bajamedica, ex_fecharecaida, ex_altamedica, '.
    '        tb_descripcion, sj_fechaalta, sj_fechamodif, sj_fechabaja, liq.get_importeliquidadoilt(ex_siniestro,ex_orden,ex_recaida) as importeliquidado '.
    '   FROM art.ctb_tablas, '.
    '        art.ctj_trabajadores, '.
    '        art.cmp_empresas, '.
    '        art.sex_expedientes, '.
    '        legales.lsj_siniestrosjuicioentramite '.
    '  WHERE NVL (ex_tipo, ''1'') = tb_codigo(+) '.
    '    AND tb_clave = ''STIPO'' '.
    '    AND mp_cuit = ex_cuit '.
    '    AND tj_cuil = ex_cuil '.
    '    AND ex_siniestro = sj_siniestro '.
    '    AND ex_recaida = sj_recaida '.
    '    AND ex_orden = sj_orden '.
    '    AND sj_idorigendemanda = '.SqlInt($od));

  $sXml=CrearXmlTabla('SINIESTROS','dtsSiniestros', 'http://www.changeme.now/dtsSiniestros.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerSiniestros - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerReclamos($nrojuicio) 
{
  //LogMessage('ObtenerReclamos - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $nro= StrToInt($nrojuicio);
  $strqry= (
    ' SELECT lrc_reclamo.rc_descripcion, lrt_reclamojuicioentramite.rt_id, '.
    '        lrt_reclamojuicioentramite.rt_idjuicioentramite, '.
    '        lrt_reclamojuicioentramite.rt_idreclamo, '.
    '        lrt_reclamojuicioentramite.rt_$montodemandado, '.
    '        lrt_reclamojuicioentramite.rt_montosentencia, '.
    '        lrt_reclamojuicioentramite.rt_porcentajesentencia, '.
    '        lrt_reclamojuicioentramite.RT_IMPORTENOMINAL, '.
    '        lrt_reclamojuicioentramite.RT_INTERESES, '.
    '        lrt_reclamojuicioentramite.rt_porcentajeincapacidad '.
    '   FROM legales.lrt_reclamojuicioentramite, legales.lrc_reclamo '.
    '  WHERE lrc_reclamo.rc_id = lrt_reclamojuicioentramite.rt_idreclamo '.
    '    AND lrt_reclamojuicioentramite.rt_fechabaja IS NULL '.
    '    AND rt_idjuicioentramite = '.SqlInt($nro) );

  $sXml=CrearXmlTabla('ORIGENRECLAMO','dstReclamo', 'http://www.changeme.now/dstReclamo.xsd',$strqry);
  return $sXml;
  //LogMessage('ObtenerReclamos - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerMedidasCautelares($pag ; $nrojuicio) 
{
  //LogMessage('ObtenerMedidasCautelares - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $nro= StrToInt($nrojuicio);
  $strqry= (
    ' SELECT ltm.tm_descripcion AS medidas, lmc.mc_importe, lmc.mc_observaciones, '.
    '        lmc.mc_idtipomedida, lmc.mc_idjuicioentramite, lmc.mc_id '.
    '   FROM legales.lmc_medidascautelares lmc, legales.ltm_tipomedidas ltm '.
    '  WHERE lmc.mc_idtipomedida = ltm.tm_id '.
    '    AND lmc.mc_fechabaja IS NULL '.
    '   AND lmc.mc_idjuicioentramite = '.SqlInt($nro));

  $sXml=CrearXmlTabla('MEDIDASCAUTELARES','dtsMedidasCautelares', 'http://www.changeme.now/dtsMedidasCautelares.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerMedidasCautelares - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerEventos($pag ; $nrojuicio) 
{
  //LogMessage('ObtenerEventos - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $nro= StrToInt($nrojuicio);
  $strqry= (
    '   SELECT ev.te_descripcion AS evento, ejt.et_fechaevento, '.
    '          ejt.et_fechavencimiento, ejt.et_observaciones, '.
    '          ejt.et_idjuicioentramite, ejt.$et_id '.
    '     FROM legales.let_eventojuicioentramite ejt, legales.lte_tipoevento ev '.
    '    WHERE ejt.et_idtipoevento = ev.te_id '.
    '      AND ejt.et_fechabaja IS NULL '.
    '      AND te_visibleweb = ''S'' '.
    '      AND ejt.et_idjuicioentramite = ' + SqlInt($nro).
    ' ORDER BY et_fechaevento DESC');

  $sXml=CrearXmlTabla('EVENTOS','dtsEventos', 'http://www.changeme.now/dtsEventos.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerEventos - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerEventosABM($et_id) 
{
  //LogMessage('ObtenerEventosABM - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $etid= StrToInt($et_id);
  $strqry= (
    ' SELECT et_fechavencimiento, et_fechaevento, et_idtipoevento, et_observaciones, '.
    '        et_idjuicioentramite,et_usualta '.
    '   FROM legales.let_eventojuicioentramite '.
    '  WHERE $et_id = '.SqlInt($etid));

  $sXml  = CrearXml1('EVENTOS',$strqry);
  return $sXml;
  //LogMessage('ObtenerEventosABM - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerEventosCYQABM($nroorden, $nroevento) 
{
  //LogMessage('ObtenerEventosCYQABM - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT ce_codevento, ce_fecha, ce_observaciones '.
    '   FROM lce_eventocyq '.
    '  WHERE ce_nroorden = '.SqlValue($nroorden).
    '    AND ce_nroevento = '.SqlValue($nroevento));

  $sXml  = CrearXml1('EVENTOSCYQ',$strqry);
  return $sXml;
  //LogMessage('ObtenerEventosCYQABM - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerAcuerdosABM($nroorden, $nropago) 
{
  //LogMessage('ObtenerAcuerdosABM - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery(
      ' SELECT ca_monto, ca_fechavenc, ca_fechapago, ca_observaciones, ca_tipo,  '.
      '        ca_fechaextincion '.
      '   FROM lca_acuerdocyq '.
      '  WHERE ca_nroorden = '.SqlValue($nroorden).
      '    AND ca_nropago = '.SqlValue($nropago));

    //LogMessage('ObtenerAcuerdosABM - Query - '.$qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    $sXml  = $sXml  + '<'.'ACUERDOS'.'>';
    while not $qry.Eof do
    {
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
        if ($qry.Fields[$i].DataType = ftFloat) and ($qry.Fields[$i].FieldName='CA_MONTO')then
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
        else
        {
          $auxString = $qry.Fields[$i].AsString;
          ReplaceString($auxString,'&','&amp;');
          ReplaceString($auxString,'<','&lt;');
          ReplaceString($auxString,'>','&gt;');
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
        }
      }
      $qry.Next;
    }
    $sXml  = $sXml  + '</'.'ACUERDOS'.'>';
    //LogMessage('ObtenerAcuerdosABM - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('ObtenerAcuerdosABM - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UploadsEventosABM($et_id) 
{
  //LogMessage('UploadsEventosABM - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $etid= StrToInt($et_id);
  $strqry= (
    ' SELECT ea_descripcion, ea_id '.
    '   FROM legales.lea_eventoarchivoasociado '.
    '  WHERE ea_ideventojuicioentramite = '.SqlInt($etid).
    '    AND ea_fechabaja IS NULL ');

  $sXml  = '';
  $sXml  = CrearXml2('EVENTOS','EVENTO',$strqry);
  return $sXml;
  //LogMessage('UploadsEventosABM - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerPeritajes($nrojuicio;$pag) 
{
  //LogMessage('ObtenerPeritajes - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $pj_idjuicioentramite= StrToInt($nrojuicio);
  $strqry= (
    ' SELECT ltp.tp_descripcion AS tipopericia, '.
    '        lpj.pj_fechanotificacion AS fechanotificacion, '.
    '        lpj.pj_fechaperitaje AS fechapericia, '.
    '        lpj.pj_fechavencimpugnacion AS fvencimpugnacion, '.
    '        lpj.pj_impugnacion AS $impugnacion, lpj.pj_id, lpj.pj_idjuicioentramite '.
    '   FROM legales.lpj_peritajejuicio lpj, legales.ltp_tipopericia ltp '.
    '  WHERE lpj.pj_idtipopericia = ltp.tp_id(+) '.
    '    AND lpj.pj_fechabaja IS NULL '.
    '    AND lpj.pj_idjuicioentramite = '.SqlInt($pj_idjuicioentramite) );

  $sXml  = '';
  $sXml=CrearXmlTabla('PERITAJES','dtsPeritajes', 'http://www.changeme.now/dtsPeritajes.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerPeritajes - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerPeritajesABM(pj_id) 
{
  //LogMessage('ObtenerPeritajesABM - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    idperitaje= StrToInt(pj_id);
    $qry= $dmConn.GetQuery(
      ' SELECT PJ_ID, PJ_FECHAPERITAJE, PJ_IDJUICIOENTRAMITE, PJ_RESULTADOPERITAJE, PJ_FECHANOTIFICACION, '.
      '        PJ_IDTIPOPERICIA, PJ_FECHAVENCIMPUGNACION, PJ_INCAPACIDADDEMANDA, PJ_USUALTA, '.
      '        PJ_INCAPACIDADPERITOMEDICO, PJ_IBMART, PJ_IBMPERICIAL, PJ_IMPUGNACION, '.
      '        DECODE (pj_impugnacion, ''S'', 0, ''N'', 1, -1) AS $impugnacion, PJ_IDPERITO, '.
      '        PE_NOMBRE, pe_nombreindividual, pe_apellido '.
      '   FROM legales.lpj_peritajejuicio,legales.lpe_perito '.
      '  WHERE pj_idperito = pe_id(+) '.
      '    AND pj_id = '.SqlInt(idperitaje));

    //LogMessage('ObtenerPeritajesABM - Query - '.$qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);  
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';

    return $sXml;
    $sXml  = $sXml  + '<PERITAJES>';
    while not $qry.Eof do
    {
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
          if ($qry.Fields[$i].DataType = ftFloat) and not (($qry.Fields[$i].FieldName = 'PJ_IDJUICIOENTRAMITE')or ($qry.Fields[$i].FieldName = '$impugnacion')or
              ($qry.Fields[$i].FieldName = 'PJ_ID') or ($qry.Fields[$i].FieldName = 'PJ_IDTIPOPERICIA') or
              ($qry.Fields[$i].FieldName = 'PJ_IDPERITO')) then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
          else
          {
            $auxString = $qry.Fields[$i].AsString;
            ReplaceString($auxString,'&','&amp;');
            ReplaceString($auxString,'<','&lt;');
            ReplaceString($auxString,'>','&gt;');
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
          }
      }
      $qry.Next;
    }
    $sXml  = $sXml  + '</PERITAJES>';
    //LogMessage('ObtenerPeritajesABM - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('ObtenerPeritajesABM - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ObtenerMedidaCautelarABM(MC_ID) 
{
  //LogMessage('ObtenerMedidaCautelarABM - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  idMedida= StrToInt(MC_ID);
  $strqry= (
    ' SELECT MC_IDTIPOMEDIDA, MC_IMPORTE, MC_OBSERVACIONES, '.
    '        MC_IDJUICIOENTRAMITE '.
    '   FROM legales.LMC_MEDIDASCAUTELARES '.
    '  WHERE MC_ID = '.SqlInt(idMedida));
  $sXml  = '';
  $sXml= CrearXml1('MEDIDACAUTELAR',$strqry);
  return $sXml;
  //LogMessage('ObtenerMedidaCautelarABM - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerGastosTasasDeJusticia($nrojuicio) 
{
  //LogMessage('ObtenerGastosTasasDeJusticia - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT nvl(sum(gj_importe),0) as gj_importe '.
    '   FROM legales.lir_importesreguladosjuicio, '.
    '        legales.lgj_gastosjuicio, '.
    '        legales.ltg_tipogasto '.
    '  WHERE ir_id = gj_idimportesjuicio '.
    '    AND ir_aplicacion = ''G'' '.
    '    AND tg_id = gj_idtipogasto '.
    '    AND GJ_IDTIPOGASTO = 1 '.
    '    AND ir_idjuicioentramite = '.SqlValue($nrojuicio));
  $sXml  = '';
  $sXml= CrearXml1('GASTOTASADEJUSTICIA',$strqry);
  return $sXml;
  //LogMessage('ObtenerGastosTasasDeJusticia - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerGastosEmbargos($nrojuicio) 
{
  //LogMessage('ObtenerGastosEmbargos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT nvl(sum(gj_importe),0) as gj_importe '.
    '   FROM legales.lir_importesreguladosjuicio, '.
    '        legales.lgj_gastosjuicio, '.
    '        legales.ltg_tipogasto '.
    '  WHERE ir_id = gj_idimportesjuicio '.
    '    AND ir_aplicacion = ''G'' '.
    '    AND tg_id = gj_idtipogasto '.
    '    AND GJ_IDTIPOGASTO = 4 '.
    '    AND ir_idjuicioentramite = '.SqlValue($nrojuicio));
  $sXml  = '';
  $sXml= CrearXml1('GASTOEMBARGO',$strqry);
  return $sXml;
  //LogMessage('ObtenerGastosEmbargos - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerGastosOtros($nrojuicio) 
{
  //LogMessage('ObtenerGastosOtros - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT nvl(sum(gj_importe),0) as gj_importe '.
    '   FROM legales.lir_importesreguladosjuicio, '.
    '        legales.lgj_gastosjuicio, '.
    '        legales.ltg_tipogasto '.
    '  WHERE ir_id = gj_idimportesjuicio '.
    '    AND ir_aplicacion = ''G'' '.
    '    AND tg_id = gj_idtipogasto '.
    '    AND GJ_IDTIPOGASTO not in (1,4) '.
    '    AND ir_idjuicioentramite = '.SqlValue($nrojuicio));
  $sXml  = '';
  $sXml= CrearXml1('GASTOOTROS',$strqry);
  return $sXml;
  //LogMessage('ObtenerGastosOtros - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerLiquidacionFinal($nrojuicio) 
{
  //LogMessage('ObtenerLiquidacionFinal - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT $lf_id, lf_capital, lf_intereses, lf_tasasdejusticia, lf_embargos, '.
    '        lf_otros, lf_observaciones '.
    '   FROM legales.llf_liquidacionfinal '.
    '  WHERE lf_idjuicioentramite = '.SqlValue($nrojuicio));

  $sXml  = '';
  $sXml= CrearXml1('LIQUIDACIONFINAL',$strqry);
  return $sXml;
  //LogMessage('ObtenerLiquidacionFinal - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ExisteLiquidacion($nrojuicio) 
{
  //LogMessage('ExisteLiquidacion - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT 1 as existe '.
    '   FROM legales.ljt_juicioentramite '.
    '  WHERE $jt_id = '.SqlValue($nrojuicio).
    '    AND jt_fechasentencia IS NOT NULL');

  $sXml  = '';
  $sXml= CrearXml1('EXISTELIQUIDACION',$strqry);
  return $sXml;
  //LogMessage('ExisteLiquidacion - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerImportesABM(ir_id) 
{
  //LogMessage('ObtenerImportesABM - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $idimporte= StrToInt(ir_id);
    $qry= $dmConn.GetQuery(
      ' SELECT ir_importesentencia, ir_aplicacion, ir_detalleweb, ir_idjuicioentramite '.
      '   FROM legales.lir_importesreguladosjuicio '.
      '  WHERE ir_id = '.SqlInt($idimporte));

    //LogMessage('ObtenerImportesABM - Query - '.$qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);  
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    $sXml  = $sXml  + '<'.'IMPORTES'.'>';
    while not $qry.Eof do
    {
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
        if ($qry.Fields[$i].DataType = ftFloat)and not ($qry.Fields[$i].FieldName = 'IR_IDJUICIOENTRAMITE') then
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
        else
        {
          $auxString = $qry.Fields[$i].AsString;
          ReplaceString($auxString,'&','&amp;');
          ReplaceString($auxString,'<','&lt;');
          ReplaceString($auxString,'>','&gt;');
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
        }
      }
      $qry.Next;
    }
    $sXml  = $sXml  + '</'.'IMPORTES'.'>';
    //LogMessage('ObtenerImportesABM - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('ObtenerImportesABM - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ObtenerSentencia($nrojuicio) 
{
  //LogMessage('ObtenerSentencia - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $nro= StrToInt($nrojuicio);
    $qry= $dmConn.GetQuery(
      ' SELECT jtr.$jt_id, jt_idtiporesultadosentencia, jtr.jt_fechasentencia,jt_fecharecepsentencia, '.
      '        jtr.jt_importedemandado, jt_importecapital, jt_importetasajusticia, '.
      '        jtr.jt_importesentencia, jtr.jt_importehonorarios, jt_detallesentencia, '.
      '        jt_interesessentencia,JT_MONTOCONDENA,JT_PORCENTAJEINCAPACIDAD '.
      '   FROM legales.ljt_juicioentramite jtr '.
      '  WHERE jtr.jt_fechabaja IS NULL AND jtr.$jt_id = '.SqlInt($nro) );

    //LogMessage('ObtenerSentencia - Query - '.$qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    $sXml  = $sXml  + '<'.'SENTENCIA'.'>';
    while not $qry.Eof do
    {
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
        if ($qry.Fields[$i].DataType = ftFloat) and not ($qry.Fields[$i].FieldName='JT_IDTIPORESULTADOSENTENCIA')then
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
        else
        {
          $auxString = $qry.Fields[$i].AsString;
          ReplaceString($auxString,'&','&amp;');
          ReplaceString($auxString,'<','&lt;');
          ReplaceString($auxString,'>','&gt;');
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
        }
      }
      $qry.Next;
    }
    $sXml  = $sXml  + '</'.'SENTENCIA'.'>';
    //LogMessage('ObtenerSentencia - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;;
    //LogMessage('ObtenerSentencia - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ObtenerMontosCYQ($nroorden) 
{
  //LogMessage('ObtenerMontosCYQ - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery(
      ' SELECT LCQ.CQ_NROORDEN, LCQ.CQ_CUIT, CMP.MP_NOMBRE, LCQ.CQ_DEUDANOMINAL, LCQ.CQ_DEUDATOTAL, '.
      '        LCQ.CQ_DEUDAVERIFICADA, NVL(LCQ.CQ_FECHACONCURSO, MP_FECHACONCURSO) $fechaconcurso, '.
      '        NVL(LCQ.CQ_FECHAQUIEBRA, MP_FECHAQUIEBRA) $fechaquiebra, CQ_FECHAASIGN, CQ_FECHAVTOART32, CQ_FECHAVTOART200, '.
      '        CQ_FECHATOMACONCONCURSO, CQ_FECHATOMACONQUIEBRA, LCQ.CQ_MONTOPRIVILEGIO, LCQ.CQ_MONTOQUIROG, LCQ.CQ_SINDICO, '.
      '        LCQ.CQ_DIRECCIONSIND, LCQ.CQ_LOCALIDADSIND, LCQ.CQ_TELEFONOSIND, LCQ.CQ_JUZGADO, LCQ.CQ_SECRETARIA, '.
      '        LCQ.CQ_FUERO, FUE.TB_DESCRIPCION FUE_DESCRIPCION, LCQ.CQ_JURISDICCION, JUR.TB_DESCRIPCION JUR_DESCRIPCION, '.
      '        LCQ.CQ_ABOGADO, bo_nombre, LCQ.CQ_MONTOHOMOLOG, LCQ.CQ_ESTADO, LCQ.CQ_AUTORIZACION, LCQ.CQ_ULTPERCONCURSO, '.
      '        LCQ.CQ_ULTPERQUIEBRA, LCQ.CQ_LEGAJO '.
      '   FROM ART.CTB_TABLAS FUE, ART.CTB_TABLAS JUR, ART.CMP_EMPRESAS CMP, LEGALES.LBO_ABOGADO LBO, ART.LCQ_CONCYQUIEBRA LCQ '.
      '  WHERE LCQ.CQ_FUERO = FUE.TB_CODIGO (+) '.
      '    AND LCQ.CQ_JURISDICCION = JUR.TB_CODIGO (+) '.
      '    AND LCQ.CQ_CUIT = CMP.MP_CUIT '.
      '    AND LCQ.CQ_ABOGADO = LBO.BO_ID (+) '.
      '    AND FUE.TB_CLAVE (+) = ''FUERO'' '.
      '    AND JUR.TB_CLAVE (+) = ''JURIS'' '.
      '    AND LCQ.CQ_NROORDEN = '.SqlValue($nroorden) );
    //LogMessage('ObtenerMontosCYQ - Query - '.$qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    $sXml  = $sXml  + '<'.'DATOSCYQ'.'>';
    while not $qry.Eof do
    {
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
          if ($qry.Fields[$i].DataType = ftFloat) and not ($qry.Fields[$i].FieldName = 'RT_IDJUICIOENTRAMITE') then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
          else
          {
            $auxString = $qry.Fields[$i].AsString;
            ReplaceString($auxString,'&','&amp;');
            ReplaceString($auxString,'<','&lt;');
            ReplaceString($auxString,'>','&gt;');
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
          }
      }
      $qry.Next;
    }
    $sXml  = $sXml  + '</'.'DATOSCYQ'.'>';
    //LogMessage('ObtenerMontosCYQ - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('ObtenerMontosCYQ - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}


function  ObtenerReclamoABM(Id_Reclamo) 
{
  //LogMessage('ObtenerReclamoABM - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $nro= StrToInt(Id_Reclamo);
    $qry= $dmConn.GetQuery(
      ' SELECT rc_descripcion, rt_$montodemandado, rt_montosentencia, '.
      '        rt_porcentajesentencia, rt_idjuicioentramite '.
      '   FROM legales.lrt_reclamojuicioentramite rjt, legales.lrc_reclamo r '.
      '  WHERE rjt.rt_idreclamo = r.rc_id AND rjt.rt_id = '.SqlInt($nro) );
    //LogMessage('ObtenerReclamoABM - Query - '.$qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    $sXml  = $sXml  + '<'.'RECLAMO'.'>';
    while not $qry.Eof do
    {
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
          if ($qry.Fields[$i].DataType = ftFloat) and not ($qry.Fields[$i].FieldName = 'RT_IDJUICIOENTRAMITE') then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
          else
          {
            $auxString = $qry.Fields[$i].AsString;
            ReplaceString($auxString,'&','&amp;');
            ReplaceString($auxString,'<','&lt;');
            ReplaceString($auxString,'>','&gt;');
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
          }
      }
      $qry.Next;
    }
    $sXml  = $sXml  + '</'.'RECLAMO'.'>';
    //LogMessage('ObtenerReclamoABM - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('ObtenerReclamoABM - Desconectado ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ObtenerTipoResultadoSentencia(seleccionado : integer) 
{
  //LogMessage('ObtenerTipoResultadoSentencia - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT tr_id, tr_descripcion, tr_etapa '.
    '   FROM legales.ltr_tiporesultadosentencia '.
    '  WHERE tr_etapa LIKE ''%J%'' ' );
  $strqry = $strqry + ' AND ( ' ;
  if(seleccionado <> -1) then
    $strqry = $strqry + ' tr_id = '.SqlInt(seleccionado). ' or ';
  $strqry = $strqry + ' tr_fechabaja is null) ';

  $sXml  = '';
  $sXml= CrearXml2('TIPOSENTENCIA','SENTENCIA',$strqry);
  return $sXml;
  //LogMessage('ObtenerTipoResultadoSentencia - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerTipoResultadoSentenciaActora(seleccionado : integer) 
{
  //LogMessage('ObtenerTipoResultadoSentenciaActora - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT tr_id, tr_descripcion, tr_etapa '.
    '   FROM legales.ltr_tiporesultadosentencia '.
    '  WHERE tr_etapa LIKE ''%A%'' ' );
  $strqry = $strqry + ' AND ( ' ;
  if(seleccionado <> -1) then
    $strqry = $strqry + ' tr_id = '.SqlInt(seleccionado). ' or ';
  $strqry = $strqry + ' tr_fechabaja is null) ';

  $sXml  = '';
  $sXml= CrearXml2('TIPOSENTENCIA','SENTENCIA',$strqry);
  return $sXml;
  //LogMessage('ObtenerTipoResultadoSentenciaActora - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerMasDatosJuicios($nrojuicio) 
{
  //LogMessage('ObtenerMasDatosJuicios - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $pj_idjuicioentramite= StrToInt($nrojuicio);
  $strqry= (
    ' SELECT ljz_juzgado.jz_descripcion, lju_jurisdiccion.ju_descripcion, '.
    '        ljz_juzgado.jz_direccion, ljz_juzgado.jz_telefono, '.
    '        lfu_fuero.fu_descripcion, lsc_secretaria.sc_descripcion, '.
    '        ljz_juzgado.jz_fax, ljz_juzgado.jz_email'.
    '   FROM legales.ljt_juicioentramite a, '.
    '        legales.lju_jurisdiccion, '.
    '        legales.ljz_juzgado, '.
    '        legales.lfu_fuero, '.
    '        legales.lsc_secretaria '.
    '  WHERE lju_jurisdiccion.ju_id = a.jt_idjurisdiccion '.
    '    AND ljz_juzgado.jz_id = a.jt_idjuzgado '.
    '    AND lfu_fuero.fu_id = a.jt_idfuero '.
    '    AND lsc_secretaria.sc_id = a.jt_idsecretaria '.
    '    AND a.$jt_id = '.SqlInt($pj_idjuicioentramite) );
  $sXml  = '';
  $sXml  = CrearXml1('MASDATOSJUICIOS',$strqry);
  return $sXml;
  //LogMessage('ObtenerMasDatosJuicios - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerInstancias($pj_idjuicioentramite ) 
{
  //LogMessage('ObtenerInstancias - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $aux_idjuicioentramite= StrToInt($pj_idjuicioentramite);
  $strqry= (
    ' SELECT   lju_jurisdiccion.ju_descripcion, lfu_fuero.fu_descripcion, '.
    '          ljz_juzgado.jz_descripcion, lsc_secretaria.sc_descripcion, '.
    '          lin_instancia.in_descripcion, lmc_motivocambiojuzgado.mc_descripcion, '.
    '          a.ij_id, a.ij_idjuicioentramite, a.ij_orden, a.ij_idjurisdiccion, '.
    '          a.ij_idfuero, a.ij_idjuzgado, a.ij_idsecretaria, a.ij_idinstancia, '.
    '          nvl2(ij_nroexpediente,ij_nroexpediente || ''/''|| ij_anioexpediente,'''') ij_expediente, a.ij_fechatraspaso, a.ij_fecharecepsentencia, '.
    '          a.ij_idmotivocambiojuzgado, a.ij_observaciones, a.ij_fechasentencia, '.
    '          a.ij_idtiporesultadosentencia, '.
    '          NVL '.
    '           ((SELECT SUM (ir_importesentencia) '.
    '               FROM legales.lir_importesreguladosjuicio '.
    '              WHERE ir_idjuicioentramite = a.ij_idjuicioentramite '.
    '                AND ir_idinstancia = a.ij_id '.
    '           AND ir_aplicacion = ''H'' '.
    '           AND ir_fechabaja IS NULL), '.
    '            0 '.
    '            ) ij_importehonorarios, '.
    '          NVL ((SELECT SUM (ir_importesentencia) '.
    '                  FROM legales.lir_importesreguladosjuicio '.
    '                 WHERE ir_idjuicioentramite = a.ij_idjuicioentramite '.
    '                   AND ir_idinstancia = a.ij_id '.
    '                   AND ir_fechabaja IS NULL), '.
    '               0 '.
    '               ) ij_importesentencia, '.
    '          NVL '.
    '           ((SELECT SUM (ir_importesentencia) '.
    '               FROM legales.lir_importesreguladosjuicio '.
    '              WHERE ir_idjuicioentramite = a.ij_idjuicioentramite '.
    '                AND ir_idinstancia = a.ij_id '.
    '                AND ir_aplicacion = ''$i'' '.
    '                AND ir_fechabaja IS NULL), '.
    '            0 '.
    '            ) ij_interesessentencia, '.
    '          NVL ((SELECT SUM (ir_importesentencia) '.
    '                  FROM legales.lir_importesreguladosjuicio '.
    '                 WHERE ir_idjuicioentramite = a.ij_idjuicioentramite '.
    '                   AND ir_idinstancia = a.ij_id '.
    '                   AND ir_aplicacion = ''C'' '.
    '                   AND ir_fechabaja IS NULL), '.
    '            0 '.
    '            ) ij_importecapital, '.
    '          NVL '.
    '           ((SELECT SUM (ir_importesentencia) '.
    '               FROM legales.lir_importesreguladosjuicio '.
    '              WHERE ir_idjuicioentramite = a.ij_idjuicioentramite '.
    '                AND ir_idinstancia = a.ij_id '.
    '                AND ir_aplicacion = ''T'' '.
    '                AND ir_fechabaja IS NULL), '.
    '            0 '.
    '            ) ij_importetasajusticia, '.
    '          lmc_motivocambiojuzgado.mc_relacionnuevojuzgado, '.
    '          weblegales.get_tiposentencia '.
    '                        (a.ij_idtiporesultadosentencia) '.
    '                                                       AS tiposentencia '.
    '     FROM legales.lij_instanciajuicioentramite a, '.
    '          legales.lmc_motivocambiojuzgado, '.
    '          legales.lju_jurisdiccion, '.
    '          legales.ljz_juzgado, '.
    '          legales.lfu_fuero, '.
    '          legales.lin_instancia, '.
    '          legales.lsc_secretaria '.
    '    WHERE lmc_motivocambiojuzgado.mc_id = a.ij_idmotivocambiojuzgado '.
    '      AND lju_jurisdiccion.ju_id = a.ij_idjurisdiccion '.
    '      AND ljz_juzgado.jz_id = a.ij_idjuzgado '.
    '      AND lfu_fuero.fu_id = a.ij_idfuero '.
    '      AND lin_instancia.in_id = a.ij_idinstancia '.
    '      AND lsc_secretaria.sc_id = a.ij_idsecretaria '.
    '      AND a.ij_idjuicioentramite = '.SqlInt($aux_idjuicioentramite).
    ' ORDER BY ij_id DESC ');

  $sXml  = '';
  $sXml  = CrearXmlTabla('INSTANCIAS','dtsInstancias','http://www.changeme.now/dtsInstancias.xsd',$strqry);
  return $sXml;
  //LogMessage('ObtenerInstancias - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerImporte($pj_idjuicioentramite, $instancia ; $pag: integer) 
{
  //LogMessage('ObtenerImporte - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT ir_id, ir_importe, ir_detalle, ir_idjuicioentramite, ir_detalleweb, '.
    '        DECODE (ir_aplicacion,     '.
    '                ''H'', ''Honorarios'', '.
    '                ''$i'', ''Intereses'',  '.
    '                ''T'', ''Tasas''   '.
    '               ) ir_aplicacion,    '.
    '        ir_importesentencia, ir_nropago '.
    '   FROM legales.lir_importesreguladosjuicio irj '.
    '  WHERE irj.ir_idjuicioentramite = '.SqlValue($pj_idjuicioentramite).
    '    AND irj.ir_idinstancia = '.SqlValue($instancia).
    '    AND irj.ir_fechabaja IS NULL '.
    '    AND not ir_aplicacion = ''C'' '.
    '    AND not ir_aplicacion = ''S'' '.
    '    AND irj.ir_carga = ''W'' ');
  $sXml  = '';
  $sXml=CrearXmlTabla('IMPORTES','dtsImportes', 'http://www.changeme.now/dtsImportes.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerImporte - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerInstanciaParaSentencia($pj_idjuicioentramite ) 
{
  //LogMessage('ObtenerInstanciaParaSentencia - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $aux_idjuicioentramite= StrToInt($pj_idjuicioentramite);
  $strqry= (
    '   SELECT   ij_id, art.weblegales.get_tiposentencia(ij_idtiporesultadosentencia) AS tiposentencia '.
    '     FROM legales.lij_instanciajuicioentramite '.
    '    WHERE ij_idjuicioentramite = '.SqlValue($aux_idjuicioentramite).
    ' ORDER BY ij_id DESC ' );
  $sXml  = '';
  $sXml  = CrearXml2('INSTANCIAS','$instancia',$strqry);
  return $sXml;
  //LogMessage('ObtenerInstanciaParaSentencia - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerListaJuiciosEnTramite($pag, idusuario,  caratula,$nrocarpeta, nombreabo, $nroexpediente, tipoJuicio  ) 
{
  //LogMessage('ObtenerListaJuiciosEnTramite - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT $jt_id, DECODE(jt_idestado, 2, ''T'', '''') estado, '.
    '        NVL2(jt_nroexpediente, jt_nroexpediente || ''/'' || jt_anioexpediente, '''') jt_expediente, jt_numerocarpeta, '.
    '        NVL(jt_demandante, '''') || '' C/ '' || NVL(jt_demandado, '''') || '' '' || jt_caratula AS descripcaratula '.
    '   FROM legales.ljt_juicioentramite, legales.lnu_nivelusuario '.
    '  WHERE (   jt_idabogado = nu_idabogado '.
    '         OR nu_usuariogenerico = ''S'') '.
    '    AND jt_fechabaja IS NULL '.
    '    AND nu_id = '.SqlValue(idusuario).
    '    AND jt_estadomediacion = ''J'' '.
    '    AND NVL(jt_bloqueado, ''N'') = ''N'' ';

  if $nroCarpeta <> '' then
  {
    $aux=StrToInt($nroCarpeta);
    $strqry=$strqry + ' AND jt_numerocarpeta = '.SqlInt($aux);
  }
  if Caratula <> ''  then
    $strqry=$strqry +  ' AND NVL(jt_demandante, '''') || '' C/ '' || NVL(jt_demandado, '''') ' +
           ' || '' '' || jt_caratula LIKE  '''' || ''%'' || UPPER('.SqlValue(caratula) .') || ''%'' ';
  if $nroexpediente <> ''  then
    $strqry=$strqry +  ' AND JT_EXPEDIENTE = '.SqlValue($nroexpediente) ;
  if tipoJuicio <> ''then
    $strqry=$strqry +  ' AND JT_IDTIPO = '.SqlValue(tipoJuicio) ;

  $strqry=$strqry +  ' ORDER BY jt_numerocarpeta ';

  $sXml  = '';
  $sXml  = CrearXmlTabla('LJT_JUICIOENTRAMITE','dtsJuicios','http://www.changeme.now/dtsJuicios.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerListaJuiciosEnTramite - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerListaJuiciosEnTramiteParteActora($pag; nombre, caratula,$nrocarpeta, nombreabo, $nroexpediente, tipoJuicio  ) 
{
  //LogMessage('ObtenerListaJuiciosEnTramiteParteActora - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT ljt_juicioentramite.$jt_id, ljt_juicioentramite.jt_idjurisdiccion, lju_jurisdiccion.ju_descripcion, ' +
    '            ljt_juicioentramite.jt_idfuero, lfu_fuero.fu_descripcion, ljt_juicioentramite.jt_idjuzgado, ljz_juzgado.jz_descripcion, ' +
    '            ljt_juicioentramite.jt_idsecretaria, lsc_secretaria.sc_descripcion, ljz_juzgado.jz_idinstancia, ' +
    '            lin_instancia.in_descripcion, ljt_juicioentramite.jt_idestado, lej_estadojuicio.ej_descripcion, ' +
    '            ljt_juicioentramite.jt_idabogado, lbo_abogado.bo_nombre, ljt_juicioentramite.jt_caratula, ' +
    '            ljt_juicioentramite.jt_fechainijuicio, ljt_juicioentramite.jt_registracion, ljt_juicioentramite.jt_idtipo, ' +
    '            ljt_juicioentramite.jt_fechafinjuicio, ljt_juicioentramite.jt_resultado, ljt_juicioentramite.jt_expediente, ' +
    '            ljt_juicioentramite.jt_fechaasign, ljt_juicioentramite.jt_descripcion, ljt_juicioentramite.jt_fechaalta, ' +
    '            ljt_juicioentramite.jt_usualta, ljt_juicioentramite.jt_fechamodif, ljt_juicioentramite.jt_usumodif, ' +
    '            ljt_juicioentramite.jt_fechabaja, ljt_juicioentramite.jt_usubaja, ljt_juicioentramite.jt_numerocarpeta, ' +
    '            ljt_juicioentramite.jt_estadomediacion, ' +
    '            NVL(ljt_juicioentramite.jt_demandante, '''') || '' C/ '' || NVL(ljt_juicioentramite.jt_demandado, '''') ' +
    '            || '' '' || ljt_juicioentramite.jt_caratula AS descripcaratula, ' +
    '            ljt_juicioentramite.jt_demandante, ljt_juicioentramite.jt_demandado, jt_fechanotificacionjuicio ' +
    '   FROM legales.ljz_juzgado, legales.lju_jurisdiccion, legales.lin_instancia, '.
    '        legales.lfu_fuero, legales.lsc_secretaria, legales.ljt_juicioentramite, '.
    '        legales.lej_estadojuicio, legales.lbo_abogado, legales.lnu_nivelusuario '.
    '  WHERE ljt_juicioentramite.jt_fechabaja IS NULL '.
    '    AND jz_idjurisdiccion = ju_id '.
    '    AND jz_idinstancia = in_id '.
    '    AND jz_idfuero = fu_id '.
    '    AND jz_id = sc_idjuzgado '.
    '    AND jt_idestado = ej_id '.
    '    AND ju_id = jt_idjurisdiccion '.
    '    AND jt_idabogado = bo_id '.
    '    AND fu_id = jt_idfuero '.
    '    AND sc_id = jt_idsecretaria '.
    '    AND (jt_idabogado = nu_idabogado OR nu_usuariogenerico = ''S'') '.
    '    AND upper(NU_USUARIO) = '.SqlValue(UpperCase(nombreabo))+
    '    AND jt_estadomediacion like  ''%A%'' ';
  if $nroCarpeta <> '' then
  {
    $aux=StrToInt($nroCarpeta);
    $strqry=$strqry + ' AND ljt_juicioentramite.jt_numerocarpeta = '.SqlInt($aux);
  }
  if Caratula <> ''  then
    $strqry=$strqry +  ' AND NVL(ljt_juicioentramite.jt_demandante, '''') || '' C/ '' || NVL(ljt_juicioentramite.jt_demandado, '''') ' +
           ' || '' '' || ljt_juicioentramite.jt_caratula LIKE  '''' || ''%'' || UPPER('.SqlValue(caratula) .') || ''%'' ';
  if $nroexpediente <> ''  then
    $strqry=$strqry +  ' AND JT_EXPEDIENTE = '.SqlValue($nroexpediente) ;
  if tipoJuicio <> ''then
    $strqry=$strqry +  ' AND JT_IDTIPO = '.SqlValue(tipoJuicio) ;

  $strqry=$strqry +  ' ORDER BY $jt_id ';

  $sXml  = '';
  $sXml  = CrearXmlTabla('LJT_JUICIOENTRAMITE','dtsJuicios','http://www.changeme.now/dtsJuicios.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerListaJuiciosEnTramiteParteActora - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerConcursosyQuiebras($pag; $nroOrden, cmbRSocial, Cuil, estudio ) 
{
  //LogMessage('ObtenerConcursosyQuiebras - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT CQ_NROORDEN, CQ_CUIT,EM_NOMBRE, CQ_FECHACONCURSO, CQ_FECHAQUIEBRA '.
    '   FROM AFI.AEM_EMPRESA, ART.LCQ_CONCYQUIEBRA, legales.lbo_abogado '.
    '  WHERE EM_CUIT = CQ_CUIT '.
    '    AND cq_abogado = bo_id '.
    '    AND bo_idestudiojuridico = '.SqlValue(estudio);
  if $nroOrden <> '' then
  {
    $strqry=$strqry + ' AND CQ_NROORDEN = '.SqlValue($nroOrden);
  }
  if cmbRSocial <> ''  then
    $strqry=$strqry +  ' AND EM_CUIT=' + SqlValue(cmbRSocial);
  if Cuil <> ''  then
    $strqry=$strqry +  ' AND CQ_CUIT=' + SqlValue(Cuil);

  $sXml  = '';
  $sXml  = CrearXmlTabla('CONCURSO','dtsConcursoyQuiebras','http://www.changeme.now/dtsConcursosyQuiebras.xsd',$strqry,$pag);
  return $sXml;
  //LogMessage('ObtenerConcursosyQuiebras - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerResultado($nrojuicio) 
{
  //LogMessage('ObtenerResultado - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $nro= StrToInt($nrojuicio);
  $strqry= (
    ' SELECT jt_resultado '.
    '   FROM legales.ljt_juicioentramite '.
    '  WHERE $jt_id = '.SqlInt($nro) );
  $sXml  = '';
  $sXml  = CrearXml1('RESULTADO',$strqry);
  return $sXml;
  //LogMessage('ObtenerResultado - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerParam(clave) 
{
  //LogMessage('ObtenerParam - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT pa_valor '.
    '   FROM legales.lpa_parametro '.
    '  WHERE pa_clave = '.SqlValue(clave) );

  $sXml  = '';
  $sXml  = CrearXml1('PARAM',$strqry);
  return $sXml;
  //LogMessage('ObtenerParam - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerInstanciaSeleccionada(idjurisdiccion, idfuero, idjuzgado : integer)  
{
  //LogMessage('ObtenerInstanciaSeleccionada - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    '   SELECT jz_id AS id, jz_id AS codigo, jz_descripcion AS descripcion, '.
    '          jz_fechabaja AS baja, jz_idinstancia, in_descripcion, jz_direccion '.
    '     FROM legales.ljz_juzgado, legales.lin_instancia '.
    '    WHERE 1 = 1 '.
    '      AND jz_idjurisdiccion = '.SqlInt(idjurisdiccion).
    '      AND jz_idfuero = '.SqlInt(idfuero).
    '      AND (in_id = jz_idinstancia) '.
    '      AND jz_id = '.SqlInt(idjuzgado).
    ' ORDER BY descripcion ');

  $sXml  = '';
  $sXml  = CrearXml2('INSTANCIAS','$instancia',$strqry);
  return $sXml;
  //LogMessage('ObtenerInstanciaSeleccionada - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerInstanciaaCambiar($nrojuicio ) 
{
  //LogMessage('ObtenerInstanciaaCambiar - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT jz_idinstancia '.
    '   FROM legales.ljt_juicioentramite, '.
    '        legales.lfu_fuero, '.
    '        legales.ljz_juzgado, '.
    '        legales.lin_instancia '.
    '  WHERE ( (ljt_juicioentramite.jt_idfuero = lfu_fuero.fu_id(+)) '.
    '    AND (ljt_juicioentramite.jt_idjuzgado = ljz_juzgado.jz_id(+)) '.
    '    AND (lin_instancia.in_id(+) = ljz_juzgado.jz_idinstancia) '.
    '        ) '.
    '    AND ljt_juicioentramite.$jt_id = '.SqlValue($nrojuicio));

  $sXml  = '';
  $sXml  = CrearXml2('INSTANCIAS','$instancia',$strqry);
  return $sXml;
  //LogMessage('ObtenerInstanciaaCambiar - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerFechadeNotificacion( $nrojuicio )  
{
  //LogMessage('ObtenerFechadeNotificacion - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT jt_fechanotificacionjuicio '.
    '   FROM legales.ljt_juicioentramite '.
    '  WHERE $jt_id = '.SqlValue($nrojuicio));

  $sXml  = '';
  $sXml  = CrearXml1('FECHA',$strqry);
  return $sXml;
  //LogMessage('ObtenerFechadeNotificacion - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerInstanciaModificar($nroinstancia : integer) 
{
  //LogMessage('ObtenerInstanciaModificar - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT ij_idjurisdiccion, ju_descripcion, ij_idfuero, fu_descripcion, '.
    '        ij_idjuzgado, jz_descripcion, ij_idsecretaria, sc_descripcion, '.
    '        ij_idmotivocambiojuzgado, mc_descripcion, ij_fechatraspaso, ij_observaciones, '.
    '        ij_nroexpediente,ij_anioexpediente, in_descripcion '.
    '   FROM legales.lij_instanciajuicioentramite, '.
    '        legales.lin_instancia, '.
    '        legales.lfu_fuero, '.
    '        legales.ljz_juzgado, '.
    '        legales.lju_jurisdiccion, '.
    '        legales.lmc_motivocambiojuzgado, '.
    '        legales.lsc_secretaria '.
    '  WHERE in_id = ij_idinstancia '.
    '    AND ij_idfuero = fu_id '.
    '    AND ij_idjuzgado = jz_id '.
    '    AND ij_idjurisdiccion = ju_id '.
    '    AND ij_idsecretaria = sc_id '.
    '    AND ij_idmotivocambiojuzgado = mc_id '.
    '    AND ij_id = '.SqlInt($nroinstancia));

  $sXml  = '';
  $sXml  = CrearXml2('INSTANCIAS','$instancia',$strqry);
  return $sXml;
  //LogMessage('ObtenerInstanciaModificar - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerEstadoMediacion($nrojuicio ) 
{
  //LogMessage('ObtenerEstadoMediacion - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT jt_estadomediacion '.
    '   FROM legales.ljt_juicioentramite '.
    '  WHERE $jt_id= '.SqlValue($nrojuicio));

  $sXml  = '';
  $sXml  = CrearXml2('ESTADOS','ESTADO',$strqry);
  return $sXml;
  //LogMessage('ObtenerEstadoMediacion - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerNivelUsuario(txtUsuario,txtCont ) 
{
  LogMessage('ObtenerNivelUsuario - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  try {
      $strqry= (
      ' SELECT nu_id, bo_idestudiojuridico, nu_forzarclave, nu_usuario, ej_nombreestudio, BO_NOMBRE'.
      '   FROM legales.lnu_nivelusuario, legales.lbo_abogado, legales.lej_estudiojuridico '.
      '  WHERE nu_usuario = upper('.SqlValue(txtUsuario).')'.
      '    AND nu_claveweb = '.SqlValue(txtCont) +
      '    AND nu_idabogado = bo_id '.
      '    AND bo_idestudiojuridico = ej_id');

    $sXml  = '';
    $sXml= CrearXml1('$usuario',$strqry);
    return $sXml;
    LogMessage('ObtenerNivelUsuario - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
  } catch (Exception $e) {
    on E: Exception do
    {
      LogMessage('ObtenerNivelUsuario - Excepcion - '.e.message,EVENTLOG_ERROR_TYPE,0,0);
      raise Exception.Create('Error: ' + e.message);
    }
  }
}

function  ObtenerAplicaciones(idUsuario) 
{
  //LogMessage('ObtenerAplicaciones - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT um_idmenuweb '.
    '   FROM legales.lum_usuariomenu '.
    '  WHERE um_idusuario = '.SqlValue(idUsuario));

  $sXml  = '';
  $sXml= CrearXml2('APLICACIONES', 'APLICACION',$strqry);
  return $sXml;
  //LogMessage('ObtenerAplicaciones - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerDatosUsuario($nrousuario ) 
{
  //LogMessage('ObtenerDatosUsuario - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT nu_usuario, nu_claveweb, nu_forzarclave '.
    '   FROM legales.lnu_nivelusuario '.
    '  WHERE nu_usuario ='.SqlValue($nrousuario));

  $sXml  = '';
  $sXml  = CrearXml1('$usuario',$strqry);
  return $sXml;
  //LogMessage('ObtenerDatosUsuario - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerValidacionFechaAcuerdo(fechavenc, $nroorden ) 
{
  //LogMessage('ObtenerValidacionFechaAcuerdo - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $format.DateSeparator = '/';
  $format.ShortDateFormat = 'd/m/$y';

  $strqry= (
    ' SELECT distinct ca_nroorden '.
    '   FROM lca_acuerdocyq '.
    '  WHERE ca_nroorden = '.SqlValue($nroorden).
    '    AND '.SqlDate(strtodatedef(fechavenc,0,$format)).' IN (SELECT ca_fechavenc '.
    '                                     FROM lca_acuerdocyq '.
    '                                    WHERE ca_nroorden = '.SqlValue($nroorden).
    '                                      AND ca_nropago >0 )');

  $sXml  = '';
  $sXml  = CrearXml1('ACUERDO',$strqry);
  return $sXml;
  //LogMessage('ObtenerValidacionFechaAcuerdo - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerValidacionFechaAcuerdoModif(fechavenc, $nroorden, $nropago) 
{
  //LogMessage('ObtenerValidacionFechaAcuerdoModif - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $format.DateSeparator = '/';
  $format.ShortDateFormat = 'd/m/$y';

  $strqry= (
    ' SELECT distinct ca_nroorden '.
    '   FROM lca_acuerdocyq '.
    '  WHERE ca_nroorden = '.SqlValue($nroorden).
    '    AND '.SqlDate(strtodatedef(fechavenc,0,$format)).' IN (SELECT ca_fechavenc '.
    '                                     FROM lca_acuerdocyq '.
    '                                    WHERE ca_nroorden = '.SqlValue($nroorden).
    '                                      AND ca_nropago >0 '.
    '                                      AND ca_nropago <>'.SqlValue($nropago).')');

  $sXml  = '';
  $sXml  = CrearXml1('ACUERDO',$strqry);
  return $sXml;
  //LogMessage('ObtenerValidacionFechaAcuerdoModif - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerValidacionFechaCuotas(fechavenc,$cantcuotas,tiempo, $nroorden) 
{
  //LogMessage('ObtenerValidacionFechaCuotas - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $format.DateSeparator = '/';
  $format.ShortDateFormat = 'd/m/$y';

  $strqry= (
    ' SELECT art.legales.do_validar_cuotas('.
             SqlDate(strtodatedef(fechavenc,0,$format)).', '.
             SqlValue($cantcuotas).', '.
             SqlValue(tiempo).', '.
             SqlValue($nroorden).') valor'.
    '   FROM dual ');

  $sXml  = '';
  $sXml  = CrearXml1('CUOTA',$strqry);
  return $sXml;
  //LogMessage('ObtenerValidacionFechaCuotas - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarDesignaciones()  
{
  //LogMessage('CargarDesignaciones - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT tb_codigo, tb_descripcion '.
    '   FROM art.ctb_tablas '.
    '  WHERE tb_clave = ''PARTO'' '.
    '  ORDER BY tb_descripcion ');

  $sXml  = '';
  $sXml  = CrearXml2('DESIGNACIONES','DESIGNACION',$strqry);
  return $sXml;
  //LogMessage('CargarDesignaciones - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarRSocial(texto  )  
{
  //LogMessage('CargarRSocial - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT EM_CUIT, EM_NOMBRE '.
    '   FROM AFI.AEM_EMPRESA '.
    '  WHERE EM_NOMBRE like upper(''%'.texto.'%'')'.
    ' ORDER BY EM_NOMBRE');

  $sXml  = '';
  $sXml  = CrearXml2('RASOCIAL','RSOCIAL',$strqry);
  return $sXml;
  //LogMessage('CargarRSocial - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarJurisdiccion(idjurisdiccion : Integer)  
{
  //LogMessage('CargarJurisdiccion - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    '   SELECT ju_id, ju_descripcion, ju_fechabaja '.
    '     FROM legales.lju_jurisdiccion '.
    '    WHERE ju_fechabaja is null OR ju_id = '.SqlValue(idjurisdiccion).
    ' ORDER BY ju_descripcion '
  );

  $sXml  = '';
  $sXml  = CrearXml2('JURISDICCIONES','JURISDICCION',$strqry);
  return $sXml;
  //LogMessage('CargarJurisdiccion - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarTipoFiltro()  
{
  //LogMessage('CargarTipoFiltro - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    'SELECT tb_codigo, tb_descripcion '.
    '  FROM ctb_tablas '.
    ' WHERE tb_fechabaja IS NULL '.
    '   AND tb_clave = ''TACYQ'' '.
    '   AND (tb_especial1 = ''Q'' OR tb_especial1 = ''C'') '.
    ' ORDER BY tb_descripcion ');

  $sXml  = '';
  $sXml  = CrearXml2('TIPOS','TIPO',$strqry);
  return $sXml;
  //LogMessage('CargarTipoFiltro - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarTipo(concurso , value )  
{
  //LogMessage('CargarTipo - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    'SELECT tb_codigo, tb_descripcion '.
    '  FROM ctb_tablas '.
    ' WHERE tb_fechabaja IS NULL '.
    '   AND tb_clave = ''TACYQ'' ');

  if concurso then
    $strqry = $strqry + '   AND( tb_especial1 = ''C'' '
  else
    $strqry = $strqry + '   AND( tb_especial1 = ''Q'' ';
  if (value <> '') then
    $strqry = $strqry + '   OR tb_codigo = ' +SqlValue(value) .' ) '
  else
    $strqry = $strqry + ' ) ';

  $strqry = $strqry + ' ORDER BY tb_descripcion ';
  $sXml  = '';
  $sXml  = CrearXml2('TIPOS','TIPO',$strqry);
  return $sXml;
  //LogMessage('CargarTipo - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarMotivo()  
{
  //LogMessage('CargarMotivo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery(
      ' SELECT mc_id, mc_descripcion, mc_relacionnuevojuzgado '.
      '   FROM legales.lmc_motivocambiojuzgado '.
      '  WHERE mc_fechabaja IS NULL AND mc_id > 1 AND mc_etapa LIKE ''%J%'' '.
      ' ORDER BY mc_descripcion ');

    //LogMessage('CargarMotivo - Query ' + $qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);

    $sXml  = '';
    $sXml  = $sXml  + '<MOTIVOS>';
    while not $qry.Eof do
    {
      $sXml  = $sXml+ '<MOTIVO>';
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$qry.Fields[$i].AsString.'</'.$qry.Fields[$i].FieldName.'>';
      }
      $qry.Next;
      $sXml  = $sXml+ '</MOTIVO>';
    }
    $sXml  = $sXml  + '</MOTIVOS>';
    //LogMessage('CargarMotivo - Finalizo ' + $qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('CargarMotivo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  CargarFuero(jurisdiccion; idfuero: Integer)  
{
  //LogMessage('CargarFuero - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT fu_id, fu_descripcion, fu_fechabaja '.
    '   FROM legales.lfu_fuero '.
    '   WHERE (fu_fechabaja IS NULL OR fu_id = '.SqlValue(idfuero).')'.
    '     AND fu_id IN (SELECT jz_idfuero '.
    '                     FROM legales.ljz_juzgado '.
    '                    WHERE jz_idjurisdiccion = '.SqlValue(jurisdiccion).')'.
    ' ORDER BY fu_descripcion';

  //LogMessage('CargarFuero - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('FUEROS','FUERO',$strqry);
  return $sXml;
  //LogMessage('CargarFuero - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarFueroCYQ()  
{
  //LogMessage('CargarFueroCYQ - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT tb_codigo, tb_descripcion '.
    '   FROM ctb_tablas '.
    '  WHERE tb_especial1 IS NOT NULL '.
    '    AND tb_codigo <> ''0'' '.
    '    AND tb_fechabaja IS NULL '.
    '    AND tb_clave = ''FUERO'' '.
    'ORDER BY tb_descripcion ' );
  //LogMessage('CargarFueroCYQ - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('FUEROS','FUERO',$strqry);
  return $sXml;
  //LogMessage('CargarFueroCYQ - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarJurisdiccionCYQ()  
{
  //LogMessage('CargarJurisdiccionCYQ - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT tb_codigo, tb_descripcion'.
    '   FROM ctb_tablas '.
    '  WHERE tb_codigo <> ''0'' AND tb_fechabaja IS NULL AND tb_clave = ''JURIS'' '.
    ' ORDER BY tb_descripcion');
  //LogMessage('CargarJurisdiccionCYQ - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('JURISDICCIONES','JURISDICCION',$strqry);
  return $sXml;
  //LogMessage('CargarJurisdiccionCYQ - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarJuzgado(jurisdiccion,fuero; idJuzgado: Integer)  
{
  //LogMessage('CargarJuzgado - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT jz_id, jz_descripcion '.
    '   FROM legales.ljz_juzgado '.
    '  WHERE (jz_fechabaja IS NULL OR jz_id = '.SqlValue(idJuzgado).' )'.
    '    AND jz_idjurisdiccion = '.SqlValue(jurisdiccion).
    '    AND jz_idfuero = '.SqlValue(fuero).
    ' ORDER BY jz_descripcion';
  //LogMessage('CargarJuzgado - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('JUZGADOS','JUZGADO',$strqry);
  return $sXml;
  //LogMessage('CargarJuzgado - Finalizado',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarSecretaria(juzgado; idSecretaria: Integer)  
{
  //LogMessage('CargarSecretaria - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT sc_id, sc_descripcion '.
    '   FROM legales.lsc_secretaria '.
    '  WHERE (sc_fechabaja IS NULL OR sc_id = '.SqlValue(idSecretaria).')'.
    '    AND sc_idjuzgado = '.SqlValue(juzgado).
    ' ORDER BY sc_descripcion ';
  //LogMessage('CargarSecretaria - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('SECRETARIAS','SECRETARIA',$strqry);
  return $sXml;
  //LogMessage('CargarSecretaria - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarEstado(seleccionado : Integer)  
{
  //LogMessage('CargarEstado - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT ej_id, ej_descripcion '.
    '   FROM legales.lej_estadojuicio '.
    '  WHERE ej_etapa LIKE ''%J%'' '.
    '    AND EJ_ACTIVOWEB = ''S'' ');
  $strqry = $strqry+ ' AND ( ';
  if(seleccionado <> -1) then
      $strqry = $strqry + 'ej_id = '.SqlInt(seleccionado).' OR ';
  $strqry= $strqry + 'ej_fechabaja IS NULL) ';
  $strqry = $strqry + ' ORDER BY ej_descripcion ';
  //LogMessage('CargarEstado - Query - ' + $strqry, EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('ESTADOS','ESTADO',$strqry);
  return $sXml;
  //LogMessage('CargarEstado - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarEstadoParteActora(seleccionado : Integer)  
{
  //LogMessage('CargarEstadoParteActora - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT ej_id, ej_descripcion '.
    '   FROM legales.lej_estadojuicio '.
    '  WHERE ej_etapa LIKE ''%A%'' '.
    '    AND EJ_ACTIVOWEB = ''S'' ');
  $strqry = $strqry+ ' AND ( ';
  if(seleccionado <> -1) then
      $strqry = $strqry + 'ej_id = '.SqlInt(seleccionado).' OR ';
  $strqry= $strqry + 'ej_fechabaja IS NULL) ';
  $strqry = $strqry + ' ORDER BY ej_descripcion ';
  //LogMessage('CargarEstadoParteActora - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('ESTADOS','ESTADO',$strqry);
  return $sXml;
  //LogMessage('CargarEstadoParteActora - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarPeritos(peritodesignacion,$filtro)  
{
  //LogMessage('CargarPeritos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT pe_id, pe_nombre '.
    '   FROM legales.lpe_perito ';
  if(peritodesignacion <> '') then
    $strqry= $strqry + ' WHERE PE_PARTEOFICIO = '.SqlValue(peritodesignacion);
  if($filtro <>'') then
    $strqry= $strqry + ' AND PE_NOMBRE like upper(''%'.$filtro.'%'')';
  $strqry=$strqry + ' ORDER BY pe_nombre ';
  //LogMessage('CargarPeritos - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('PERITOS','PERITO',$strqry);
  return $sXml;
  //LogMessage('CargarPeritos - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarMedidasCautelares(seleccionado : Integer)  
{
  //LogMessage('CargarMedidasCautelares - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT TM_ID, TM_DESCRIPCION '.
    '   FROM legales.LTM_TIPOMEDIDAS ');
  $strqry = $strqry+ ' WHERE ( ';
  if(seleccionado <> -1) then
      $strqry = $strqry + 'TM_ID = '.SqlInt(seleccionado).' OR ';
  $strqry= $strqry + 'TM_FECHABAJA IS NULL) ';
  $strqry= $strqry + ' ORDER BY TM_DESCRIPCION ';
  //LogMessage('CargarMedidasCautelares - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
  $sXml  = '';
  $sXml  = CrearXml2('MEDIDASCAUTELARES','MEDIDACAUTELAR',$strqry);
  Result =$sXml;
  //LogMessage('CargarMedidasCautelares - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarEventos(seleccionado : Integer)  
{
  //LogMessage('CargarEventos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT te_id, te_descripcion '.
    '   FROM legales.lte_tipoevento ');
  $strqry = $strqry+ ' WHERE ( ';
  if(seleccionado <> -1) then
      $strqry = $strqry + 'te_id = '.SqlInt(seleccionado).' OR ';
  $strqry= $strqry + ' te_fechabaja IS NULL) ';
  $strqry= $strqry + ' AND ( te_id = '.SqlInt(seleccionado).'OR te_visibleweb = ''S'') ';
  $strqry= $strqry + ' AND TE_ETAPA like ''%J%'' AND TE_ID <> 1 ';
  $strqry = $strqry + ' ORDER BY te_descripcion ';
  //LogMessage('CargarEventos - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
  $sXml  = '';
  $sXml  = CrearXml2('EVENTOS','EVENTO',$strqry);
  Result =$sXml;
  //LogMessage('CargarEventos - Finalizar',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarEventosActora(seleccionado : Integer)  
{
  //LogMessage('CargarEventosActora - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT te_id, te_descripcion '.
    '   FROM legales.lte_tipoevento ');
  $strqry = $strqry+ ' WHERE ( ';
  if(seleccionado <> -1) then
      $strqry = $strqry + 'te_id = '.SqlInt(seleccionado).' OR ';
  $strqry= $strqry + 'te_fechabaja IS NULL)  ';
  $strqry= $strqry + ' AND TE_ETAPA like ''%A%'' AND TE_ID <> 1';
  $strqry = $strqry + ' ORDER BY te_descripcion ';
  //LogMessage('CargarEventosActora - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('EVENTOS','EVENTO',$strqry);
  Result =$sXml;
  //LogMessage('CargarEventosActora - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarTipoJuicio(seleccionado : Integer)  
{
  //LogMessage('CargarTipoJuicio - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    ' SELECT tj_id, tj_descripcion '.
    '   FROM legales.ltj_tipojuicio ');
  $strqry = $strqry+ ' WHERE ( ';
  if(seleccionado <> -1) then
      $strqry = $strqry + 'tj_id = '.SqlInt(seleccionado).' OR ';
  $strqry= $strqry + 'tj_fechabaja IS NULL) ';
  $strqry= $strqry + ' ORDER BY tj_descripcion ';
  //LogMessage('CargarTipoJuicio - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('JUICIOS','JUICIO',$strqry);
  Result =$sXml;
  //LogMessage('CargarTipoJuicio - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarTipoPericia 
{
  //LogMessage('CargarTipoPericia - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    '   SELECT tp_id, tp_descripcion '.
    '     FROM legales.ltp_tipopericia '.
    '    WHERE tp_fechabaja IS NULL '.
    ' ORDER BY tp_descripcion ');
  //LogMessage('CargarTipoPericia - Query - ' + $strqry, EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('TIPOSPERICIAS','TIPOPERICIA',$strqry);
  Result =$sXml;
  //LogMessage('CargarTipoPericia - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CargarEventosCYQ()  
{
  //LogMessage('CargarEventosCYQ - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry= (
    '   SELECT tb_codigo, tb_descripcion '.
    '     FROM ctb_tablas '.
    '    WHERE tb_codigo <> ''0'' AND tb_fechabaja IS NULL AND tb_clave = ''EVCYQ'' '.
    ' ORDER BY tb_descripcion ');
  //LogMessage('CargarEventosCYQ - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

  $sXml  = '';
  $sXml  = CrearXml2('EVENTOSCYQ','EVENTOCYQ',$strqry);
  Result =$sXml;
  //LogMessage('CargarEventosCYQ - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  CrearXml1(param,consulta  ) 
{
  //LogMessage('CrearXml1 - Inicio - ' + consulta,EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  $qry=$dmConn.GetQuery(consulta);
  //LogMessage('CrearXml1 - Query - ' + consulta,EVENTLOG_INFORMATION_TYPE,0,0);
  $sXml  = '';
  try {
    $sXml  = $sXml  + '<'.param.'>';
    while not $qry.Eof do
    {
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
        {
          $auxString = $qry.Fields[$i].AsString;
          ReplaceString($auxString,'&','&amp;');
          ReplaceString($auxString,'<','&lt;');
          ReplaceString($auxString,'>','&gt;');
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
        }
      }
      $qry.Next;
    }
    $sXml  = $sXml  + '</'.param.'>';
    //LogMessage('CrearXml1 - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.free;
    //LogMessage('CrearXml1 - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  CrearXmlTabla(param2,tabla,direccion,consulta  ; $pag  = 0) 
{
  //LogMessage('CrearXmlTabla - Inicio - ' + consulta,EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  $qry=$dmConn.GetQuery(consulta);
  //LogMessage('CrearXmlTabla - Query - ' + consulta,EVENTLOG_INFORMATION_TYPE,0,0);
  $sXml  = '';
  $x=0;
  try {
    $sXml  = $sXml  + '<'.tabla.' xmlns="'.direccion.'">';
    if $pag<>0 then
    {
    $y=0;
      while not $qry.Eof and ($y<$pag*9) do
      {
        $qry.Next;
        $y=$y+1;
      }
    }
    while not $qry.Eof and ($x<9) do
    {
      $sXml  = $sXml+ '<'.param2.'>';
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
        {
          $auxString = $qry.Fields[$i].AsString;
          ReplaceString($auxString,'&','&amp;');
          ReplaceString($auxString,'<','&lt;');
          ReplaceString($auxString,'>','&gt;');
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
        }
      }
      $qry.Next;
      $sXml  = $sXml+ '</'.param2.'>';
      $x=$x+1;
    }
    $sXml  = $sXml  + '</'.tabla.'>';
   //LogMessage('CrearXmlTabla - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.Free;
    //LogMessage('CrearXmlTabla - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  CrearXml2(param, param2, consulta ) 
{
  //LogMessage('CrearXml2 - Inicio - ' + consulta,EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);

  $qry=$dmConn.GetQuery(consulta);
  //LogMessage('CrearXml2 - Query - ' + consulta,EVENTLOG_INFORMATION_TYPE,0,0);
  $sXml  = '';
  try {
    $sXml  = $sXml  + '<'.param.'>';
    while not $qry.Eof do
    {
      $sXml  = $sXml+ '<'.param2.'>';
      for $i=0 to $qry.Fields.Count - 1 do
      {
        if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
          if  not ($qry.Fields[$i].AsString = '')   then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
        else
        {
          $auxString = $qry.Fields[$i].AsString;
          ReplaceString($auxString,'&','&amp;');
          ReplaceString($auxString,'<','&lt;');
          ReplaceString($auxString,'>','&gt;');
          $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$auxString.'</'.$qry.Fields[$i].FieldName.'>';
        }
      }
      $qry.Next;
      $sXml  = $sXml+ '</'.param2.'>';
    }
    $sXml  = $sXml  + '</'.param.'>';
    //LogMessage('CrearXml2 - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $qry.free;
    return $sXml;
    $dmConn.Free;
    //LogMessage('CrearXml2 - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ValidarModificacionReclamo(id_reclamo, $instancia   );
{
  //LogMessage('ValidarModificacionReclamo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $nroPago = $dmConn.ValorSqlInteger('SELECT ir_nropago FROM legales.lir_importesreguladosjuicio ' +
                               'WHERE ir_idreclamojuicioentramite = ' +
                                      SqlValue(id_reclamo) +
                               '  AND ir_idinstancia = ' + SqlValue($instancia));
    return $nroPago;
    //LogMessage('ValidarModificacionReclamo - Finalizo - ' + $instancia,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('ValidarModificacionReclamo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ValidarModificacionimporte(id_importe   );
{
  //LogMessage('ValidarModificacionimporte - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $nroPago = $dmConn.ValorSqlInteger(' SELECT ir_nropago '.
                               '   FROM legales.lir_importesreguladosjuicio irj '.
                               '  WHERE ir_id = ' + SqlValue(id_importe));
    return $nroPago;
    //LogMessage('ValidarModificacionimporte - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('ValidarModificacionimporte - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateSentencia($txtfechasentencia,$txtfecharecep, $jt_sentencia, $cmbsentencia,  $usuario, $jt_id, 
$txtimportehonorarios, $txtimporteintereses, $txtimportetasajusticia,$instancia,$txtMontoCondena, $txtPorcentajeIncapacidad  );
{
  //LogMessage('UpdateSentencia - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt($jt_id);
    $idsentencia = StrToInt($cmbsentencia);
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $importeCondena = StrToFloatDef($txtMontoCondena, 0, $format);
    $porcentajeIncapacidad = StrToFloatDef($txtPorcentajeIncapacidad, 0, $format);

    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';

    try {
      $sSQL =
      ' DECLARE '.
      '   detallesentencia varchar2(32767); '.
      ' { '.
      '   detallesentencia = :dts; '.
      ' UPDATE legales.ljt_juicioentramite '.
         ' SET jt_idtiporesultadosentencia = '.SqlInt($idsentencia).', '.
             ' jt_fechasentencia = '.SqlDate($txtfechasentencia).', '.
             ' jt_detallesentencia = detallesentencia,'.
             ' JT_MONTOCONDENA = '.SqlNumber($importeCondena) .', '.
             ' JT_PORCENTAJEINCAPACIDAD = '.SqlNumber($porcentajeIncapacidad) .', '.
             ' jt_usumodif = '.SqlValue($usuario).', '.
             ' jt_fechamodif = SYSDATE ,'.
             ' jt_fecharecepsentencia = '.SqlDate($txtfecharecep).
       ' WHERE $jt_id = '.SqlInt($id).';'.
       ' }' ;
      //LogMessage('UpdateSentencia - Query - ' + $sSQL,EVENTLOG_INFORMATION_TYPE,0,0);
      $dmConn.EjecutarSqlEx($sSQL,[$jt_sentencia]);
      //LogMessage('UpdateSentencia - Corrio - ' + $sSQL,EVENTLOG_INFORMATION_TYPE,0,0);

      $sSQL =
      ' DECLARE '.
      '    detallesentencia VARCHAR2(32767); '.
      ' { '.
      '   detallesentencia = :dts; '.
      ' UPDATE legales.lij_instanciajuicioentramite '.
         ' SET ij_idtiporesultadosentencia = '.SqlInt($idsentencia).', '.
             ' ij_fechasentencia = '.SqlDate($txtfechasentencia).', '.
             ' ij_MONTOCONDENA = '.SqlNumber($importeCondena) .', '.
             ' ij_PORCENTAJEINCAPACIDAD = '.SqlNumber($porcentajeIncapacidad) .', '.
             ' ij_usumodif = '.SqlValue($usuario).', '.
             ' ij_fechamodif = SYSDATE ,'.
             ' ij_detallesentencia = detallesentencia, '.
             ' ij_fecharecepsentencia = '.SqlDate($txtfecharecep).
       ' WHERE ij_idjuicioentramite = '.SqlInt($id).
       '   AND ij_id = '.SqlValue($instancia).';'.
       ' }';

      //LogMessage('UpdateSentencia - Query - ' + $sSQL,EVENTLOG_INFORMATION_TYPE,0,0);
      $dmConn.EjecutarSqlEx($sSQL,[$jt_sentencia]);
      //LogMessage('UpdateSentencia - Corrio - ' + $sSQL,EVENTLOG_INFORMATION_TYPE,0,0);
      //LogMessage('UpdateSentencia - Finalizado',EVENTLOG_INFORMATION_TYPE,0,0);
    } catch (Exception $e) {
      on e: Exception do
      {
        LogMessage('UpdateSentencia - Error - ' + e.Message,EVENTLOG_ERROR_TYPE,0,0);
        raise Exception.Create('Error! ' + e.message);
      }
    }
   finally {
    {
      $dmConn.Free;
      //LogMessage('UpdateSentencia - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
    }
  }
}

function  UpdateSentenciaParteActora($txtfechasentencia,$jt_sentencia, $cmbsentencia,  $usuario, 
	$jt_id, $txtimportehonorarios, $txtimporteintereses, $txtimportetasajusticia,txtImporteCapital,$instancia  );
{
  //LogMessage('UpdateSentenciaParteActora - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt($jt_id);
    $idsentencia = StrToInt($cmbsentencia);

    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $importehonorarios = StrToFloatDef($txtimportehonorarios, 0, $format);
    $importeintereses = StrToFloatDef($txtimporteintereses, 0, $format);
    $importetasajusticia = StrToFloatDef($txtimportetasajusticia, 0, $format);
    $importeCapital = StrToFloatDef(txtImporteCapital, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';

    try {
      $sSQL =
              ' DECLARE '.
              '   detallesentencia VARCHAR2(32767); '.
              ' { '.
              ' detallesentencia = :dts; '.
              ' UPDATE legales.ljt_juicioentramite '.
                 ' SET jt_idtiporesultadosentencia = '.SqlInt($idsentencia).', '.
                     ' jt_fechasentencia = '.SqlDate($txtfechasentencia).', '.
                     ' jt_importecapital = '.SqlNumber($importeCapital) .', '.
                     ' jt_importehonorarios = '.SqlNumber($importehonorarios) .', '.
                     ' jt_interesessentencia = '.SqlNumber($importeintereses) .', '.
                     ' jt_importetasajusticia = '.SqlNumber($importetasajusticia) .', '.
                     ' jt_importesentencia = '.SqlNumber($importetasajusticia).
                                            ' + ' + SqlNumber($importeCapital) + ' + '.
                                            SqlNumber($importehonorarios). ' + ' +
                                            SqlNumber($importeintereses).', '.
                     ' jt_usumodif = '.SqlValue($usuario).', '.
                     ' jt_detallesentencia = detallesentencia,'.
                     ' jt_fechamodif = SYSDATE '.
               ' WHERE $jt_id = '.SqlInt($id).'; }';
      //LogMessage('UpdateSentenciaParteActora - Query - ' + $sSQL,EVENTLOG_INFORMATION_TYPE,0,0);
      $dmConn.EjecutarSqlEx($sSQL,[$jt_sentencia]);
      //LogMessage('UpdateSentenciaParteActora - Corrio - ' + $sSQL,EVENTLOG_INFORMATION_TYPE,0,0);

      $sSQL =
              ' DECLARE '.
              '   detallesentencia VARCHAR2(32767); '.
              ' { '.
              '   detallesentencia = :dts; '.
              ' UPDATE legales.lij_instanciajuicioentramite '.
                 ' SET ij_idtiporesultadosentencia = '.SqlInt($idsentencia).', '.
                     ' ij_fechasentencia = '.SqlDate($txtfechasentencia).', '.
                     ' IJ_IMPORTECAPITAL = '.SqlNumber($importeCapital) .', '.
                     ' ij_importehonorarios = '.SqlNumber($importehonorarios) .', '.
                     ' ij_interesessentencia = '.SqlNumber($importeintereses) .', '.
                     ' IJ_IMPORTETASAJUSTICIA = '.SqlNumber($importetasajusticia) .', '.
                     ' ij_importesentencia = '.SqlNumber($importetasajusticia).
                                              ' + ' + SqlNumber($importeCapital) + ' + '.
                                              SqlNumber($importehonorarios). ' + ' +
                                              SqlNumber($importeintereses).', '.
                     ' ij_usumodif = '.SqlValue($usuario).', '.
                     ' ij_detallesentencia = detallesentencia, '.
                     ' ij_fechamodif = SYSDATE '.
               ' WHERE ij_idjuicioentramite = '.SqlInt($id).
               '   AND ij_id = '.SqlValue($instancia).'; }';
      //LogMessage('UpdateSentenciaParteActora - Query - ' + $sSQL,EVENTLOG_INFORMATION_TYPE,0,0);
      $dmConn.EjecutarSqlEx($sSQL,[$jt_sentencia]);
      //LogMessage('UpdateSentenciaParteActora - Corrio - ' + $sSQL,EVENTLOG_INFORMATION_TYPE,0,0);
      //LogMessage('UpdateSentenciaParteActora - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
    } catch (Exception $e) {
    on e: Exception do
      {
        LogMessage('UpdateSentenciaParteActora - Error - ' + e.Message, EVENTLOG_ERROR_TYPE,0,0);
        raise Exception.Create('Error! ' + e.message);
      }
    }
   finally {
    {
      $dmConn.Free;
      //LogMessage('UpdateSentenciaParteActora - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
    }
  }
}

function  UpdateInstanciaAbmMod(JuicioEnTramite, Jurisdiccion, Fuero, Juzgado, Secretaria, $instancia, $nroExpediente, AnioExpediente, Motivo, Detalle, LoginName, $nroInstancia,EstadoMediacion ; FechaIngreso  );
{
  //LogMessage('UpdateInstanciaAbmMod - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $dmConn.EjecutarSql(
      ' UPDATE legales.lij_instanciajuicioentramite' +
      '    SET ij_nroexpediente = ' + SqlValue($nroExpediente) + ',' +
      '        ij_anioexpediente = ' + SqlValue(AnioExpediente) + ',' +
      '        ij_fechatraspaso = ' + SqlDate(FechaIngreso) + ',' +
      '        ij_usumodif = ' + SqlValue(LoginName) + ',' +
      '        ij_idjurisdiccion =  ' + SqlValue(Jurisdiccion) + ',' +
      '        ij_idfuero = ' + SqlValue(Fuero) + ',' +
      '        ij_idjuzgado = ' + SqlValue(Juzgado) + ',' +
      '        ij_idsecretaria = ' + SqlValue(Secretaria) + ',' +
      '        ij_observaciones = ' + SqlValue(Detalle) +  ' , ' +
      '        ij_idmotivocambiojuzgado = ' + SqlValue(Motivo) + ', ' +
      '        ij_idinstancia = ' + SqlValue($instancia) + ', ' +
      '        ij_fechamodif = sysdate' +
      '  WHERE ij_id = ' + SqlValue($nroInstancia));
    //LogMessage('UpdateInstanciaAbmMod - Query',EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql(
      ' UPDATE legales.ljt_juicioentramite ' +
      '    SET jt_idjurisdiccion =  ' + SqlValue(Jurisdiccion)  + ', ' +
      '        jt_idfuero = '  + SqlValue(Fuero)  + ', ' +
      '        jt_idjuzgado = ' + SqlValue(Juzgado) + ', ' +
      '        jt_idsecretaria = ' + SqlValue(Secretaria) + ', ' +
      '        jt_nroexpediente = ' + SqlValue($nroExpediente) + ', ' +
      '        jt_anioexpediente = ' + SqlValue(AnioExpediente) + ', ' +
      '        jt_fechaingreso = ' + SqlDate(FechaIngreso) + ', ' +
      '        jt_usumodif = ' + SqlValue(LoginName) + ', ' +
      '        jt_estadomediacion = '.SqlValue(EstadoMediacion) + ', ' +
      '        jt_fechamodif = Sysdate ' +
      '  WHERE $jt_id = ' + SqlValue(JuicioEnTramite));
    //LogMessage('UpdateInstanciaAbmMod - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateInstanciaAbmMod - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateInstanciaJuicio(JuicioEnTramite, Jurisdiccion, Fuero, Juzgado,
          Secretaria, $instancia, $nroExpediente, AnioExpediente, LoginName, $nroInstancia,EstadoMediacion );
{
  //LogMessage('UpdateInstanciaJuicio - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $dmConn.EjecutarSql(
      ' UPDATE legales.lij_instanciajuicioentramite' +
      '    SET ij_nroexpediente = ' + SqlValue($nroExpediente) + ',' +
      '        ij_anioexpediente = ' +SqlValue(AnioExpediente) + ',' +
      '        ij_usumodif = ' + SqlValue(LoginName) + ',' +
      '        ij_idjurisdiccion =  ' + SqlValue(Jurisdiccion) + ',' +
      '        ij_idfuero = ' + SqlValue(Fuero) + ',' +
      '        ij_idjuzgado = ' + SqlValue(Juzgado) + ',' +
      '        ij_idsecretaria = ' + SqlValue(Secretaria) + ',' +
      '        ij_idinstancia = ' + SqlValue($instancia) + ', ' +
      '        ij_fechamodif = sysdate' +
      '  WHERE ij_idinstancia = ' + SqlValue($instancia).
      '    AND ij_idjuicioentramite = '.SqlValue(JuicioEnTramite));
    //LogMessage('UpdateInstanciaJuicio - Query',EVENTLOG_INFORMATION_TYPE,0,0);

   $dmConn.EjecutarSql(
      ' UPDATE legales.ljt_juicioentramite ' +
      '    SET jt_idjurisdiccion =  ' + SqlValue(Jurisdiccion)  + ', ' +
      '        jt_idfuero = '  + SqlValue(Fuero)  + ', ' +
      '        jt_idjuzgado = ' + SqlValue(Juzgado) + ', ' +
      '        jt_idsecretaria = ' + SqlValue(Secretaria) + ', ' +
      '        jt_nroexpediente = ' + SqlValue($nroExpediente) + ', ' +
      '        jt_anioexpediente = ' + SqlValue(AnioExpediente) + ', ' +
      '        jt_usumodif = ' + SqlValue(LoginName) + ', ' +
      '        jt_estadomediacion = '.SqlValue(EstadoMediacion) + ', ' +
      '        jt_fechamodif = Sysdate ' +
      '  WHERE $jt_id = ' + SqlValue(JuicioEnTramite));
    //LogMessage('UpdateInstanciaJuicio - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateInstanciaJuicio - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateInstanciaABMAlta(JuicioEnTramite, Jurisdiccion, Fuero, Juzgado, Secretaria, $instancia, $nroExpediente, AnioExpediente, Motivo, Detalle, LoginName, EstadoMediacion ; FechaIngreso  );
{
  //LogMessage('UpdateInstanciaABMAlta - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $dmConn.EjecutarSql(
      ' INSERT INTO legales.lij_instanciajuicioentramite (          ' +
      '               ij_id, ij_idjuicioentramite, ij_idjurisdiccion,  ' +
      '               ij_idfuero, ij_idjuzgado, ij_idsecretaria, ij_idinstancia, ' +
      '               ij_nroexpediente, ij_anioexpediente, ij_fechatraspaso, ' +
      '               ij_idmotivocambiojuzgado, ij_observaciones, ij_usualta,    ' +
      '               ij_fechaalta)  ' +
      '      VALUES (LEGALES.SEQ_LIJ_ID.NEXTVAL, ' +
                     SqlValue(JuicioEnTramite) + ', ' +
                     SqlValue(Jurisdiccion)  + ', ' +
                     SqlValue(Fuero)  + ', ' +
                     SqlValue(Juzgado) + ', ' +
                     SqlValue(Secretaria) + ', ' +
                     SqlValue($instancia) + ', ' +
                     SqlValue($nroExpediente) + ', ' +
                     SqlValue(AnioExpediente) + ', ' +
                     SqlDate(FechaIngreso) + ', ' +
                     SqlValue(Motivo) + ', ' +
                     SqlValue(Detalle) +  ' , ' +
                     SqlValue(LoginName) + ' ,Sysdate ' + ')');
    //LogMessage('UpdateInstanciaABMAlta - Query',EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql(
      ' UPDATE legales.ljt_juicioentramite ljt ' +
      '    SET jt_importesentencia = NULL, ' +
      '        jt_importecapital = NULL, ' +
      '        jt_importetasajusticia = NULL, ' +
      '        jt_importehonorarios = NULL, ' +
      '        jt_idtiporesultadosentencia = NULL, ' +
      '        jt_fechasentencia = NULL, ' +
      '        jt_fecharecepsentencia = NULL, '.
      '        jt_detallesentencia = NULL, ' +
      '        jt_interesesSentencia = NULL ' +
      '  WHERE ljt.$jt_id = ' + SqlValue(JuicioEnTramite));
    //LogMessage('UpdateInstanciaABMAlta - Query1',EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql(
      ' UPDATE legales.lrt_reclamojuicioentramite ' +
      '    SET rt_montosentencia = NULL, ' +
      '        rt_porcentajesentencia = NULL, ' +
      '        rt_usumodif = ' + SqlValue(LoginName) + ', ' +
      '        rt_fechamodif = SysDate ' +
      '  WHERE rt_idjuicioentramite = ' + SqlValue(JuicioEnTramite));
    //LogMessage('UpdateInstanciaABMAlta - Query2',EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql(
      ' UPDATE legales.ljt_juicioentramite ' +
      '    SET jt_idjurisdiccion =  ' + SqlValue(Jurisdiccion)  + ', ' +
      '        jt_idfuero = '  + SqlValue(Fuero)  + ', ' +
      '        jt_idjuzgado = ' + SqlValue(Juzgado) + ', ' +
      '        jt_idsecretaria = ' + SqlValue(Secretaria) + ', ' +
      '        JT_NROEXPEDIENTE = ' + SqlValue($nroExpediente) + ', ' +
      '        JT_ANIOEXPEDIENTE = ' + SqlValue(AnioExpediente) + ', ' +
      '        jt_fechaingreso = ' + SqlDate(FechaIngreso) + ', ' +
      '        jt_usumodif = ' + SqlValue(LoginName) + ', ' +
      '        jt_estadomediacion = '.SqlValue(EstadoMediacion) + ', ' +
      '        jt_fechamodif = Sysdate ' +
      '  WHERE $jt_id = ' + SqlValue(JuicioEnTramite));
    //LogMessage('UpdateInstanciaABMAlta - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateInstanciaABMAlta - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateMasDatosJuicios(txtDomicilio, txtTelefonos,
  txtFax, txtEmail, $usuario,
  $idJuicio);
{
  //LogMessage('UpdateMasDatosJuicios - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    idJuzgado = $dmConn.ValorSql( ' SELECT jt_idjuzgado FROM legales.ljt_juicioentramite '.
                           '  WHERE $jt_id = '.SqlValue($idJuicio));
    //LogMessage('UpdateMasDatosJuicios - Query Juzgado',EVENTLOG_INFORMATION_TYPE,0,0);
    $strqry=
      ' UPDATE legales.ljz_juzgado '.
      '    SET jz_usumodif = '.SqlValue($usuario).','.
      '        jz_fechamodif = SYSDATE, '.
      '        jz_direccion = '.SqlValue(txtDomicilio).', '.
      '        jz_telefono = '.SqlValue(txtTelefonos).', '.
      '        jz_fax = '.SqlValue(txtFax).', '.
      '        jz_email = '.SqlValue(txtEmail).
      '  WHERE jz_id = '.SqlValue(idJuzgado);
    //LogMessage('UpdateMasDatosJuicios - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('UpdateMasDatosJuicios - Finalizo - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateMasDatosJuicios - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateClaveUsuario($nrousuario,clave);
{
  //LogMessage('UpdateClaveUsuario - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $dmConn.EjecutarSql(
      ' UPDATE legales.lnu_nivelusuario '.
      '    SET nu_claveweb = '.SqlValue(clave).', '.
      '        nu_forzarclave = ''N'' '.
      '  WHERE nu_id = '.SqlValue($nrousuario) );
    //LogMessage('UpdateClaveUsuario - Finalizo - '  + $nrousuario,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateClaveUsuario - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}


function  UpdatePeritajes(pj_id, $usuario ):Boolean;
{
  //LogMessage('UpdatePeritajes - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt(pj_id);
    if $dmConn.ExisteSql(
      ' SELECT 1 '.
      '   FROM legales.lpj_peritajejuicio '.
      '  WHERE pj_fechabaja IS NULL '.
      '    AND pj_id = '.Sqlint($id).
      '    AND pj_usualta = '.SqlValue($usuario)) then
    {
      $dmConn.EjecutarSql(
        ' UPDATE legales.lpj_peritajejuicio '.
        '    SET pj_fechabaja = SYSDATE, '.
        '        pj_usubaja = ' +  SqlValue($usuario) +
        '  WHERE pj_fechabaja IS NULL AND pj_id = ' + SqlInt($id) );
      return True;
    end
    else
      return False;
    //LogMessage('Desconecto - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //return True;
    //LogMessage('Desconecto - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateImportes(ir_id, $usuario );
{
  //LogMessage('UpdateImportes - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt(ir_id);
    $dmConn.EjecutarSql(
      ' UPDATE legales.lir_importesreguladosjuicio '.
      '    SET ir_fechabaja = SYSDATE, '.
      '        ir_usubaja = '.SqlValue($usuario) +
      '  WHERE ir_fechabaja IS NULL AND ir_id = ' + SqlInt($id));
  //LogMessage('UpdateImportes - Finalizo - ' + ir_id,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateImportes - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateImporteABM(ir_id,$txtImporte, detalle, $usuario ,aplicado);
{
  //LogMessage('UpdateImporteABM - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt(ir_id);
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $importe= StrToFloatDef($txtImporte, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';

    $dmConn.EjecutarSql(
      ' UPDATE legales.lir_importesreguladosjuicio '.
      '    SET ir_importesentencia = '.SqlNumber($importe) .','.
      '        ir_detalleweb = '.SqlValue(detalle) .','.
      '        ir_usumodif = '.SqlValue($usuario) .','.
      '        ir_fechamodif = SYSDATE, '.
      '        ir_aplicacion = '.SqlValue(aplicado).
      '  WHERE ir_id = '.SqlInt($id));
    //LogMessage('UpdateImporteABM - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateImporteABM - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  InsertarEventoNuevo(txtfecha  ; txtfechavencimiento, $txtobservaciones ,$nrojuicio, $usuario, cmbEventos  );
{
  //LogMessage('InsertarEventoNuevo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    try {
      $eventoid= $dmConn.GetSecNextVal('legales.seq_let_id');
      $format.DateSeparator = '/';
      $format.ShortDateFormat = 'd/m/$y';
      $nro_juicio = StrToInt($nrojuicio);
      idtipoevento = StrToInt(cmbEventos);
      ReplaceString($txtobservaciones,'<','');
      ReplaceString($txtobservaciones,'>','');
      $strqry=
        'DECLARE '.
        '  observacion varchar2(32767); '.
        ' { '.
        ' observacion = :obs; '.
        ' INSERT INTO legales.let_eventojuicioentramite '.
        '             ($et_id, et_fechaevento, et_fechavencimiento, '.
        '              et_idjuicioentramite, et_fechaalta, et_usualta, et_fechamodif, '.
        '              et_usumodif, et_fechabaja, et_usubaja, et_idtipoevento, et_observaciones '.
        '             ) '.
        ' VALUES ( '.SqlInt($eventoid).','.SqlDate(txtfecha) .',';


      if txtfechavencimiento <>'' then
        $strqry= $strqry+ SqlDate(strtodatedef(txtfechavencimiento,0,$format)) .','
      else
        $strqry = $strqry + 'NULL , ';
      $strqry= $strqry+ SqlInt($nro_juicio).',SYSDATE,'.SqlValue($usuario) .', NULL, '.
               ' NULL, NULL, NULL,'.SqlInt(idtipoevento).',observacion'.
               ' ); '.
      '} ';

      //LogMessage('InsertarEventoNuevo - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
      $dmConn.EjecutarSqlEx($strqry,[$txtobservaciones]);
      //LogMessage('InsertarEventoNuevo - Ejecuto - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);

    } catch (Exception $e) {
      on e: Exception do
      {
        LogMessage('InsertarEventoNuevo - Error - ' + e.message,EVENTLOG_ERROR_TYPE,0,0);
        raise Exception.Create('Error! ' + e.message);
      }
    }
    //LogMessage('InsertarEventoNuevo - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarEventoNuevo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  InsertarEventoCYQNuevo(txtfecha  ; $txtobservaciones , $usuario, cmbEventos,$nroorden  );
{
  //LogMessage('InsertarEventoCYQNuevo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery(
      ' SELECT iif_compara (''<='', '.
      '                     NVL (MAX (ce_nroevento), 0), '.
      '                     0, '.
      '                     1, '.
      '                     NVL (MAX (ce_nroevento), 0) + 1 '.
      '                    ) '.
      '   FROM lce_eventocyq '.
      '  WHERE ce_nroorden = '.SqlValue($nroorden));
    $nroevento= $qry.Fields[0].AsString;
    //LogMessage('InsertarEventoCYQNuevo - Query - ' + $qry.SQL.Text, EVENTLOG_INFORMATION_TYPE,0,0);

    $strqry=
      ' INSERT INTO lce_eventocyq '.
      '        (ce_nroorden, ce_nroevento, ce_usualta, ce_fechaalta, '.
      '         ce_codevento, ce_fecha, ce_observaciones '.
      '        ) '.
      ' VALUES ('.SqlValue($nroorden).','.SqlValue($nroevento).', '.SqlValue($usuario).', SYSDATE, '.
              SqlValue(cmbEventos).', '.SqlDate(txtfecha).', '.SqlValue($txtobservaciones).
      '      ) ';

    //LogMessage('InsertarEventoCYQNuevo - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('InsertarEventoCYQNuevo - Finalizo - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarEventoCYQNuevo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  InsertarAcuerdoNuevo(txtfechavenc  ; txtmonto, txtfechapago, $txtobservaciones, $usuario,$nroorden, txtFechaExtincion, cmbTipo );
{
  //LogMessage('InsertarAcuerdoNuevo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $monto= StrToFloatDef(txtmonto, 0, $format);
    $format.DateSeparator = '/';
    $format.ShortDateFormat = 'd/m/$y';
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';
    $qry= $dmConn.GetQuery(
      ' SELECT IIF_Compara( ''<='', NVL(Max(CA_NROPAGO), 0), 0, 1, NVL(Max(CA_NROPAGO), 0) + 1) FROM LCA_ACUERDOCYQ WHERE CA_NROORDEN = '.SqlValue($nroorden));
      $nropago= $qry.Fields[0].AsString;
    //LogMessage('InsertarAcuerdoNuevo - Query - ' + $qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);

    $strqry=
      'INSERT INTO LCA_ACUERDOCYQ '.
      '           (CA_NROORDEN, CA_NROPAGO, CA_USUALTA, '.
      '            CA_FECHAALTA, CA_FECHAPAGO, CA_FECHAVENC, CA_MONTO, '.
      '            CA_OBSERVACIONES, CA_FECHAEXTINCION, CA_TIPO) '.
      ' VALUES ('.SqlValue($nroorden).','.SqlValue($nropago).', '.SqlValue($usuario).', SYSDATE, ';
    if txtfechapago <>'' then
      $strqry= $strqry+SqlDate(strtodatedef(txtfechapago,0,$format)) .','
    else
      $strqry = $strqry .' NULL , ';
      $strqry = $strqry +SqlDate(txtfechavenc).', '.SqlNumber($monto).', '.SqlValue($txtobservaciones).', ';
    if txtFechaExtincion <>'' then
      $strqry= $strqry + SqlDate(strtodatedef(txtFechaExtincion,0,$format)) .','
    else
      $strqry = $strqry .' NULL ,';
    $strqry = $strqry + SqlValue(cmbTipo).')';
    //LogMessage('InsertarAcuerdoNuevo - Query - ' + $strqry, EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql($strqry);
    //LogMessage('InsertarAcuerdoNuevo - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarAcuerdoNuevo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  InsertarCuotas(txtfecha1  ; $cantcuota, tiempo, txtmonto, $usuario, $nroorden, cmbTipo );
{
  //LogMessage('InsertarCuotas - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $monto= StrToFloatDef(txtmonto, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';
    $dmConn.EjecutarSql(
     '{ ART.LEGALES.Do_PlanCyQ ( '.SqlDate(txtfecha1).', '.SqlValue($cantcuota).', '.
      SqlValue(tiempo).', '.SqlNumber($monto).', '.
      SqlValue($nroorden).', '.SqlValue($usuario).', '.SqlValue(cmbTipo).'); } ');
    //LogMessage('InsertarCuotas - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarCuotas - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}


function  InsertarEventoArchivo( listarchivoagregado, nombrearchivo, $idevento, $usuario );
{
  //LogMessage('InsertarEventoArchivo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $eventoid= StrToInt($idevento);
    $dmConn.EjecutarSql(
      ' INSERT INTO legales.lea_eventoarchivoasociado '.
      '             (ea_id, ea_descripcion, ea_patharchivo, '.
      '              ea_ideventojuicioentramite, ea_usualta, ea_fechaalta '.
      '             ) '.
      '      VALUES ( legales.seq_lea_id.NEXTVAL, '.SqlValue(listarchivoagregado).', '.SqlValue(nombrearchivo).', '.
                      SqlInt($eventoid).', '.SqlValue($usuario).', SYSDATE )');
    //LogMessage('InsertarEventoArchivo - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarEventoArchivo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  InsertarPeritajeNuevo($txtFechaAsignacion, $txtFechaPericia,
                    $txtFVencImpug, $cmbPericia, $txtResultados, $nrojuicio, $usuario,
                    $incapacidadDemanda, $incapacidadPeritoMedico, $ibmArt, $ibmPericial, $impugnacion,$idperito  );
{
  //LogMessage('InsertarPeritajeNuevo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $peritajeid= $dmConn.GetSecNextVal('legales.seq_lpj_id');
    $nro_juicio = StrToInt($nrojuicio);
    $format.DateSeparator = '/';
    $format.ShortDateFormat = 'd/m/$y';
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $montodemanda= StrToFloatDef($incapacidadDemanda, 0, $format);
    $montomedico = StrToFloatDef($incapacidadPeritoMedico, 0, $format);
    $montoart = StrToFloatDef($ibmArt, 0, $format);
    $montopericial = StrToFloatDef($ibmPericial, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';

    $strqry =
      ' INSERT INTO legales.lpj_peritajejuicio '.
      '             (pj_id, PJ_IDPERITO,pj_fechanotificacion, pj_fechaperitaje, pj_fechavencimpugnacion, '.
      '              pj_resultadoperitaje, pj_fechaalta, pj_usualta, pj_idjuicioentramite, '.
      '              pj_idtipopericia, pj_incapacidaddemanda, pj_incapacidadperitomedico, pj_ibmart, '.
      '              pj_ibmpericial, pj_impugnacion '.
      '             ) '.
      '      VALUES ( '.
                    SqlInt($peritajeid).','.SqlValue($idperito).',';
      if $txtFechaAsignacion <>'' then
        $strqry = $strqry + SqlDate(strtodatedef($txtFechaAsignacion,0,$format)) + ','
      else
        $strqry = $strqry + 'NULL,';
      if $txtFechaPericia <>'' then
        $strqry = $strqry + SqlDate(strtodatedef($txtFechaPericia,0,$format)) + ','
      else
        $strqry = $strqry + 'NULL,';
      if $txtFVencImpug <>'' then
        $strqry = $strqry + SqlDate(strtodatedef($txtFVencImpug,0,$format)) + ','
      else
        $strqry = $strqry + 'NULL,';


        $strqry = $strqry + SqlValue($txtResultados) +  ',' +
                 ' SYSDATE, '.SqlValue($usuario) + ' , '.
                  SqlInt($nro_juicio) +  ',' +
                  SqlValue($cmbPericia) +  ',' +
                  SqlValue($montodemanda). ','.
                  SqlValue($montomedico). ','.
                  SqlValue($montoart). ','.
                  SqlValue($montopericial). ','.
                  SqlValue($impugnacion). ' ) '  ;
    //LogMessage('InsertarPeritajeNuevo - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('InsertarPeritajeNuevo - Finalizo - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarPeritajeNuevo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  InsertarMedidaCautelar($importe, $txtImporte, $txtObservacion, $nrojuicio, $usuario  );
{
  //LogMessage('InsertarMedidaCautelar - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $mcid = $dmConn.GetSecNextVal('LEGALES.SEQ_LMC_ID');
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $importe= StrToFloatDef($txtImporte, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';

    $strqry =
      ' INSERT INTO legales.lmc_medidascautelares '.
      '             (mc_id, mc_idtipomedida, mc_importe, mc_observaciones, '.
      '              mc_usualta, mc_fechaalta, mc_idjuicioentramite '.
      '             ) '.
      '      VALUES ( '.
                    SqlValue($mcid).','.
                    SqlValue($cmbMedida). ',' +
                    SqlValue($importe). ',' +
                    SqlValue($txtObservacion). ',' +
                    SqlValue($usuario). ',sysdate,' +
                    SqlValue($nrojuicio).' ) '  ;
    //LogMessage('InsertarMedidaCautelar - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('InsertarMedidaCautelar - Finalizo - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarMedidaCautelar - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateMedidaCautelarABM($cmbMedida, $txtImporte, $txtObservacion, $mcid, $usuario  );
{
  //LogMessage('UpdateMedidaCautelarABM - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $importe= StrToFloatDef($txtImporte, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';

    $strqry =
      ' UPDATE legales.lmc_medidascautelares '.
      '    SET mc_idtipomedida = '.SqlValue($cmbMedida).' , '.
      '        mc_importe = '.SqlValue($importe) .' , '.
      '        mc_observaciones = '.SqlValue($txtObservacion) +  ',' +
      '        mc_fechamodif = SYSDATE, '.
      '        mc_usumodif = '.SqlValue($usuario) + ' , '.
      '        mc_fechabaja = NULL, '.
      '        mc_usubaja = NULL '.
      '  WHERE mc_id = ' + SqlValue($mcid) ;
    //LogMessage('UpdateMedidaCautelarABM - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('UpdateMedidaCautelarABM - Finalizo - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateMedidaCautelarABM - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdatePeritajesABM($txtFechaAsignacion, $txtFechaPericia,
                    $txtFVencImpug, $cmbPericia, $txtResultados, pj_id, $usuario,
                    $incapacidadDemanda, $incapacidadPeritoMedico, $ibmArt, $ibmPericial, $impugnacion, $idperito  );
{
  //LogMessage('UpdatePeritajesABM - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt(pj_id);
    $format.DateSeparator = '/';
    $format.ShortDateFormat = 'd/m/$y';
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $montodemanda= StrToFloatDef($incapacidadDemanda, 0, $format);
    $montomedico = StrToFloatDef($incapacidadPeritoMedico, 0, $format);
    $montoart = StrToFloatDef($ibmArt, 0, $format);
    $montopericial = StrToFloatDef($ibmPericial, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';


    $strqry =
      ' UPDATE legales.lpj_peritajejuicio '.
      '    SET ';

      if $txtFechaAsignacion <>'' then
        $strqry= $strqry+ ' pj_fechanotificacion ='.SqlDate(strtodatedef($txtFechaAsignacion,0,$format)) .','
      else
        $strqry = $strqry .' pj_fechanotificacion = NULL , ';

      if $txtFechaPericia <>'' then
        $strqry= $strqry+ ' pj_fechaperitaje ='.SqlDate(strtodatedef($txtFechaPericia,0,$format)) .','
      else
        $strqry = $strqry .' pj_fechaperitaje = NULL , ';

      if $txtFVencImpug <>'' then
        $strqry= $strqry+ ' pj_fechavencimpugnacion ='.SqlDate(strtodatedef($txtFVencImpug,0,$format)) .','
      else
        $strqry = $strqry .' pj_fechavencimpugnacion = NULL , ';

      $strqry = $strqry + ' pj_resultadoperitaje = '.SqlValue($txtResultados) +  ',' +
              ' pj_fechamodif = SYSDATE, '.
              ' pj_usumodif = '.SqlValue($usuario) + ' , '.
              ' pj_fechabaja = NULL, '.
              ' pj_usubaja = NULL, '.
              ' pj_idperito = '.SqlValue($idperito).','.
              ' pj_idtipopericia = '.SqlValue($cmbPericia) +  ',' +
              ' pj_incapacidaddemanda = '.SqlValue($montodemanda) +  ',' +
              ' pj_incapacidadperitomedico = '.SqlValue($montomedico) +  ',' +
              ' pj_ibmart = '.SqlValue($montoart) +  ',' +
              ' pj_ibmpericial = '.SqlValue($montopericial) +  ',' +
              ' pj_impugnacion = '.SqlValue($impugnacion). ','.
              ' pj_completaestudio = ''N'' '.
       '  WHERE pj_id = ' + SqlInt($id) ;
    //LogMessage('UpdatePeritajesABM - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('UpdatePeritajesABM - Finalizo - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdatePeritajesABM - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  InsertarPeritoNuevo(nombre, apellido, cuil, tipoperito,parteoficio, $usuario, direccion );
{
  //LogMessage('InsertarPeritoNuevo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $dmConn.EjecutarSql(
      ' INSERT INTO legales.lpe_perito '.
      '             (pe_id, pe_nombre, pe_cuitcuil, pe_idtipoperito, '.
      '              pe_parteoficio, pe_usualta, pe_fechaalta,pe_direccion,  '.
      '              PE_NOMBREINDIVIDUAL, PE_APELLIDO ) '.
      '      VALUES (legales.seq_lpe_id.NEXTVAL, upper('.SqlValue(apellido.' '.nombre).'),'.SqlString(cuil,False,True).','.SqlValue(tipoperito).','.
                    SqlValue(parteoficio).','.SqlValue($usuario).', SYSDATE,'.SqlValue(direccion).','.
                    'upper('.SqlValue(nombre).'),upper('.SqlValue(apellido).')'.
      '             )');
    //LogMessage('InsertarPeritoNuevo - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarPeritoNuevo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}



function  InsertarImporteNuevo($nrojuicio, $usuario, $txtImporte,aplicado, detalle, detalleweb,$instancia );
{
  //LogMessage('InsertarImporteNuevo - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $nro= StrToInt($nrojuicio);
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $importe= StrToFloatDef($txtImporte, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';
    $dmConn.EjecutarSql(
      'INSERT INTO legales.lir_importesreguladosjuicio (' +
      '              ir_id, ir_idjuicioentramite, ir_importe, ir_detalle, ' +
      '              ir_aplicacion, ir_usualta, ir_fechaalta, ir_usumodif, ' +
      '              ir_fechamodif, ir_usubaja, ir_fechabaja, ir_idinstancia, ' +
      '              ir_nropago, ir_idreclamojuicioentramite, ir_importesinret, ' +
      '              ir_importesentencia,ir_carga,ir_detalleweb) ' +
      '     VALUES (legales.seq_lir_id.NEXTVAL, ' +
                    SqlInt($nro) + ', 0,' +
                    SqlValue(detalle) + ', ' +
                    SqlValue(aplicado) + ', ' +
                    SqlValue($usuario) + ', SYSDATE, NULL, NULL, NULL, NULL, ' +
                    SqlValue($instancia) + ', NULL, ' +
      '             NULL, ' +
      '             NULL, ' +
                    SqlNumber($importe) .',''W'','.SqlValue(detalleweb). ')');
    //LogMessage('InsertarImporteNuevo - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarImporteNuevo - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}


function  UpdateReclamos(rt_id, $nrojuicio, $usuario, montosentencia, porcentajesentencia );
{
  //LogMessage('UpdateReclamos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $nro= StrToInt($nrojuicio);
    rtid=StrtoInt(rt_id);
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $monto= StrToFloatDef(montosentencia, 0, $format);
    porcentaje = StrToFloatDef(porcentajesentencia, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';
    $dmConn.EjecutarSql(
      ' UPDATE legales.lrt_reclamojuicioentramite '.
      '    SET rt_montosentencia = '.SqlNumber($monto).','.
      '        rt_porcentajesentencia = '.SqlNumber(porcentaje).','.
      '        rt_fechamodif = SYSDATE, '.
      '        rt_usumodif = '. SqlValue($usuario) +
      '  WHERE rt_id = ' + SqlInt(rtid) );
    //LogMessage('UpdateReclamos - Query',EVENTLOG_INFORMATION_TYPE,0,0);

    if $dmConn.ValorSqlInteger(
      'SELECT 1 FROM legales.lir_importesreguladosjuicio ' +
      ' WHERE ir_idreclamojuicioentramite = ' + SqlInt(rtid) +
      '   AND ir_idinstancia in' + '(SELECT ij_id '.
      '                                FROM legales.ljz_juzgado INNER JOIN legales.lin_instancia ON ljz_juzgado.jz_idinstancia = '.
      '                                     lin_instancia.in_id '.
      '                          INNER JOIN legales.ljt_juicioentramite ON ljt_juicioentramite.jt_idjuzgado = '.
      '                                     ljz_juzgado.jz_id '.
      '                          INNER JOIN legales.lij_instanciajuicioentramite ON lij_instanciajuicioentramite.ij_idjuicioentramite = '.
      '                                     ljt_juicioentramite.$jt_id '.
      '                                 AND ljz_juzgado.jz_id =  ij_idjuzgado '.
      '                               WHERE ljt_juicioentramite.$jt_id = '.SqlInt($nro).')'
                        ) = 1 then
    {
      $dmConn.EjecutarSql(
      ' UPDATE legales.lir_importesreguladosjuicio ' +
      '    SET ir_importesinret = NULL, ' +
      '        ir_importesentencia = '.SqlNumber($monto) + ', ' +
      '        ir_usumodif = ' + SqlValue($usuario) + ', ' +
      '        ir_fechamodif = SYSDATE, ' +
      '        ir_carga = ''W'' '.
      '  WHERE ir_idreclamojuicioentramite = ' + SqlInt(rtid) +
      '    AND ir_idinstancia = ' + '(SELECT ij_id '.
      '                                 FROM legales.ljz_juzgado INNER JOIN legales.lin_instancia ON ljz_juzgado.jz_idinstancia = '.
      '                                      lin_instancia.in_id '.
      '                           INNER JOIN legales.ljt_juicioentramite ON ljt_juicioentramite.jt_idjuzgado = '.
      '                                      ljz_juzgado.jz_id '.
      '                           INNER JOIN legales.lij_instanciajuicioentramite ON lij_instanciajuicioentramite.ij_idjuicioentramite = '.
      '                                      ljt_juicioentramite.$jt_id '.
      '                                  AND ljz_juzgado.jz_id = '.
      '                                      ij_idjuzgado '.
      '                                WHERE ljt_juicioentramite.$jt_id = '.SqlInt($nro).')');
      //LogMessage('UpdateReclamos - Query1',EVENTLOG_INFORMATION_TYPE,0,0);
    end
    else
      $dmConn.EjecutarSql('INSERT INTO legales.lir_importesreguladosjuicio (' +
                    'ir_id, ir_idjuicioentramite, ir_importe, ir_detalle, ' +
                    'ir_aplicacion, ir_usualta, ir_fechaalta, ir_usumodif, ' +
                    'ir_fechamodif, ir_usubaja, ir_fechabaja, ir_idinstancia, ' +
                    'ir_nropago, ir_idreclamojuicioentramite, ir_importesinret, ' +
                    'ir_importesentencia,ir_carga) ' +
                    'VALUES (legales.seq_lir_id.NEXTVAL, ' +
                    SqlInt($nro) + ', 0,' +
                    SqlValue('Sentencia a Reclamo ' +
                             String($dmConn.ValorSql('SELECT od_nombre from legales.lod_origendemanda ' +
                                             ' WHERE od_idjuicioentramite = ' + SqlInt($nro) +
                                             '   AND od_id =   (SELECT od_id  '.
                                                               '  FROM legales.lod_origendemanda '.
                                                               ' WHERE od_idjuicioentramite = ' + SqlInt($nro) + ')' +
                                             '   AND od_fechabaja IS NULL', ''))
                            ) + ', ' +
                    SqlValue('C') + ', ' +
                    SqlValue($usuario) + ', SYSDATE, NULL, NULL, NULL, NULL, ' +
                    '(SELECT ij_id '.
                          ' FROM legales.ljz_juzgado INNER JOIN legales.lin_instancia ON ljz_juzgado.jz_idinstancia = '.
                                                                                         ' lin_instancia.in_id '.
                               ' INNER JOIN legales.ljt_juicioentramite ON ljt_juicioentramite.jt_idjuzgado = '.
                                                                           ' ljz_juzgado.jz_id '.
                               ' INNER JOIN legales.lij_instanciajuicioentramite ON lij_instanciajuicioentramite.ij_idjuicioentramite = '.
                                                                                    ' ljt_juicioentramite.$jt_id '.
                                                                             ' AND ljz_juzgado.jz_id = '.
                                                                                    ' ij_idjuzgado '.
                         ' WHERE ljt_juicioentramite.$jt_id = '.SqlInt($nro).')' + ', NULL, ' +
                    SqlInt(rtid) + ', ' +
                     'NULL, ' +
                    SqlNumber($monto) .',''W'''.')');
    //LogMessage('UpdateReclamos - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateReclamos - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateMedidasCautelares(mc_id, $usuario );
{
  //LogMessage('UpdateMedidasCautelares - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt(mc_id);
    $dmConn.EjecutarSql(
      ' UPDATE legales.lmc_medidascautelares '.
      '    SET MC_FECHABAJA = SYSDATE, '.
      '        MC_USUBAJA = '. SqlValue($usuario) +
      '  WHERE MC_FECHABAJA IS NULL AND MC_ID = ' + SqlInt($id) );
    //LogMessage('UpdateMedidasCautelares - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateMedidasCautelares - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateEventos($et_id, $usuario ):Boolean;
{
  //LogMessage('UpdateEventos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt($et_id);
    if $dmConn.ExisteSql(
      ' SELECT 1 '.
      '   FROM legales.let_eventojuicioentramite '.
      '  WHERE et_fechabaja IS NULL '.
      '    AND $et_id = '.Sqlint($id).
      '    AND et_usualta = '.SqlValue($usuario)) then
    {
      $dmConn.EjecutarSql(
      ' UPDATE legales.let_eventojuicioentramite '.
      '    SET et_fechabaja = SYSDATE, '.
      '        et_usubaja = '. SqlValue($usuario) +
      '  WHERE et_fechabaja IS NULL AND $et_id = ' + SqlInt($id) );
      return True;
    end
    else
      return False;
    //LogMessage('UpdateEventos - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateEventos - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateEventosCYQ($nroorden, $nroevento, $usuario );
{
  //LogMessage('UpdateEventosCYQ - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery(
      'SELECT IIF_Compara( ''>'', NVL(Min( CE_NROEVENTO ), 0), 0, -1, NVL(Min( CE_NROEVENTO ), 0)-1 ) FROM LCE_EVENTOCYQ WHERE '.
      '        CE_NROORDEN = '.SqlValue($nroorden));
    $nro_evento= $qry.Fields[0].AsString;
    //LogMessage('UpdateEventosCYQ - Query - ' + $qry.SQL.Text, EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql(
      ' UPDATE lce_eventocyq '.
      '    SET ce_nroevento = '.SqlValue($nro_evento).', '.
      '        ce_usumodif = '.SqlValue($usuario).', '.
      '        ce_fechamodif = SYSDATE '.
      '  WHERE ce_nroorden = '.SqlValue($nroorden).
      '    AND ce_nroevento = '.SqlValue($nroevento));
    //LogMessage('UpdateEventosCYQ - Query',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateEventosCYQ - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateAcuerdos($nroorden, $nropago, $usuario );
{
  //LogMessage('UpdateAcuerdos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery(
      ' SELECT IIF_Compara( ''>'', NVL(Min(CA_NROPAGO), 0), 0, -1, NVL(Min(CA_NROPAGO), 0) - 1) FROM LCA_ACUERDOCYQ WHERE CA_NROORDEN = '.SqlValue($nroorden));
    $nro_pago= $qry.Fields[0].AsString;
    //LogMessage('UpdateAcuerdos - Query - ' + $qry.SQL.Text, EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql(
      ' UPDATE lca_acuerdocyq '.
      '    SET ca_nropago = '.SqlValue($nro_pago).', '.
      '        ca_usumodif = '.SqlValue($usuario).', '.
      '        ca_fechamodif = SYSDATE '.
      '  WHERE ca_nroorden = '.SqlValue($nroorden).
      '    AND ca_nropago = '.SqlValue($nropago));
    //LogMessage('UpdateAcuerdos - Query', EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateAcuerdos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateEventosABM(txtfecha  ; txtfechavencimiento, $et_id, $txtobservaciones , $usuario, cmbEventos  );
{
  //LogMessage('UpdateEventosABM - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    try {
      $id=StrtoInt($et_id);
      idtipoevento = StrToInt(cmbEventos);
      $format.DateSeparator = '/';
      $format.ShortDateFormat = 'd/m/$y';
      ReplaceString($txtobservaciones,'<','');
      ReplaceString($txtobservaciones,'>','');
      $strqry=
        ' DECLARE '.
        '   observaciones varchar2(32767); '.
        ' { '.
        ' observaciones = :obs; '.
        ' UPDATE legales.let_eventojuicioentramite '.
        '    SET et_fechaevento = '.SqlDate(txtfecha) .',';

      if txtfechavencimiento <>'' then
        $strqry= $strqry+ ' et_fechavencimiento ='.SqlDate(strtodatedef(txtfechavencimiento,0,$format)) .','
      else
        $strqry = $strqry .' et_fechavencimiento = NULL , ';
      $strqry = $strqry +
        '        et_fechamodif = SYSDATE, '.
        '        et_usumodif ='.SqlValue($usuario) .', '.
        '        et_fechabaja = NULL, '.
        '        et_usubaja = NULL, '.
        '        et_observaciones = observaciones,'.
        '        et_idtipoevento = '.SqlInt(idtipoevento).
        '  WHERE $et_id = '.SqlInt($id).';'.
        ' }';

      //LogMessage('UpdateEventosABM - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
      $dmConn.EjecutarSqlEx($strqry,[$txtobservaciones]);
      //LogMessage('UpdateEventosABM - Corrio - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);



    } catch (Exception $e) {
      on e: Exception do
      {
        LogMessage('UpdateEventosABM - Error - ' + E.Message,EVENTLOG_ERROR_TYPE,0,0);
        raise Exception.Create('Error! ' + e.message);
      }
    }
   finally {
    //tslAux.Free;
    $dmConn.Free;
    //LogMessage('UpdateEventosABM - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateEventosCYQABM(txtfecha  ; $txtobservaciones, $usuario, cmbEventos, $nroorden,$nroevento );
{
  //LogMessage('UpdateEventosCYQABM - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $strqry=
      ' UPDATE lce_eventocyq '.
      '    SET ce_usumodif = '.SqlValue($usuario).', '.
      '        ce_fechamodif = SYSDATE, '.
      '        ce_codevento = '.SqlValue(cmbEventos).', '.
      '        ce_fecha = '.SqlDate(txtfecha).', '.
      '        ce_observaciones = '.SqlValue($txtobservaciones).
      '  WHERE ce_nroorden = '.SqlValue($nroorden).
      '    AND ce_nroevento = '.SqlValue($nroevento);
    //LogMessage('UpdateEventosCYQABM - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('UpdateEventosCYQABM - Finalizo - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateEventosCYQABM - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateLiquidacionFinal( $FImporteCapital, $FImporteIntereses, $FTasasDeJusticias, $FEmbargos, $FOtros, $txtobservaciones, $usuario, $lf_id );
{
  //LogMessage('UpdateLiquidacionFinal - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $format.DecimalSeparator = ',';
  $format.ThousandSeparator = '.';
  $importeCapital   = StrToFloatDef($FImporteCapital, 0, $format);
  $importeintereses = StrToFloatDef($FImporteIntereses, 0, $format);
  $TasasDeJusticias = StrToFloatDef($FTasasDeJusticias, 0, $format);
  $Embargos         = StrToFloatDef($FEmbargos, 0, $format);
  $Otros            = StrToFloatDef($FOtros, 0, $format);
  $format.DecimalSeparator = '.';
  $format.ThousandSeparator = ',';

  $dmConn = TdmPrincipal.Create(nil);
  try {
    $strqry=
      ' UPDATE legales.llf_liquidacionfinal '.
      '    SET lf_usumodif = '.SqlValue($usuario).', '.
      '        lf_fechamodif = SYSDATE, '.
      '        LF_CAPITAL = '.SqlValue($importeCapital).', '.
      '        LF_INTERESES = '.SqlValue($importeintereses).', '.
      '        LF_TASASDEJUSTICIA = '.SqlValue($TasasDeJusticias).', '.
      '        LF_EMBARGOS = '.SqlValue($Embargos).', '.
      '        LF_OTROS = '.SqlValue($Otros).', '.
      '        LF_OBSERVACIONES = '.SqlValue($txtobservaciones).
      '  WHERE $lf_id = '.SqlValue($lf_id);

    //LogMessage('UpdateLiquidacionFinal - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('UpdateLiquidacionFinal - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateLiquidacionFinal - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  InsertarLiquidacionFinal( $FImporteCapital, $FImporteIntereses, $FTasasDeJusticias, $FEmbargos, $FOtros, $txtobservaciones, $usuario, $nrojuicio );
{
  //LogMessage('InsertarLiquidacionFinal - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $importeCapital   = StrToFloatDef($FImporteCapital, 0, $format);
    $importeintereses = StrToFloatDef($FImporteIntereses, 0, $format);
    $TasasDeJusticias = StrToFloatDef($FTasasDeJusticias, 0, $format);
    $Embargos         = StrToFloatDef($FEmbargos, 0, $format);
    $Otros            = StrToFloatDef($FOtros, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';
    $dmConn.EjecutarSql(
      'INSERT INTO LEGALES.LLF_LIQUIDACIONFINAL (' +
      '              $lf_id, LF_IDJUICIOENTRAMITE, LF_CAPITAL, LF_INTERESES, ' +
      '              LF_TASASDEJUSTICIA, LF_EMBARGOS, LF_OTROS, LF_OBSERVACIONES, ' +
      '              LF_USUALTA, LF_FECHAALTA' +
      '             ) ' +
      '     VALUES (LEGALES.SEQ_LLI_ID.NEXTVAL, ' +
                    SqlValue($nrojuicio) + ',' +
                    SqlValue($importeCapital) + ',' +
                    SqlValue($importeintereses) + ',' +
                    SqlValue($TasasDeJusticias) + ',' +
                    SqlValue($Embargos) + ',' +
                    SqlValue($Otros) + ',' +
                    SqlValue($txtobservaciones) + ',' +
                    SqlValue($usuario) + ',sysdate' +
                    ')');
    //LogMessage('InsertarLiquidacionFinal - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('InsertarLiquidacionFinal - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateAcuerdosABM(txtfechavenc  ; txtmonto, txtfechapago, $txtobservaciones, $usuario,$nroorden,$nropago,txtFechaExtincion, cmbtipo );
{
  //LogMessage('UpdateAcuerdosABM - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $monto= StrToFloatDef(txtmonto, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';
    $format.DateSeparator = '/';
    $format.ShortDateFormat = 'd/m/$y';

    $strqry=
      ' UPDATE lca_acuerdocyq '.
      '    SET ca_usumodif = '.SqlValue($usuario).', '.
      '        ca_fechamodif = SYSDATE, ';
    if txtfechapago <>'' then
      $strqry= $strqry+ ' ca_fechapago ='.SqlDate(strtodatedef(txtfechapago,0,$format)) .','
    else
      $strqry = $strqry .' ca_fechapago = NULL , ';
    $strqry = $strqry .'        ca_fechavenc = '.SqlDate(txtfechavenc).', '.
      '        ca_monto = '.SqlNumber($monto).', ';
    if txtFechaExtincion <>'' then
      $strqry= $strqry+ ' CA_FECHAEXTINCION ='.SqlDate(strtodatedef(txtFechaExtincion,0,$format)) .','
    else
      $strqry = $strqry .' CA_FECHAEXTINCION = NULL , ';

    $strqry = $strqry +
      '        CA_TIPO = '.SqlValue(cmbtipo) + ', '.
      '        ca_observaciones = '.SqlValue($txtobservaciones) +
      '  WHERE ca_nroorden = '.SqlValue($nroorden).
      '    AND ca_nropago = '.SqlValue($nropago);
    //LogMessage('UpdateAcuerdosABM - Query - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('UpdateAcuerdosABM - Finalizo - ' + $strqry,EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateAcuerdosABM - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateConcursoyquiebras($txtsindico, $txtdireccion, $txtlocaclidad, $txtfuero, $txttelefono, 
	$txtjurisdiccion, $txtjuzgado, $txtsecretaria, $fechaconcurso, $fechaquiebra, $fechaart32, 
	$fechaart200, $fverificacioncredito, $usuario, $nroorden, $montoprivilegio, $montoquirografario);
{
  //LogMessage('UpdateConcursoyquiebras - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $format.DateSeparator = '/';
    $format.ShortDateFormat = 'd/m/$y';
    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $importe_privilegio= StrToFloatDef($montoprivilegio, 0, $format);
    $importe_quiro = StrToFloatDef($montoquirografario, 0, $format);
    $format.DecimalSeparator = '.';
    $format.ThousandSeparator = ',';

    $strqry=
      ' UPDATE art.lcq_concyquiebra '.
      '    SET cq_sindico = '.SqlValue($txtsindico)  .', '.
      '        cq_direccionsind = '.SqlValue($txtdireccion)  .', '.
      '        cq_localidadsind = '.SqlValue($txtlocaclidad)  .', '.
      '        cq_telefonosind = '.SqlValue($txttelefono)  .', '.
      '        cq_fuero = '.SqlValue($txtfuero)  .', '.
      '        cq_jurisdiccion = '.SqlValue($txtjurisdiccion)  .', '.
      '        cq_juzgado = '.SqlValue($txtjuzgado)  .', '.
      '        cq_montoprivilegio = '.SqlNumber($importe_privilegio).', '.
      '        cq_montoquirog = '.SqlNumber($importe_quiro).', '.
      '        cq_secretaria = '.SqlValue($txtsecretaria)  .', ';
    if $fechaconcurso <>'' then
      $strqry= $strqry+ ' cq_fechaconcurso ='.SqlDate(strtodatedef($fechaconcurso,0,$format)) .','
    else
      $strqry = $strqry .' cq_fechaconcurso = NULL , ';
    if $fechaquiebra <>'' then
      $strqry= $strqry+ ' cq_fechaquiebra ='.SqlDate(strtodatedef($fechaquiebra,0,$format)) .','
    else
      $strqry = $strqry .' cq_fechaquiebra = NULL , ';
    if $fechaart32 <>'' then
      $strqry= $strqry+ ' cq_fechavtoart32 ='.SqlDate(strtodatedef($fechaart32,0,$format)) .','
    else
      $strqry = $strqry .' cq_fechavtoart32 = NULL , ';
    if $fechaart200 <>'' then
      $strqry= $strqry+ ' cq_fechavtoart200 ='.SqlDate(strtodatedef($fechaart200,0,$format)) .','
    else
      $strqry = $strqry .' cq_fechavtoart200 = NULL , ';
    if $fverificacioncredito <>'' then
      $strqry= $strqry+ ' cq_fechaverificacioncredito ='.SqlDate(strtodatedef($fverificacioncredito,0,$format)) .','
    else
      $strqry = $strqry .' cq_fechaverificacioncredito = NULL , ';
    $strqry = $strqry + '        cq_usumodif = '.SqlValue($usuario).', '.
      '        cq_fechamodif = SYSDATE '.
      '  WHERE cq_nroorden = '.SqlValue($nroorden);
    //LogMessage('UpdateConcursoyquiebras - Query - ' + $strqry, EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql($strqry);
    //LogMessage('UpdateConcursoyquiebras - Finalizo - ' + $strqry, EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateConcursoyquiebras - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateEventosArchivos(ea_id, $usuario, id_evento  );
{
  //LogMessage('UpdateEventosArchivos - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $eaid= StrToInt(ea_id);
    $idevento = StrToInt(id_evento);
    $dmConn.EjecutarSql(
      ' UPDATE legales.lea_eventoarchivoasociado '.
      '    SET ea_usubaja = '.SqlValue($usuario) .', '.
      '        ea_fechabaja = SYSDATE '.
      '  WHERE ea_id = '.SqlInt($eaid).' AND ea_ideventojuicioentramite = '.SqlInt($idevento));
    //LogMessage('UpdateEventosArchivos - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateEventosArchivos - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateEstado($jt_id, cmbEstado,$usuario );
{
  //LogMessage('UpdateEstado - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt($jt_id);
    $idEstado = StrToInt(cmbEstado);

    $dmConn.EjecutarSql( $format('{'#13#10' %s '#13#10'}', ['art.Legales.Set_CambioEstado( ' +
                    SqlInt($id) +  ', ' +
                    SqlInt($idEstado) +  ', ' +
                    SqlDate($dmConn.DBDateTime) +  ', ' +
                    SqlValue($usuario) +  '); ']) );
    //LogMessage('UpdateEstado - Store',EVENTLOG_INFORMATION_TYPE,0,0);
    $dmConn.EjecutarSql(
      ' UPDATE legales.ljt_juicioentramite '.
      '    SET jt_fechamodif = SYSDATE, '.
      '        jt_idestado = '.SqlInt($idEstado) +
      '  WHERE $jt_id = '.SqlInt($id) );
    //LogMessage('UpdateEstado - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateEstado - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  UpdateResultado($jt_id, resultado, cmbEstado,$usuario );
{
  //LogMessage('UpdateResultado - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $id=StrtoInt($jt_id);
    $idEstado = StrToInt(cmbEstado);
    $dmConn.EjecutarSql( $format('{'#13#10' %s '#13#10'}', ['art.Legales.Set_CambioEstado( ' +
                    SqlInt($id) +  ', ' +
                    SqlInt($idEstado) +  ', ' +
                    SqlDate($dmConn.DBDateTime) +  ', ' +
                    SqlValue($usuario) +  '); ']) );

    //LogMessage('UpdateResultado - Store',EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql(
      ' UPDATE legales.ljt_juicioentramite '.
      '    SET jt_resultado = '. SqlValue(resultado) + ','.
      '        jt_fechamodif = SYSDATE, '.
      '        jt_idestado = '.SqlInt($idEstado) +
      '  WHERE $jt_id = '.SqlInt($id) );

    //LogMessage('UpdateResultado - Query',EVENTLOG_INFORMATION_TYPE,0,0);

    $dmConn.EjecutarSql(
      ' INSERT INTO legales.lhr_historicoresprobable '.
      '             (hr_id, hr_resultado, hr_usualta, hr_fechaalta, '.
      '              hr_idjuicioentramite '.
      '             ) '.
      '      VALUES (legales.seq_lhp_id.NEXTVAL,'.SqlValue(resultado) + ','. SqlValue($usuario). ', SYSDATE,'.
                    SqlInt($id).')');
    //LogMessage('UpdateResultado - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('UpdateResultado - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  sumaHonorarios($nrojuicio,$instancia) 
{
  //LogMessage('sumaHonorarios - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery(
      ' SELECT SUM (ir_importesentencia) as ir_importesentencia'.
      '   FROM legales.lir_importesreguladosjuicio '.
      '  WHERE ir_idjuicioentramite = '.SqlValue($nrojuicio).'AND ir_idinstancia='.SqlValue($instancia).' AND ir_aplicacion = ''H'' '.
      '    AND ir_fechabaja IS NULL '      );
    //LogMessage('sumaHonorarios - Query - ' + $qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);

    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    try {
      $sXml  = $sXml  + '<'.'HONORARIOS'.'>';
      while not $qry.Eof do
      {
        for $i=0 to $qry.Fields.Count - 1 do
        {
          if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
            if  not ($qry.Fields[$i].AsString = '')   then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
          else
            if ($qry.Fields[$i].DataType = ftFloat)then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$qry.Fields[$i].AsString.'</'.$qry.Fields[$i].FieldName.'>';
        }
        $qry.Next;
      }
      $sXml  = $sXml  + '</'.'HONORARIOS'.'>';
     finally {
      $qry.free;
      return $sXml;
    }
    //LogMessage('sumaHonorarios - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('sumaHonorarios - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  sumaIntereses($nrojuicio,$instancia) 
{
  //LogMessage('sumaIntereses - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery(
      ' SELECT SUM (ir_importesentencia) as ir_importesentencia'.
      '   FROM legales.lir_importesreguladosjuicio '.
      '  WHERE ir_idjuicioentramite = '.SqlValue($nrojuicio).'AND ir_idinstancia='.SqlValue($instancia).' AND ir_aplicacion = ''$i'' '.
      '    AND ir_fechabaja IS NULL ' );
    //LogMessage('sumaIntereses - Query - ' + $qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);

    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    try {
      $sXml  = $sXml  + '<'.'INTERESES'.'>';
      while not $qry.Eof do
      {
        for $i=0 to $qry.Fields.Count - 1 do
        {
          if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
            if  not ($qry.Fields[$i].AsString = '')   then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
          else
            if ($qry.Fields[$i].DataType = ftFloat)then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$qry.Fields[$i].AsString.'</'.$qry.Fields[$i].FieldName.'>';
        }
        $qry.Next;
      }
      $sXml  = $sXml  + '</'.'INTERESES'.'>';
     finally {
      $qry.free;
      return $sXml;
    }
    //LogMessage('sumaIntereses - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('sumaIntereses - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  sumaTasas($nrojuicio,$instancia) 
{
  //LogMessage('sumaTasas - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry=$dmConn.GetQuery (
      ' SELECT SUM (ir_importesentencia) as ir_importesentencia'.
      '   FROM legales.lir_importesreguladosjuicio '.
      '  WHERE ir_idjuicioentramite = '.SqlValue($nrojuicio).'AND ir_idinstancia='.SqlValue($instancia).' AND ir_aplicacion = ''T'' '.
      '    AND ir_fechabaja IS NULL ');
    //LogMessage('sumaTasas - Query - ' + $qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);

    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    try {
      $sXml  = $sXml  + '<'.'TASAS'.'>';
      while not $qry.Eof do
      {
        for $i=0 to $qry.Fields.Count - 1 do
        {
          if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
            if  not ($qry.Fields[$i].AsString = '')   then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
          else
            if ($qry.Fields[$i].DataType = ftFloat)then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$qry.Fields[$i].AsString.'</'.$qry.Fields[$i].FieldName.'>';
        }
        $qry.Next;
      }
      $sXml  = $sXml  + '</'.'TASAS'.'>';
     finally {
      $qry.free;
      return $sXml;
    }
    //LogMessage('sumaTasas - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('sumaTasas - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  sumaCapital($nrojuicio,$instancia) 
{
  //LogMessage('sumaCapital - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery (
      ' SELECT SUM (ir_importesentencia) as ir_importesentencia'.
      '   FROM legales.lir_importesreguladosjuicio '.
      '  WHERE ir_idjuicioentramite = '.SqlValue($nrojuicio).'AND ir_idinstancia='.SqlValue($instancia).' AND ir_aplicacion = ''C'' '.
      '    AND ir_fechabaja IS NULL '
      );
    //LogMessage('sumaCapital - Query - ' + $qry.SQL.Text,EVENTLOG_INFORMATION_TYPE,0,0);

    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    try {
      $sXml  = $sXml  + '<'.'CAPITAL'.'>';
      while not $qry.Eof do
      {
        for $i=0 to $qry.Fields.Count - 1 do
        {
          if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
            if  not ($qry.Fields[$i].AsString = '')   then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
          else
          if ($qry.Fields[$i].DataType = ftFloat)then
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
          else
            $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$qry.Fields[$i].AsString.'</'.$qry.Fields[$i].FieldName.'>';
        }
        $qry.Next;
      }
      $sXml  = $sXml  + '</'.'CAPITAL'.'>';
     finally {
      $qry.free;
      return $sXml;
    }
    //LogMessage('sumaCapital - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('sumaCapital - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  sumaSentencia($nrojuicio,$instancia) stdcall;
{
  //LogMessage('sumaSentencia - Inicio',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery (
      ' SELECT SUM (ir_importesentencia) as ir_importesentencia'.
      '   FROM legales.lir_importesreguladosjuicio '.
      '  WHERE ir_idjuicioentramite = '.SqlValue($nrojuicio).'AND ir_idinstancia='.SqlValue($instancia).
      '    AND ir_fechabaja IS NULL ' );
    //LogMessage('sumaSentencia - Query - ' + $qry.SQL.Text, EVENTLOG_INFORMATION_TYPE,0,0);

    $format.DecimalSeparator = ',';
    $format.ThousandSeparator = '.';
    $sXml  = '';
    try {
      $sXml  = $sXml  + '<'.'SENTENCIA'.'>';
      while not $qry.Eof do
      {
        for $i=0 to $qry.Fields.Count - 1 do
        {
          if (($qry.Fields[$i].DataType = ftDate) or ($qry.Fields[$i].DataType = ftDateTime)) then
            if  not ($qry.Fields[$i].AsString = '')   then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatDateTime('yyyy-mm-dd"T"hh:nn:ss.0000000-03:00', $qry.Fields[$i].AsDateTime).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.''.'</'.$qry.Fields[$i].FieldName.'>'
          else
            if ($qry.Fields[$i].DataType = ftFloat)then
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.FormatFloat('0.00', $qry.Fields[$i].AsFloat,$format).'</'.$qry.Fields[$i].FieldName.'>'
            else
              $sXml  =$sXml .'<'.$qry.Fields[$i].FieldName.'>'.$qry.Fields[$i].AsString.'</'.$qry.Fields[$i].FieldName.'>';
        }
        $qry.Next;
      }
      $sXml  = $sXml  + '</'.'SENTENCIA'.'>';
     finally {
      $qry.free;
      return $sXml;
    }
    //LogMessage('sumaSentencia - Finalizo',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('sumaSentencia - Desconecto',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ObtenerChequesDisponible(Abogado, pag) 
{
  //LogMessage('ObtenerChequesDisponible - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $idAbogado = $dmConn.ValorSql(' SELECT NU_IDABOGADO '.
                          '   FROM legales.lnu_nivelusuario '.
                          '  WHERE nu_usuario = UPPER('.SqlValue(Abogado).')');
    $generico =  $dmConn.ValorSql(' SELECT nu_usuariogenerico '.
                          '   FROM legales.lnu_nivelusuario '.
                          '  WHERE nu_usuario = UPPER('.SqlValue(Abogado).')');

    //LogMessage('ObtenerChequesDisponible - Despues de los valor SQL ',EVENTLOG_INFORMATION_TYPE,0,0);

    $strqry=
      ' SELECT   ce_fechacheque fecha, ce_numero cheque, ce_monto $importe, '.
      '          ce_beneficiario beneficiario, ce_id,  ce_ordenpago ordenpago '.
      '     FROM rce_chequeemitido '.
      '    WHERE ce_id IN(SELECT pl_idchequeemitido '.
      '                     FROM legales.ljt_juicioentramite, legales.lpl_pagolegal '.
      '                    WHERE $jt_id = pl_idjuicioentramite '.
      '                      AND (jt_idabogado = '.SqlValue($idAbogado).
      '                      OR ''S'' = '.SqlValue($generico) .')'.
      '                   UNION '.
      '                   SELECT pm_idchequeemitido '.
      '                     FROM legales.lme_mediacion, legales.lpm_pagomediacion '.
      '                    WHERE me_id = pm_idmediacion '.
      '                      AND (me_idabogado = '.SqlValue($idAbogado).
      '                      OR ''S'' = '.SqlValue($generico) .')' .')'.
      '      AND ce_estado = ''01'' '.
      '      AND ce_situacion IN(''01'', ''14'',''19'') ' +
      '      AND ce_cuenta IS NULL ' +
      '      AND ce_debitado = ''N''' +
      ' ORDER BY 1, 2 ';

    $sXml  = '';
    $sXml=CrearXmlTabla('RCE_CHEQUEEMITIDO','dtsChequesDisponibles', 'http://www.changeme.now/dtsChequesDisponibles.xsd',$strqry,pag);
    return $sXml;
    //LogMessage('ObtenerChequesDisponible - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('ObtenerChequesDisponible - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  ObtenerChequeDetalle(idCheque;pag) 
{
  //LogMessage('ObtenerChequeDetalle - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT ''JUICIO'' tipo, jt_numerocarpeta numero, cp_denpago conpago, '.
    '        jt_demandante demandante, jt_demandado demandado, '.
    '        NVL(pl_importepago, 0) + NVL(pl_importeconretencion, 0) $importe '.
    '   FROM art.scp_conpago, legales.lbo_abogado, legales.ljt_juicioentramite, '.
    '        legales.lpl_pagolegal '.
    '  WHERE $jt_id = pl_idjuicioentramite '.
    '    AND bo_id = jt_idabogado '.
    '    AND pl_conpago = cp_conpago '.
    '    AND pl_idchequeemitido = '.SqlValue(idCheque).
    ' UNION '.
    ' SELECT ''MEDIACIÓN'' tipo, me_numerofolio numero, cp_denpago conpago, '.
    '        me_demandante demandante, me_demandado demandado, '.
    '        NVL(pm_importepago, 0) + NVL(pm_importeconretencion, 0) $importe '.
    '   FROM art.scp_conpago, legales.lbo_abogado, legales.lme_mediacion, '.
    '        legales.lpm_pagomediacion '.
    '  WHERE me_id = pm_idmediacion '.
    '    AND bo_id = me_idabogado '.
    '    AND pm_conpago = cp_conpago '.
    '    AND pm_idchequeemitido = '.SqlValue(idCheque);

  $sXml  = '';
  $sXml=CrearXmlTabla('DETALLECHEQUE','dtsDetalleCheque', 'http://www.changeme.now/dtsDetalleCheque.xsd',$strqry,pag);
  return $sXml;
  //LogMessage('ObtenerChequeDetalle - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerCheque$cantidad(Abogado) 
{
  //LogMessage('ObtenerCheque$cantidad - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $dmConn = TdmPrincipal.Create(nil);
  try {
    $idAbogado = $dmConn.ValorSql(' SELECT NU_IDABOGADO '.
                          '   FROM legales.lnu_nivelusuario '.
                          '  WHERE nu_usuario = UPPER('.SqlValue(Abogado).')');
    $generico =  $dmConn.ValorSql(' SELECT nu_usuariogenerico '.
                          '   FROM legales.lnu_nivelusuario '.
                          '  WHERE nu_usuario = UPPER('.SqlValue(Abogado).')');
    //LogMessage('ObtenerCheque$cantidad - Despues de los valor Sql ',EVENTLOG_INFORMATION_TYPE,0,0);

    $strqry=
      ' SELECT /*+RULE*/ COUNT(*) $cantcheque '.
      '   FROM rce_chequeemitido '.
      '  WHERE ce_id IN(SELECT pl_idchequeemitido '.
      '                   FROM legales.ljt_juicioentramite, legales.lpl_pagolegal '.
      '                  WHERE $jt_id = pl_idjuicioentramite '.
      '                     AND (jt_idabogado = '.SqlValue($idAbogado).
      '                      OR ''S'' = '.SqlValue($generico) .')'.
      '                 UNION '.
      '                 SELECT pm_idchequeemitido '.
      '                   FROM legales.lme_mediacion, legales.lpm_pagomediacion '.
      '                  WHERE me_id = pm_idmediacion '.
      '                     AND (me_idabogado = '.SqlValue($idAbogado).
      '                      OR ''S'' = '.SqlValue($generico) .')' .')'.
      '    AND ce_estado = ''01'' ' +
      '    AND ce_cuenta IS NULL ' +
      '    AND ce_debitado = ''N''' +
      '    AND ce_situacion IN(''01'', ''14'',''19'') ';

    $sXml  = '';
    $sXml=CrearXml1('CHEQUE$cantIDAD',$strqry);
    return $sXml;
    //LogMessage('ObtenerCheque$cantidad - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
   finally {
    $dmConn.Free;
    //LogMessage('ObtenerCheque$cantidad - Desconecto ',EVENTLOG_INFORMATION_TYPE,0,0);
  }
}

function  Obtener$montodemandadoObligatorio(idreclamo ) 
{
  //LogMessage('Obtener$montodemandadoObligatorio - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT NVL(tr_requiereimporte, ''N'') requiereimporte'.
    '   FROM legales.ltr_tiporesultadosentencia '.
    '  WHERE tr_id = '.SqlValue(idreclamo);

  $sXml  = '';
  $sXml=CrearXml1('OBLIGATORIO',$strqry);
  return $sXml;
  //LogMessage('Obtener$montodemandadoObligatorio - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerEsFederal($idJuicio) 
{
  //LogMessage('ObtenerEsFederal - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT jt_federal FEDERAL'.
    '   FROM legales.ljt_juicioentramite '.
    '  WHERE $jt_id = '.SqlValue($idJuicio);

  $sXml  = '';
  $sXml=CrearXml1('ESFEDERAL',$strqry);
  return $sXml;
  //LogMessage('ObtenerEsFederal - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerIncapacidadVisible($idJuicio ) 
{
  //LogMessage('ObtenerIncapacidadVisible - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' select nvl((SELECT DISTINCT ''S'' '.
    '   FROM legales.lrc_reclamo, legales.lrt_reclamojuicioentramite '.
    '  WHERE rc_reclamaincapacidad = ''S'' '.
    '    AND rt_idreclamo = rc_id '.
    '    AND rt_idjuicioentramite = '.SqlValue($idJuicio) .'), ''N'')visible FROM DUAL';

  $sXml  = '';
  $sXml=CrearXml1('INCAPACIDAD',$strqry);
  return $sXml;
  //LogMessage('ObtenerIncapacidadVisible - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerAnioValidoExpediente(anioExpediente ) 
{
  //LogMessage('ObtenerAnioValidoExpediente - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    ' SELECT nvl((SELECT ''S'' valido '.
    '   FROM DUAL '.
    '  WHERE TO_CHAR(art.actualdate, ''YY'') >= '.SqlValue(anioExpediente).
    '     OR '.SqlValue(anioExpediente).' > 96),''N'') VALIDO FROM DUAL ';

  $sXml  = '';
  $sXml=CrearXml1('VALIDO',$strqry);
  return $sXml;
  //LogMessage('ObtenerAnioValidoExpediente - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerPeritosNombre(Nombre, tipoPericia ) 
{
  //LogMessage('ObtenerPeritos - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    '   SELECT pe_nombreindividual || '' '' || pe_apellido nombre, pe_id $id '.
    '     FROM legales.lpe_perito '.
    '    WHERE UPPER(pe_nombreindividual) LIKE UPPER(''%'.Nombre.'%'') '.
    '      AND pe_fechabaja IS NULL ';

  if tipoPericia <> '' then
    $strqry = $strqry + ' AND pe_idtipoperito = '.SqlValue(tipoPericia);

  $strqry = $strqry .' ORDER BY 1 ';
  

  $sXml  = CrearXml2('PERITOS','PERITO',$strqry);
  return $sXml;
  //LogMessage('ObtenerPeritos - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  ObtenerPeritosApellido(Apellido, tipoPericia ) 
{
  //LogMessage('ObtenerPeritos - Inicio ',EVENTLOG_INFORMATION_TYPE,0,0);
  $strqry=
    '   SELECT pe_nombreindividual || '' '' || pe_apellido nombre, pe_id $id '.
    '     FROM legales.lpe_perito '.
    '    WHERE UPPER(PE_APELLIDO) LIKE UPPER(''%'.Apellido.'%'') '.
    '      AND pe_fechabaja IS NULL ';
  if tipoPericia <> '' then
    $strqry = $strqry + ' AND pe_idtipoperito = '.SqlValue(tipoPericia);

  $strqry = $strqry .' ORDER BY 1 ';

  $sXml  = CrearXml2('PERITOS','PERITO',$strqry);
  return $sXml;
  //LogMessage('ObtenerPeritos - Finalizo ',EVENTLOG_INFORMATION_TYPE,0,0);
}

function  VerificarConexion,
{
  return true;

(*  try {
    s='1';
    dmPrincipal = TdmPrincipal.Create(nil);
    s=s.'4';
//    $i= ValorSqlInteger('SELECT 1 FROM DUAL');
    return dmPrincipal.sdbPrincipal.Connected;
  } catch (Exception $e) {
    on E: Exception do
    {
      s = s+e.Message;
      raise Exception.Create('Error de Conexión! ' + e.message + ' >> ' + s);
      result =false;
    }
  }
*)
}

function  CerrarConexion;
{
//  if Assigned(dmPrincipal) then
//    FreeAndNil(dmPrincipal);
}


function  LogMessage(Message; EventType: DWord;
  Category: Word; $id: DWord);
{
  $p = PChar(Message);
  $FEventLog = windows.RegisterEventSource(nil, PChar('Synsrv')); // <- blows up here
  if $FEventLog = 0 then
  {
    Raise exception.Create('Event logging error: ' + SysErrorMessage(getLastError));
  }
//  USID = GetCurrentUserSid();
//  try {
  if not ReportEvent($FEventLog, EventType, Category, $id, nil, 1, 0, @$p, nil) then
    raiseLastOSError;
  // finally {
  //  FreeAndNil(USID);
//  }
}

function  ObtenerNroCarpeta($idJuicio ) 
{
  $strqry=
    ' SELECT jt_numerocarpeta, NVL(jt_demandante, '''') || '' C/ '' || NVL( '.
    '        jt_demandado, '''') || '' '' || jt_caratula AS descripcaratula, ej_descripcion '.
    '   FROM legales.ljt_juicioentramite, legales.lej_estadojuicio '.
    '  WHERE $jt_id = '.sqlvalue($idJuicio).
    '    AND jt_idestado = ej_id ';

  $sXml  = '';
  $sXml=CrearXml1('JUICIO',$strqry);
  return $sXml;
}

function  ObtenerListadoTabla($bActivos, $bTerminados , $nomusuario ) 
{
  $strqry=
            ' SELECT ''<td>'' || nvl(TO_CHAR(jt_numerocarpeta), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(jt_fechanotificacionjuicio), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(ej_descripcion), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(jt_demandante), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(jt_demandado), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(jt_nroexpediente), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(jt_anioexpediente), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(ju_descripcion), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(fu_descripcion), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(jz_descripcion), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(sc_descripcion), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(jt_fechafinjuicio), ''&nbsp;'') || ''</td>'' || '.
            '        ''<td>'' || nvl(TO_CHAR(bo_nombreindividual), ''&nbsp;'') || '' '' || nvl(TO_CHAR(bo_apellido), ''&nbsp;'') || ''</td>'' campos '.
            '   FROM legales.ljt_juicioentramite, legales.lej_estadojuicio, legales.lju_jurisdiccion, legales.lfu_fuero, '.
            '        legales.ljz_juzgado, legales.lsc_secretaria, legales.lbo_abogado, legales.lnu_nivelusuario '.
            '  WHERE jt_idestado = ej_id '.
            '    AND jt_idjurisdiccion = ju_id '.
            '    AND (jt_idabogado = nu_idabogado OR nu_usuariogenerico = ''S'') '.
            '    AND jt_idfuero = fu_id '.
            '    AND jt_idjuzgado = jz_id '.
            '    AND jt_idsecretaria = sc_id '.
            '    AND jt_idabogado = bo_id '.
            '    AND jt_idestado <> 3 '.
            '    AND jt_estadomediacion = ''J'' '.
            '    AND ROWNUM <= 2400 '.
            '    AND nu_usuario = '.SqlValue($nomusuario);
  if not ($bActivos and $bTerminados) then
  {
    if $bTerminados then
      $strqry = $strqry .' AND jt_idestado = 2';
    if $bActivos then
      $strqry = $strqry .' AND jt_idestado <> 2';
  }
   $strqry = $strqry .' ORDER BY jt_numerocarpeta';


  $dmConn = TdmPrincipal.Create(nil);
  try {
    $qry= $dmConn.GetQuery($strqry);
    $cant = $qry.RecordCount;
    $salida = '';
    try {
      while not $qry.Eof do
      {
        $salida = $salida .'<tr>'.$qry.FieldByName('campos').AsString.'</tr>';
        $qry.Next;
      }
     finally {
      $qry.free;
    }
   finally {
    $dmConn.Free;
  }



  $salida =
    //' <> <head> '.
    //' <meta http-equiv="Content-Type" content="text/; charset=iso-8859-1"/> '.
    //' <title>Listado Juicios</title> '.
    //'   <link href="Estilos/legales.css" type="text/css" rel="stylesheet"/>'.
    //'   <link href="Estilos/formularios.css" type="text/css" rel="stylesheet"/>  '.
    //'   <link href="Estilos/textos.css" type="text/css" rel="stylesheet"/>  '.
    //' </head> '.
    //' <body> '.
    //'   <table cellspacing="0" cellpadding="0" width="90%" align="center" class="body_border"> '.
    //'   <tr> '.
    //'     <td> '.
    //'       <table cellspacing="0" cellpadding="0" width="100%" align="center" class="body_border"> '.
    //'       <tr> '.
    //'         <td height="70" colspan="12"> '.
    //'           <img src="imagenes/logo_provart_fondo_blanco.jpg" align="right" width="213" height="52"/> '.
    //'         </td> '.
    //'       </tr> '.
    //'       </table> '.
    //'     </td> '.
    //'   </tr> '.
    //'   <tr> '.
    //'     <td> '.
    '       <table cellpadding=''3'' > '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    '       <tr height= ''200px''> <td >&nbsp; </td> </tr> '.
    IIF($cant = 2400,'<tr> <td style= ''color:red''>Este Listado esta acotado a 2400 registros </td> </tr> ','')+
    '       </table> '.
    '       <font size=''1px''>'.
    '       <table cellpadding=''3'' border=''1'' style=''text-align:center''> '.
    '       <tr> '.
    '         <td style=''font-weight:bold''>JD Nº</td> '.
    '         <td style=''font-weight:bold''>Fecha Notificación</td> '.
    '         <td style=''font-weight:bold''>Estado</td> '.
    '         <td style=''font-weight:bold''>Demandante</td> '.
    '         <td style=''font-weight:bold''>Demandado</td> '.
    '         <td style=''font-weight:bold''>Epdte Nº</td> '.
    '         <td style=''font-weight:bold''>Año Nº</td> '.
    '         <td style=''font-weight:bold''>Jurisdicción</td> '.
    '         <td style=''font-weight:bold''>Fuero</td> '.
    '         <td style=''font-weight:bold''>Juzgado Nº</td> '.
    '         <td style=''font-weight:bold''>Secretaría</td> '.
    '         <td style=''font-weight:bold''>Fecha Fin</td> '.
    '         <td style=''font-weight:bold''>Estudio</td> '.

    '       </tr> '.$salida+
    '       </table> '.
    '       </font>';
    //'     </td> '.
    //'   </tr> '.
    //'   </table> ';
    //' </body> '.
    //' </> ';

  return $salida;
}


?>