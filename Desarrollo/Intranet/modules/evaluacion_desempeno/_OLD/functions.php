<?
function disableControls($isEvaluado, $isEvaluador, $isSupervisor, $isNotificado, $isFueEvaluado, $isEvaluacionAceptada, $isEvaluacionVigente, $existeEvaluacionAnterior, $otraIdentidad) {
	echo "with (window.parent.document) {";
	echo "enableAllControls();";

	if (($isFueEvaluado) or (!$isEvaluacionVigente) or (!$isEvaluador) or ($isNotificado) or ($existeEvaluacionAnterior)) {
		/* COMIENZO COMPETENCIAS */
		for ($i=0; $i<=4; $i++) {
			if ($i <= 2)
				echo "getElementById('formEvaluacion').Competencias[".$i."].disabled = true;";

			echo "getElementById('formEvaluacion').OrientacionEsp[".$i."].disabled = true;";
			echo "getElementById('formEvaluacion').AdaptabilidadEsp[".$i."].disabled = true;";
			echo "getElementById('formEvaluacion').TrabajoEnEquipoEsp[".$i."].disabled = true;";
			echo "getElementById('formEvaluacion').OrientacionAlClienteEsp[".$i."].disabled = true;";
			echo "getElementById('formEvaluacion').LiderazgoEsp[".$i."].disabled = true;";
			echo "getElementById('formEvaluacion').CapacidadPlanificacionEsp[".$i."].disabled = true;";
			echo "getElementById('formEvaluacion').PensamientoAnaliticoEsp[".$i."].disabled = true;";


			if (($isFueEvaluado) or (!$isEvaluacionVigente) or (!$isEvaluador) or ($isNotificado)) {
				echo "getElementById('formEvaluacion').Orientacion[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').Adaptabilidad[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').TrabajoEnEquipo[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').OrientacionAlCliente[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').Liderazgo[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').CapacidadPlanificacion[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').PensamientoAnalitico[".$i."].disabled = true;";

				echo "getElementById('formEvaluacion').OrientacionFuturo[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').AdaptabilidadFuturo[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').TrabajoEnEquipoFuturo[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').OrientacionAlClienteFuturo[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').LiderazgoFuturo[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').CapacidadPlanificacionFuturo[".$i."].disabled = true;";
				echo "getElementById('formEvaluacion').PensamientoAnaliticoFuturo[".$i."].disabled = true;";
			}
		}

		if (($isFueEvaluado) or (!$isEvaluacionVigente) or (!$isEvaluador) or ($isNotificado)) {
			echo "getElementById('OrientacionObservaciones').readOnly = true;";
			echo "getElementById('AdaptabilidadObservaciones').readOnly = true;";
			echo "getElementById('TrabajoEnEquipoObservaciones').readOnly = true;";
			echo "getElementById('OrientacionAlClienteObservaciones').readOnly = true;";
			echo "getElementById('LiderazgoObservaciones').readOnly = true;";
			echo "getElementById('CapacidadPlanificacionObservaciones').readOnly = true;";
			echo "getElementById('PensamientoAnaliticoObservaciones').readOnly = true;";
			/* FIN COMPETENCIAS */
		
			/* COMIENZO OBJETIVOS */
			echo "getElementById('Objetivo1Descripcion').readOnly = true;";
			echo "getElementById('Objetivo1ResultadoAObtener').readOnly = true;";
			echo "getElementById('Objetivo1Indicador').readOnly = true;";
			echo "getElementById('Objetivo1PlazoEjecucion').readOnly = true;";
			echo "getElementById('Objetivo2Descripcion').readOnly = true;";
			echo "getElementById('Objetivo2ResultadoAObtener').readOnly = true;";
			echo "getElementById('Objetivo2Indicador').readOnly = true;";
			echo "getElementById('Objetivo2PlazoEjecucion').readOnly = true;";

			echo "getElementById('Objetivo1DescripcionFuturo').readOnly = true;";
			echo "getElementById('Objetivo1ResultadoAObtenerFuturo').readOnly = true;";
			echo "getElementById('Objetivo1IndicadorFuturo').readOnly = true;";
			echo "getElementById('Objetivo1PlazoEjecucionFuturo').readOnly = true;";
			echo "getElementById('Objetivo2DescripcionFuturo').readOnly = true;";
			echo "getElementById('Objetivo2ResultadoAObtenerFuturo').readOnly = true;";
			echo "getElementById('Objetivo2IndicadorFuturo').readOnly = true;";
			echo "getElementById('Objetivo2PlazoEjecucionFuturo').readOnly = true;";
			/* FIN OBJETIVOS */

			/* COMIENZO COMPROMISOS */
			echo "for (i=1; i<=getElementById('tableCompromisosMejora').totItems; i++) {";
			echo "getElementById('CompromisoMejora' + i).readOnly = true;";
			echo "}";
			echo "getElementById('btnAgregarActividad').style.display = 'none';";
			echo "getElementById('CompromisoMejoraNuevoItem').style.display = 'none';";
			/* FIN COMPROMISOS */

			/* COMIENZO SEGUIMIENTO */
//		echo "getElementById('btnInsertarEvento').style.display = 'none';";
			/* FIN SEGUIMIENTO */
		}
	}

	if ($isEvaluado) {
		echo "getElementById('porcentajeCumplimiento1').readOnly = true;";
		echo "for (i=0; i<=3; i++)";
		echo "formEvaluacion.Objetivo1Estado[i].disabled = true;";
		echo "getElementById('btnGuardarObjetivo1').style.display = 'none';";
		echo "getElementById('btnModificarObjetivo1').style.display = 'none';";

		echo "getElementById('porcentajeCumplimiento2').readOnly = true;";
		echo "for (i=0; i<=3; i++)";
		echo "formEvaluacion.Objetivo2Estado[i].disabled = true;";
		echo "getElementById('btnGuardarObjetivo2').style.display = 'none';";
		echo "getElementById('btnModificarObjetivo2').style.display = 'none';";

		echo "getElementById('divSeguimientoTitulo').mostrar = 'no';";
		echo "getElementById('divSeguimientoTitulo').style.display = 'none';";
		echo "getElementById('divSeguimiento').style.display = 'none';";
		echo "getElementById('btnInsertarEvento').style.display = 'none';";
		echo "getElementById('btnGuardar').style.display = 'none';";
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
	}
	if ($isEvaluador) {
		echo "getElementById('porcentajeCumplimiento1').readOnly = false;";
		echo "for (i=0; i<=3; i++)";
		echo "formEvaluacion.Objetivo1Estado[i].disabled = false;";
		echo "getElementById('btnGuardarObjetivo1').style.display = 'block';";
		echo "getElementById('btnModificarObjetivo1').style.display = 'block';";

		echo "getElementById('porcentajeCumplimiento2').readOnly = false;";
		echo "for (i=0; i<=3; i++)";
		echo "formEvaluacion.Objetivo2Estado[i].disabled = false;";
		echo "getElementById('btnGuardarObjetivo2').style.display = 'block';";
		echo "getElementById('btnModificarObjetivo2').style.display = 'block';";

		echo "getElementById('btnMeNotifique').style.display = 'none';";
	}
	if ($isSupervisor) {
		echo "getElementById('porcentajeCumplimiento1').readOnly = true;";
		echo "for (i=0; i<=3; i++)";
		echo "formEvaluacion.Objetivo1Estado[i].disabled = true;";
		echo "getElementById('btnGuardarObjetivo1').style.display = 'none';";
		echo "getElementById('btnModificarObjetivo1').style.display = 'none';";

		echo "getElementById('porcentajeCumplimiento2').readOnly = true;";
		echo "for (i=0; i<=3; i++)";
		echo "formEvaluacion.Objetivo2Estado[i].disabled = true;";
		echo "getElementById('btnGuardarObjetivo2').style.display = 'none';";
		echo "getElementById('btnModificarObjetivo2').style.display = 'none';";

		echo "getElementById('btnInsertarEvento').style.display = 'none';";
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
		echo "getElementById('btnMeNotifique').style.display = 'none';";
	}


	if (($isEvaluador) or ($isSupervisor) or (($isEvaluado) and (($isEvaluacionAceptada) or (!$isEvaluacionVigente)))) {
		echo "getElementById('ComentariosEvaluado').readOnly = true;";
		echo "getElementById('btnMeNotifique').style.display = 'none';";
	}

	if (($isEvaluado) or ($isSupervisor) or (($isEvaluador) and (($isFueEvaluado) or (!$isEvaluacionVigente)))) {
		echo "getElementById('ComentariosEvaluador').readOnly = true;";
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
		if (!$isSupervisor)
			echo "getElementById('btnGuardar').style.display = 'none';";
	}

	if (($isEvaluado) or ($isEvaluador) or (($isSupervisor) and (!$isEvaluacionVigente))) {
		echo "getElementById('ComentariosSupervisor').readOnly = true;";
		if (!$isEvaluador)
			echo "getElementById('btnGuardar').style.display = 'none';";
	}
	
	if ($isNotificado) {
		echo "getElementById('btnGuardar').style.display = 'none';";
		echo "getElementById('btnMeNotifique').style.display = 'none';";
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
		echo "getElementById('ComentariosEvaluado').readOnly = true;";
		echo "getElementById('ComentariosEvaluador').readOnly = true;";
		echo "getElementById('ComentariosSupervisor').readOnly = true;";
	}

	if ($otraIdentidad) {
		echo "getElementById('btnAgregarActividad').style.display = 'none';";
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
		echo "getElementById('btnGuardar').style.display = 'none';";
		echo "getElementById('btnGuardarObjetivo1').style.display = 'none';";
		echo "getElementById('btnGuardarObjetivo2').style.display = 'none';";
		echo "getElementById('btnInsertarEvento').style.display = 'none';";
		echo "getElementById('btnMeNotifique').style.display = 'none';";
		echo "getElementById('btnModificarObjetivo1').style.display = 'none';";
		echo "getElementById('btnModificarObjetivo2').style.display = 'none';";
	}

	echo "}";
}

