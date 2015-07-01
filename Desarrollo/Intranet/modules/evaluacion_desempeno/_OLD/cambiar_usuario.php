<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once("functions.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
?>
<script language='JavaScript' src='js/evaluacion.js'></script>
<script>
	// Inicializo componentes..
	window.parent.document.getElementById('CerrarEvaluacion').value = false;
	window.parent.document.getElementById('CompromisoMejora1').value = '';
	window.parent.document.getElementById('CompromisoMejora2').value = '';
	window.parent.document.getElementById('CompromisoMejora3').value = '';
	window.parent.document.getElementById('CompromisoMejoraId1').value = -1;
	window.parent.document.getElementById('CompromisoMejoraId2').value = -1;
	window.parent.document.getElementById('CompromisoMejoraId3').value = -1;
	window.parent.document.getElementById('CompromisoMejoraNuevoItem').value = '';
	uncheckRadioControls();
<?
$user = $_SESSION["identidad"];
$ano = $_REQUEST["ano"];
$evaluado = $_REQUEST["user"];

$sql =
	"SELECT UPPER(ue_evaluador)
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_evaluado = UPPER(:evaluado)
		  AND ue_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => $ano);
$evaluador = ValorSql($sql, "", $params);

$sql =
	"SELECT UPPER(ue_supervisor)
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_evaluado = UPPER(:evaluado)
		  AND ue_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => $ano);
$supervisor = ValorSql($sql, "", $params);

$sql =
	"SELECT UPPER(ue_notificacion)
 		 FROM rrhh.hue_usuarioevaluacion
		WHERE ue_evaluado = UPPER(:evaluado)
			AND ue_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => $ano);
$notificado = ValorSql($sql, "", $params);

$sql =
	"SELECT 1
  	 FROM rrhh.hue_usuarioevaluacion
 		WHERE ue_evaluador = UPPER(:evaluador)
 			AND ue_anoevaluacion = :ano";
$params = array(":evaluador" => $user, ":ano" => $ano);
$esEvaluador = (ValorSql($sql, -1, $params) == 1);
?>
	window.parent.document.getElementById('Ano').value = <?= $ano?>;
	window.parent.document.getElementById('Evaluado').value = '<?= $evaluado?>';
	window.parent.document.getElementById('Evaluador').value = '<?= $evaluador?>';
	window.parent.document.getElementById('Supervisor').value = '<?= $supervisor?>';
<?
$sql =
	"SELECT 1
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_evaluado = UPPER(:evaluado)
		  AND ue_evaluador_ok = 1
		  AND ue_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => $ano);
$fueEvaluado = (ValorSql($sql, -1, $params) == 1);

$sql =
	"SELECT 1
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_evaluado = UPPER(:evaluado)
		  AND ue_evaluado_ok = 1
		  AND ue_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => $ano);
$evaluacionAceptada = (ValorSql($sql, -1, $params) == 1);


// Si no existe el registro lo inserto..
$sql =
	"SELECT fe_id
		FROM rrhh.hfe_formularioevaluacion2008
	 WHERE fe_evaluado = :evaluado
		  AND fe_estado = 1
		  AND fe_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => $ano);
