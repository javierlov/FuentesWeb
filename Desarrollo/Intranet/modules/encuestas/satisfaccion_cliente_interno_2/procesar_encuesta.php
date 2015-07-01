<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


function validar() {
	global $periodo;
	global $usuario;

	$params = array(":anio" => $periodo, ":id" => $_POST["idEncuesta"], ":usuario" => $usuario);
	$sql =
		"SELECT 1
			 FROM web.weu_encuesta_usuario
			WHERE eu_anio = :anio
				AND eu_fechabaja IS NULL
				AND eu_usuario = :usuario
				AND eu_id = :id";
	$usuarioHabilitado = ExisteSql($sql, $params);

	if (!$usuarioHabilitado) {
		echo "<script type='text/javascript'>alert('ACCESO DENEGADO. Usted no tiene encuestas para completar.');</script>";
		return false;
	}

	$calificacionOk = true;
	foreach ($_POST as $key => $value)
		if (substr($key, 0, 13) == "calificacion_")
			if ((!validarEntero($value)) or ($value < 1) or ($value > 10)) {
				$calificacionOk = false;
				break;
			}

	if (!$calificacionOk) {
		echo "<script type='text/javascript'>alert('Recuerde que debe completar todas las calificaciones de manera correcta.'); parent.document.getElementById('".$key."').focus();</script>";
		return false;
	}

	$comentarioCargado = true;
	foreach ($_POST as $key => $value)
		if (substr($key, 0, 13) == "calificacion_")
			if (($value < 7) and (!caracteresValidos($_POST["comentarios_".substr($key, 13)], 5))) {
				$comentarioCargado = false;
				break;
			}

	if (!$comentarioCargado) {
		echo "<script type='text/javascript'>alert('Por favor, ingrese un comentario válido.'); parent.document.getElementById('comentarios_".substr($key, 13)."').focus();</script>";
		return false;
	}


	return true;
}


try {
	$usuario = GetWindowsLoginName(true);

	$sql = "SELECT TO_CHAR(SYSDATE, 'YYYYMM') FROM DUAL";
	$sql = "SELECT tb_codigo FROM art.ctb_tablas WHERE tb_clave = 'C_INT'";
	$periodo = ValorSql($sql);

	if (!validar())
		exit;


	// Loopeo por los campos cargados..
	foreach ($_POST as $key => $value)
		if (substr($key, 0, 13) == "calificacion_") {
			$params = array(":id_encuesta_maestro" => substr($key, 13), ":id_encuesta_usuario" => $_POST["idEncuesta"]);
			$sql =
				"SELECT er_id
					 FROM web.wer_encuesta_resultado
					WHERE em_id_encuesta_maestro = :id_encuesta_maestro
						AND em_id_encuesta_usuario = :id_encuesta_usuario";
			$id = ValorSql($sql, -1, $params, OCI_DEFAULT);

			if ($id == -1) {		// Alta..
				$params = array(":calificacion" => $value,
												":id_encuesta_maestro" => substr($key, 13),
												":id_encuesta_usuario" => $_POST["idEncuesta"],
												":observaciones" => $_POST["comentarios_".substr($key, 13)],
												":usualta" => $usuario);
				$sql =
					"INSERT INTO web.wer_encuesta_resultado
											 (em_calificacion, em_fechaalta, em_id_encuesta_maestro, em_id_encuesta_usuario, em_observaciones, em_usualta)
								VALUES (:calificacion, SYSDATE, :id_encuesta_maestro, :id_encuesta_usuario, :observaciones, :usualta)";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
			else {		// Modificación..
				$params = array(":calificacion" => $value,
												":id" => $id,
												":observaciones" => $_POST["comentarios_".substr($key, 13)],
												":usumodif" => $usuario);
				$sql =
					"UPDATE web.wer_encuesta_resultado
							SET em_calificacion = :calificacion,
									em_fechamodif = SYSDATE,
									em_observaciones = :observaciones,
									em_usumodif = :usumodif
						WHERE er_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
		}

	// Doy por finalizada la encuesta..
	$params = array(":id" => $_POST["idEncuesta"]);
	$sql =
		"UPDATE web.weu_encuesta_usuario
				SET eu_estado = 'T'
			WHERE eu_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);


	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.location.href = '/encuesta-satisfaccion-cliente';
	}

	with (window.parent.document) {
		getElementById('divPaso2').style.display = 'none';
		getElementById('divDatosOk').style.display = 'block';
	}

	setTimeout('redirect()', 1500);
</script>