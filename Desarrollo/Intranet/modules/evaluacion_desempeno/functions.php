<?
require_once("crypt.php");


function disableControls($isEvaluado, $isEvaluador, $isSupervisor, $isNotificado, $isFueEvaluado, $isEvaluacionAceptada, $isEvaluacionVigente, $existeEvaluacionAnterior, $otraIdentidad) {
	echo "with (window.parent.document) {";
	echo "enableAllControls();";

	if (($isFueEvaluado) or (!$isEvaluacionVigente) or (!$isEvaluador) or ($isNotificado) or ($existeEvaluacionAnterior)) {
?>
	with (window.parent.document.getElementById('formEvaluacion'))
		for (i=0; i<elements.length; i++) {
			if (elements[i].type == 'radio')
				elements[i].disabled = true;

			if (elements[i].type == 'select-one')
				if (elements[i].id.substr(0, 6) == 'combo_')
					elements[i].disabled = true;

			if (elements[i].type == 'textarea')
				if ((elements[i].id != 'comentariosEvaluado') && (elements[i].id != 'comentariosEvaluador') && (elements[i].id != 'comentariosSupervisor'))
					elements[i].readOnly = true;
		}
<?
	}

	if ($isEvaluado) {
		echo "getElementById('btnGuardar').style.display = 'none';";
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
	}
	if ($isEvaluador) {
		echo "getElementById('btnMeNotifique').style.display = 'none';";
	}
	if ($isSupervisor) {
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
		echo "getElementById('btnMeNotifique').style.display = 'none';";
	}

	if (($isEvaluador) or ($isSupervisor) or (($isEvaluado) and (($isEvaluacionAceptada) or (!$isEvaluacionVigente)))) {
		echo "getElementById('comentariosEvaluado').readOnly = true;";
		echo "getElementById('btnMeNotifique').style.display = 'none';";
	}

	if (($isEvaluado) or ($isSupervisor) or (($isEvaluador) and (($isFueEvaluado) or (!$isEvaluacionVigente)))) {
		echo "getElementById('comentariosEvaluador').readOnly = true;";
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
		if (!$isSupervisor)
			echo "getElementById('btnGuardar').style.display = 'none';";
		if (($isSupervisor) and (!$isEvaluacionAceptada))
			echo "getElementById('btnGuardar').style.display = 'none';";
	}

	if (($isEvaluado) or ($isEvaluador) or (($isSupervisor) and ((!$isEvaluacionVigente) or (!$isEvaluacionAceptada)))) {
		echo "getElementById('comentariosSupervisor').readOnly = true;";
		if (!$isEvaluador)
			echo "getElementById('btnGuardar').style.display = 'none';";
	}

	if ($isNotificado) {
		if (!(($isSupervisor) and ($isEvaluacionAceptada))) {
			echo "getElementById('btnGuardar').style.display = 'none';";
			echo "getElementById('comentariosSupervisor').readOnly = true;";
		}
		echo "getElementById('btnMeNotifique').style.display = 'none';";
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
		echo "getElementById('comentariosEvaluado').readOnly = true;";
		echo "getElementById('comentariosEvaluador').readOnly = true;";
	}

	// ESTE IF SE PUEDE DESHABILITAR EN DESARROLLO SI ES NECESARIO GRABAR..
/*	if ($otraIdentidad) {
		echo "getElementById('btnEnviarEvaluacion').style.display = 'none';";
		echo "getElementById('btnGuardar').style.display = 'none';";
		echo "getElementById('btnMeNotifique').style.display = 'none';";
	}*/

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

function getResultadoEvaluacionNoSPAC($idEvaluacion, $idCompetencia) {
	global $conn;

	$params = array(":idcompetencia" => $idCompetencia, ":idusuario" => $idEvaluacion);
	$sql =
		"SELECT rc_id, rc_valor
			 FROM rrhh.rrc_relacomptencia, rrhh.rre_resultadoevaluacion
			WHERE rc_id = re_idrelacompetencia
				AND rc_fechabaja IS NULL
				AND re_fechabaja IS NULL
				AND rc_idcompetencia = :idcompetencia
				AND re_idusuario = :idusuario";
	$stmt = DBExecSql($conn, $sql, $params);
	$minValor = 9999;
	while ($row = DBGetQuery($stmt)) {
		$valor = substr(desencriptar($row["RC_VALOR"]), strlen($row["RC_ID"]));
		if ($valor < $minValor)
			$minValor = $valor;
	}

	switch ($minValor) {
		case 1:
			$result = "Estadio Inicial";
			break;
		case 2:
			$result = "En Desarrollo";
			break;
		case 3:
			$result = "Consolidado";
			break;
		case 4:
			$result = "Referencia e Influencia";
			break;
		default:
			$result = "";
	}

	return $result;
}

function hideAll() {
?>
	window.parent.document.getElementById('divDatos').style.display = 'none';
<?
}

function loadDatosCompetencias($idEvaluacion, $evaluado, $ano) {
	global $conn;

	echo "with (window.parent.document) {";

	// Cargo las competencias..
	$params = array(":anio" => $ano, ":idusuario" => $idEvaluacion);
	$sql =
		"SELECT ec_id, re_nivel_evaluado, re_observacion
			 FROM rrhh.rec_evaluacioncompetencia, rrhh.rre_resultadoevaluacion
			WHERE ec_id = re_idcompetencia(+)
				AND ec_grupo = 'SPAC'
				AND ec_fechabaja IS NULL
				AND re_fechabaja IS NULL
				AND ec_anio = :anio
				AND re_idusuario(+) = :idusuario
	 ORDER BY ec_orden";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt)) {
		setRadioIndex("item_".$row["EC_ID"], $row["RE_NIVEL_EVALUADO"]);
		echo "getElementById('observaciones_".$row["EC_ID"]."').value = unescape('".rawurlencode($row["RE_OBSERVACION"])."');";
	}

	// Cargo el nivel requerido..
	$params = array(":anio" => $ano, ":evaluado" => $evaluado);
	$sql =
		"SELECT ec_id, nr_nivel
			 FROM rrhh.rec_evaluacioncompetencia, rrhh.rnr_nivelrequerido
			WHERE ec_id = nr_idcompetencia(+)
				AND ec_grupo = 'SPAC'
				AND ec_fechabaja IS NULL
				AND nr_fechabaja IS NULL
				AND ec_anio = :anio
				AND nr_evaluado(+) = :evaluado
	 ORDER BY ec_orden";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		echo "getElementById('spanNivelRequerido_".$row["EC_ID"]."').innerHTML = '".$row["NR_NIVEL"]."';";

	// Cargo los comentarios..
	$params = array(":id" => $idEvaluacion);
	$sql =
		"SELECT ue_evaluado_comentario, ue_evaluador_comentario, ue_fechadesde, ue_fechahasta, ue_supervisor_comentario
			 FROM rrhh.rue_usuarioevaluacion
			WHERE ue_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
	echo "getElementById('tdPeriodo').innerHTML = 'desde <b>".$row["UE_FECHADESDE"]."</b> hasta <b>".$row["UE_FECHAHASTA"]."</b>';";
	echo "getElementById('comentariosEvaluado').value = unescape('".rawurlencode($row["UE_EVALUADO_COMENTARIO"])."');";
	echo "getElementById('comentariosEvaluador').value = unescape('".rawurlencode($row["UE_EVALUADOR_COMENTARIO"])."');";
	echo "getElementById('comentariosSupervisor').value = unescape('".rawurlencode($row["UE_SUPERVISOR_COMENTARIO"])."');";

	echo "}";
}

