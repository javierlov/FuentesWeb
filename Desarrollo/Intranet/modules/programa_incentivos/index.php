<?
if (!isset($_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]))
	$_SESSION["USUARIO_PROGRAMA_INCENTIVOS"] = getWindowsLoginName(true);


require_once("index_combos.php");


$params = array(":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(MAX(oi_fecha_proceso))
		 FROM rrhh.roi_objetivos_individuales
		WHERE oi_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oi_fechabaja IS NULL
			AND oi_usuario = :usuario";
$fechaObjetivosIndividuales = valorSql($sql, -1, $params);

$params = array(":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(MAX(op_fecha_proceso))
		 FROM rrhh.rop_objetivos_presentismo
		WHERE op_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND op_fechabaja IS NULL
			AND op_usuario = :usuario";
$fechaPresentismo = valorSql($sql, -1, $params);

$params = array(":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(MAX(od_fecha_proceso))
		 FROM rrhh.rod_objetivos_desempeno
		WHERE od_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND od_fechabaja IS NULL
			AND od_usuario = :usuario";
$fechaEvaluacionDesempeño = valorSql($sql, -1, $params);

$params = array(":usuario" => $_SESSION["USUARIO_PROGRAMA_INCENTIVOS"]);
$sql =
	"SELECT TRUNC(MAX(oc_fecha_proceso))
		 FROM rrhh.roc_objetivos_curso
		WHERE oc_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oc_fechabaja IS NULL
			AND oc_usuario = :usuario";
$fechaCursosObligatorios = valorSql($sql, -1, $params);
?>
<link href="/modules/programa_incentivos/css/incentivos.css" rel="stylesheet" type="text/css" />
<script src="/modules/programa_incentivos/js/programa_incentivos.js" type="text/javascript"></script>

<div id="divProgramaIncentivos">
	<table cellspacing="0" cellpadding="0" style="width:925px;">
		<tr>
			<td>Usuario: <span class="user"><?= getUserName($_SESSION["USUARIO_PROGRAMA_INCENTIVOS"])?></span></td>
			<td align="right">
<!--				<select name="select">
					<option value="value1">-- ver empleados a cargo --</option>
				</select>-->
			</td>
<?
if (in_array(getWindowsLoginName(true), array("ALAPACO", "EVILA", "VDOMINGUEZ"))) {
?>
			<td></td>
			<td>Ver otro usuario:</td>
			<td><?= $comboUsuario->draw();?></td>
<?
}
?>
		</tr>
	</table>

	<br/>

	<iframe id="iframeObjetivosIndividuales" name="iframeObjetivosIndividuales" src="" style="display:none;"></iframe>
	<form action="/modules/programa_incentivos/guardar_objetivos_individuales.php" id="formObjetivosIndividuales" method="post" name="formObjetivosIndividuales" target="iframeObjetivosIndividuales">
		<div id="divObjetivosIndividuales"></div>
<?
if ($_SESSION["USUARIO_PROGRAMA_INCENTIVOS"] == getWindowsLoginName(true)) {
?>
		<table cellspacing="0" cellpadding="0" width="925px">
			<tr><td height="10px"></td></tr>
			<tr>
				<td align="right">
					<img id="imgGuardar" src="/images/botones_formularios/boton_guardar.jpg" onClick="guardarObjetivosIndividuales()" />
					<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
				</td>
			</tr>
		</table>
<?
}
?>
	</form>

	<br/>

<?
$params = array();
$sql =
	"SELECT oc_cumplimiento, oc_descripcion
		 FROM rrhh.roc_objetivos_calidad
		WHERE oc_anio = TO_CHAR(SYSDATE, 'yyyy')
			AND oc_fechabaja IS NULL";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
	<table cellspacing="0" cellpadding="0" class="tabla2" align="center">
		<tr>
			<td class="encabezado">OBJETIVO CALIDAD</td>
			<td class="titulo" style="width:10px;" rowspan="3"><b>% CUMPLIMIENTO</b><br><b><input readonly style="width:30px;" type="text" value="<?= $row["OC_CUMPLIMIENTO"]?>%" /></b></td>
		</tr>
		<tr>
			<td height="10px"></td>
		</tr>
		<tr>
			<td class="texto" height="10px"><?= $row["OC_DESCRIPCION"]?></td>
		</tr>
	</table>

	<br/>

	<iframe id="iframePresentismo" name="iframePresentismo" src="" style="display:none;"></iframe>
	<div id="divPresentismo"></div>

	<br/>

	<iframe id="iframeEvaluacionDesempeño" name="iframeEvaluacionDesempeño" src="" style="display:none;"></iframe>
	<div id="divEvaluacionDesempeño"></div>

	<br/>

	<iframe id="iframeCursosObligatorios" name="iframeCursosObligatorios" src="" style="display:none;"></iframe>
	<div id="divCursosObligatorios"></div>
</div>

<script type="text/javascript">
	cargarCursosObligatorios('<?= $fechaCursosObligatorios?>');
	cargarEvaluacionDesempeño('<?= $fechaEvaluacionDesempeño?>');
	cargarObjetivoIndividual('<?= $fechaObjetivosIndividuales?>');
	cargarPresentismo('<?= $fechaPresentismo?>');
</script>