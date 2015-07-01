<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function deletePreguntasSinVinculo($idEncuesta) {
// Elimina las respuestas de preguntas que no tengan preguntas padre..
// Esto puede pasar porque en un momento se eligió una opción y en otro momento se eligió otra que lleva a otra pregunta..

	global $conn;
	global $dbError;

	$opciones = array();
	foreach ($_SESSION as $key => $value)
		if (substr($key, 0, 17) == "ENCUESTA_pregunta")
			$opciones[] = $value[1];

	$params = array(":idencuesta" => $idEncuesta, ":usuario" => getUserId());
	$sql =
		"DELETE FROM rrhh.rrp_respuestaspreguntas
					 WHERE rp_idencuesta = :idencuesta
						 AND rp_usuario = :usuario
						 AND rp_idopcion NOT IN(".implode(",", $opciones).")";
	DBExecSql($conn, $sql, $params);

	if ($dbError["offset"])
		return $dbError["message"];
}

function getPreguntaSiguiente() {
// Devuelve la pregunta que se tiene que mostrar a continuación..

	$opciones = array();
	if ($_POST["multiOpcion"] == "T") {
		foreach ($_POST as $key => $value) {
			if (substr($key, 0, 7) == "opcionH")
				$opciones[] = substr($key, 7);
			elseif (substr($key, 0, 6) == "opcion")
				$opciones[] = substr($key, 6);
		}
	}
	else
		$opciones[] = $_POST["opcion"];

	$sql =
		"SELECT op_idpreguntasiguiente
			 FROM rrhh.rop_opcionespreguntas
			WHERE op_id IN(".implode(",", $opciones).")";
 	return valorSql($sql, 0);
}

function insertRespuesta($idEncuesta, $idPregunta, $idOpcion, $observacion) {
// Hace un insert sobre la opción seleccionada..

	global $conn;
	global $dbError;

	$params = array(":idencuesta" => $idEncuesta,
									":idopcion" => $idOpcion,
									":idpregunta" => $idPregunta,
									":observaciones" => substr($observacion, 0, 1024),
									":usuario" => getUserId());
	$sql =
		"INSERT INTO rrhh.rrp_respuestaspreguntas (rp_idencuesta, rp_idpregunta, rp_idopcion, rp_usuario, rp_fechaalta, rp_observaciones)
																			 VALUES (:idencuesta, :idpregunta, :idopcion, :usuario, SYSDATE, :observaciones)";
	DBExecSql($conn, $sql, $params);

	if ($dbError["offset"])
		return $dbError["message"];
}

function isUltimaPregunta() {
// Devuelve true si ninguna de la opciones elegidas tiene una pregunta siguiente asignada..

	global $conn;

	$opciones = array();
	if ($_POST["multiOpcion"] == "T") {
		foreach ($_POST as $key => $value) {
			if (substr($key, 0, 7) == "opcionH")
				$opciones[] = substr($key, 7);
			elseif (substr($key, 0, 6) == "opcion")
				$opciones[] = substr($key, 6);
		}
	}
	else
		$opciones[] = $_POST["opcion"];

	$sql =
		"SELECT 1
			 FROM rrhh.rpe_preguntasencuesta, rrhh.rop_opcionespreguntas
			WHERE pe_id = op_idpreguntasiguiente
				AND op_id IN(".implode(",", $opciones).")";

	return (existeSql($sql))?"F":"T";
}

function updateRespuesta($idEncuesta, $idPregunta, $idOpcion, $observacion) {
// Hace un update sobre la opción seleccionada..

	global $conn;
	global $dbError;

	$params = array(":idencuesta" => $idEncuesta,
									":idopcion" => $idOpcion,
									":idpregunta" => $idPregunta,
									":observaciones" => substr($observacion, 0, 1024),
									":usuario" => getUserId());
	$sql =
		"UPDATE rrhh.rrp_respuestaspreguntas
				SET rp_fechamodif = SYSDATE,
						rp_observaciones = :observaciones
			WHERE rp_idencuesta = :idencuesta
				AND rp_idpregunta = :idpregunta
				AND rp_idopcion = :idopcion
				AND rp_usuario = :usuario";
	DBExecSql($conn, $sql, $params);

	if ($dbError["offset"])
		return $dbError["message"];
}

