<?
$params = array(":id" => $_REQUEST["encuestaid"]);
$sql =
	"SELECT en_detalle, en_imagencabecera, en_mostrarimagencabecera, en_titulo
		 FROM rrhh.ren_encuestas
		WHERE en_id = :id";
$stmtEncuesta = DBExecSql($conn, $sql, $params);
while ($rowEncuesta = DBGetQuery($stmtEncuesta)) {
?>
	<h1><?= $rowEncuesta["EN_TITULO"]?></h1>
	<h2><?= $rowEncuesta["EN_DETALLE"]?></h2>
<?
	if ($rowEncuesta["EN_MOSTRARIMAGENCABECERA"] == "T") {
?>
		<p id="imagenTitulo"><img id="imgCabecera" src="<?= "/functions/get_image.php?file=".base64_encode(IMAGES_ENCUESTAS_CABECERA_PATH.$rowEncuesta["EN_IMAGENCABECERA"])?>"></p>
<?
	}

	$params = array(":idencuesta" => $_REQUEST["encuestaid"]);
	$sql =
		"SELECT pe_id, pe_multiopcion, pe_pregunta, pe_respuestalibre, pe_validarcheck
			 FROM rrhh.rpe_preguntasencuesta
			WHERE pe_fechabaja IS NULL
				AND pe_idencuesta = :idencuesta
	 ORDER BY pe_orden, pe_id";
	$stmtPregunta = DBExecSql($conn, $sql, $params);
	while ($rowPregunta = DBGetQuery($stmtPregunta)) {
?>
		<p id="pregunta"><?= $rowPregunta["PE_PREGUNTA"]?></p>
<?
		$params = array(":idpregunta" => $rowPregunta["PE_ID"], ":usuario" => getUserId());
		$sql =
			"SELECT op_id, op_idpreguntasiguiente, op_imagen, op_opcion, op_permiteobservacion, rp_observaciones, DECODE(rp_usuario, NULL, 'F', 'T') seleccionado
				 FROM rrhh.rop_opcionespreguntas, rrhh.rrp_respuestaspreguntas
				WHERE op_id = rp_idopcion(+)
					AND op_idpregunta = :idpregunta
					AND (rp_idpregunta = :idpregunta OR rp_idpregunta IS NULL)
					AND rp_usuario(+) = :usuario
					AND op_fechabaja IS NULL
		 ORDER BY op_id";
		$stmtRespuesta = DBExecSql($conn, $sql, $params);
		$preguntaFinal = true;
		while ($rowRespuesta = DBGetQuery($stmtRespuesta)) {
			if ($preguntaFinal) {
				$params = array(":id" => intval("0".$rowRespuesta["OP_IDPREGUNTASIGUIENTE"]));
				$sql =
					"SELECT 1
						 FROM rrhh.rpe_preguntasencuesta
						WHERE pe_id = :id
							AND pe_fechabaja IS NULL";
				if (existeSql($sql, $params))
					$preguntaFinal = false;
			}
?>
			<p id="pOpcion">
<?
			if ($rowRespuesta["OP_IMAGEN"] != "") {
?>
				<img class="imgOpciones" src="<?= "/functions/get_image.php?file=".base64_encode(IMAGES_ENCUESTAS_OPCIONES_PATH.$rowRespuesta["OP_IMAGEN"])?>">
<?
			}

			if ($rowPregunta["PE_RESPUESTALIBRE"] == "T") {
?>
				<input checked disabled id="opcion<?= $rowRespuesta["OP_ID"]?>" name="opcion<?= $rowRespuesta["OP_ID"]?>" style="display:none" type="checkbox" value="T" />
				<label class="respuestas" for="opcion<?= $rowRespuesta["OP_ID"]?>"><?= $rowRespuesta["OP_OPCION"]?></label>
				<br />
				<textarea class="textareaObservacion" disabled id="observacion<?= $rowRespuesta["OP_ID"]?>" name="observacion<?= $rowRespuesta["OP_ID"]?>" rows="10" title="de observaciones"><?= $rowRespuesta["RP_OBSERVACIONES"]?></textarea>
<?
			}
			else {
				if ($rowPregunta["PE_MULTIOPCION"] == "T") {
?>
					<input <?= (($rowRespuesta["SELECCIONADO"] == "T") or ($rowPregunta["PE_VALIDARCHECK"] == "F"))?"checked":""?> disabled id="opcion<?= $rowRespuesta["OP_ID"]?>" name="opcion<?= $rowRespuesta["OP_ID"]?>" type="checkbox" value="T" />
<?
				}
				else {
?>
					<input disabled id="opcion<?= $rowRespuesta["OP_ID"]?>" name="opcion<?= $rowRespuesta["OP_ID"]?>" type="radio" <?= ($rowRespuesta["SELECCIONADO"] == "T")?"checked":""?> value="<?= $rowRespuesta["OP_ID"]?>" />
<?
				}
?>
				<label class="respuestas" for="opcion"><?= $rowRespuesta["OP_OPCION"]?></label>
				<span class="spanEncuestasCantidadVotos"><?= getTotalVotos($rowPregunta["PE_ID"], $rowRespuesta["OP_ID"])?></span>
<?
				if ($rowRespuesta["OP_PERMITEOBSERVACION"] == "T") {
?>
					<p class="pPermiteObservacion" id="pObservacion<?= $rowRespuesta["OP_ID"]?>" style="display:<?= (($multiopcion == "T") or ($rowRespuesta["SELECCIONADO"] == "T"))?"block":"none"?>;">
						<input class="inputObservacion" disabled id="observacion<?= $rowRespuesta["OP_ID"]?>" name="observacion<?= $rowRespuesta["OP_ID"]?>" type="text" value="<?= $rowRespuesta["RP_OBSERVACIONES"]?>" />
					</p>
<?
				}
			}
?>
			</p>
<?
		}
	}
}
?>
<script type="text/javascript">
	document.getElementById('divTituloSeccion').innerHTML+= ' - RESULTADOS';
</script>