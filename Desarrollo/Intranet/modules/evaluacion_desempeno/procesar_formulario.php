<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


function validarEvaluador($grupo) {
	global $conn;

	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["cerrarEvaluacion"] == "true") {
		if ($grupo == "SPAC") {
			$itemsCargados = true;
			foreach ($_POST as $key => $value)
				if (substr($key, 0, 14) == "observaciones_") {
					$arr = explode("_", $key);

					if (!isset($_POST["item_".$arr[1]])) {
						$itemsCargados = false;
						break;
					}
				}
			if ((!$itemsCargados) and ($_POST["comentariosEvaluador"] == "")) {
				echo "errores+= '- Debe seleccionar el nivel de todas las competencias o cargar un comentario.<br />';";
				$errores = true;
			}

			if (!$errores) {
				$params = array(":anio" => $_POST["ano"], ":evaluado" => $_POST["evaluado"]);
				$sql =
					"SELECT ec_descripcion, ec_id, nr_nivel
						 FROM rrhh.rec_evaluacioncompetencia, rrhh.rnr_nivelrequerido
						WHERE ec_id = nr_idcompetencia(+)
							AND ec_grupo = 'SPAC'
							AND ec_fechabaja IS NULL
							AND nr_fechabaja IS NULL
							AND ec_anio = :anio
							AND nr_evaluado(+) = :evaluado";
				$stmt = DBExecSql($conn, $sql, $params);
				while ($row = DBGetQuery($stmt)) {
					if (($_POST["item_".$row["EC_ID"]] > $row["NR_NIVEL"]) and ($_POST["observaciones_".$row["EC_ID"]] == "")) {
						echo "errores+= '- Debe ingresar las observaciones de la competencia \"".$row["EC_DESCRIPCION"]."\".<br />';";
						$errores = true;
					}
				}
			}
		}
		else {		// Si tiene personal a cargo..
			$existeComboSinSeleccionar = false;
			foreach ($_POST as $key => $value)
				if (substr($key, 0, 6) == "combo_") {
					if ($value == -1) {
						$existeComboSinSeleccionar = true;
						echo "getElementById('".$key."').style.backgroundColor = '#f00';";
						echo "getElementById('".$key."').style.color = '#fff';";
					}
				}

			if ($existeComboSinSeleccionar) {
				echo "errores+= '- Debe seleccionar un valor para todos los items.<br />';";
				$errores = true;
			}
		}
	}


	if ($errores) {
		echo "getElementById('divDatos').style.display = 'inline-block';";
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


if (!isset($_SESSION["identidad"]))
	$_SESSION["identidad"] = getWindowsLoginName(true);

$user = $_SESSION["identidad"];

try {
	$idEvaluacion = $_POST["usuarioAEvaluar"];		// Pongo este valor en una variable de nombre mas descriptivo..

	if (!isset($_POST["comentariosEvaluado"]))
		$_POST["comentariosEvaluado"] = "";
	if (!isset($_POST["comentariosSupervisor"]))
		$_POST["comentariosSupervisor"] = "";

	$params = array(":id" => $idEvaluacion);
	$sql =
		"SELECT ue_grupo
			 FROM rrhh.rue_usuarioevaluacion
			WHERE ue_id = :id";
	$grupo = valorSql($sql, "", $params, 0);

	if ($user == $_POST["evaluador"]) {		// Si el que guarda es el evaluador..
		if (!validarEvaluador($grupo))
			exit;

		if ($grupo == "SPAC") {
			foreach ($_POST as $key => $value)
				if (substr($key, 0, 14) == "observaciones_") {
					$arr = explode("_", $key);

					$item = "";
					if (isset($_POST["item_".$arr[1]]))
						$item = $_POST["item_".$arr[1]];

					$params = array(":idcompetencia" => $arr[1],
													":idusuario" => $idEvaluacion);
					$sql =
						"SELECT re_id
							 FROM rrhh.rre_resultadoevaluacion
							WHERE re_idusuario = :idusuario
								AND re_idcompetencia = :idcompetencia";
					$id = valorSql($sql, -1, $params, 0);

					if ($id == -1) {		// Alta..
						$params = array(":idcompetencia" => $arr[1],
														":idusuario" => $idEvaluacion,
														":nivel_evaluado" => $item,
														":observacion" => substr($value, 0, 2000),
														":usualta" => getWindowsLoginName(true));
						$sql =
							"INSERT INTO rrhh.rre_resultadoevaluacion(re_fechaalta, re_idcompetencia, re_idusuario, re_nivel_evaluado, re_observacion, re_usualta)
																								VALUES (SYSDATE, :idcompetencia, :idusuario, :nivel_evaluado, :observacion, :usualta)";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
					}
					else {		// Modificación..
						$params = array(":id" => $id,
														":nivel_evaluado" => $item,
														":observacion" => substr($value, 0, 2000),
														":usumodif" => getWindowsLoginName(true));
						$sql =
							"UPDATE rrhh.rre_resultadoevaluacion
									SET re_fechamodif = SYSDATE,
											re_nivel_evaluado = :nivel_evaluado,
											re_observacion = :observacion,
											re_usumodif = :usumodif
								WHERE re_id = :id";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
					}
				}
		}
		else {		// Si tiene personal a cargo..
			foreach ($_POST as $key => $value)
				if (substr($key, 0, 6) == "combo_") {
					$arr = explode("_", $key);

					$params = array(":combo" => $arr[2],
													":idcompetencia" => $arr[1],
													":idusuario" => $idEvaluacion);
					$sql =
						"SELECT re_id
							 FROM rrhh.rre_resultadoevaluacion
							WHERE re_idusuario = :idusuario
								AND re_idcompetencia = :idcompetencia
								AND re_combo = :combo";
					$id = valorSql($sql, -1, $params, 0);

					if ($id == -1) {		// Alta..
						$params = array(":combo" => $arr[2],
														":idcompetencia" => $arr[1],
														":idrelacompetencia" => $value,
														":idusuario" => $idEvaluacion,
														":usualta" => getWindowsLoginName(true));
						$sql =
							"INSERT INTO rrhh.rre_resultadoevaluacion (re_combo, re_fechaalta, re_idcompetencia, re_idrelacompetencia, re_idusuario, re_usualta)
																								 VALUES (:combo, SYSDATE, :idcompetencia, :idrelacompetencia, :idusuario, :usualta)";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
					}
					else {		// Modificación..
						$params = array(":id" => $id,
														":idrelacompetencia" => $value,
														":usumodif" => getWindowsLoginName(true));
						$sql =
							"UPDATE rrhh.rre_resultadoevaluacion
									SET re_fechamodif = SYSDATE,
											re_idrelacompetencia = :idrelacompetencia,
											re_usumodif = :usumodif
								WHERE re_id = :id";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
					}
				}
		}

		$params = array(":evaluacion_integral" => 9,
										":evaluador_comentario" => substr($_POST["comentariosEvaluador"], 0, 2000),
										":id" => $idEvaluacion,
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rue_usuarioevaluacion
					SET ue_evaluacion_integral = :evaluacion_integral,
							ue_evaluador_comentario = :evaluador_comentario,
							ue_fechamodif = SYSDATE,
							ue_usumodif = :usumodif
				WHERE ue_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);


		if ($_POST["cerrarEvaluacion"] == "true") {
			$params = array(":id" => $idEvaluacion);
			$sql =
				"UPDATE rrhh.rue_usuarioevaluacion
						SET ue_estado = 2,
								ue_evaluador_fecha = SYSDATE
				  WHERE ue_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			if ($grupo == "SPAC") {
				$body = "<html><body>Su evaluación de desempeño ya está disponible, por favor ingrese haciendo clic <a href='http://".$_SERVER["HTTP_HOST"]."/evaluacion-desempeno'>aquí</a> para notificarse.<br /><br />Si el link no funciona pegue esta dirección en su navegador: http://".$_SERVER["HTTP_HOST"]."/evaluacion-desempeno</body></html>";
				sendEmail($body, "Aviso Intranet", "Evaluación de Desempeño disponible", getEmail(explode(";", $_POST["evaluado"])), array(), array(), "H");
			}
		}
	}

	if ($user == $_POST["evaluado"]) {		// Si el que guarda es el evaluado..
		$params = array(":evaluado_comentario" => substr($_POST["comentariosEvaluado"], 0, 2000),
										":id" => $idEvaluacion,
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rue_usuarioevaluacion
					SET ue_estado = 3,
							ue_evaluado_comentario = :evaluado_comentario,
							ue_evaluado_fecha = SYSDATE,
							ue_fechamodif = SYSDATE,
							ue_usumodif = :usumodif
				WHERE ue_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$params = array(":id" => $idEvaluacion);
		$sql = 			
			"SELECT ue_evaluador || ';' || ue_supervisor || ';' || ue_notificaciones destinatarios
				 FROM rrhh.rue_usuarioevaluacion
				WHERE ue_id = :id";
		$body = "<html><body>".getUserName($_POST["evaluado"])." ya se ha notificado de su evaluación, haga clic <a href='http://".$_SERVER["HTTP_HOST"]."/evaluacion-desempeno'>aquí</a> para consultar.<br><br>Si el link no funciona pegue esta dirección en su navegador: http://".$_SERVER["HTTP_HOST"]."/evaluacion-desempeno</body></html>";
		sendEmail($body, "Aviso Intranet", "Evaluación de desempeño notificada", getEmail(explode(";", valorSql($sql, "", $params, 0))), array(), array(), "H");
	}

	if ($user == $_POST["supervisor"]) {		// Si el que guarda es el supervisor..
		$params = array(":supervisor_comentario" => substr($_POST["comentariosSupervisor"], 0, 2000),
										":id" => $idEvaluacion,
										":usumodif" => getWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rue_usuarioevaluacion
					SET ue_estado = 4,
							ue_fechamodif = SYSDATE,
							ue_supervisor_comentario = :supervisor_comentario,
							ue_supervisor_fecha = SYSDATE,
							ue_usumodif = :usumodif
				WHERE ue_id = :id";
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
	function closeWindow() {
		divWin.close();
		window.parent.cambiarUsuarioAEvaluar('<?= $idEvaluacion?>', <?= $_POST["ano"]?>);		// Recargo los datos..
	}

	setInterval("closeWindow()", 3000);
  medioancho = (screen.width - 320) / 2;
  medioalto = (screen.height - 200) / 2;
	divWin = window.parent.dhtmlwindow.open('divBox', 'div', 'msgOk', 'Aviso', 'width=320px,height=40px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=0,scrolling=0');
</script>