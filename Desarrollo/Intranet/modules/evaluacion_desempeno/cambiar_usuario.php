<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once("crypt.php");
require_once("functions.php");


if (!isset($_SESSION["identidad"]))
	$_SESSION["identidad"] = getWindowsLoginName(true);
?>
<script language="JavaScript" src="/modules/evaluacion_desempeno/js/evaluacion.js?rnd=<?= time()?>"></script>
<script>
	// Inicializo componentes..
	window.parent.document.getElementById('cerrarEvaluacion').value = false;
	uncheckRadioControls();
<?
$params = array(":id" => $_REQUEST["idevaluacion"]);
$sql =
	"SELECT ue_anio, ue_evaluado
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$ano = $_REQUEST["ano"];
$evaluado = $row["UE_EVALUADO"];
$user = $_SESSION["identidad"];

if ($_REQUEST["ano"] == $row["UE_ANIO"])
	$idEvaluacion = $_REQUEST["idevaluacion"];
else {
	$params = array(":anio" => $_REQUEST["ano"], ":evaluado" => $row["UE_EVALUADO"]);
	$sql =
		"SELECT ue_id
			 FROM rrhh.rue_usuarioevaluacion
			WHERE ue_anio = :anio
				AND ue_evaluado = :evaluado";
	$idEvaluacion = valorSql($sql, -1, $params);
}

$params = array(":id" => $idEvaluacion);
$sql =
	"SELECT CASE WHEN ART.ACTUALDATE BETWEEN ue_fechadesde AND ue_fechahasta THEN 'S' ELSE 'N' END evaluacionvigente, UPPER(ue_evaluador) evaluador, ue_evaluado_fecha, ue_evaluador_fecha,
					ue_grupo, UPPER(ue_supervisor) supervisor
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$esEvaluador = ($row["EVALUADOR"] == $user);
$evaluacionAceptada = ($row["UE_EVALUADO_FECHA"] != "");
$evaluacionVigente = ($row["EVALUACIONVIGENTE"] == "S");
$evaluador = $row["EVALUADOR"];
$fueEvaluado = ($row["UE_EVALUADOR_FECHA"] != "");
$grupo = $row["UE_GRUPO"];
$notificado = ($row["UE_EVALUADO_FECHA"] != "");
$supervisor = $row["SUPERVISOR"];
?>
	window.parent.document.getElementById('ano').value = <?= $ano?>;
	window.parent.document.getElementById('evaluado').value = '<?= $evaluado?>';
	window.parent.document.getElementById('evaluador').value = '<?= $evaluador?>';
	window.parent.document.getElementById('supervisor').value = '<?= $supervisor?>';
<?
// Datos del evaluador..
$params = array(":usuario" => $evaluador);
$sql =
	"SELECT cse3.se_descripcion gerencia, tb_descripcion puesto, useu.se_nombre, cse.se_descripcion sector
		 FROM use_usuarios useu, ctb_tablas, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
		WHERE tb_clave(+) = 'USCAR'
			AND tb_codigo(+) = useu.se_cargo
			AND useu.se_idsector = cse.se_id
			AND useu.se_fechabaja IS NULL
			AND cse.se_idsectorpadre = cse2.se_id
			AND cse2.se_idsectorpadre = cse3.se_id
			AND useu.se_usuario = UPPER(:usuario)";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
loadDatosEvaluador($row);

// Datos del evaluado..
$params = array(":usuario" => $evaluado);
$sql =
	"SELECT cse3.se_descripcion gerencia, tb_descripcion puesto, useu.se_nombre, cse.se_descripcion sector
		 FROM use_usuarios useu, ctb_tablas, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
		WHERE tb_clave(+) = 'USCAR'
			AND tb_codigo(+) = useu.se_cargo
			AND useu.se_idsector = cse.se_id
			AND useu.se_fechabaja IS NULL
			AND cse.se_idsectorpadre = cse2.se_id
			AND cse2.se_idsectorpadre = cse3.se_id
			AND useu.se_usuario = UPPER(:usuario)";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
loadDatosEvaluado($row);

showDatosNoCargados(($user == $evaluado) and (!$fueEvaluado));

// Cargo datos de las competencias..
loadDatosCompetencias($idEvaluacion, $evaluado, $ano);

