<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


/* Anticuación promedio de los tickets de desarrollo (no se incluye a Carozo) + los WF */
$sql =
	"SELECT TRUNC(SUM(anticuacion_promedio * cantidad) / SUM(cantidad)) AS anticuacion_promedio, SUM(cantidad) AS cantidad
  	FROM (SELECT TRUNC(SUM(TRUNC(SYSDATE - TO_DATE(wf_fecharecepcion, 'YYYY/MM/DD'))) / COUNT(*)) AS anticuacion_promedio,
    	           COUNT(*) AS cantidad
      	    FROM comunes.cwf_registro
        	 WHERE UPPER(wf_etapaactual) IN('RESOLUCIÓN', 'REVISIÓN')
	           AND wf_fecharecepcion >= '2005/03/01'
  	      UNION ALL
    	    SELECT SUM(anticuacion_promedio) / COUNT(*) AS anticuacion_promedio, SUM(cantidad) AS cantidad
      	    FROM (SELECT TRUNC(SUM(art.actualdate - ss_fecha_solicitud) / COUNT(*)) AS anticuacion_promedio, COUNT(*) AS cantidad
        	          FROM computos.css_solicitudsistemas, computos.chs_historicosolicitud chs1
          	       WHERE ss_idestadoactual IN(1, 10, 3, 4)
            	       AND chs1.hs_idsolicitud = ss_id
              	     AND chs1.hs_fecha_cambio = (SELECT MAX(chs2.hs_fecha_cambio)
                	                                 FROM computos.chs_historicosolicitud chs2
                  	                              WHERE chs2.hs_idsolicitud = ss_id
                    	                              AND chs2.hs_fecha_cambio <= art.amebpba.calcdiashabiles(sysdate, -1))
	                   AND ss_idsector_asignado = 23032
  	                 AND NOT EXISTS(SELECT 1
    	                                FROM computos.chs_historicosolicitud
      	                             WHERE ss_id = hs_idsolicitud
        	                             AND hs_idestado = 2)
          	      UNION
            	    SELECT TRUNC(SUM(art.actualdate - (SELECT MAX(hs_fecha_cambio)
              	                                       FROM computos.chs_historicosolicitud
                	                                    WHERE ss_id = hs_idsolicitud
                  	                                    AND hs_idestado = 2)) / COUNT(*)) AS anticuacion_promedio,
                    	   COUNT(*) AS cantidad
	                  FROM computos.css_solicitudsistemas, computos.chs_historicosolicitud chs1
  	               WHERE ss_idestadoactual IN(1, 10, 3, 4)
    	               AND ss_idsector_asignado = 23032
      	             AND chs1.hs_idsolicitud = ss_id
        	           AND chs1.hs_fecha_cambio = (SELECT MAX(chs2.hs_fecha_cambio)
          	                                       FROM computos.chs_historicosolicitud chs2
            	                                    WHERE chs2.hs_idsolicitud = ss_id
              	                                    AND chs2.hs_fecha_cambio <= art.amebpba.calcdiashabiles(sysdate, -1))
	                   AND EXISTS(SELECT 1
  	                              FROM computos.chs_historicosolicitud
    	                           WHERE ss_id = hs_idsolicitud
      	                           AND hs_idestado = 2)))";
$stmt = DBExecSql($conn, $sql);
$row = DBGetQuery($stmt);
$cantidad = $row["CANTIDAD"];
$anticuacion = $row["ANTICUACION_PROMEDIO"];
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
	<title>WorkFlow</title>
	<base target="_self">
	<style type="text/css">
	body {
		scrollbar-face-color: #aaaaaa;
		scrollbar-highlight-color: #aaaaaa;
		scrollbar-shadow-color: #aaaaaa;
		scrollbar-3dlight-color: #eeeeee;
		scrollbar-arrow-color: #eeeeee;
		scrollbar-track-color: #e3e3e3;
		scrollbar-darkshadow-color: ffffff;
	}
	</style>
</head>
<body topmargin="2" leftmargin="2">
<form name="prueba">
<?
$color1 = "&H00FF00";
$color2 = "&H00FFFF";
$color3 = "&H0000FF";
?>