function getRadioIndex($item) {
	switch ($item) {
		case "0":
		case "A":
			return 0;
			break;
		case "1":
		case "B":
			return 1;
			break;
		case "2":
		case "C":
			return 2;
			break;
		case "D":
			return 3;
			break;
		case "E":
			return 4;
			break;
		default:
			return 0;
	}
}

function hideAll() {
?>
	window.parent.document.getElementById('divDatos').style.display = 'none';
<?
}

function loadCompromisosMejora($stmt) {
	echo "removerCompromisosMejora(window.parent.document);";
	$iLoop = 1;
	while ($row = DBGetQuery($stmt)) {
		if ($iLoop > 3)
			echo "agregarCompromisoMejora(window.parent.document, -1, '', false);";
		echo "window.parent.document.getElementById('CompromisoMejora".$iLoop."').value = unescape('".rawurlencode($row["CM_MEJORA"])."');";
		echo "window.parent.document.getElementById('CompromisoMejoraId".$iLoop."').value = '".$row["CM_ID"]."';";
		$iLoop++;
	}
}

function LoadDatosCompetencias($row) {
	echo "with (window.parent.document) {";
	echo "getElementById('divPeriodo').innerHTML = 'Período desde: ".$row["FECHADESDE"]." hasta: ".$row["FECHAHASTA"]."';";
	setRadioIndex("OrientacionEsp", $row["FE_ORIENTACIONESP"]);
	setRadioIndex("Orientacion", $row["FE_ORIENTACION"]);
	echo "getElementById('OrientacionObservaciones').value = unescape('".rawurlencode($row["FE_ORIENTACION_EJ"])."');";
	setRadioIndex("AdaptabilidadEsp", $row["FE_ADAPTABILIDADESP"]);
	setRadioIndex("Adaptabilidad", $row["FE_ADAPTIBILIDAD"]);
	echo "getElementById('AdaptabilidadObservaciones').value = unescape('".rawurlencode($row["FE_ADAPTIBILIDAD_EJ"])."');";
	setRadioIndex("TrabajoEnEquipoEsp", $row["FE_EQUIPOESP"]);
	setRadioIndex("TrabajoEnEquipo", $row["FE_EQUIPO"]);
	echo "getElementById('TrabajoEnEquipoObservaciones').value = unescape('".rawurlencode($row["FE_EQUIPO_EJ"])."');";
	setRadioIndex("OrientacionAlClienteEsp", $row["FE_CLIENTEESP"]);
	setRadioIndex("OrientacionAlCliente", $row["FE_CLIENTE"]);
	echo "getElementById('OrientacionAlClienteObservaciones').value = unescape('".rawurlencode($row["FE_CLIENTE_EJ"])."');";
	setRadioIndex("LiderazgoEsp", $row["FE_LIDERAZGOESP"]);
	setRadioIndex("Liderazgo", $row["FE_LIDERAZGO"]);
	echo "getElementById('LiderazgoObservaciones').value = unescape('".rawurlencode($row["FE_LIDERAZGO_EJ"])."');";
	setRadioIndex("CapacidadPlanificacionEsp", $row["FE_PLANIFICACIONESP"]);
	setRadioIndex("CapacidadPlanificacion", $row["FE_PLANIFICACION"]);
	echo "getElementById('CapacidadPlanificacionObservaciones').value = unescape('".rawurlencode($row["FE_PLANIFICACION_EJ"])."');";
	setRadioIndex("PensamientoAnaliticoEsp", $row["FE_ANALITICOESP"]);
	setRadioIndex("PensamientoAnalitico", $row["FE_ANALITICO"]);
	echo "getElementById('PensamientoAnaliticoObservaciones').value = unescape('".rawurlencode($row["FE_ANALITICO_EJ"])."');";
	setRadioIndex("Competencias", $row["FE_INT_COMPETENCIA"]);

	echo "getElementById('promedioEvaluacionIntegradora').value = '".$row["FE_PROMEVALUACIONINTEGRADORA"]."';";

	setRadioIndex("OrientacionFuturo", $row["FE_ORIENTACIONFUTURO"]);
	setRadioIndex("AdaptabilidadFuturo", $row["FE_ADAPTABILIDADFUTURO"]);
	setRadioIndex("TrabajoEnEquipoFuturo", $row["FE_EQUIPOFUTURO"]);
	setRadioIndex("OrientacionAlClienteFuturo", $row["FE_CLIENTEFUTURO"]);
	setRadioIndex("LiderazgoFuturo", $row["FE_LIDERAZGOFUTURO"]);
	setRadioIndex("CapacidadPlanificacionFuturo", $row["FE_PLANIFICACIONFUTURO"]);
	setRadioIndex("PensamientoAnaliticoFuturo", $row["FE_ANALITICOFUTURO"]);

	echo "getElementById('ComentariosEvaluado').value = unescape('".rawurlencode($row["FE_COMENTARIOEVALUADO"])."');";
	echo "getElementById('ComentariosEvaluador').value = unescape('".rawurlencode($row["FE_COMENTARIOEVALUADOR"])."');";
	echo "getElementById('ComentariosSupervisor').value = unescape('".rawurlencode($row["FE_COMENTARIOSUPERVISOR"])."');";
	echo "}";
}

