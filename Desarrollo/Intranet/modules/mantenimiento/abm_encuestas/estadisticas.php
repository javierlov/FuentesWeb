<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


validarParametro(isset($_REQUEST["id"]));

$params = array(":id" => $_REQUEST["id"]);
$sql = 
	"SELECT en_activa, en_detalle, en_fechabaja, en_imagencabecera, en_mostrarimagencabecera, en_permitemodificaciones, en_titulo, TO_CHAR(en_fechaalta, 'dd/mm/yyyy') fechaalta
 		 FROM rrhh.ren_encuestas
		WHERE en_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<link href="/modules/mantenimiento/css/abm_encuestas.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/abm_encuestas.js" type="text/javascript"></script>
<iframe id="iframeExcel" name="iframeExcel" src="" style="display:none;"></iframe>
<div>
	<p id="separadores">
		<span id="tituloGrande"><?= $row["EN_TITULO"]?></span>
		<img id="imgVerGrafico" src="/modules/mantenimiento/images/excel.png" title="Respuestas por usuario" onClick="verRespuestasXUsuarios(<?= $_REQUEST["id"]?>)" />
	</p>
	<p id="separadores">
		<span id="detalleGrande"><?= $row["EN_DETALLE"]?></span>
	</p>
	<p id="separadores">
		<hr id="linea">
	</p>
<?
$params = array(":idencuesta" => $_REQUEST["id"]);
$sql = 
	"SELECT pe_id, pe_pregunta
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_fechabaja IS NULL
			AND pe_idencuesta = :idencuesta";
$stmt = DBExecSql($conn, $sql, $params);
$num = 1;
while ($row = DBGetQuery($stmt)) {
?>
	<p id="separadores">
		<span id="pregunta" onClick="seleccionarPregunta(<?= $row["PE_ID"]?>, '<?= $row["PE_PREGUNTA"]?>')">Pregunta <?= $num?> - <?= $row["PE_PREGUNTA"]?></span>
<?
	$params = array(":idpregunta" => $row["PE_ID"]);
	$sql =
		"SELECT COUNT(*)
			 FROM rrhh.rrp_respuestaspreguntas
			WHERE rp_idpregunta = :idpregunta";
	$votos = valorSql($sql, "", $params);
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
	$votantes = valorSql($sql, "", $params);
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
	if (existeSql($sql, $params)) {
?>
		(<span id="pregunta" onClick="mostrarObservaciones(<?= $row["PE_ID"]?>)" title="Clic aquí para ver las observaciones">Observaciones</span>)
<?
	}

	// Cargo las obervaciones..
	$params = array(":idpregunta" => $row["PE_ID"]);
	$sql =
		"SELECT DISTINCT op_opcion, rp_idopcion
								FROM rrhh.rrp_respuestaspreguntas, rrhh.rop_opcionespreguntas
							 WHERE rp_idopcion = op_id
								 AND rp_idpregunta = :idpregunta
								 AND TRIM(rp_observaciones) IS NOT NULL
						ORDER BY rp_idopcion";
	$stmt2 = DBExecSql($conn, $sql, $params);
	while ($row2 = DBGetQuery($stmt2)) {
?>
		<table class="tableObservaciones" id="tableObservaciones_<?= $row["PE_ID"]?>">
			<tr>
				<td colspan="3" id="tituloOpcion"><?= $row2["OP_OPCION"]?></td>
			</tr>
			<tr>
				<th id="tituloObservaciones">Fecha</th>
				<th id="tituloObservaciones">Usuario</th>
				<th id="tituloObservaciones">Observación</th>
			</tr>
<?
		$params = array(":idpregunta" => $row["PE_ID"], ":id" => $row2["RP_IDOPCION"]);
		$sql =
			"SELECT NVL(rp_fechamodif, rp_fechaalta) fecha, se_nombre, rp_observaciones
				 FROM rrhh.rrp_respuestaspreguntas, rrhh.rop_opcionespreguntas, use_usuarios
				WHERE rp_idopcion = op_id
					AND rp_usuario = se_id
					AND rp_idpregunta = :idpregunta
					AND op_id = :id
					AND TRIM(rp_observaciones) IS NOT NULL
		 ORDER BY rp_idopcion, fecha DESC, se_nombre";
		$stmt3 = DBExecSql($conn, $sql, $params);
		while ($row3 = DBGetQuery($stmt3)) {
?>
			<tr>
				<td id="celdaObservaciones"><?= $row3["FECHA"]?></td>
				<td id="celdaObservaciones"><?= $row3["SE_NOMBRE"]?></td>
				<td id="celdaObservaciones"><?= $row3["RP_OBSERVACIONES"]?></td>
			</tr>
<?
		}
?>
		</table>
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
	<span id="tipoGrafico">
		<p align="center" id="separadores">
			<span id="tipoGraficoBarra" onClick="seleccionarTipoGrafico('B')">Barra</span>
			<span id="tipoGraficoTorta" onClick="seleccionarTipoGrafico('T')">Torta</span>
		</p>
	</span>
	<p align="center">
		<img id="grafico" src="" />
	</p>
	<p id="separadores">
		<input class="btnVolver" type="button" value="" onClick="window.location.href = '/encuestas-abm-busqueda/0'" />
	</p>
</div>
<div id="observaciones" name="observaciones" style="display:none"></div>