<div align="left">
<table border="0" cellspacing="0" height="181" cellpadding="0" width="100%">
	<tr>
		<td height="14" align="center" colspan="2" valign="top" style="padding: 0"><p style="background-color: #006699; border-top: 1 solid #C0C0C0; border-bottom: 1 solid #C0C0C0; margin: 0"><font size="1" face="Verdana" color="#FFFFFF"><b>Cantidad de Requerimientos Pendientes</b></font></p></td>
	</tr>
	<tr>
		<td height="167" align="center" valign="top" width="49%"><p style="margin-top: 0; margin-bottom: 0" align="center">&nbsp;</p><p style="margin-top: 0; margin-bottom: 0" align="center"><font Face="Arial" Font Size=1>
			<object classid="clsid:39B42F8E-60A4-11D4-9D37-C844D961244F" id="CirGauge1" name="WF_Pendientes" width="128" height="107" align="center">
				<param name="ValueTxtLen" value="28">
				<param name="GaugeName" value="Actual">
				<param name="DecimalNum" value=0>
				<param name="PointerColor" value="&H00C0C0C0">
				<param name="TextColor" value="&H00000000">
				<param name="BkColor" value="&H00E0E0E0">
				<param name="UnitName" value="">
				<param name="CntlBKColor" value="&H00FFFFFF">
				<param name="ForeColor" value="&H00000000">
				<param name="TickerColor" value="&H00000000">
				<param name="NeedleType" value="3">
				<param name="TickerAlignment" value="1">
				<param name="ScaleRingBorderType" value="0">
				<param name="InverseDirection" value="False">
			</object>
			</font> 
<?
$limite1 = 0;
$limite2= 50;
$limite3= 100;
$limite4= 150;
?>

<script LANGUAGE="JavaScript">
document.forms[0].WF_Pendientes.MinVal = <?= $limite1 ?>;
document.forms[0].WF_Pendientes.MaxVal = <?= $limite4 ?>;
document.forms[0].WF_Pendientes.value = <?= $cantidad ?>;
document.forms[0].WF_Pendientes.SetScaleRangeColor(1, <?= $limite1 ?>, <?= $limite2 ?>, "&H00FF00");
document.forms[0].WF_Pendientes.SetScaleRangeColor(2, <?= $limite2 ?>, <?= $limite3 ?>, "&H00FFFF");
document.forms[0].WF_Pendientes.SetScaleRangeColor(3, <?= $limite3 ?>, <?= $limite4 ?>, "&H0000FF");
</script>
			</p>
		</td>
		<td height="167" align="center" valign="top" width="49%"><p style="margin-top: 0; margin-bottom: 0"></p><b><font face="Verdana" size="2"></font></b><p></td>
	</tr>
</table>
</div>
<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="223" bgcolor="#CCCCCC" align="right"><p align="right">&nbsp;</td>
		<td width="22%" bgcolor="#CCCCCC"><p align="right"><font face="Verdana" size="1" color="#000080">Última Actualización<b>:</b></font></td>
		<td bgcolor="#CCCCCC" width="23%"><p align="left"><b><font face="Verdana" size="1" color="#000080">&nbsp;<?= date("d/m/Y H:i") ?></font></b></td>
		<td bgcolor="#CCCCCC" width="26%"><p align="right">&nbsp;</td>
	</tr>
</table>
</div>
<table border="0" cellspacing="0" height="151" cellpadding="0" width="100%">
	<tr>
		<td height="16" align="center" valign="top" colspan="2" bgcolor="#336699" style="border-top-style: solid; border-top-width: 1px; border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><b><font face="Verdana" size="1" color="#FFFFFF">Anticuación Requerimientos Pendientes</font></b></td>
	</tr>
	<tr>
		<td height="135" align="center" valign="top"><font Face="Arial" Font Size=1>
			<object classid="clsid:39B42F8E-60A4-11D4-9D37-C844D961244F" id="CirGauge6" name="WF_Anticuacion" width="128" height="107" align="center">
				<param name="ValueTxtLen" value="28">
				<param name="GaugeName" value="Actual">
				<param name="DecimalNum" value=0>
				<param name="PointerColor" value="&H00C0C0C0">
				<param name="TextColor" value="&H00000000">
				<param name="BkColor" value="&H00E0E0E0">
				<param name="UnitName" value="">
				<param name="CntlBKColor" value="&H00FFFFFF">
				<param name="ForeColor" value="&H00000000">
				<param name="TickerColor" value="&H00000000">
				<param name="NeedleType" value="3">
				<param name="TickerAlignment" value="1">
				<param name="ScaleRingBorderType" value="0">
				<param name="InverseDirection" value="False">
			</object>
			</font>