$formularioId = ValorSql($sql, -1, $params);
if ($formularioId == -1) {
	$sql =
		"INSERT INTO rrhh.hfe_formularioevaluacion2008 (fe_id, fe_estado, fe_evaluado, fe_evaluador, fe_supervisor, fe_fechadesde,
																						  fe_fechahasta, fe_usualta, fe_fechaalta, fe_anoevaluacion)
																			SELECT NULL, 1, ue_evaluado, ue_evaluador, ue_supervisor, pa_fechadesde, pa_fechahasta,
																						 :usualta, SYSDATE, :ano
																				FROM rrhh.hpa_parametro, rrhh.hue_usuarioevaluacion
																			 WHERE pa_estado = 1
																				  AND pa_fechabaja IS NULL
																				  AND pa_ano = :ano
																				  AND ue_evaluado = :evaluado
																				  AND ue_fechabaja IS NULL
																				  AND ue_anoevaluacion = :ano";
	$params = array(":usualta" => $user, ":ano" => $ano, ":evaluado" => $evaluado);
	DBExecSql($conn, $sql, $params);

	// Obtengo el id del formulario..
	$sql =
		"SELECT fe_id
			FROM rrhh.hfe_formularioevaluacion2008
		 WHERE fe_evaluado = :evaluado
			  AND fe_estado = 1
			  AND fe_anoevaluacion = :ano";
	$params = array(":evaluado" => $evaluado, ":ano" => $ano);
	$formularioId = ValorSql($sql, -1, $params);

	// Actualizo las competencias que se espera que el usuario cumpla trayendolas de la evaluación anterior..
	$sql =
		"UPDATE rrhh.hfe_formularioevaluacion2008
				SET (fe_orientacionesp, fe_adaptabilidadesp, fe_equipoesp, fe_clienteesp, fe_liderazgoesp, fe_planificacionesp, fe_analiticoesp) =
		 (SELECT fe_orientacionfuturo, fe_adaptabilidadfuturo, fe_equipofuturo, fe_clientefuturo, fe_liderazgofuturo, fe_planificacionfuturo,
						fe_analiticofuturo
			 FROM rrhh.hfe_formularioevaluacion2008
		  WHERE fe_evaluado = :evaluado
				AND fe_fechabaja IS NULL
				AND fe_anoevaluacion = :anoevaluacion)
		  WHERE fe_id = :id";
	$params = array(":evaluado" => $evaluado, ":anoevaluacion" => ($ano - 1), ":id" => $formularioId);
	DBExecSql($conn, $sql, $params);
}
?>
	window.parent.document.getElementById('FormularioId').value = '<?= $formularioId?>';
<?
// Obtengo el dato de si la evaluación esta vigente o no..
$sql =
	"SELECT 1
		FROM rrhh.hfe_formularioevaluacion2008
	 WHERE fe_evaluado = UPPER(:evaluado)
		  AND fe_estado = 1
		  AND SYSDATE BETWEEN fe_fechadesde AND fe_fechahasta
		  AND fe_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => $ano);
$evaluacionVigente = (ValorSql($sql, -1, $params) == 1);


// Datos del evaluador..
$sql =
	"SELECT useu.se_nombre, tb_descripcion puesto, cse.se_descripcion sector, cse3.se_descripcion gerencia
		FROM use_usuarios useu, ctb_tablas, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
	 WHERE tb_clave(+) = 'USCAR'
		  AND tb_codigo(+) = useu.se_cargo
		  AND useu.se_idsector = cse.se_id
		  AND useu.se_fechabaja IS NULL
		  AND cse.se_idsectorpadre = cse2.se_id
		  AND cse2.se_idsectorpadre = cse3.se_id
		  AND useu.se_usuario = UPPER(:usuario)";
$params = array(":usuario" => $evaluador);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
LoadDatosEvaluador($row);

// Datos del evaluado..
$sql =
	"SELECT useu.se_nombre, tb_descripcion puesto, cse.se_descripcion sector, cse3.se_descripcion gerencia
		FROM use_usuarios useu, ctb_tablas, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
	 WHERE tb_clave(+) = 'USCAR'
		  AND tb_codigo(+) = useu.se_cargo
		  AND useu.se_idsector = cse.se_id
		  AND useu.se_fechabaja IS NULL
		  AND cse.se_idsectorpadre = cse2.se_id
		  AND cse2.se_idsectorpadre = cse3.se_id
		  AND useu.se_usuario = UPPER(:usuario)";
$params = array(":usuario" => $evaluado);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
LoadDatosEvaluado($row);

ShowDatosNoCargados(($user == $evaluado) and (!$fueEvaluado));