function saveRespuesta($idEncuesta, $idPregunta, $idOpcion, $observaciones) {
// Inserta o actualiza la respuesta según corresponda..

	$params = array(":idpregunta" => $idPregunta,
									":idopcion" => $idOpcion,
									":usuario" => getUserId());
	$sql =
		"SELECT 1
			 FROM rrhh.rrp_respuestaspreguntas
			WHERE rp_idpregunta = :idpregunta
				AND rp_idopcion = :idopcion
				AND rp_usuario = :usuario";
	if (!existeSql($sql, $params))
		$error = insertRespuesta($idEncuesta, $idPregunta, $idOpcion, $observaciones);
	else
		$error = updateRespuesta($idEncuesta, $idPregunta, $idOpcion, $observaciones);
}


$error = "";
	
// Si no está haciendo una vista previa grabo los datos..
if ($_POST["vistaPrevia"] != "T") {
	$_SESSION["ENCUESTA_idEncuesta"] = $_POST["idEncuesta"];

	if ($_POST["multiOpcion"] == "T") {
		foreach ($_POST as $key => $value) {
			if (substr($key, 0, 7) == "opcionH") {
				$idOpcion = substr($key, 7);
				$observacion = "";
				if (isset($_POST["observacion".$idOpcion]))
					$observacion = $_POST["observacion".$idOpcion];
				$_SESSION["ENCUESTA_pregunta".$_POST["idPregunta"]."_opcion".$idOpcion] = array($_POST["idPregunta"], $idOpcion, $observacion);
			}
			elseif (substr($key, 0, 6) == "opcion") {
				$idOpcion = substr($key, 6);
				$observacion = "";
				if (isset($_POST["observacion".$idOpcion]))
					$observacion = $_POST["observacion".$idOpcion];
				$_SESSION["ENCUESTA_pregunta".$_POST["idPregunta"]."_opcion".$idOpcion] = array($_POST["idPregunta"], $idOpcion, $observacion);
			}
		}
	}
	else {
		$observacion = "";
		if (isset($_POST["observacion".$_POST["opcion"]]))
			$observacion = $_POST["observacion".$_POST["opcion"]];
		$_SESSION["ENCUESTA_pregunta".$_POST["idPregunta"]."_opcion".$idOpcion] = array($_POST["idPregunta"], $_POST["opcion"], $observacion);
	}


	if ($_POST["tipoAlmacenamiento"] == "T") {		// Se almacenan al final todas las respuestas juntas..
		if (isUltimaPregunta() == "T")
			foreach ($_SESSION as $key => $value)
				if (substr($key, 0, 17) == "ENCUESTA_pregunta")
					$error = saveRespuesta($_SESSION["ENCUESTA_idEncuesta"], $value[0], $value[1], $value[2]);
	}

	if ($_POST["tipoAlmacenamiento"] == "P") {		// Se almacena pregunta a pregunta..
		if ($_POST["multiOpcion"] == "T") {
			foreach ($_POST as $key => $value) {
				if (substr($key, 0, 7) == "opcionH") {
					$idOpcion = substr($key, 7);
					$error = saveRespuesta($_POST["idEncuesta"], $_POST["idPregunta"], $idOpcion, (isset($_POST["observacion".$idOpcion]))?$_POST["observacion".$idOpcion]:"");
				}
				elseif (substr($key, 0, 6) == "opcion") {
					$idOpcion = substr($key, 6);
					$error = saveRespuesta($_POST["idEncuesta"], $_POST["idPregunta"], $idOpcion, (isset($_POST["observacion".$idOpcion]))?$_POST["observacion".$idOpcion]:"");
				}
			}
		}
		else
			$error = saveRespuesta($_POST["idEncuesta"], $_POST["idPregunta"], $_POST["opcion"], (isset($_POST["observacion".$_POST["opcion"]]))?$_POST["observacion".$_POST["opcion"]]:"");
	}

	if (($error == "") and (isUltimaPregunta() == "T"))
		$error = deletePreguntasSinVinculo($_POST["idEncuesta"]);
}
?>
<script type="text/javascript">
<?
if ($error != "")
	echo "alert('".$error."');";
else
	echo "window.parent.location.href = '/encuestas/".$_POST["idEncuesta"]."/".getPreguntaSiguiente()."/".$_POST["vistaPrevia"]."/".isUltimaPregunta()."'";
?>
</script>