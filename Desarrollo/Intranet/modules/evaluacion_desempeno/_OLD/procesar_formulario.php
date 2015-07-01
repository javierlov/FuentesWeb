<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


function getCheckValue($value) {
	$result = NULL;
	if (isset($_POST[$value]))
		$result = addQuotes($_POST[$value]);
	return $result;
}


$user = $_SESSION["identidad"];

try {
	if ($user == $_POST["Evaluado"]) {		// Si el que guarda es el evaluado..
		$params = array(":comentarioevaluado" => $_POST["ComentariosEvaluado"],
										":id" => $_POST["FormularioId"],
										":usumodif" => $user);
		$sql =
			"UPDATE rrhh.hfe_formularioevaluacion2008
					SET fe_fechaevaluado = SYSDATE,
							fe_comentarioevaluado = SUBSTR(:comentarioevaluado, 1, 2000),
							fe_usumodif = UPPER(:usumodif),
							fe_fechamodif = SYSDATE
			  WHERE fe_id = :id";
		DBExecSql($conn, $sql, $params);

		if ($_POST["CerrarEvaluacion"] == "true") {
			$params = array(":ano" => $_POST["Ano"], ":evaluado" => $_POST["Evaluado"]);
			$sql =
				"UPDATE rrhh.hue_usuarioevaluacion
						SET ue_evaluado_ok = 1
					WHERE ue_evaluado = UPPER(:evaluado)
						AND ue_anoevaluacion = :ano";
			DBExecSql($conn, $sql, $params);

			$params = array(":ano" => $_POST["Ano"], ":evaluado" => $_POST["Evaluado"]);
			$sql = 			
				"SELECT ue_evaluador || ';' || ue_supervisor || ';' || ue_notificacion destinatarios
					 FROM rrhh.hue_usuarioevaluacion
					WHERE ue_evaluado = :evaluado
						AND ue_anoevaluacion = :ano";
			$body = "<html><body>".getUserName($_POST["Evaluado"])." ya se ha notificado de su evaluación, <a href='http://".$_SERVER["HTTP_HOST"]."/modules/evaluacion_desempeno/'>haga clic aquí</a> para consultar.<br><br>Si el link no funciona pegue esta dirección en su navegador: http://".$_SERVER["HTTP_HOST"]."/modules/evaluacion_desempeno</body></html>";
			sendEmail($body, "Aviso Intranet", "Evaluación notificada", getEmail(explode(";", valorSql($sql, "", $params))), array(), array(), "H");
		}
	}

	if ($user == $_POST["Evaluador"]) {		// Si el que guarda es el evaluador..
		// Tomo el valor de campos que pueden existir o no..
		$orientacion = getCheckValue("Orientacion");
		$orientacionEsp = getCheckValue("OrientacionEsp");
		$orientacionFuturo = getCheckValue("OrientacionFuturo");

		$adaptabilidad = getCheckValue("Adaptabilidad");
		$adaptabilidadEsp = getCheckValue("AdaptabilidadEsp");
		$adaptabilidadFuturo = getCheckValue("AdaptabilidadFuturo");

		$equipo = getCheckValue("TrabajoEnEquipo");
		$equipoEsp = getCheckValue("TrabajoEnEquipoEsp");
		$equipoFuturo = getCheckValue("TrabajoEnEquipoFuturo");

		$cliente = getCheckValue("OrientacionAlCliente");
		$clienteEsp = getCheckValue("OrientacionAlClienteEsp");
		$clienteFuturo = getCheckValue("OrientacionAlClienteFuturo");

		$liderazgo = getCheckValue("Liderazgo");
		$liderazgoEsp = getCheckValue("LiderazgoEsp");
		$liderazgoFuturo = getCheckValue("LiderazgoFuturo");

		$planificacion = getCheckValue("CapacidadPlanificacion");
		$planificacionEsp = getCheckValue("CapacidadPlanificacionEsp");
		$planificacionFuturo = getCheckValue("CapacidadPlanificacionFuturo");

		$analitico = getCheckValue("PensamientoAnalitico");
		$analiticoEsp = getCheckValue("PensamientoAnaliticoEsp");
		$analiticoFuturo = getCheckValue("PensamientoAnaliticoFuturo");

		$competencia = NULL;
		if (isset($_POST["Competencias"]))
			$competencia = $_POST["Competencias"] - 1;

		// Guardo las competencias..
		$params = array(":adaptabilidad" => $adaptabilidad,
										":adaptabilidadej" => $_POST["AdaptabilidadObservaciones"],
										":adaptabilidadesp" => $adaptabilidadEsp,
										":adaptabilidadfuturo" => $adaptabilidadFuturo,
										":analitico" => $analitico,
										":analiticoej" => $_POST["PensamientoAnaliticoObservaciones"],
										":analiticoesp" => $analiticoEsp,
										":analiticofuturo" => $analiticoFuturo,
										":cliente" => $cliente,
										":clienteej" => $_POST["OrientacionAlClienteObservaciones"],
										":clienteesp" => $clienteEsp,
										":clientefuturo" => $clienteFuturo,
										":comentarioevaluador" => $_POST["ComentariosEvaluador"],
										":competencia" => $competencia,
										":equipo" => $equipo,
										":equipoej" => $_POST["TrabajoEnEquipoObservaciones"],
										":equipoesp" => $equipoEsp,
										":equipofuturo" => $equipoFuturo,
										":id" => $_POST["FormularioId"],
										":liderazgo" => $liderazgo,
										":liderazgoej" => $_POST["LiderazgoObservaciones"],
										":liderazgoesp" => $liderazgoEsp,
										":liderazgofuturo" => $liderazgoFuturo,
										":orientacion" => $orientacion,
										":orientacionej" => $_POST["OrientacionObservaciones"],
										":orientacionesp" => $orientacionEsp,
										":orientacionfuturo" => $orientacionFuturo,
										":planificacion" => $planificacion,
										":planificacionej" => $_POST["CapacidadPlanificacionObservaciones"],
										":planificacionesp" => $planificacionEsp,
										":planificacionfuturo" => $planificacionFuturo,
										":promevaluacionintegradora" => nullIfCero($_POST["promedioEvaluacionIntegradora"]),
										":usumodif" => $user);
		$sql =
			"UPDATE rrhh.hfe_formularioevaluacion2008
				  SET fe_fechaevaluador = SYSDATE,
						  fe_comentarioevaluador = SUBSTR(:comentarioevaluador, 1, 2000),
						  fe_orientacion = :orientacion,
						  fe_orientacionesp = :orientacionesp,
						  fe_orientacion_ej = SUBSTR(:orientacionej, 1, 2000),
						  fe_orientacionfuturo = :orientacionfuturo,
						  fe_adaptibilidad = :adaptabilidad,
						  fe_adaptabilidadesp = :adaptabilidadesp,
						  fe_adaptibilidad_ej = SUBSTR(:adaptabilidadej, 1, 2000),
						  fe_adaptabilidadfuturo = :adaptabilidadfuturo,
						  fe_equipo = :equipo,
						  fe_equipoesp = :equipoesp,
						  fe_equipo_ej = SUBSTR(:equipoej, 1, 2000),
						  fe_equipofuturo = :equipofuturo,
						  fe_cliente = :cliente,
						  fe_clienteesp = :clienteesp,
						  fe_cliente_ej = SUBSTR(:clienteej, 1, 2000),
						  fe_clientefuturo = :clientefuturo,
						  fe_liderazgo = :liderazgo,
						  fe_liderazgoesp = :liderazgoesp,
						  fe_liderazgo_ej = SUBSTR(:liderazgoej, 1, 2000),
						  fe_liderazgofuturo = :liderazgofuturo,
						  fe_planificacion = :planificacion,
						  fe_planificacionesp = :planificacionesp,
						  fe_planificacion_ej = SUBSTR(:planificacionej, 1, 2000),
						  fe_planificacionfuturo = :planificacionfuturo,
						  fe_analitico = :analitico,
						  fe_analiticoesp = :analiticoesp,
						  fe_analitico_ej = SUBSTR(:analiticoej, 1, 2000),
						  fe_analiticofuturo = :analiticofuturo,
						  fe_int_competencia = :competencia,
						  fe_promevaluacionintegradora = :promevaluacionintegradora,
						  fe_usumodif = UPPER(:usumodif),
						  fe_fechamodif = SYSDATE
			 WHERE fe_id = :id";
		DBExecSql($conn, $sql, $params);

		// Guardo los objetivos..
		for ($i=1; $i<=2; $i++) {
			if ($_POST["Objetivo".$i."Id"] == "")
				$sql =
					"INSERT INTO rrhh.hfo_formularioobjetivo (fo_id_formularioevaluacion, fo_nroobjetivo, fo_objetivo, fo_resultado, fo_indicador, fo_plazo, fo_objetivofuturo, fo_resultadofuturo,
																										fo_indicadorfuturo, fo_plazofuturo, fo_usualta, fo_fechaalta)
																						VALUES (".$_POST["FormularioId"].", ".$i.",
																										SUBSTR(".addQuotes($_POST["Objetivo".$i."Descripcion"]).", 1, 2000),
																										SUBSTR(".addQuotes($_POST["Objetivo".$i."ResultadoAObtener"]).", 1, 2000),
																										SUBSTR(".addQuotes($_POST["Objetivo".$i."Indicador"]).", 1, 2000),
																										SUBSTR(".addQuotes($_POST["Objetivo".$i."PlazoEjecucion"]).", 1, 2000),
																										SUBSTR(".addQuotes($_POST["Objetivo".$i."DescripcionFuturo"]).", 1, 2000),
																										SUBSTR(".addQuotes($_POST["Objetivo".$i."ResultadoAObtenerFuturo"]).", 1, 2000),
																										SUBSTR(".addQuotes($_POST["Objetivo".$i."IndicadorFuturo"]).", 1, 2000),
																										SUBSTR(".addQuotes($_POST["Objetivo".$i."PlazoEjecucionFuturo"]).", 1, 2000),
																										UPPER(".addQuotes($user)."), SYSDATE)";
			else
				$sql =
					"UPDATE rrhh.hfo_formularioobjetivo
							SET fo_fechamodif = SYSDATE,
									fo_indicador = SUBSTR(:indicador, 1, 2000),
									fo_indicadorfuturo = SUBSTR(:indicadorfuturo, 1, 2000),
									fo_objetivo = SUBSTR(:objetivo, 1, 2000),
									fo_objetivofuturo = SUBSTR(:objetivofuturo, 1, 2000),
									fo_plazo = SUBSTR(:plazo, 1, 2000),
									fo_plazofuturo = SUBSTR(:plazofuturo, 1, 2000),
									fo_resultado = SUBSTR(:resultado, 1, 2000),
									fo_resultadofuturo = SUBSTR(:resultadofuturo, 1, 2000),
									fo_usumodif = UPPER(:usumodif)
					  WHERE fo_id = :id";
			$params = array(":id" => $_POST["Objetivo".$i."Id"],
											":indicador" => $_POST["Objetivo".$i."Indicador"],
											":indicadorfuturo" => $_POST["Objetivo".$i."IndicadorFuturo"],
											":objetivo" => $_POST["Objetivo".$i."Descripcion"],
											":objetivofuturo" => $_POST["Objetivo".$i."DescripcionFuturo"],
											":plazo" => $_POST["Objetivo".$i."PlazoEjecucion"],
											":plazofuturo" => $_POST["Objetivo".$i."PlazoEjecucionFuturo"],
											":resultado" => $_POST["Objetivo".$i."ResultadoAObtener"],
											":resultadofuturo" => $_POST["Objetivo".$i."ResultadoAObtenerFuturo"],
											":usumodif" => $user);
			DBExecSql($conn, $sql, $params);
		}

		// Guardo el porcentaje de cumplimiento y el estado de los objetivos..
		$params = array(":estado" => $_POST["estadoObjetivo1Tmp"],
										":idformularioevaluacion" => $_POST["FormularioId"],
										":porcentajecumplimiento" => nullIfCero($_POST["porcentajeCumplimiento1"]),
										":usumodif" => $user);
		$sql =
			"UPDATE rrhh.hfo_formularioobjetivo
					SET fo_estado = :estado,
							fo_fechamodif = SYSDATE,
							fo_porcentajecumplimiento = :porcentajecumplimiento,
							fo_usumodif = UPPER(:usumodif)
			  WHERE fo_id = (SELECT MAX(fo_id)
												 FROM rrhh.hfo_formularioobjetivo
												WHERE fo_id_formularioevaluacion = :idformularioevaluacion
													AND fo_nroobjetivo = 1)";
		DBExecSql($conn, $sql, $params);

		$sql =
			"UPDATE rrhh.hfo_formularioobjetivo
					SET fo_estado = :estado,
							fo_fechamodif = SYSDATE,
							fo_porcentajecumplimiento = :porcentajecumplimiento,
							fo_usumodif = UPPER(:usumodif)
			  WHERE fo_id = (SELECT MAX(fo_id)
												 FROM rrhh.hfo_formularioobjetivo
												WHERE fo_id_formularioevaluacion = :idformularioevaluacion
													AND fo_nroobjetivo = 2)";
		$params = array(":estado" => $_POST["estadoObjetivo2Tmp"],
										":idformularioevaluacion" => $_POST["FormularioId"],
										":porcentajecumplimiento" => nullIfCero($_POST["porcentajeCumplimiento2"]),
										":usumodif" => $user);
		DBExecSql($conn, $sql, $params);


		// Guardo los compromisos de mejora..
		$iLoop = 1;
		while (isset($_POST["CompromisoMejoraId".$iLoop])) {
			if ($_POST["CompromisoMejoraId".$iLoop] > 0) {
				$params = array(":id" => $_POST["CompromisoMejoraId".$iLoop],
												":mejora" => $_POST["CompromisoMejora".$iLoop],
												":usumodif" => $user);
				$sql =
					"UPDATE rrhh.hcm_compromisomejora
							SET cm_fechamodif = SYSDATE,
									cm_mejora = SUBSTR(:mejora, 1, 2000),
									cm_usumodif = UPPER(:usumodif)
					  WHERE cm_id = :id";
				DBExecSql($conn, $sql, $params);
			}
			else {
				$params = array(":idformularioevaluacion" => $_POST["FormularioId"],
												":mejora" => $_POST["CompromisoMejora".$iLoop],
												":usualta" => $user);
				$sql =
					"INSERT INTO rrhh.hcm_compromisomejora (cm_id_formularioevaluacion, cm_mejora, cm_usualta, cm_fechaalta)
																					VALUES (:idformularioevaluacion, SUBSTR(:mejora, 1, 2000), UPPER(:usualta), SYSDATE)";
				DBExecSql($conn, $sql, $params);
     	}
			$iLoop++;
		}

		if ($_POST["CerrarEvaluacion"] == "true") {
			$params = array(":ano" => $_POST["Ano"], ":evaluado" => $_POST["Evaluado"]);
			$sql =
				"UPDATE rrhh.hue_usuarioevaluacion
						SET ue_evaluador_ok = 1
				  WHERE ue_evaluado = UPPER(:evaluado)
						AND ue_anoevaluacion = :ano";
			DBExecSql($conn, $sql, $params);

			$sql = 			
				"SELECT ue_evaluador destinatarios
  				 FROM rrhh.hue_usuarioevaluacion
 					WHERE ue_evaluado = ".addQuotes($_POST["Evaluado"])."
 						AND ue_anoevaluacion = ".$_POST["Ano"];
			$body = "<html><body>Su evaluación de desempeño ya está disponible, por favor <a href='http://".$_SERVER["HTTP_HOST"]."/modules/evaluacion_desempeno/'>ingrese haciendo clic aquí</a> para notificarse.<br><br>Si el link no funciona pegue esta dirección en su navegador: http://".$_SERVER["HTTP_HOST"]."/modules/evaluacion_desempeno</body></html>";
			sendEmail($body, "Aviso Intranet", "Evaluación efectuada", getEmail(explode(";", $_POST["Evaluado"])), array(), array(), "H");
		}
	}

	if ($user == $_POST["Supervisor"]) {		// Si el que guarda es el supervisor..
		$params = array(":comentariossupervisor" => $_POST["ComentariosSupervisor"],
										":id" => $_POST["FormularioId"],
										":usumodif" => $user);
		$sql =
			"UPDATE rrhh.hfe_formularioevaluacion2008
					SET fe_comentariosupervisor = SUBSTR(:comentariossupervisor, 1, 2000),
							fe_fechamodif = SYSDATE,
							fe_fechasupervisor = SYSDATE,
							fe_usumodif = UPPER(:usumodif)
			  WHERE fe_id = :id";
		DBExecSql($conn, $sql, $params);
	}
}
catch (Exception $e) {
	echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script>
	function closeWindow() {
		divWin.close();
		window.parent.cambiarUsuarioAEvaluar('<?= $_POST["Evaluado"]?>', <?= $_POST["Ano"]?>);		// Recargo los datos..
	}

	setInterval("closeWindow()", 3000);
  medioancho = (screen.width - 320) / 2;
  medioalto = (screen.height - 200) / 2;
	divWin = window.parent.dhtmlwindow.open('divBox', 'div', 'msgOk', 'Aviso', 'width=320px,height=40px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=0,scrolling=0');
</script>