function loadDatosEvaluado($row) {
?>
	with (window.parent.document) {
		getElementById('NombreEvaluado').innerHTML = '<?= $row["SE_NOMBRE"]?>';
		getElementById('PuestoEvaluado').innerHTML = '<?= $row["PUESTO"]?>';
		getElementById('SectorEvaluado').innerHTML = '<?= $row["SECTOR"]?>';
		getElementById('GerenciaEvaluado').innerHTML = '<?= $row["GERENCIA"]?>';
	}
<?
}

function loadDatosEvaluador($row) {
?>
	with (window.parent.document) {
		getElementById('NombreEvaluador').innerHTML = '<?= $row["SE_NOMBRE"]?>';
		getElementById('PuestoEvaluador').innerHTML = '<?= $row["PUESTO"]?>';
		getElementById('SectorEvaluador').innerHTML = '<?= $row["SECTOR"]?>';
		getElementById('GerenciaEvaluador').innerHTML = '<?= $row["GERENCIA"]?>';
	}
<?
}

function loadEventos($tipoEvento, $stmt) {
	echo "removerEventos(window.parent.document, '".$tipoEvento."');";
	while ($row = DBGetQuery($stmt))
		echo "agregarEvento(window.parent.document, '".$tipoEvento."', '".$row["FS_FECHA"]."', unescape('".rawurlencode($row["FS_EVENTO"])."'));";
}