$sql =
	"SELECT ue_categoria
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_evaluado = UPPER(:evaluado)
		  AND ue_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => $ano);
ShowCompetenciasConduccion(ValorSql($sql, "N", $params) == "S");

// Cargo datos de las competencias..
$sql =
	"SELECT hfe.*, TO_CHAR(fe_fechadesde, 'dd/mm/yyyy') fechadesde, TO_CHAR(fe_fechahasta, 'dd/mm/yyyy') fechahasta
		FROM rrhh.hfe_formularioevaluacion2008 hfe
	 WHERE fe_id = :id
		  AND fe_anoevaluacion = :anoevaluacion";
$params = array(":id" => $formularioId, ":anoevaluacion" => $ano);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
LoadDatosCompetencias($row);

// Cargo el objetivo 1..
$sql =
	"SELECT *
		FROM (SELECT fo_estado, fo_id, fo_indicador, fo_indicadorfuturo, fo_objetivo, fo_objetivofuturo, fo_plazo, fo_plazofuturo,
								 NVL(fo_porcentajecumplimiento, 0) fo_porcentajecumplimiento, fo_resultado, fo_resultadofuturo, ROWNUM total
					  FROM rrhh.hfo_formularioobjetivo
					WHERE fo_id_formularioevaluacion = :idformularioevaluacion
						 AND fo_nroobjetivo = 1
						 AND fo_fechabaja IS NULL)
 ORDER BY fo_id DESC";
$params = array(":idformularioevaluacion" => $formularioId);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
LoadObjetivo(1, $row);

// Cargo el objetivo 2..
$sql =
	"SELECT *
		FROM (SELECT fo_estado, fo_id, fo_indicador, fo_indicadorfuturo, fo_objetivo, fo_objetivofuturo, fo_plazo, fo_plazofuturo,
								 NVL(fo_porcentajecumplimiento, 0) fo_porcentajecumplimiento, fo_resultado, fo_resultadofuturo, ROWNUM total
					  FROM rrhh.hfo_formularioobjetivo
					WHERE fo_id_formularioevaluacion = :idformularioevaluacion
						 AND fo_nroobjetivo = 2
						 AND fo_fechabaja IS NULL)
 ORDER BY fo_id DESC";
$params = array(":idformularioevaluacion" => $formularioId);
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
LoadObjetivo(2, $row);

// Cargo los compromisos de mejora..
$sql =
	"SELECT cm_id, cm_mejora
		FROM rrhh.hcm_compromisomejora, rrhh.hfe_formularioevaluacion2008
	 WHERE cm_id_formularioevaluacion = fe_id
		  AND fe_id = :id
		  AND cm_fechabaja IS NULL
 ORDER BY cm_id";
$params = array(":id" => $formularioId);
$stmt = DBExecSql($conn, $sql, $params);
LoadCompromisosMejora($stmt);

// Cargo los eventos positivos..
$sql =
	"SELECT TO_CHAR(fs_fecha, 'dd/mm/yyyy') fs_fecha, fs_evento
		FROM rrhh.hfs_formularioseguimiento, rrhh.hfe_formularioevaluacion2008
	 WHERE fs_id_formularioevaluacion = fe_id
		  AND fe_id = :id
		  AND fs_positivonegativo = 'P'
		  AND fs_fechabaja IS NULL";
$params = array(":id" => $formularioId);
$stmt = DBExecSql($conn, $sql, $params);
LoadEventos("P", $stmt);

// Cargo los eventos negativos..
$sql =
	"SELECT TO_CHAR(fs_fecha, 'dd/mm/yyyy') fs_fecha, fs_evento
		FROM rrhh.hfs_formularioseguimiento, rrhh.hfe_formularioevaluacion2008
	 WHERE fs_id_formularioevaluacion = fe_id
		  AND fe_id = :id
		  AND fs_positivonegativo = 'N'
		  AND fs_fechabaja IS NULL";
$params = array(":id" => $formularioId);
$stmt = DBExecSql($conn, $sql, $params);
LoadEventos("N", $stmt);