<?
$limite1 = 0;
$limite2= 50;
$limite3= 100;
$limite4= 150;
?>
<script LANGUAGE="JavaScript">
document.forms[0].WF_Anticuacion.MinVal = <?= $limite1 ?>;
document.forms[0].WF_Anticuacion.MaxVal = <?= $limite4 ?>;
document.forms[0].WF_Anticuacion.value = <?= $anticuacion ?>;
document.forms[0].WF_Anticuacion.SetScaleRangeColor(1, <?= $limite1 ?>, <?= $limite2 ?>, "&H00FF00");
document.forms[0].WF_Anticuacion.SetScaleRangeColor(2, <?= $limite2 ?>, <?= $limite3 ?>, "&H00FFFF");
document.forms[0].WF_Anticuacion.SetScaleRangeColor(3, <?= $limite3 ?>, <?= $limite4 ?>, "&H0000FF");
</script>
		</td>
<?
/* Consulta de cantidad de pendientes por rango de fechas */
$sql =
	"SELECT COUNT(*) AS cantidad, '0 - 30' AS rango
  	FROM (SELECT TRUNC(SYSDATE - TO_DATE(wf_fecharecepcion, 'YYYY/MM/DD')) AS anticuacion, 1 AS cantidad
    	      FROM comunes.cwf_registro
      	   WHERE UPPER(wf_etapaactual) IN('RESOLUCIÓN', 'REVISIÓN')
        	   AND wf_fecharecepcion >= '2005/03/01'
	        UNION ALL
  	      SELECT anticuacion_promedio AS anticuacion, 1 AS cantidad
    	      FROM (SELECT TRUNC(art.actualdate - ss_fecha_solicitud) AS anticuacion_promedio, 1 AS cantidad
      	            FROM computos.css_solicitudsistemas, computos.chs_historicosolicitud chs1
        	         WHERE ss_idestadoactual IN(1, 10, 3, 4)
          	         AND ss_idsector_asignado = 23032
            	       AND chs1.hs_idsolicitud = ss_id
              	     AND chs1.hs_fecha_cambio = (SELECT MAX(chs2.hs_fecha_cambio)
                	                                 FROM computos.chs_historicosolicitud chs2
                  	                              WHERE chs2.hs_idsolicitud = ss_id
                    	                              AND chs2.hs_fecha_cambio <= art.amebpba.calcdiashabiles(sysdate, -1))
	                   AND NOT EXISTS(SELECT 1
  	                                  FROM computos.chs_historicosolicitud
    	                               WHERE ss_id = hs_idsolicitud
      	                               AND hs_idestado = 2)
        	        UNION ALL
          	      SELECT TRUNC(art.actualdate - (SELECT MAX(hs_fecha_cambio)
            	                                     FROM computos.chs_historicosolicitud
              	                                  WHERE ss_id = hs_idsolicitud
                	                                  AND hs_idestado = 2)) AS anticuacion, 1 AS cantidad
                  	FROM computos.css_solicitudsistemas, computos.chs_historicosolicitud chs1
	                 WHERE ss_idestadoactual IN(1, 10, 3, 4)
  	                 AND ss_idsector_asignado = 23032
    	               AND chs1.hs_idsolicitud = ss_id
      	             AND chs1.hs_fecha_cambio = (SELECT MAX(chs2.hs_fecha_cambio)
        	                                         FROM computos.chs_historicosolicitud chs2
          	                                      WHERE chs2.hs_idsolicitud = ss_id
            	                                      AND chs2.hs_fecha_cambio <= art.amebpba.calcdiashabiles(sysdate, -1))
                  	 AND EXISTS(SELECT 1
                    	            FROM computos.chs_historicosolicitud
                      	         WHERE ss_id = hs_idsolicitud
                        	         AND hs_idestado = 2)))
	 WHERE anticuacion BETWEEN 0 AND 30
	UNION ALL
	SELECT COUNT(*) AS cantidad, '31 - 90' AS rango
  	FROM (SELECT TRUNC(SYSDATE - TO_DATE(wf_fecharecepcion, 'YYYY/MM/DD')) AS anticuacion, 1 AS cantidad
    	      FROM comunes.cwf_registro
      	   WHERE UPPER(wf_etapaactual) IN('RESOLUCIÓN', 'REVISIÓN')
        	   AND wf_fecharecepcion >= '2005/03/01'
	        UNION ALL
  	      SELECT anticuacion_promedio AS anticuacion, 1 AS cantidad
    	      FROM (SELECT TRUNC(art.actualdate - ss_fecha_solicitud) AS anticuacion_promedio, 1 AS cantidad
      	            FROM computos.css_solicitudsistemas, computos.chs_historicosolicitud chs1
        	         WHERE ss_idestadoactual IN(1, 10, 3, 4)
          	         AND ss_idsector_asignado = 23032
            	       AND chs1.hs_idsolicitud = ss_id
              	     AND chs1.hs_fecha_cambio = (SELECT MAX(chs2.hs_fecha_cambio)
                	                                 FROM computos.chs_historicosolicitud chs2
                  	                              WHERE chs2.hs_idsolicitud = ss_id
                    	                              AND chs2.hs_fecha_cambio <= art.amebpba.calcdiashabiles(sysdate, -1))
	                   AND NOT EXISTS(SELECT 1
  	                                  FROM computos.chs_historicosolicitud
    	                               WHERE ss_id = hs_idsolicitud
      	                               AND hs_idestado = 2)
        	        UNION ALL
          	      SELECT TRUNC(art.actualdate - (SELECT MAX(hs_fecha_cambio)
            	                                     FROM computos.chs_historicosolicitud
              	                                  WHERE ss_id = hs_idsolicitud
                	                                  AND hs_idestado = 2)) AS anticuacion, 1 AS cantidad
                  	FROM computos.css_solicitudsistemas, computos.chs_historicosolicitud chs1
	                 WHERE ss_idestadoactual IN(1, 10, 3, 4)
  	                 AND ss_idsector_asignado = 23032
    	               AND chs1.hs_idsolicitud = ss_id
      	             AND chs1.hs_fecha_cambio = (SELECT MAX(chs2.hs_fecha_cambio)
        	                                         FROM computos.chs_historicosolicitud chs2
          	                                      WHERE chs2.hs_idsolicitud = ss_id
            	                                      AND chs2.hs_fecha_cambio <= art.amebpba.calcdiashabiles(sysdate, -1))
                  	 AND EXISTS(SELECT 1
                    	            FROM computos.chs_historicosolicitud
                      	         WHERE ss_id = hs_idsolicitud
                        	         AND hs_idestado = 2)))
	 WHERE anticuacion BETWEEN 31 AND 90
	UNION ALL
	SELECT COUNT(*) AS cantidad, 'Mas de 91' AS rango
  	FROM (SELECT TRUNC(SYSDATE - TO_DATE(wf_fecharecepcion, 'YYYY/MM/DD')) AS anticuacion, 1 AS cantidad
    	      FROM comunes.cwf_registro
      	   WHERE UPPER(wf_etapaactual) IN('RESOLUCIÓN', 'REVISIÓN')
        	   AND wf_fecharecepcion >= '2005/03/01'
	        UNION ALL
  	      SELECT anticuacion_promedio AS anticuacion, 1 AS cantidad
    	      FROM (SELECT TRUNC(art.actualdate - ss_fecha_solicitud) AS anticuacion_promedio, 1 AS cantidad
      	            FROM computos.css_solicitudsistemas, computos.chs_historicosolicitud chs1
        	         WHERE ss_idestadoactual IN(1, 10, 3, 4)
          	         AND ss_idsector_asignado = 23032
            	       AND chs1.hs_idsolicitud = ss_id
              	     AND chs1.hs_fecha_cambio = (SELECT MAX(chs2.hs_fecha_cambio)
                	                                 FROM computos.chs_historicosolicitud chs2
                  	                              WHERE chs2.hs_idsolicitud = ss_id
                    	                              AND chs2.hs_fecha_cambio <= art.amebpba.calcdiashabiles(sysdate, -1))
	                   AND NOT EXISTS(SELECT 1
  	                                  FROM computos.chs_historicosolicitud
    	                               WHERE ss_id = hs_idsolicitud
      	                               AND hs_idestado = 2)
        	        UNION ALL
          	      SELECT TRUNC(art.actualdate - (SELECT MAX(hs_fecha_cambio)
            	                                     FROM computos.chs_historicosolicitud
              	                                  WHERE ss_id = hs_idsolicitud
                	                                  AND hs_idestado = 2)) AS anticuacion, 1 AS cantidad
                  	FROM computos.css_solicitudsistemas, computos.chs_historicosolicitud chs1
	                 WHERE ss_idestadoactual IN(1, 10, 3, 4)
  	                 AND ss_idsector_asignado = 23032
    	               AND chs1.hs_idsolicitud = ss_id
      	             AND chs1.hs_fecha_cambio = (SELECT MAX(chs2.hs_fecha_cambio)
        	                                         FROM computos.chs_historicosolicitud chs2
          	                                      WHERE chs2.hs_idsolicitud = ss_id
            	                                      AND chs2.hs_fecha_cambio <= art.amebpba.calcdiashabiles(sysdate, -1))
                  	 AND EXISTS(SELECT 1
                    	            FROM computos.chs_historicosolicitud
                      	         WHERE ss_id = hs_idsolicitud
                        	         AND hs_idestado = 2)))
	 WHERE anticuacion > 91";
