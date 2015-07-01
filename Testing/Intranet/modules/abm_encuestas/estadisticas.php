<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


validarParametro(isset($_REQUEST["encuestaid"]));

$params = array(":id" => $_REQUEST["encuestaid"]);
$sql = 
	"SELECT en_activa, en_detalle, en_fechabaja, en_imagencabecera, en_mostrarimagencabecera, en_permitemodificaciones, en_titulo, TO_CHAR(en_fechaalta, 'dd/mm/yyyy') fechaalta
		 FROM rrhh.ren_encuestas
		WHERE en_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<link href="/modules/abm_encuestas/css/style_encuestas.css" rel="stylesheet" type="text/css" />
<link href="/js/popup/dhtmlwindow.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/popup/dhtmlwindow.js"></script>
<script language="JavaScript" src="/modules/abm_encuestas/js/encuesta.js"></script>
<script>
	function showObservaciones(idPregunta, numPregunta) {
		if ((divWin == null) || (divWin.style.display == 'none')) {
			medioancho = 16;
			medioalto = document.body.offsetHeight - 280;
			divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Observaciones', 'width=760px,height=200px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
		}
		divWin.load('iframe', '/modules/abm_encuestas/observaciones.php?preguntaid=' + idPregunta, 'Pregunta ' + numPregunta + ' - Observaciones');
		divWin.show();
	}

	var pregunta = '';
	var preguntaid = 0;
	var tipoGrafico = 'T';

	divWin = null;

	showTitle(true, 'ABM ENCUESTAS');
</script>
<iframe id="iframeExcel" name="iframeExcel" src="" style="display:none;"></iframe>
<div>
	<p id="separadores" style="margin-left:64px;">
		<span id="tituloGrande"><?= $row["EN_TITULO"]?></span>
		<img alt="Respuestas por usuario" src="/images/excel.png" style="cursor:hand; margin-left:16px;" onClick="document.getElementById('iframeExcel').src = '/modules/abm_encuestas/respuestas_por_usuarios.php?id=<?= $_REQUEST["encuestaid"]?>'" />
	</p>
	<p id="separadores" style="margin-left:64px;">
		<span id="detalleGrande"><?= $row["EN_DETALLE"]?></span>
	</p>
	<p id="separadores">
		<hr id="linea">
	</p>
<?
$params = array(":idencuesta" => $_REQUEST["encuestaid"]);
$sql = 
	"SELECT pe_id, pe_pregunta
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_fechabaja IS NULL
			AND pe_idencuesta = :idencuesta";
$stmt = DBExecSql($conn, $sql, $params);
$num = 1;
while ($row = DBGetQuery($stmt)) {
?>
	<p id="separadores" style="margin-left:64px;">
		<span id="pregunta" onClick="seleccionarPregunta(<?= $row["PE_ID"]?>, '<?= $row["PE_PREGUNTA"]?>')">Pregunta <?= $num?> - <?= $row["PE_PREGUNTA"]?></span>
<?
	$params = array(":idpregunta" => $row["PE_ID"]);
	$sql =
		"SELECT COUNT(*)
			 FROM rrhh.rrp_respuestaspreguntas
			WHERE rp_idpregunta = :idpregunta";
 	$votos = 	ValorSql($sql, "", $params);
 	if ($votos == 0)
 		$votos = "Sin votos.";
 	elseif ($votos == 1)
 		$votos = "1 voto.";
 	else
 		$votos = $votos." votos.";
?>
	<span id="votos"><?= $votos?></span>
<?
	$params = array(":idpregunta" => $row["PE_ID"]);
	$sql =
		"SELECT COUNT(*)
			 FROM (SELECT DISTINCT rp_usuario
							 FROM rrhh.rrp_respuestaspreguntas
							WHERE rp_idpregunta = :idpregunta)";
 	$votantes = 	ValorSql($sql, "", $params);
 	if ($votantes == 0)
 		$votantes = "Sin votantes.";
 	elseif ($votantes == 1)
 		$votantes = "1 votantes.";
 	else
 		$votantes = $votantes." votantes.";
?>
	<span id="votantes"><?= $votantes?></span>
<?
	$params = array(":idpregunta" => $row["PE_ID"]);
	$sql =
		"SELECT 1
			 FROM rrhh.rrp_respuestaspreguntas
			WHERE rp_idpregunta = :idpregunta
				AND TRIM(rp_observaciones) IS NOT NULL";
	if (ExisteSql($sql, $params)) {
?>
		(<span id="pregunta" onClick="showObservaciones(<?= $row["PE_ID"]?>, <?= $num?>)">Observaciones</span>)
<?
	}
?>
	</p>
<?
	$num++;
}
?>
	<p id="separadores">
		<hr id="linea">
	</p>
	<p align="center" id="separadores">
		<span id="tipoGrafico">
			<span id="tipoGraficoBarra" style="cursor:pointer;" onClick="seleccionarTipoGrafico('B')">Barra</span>
			<span id="tipoGraficoTorta" style="margin-left:16px; margin-right:16px; cursor:pointer;" onClick="seleccionarTipoGrafico('T')">Torta</span>
		</span>
	</p>
	<p align="center">
		<img id="grafico" src="/modules/abm_encuestas/ver_grafico.php?test=2" />
	</p>
	<p id="separadores">
		<hr id="linea">
	</p>
	<p id="separadores" style="margin-left:616px;">
		<input class="BotonBlanco" name="btnVolver" type="button" value="Volver" onClick="history.back()">
	</p>
</div>
<div id="observaciones" name="observaciones" style="display:none"></div>