function loadDatosEvaluado($row) {
?>
	with (window.parent.document) {
		getElementById('nombreEvaluado').innerHTML = '<?= $row["SE_NOMBRE"]?>';
//		getElementById('puestoEvaluado').innerHTML = '<?= $row["PUESTO"]?>';
		getElementById('sectorEvaluado').innerHTML = '<?= $row["SECTOR"]?>';
		getElementById('gerenciaEvaluado').innerHTML = '<?= $row["GERENCIA"]?>';
	}
<?
}

function loadDatosEvaluador($row) {
?>
	with (window.parent.document) {
		getElementById('nombreEvaluador').innerHTML = '<?= $row["SE_NOMBRE"]?>';
//		getElementById('puestoEvaluador').innerHTML = '<?= $row["PUESTO"]?>';
		getElementById('sectorEvaluador').innerHTML = '<?= $row["SECTOR"]?>';
		getElementById('gerenciaEvaluador').innerHTML = '<?= $row["GERENCIA"]?>';
	}
<?
}

function setYear($ano) {
?>
	with (window.parent.document) {
		getElementById('labelAno3').innerHTML = '<?= $ano?>';
	}
<?
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
			getElementById('divDatos').style.display = 'inline-block';
		}
<?
	}
}

function setRadioIndex($radio, $value) {
	if ($value != "")
		echo "getElementById('formEvaluacion').".$radio."[".getRadioIndex($value)."].checked = true;";
}
?>