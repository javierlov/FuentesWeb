<link href="/modules/encuestas/css/encuestas.css" rel="stylesheet" type="text/css" />
<?
function clearSesion() {
	foreach ($_SESSION as $key => $value)
		if (substr($key, 0, 9) == "ENCUESTA_")
			unset($_SESSION[$key]);
}

function encuestaActiva() {
	if (!isVistaPrevia()) {
		$params = array(":id" => $_REQUEST["encuestaid"]);
		$sql =
			"SELECT 1
				 FROM rrhh.ren_encuestas
				WHERE art.actualdate BETWEEN TRUNC(en_fechavigenciadesde) AND en_fechavigenciahasta
					AND en_activa = 'T'
					AND en_fechabaja IS NULL
					AND en_id = :id";
		if (!existeSql($sql, $params))
			return false;
	}

	return true;
}

function getIdPregunta($encuestaId) {
	if (isset($_REQUEST["prg"]))
		return $_REQUEST["prg"];
	else {
		$params = array(":idencuesta" => $encuestaId);
		$sql =
			"SELECT pe_id
				 FROM rrhh.rpe_preguntasencuesta
				WHERE pe_idencuesta = :idencuesta
					AND pe_fechabaja IS NULL
		 ORDER BY pe_id";

		return valorSql($sql, "", $params);
	}
}

function getTotalVotos($idPregunta, $idOpcion) {
	global $conn;

	$params = array(":idopcion" => $idOpcion, ":idpregunta" => $idPregunta);
	$sql =
		"SELECT COUNT(*)
			 FROM rrhh.rrp_respuestaspreguntas
			WHERE rp_fechabaja IS NULL
				AND rp_idpregunta = :idpregunta
				AND rp_idopcion = :idopcion";
	$votos = valorSql($sql, "", $params);

	if ($votos == 1)
		$votos = "(1 voto)";
	else
		$votos = "(".$votos." votos)";

	return $votos;
}

function isVistaPrevia() {
	return ((isset($_REQUEST["vp"])) and ($_REQUEST["vp"] == "T"));
}

function mostrarResultados() {
	global $conn;

	$params = array(":id" => $_REQUEST["encuestaid"]);
	$sql =
		"SELECT 1
			 FROM rrhh.ren_encuestas
			WHERE en_fechabaja IS NULL
				AND en_mostrarresultados = 'T'
				AND en_id = :id";
	if (existeSql($sql, $params)) {
		require_once($_SERVER["DOCUMENT_ROOT"]."/modules/encuestas/ver_resultados.php");
		return true;
	}

	return false;
}

function validateEncuestaYaCompletada($idEncuesta, $idPregunta, $permiteModificaciones) {
	if ($permiteModificaciones == "F") {
		$params = array(":idencuesta" => $idEncuesta,
										":idpregunta" => $idPregunta,
										":usuario" => getUserId());
		$sql =
			"SELECT COUNT(*)
				 FROM rrhh.rrp_respuestaspreguntas
				WHERE rp_idencuesta = :idencuesta
					AND rp_idpregunta = :idpregunta
					AND rp_usuario = :usuario";
		if (valorSql($sql, "", $params) > 0)
			return false;
	}

	return true;
}

function validatePregunta($idEncuesta, $idPregunta) {
	global $conn;

	$params = array(":id" => $idPregunta, ":idencuesta" => $idEncuesta);
	$sql =
		"SELECT 1
			 FROM rrhh.rpe_preguntasencuesta
			WHERE pe_id = :id
				AND pe_idencuesta = :idencuesta
				AND pe_fechabaja IS NULL";
	if (!existeSql($sql, $params)) {
		echo "<span class=\"pie\">Pregunta inválida.</span>";
		return false;
	}

	// Si no es la vista previa y no es la primer pregunta valido que no escriban la url en la barra de direcciones..
	if ((!isVistaPrevia()) and (isset($_REQUEST["prg"]))) {
		$params = array(":idpreguntasiguiente" => "0".$idPregunta);
		$sql =
			"SELECT op_idpregunta
				 FROM rrhh.rop_opcionespreguntas
				WHERE op_idpreguntasiguiente = :idpreguntasiguiente";
		$stmt = DBExecSql($conn, $sql, $params);
		$existe = false;
		while ($row = DBGetQuery($stmt)) {
			foreach ($_SESSION as $key => $value)
				if (substr($key, 0, 17) == "ENCUESTA_pregunta")
					if ($row["OP_IDPREGUNTA"] == $value[0]) {
						$existe = true;
						break;
					}
			if ($existe)
				break;
		}
		if (!$existe) {
			echo "<span class=\"pie\">Pregunta inválida 2.</span>";
			return false;
		}
	}

	return true;
}

