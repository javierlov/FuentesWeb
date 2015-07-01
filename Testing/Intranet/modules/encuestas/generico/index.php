<?
function clearSesion() {
	foreach ($_SESSION as $key => $value)
		if (substr($key, 0, 9) == "ENCUESTA_")
			unset($_SESSION[$key]);
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

		return ValorSql($sql, "", $params);
	}
}

function isVistaPrevia() {
	return ((isset($_REQUEST["vp"])) and ($_REQUEST["vp"] == "T"));
}

function validateEncuestaActiva() {
	if (!isVistaPrevia()) {
		$sql =
			"SELECT 1
				 FROM rrhh.ren_encuestas
				WHERE en_activa = 'T'
					AND en_fechabaja IS NULL";
		if (!ExisteSql($sql)) {
			echo '<span class="Pie">En este momento no hay encuestas activas.</span>';
			exit;
		}
	}
}

function validateEncuestaYaCompletada($idEncuesta, $idPregunta, $permiteModificaciones) {
	if ($permiteModificaciones == "F") {
		$params = array(":idencuesta" => $idEncuesta, ":idpregunta" => $idPregunta, ":usuario" => GetUserID());
		$sql =
			"SELECT COUNT(*)
				 FROM rrhh.rrp_respuestaspreguntas
				WHERE rp_idencuesta = :idencuesta
					AND rp_idpregunta = :idpregunta
					AND rp_usuario = :usuario";
		if (ValorSql($sql, "", $params) > 0) {
			echo '<span class="Pie">Usted ya ha participado en la encuesta.</span>';
			exit;
		}
	}
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
	if (!ExisteSql($sql, $params)) {
		echo '<span class="Pie">Pregunta inválida.</span>';
		exit;
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
			echo '<span class="Pie">Pregunta inválida 2.</span>';
			exit;
		}
	}
}

function validateUltimaPregunta() {
	if ((isset($_REQUEST["fin"])) and ($_REQUEST["fin"] == "T")) {
		echo '<p id="mensajeFinal">';
		echo '<br /><img border="0" src="/images/provart_blanco.png">';
		echo '<br /><span class="Pie">Le agradece su tiempo y colaboración.</span>';
		echo '</p>';
		exit;
	}
}

function writeHTML() {
	echo "<link rel='stylesheet' type='text/css' href='/modules/encuestas/generico/css/style_encuesta.css'>";
	echo "<script>";
	echo "showTitle(true, 'ENCUESTA');";
	echo "</script>";
}


writeHTML();
validateEncuestaActiva();

if (isVistaPrevia())
	$sql =
		"SELECT *
  		 FROM rrhh.ren_encuestas
			WHERE en_id = ".$_REQUEST["encuestaid"];
else
	$sql =
		"SELECT *
  		 FROM rrhh.ren_encuestas
			WHERE en_activa = 'T'
   			AND en_fechabaja IS NULL";

$stmt = DBExecSql($conn, $sql);
$row = DBGetQuery($stmt);
?>
<script>
	showTitle(true, 'ENCUESTA  <?= $row["EN_TITULO"]?>');
</script>
<?
validateUltimaPregunta();
$idPregunta = getIdPregunta($row["EN_ID"]);
validateEncuestaYaCompletada($row["EN_ID"], $idPregunta, $row["EN_PERMITEMODIFICACIONES"]);
if (!isset($_REQUEST["prg"]))
	clearSesion();

validatePregunta($row["EN_ID"], $idPregunta);

$params = array(":id" => $idPregunta);
$sql =
	"SELECT pe_multiopcion
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_id = :id";
$multiopcion = ValorSql($sql, "", $params);

$params = array(":id" => $idPregunta);
$sql =
	"SELECT pe_respuestalibre
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_id = :id";
$respuestaLibre = ValorSql($sql, "", $params);

$params = array(":id" => $idPregunta);
$sql =
	"SELECT pe_validarcheck
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_id = :id";
$validarCheck = ValorSql($sql, "", $params);
?>
<script language="JavaScript" src="/modules/encuestas/generico/js/encuesta.js?rnd=<?= time()?>"></script>
<script>
	function contar(obj) {
		document.getElementById('numero').innerText = 1024 - obj.value.length;

		if (obj.value.length > 1024)
			obj.value = obj.value.substr(0, 1024);
	}
</script>
<?
if ($row["EN_MOSTRARIMAGENCABECERA"] == "T") {
?>
	<p id="imagenTitulo"><img border="0" src="<?= "/functions/get_image.php?file=".base64_encode(IMAGES_ENCUESTAS_CABECERA_PATH.$row["EN_IMAGENCABECERA"])?>"></p>
<?
}
?>
<iframe id="iframeEncuesta" name="iframeEncuesta" src="" style="display:none;"></iframe>
<form action="/modules/encuestas/generico/procesar_encuesta.php" id="formEncuesta" method="post" name="formEncuesta" target="iframeEncuesta" onSubmit="return validarFormEncuesta(formEncuesta)">
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
echo ValorSql($sql, "", $params);
?>
	</p>
<?
$params = array(":idpregunta" => $idPregunta, ":usuario" => GetUserID());
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
		if (ExisteSql($sql, $params))
			$preguntaFinal = false;
	}
?>
	<p id="pOpcion">
<?
	if ($row["OP_IMAGEN"] != "") {
?>
		<img border="0" class="imgOpciones" src="<?= "/functions/get_image.php?file=".base64_encode(IMAGES_ENCUESTAS_OPCIONES_PATH.$row["OP_IMAGEN"])?>">
<?
	}

	if ($respuestaLibre == "T") {
?>
		<input id="opcion<?= $row["OP_ID"]?>" name="opcion<?= $row["OP_ID"]?>" type="checkbox" value="T" checked style="display:none" />
		<label class="Respuestas" for="opcion<?= $row["OP_ID"]?>"><?= $row["OP_OPCION"]?></label><br />
		<textarea class="FormTextArea" id="observacion<?= $row["OP_ID"]?>" name="observacion<?= $row["OP_ID"]?>" rows="10" title="de observaciones" validar="true" style="margin-top:8px; width:520px" onFocus="limpiar(this)" onKeyUp="contar(this)"><?= $row["RP_OBSERVACIONES"]?></textarea>
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
		<label class="Respuestas" for="opcion"><?= $row["OP_OPCION"]?></label>
<?
		if ($row["OP_PERMITEOBSERVACION"] == "T") {
?>
			<p id="pObservacion<?= $row["OP_ID"]?>" style="display:<?= (($multiopcion == "T") or ($row["SELECCIONADO"] == "T"))?"block":"none"?>; margin-bottom:4px; margin-left:48px;">
				<input class="FormInputText" id="observacion<?= $row["OP_ID"]?>" name="observacion<?= $row["OP_ID"]?>" size="60" type="text" value="<?= $row["RP_OBSERVACIONES"]?>"></td>
			</p>
<?
		}
	}
?>
	</p>
<?
}
?>
	<p id="pButton">
<?
if ($preguntaFinal) {
?>
	<input class="BotonBlanco" id="btnVotar" name="btnVotar" type="submit" value="VOTAR">
<?
}
else {
?>
	<input class="BotonBlanco" id="btnSiguiente" name="btnSiguiente" type="submit" value="Siguiente">
<?
}
?>
	</p>
</form>