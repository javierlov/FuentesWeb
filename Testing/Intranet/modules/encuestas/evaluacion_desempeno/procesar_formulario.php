<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
session_start();


function getCheckValue($value) {
	$result = NULL;
	if (isset($_POST[$value]))
		$result = addQuotes($_POST[$value]);
	return $result;
}


$user = $_SESSION["identidad"];

try {
	if ($user == $_POST["Evaluado"]) {		// Si el que guarda es el evaluado..
		$sql =
			"UPDATE rrhh.hfe_formularioevaluacion2008
					SET fe_fechaevaluado = SYSDATE,
							fe_comentarioevaluado = SUBSTR(:comentarioevaluado, 1, 2000),
							fe_usumodif = UPPER(:usumodif),
							fe_fechamodif = SYSDATE
			  WHERE fe_id = :id";
		$params = array(":comentarioevaluado" => $_POST["ComentariosEvaluado"], ":usumodif" => $user, ":id" => $_POST["FormularioId"]);
		DBExecSql($conn, $sql, $params);

		if ($_POST["CerrarEvaluacion"] == "true") {
			$sql =
				"UPDATE rrhh.hue_usuarioevaluacion
						SET ue_evaluado_ok = 1
					WHERE ue_evaluado = UPPER(:evaluado)
						AND ue_anoevaluacion = :ano";
			$params = array(":evaluado" => $_POST["Evaluado"], ":ano" => $_POST["Ano"]);
			DBExecSql($conn, $sql, $params);

			$sql = 			
				"SELECT ue_evaluador || ';' || ue_supervisor || ';' || ue_notificacion destinatarios
					FROM rrhh.hue_usuarioevaluacion
				 WHERE ue_evaluado = :evaluado
					  AND ue_anoevaluacion = :ano";
			$params = array(":evaluado" => $_POST["Evaluado"], ":ano" => $_POST["Ano"]);
			$body = "<html><body>".GetUserName($_POST["Evaluado"])." ya se ha notificado de su evaluación, <a href='http://".$_SERVER["HTTP_HOST"]."/modules/encuestas/evaluacion_desempeno/'>haga click aquí</a> para consultar.<br><br>Si el link no funciona pegue esta dirección en su navegador: http://".$_SERVER["HTTP_HOST"]."/modules/encuestas/evaluacion_desempeno</body></html>";
			SendEmail($body, "Aviso Intranet", "Evaluación notificada", GetEmail(explode(";", ValorSql($sql, "", $params))), array(), array(), "H");
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
		$params = array(":comentarioevaluador" => $_POST["ComentariosEvaluador"],
									":orientacion" => $orientacion,
									":orientacionesp" => $orientacionEsp,
									":orientacionej" => $_POST["OrientacionObservaciones"],
									":orientacionfuturo" => $orientacionFuturo,
									":adaptabilidad" => $adaptabilidad,
									":adaptabilidadesp" => $adaptabilidadEsp,
									":adaptabilidadej" => $_POST["AdaptabilidadObservaciones"],
									":adaptabilidadfuturo" => $adaptabilidadFuturo,
									":equipo" => $equipo,
									":equipoesp" => $equipoEsp,
									":equipoej" => $_POST["TrabajoEnEquipoObservaciones"],
									":equipofuturo" => $equipoFuturo,
									":cliente" => $cliente,
									":clienteesp" => $clienteEsp,
									":clienteej" => $_POST["OrientacionAlClienteObservaciones"],
									":clientefuturo" => $clienteFuturo,
									":liderazgo" => $liderazgo,
									":liderazgoesp" => $liderazgoEsp,
									":liderazgoej" => $_POST["LiderazgoObservaciones"],
									":liderazgofuturo" => $liderazgoFuturo,
									":planificacion" => $planificacion,
									":planificacionesp" => $planificacionEsp,
									":planificacionej" => $_POST["CapacidadPlanificacionObservaciones"],
									":planificacionfuturo" => $planificacionFuturo,
									":analitico" => $analitico,
									":analiticoesp" => $analiticoEsp,
									":analiticoej" => $_POST["PensamientoAnaliticoObservaciones"],
									":analiticofuturo" => $analiticoFuturo,
									":competencia" => $competencia,
									":promevaluacionintegradora" => nullIfCero($_POST["promedioEvaluacionIntegradora"]),
									":usumodif" => $user,
									":id" => $_POST["FormularioId"]);
		DBExecSql($conn, $sql, $params);

		// Guardo los objetivos..
		for ($i=1;$i<=2;$i++) {
			if ($_POST["Objetivo".$i."Id"] == "")
				$sql =
					"INSERT INTO rrhh.hfo_formularioobjetivo
											(fo_id_formularioevaluacion, fo_nroobjetivo, fo_objetivo, fo_resultado, fo_indicador, fo_plazo, fo_objetivofuturo,
											fo_resultadofuturo, fo_indicadorfuturo, fo_plazofuturo, fo_usualta, fo_fechaalta)
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
							SET fo_objetivo = SUBSTR(:objetivo, 1, 2000),
									fo_resultado = SUBSTR(:resultado, 1, 2000),
									fo_indicador = SUBSTR(:indicador, 1, 2000),
									fo_plazo = SUBSTR(:plazo, 1, 2000),
									fo_objetivofuturo = SUBSTR(:objetivofuturo, 1, 2000),
									fo_resultadofuturo = SUBSTR(:resultadofuturo, 1, 2000),
									fo_indicadorfuturo = SUBSTR(:indicadorfuturo, 1, 2000),
									fo_plazofuturo = SUBSTR(:plazofuturo, 1, 2000),
									fo_usumodif = UPPER(:usumodif),
									fo_fechamodif = SYSDATE
					  WHERE fo_id = :id";
			$params = array(":objetivo" => $_POST["Objetivo".$i."Descripcion"],
										":resultado" => $_POST["Objetivo".$i."ResultadoAObtener"],
										":indicador" => $_POST["Objetivo".$i."Indicador"],
										":plazo" => $_POST["Objetivo".$i."PlazoEjecucion"],
										":objetivofuturo" => $_POST["Objetivo".$i."DescripcionFuturo"],
										":resultadofuturo" => $_POST["Objetivo".$i."ResultadoAObtenerFuturo"],
										":indicadorfuturo" => $_POST["Objetivo".$i."IndicadorFuturo"],
										":plazofuturo" => $_POST["Objetivo".$i."PlazoEjecucionFuturo"],
										":usumodif" => $user,
										":id" => $_POST["Objetivo".$i."Id"]);
			DBExecSql($conn, $sql, $params);
		}
		// Guardo el porcentaje de cumplimiento y el estado de los objetivos..
		$sql =
			"UPDATE rrhh.hfo_formularioobjetivo
					SET fo_porcentajecumplimiento = :porcentajecumplimiento,
							fo_estado = :estado,
							fo_usumodif = UPPER(:usumodif),
							fo_fechamodif = SYSDATE
			  WHERE fo_id = (SELECT MAX(fo_id)
											FROM rrhh.hfo_formularioobjetivo
										 WHERE fo_id_formularioevaluacion = :idformularioevaluacion
											  AND fo_nroobjetivo = 1)";
		$params = array(":porcentajecumplimiento" => nullIfCero($_POST["porcentajeCumplimiento1"]),
									":estado" => $_POST["estadoObjetivo1Tmp"],
									":usumodif" => $user,
									":idformularioevaluacion" => $_POST["FormularioId"]);
		DBExecSql($conn, $sql, $params);
		$sql =
			"UPDATE rrhh.hfo_formularioobjetivo
					SET fo_porcentajecumplimiento = :porcentajecumplimiento,
							fo_estado = :estado,
							fo_usumodif = UPPER(:usumodif),
							fo_fechamodif = SYSDATE
			  WHERE fo_id = (SELECT MAX(fo_id)
											FROM rrhh.hfo_formularioobjetivo
										 WHERE fo_id_formularioevaluacion = :idformularioevaluacion
											  AND fo_nroobjetivo = 2)";
		$params = array(":porcentajecumplimiento" => nullIfCero($_POST["porcentajeCumplimiento2"]),
									":estado" => $_POST["estadoObjetivo2Tmp"],
									":usumodif" => $user,
									":idformularioevaluacion" => $_POST["FormularioId"]);
		DBExecSql($conn, $sql, $params);


		// Guardo los compromisos de mejora..
		$iLoop = 1;
		while (isset($_POST["CompromisoMejoraId".$iLoop])) {
			if ($_POST["CompromisoMejoraId".$iLoop] > 0) {
				$sql =
					"UPDATE rrhh.hcm_compromisomejora
							SET cm_mejora = SUBSTR(:mejora, 1, 2000),
									cm_usumodif = UPPER(:usumodif),
									cm_fechamodif = SYSDATE
					  WHERE cm_id = :id";
				$params = array(":mejora" => $_POST["CompromisoMejora".$iLoop],
											":usumodif" => $user,
											":id" => $_POST["CompromisoMejoraId".$iLoop]);
				DBExecSql($conn, $sql, $params);
			}
			else {
				$sql =
					"INSERT INTO rrhh.hcm_compromisomejora (cm_id_formularioevaluacion, cm_mejora, cm_usualta, cm_fechaalta)
																			  VALUES (:idformularioevaluacion, SUBSTR(:mejora, 1, 2000), UPPER(:usualta), SYSDATE)";
				$params = array(":idformularioevaluacion" => $_POST["FormularioId"],
											":mejora" => $_POST["CompromisoMejora".$iLoop],
											":usualta" => $user);
				DBExecSql($conn, $sql, $params);
     		}
			$iLoop++;
		}

		if ($_POST["CerrarEvaluacion"] == "true") {
			$sql =
				"UPDATE rrhh.hue_usuarioevaluacion
						SET ue_evaluador_ok = 1
				  WHERE ue_evaluado = UPPER(:evaluado)
						AND ue_anoevaluacion = :ano";
			$params = array(":evaluado" => $_POST["Evaluado"], ":ano" => $_POST["Ano"]);
			DBExecSql($conn, $sql, $params);

			$sql = 			
				"SELECT ue_evaluador destinatarios
  				 FROM rrhh.hue_usuarioevaluacion
 					WHERE ue_evaluado = ".addQuotes($_POST["Evaluado"])."
 						AND ue_anoevaluacion = ".$_POST["Ano"];
			$body = "<html><body>Su evaluación de desempeño ya está disponible, por favor <a href='http://".$_SERVER["HTTP_HOST"]."/modules/encuestas/evaluacion_desempeno/'>ingrese haciendo click aquí</a> para notificarse.<br><br>Si el link no funciona pegue esta dirección en su navegador: http://".$_SERVER["HTTP_HOST"]."/modules/encuestas/evaluacion_desempeno</body></html>";
			SendEmail($body, "Aviso Intranet", "Evaluación efectuada", GetEmail(explode(";", $_POST["Evaluado"])), array(), array(), "H");
		}
	}

	if ($user == $_POST["Supervisor"]) {		// Si el que guarda es el supervisor..
		$sql =
			"UPDATE rrhh.hfe_formularioevaluacion2008
					SET fe_fechasupervisor = SYSDATE,
							fe_comentariosupervisor = SUBSTR(:comentariossupervisor, 1, 2000),
							fe_usumodif = UPPER(:usumodif),
							fe_fechamodif = SYSDATE
			  WHERE fe_id = :id";
		$params = array(":comentariossupervisor" => $_POST["ComentariosSupervisor"],
									":usumodif" => $user,
									":id" => $_POST["FormularioId"]);
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