function validateUltimaPregunta() {
	if ((isset($_REQUEST["fin"])) and ($_REQUEST["fin"] == "T")) {
		echo "<p id=\"mensajeFinal\"><br />";
		echo "<img src=\"/images/provart_blanco.png\" /><br /><br />";
		echo "<span class=\"pie\">Le agradece su tiempo y colaboración.</span>";
		echo "</p>";
		return false;
	}

	return true;
}


if (!encuestaActiva()) {
	if (!mostrarResultados())
		echo "<span class=\"pie\">Esta encuesta no se encuentra activa.</span>";
	return;
}

$sql =
	"SELECT *
 		 FROM rrhh.ren_encuestas
		WHERE en_id = ".$_REQUEST["encuestaid"];
$stmt = DBExecSql($conn, $sql);
$row = DBGetQuery($stmt);


if (!validateUltimaPregunta())
	return;

$idPregunta = getIdPregunta($row["EN_ID"]);

if (!validateEncuestaYaCompletada($row["EN_ID"], $idPregunta, $row["EN_PERMITEMODIFICACIONES"])) {
	if (!mostrarResultados())
		echo "<span class=\"pie\">Usted ya ha participado en la encuesta.</span>";
	return;
}

if (!isset($_REQUEST["prg"]))
	clearSesion();

if (!validatePregunta($row["EN_ID"], $idPregunta))
	return;

$params = array(":id" => $idPregunta);
$sql =
	"SELECT pe_multiopcion
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_id = :id";
$multiopcion = valorSql($sql, "", $params);

$params = array(":id" => $idPregunta);
$sql =
	"SELECT pe_respuestalibre
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_id = :id";
$respuestaLibre = valorSql($sql, "", $params);

$params = array(":id" => $idPregunta);
$sql =
	"SELECT pe_validarcheck
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_id = :id";
$validarCheck = valorSql($sql, "", $params);

if ($row["EN_MOSTRARIMAGENCABECERA"] == "T") {
?>
	<p id="imagenTitulo"><img id="imgCabecera" src="<?= "/functions/get_image.php?file=".base64_encode(IMAGES_ENCUESTAS_CABECERA_PATH.$row["EN_IMAGENCABECERA"])?>"></p>
<?
}
?>
<script src="/modules/encuestas/js/encuestas.js" type="text/javascript"></script>
<iframe id="iframeEncuesta" name="iframeEncuesta" src="" style="display:none;"></iframe>
<form action="/modules/encuestas/guardar_encuesta.php" id="formEncuesta" method="post" name="formEncuesta" target="iframeEncuesta" onSubmit="return validarFormEncuesta(formEncuesta)">
	<input id="idEncuesta" name="idEncuesta" type="hidden" value="<?= $row["EN_ID"]?>" />
	<input id="idPregunta" name="idPregunta" type="hidden" value="<?= $idPregunta?>" />
	<input id="multiOpcion" name="multiOpcion" type="hidden" value="<?= $multiopcion?>" />
	<input id="tipoAlmacenamiento" name="tipoAlmacenamiento" type="hidden" value="<?= $row["EN_TIPOALMACENAMIENTO"]?>" />
	<input id="validarCheck" name="validarCheck" type="hidden" value="<?= $validarCheck?>" />
	<input id="vistaPrevia" name="vistaPrevia" type="hidden" value="<?= (isVistaPrevia()?"T":"F")?>" />
	<p id="pregunta">
