<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


SetDateFormatOracle("DD/MM/YYYY");

$sql = "SELECT TO_CHAR(SYSDATE, 'YYYY') FROM DUAL";
$ano = ValorSql($sql);

$params = array(":anio" => $ano, ":usuario" => GetWindowsLoginName(true));
$sql =
	"SELECT 1
		 FROM web.weu_encuesta_usuario
		WHERE eu_anio = :anio
			AND eu_fechabaja IS NULL
			AND eu_usuario = :usuario";
$usuarioHabilitado = ExisteSql($sql, $params);

if (!$usuarioHabilitado) {
?>
	<script>
		with (window.parent.document) {
			getElementById('divEncuesta').innerHTML = '<h1 style="color:#f00; margin-top:80px;">ACCESO DENEGADO</h1><h3>Usted no tiene encuestas para completar.</h3>';
			getElementById('divPaso1').style.display = 'none';
			getElementById('divPaso2').style.display = 'block';
		}
	</script>
<?
	exit;
}


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT se_descripcion
		 FROM computos.cse_sector, web.weu_encuesta_usuario
		WHERE se_id = eu_idsector
			AND eu_id = :id";
$sector = ValorSql($sql, "", $params);

$params = array(":anio" => $ano);
$sql =
	"SELECT em_descripcion, em_id, em_titulo
		 FROM web.wem_encuesta_maestro
		WHERE em_anio = :anio
			AND em_fechabaja IS NULL
 ORDER BY em_renglon";
$stmt = DBExecSql($conn, $sql, $params);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('hSectorEvaluado').innerText = 'SECTOR EVALUADO: <?= $sector?>';

		var str = '<form action="/modules/encuestas/satisfaccion_cliente_interno_2/procesar_encuesta.php" id="formEncuesta" method="post" name="formEncuesta" target="iframeEncuesta">';
		str+= '<input id="idEncuesta" name="idEncuesta" type="hidden" value="<?= $_REQUEST["id"]?>" />';
		str+= '<table border="1" cellspacing="0" width="100%">';
<?
$primerRegistro = true;
while ($row = DBGetQuery($stmt)) {
	$params = array(":id_encuesta_maestro" => $row["EM_ID"], ":id_encuesta_usuario" => $_REQUEST["id"]);
	$sql =
		"SELECT em_calificacion, em_observaciones
			 FROM web.wer_encuesta_resultado
			WHERE em_fechabaja IS NULL
				AND em_id_encuesta_maestro = :id_encuesta_maestro
				AND em_id_encuesta_usuario = :id_encuesta_usuario";
	$stmt2 = DBExecSql($conn, $sql, $params);
	$row2 = DBGetQuery($stmt2);
?>
		str+= '<tr>';
		str+= '<td style="font-weight:bold;"><?= $row["EM_TITULO"]?></td>';
		str+= '<td align="center" style="background-color:#c1c1c1;"><?= ($primerRegistro)?"Calificación":""?></td>';
		str+= '<td align="center"><?= ($primerRegistro)?"Comentarios":""?></td>';
		str+= '</tr>';
		str+= '<tr>';
		str+= '<td style="border-left:0; padding-left:4px;"><?= $row["EM_DESCRIPCION"]?></td>';
		str+= '<td align="center">';
		str+= '<input id="idOpcion_<?= $row["EM_ID"]?>" name="idOpcion_<?= $row["EM_ID"]?>" type="hidden" value="<?= $row["EM_ID"]?>" />';
		str+= '<input <?= ($primerRegistro)?"autofocus":""?> id="calificacion_<?= $row["EM_ID"]?>" name="calificacion_<?= $row["EM_ID"]?>" maxlength="2" style="width:40px;" type="text" value="<?= $row2["EM_CALIFICACION"]?>" onKeyUp="calcularPromedio()" />';
		str+= '</td>';
		str+= '<td align="center"><textarea id="comentarios_<?= $row["EM_ID"]?>" maxlength="2000" name="comentarios_<?= $row["EM_ID"]?>" style="height:80px; width:240px;"><?= $row2["EM_OBSERVACIONES"]?></textarea></td>';
		str+= '</tr>';
<?
	$primerRegistro = false;
}
?>
		str+= '<tr>';
		str+= '<td align="right" style="border-left:0; font-size:14px; font-weight:bold; padding-right:8px;">Promedio Total</td>';
		str+= '<td align="center"><input id="promedioTotal" name="promedioTotal" readonly style="background-color:#c1c1c1; width:40px;" type="text" value="" /></td>';
		str+= '<td align="center"></td>';
		str+= '</tr>';
		str+= '</table>';
		str+= '<div style="margin-top:24px;">';
		str+= '<input class="BotonBlanco" id="btnVotar" name="btnVotar" style="margin-left:46%;" type="submit" value="ENVIAR">';
		str+= '<image border="0" src="/images/boton_volver.jpg" style="cursor:pointer; margin-left:296px; vertical-align:-4px;" onClick="volver()" /></div>';
		str+= '</form>';

		getElementById('divEncuesta').innerHTML = str;
		getElementById('divPaso1').style.display = 'none';
		getElementById('divPaso2').style.display = 'block';

		window.parent.calcularPromedio();
	}
</script>