$params = array(":evaluado" => $evaluado, ":ano" => ($ano - 1));
$sql =
	"SELECT 1
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_evaluado = :evaluado
			AND ue_anio = :ano";
$existeEvaluacionAnterior = existeSql($sql, $params);

setYear($ano);

// INICIO - Carga datos NO SPAC..
if ($grupo != "SPAC") {
	$params = array(":anio" => $ano, ":grupo" => $grupo);
	$sql =
		"SELECT ec_descripcion, ec_id
			 FROM rrhh.rec_evaluacioncompetencia
			WHERE ec_anio = :anio
				AND ec_grupo = :grupo
				AND ec_fechabaja IS NULL
	 ORDER BY ec_orden";
	$stmt = DBExecSql($conn, $sql, $params);
	$html = "";
	$colorFondo = "ace";
	while ($row = DBGetQuery($stmt)) {
		$colorFondo = (($colorFondo == "ace")?"eca":"ace");
		$html.= "<div style=\"background-color:#".$colorFondo."; padding-left:4px; padding-top:8px;\"><b>".$row["EC_DESCRIPCION"]."</b><span style=\"color:#4951e2; font-size:20pt; margin-left:24px; \">".getResultadoEvaluacionNoSPAC($idEvaluacion, $row["EC_ID"])."</span></div>";
		$html.= "<div style=\"background-color:#".$colorFondo."; padding-left:4px;\">";

		$params = array(":idcompetencia" => $row["EC_ID"]);
		$sql =
			"SELECT DISTINCT rc_combo
									FROM rrhh.rrc_relacomptencia
								 WHERE rc_idcompetencia = :idcompetencia
									 AND rc_fechabaja IS NULL
							ORDER BY rc_combo";
		$stmt2 = DBExecSql($conn, $sql, $params);
		while ($rowCombos = DBGetQuery($stmt2)) {
			$html.= "<div class=\"divComboNoSPAC\"><select id=\"combo_".$row["EC_ID"]."_".$rowCombos["RC_COMBO"]."\" name=\"combo_".$row["EC_ID"]."_".$rowCombos["RC_COMBO"]."\" onFocus=\"despintarCombo(this)\">";
			$html.= "<option value=\"-1\">- SELECCIONE UNA OPCIÓN -</option>";

			$params = array(":combo" => $rowCombos["RC_COMBO"],
											":idcompetencia" => $row["EC_ID"],
											":idusuario" => $idEvaluacion);
			$sql =
				"SELECT rc_descripcion, rc_id, re_idrelacompetencia
					 FROM rrhh.rrc_relacomptencia, rrhh.rre_resultadoevaluacion
					WHERE rc_id = re_idrelacompetencia(+)
						AND rc_fechabaja IS NULL
						AND re_fechabaja IS NULL
						AND rc_idcompetencia = :idcompetencia
						AND rc_combo = :combo
						AND re_idusuario(+) = :idusuario
			 ORDER BY rc_orden";
			$stmt3 = DBExecSql($conn, $sql, $params);
			while ($rowOpciones = DBGetQuery($stmt3)) {
				$selected = (($rowOpciones["RC_ID"] == $rowOpciones["RE_IDRELACOMPETENCIA"])?"selected":"");
				$html.= "<option ".$selected." value=\"".$rowOpciones["RC_ID"]."\">".substr(desencriptar($rowOpciones["RC_DESCRIPCION"]), strlen($rowOpciones["RC_ID"]))."</option>";
			}

			$html.= "</select></div>";
		}

		$html.= "</div>";
	}
?>
	window.parent.document.getElementById('divNoSPAC').innerHTML = '<?= $html?>';
	setTimeout('ajustarAnchoCombos()', 500);
<?
}
// FIN - Carga datos NO SPAC..

// Deshabilito controles según corresponda..
disableControls(($user == $evaluado),
								($user == $evaluador),
								($user == $supervisor),
								($user == $notificado),
								$fueEvaluado,
								$evaluacionAceptada,
								$evaluacionVigente,
								$existeEvaluacionAnterior,
								($_SESSION["identidad"] != getWindowsLoginName(true)));

if ($evaluado == -1)
	hideAll();

