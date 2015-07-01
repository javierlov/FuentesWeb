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

if (isset($_REQUEST["mes"])) {
	if (strlen($_REQUEST["mes"]) == 1)
		$_REQUEST["mes"] = "0".$_REQUEST["mes"];
}
else
	$_REQUEST["mes"] = date("m");

require_once("cumpleanos_combos.php");
?>
<div>
	<label for="mes">Mes</label>
	<?= $comboMes->draw();?>
</div>
<div>
	<label for="mes">Cantidad</label>
	<span id="spanCantidad"></span>
</div>
<div align="center">
	<table border="0" height="1" cellspacing="1" width="50%">
		<tr>
			<th align="left" bgcolor="#00539b" style="color:#fff; padding-left:5px;">Nombre</th>
			<th bgcolor="#00539b" style="color:#fff; padding-left:5px;" width="104">Fecha</th>
		</tr>
<?
$params = array(":mes" => $_REQUEST["mes"]);
$sql =
	"SELECT TRIM(LOWER(TO_CHAR(TO_DATE(TO_CHAR(se_fechacumple, 'DD') || '/' ||
																			TO_CHAR(se_fechacumple, 'MM') || '/' ||
																			TO_CHAR(SYSDATE, 'YYYY'), 'dd/mm/yyyy'), 'DAY'))) dia, se_fechacumple,
				  TO_NUMBER(TO_CHAR(se_fechacumple, 'DD')) numerodia, se_id, se_nombre
		FROM art.use_usuarios
	 WHERE TO_CHAR(se_fechacumple, 'MM') = :mes
		  AND se_fechabaja IS NULL
		  AND TO_NUMBER(TO_CHAR(se_fechacumple, 'YYYY')) > 1910
 ORDER BY numerodia, se_buscanombre";
$stmt = DBExecSql($conn, $sql, $params);
$total = 0;
while ($row = DBGetQuery($stmt)) {
?>
		<tr bgcolor="#ade">
			<td align="left" style="padding-left:5px"><a href="/index.php?pageid=56&id=<?= $row["SE_ID"]?>" style="text-decoration:none;"><?= $row["SE_NOMBRE"]?></a></td>
			<td align="left" style="cursor:default;">
				<span>
					<font style="margin-left:<?= setPosicionDia($row["NUMERODIA"])?>px;"><?= $row["NUMERODIA"]."&nbsp;&nbsp;(".$row["DIA"].")"?></font>
				</span>
			</td>
		</tr>
<?
	$total++;
}
?>
	</table>
</div>
<script>
	document.getElementById('spanCantidad').innerHTML = '<?= $total	?>';
</script>