$sql =
	"SELECT 1
		FROM rrhh.hfe_formularioevaluacion2008
	  WHERE fe_evaluado = :evaluado
		  AND fe_anoevaluacion = :ano";
$params = array(":evaluado" => $evaluado, ":ano" => ($ano - 1));
$existeEvaluacionAnterior = ExisteSql($sql, $params);

SetYear($ano);

// Deshabilito controles según corresponda..
DisableControls(($user == $evaluado),
						  ($user == $evaluador),
						  ($user == $supervisor),
						  ($user == $notificado),
						  ($fueEvaluado),
						  $evaluacionAceptada,
						  $evaluacionVigente,
						  $existeEvaluacionAnterior,
						  ($_SESSION["identidad"] != GetWindowsLoginName(true)));

if ($evaluado == -1)
	HideAll();


// Cargo el combo de usuarios con los usuarios del año seleccionado..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "parent.window";

$RCfield = "UsuarioAEvaluar";
$RCparams = array(":usuario" => $user, ":ano" => $ano);
$RCquery =
	"SELECT ue_evaluado ID, ue_evaluado detalle
     FROM rrhh.hue_usuarioevaluacion
    WHERE ue_evaluado = UPPER(:usuario)
    	AND ue_anoevaluacion = :ano
    	AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_evaluado, ue_evaluado
     FROM rrhh.hue_usuarioevaluacion
    WHERE ue_evaluador = UPPER(:usuario)
    	AND ue_anoevaluacion = :ano
    	AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_evaluado ID, ue_evaluado detalle
     FROM rrhh.hue_usuarioevaluacion
    WHERE ue_supervisor = UPPER(:usuario)
    	AND ue_anoevaluacion = :ano
    	AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_evaluado ID, ue_evaluado detalle
     FROM rrhh.hue_usuarioevaluacion
    WHERE ue_notificacion = UPPER(:usuario)
    	AND ue_anoevaluacion = :ano
    	AND ue_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = $evaluado;
FillCombo();
?>

/* INICIO - PONER COLORES AL COMBO DE USUARIOS */
	var combo = window.parent.document.getElementById('UsuarioAEvaluar');
	var arrTiposUsuarios = new Array();
	arrTiposUsuarios[0] = 0;
<?
$sql =
	"SELECT   1 tipousuario, ue_evaluado
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_evaluado = UPPER(:usuario)
		 AND ue_anoevaluacion = :ano
		 AND ue_fechabaja IS NULL
UNION ALL
	SELECT   2, ue_evaluado
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_evaluador = UPPER(:usuario)
		 AND ue_anoevaluacion = :ano
		 AND ue_fechabaja IS NULL
UNION ALL
	SELECT   3, ue_evaluado
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_supervisor = UPPER(:usuario)
		 AND ue_anoevaluacion = :ano
		 AND ue_fechabaja IS NULL
UNION ALL
	SELECT   4, ue_evaluado
		FROM rrhh.hue_usuarioevaluacion
	 WHERE ue_notificacion = UPPER(:usuario)
		 AND ue_anoevaluacion = :ano
		 AND ue_fechabaja IS NULL
ORDER BY ue_evaluado";
$params = array(":usuario" => $user, ":ano" => $ano);
$stmt = DBExecSql($conn, $sql, $params);
$i = 1;
while ($row = DBGetQuery($stmt)) {
?>
	arrTiposUsuarios[<?= $i?>] = <?= $row["TIPOUSUARIO"]?>;
<?
	$i++;
}
?>
	for (var i=0; i<arrTiposUsuarios.length;i++) {
		if (arrTiposUsuarios[i] == 1) {
			combo.options[i].style.backgroundColor = '#ddf';
			combo.options[i].title = 'Usuario actual';
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

	window.parent.document.getElementById('tableUsuariosAEvaluar').style.display = '<?= (($esEvaluador)?"block":"none")?>';
</script>