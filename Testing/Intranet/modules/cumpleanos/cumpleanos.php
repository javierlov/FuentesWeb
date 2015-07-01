<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");

function setPosicionDia($dia) {
	if ($dia < 10)
		return "17";
	else
		return "10";
}
?>
<script>
	showTitle(true, 'CUMPLEAÑOS DEL MES');
</script>
<body link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
	<div align="center">
		<table border="0" height="1" cellspacing="1" width="50%">
			<tr>
				<th style="padding-left:5px" align="left" bgcolor="#00539B" class="FormLabelBlanco10">Nombre</th>
				<th bgcolor="#00539B" class="FormLabelBlanco10" width="104">Fecha</th>
			</tr>
<?
$sql =
	"SELECT TRIM(LOWER(TO_CHAR(TO_DATE(TO_CHAR(se_fechacumple, 'DD') || '/' ||
																			TO_CHAR(se_fechacumple, 'MM') || '/' ||
																			TO_CHAR(SYSDATE, 'YYYY'), 'dd/mm/yyyy'), 'DAY'))) dia, se_fechacumple,
				  TO_NUMBER(TO_CHAR(se_fechacumple, 'DD')) numerodia, se_id, se_nombre
		FROM art.use_usuarios
	 WHERE TO_CHAR(se_fechacumple, 'MM') = TO_CHAR(SYSDATE, 'MM')
		  AND se_fechabaja IS NULL
		  AND TO_NUMBER(TO_CHAR(se_fechacumple, 'YYYY')) > 1910
 ORDER BY numerodia, se_buscanombre";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
?>
			<tr bgcolor="#807F84" class="FondoOnMouseOver">
				<td style="padding-left:5px" align="left"><font size="2"><a href="/index.php?pageid=56&id=<?= $row["SE_ID"]?>" style="text-decoration: none"><?= $row["SE_NOMBRE"]?></a></font></td>
				<td align="left" style="cursor:default;">
					<span></span>
					<span>
						<font color="#FFFFFF" size="2" style="margin-left:<?= setPosicionDia($row["NUMERODIA"])?>px;"><?= $row["NUMERODIA"]."&nbsp;&nbsp;(".$row["DIA"].")"?></font>
					</span>
				</td>
			</tr>
<?
}
?>
		</table>
	</div>