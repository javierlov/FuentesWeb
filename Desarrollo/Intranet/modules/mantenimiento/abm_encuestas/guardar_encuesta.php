<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function moverImagen($encuestaId, $img) {
	global $conn;

	if ($img != "") {
		$fileOrigen = IMAGES_EDICION_PATH.$img;
		$partes_ruta = pathinfo($img);
		$filename = $encuestaId.".".$partes_ruta["extension"];
		$fileDest = IMAGES_ENCUESTAS_CABECERA_PATH.$filename;

		unlink($fileDest);
		if (rename($fileOrigen, $fileDest)) {
			$params = array(":id" => $encuestaId,
											":imagencabecera" => $filename);
			$sql =
				"UPDATE rrhh.ren_encuestas
						SET en_imagencabecera = :imagencabecera
					WHERE en_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
		else
			unlink($fileOrigen);
	}
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

function validar() {
	global $mostrarEnPortada;

	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["detalle"] == "") {
		echo "errores+= '- El campo Detalle es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["vigenciaDesde"] == "") {
		echo "errores+= '- El campo Vigencia Desde es obligatorio.<br />';";
		$errores = true;
	}
	elseif (!isFechaValida($_POST["vigenciaDesde"])) {
		echo "errores+= '- El campo Vigencia Desde debe ser una fecha válida.<br />';";
		$errores = true;
	}

	if ($_POST["vigenciaHasta"] == "") {
		echo "errores+= '- El campo Vigencia Hasta es obligatorio.<br />';";
		$errores = true;
	}
	elseif (!isFechaValida($_POST["vigenciaHasta"])) {
		echo "errores+= '- El campo Vigencia Hasta debe ser una fecha válida.<br />';";
		$errores = true;
	}

	if (dateDiff($_POST["vigenciaHasta"], $_POST["vigenciaDesde"]) > 0) {
		echo "errores+= '- La Vigencia Hasta debe ser mayor a la Vigencia Desde.<br />';";
		$errores = true;
	}


	if ($errores) {
		echo "body.style.cursor = 'default';";
		echo "getElementById('btnGuardar').style.display = 'inline';";
		echo "getElementById('imgProcesando').style.display = 'none';";
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErroresForm').style.display = 'block';";
		echo "getElementById('foco').style.display = 'block';";
		echo "getElementById('foco').focus();";
		echo "getElementById('foco').style.display = 'none';";
	}
	else {
		echo "getElementById('divErroresForm').style.display = 'none';";
	}

	echo "}";
	echo "</script>";

	return !$errores;
}


try {
	$activa = (isset($_POST["activa"])?"T":"F");
	$mostrarImagen = (isset($_POST["mostrarImagen"])?"T":"F");
	$mostrarResultados = (isset($_POST["mostrarResultados"])?"T":"F");
	$permiteModificaciones = (isset($_POST["permiteModificaciones"])?"T":"F");

	if (!hasPermiso(25))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;


	if ($activa == "T") {		// Si esta encuesta está activa desactivo TODAS las otras..
		$sql =
			"UPDATE rrhh.ren_encuestas
					SET en_activa = 'F'";
		DBExecSql($conn, $sql, array(), OCI_DEFAULT);
	}

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":activa" => $activa,
										":detalle" => $_POST["detalle"],
										":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":mostrarimagen" => $mostrarImagen,
										":mostrarresultados" => $mostrarResultados,
										":permitemodificaciones" => $permiteModificaciones,
										":tipoalmacenamiento" => $_POST["tipoAlmacenamiento"],
										":titulo" => $_POST["titulo"],
										":usualta" => getWindowsLoginName(true));
		$sql =
			"INSERT INTO rrhh.ren_encuestas(en_activa, en_detalle, en_fechaalta, en_fechavigenciadesde, en_fechavigenciahasta, en_id, en_mostrarimagencabecera,
																			en_mostrarresultados, en_permitemodificaciones, en_tipoalmacenamiento, en_titulo, en_usualta)
															VALUES (:activa, :detalle, SYSDATE, TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'), TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'), -1, :mostrarimagen,
																			:mostrarresultados, :permitemodificaciones, :tipoalmacenamiento, :titulo, :usualta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(en_id) FROM rrhh.ren_encuestas";
		$encuestaId = valorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$encuestaId = $_POST["id"];
		$params = array(":activa" => $activa,
										":detalle" => $_POST["detalle"],
										":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":id" => $encuestaId,
										":mostrarimagen" => $mostrarImagen,
										":mostrarresultados" => $mostrarResultados,
										":permitemodificaciones" => $permiteModificaciones,
										":tipoalmacenamiento" => $_POST["tipoAlmacenamiento"],
										":titulo" => $_POST["titulo"],
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.ren_encuestas
					SET en_activa = :activa,
							en_detalle = :detalle,
							en_fechamodif = SYSDATE,
							en_fechavigenciadesde = TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'),
							en_fechavigenciahasta = TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'),
							en_mostrarimagencabecera = :mostrarimagen,
							en_mostrarresultados = :mostrarresultados,
							en_permitemodificaciones = :permitemodificaciones,
							en_tipoalmacenamiento = :tipoalmacenamiento,
							en_titulo = :titulo,
							en_usumodif = :usumodif
			  WHERE en_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}


	// Muevo la imagen de cabecera..
	moverImagen($encuestaId, $_POST["fileImgCabecera"]);


	// Elimino los usuarios habilitados asociados a esta encuesta..
	$params = array(":idencuesta" => $encuestaId);
	$sql =
		"DELETE FROM rrhh.rue_usuariosxencuestas
					 WHERE ue_idencuesta = :idencuesta";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	// Agrego los usuarios habilitados asociados a esta encuesta..
	for ($i=0; $i<count($_POST["usuarios"]); $i++) {
		$params = array(":idencuesta" => $encuestaId, ":idusuario" => $_POST["usuarios"][$i]);
		$sql =
			"INSERT INTO rrhh.rue_usuariosxencuestas (ue_idencuesta, ue_idusuario)
																				VALUES (:idencuesta, :idusuario)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}


	// Actualizo las preguntas y opciones..
	$pregunta = 1;
	while (isset($_POST["pregunta".$pregunta])) {		// Actualizo las preguntas..
		$preguntaId = $_POST["pregunta".$pregunta."Id"];
		$multiOpcion = isset($_POST["pregunta".$pregunta."Multi"])?"T":"F";
		$respuestaLibre = isset($_POST["pregunta".$pregunta."Libre"])?"T":"F";
		$validarCheck = isset($_POST["pregunta".$pregunta."ValidarCheck"])?"T":"F";

		if ($_POST["pregunta".$pregunta."Baja"] == "T") {		// Baja de la pregunta..
			$params = array(":id" => $preguntaId, ":usubaja" => getWindowsLoginName(true));
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
											":usualta" => getWindowsLoginName(true),
											":validarcheck" => $validarCheck);
			$sql =
				"INSERT INTO rrhh.rpe_preguntasencuesta (pe_id, pe_fechaalta, pe_idencuesta, pe_multiopcion, pe_pregunta, pe_respuestalibre, pe_usualta, pe_validarcheck)
																				 VALUES (:id, SYSDATE, :idencuesta, :multiopcion, :pregunta, :respuestalibre, :usualta, :validarcheck)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			$sql = "SELECT MAX(pe_id) FROM rrhh.rpe_preguntasencuesta";
			$preguntaId = valorSql($sql, "", array(), 0);
		}
		else {		// Modificación de la pregunta..
			$params = array(":id" => $preguntaId,
											":multiopcion" => $multiOpcion,
											":pregunta" => $_POST["pregunta".$pregunta],
											":respuestalibre" => $respuestaLibre,
											":usumodif" => getWindowsLoginName(true),
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
				$params = array(":usubaja" => getWindowsLoginName(true), ":id" => $_POST["pregunta".$pregunta."Opcion".$opcion."Id"]);
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
												":usualta" => getWindowsLoginName(true));
				$sql =
					"INSERT INTO rrhh.rop_opcionespreguntas (op_id, op_fechaalta, op_idpregunta, op_idpreguntasiguiente, op_opcion, op_permiteobservacion, op_usualta)
																					 VALUES (:id, SYSDATE, :idpregunta, :idpreguntasiguiente, :opcion, :permiteobservacion, :usualta)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);

				$sql = "SELECT MAX(op_id) FROM rrhh.rop_opcionespreguntas";
				$opcionId = valorSql($sql, "", array(), 0);
			}
			else {		// Modificación de la opción..
				$opcionId = $_POST["pregunta".$pregunta."Opcion".$opcion."Id"];
				$updateImagen = true;
				$params = array(":id" => $opcionId,
												":idpreguntasiguiente" => $preguntaSiguiente,
												":opcion" => $_POST["pregunta".$pregunta."Opcion".$opcion],
												":permiteobservacion" => $permiteObservacion,
												":usumodif" => getWindowsLoginName(true));
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
				if ((isset($_FILES["piPregunta".$pregunta."Opcion".$opcion]["name"])) and ($_FILES["piPregunta".$pregunta."Opcion".$opcion]["name"] != ""))		// Si existe la imagen, la subo..
					if (uploadImagen($_FILES["piPregunta".$pregunta."Opcion".$opcion], IMAGES_ENCUESTAS_OPCIONES_PATH, $opcionId, $imgPath))
						updateImagenOpcion($opcionId, IMAGES_ENCUESTAS_OPCIONES_PATH, $imgPath);
					else
						exit;
			}

			$opcion++;
		}
		$pregunta++;
	}


	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
	</script>
<?
	exit;
}
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/encuestas-abm-busqueda/<?= $encuestaId?>', window.parent);
</script>