$stmt = DBExecSql($conn, $sql);

$strRangos = "";
$strCantidad = "";
while($row = DBGetQuery($stmt)) {
	$strRangos.= "&amp;quot;".$row["RANGO"]."&amp;quot;,";
	$strCantidad.= $row["CANTIDAD"].",";
}
$strRangos = substr($strRangos, 0, -1);
$strCantidad = substr($strCantidad, 0, -1);
?>
		<td height="135" align="center" valign="top">
			<object classid="clsid:0002E556-0000-0000-C000-000000000046" id="ChartSpace2" width="191" height="125">
				<param name="XMLData" value="&lt;xml xmlns:x=&quot;urn:schemas-microsoft-com:office:excel&quot;&gt;
					&lt;x:ChartSpace&gt;
					&lt;x:OWCVersion&gt;10.0.0.5605         &lt;/x:OWCVersion&gt;
					&lt;x:Width&gt;5054&lt;/x:Width&gt;
					&lt;x:Height&gt;3307&lt;/x:Height&gt;
					&lt;x:Palette&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000000&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#ECE5CB&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#7EC1DC&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#C2CE99&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#A0E0E0&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#600080&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#FF8080&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#008080&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#C0C0FF&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#000080&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#FF00FF&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#80FFFF&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#0080FF&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#FF8080&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#C0FF90&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#FFC0FF&lt;/x:Entry&gt;
					&lt;x:Entry&gt;#FF80FF&lt;/x:Entry&gt;
					&lt;/x:Palette&gt;
					&lt;x:DefaultFont&gt;Arial&lt;/x:DefaultFont&gt;
					&lt;x:Border&gt;
					&lt;x:ColorIndex&gt;None&lt;/x:ColorIndex&gt;
					&lt;/x:Border&gt;
					&lt;x:Chart&gt;
					&lt;x:PlotArea&gt;
					&lt;x:Graph&gt;
					&lt;x:Type&gt;Pie&lt;/x:Type&gt;
					&lt;x:SubType&gt;Standard&lt;/x:SubType&gt;
					&lt;x:SubType&gt;3D&lt;/x:SubType&gt;
					&lt;x:Series&gt;
					&lt;x:Border&gt;
					&lt;x:Color&gt;#808080&lt;/x:Color&gt;
					&lt;/x:Border&gt;
					&lt;x:FormatMap&gt;
					&lt;/x:FormatMap&gt;
					&lt;x:Name&gt;Series 1&lt;/x:Name&gt;
					&lt;x:Caption&gt;
					&lt;x:DataSourceIndex&gt;-1&lt;/x:DataSourceIndex&gt;
					&lt;x:Data&gt;&amp;quot;Series 1&amp;quot;&lt;/x:Data&gt;
					&lt;/x:Caption&gt;
					&lt;x:Index&gt;0&lt;/x:Index&gt;
					&lt;x:Category&gt;
					&lt;x:DataSourceIndex&gt;-1&lt;/x:DataSourceIndex&gt;
					&lt;x:Data&gt;{<?= $strRangos ?>}&lt;/x:Data&gt;
					&lt;/x:Category&gt;
					&lt;x:Value&gt;
					&lt;x:DataSourceIndex&gt;-1&lt;/x:DataSourceIndex&gt;
					&lt;x:Data&gt;{<?= $strCantidad ?>}&lt;/x:Data&gt;
					&lt;/x:Value&gt;
					&lt;x:FormatValue&gt;
					&lt;x:DataSourceIndex&gt;-3&lt;/x:DataSourceIndex&gt;
					&lt;x:Data&gt;2&lt;/x:Data&gt;
					&lt;/x:FormatValue&gt;
					&lt;x:DataLabels&gt;
					&lt;x:Border&gt;
					&lt;x:ColorIndex&gt;None&lt;/x:ColorIndex&gt;
					&lt;/x:Border&gt;
					&lt;x:Font&gt;
					&lt;x:Color&gt;#808080&lt;/x:Color&gt;
					&lt;x:B/&gt;
					&lt;x:I&gt;Automatic&lt;/x:I&gt;
					&lt;x:U&gt;Automatic&lt;/x:U&gt;
					&lt;/x:Font&gt;
					&lt;x:ShowValue/&gt;
					&lt;x:Separator&gt;,&lt;/x:Separator&gt;
					&lt;x:Position&gt;Center&lt;/x:Position&gt;
					&lt;/x:DataLabels&gt;
					&lt;x:Marker&gt;
					&lt;x:Symbol&gt;None&lt;/x:Symbol&gt;
					&lt;/x:Marker&gt;
					&lt;x:Explode&gt;0&lt;/x:Explode&gt;
					&lt;x:Thickness&gt;10&lt;/x:Thickness&gt;
					&lt;/x:Series&gt;
					&lt;x:VaryColors/&gt;
					&lt;x:Dimension&gt;
					&lt;x:ScaleID&gt;21387816&lt;/x:ScaleID&gt;
					&lt;x:Index&gt;Value&lt;/x:Index&gt;
					&lt;/x:Dimension&gt;
					&lt;x:Dimension&gt;
					&lt;x:ScaleID&gt;21388036&lt;/x:ScaleID&gt;
					&lt;x:Index&gt;FormatValue&lt;/x:Index&gt;
					&lt;/x:Dimension&gt;
					&lt;x:HoleSize&gt;0&lt;/x:HoleSize&gt;
					&lt;x:FirstSliceAngle&gt;0&lt;/x:FirstSliceAngle&gt;
					&lt;/x:Graph&gt;
					&lt;/x:PlotArea&gt;
					&lt;x:View3D&gt;
					&lt;x:GapDepth&gt;150&lt;/x:GapDepth&gt;
					&lt;x:Perspective&gt;20.0&lt;/x:Perspective&gt;
					&lt;x:Rotation&gt;20.0&lt;/x:Rotation&gt;
					&lt;x:Inclination&gt;45.0&lt;/x:Inclination&gt;
					&lt;x:Light&gt;
					&lt;x:Rotation&gt;315.0&lt;/x:Rotation&gt;
					&lt;x:Inclination&gt;15.0&lt;/x:Inclination&gt;
					&lt;x:IntensityDiffuse&gt;0.549019607843137&lt;/x:IntensityDiffuse&gt;
					&lt;x:IntensityAmbient&gt;0.619607843137255&lt;/x:IntensityAmbient&gt;
					&lt;x:Normal&gt;0.5&lt;/x:Normal&gt;
					&lt;/x:Light&gt;
					&lt;/x:View3D&gt;
					&lt;x:Walls&gt;
					&lt;x:Index&gt;0&lt;/x:Index&gt;
					&lt;x:Thickness&gt;6&lt;/x:Thickness&gt;
					&lt;/x:Walls&gt;
					&lt;x:Walls&gt;
					&lt;x:Index&gt;1&lt;/x:Index&gt;
					&lt;x:Thickness&gt;6&lt;/x:Thickness&gt;
					&lt;/x:Walls&gt;
					&lt;x:Walls&gt;
					&lt;x:Index&gt;2&lt;/x:Index&gt;
					&lt;x:Thickness&gt;6&lt;/x:Thickness&gt;
					&lt;/x:Walls&gt;
					&lt;/x:Chart&gt;
					&lt;x:Legend&gt;
					&lt;x:Font&gt;
					&lt;x:Color&gt;#808080&lt;/x:Color&gt;
					&lt;x:B&gt;Automatic&lt;/x:B&gt;
					&lt;x:I&gt;Automatic&lt;/x:I&gt;
					&lt;x:U&gt;Automatic&lt;/x:U&gt;
					&lt;/x:Font&gt;
					&lt;x:Border&gt;
					&lt;x:Color&gt;#A9A9A9&lt;/x:Color&gt;
					&lt;/x:Border&gt;
					&lt;x:Placement&gt;Right&lt;/x:Placement&gt;
					&lt;/x:Legend&gt;
					&lt;x:Scaling&gt;
					&lt;x:ScaleID&gt;21387816&lt;/x:ScaleID&gt;
					&lt;/x:Scaling&gt;
					&lt;x:Scaling&gt;
					&lt;x:ScaleID&gt;21388036&lt;/x:ScaleID&gt;
					&lt;/x:Scaling&gt;
					&lt;/x:ChartSpace&gt;
					&lt;/xml&gt;">
				<param name="ScreenUpdating" value="-1">
				<param name="EnableEvents" value="-1">
				<table width='100%' cellpadding='0' cellspacing='0' border='0' height='8'>
					<tr>
						<td bgColor='#336699' height='25' width='10%'>&nbsp;</td>
						<td bgColor='#666666'width='85%'><font face='Tahoma' color='white' size='4'><b>&nbsp; Faltan: Microsoft Office Web Components</b></font></td>
					</tr>
					<tr>
						<td bgColor='#cccccc' width='15'>&nbsp;</td>
						<td bgColor='#cccccc' width='500px'><br><font face='Tahoma' size='2'>Esta página requiere Microsoft Office Web Components.<p align='center'><a href='files/owc11/setup.exe'>Haga clic aquí para instalar Microsoft Office Web Components.</a>.</p></font><p><font face='Tahoma' size='2'>Esta página también requiere Microsoft Internet Explorer versión 5.01 o posterior.</p><p align='center'><a href='http://www.microsoft.com/windows/ie_intl/es/default.htm'> Haga clic aquí para instalar la última versión de Internet Explorer</a>.</font><br>&nbsp;</td>
					</tr>
				</table>
			</object>
		</td>
	</tr>
</table>
<div align="left">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td width="223" bgcolor="#CCCCCC" align="right"><p align="right">&nbsp;</td>		
		<td width="22%" bgcolor="#CCCCCC"><p align="right"><font face="Verdana" size="1" color="#000080">Última Actualización<b>:</b></font></td>
		<td bgcolor="#CCCCCC" width="23%"><p align="left"><b><font face="Verdana" size="1" color="#000080">&nbsp;<?= date("d/m/Y H:i") ?></font></b></td>
		<td bgcolor="#CCCCCC" width="26%"><p align="right">&nbsp;</td>
	</tr>
</table>
</form>
<table border="0" width="100%">
	<tr>
		<td><p align="center">&nbsp;</td>
		<td><p align="center">&nbsp;</td>
	</tr>
</table>
</body>
</html>