function loadObjetivo($numero, $row) {
	if ($row["TOTAL"] > 1)
		$readonly = "true";
	else
		$readonly = "false";

	echo "with (window.parent.document) {";
	echo "getElementById('Objetivo".$numero."Id').value = '".$row["FO_ID"]."';";
	echo "getElementById('Objetivo".$numero."Descripcion').value = unescape('".rawurlencode($row["FO_OBJETIVO"])."');";
	echo "getElementById('Objetivo".$numero."ResultadoAObtener').value = unescape('".rawurlencode($row["FO_RESULTADO"])."');";
	echo "getElementById('Objetivo".$numero."Indicador').value = unescape('".rawurlencode($row["FO_INDICADOR"])."');";
	echo "getElementById('Objetivo".$numero."PlazoEjecucion').value = unescape('".rawurlencode($row["FO_PLAZO"])."');";
	echo "getElementById('porcentajeCumplimiento".$numero."').value = ".(($row["FO_PORCENTAJECUMPLIMIENTO"]=="")?"''":$row["FO_PORCENTAJECUMPLIMIENTO"]).";";
	setRadioIndex("Objetivo".$numero."Estado", $row["FO_ESTADO"]);
	echo "getElementById('Objetivo".$numero."DescripcionFuturo').value = unescape('".rawurlencode($row["FO_OBJETIVOFUTURO"])."');";
	echo "getElementById('Objetivo".$numero."ResultadoAObtenerFuturo').value = unescape('".rawurlencode($row["FO_RESULTADOFUTURO"])."');";
	echo "getElementById('Objetivo".$numero."IndicadorFuturo').value = unescape('".rawurlencode($row["FO_INDICADORFUTURO"])."');";
	echo "getElementById('Objetivo".$numero."PlazoEjecucionFuturo').value = unescape('".rawurlencode($row["FO_PLAZOFUTURO"])."');";

	echo "getElementById('Objetivo".$numero."Descripcion').readOnly = ".$readonly.";";
	echo "getElementById('Objetivo".$numero."ResultadoAObtener').readOnly = ".$readonly.";";
	echo "getElementById('Objetivo".$numero."Indicador').readOnly = ".$readonly.";";
	echo "getElementById('Objetivo".$numero."PlazoEjecucion').readOnly = ".$readonly.";";

	echo "getElementById('Objetivo".$numero."DescripcionFuturo').readOnly = ".$readonly.";";
	echo "getElementById('Objetivo".$numero."ResultadoAObtenerFuturo').readOnly = ".$readonly.";";
	echo "getElementById('Objetivo".$numero."IndicadorFuturo').readOnly = ".$readonly.";";
	echo "getElementById('Objetivo".$numero."PlazoEjecucionFuturo').readOnly = ".$readonly.";";
	echo "}";
}