<?
$params = array(":id" => $idPregunta);
$sql =
	"SELECT pe_pregunta
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_id = :id";
echo valorSql($sql, "", $params);
?>
	</p>
<?
$params = array(":idpregunta" => $idPregunta, ":usuario" => getUserId());
$sql =
	"SELECT op_id, op_idpreguntasiguiente, op_imagen, op_opcion, op_permiteobservacion, rp_observaciones, DECODE(rp_usuario, NULL, 'F', 'T') seleccionado
		 FROM rrhh.rop_opcionespreguntas, rrhh.rrp_respuestaspreguntas
		WHERE op_id = rp_idopcion(+)
			AND op_idpregunta = :idpregunta
			AND (rp_idpregunta = :idpregunta OR rp_idpregunta IS NULL)
			AND rp_usuario(+) = :usuario
			AND op_fechabaja IS NULL
 ORDER BY op_id";
$stmt = DBExecSql($conn, $sql, $params);
$preguntaFinal = true;
while ($row = DBGetQuery($stmt)) {
	if ($preguntaFinal) {
		$params = array(":id" => intval("0".$row["OP_IDPREGUNTASIGUIENTE"]));
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
	if ($row["OP_IMAGEN"] != "") {
?>
		<img class="imgOpciones" src="<?= "/functions/get_image.php?file=".base64_encode(IMAGES_ENCUESTAS_OPCIONES_PATH.$row["OP_IMAGEN"])?>">
<?
	}

	if ($respuestaLibre == "T") {
?>
		<input checked id="opcion<?= $row["OP_ID"]?>" name="opcion<?= $row["OP_ID"]?>" style="display:none" type="checkbox" value="T" />
		<label class="respuestas" for="opcion<?= $row["OP_ID"]?>"><?= $row["OP_OPCION"]?></label>
		<br />
		<textarea class="textareaObservacion" id="observacion<?= $row["OP_ID"]?>" name="observacion<?= $row["OP_ID"]?>" rows="10" title="de observaciones" validar="true" onFocus="limpiar(this)" onKeyUp="contar(this)"><?= $row["RP_OBSERVACIONES"]?></textarea>
		<span>Caracteres restantes: <span id="numero">1024</span></span>
		<script>
			document.getElementById('observacion<?= $row["OP_ID"]?>').focus();
		</script>
<?
	}
	else {
		if ($multiopcion == "T") {
?>
			<input <?= (($row["SELECCIONADO"] == "T") or ($validarCheck == "F"))?"checked":""?> <?= ($validarCheck == "F")?"disabled":""?> id="opcion<?= $row["OP_ID"]?>" name="opcion<?= $row["OP_ID"]?>" type="checkbox" value="T" />
<?
			if ($validarCheck == "F") {
?>
				<input id="opcionH<?= $row["OP_ID"]?>" name="opcionH<?= $row["OP_ID"]?>" type="hidden" value="T" />
<?
			}
		}
		else {
?>
			<input id="opcion" name="opcion" type="radio" <?= ($row["SELECCIONADO"] == "T")?"CHECKED":""?> value="<?= $row["OP_ID"]?>" onClick="showHideObservacion(<?= $row["OP_ID"]?>)" />
<?
		}
?>
		<label class="respuestas" for="opcion"><?= $row["OP_OPCION"]?></label>
<?
		if ($row["OP_PERMITEOBSERVACION"] == "T") {
?>
			<p class="pPermiteObservacion" id="pObservacion<?= $row["OP_ID"]?>" style="display:<?= (($multiopcion == "T") or ($row["SELECCIONADO"] == "T"))?"block":"none"?>;">
				<input class="inputObservacion" id="observacion<?= $row["OP_ID"]?>" name="observacion<?= $row["OP_ID"]?>" size="60" type="text" value="<?= $row["RP_OBSERVACIONES"]?>" />
			</p>
<?
		}
	}
?>
	</p>
<?
}

if ($preguntaFinal)
	$btnId = "btnVotar";
else
	$btnId = "btnSiguiente";
?>
	<p id="pButton">
		<input id="<?= $btnId?>" name="<?= $btnId?>" type="submit" value="" />
	</p>
</form>