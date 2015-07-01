<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function updateImagenCabecera($id, $folder, $img) {
	global $conn;

	$params = array(":id" => $id);
	$sql =
		"SELECT en_imagencabecera
			 FROM rrhh.ren_encuestas
			WHERE en_id = :id";
	$imgTemp = ValorSql($sql, "", $params, 0);

	if ($imgTemp != $img)
		unlink($folder.$imgTemp);

	$params = array(":imagencabecera" => $img, ":id" => $id);
	$sql =
		"UPDATE rrhh.ren_encuestas
				SET en_imagencabecera = :imagencabecera
			WHERE en_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
}

function updateImagenOpcion($id, $folder, $img) {
	global $conn;

	$params = array(":id" => $id);
	$sql =
		"SELECT op_imagen
			 FROM rrhh.rop_opcionespreguntas
			WHERE op_id = :id";
	$imgTemp = ValorSql($sql, "", $params, 0);

	if ($imgTemp != $img)
		unlink($folder.$imgTemp);

	$params = array(":imagen" => $img, ":id" => $id);
	$sql =
		"UPDATE rrhh.rop_opcionespreguntas
				SET op_imagen = :imagen
			WHERE op_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
}

function uploadImagen($img, $folder, $id, &$imgPath) {
	$tempfile = $img["tmp_name"];
	$filename = StringToLower($id."_".$img["name"], ".");

	$uploadOk = false;
	if (is_uploaded_file($tempfile))
		if (move_uploaded_file($tempfile, $folder.$filename)) {
			$uploadOk = true;
			$imgPath = $filename;
		}

	if (!$uploadOk)
		echo "<script>alert('Ocurrió un error al guardar la imagen.');</script>";

	return $uploadOk;
}