// Cargo el combo de usuarios con los usuarios del año seleccionado..
$sql =
	"SELECT ue_id id, ue_evaluado detalle, ue_evaluador, 1 tipousuario
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_evaluado = UPPER(:usuario)
			AND ue_anio = :ano
			AND (ue_grupo = 'SPAC' OR ue_visible = 'S')
			AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_id, ue_evaluado, ue_evaluador, 2
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_evaluador = UPPER(:usuario)
			AND ue_anio = :ano
			AND ue_grupo != 'NO PARTICIPA'
			AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_id, ue_evaluado, ue_evaluador, 3
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_supervisor = UPPER(:usuario)
			AND ue_anio = :ano
			AND ue_grupo != 'NO PARTICIPA'
			AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_id, ue_evaluado, ue_evaluador, 4
		 FROM rrhh.rue_usuarioevaluacion
		WHERE SUBSTR(ue_notificaciones, 1, INSTR(ue_notificaciones, ';') - 1) = UPPER(:usuario)
			AND ue_anio = :ano
			AND ue_grupo != 'NO PARTICIPA'
			AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_id, ue_evaluado, ue_evaluador, 4
		 FROM rrhh.rue_usuarioevaluacion
		WHERE SUBSTR(ue_notificaciones, INSTR(ue_notificaciones, ';') + 1, LENGTH(ue_notificaciones)) = UPPER(:usuario)
			AND ue_anio = :ano
			AND ue_grupo != 'NO PARTICIPA'
			AND ue_fechabaja IS NULL
 ORDER BY 2, 3";
$comboUsuarioAEvaluar = new Combo($sql, "usuarioAEvaluar", $evaluado);
$comboUsuarioAEvaluar->addParam(":ano", $ano);
$comboUsuarioAEvaluar->addParam(":usuario", $user);
$comboUsuarioAEvaluar->setAddFirstItem(false);
$comboUsuarioAEvaluar->setOnChange("cambiarUsuarioAEvaluar(document.getElementById('usuarioAEvaluar').value, document.getElementById('ano').value)");
?>

/* INICIO - PONER COLORES AL COMBO DE USUARIOS */
	var combo = window.parent.document.getElementById('usuarioAEvaluar');
	var arrEvaluadores = new Array();
	var arrTiposUsuarios = new Array();
<?
$params = array(":usuario" => $user, ":ano" => $ano);
$stmt = DBExecSql($conn, $sql, $params);
$i = 0;
while ($row = DBGetQuery($stmt)) {
?>
	arrEvaluadores[<?= $i?>] = '<?= $row["UE_EVALUADOR"]?>';
	arrTiposUsuarios[<?= $i?>] = <?= $row["TIPOUSUARIO"]?>;
<?
	$i++;
}
?>
	for (var i=0; i<arrTiposUsuarios.length;i++) {
		if (arrTiposUsuarios[i] == 1) {
			combo.options[i].style.backgroundColor = '#ddf';
			combo.options[i].title = 'Usuario actual evaluado por ' + arrEvaluadores[i];
		}
		if (arrTiposUsuarios[i] == 2) {
			combo.options[i].style.backgroundColor = '#fdd';
			combo.options[i].title = 'Usuario evaluado';
		}
		if (arrTiposUsuarios[i] == 3) {
			combo.options[i].style.backgroundColor = '#dfd';
			combo.options[i].title = 'Usuario supervisado';
		}
		if (arrTiposUsuarios[i] == 4) {
			combo.options[i].style.backgroundColor = '#eee';
			combo.options[i].title = 'Usuario notificado';
		}
	}
/* FIN - PONER COLORES AL COMBO DE USUARIOS */

	with (window.parent.document) {
		getElementById('divCompetencias').style.display = 'block';

		getElementById('divSPAC').style.display = '<?= ($grupo == "SPAC")?"block":"none"?>';
		getElementById('divNoSPAC').style.display = '<?= ($grupo != "SPAC")?"block":"none"?>';

		getElementById('comentariosEvaluado').style.display = '<?= ($grupo == "SPAC")?"block":"none"?>';
//		getElementById('comentariosSupervisor').style.display = '<?= ($grupo == "SPAC")?"block":"none"?>';
		getElementById('comentariosSupervisor').style.display = 'none';
		getElementById('divComentariosEvaluadoTitulo').style.display = '<?= ($grupo == "SPAC")?"block":"none"?>';
//		getElementById('divComentariosSupervisorTitulo').style.display = '<?= ($grupo == "SPAC")?"block":"none"?>';
		getElementById('divComentariosSupervisorTitulo').style.display = 'none';
	}
</script>