function setYear($ano) {
?>
	with (window.parent.document) {
		getElementById('labelAno1').innerHTML = '<?= $ano?>';
		getElementById('labelAno2').innerHTML = '<?= $ano?>';
		getElementById('labelAno3').innerHTML = '<?= $ano?>';
		getElementById('labelAno4').innerHTML = '<?= $ano?>';
		getElementById('labelAnoSiguiente1').innerHTML = '<?= ($ano + 1)?>';
		getElementById('labelAnoSiguiente2').innerHTML = '<?= ($ano + 1)?>';
		getElementById('labelAnoSiguiente3').innerHTML = '<?= ($ano + 1)?>';
	}
<?
}

function showCompetenciasConduccion($value) {
	if ($value) {
?>
		with (window.parent.document) {
			getElementById('divCompetenciasConduccion').style.display = 'block';
			getElementById('divCompetenciasConduccionFuturo').style.display = 'block';
			getElementById('ValidarCompetenciasConduccion').value = 'true';
		}
<?
	}
	else {
?>
		with (window.parent.document) {
			getElementById('divCompetenciasConduccion').style.display = 'none';
			getElementById('divCompetenciasConduccionFuturo').style.display = 'none';
			getElementById('ValidarCompetenciasConduccion').value = 'false';
		}
<?
	}
}

function showDatosNoCargados($value) {
	if ($value) {
?>
		with (window.parent.document) {
			getElementById('divDatosNoCargados').style.display = 'block';
			getElementById('divDatos').style.display = 'none';
		}
<?
	}
	else {
?>
		with (window.parent.document) {
			getElementById('divDatosNoCargados').style.display = 'none';
			getElementById('divDatos').style.display = 'block';
		}
<?
	}
}

function setRadioIndex($radio, $value) {
	if ($value != "")
		echo "getElementById('formEvaluacion').".$radio."[".getRadioIndex($value)."].checked = true;";
}
?>