try {
	$activa = (isset($_POST["activa"])?"T":"F");
	$imgPath = "";
	$mostrarImagen = (isset($_POST["mostrarImagen"])?"T":"F");
	$permiteModificaciones = (isset($_POST["permiteModificaciones"])?"T":"F");

	if ($activa == "T") {		// Si esta encuesta está activa desactivo TODAS las otras..
		$sql =
			"UPDATE rrhh.ren_encuestas
					SET en_activa = 'F'";
		DBExecSql($conn, $sql, array(), OCI_DEFAULT);
	}


	if ($_POST["tipoOp"] == "A") {		// Alta..
		$params = array(":id" => -1,
										":activa" => $activa,
										":detalle" => $_POST["detalle"],
										":mostrarimagen" => $mostrarImagen,
										":permitemodificaciones" => $permiteModificaciones,
										":tipoalmacenamiento" => $_POST["tipoAlmacenamiento"],
										":titulo" => $_POST["titulo"],
										":usualta" => GetWindowsLoginName(true));
		$sql =
			"INSERT INTO rrhh.ren_encuestas (en_id, en_activa, en_detalle, en_fechaalta, en_mostrarimagencabecera,
																			 en_permitemodificaciones, en_tipoalmacenamiento, en_titulo, en_usualta)
															 VALUES (:id, :activa, :detalle, SYSDATE, :mostrarimagen,
																			 :permitemodificaciones, :tipoalmacenamiento, :titulo, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(en_id) FROM rrhh.ren_encuestas";
		$encuestaId = ValorSql($sql, "", array(), 0);
	}

	if ($_POST["tipoOp"] == "M") {		// Modificación..
		$encuestaId = $_POST["id"];
		$params = array(":activa" => $activa,
										":detalle" => $_POST["detalle"],
										":id" => $encuestaId,
										":mostrarimagen" => $mostrarImagen,
										":permitemodificaciones" => $permiteModificaciones,
										":tipoalmacenamiento" => $_POST["tipoAlmacenamiento"],
										":titulo" => $_POST["titulo"],
										":usumodif" => GetWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.ren_encuestas
					SET en_activa = :activa,
							en_detalle = :detalle,
							en_fechamodif = SYSDATE,
							en_mostrarimagencabecera = :mostrarimagen,
							en_permitemodificaciones = :permitemodificaciones,
							en_tipoalmacenamiento = :tipoalmacenamiento,
							en_titulo = :titulo,
							en_usumodif = :usumodif
			  WHERE en_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	
	if (($_POST["tipoOp"] == "A") or ($_POST["tipoOp"] == "M")) {
		// Actualizo las imagenes..
		if ($_FILES["imagen"]["name"] != "")		// Si existe la imagen, la subo..
			if (uploadImagen($_FILES["imagen"], IMAGES_ENCUESTAS_CABECERA_PATH, $encuestaId, $imgPath))
				updateImagenCabecera($encuestaId, IMAGES_ENCUESTAS_CABECERA_PATH, $imgPath);
			else
				exit;


		// Elimino los usuarios habilitados asociados a esta encuesta..
		$params = array(":idencuesta" => $encuestaId);
		$sql =
			"DELETE FROM rrhh.rue_usuariosxencuestas
						 WHERE ue_idencuesta = :idencuesta";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		// Agrego los usuarios habilitados asociados a esta encuesta..
		for ($i=0; $i<count($_REQUEST["usuarios"]); $i++) {
			$params = array(":idencuesta" => $encuestaId, ":idusuario" => $_REQUEST["usuarios"][$i]);
			$sql =
				"INSERT INTO rrhh.rue_usuariosxencuestas (ue_idencuesta, ue_idusuario)
																					VALUES (:idencuesta, :idusuario)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}


		// Actualizo las preguntas y opciones..
		$pregunta = 1;
		while (isset($_POST["pregunta".$pregunta])) {		// Actualizo las preguntas..
			$preguntaId = $_POST["pregunta".$pregunta."Id"];
			$multiOpcion = ($_POST["pregunta".$pregunta."Multi"])?"T":"F";
			$respuestaLibre = ($_POST["pregunta".$pregunta."Libre"])?"T":"F";
			$validarCheck = ($_POST["pregunta".$pregunta."ValidarCheck"])?"T":"F";

			if ($_POST["pregunta".$pregunta."Baja"] == "T") {		// Baja de la pregunta..
				$params = array(":id" => $preguntaId, ":usubaja" => GetWindowsLoginName(true));
				$sql =
					"UPDATE rrhh.rpe_preguntasencuesta
							SET pe_fechabaja = SYSDATE,
									pe_usubaja = :usubaja
					  WHERE pe_id = :id";
 				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
			else if ($_POST["pregunta".$pregunta."Id"] == "-1") {		// Alta de la pregunta..
				$params = array(":id" => -1,
												":idencuesta" => $encuestaId,
												":multiopcion" => $multiOpcion,
												":pregunta" => $_POST["pregunta".$pregunta],
												":respuestalibre" => $respuestaLibre,
												":usualta" => GetWindowsLoginName(true),
												":validarcheck" => $validarCheck);
				$sql =
					"INSERT INTO rrhh.rpe_preguntasencuesta (pe_id, pe_fechaalta, pe_idencuesta, pe_multiopcion, pe_pregunta, pe_respuestalibre, pe_usualta, pe_validarcheck)
																					 VALUES (:id, SYSDATE, :idencuesta, :multiopcion, :pregunta, :respuestalibre, :usualta, :validarcheck)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);

				$sql = "SELECT MAX(pe_id) FROM rrhh.rpe_preguntasencuesta";
				$preguntaId = ValorSql($sql, "", array(), 0);
			}
			else {		// Modificación de la pregunta..
				$params = array(":id" => $preguntaId,
												":multiopcion" => $multiOpcion,
												":pregunta" => $_POST["pregunta".$pregunta],
												":respuestalibre" => $respuestaLibre,
												":usumodif" => GetWindowsLoginName(true),
												":validarcheck" => $validarCheck);
				$sql =
					"UPDATE rrhh.rpe_preguntasencuesta
							SET pe_fechamodif = SYSDATE,
									pe_multiopcion = :multiopcion,
									pe_pregunta = :pregunta,
									pe_respuestalibre = :respuestalibre,
									pe_usumodif = :usumodif,
									pe_validarcheck = :validarcheck
					  WHERE pe_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}

			$opcion = 1;
			while (isset($_POST["pregunta".$pregunta."Opcion".$opcion])) {		// Actualizo las opciones..
				$permiteObservacion = (isset($_POST["poPregunta".$pregunta."Opcion".$opcion])?"T":"F");

				$preguntaSiguiente = NULL;
				$updateImagen = false;
				if (isset($_POST["pregunta".$_POST["psPregunta".$pregunta."Opcion".$opcion]."Id"]))
					$preguntaSiguiente = $_POST["pregunta".$_POST["psPregunta".$pregunta."Opcion".$opcion]."Id"];

				if ($_POST["pregunta".$pregunta."Opcion".$opcion."Baja"] == "T") {		// Baja de la opción..
					$params = array(":usubaja" => GetWindowsLoginName(true), ":id" => $_POST["pregunta".$pregunta."Opcion".$opcion."Id"]);
					$sql =
						"UPDATE rrhh.rop_opcionespreguntas
								SET op_fechabaja = SYSDATE,
										op_usubaja = :usubaja
						  WHERE op_id = :id";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}
				else if ($_POST["pregunta".$pregunta."Opcion".$opcion."Id"] == "-1") {		// Alta de la opción..
					$updateImagen = true;
					$params = array(":id" => -1,
													":idpregunta" => $preguntaId,
													":idpreguntasiguiente" => $preguntaSiguiente,
													":opcion" => $_POST["pregunta".$pregunta."Opcion".$opcion],
													":permiteobservacion" => $permiteObservacion,
													":usualta" => GetWindowsLoginName(true));
					$sql =
						"INSERT INTO rrhh.rop_opcionespreguntas (op_id, op_fechaalta, op_idpregunta, op_idpreguntasiguiente, op_opcion,
																										 op_permiteobservacion, op_usualta)
																						 VALUES (:id, SYSDATE, :idpregunta, :idpreguntasiguiente, :opcion,
																										 :permiteobservacion, :usualta)";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);

					$sql = "SELECT MAX(op_id) FROM rrhh.rop_opcionespreguntas";
					$opcionId = ValorSql($sql, "", array(), 0);
				}
				else {		// Modificación de la opción..
					$opcionId = $_POST["pregunta".$pregunta."Opcion".$opcion."Id"];
					$updateImagen = true;
					$params = array(":id" => $opcionId,
													":idpreguntasiguiente" => $preguntaSiguiente,
													":opcion" => $_POST["pregunta".$pregunta."Opcion".$opcion],
													":permiteobservacion" => $permiteObservacion,
													":usumodif" => GetWindowsLoginName(true));
					$sql =
						"UPDATE rrhh.rop_opcionespreguntas
								SET op_idpreguntasiguiente = :idpreguntasiguiente,
										op_fechamodif = SYSDATE,
										op_opcion = :opcion,
										op_permiteobservacion = :permiteobservacion,
										op_usumodif = :usumodif
						  WHERE op_id = :id";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}

				if ($updateImagen) {
					if ($_FILES["piPregunta".$pregunta."Opcion".$opcion]["name"] != "")		// Si existe la imagen, la subo..
						if (uploadImagen($_FILES["piPregunta".$pregunta."Opcion".$opcion], IMAGES_ENCUESTAS_OPCIONES_PATH, $opcionId, $imgPath))
							updateImagenOpcion($opcionId, IMAGES_ENCUESTAS_OPCIONES_PATH, $imgPath);
						else
							exit;
				}

				$opcion++;
			}
			$pregunta++;
		}
	}


	if ($_POST["tipoOp"] == "B") {		// Baja..
		$params = array(":usubaja" => GetWindowsLoginName(true), ":id" => $_POST["id"]);
		$sql =
			"UPDATE rrhh.ren_encuestas
					SET en_fechabaja = SYSDATE,
							en_usubaja = :usubaja
			  WHERE en_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	window.parent.location.href = '/index.php?pageid=48&buscar=yes';
</script>