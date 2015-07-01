<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$params = array();
$sql =
	"SELECT 2015 id, 2015 detalle
		 FROM DUAL
 ORDER BY 2";
$comboAno = new Combo($sql, "ano", $ano);
$comboAno->setAddFirstItem(false);
$comboAno->setOnChange("cambiarUsuarioAEvaluar(document.getElementById('usuarioAEvaluar').value, document.getElementById('ano').value)");

$sql =
	"SELECT ue_id id, ue_evaluado detalle, ue_evaluador
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_evaluado = UPPER(:usuario)
			AND ue_anio = :ano
			AND (ue_grupo = 'SPAC' OR ue_visible = 'S')
			AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_id, ue_evaluado, ue_evaluador
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_evaluador = UPPER(:usuario)
			AND ue_anio = :ano
			AND ue_grupo != 'NO PARTICIPA'
			AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_id, ue_evaluado, ue_evaluador
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_supervisor = UPPER(:usuario)
			AND ue_anio = :ano
			AND ue_grupo != 'NO PARTICIPA'
			AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_id, ue_evaluado, ue_evaluador
		 FROM rrhh.rue_usuarioevaluacion
		WHERE SUBSTR(ue_notificaciones, 1, INSTR(ue_notificaciones, ';') - 1) = UPPER(:usuario)
			AND ue_anio = :ano
			AND ue_grupo != 'NO PARTICIPA'
			AND ue_fechabaja IS NULL
UNION ALL
	 SELECT ue_id, ue_evaluado, ue_evaluador
		 FROM rrhh.rue_usuarioevaluacion
		WHERE SUBSTR(ue_notificaciones, INSTR(ue_notificaciones, ';') + 1, LENGTH(ue_notificaciones)) = UPPER(:usuario)
			AND ue_anio = :ano
			AND ue_grupo != 'NO PARTICIPA'
			AND ue_fechabaja IS NULL
 ORDER BY 2, 3";
$comboUsuarioAEvaluar = new Combo($sql, "usuarioAEvaluar", $user);
$comboUsuarioAEvaluar->addParam(":ano", $ano);
$comboUsuarioAEvaluar->addParam(":usuario", $user);
$comboUsuarioAEvaluar->setAddFirstItem(false);
$comboUsuarioAEvaluar->setOnChange("cambiarUsuarioAEvaluar(document.getElementById('usuarioAEvaluar').value, document.getElementById('ano').value)");
?>