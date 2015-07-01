<?
function GetMaxItem($field) {
	global $conn;

	$result = "";
	$sql =
		"SELECT ".$field.", COUNT(*)
			FROM rrhh.hfe_formularioevaluacion2008
		 WHERE ".$field." IS NOT NULL
			  AND fe_anoevaluacion = :ano
			  AND fe_fechabaja IS NULL
			  AND fe_evaluado IN(SELECT ue_evaluado
													FROM rrhh.hue_usuarioevaluacion
												 WHERE ue_categoria = 'S'
													  AND ue_anoevaluacion = :ano
													  AND ue_fechabaja IS NULL)
	GROUP BY ".$field."
	ORDER BY 2 DESC";
	$params = array(":ano" => $_REQUEST["ano"]);

	return ValorSql($sql, "", $params);
}

function GetTotEvaluados($eval, $field, $max) {
	global $conn;

	$result = "";
	$sql =
		"SELECT COUNT(*)
			FROM rrhh.hfe_formularioevaluacion2008
		 WHERE ".$field." IS NOT NULL
			  AND fe_anoevaluacion = :ano
			  AND fe_fechabaja IS NULL
			  AND fe_evaluado IN(SELECT ue_evaluado
													FROM rrhh.hue_usuarioevaluacion
												 WHERE ue_categoria = 'S'
													  AND ue_anoevaluacion = :ano
													  AND ue_fechabaja IS NULL)
   			AND ".$field." ".$eval." '".$max."'";
	$params = array(":ano" => $_REQUEST["ano"]);

	return ValorSql($sql, "", $params);
}
?>
<table>
	<tr id="rowTitle">
		<td>Competencia</td>
		<td>Requerido</td>
		<td>Evaluado</td>
		<td>Evaluado por encima del promedio</td>
		<td>Evaluado igual al promedio</td>
		<td>Evaluado por debajo del promedio</td>
	</tr>
	<tr>
		<td>Orientación al cliente</td>
		<td><?= GetMaxItem("FE_CLIENTEESP")?></td>
		<td><?= GetMaxItem("FE_CLIENTE")?></td>
		<td><?= GetTotEvaluados("<", "FE_CLIENTE", GetMaxItem("FE_CLIENTE"))?></td>
		<td><?= GetTotEvaluados("=", "FE_CLIENTE", GetMaxItem("FE_CLIENTE"))?></td>
		<td><?= GetTotEvaluados(">", "FE_CLIENTE", GetMaxItem("FE_CLIENTE"))?></td>
	</tr>
	<tr>
		<td>Orientación a los resultados</td>
		<td><?= GetMaxItem("FE_ORIENTACIONESP")?></td>
		<td><?= GetMaxItem("FE_ORIENTACION")?></td>
		<td><?= GetTotEvaluados("<", "FE_ORIENTACION", GetMaxItem("FE_ORIENTACION"))?></td>
		<td><?= GetTotEvaluados("=", "FE_ORIENTACION", GetMaxItem("FE_ORIENTACION"))?></td>
		<td><?= GetTotEvaluados(">", "FE_ORIENTACION", GetMaxItem("FE_ORIENTACION"))?></td>
	</tr>
	<tr>
		<td>Trabajo en equipo</td>
		<td><?= GetMaxItem("FE_EQUIPOESP")?></td>
		<td><?= GetMaxItem("FE_EQUIPO")?></td>
		<td><?= GetTotEvaluados("<", "FE_EQUIPO", GetMaxItem("FE_EQUIPO"))?></td>
		<td><?= GetTotEvaluados("=", "FE_EQUIPO", GetMaxItem("FE_EQUIPO"))?></td>
		<td><?= GetTotEvaluados(">", "FE_EQUIPO", GetMaxItem("FE_EQUIPO"))?></td>
	</tr>
	<tr>
		<td>Adaptabilidad al cambio</td>
		<td><?= GetMaxItem("FE_ADAPTABILIDADESP")?></td>
		<td><?= GetMaxItem("FE_ADAPTIBILIDAD")?></td>
		<td><?= GetTotEvaluados("<", "FE_EQUIPO", GetMaxItem("FE_EQUIPO"))?></td>
		<td><?= GetTotEvaluados("=", "FE_EQUIPO", GetMaxItem("FE_EQUIPO"))?></td>
		<td><?= GetTotEvaluados(">", "FE_EQUIPO", GetMaxItem("FE_EQUIPO"))?></td>
	</tr>
	<tr>
		<td>Liderazgo</td>
		<td><?= GetMaxItem("FE_LIDERAZGOESP")?></td>
		<td><?= GetMaxItem("FE_LIDERAZGO")?></td>
		<td><?= GetTotEvaluados("<", "FE_LIDERAZGO", GetMaxItem("FE_LIDERAZGO"))?></td>
		<td><?= GetTotEvaluados("=", "FE_LIDERAZGO", GetMaxItem("FE_LIDERAZGO"))?></td>
		<td><?= GetTotEvaluados(">", "FE_LIDERAZGO", GetMaxItem("FE_LIDERAZGO"))?></td>
	</tr>
	<tr>
		<td>Pensamiento analítico</td>
		<td><?= GetMaxItem("FE_ANALITICOESP")?></td>
		<td><?= GetMaxItem("FE_ANALITICO")?></td>
		<td><?= GetTotEvaluados("<", "FE_ANALITICO", GetMaxItem("FE_ANALITICO"))?></td>
		<td><?= GetTotEvaluados("=", "FE_ANALITICO", GetMaxItem("FE_ANALITICO"))?></td>
		<td><?= GetTotEvaluados(">", "FE_ANALITICO", GetMaxItem("FE_ANALITICO"))?></td>
	</tr>
	<tr>
		<td>Capacidad de Organización</td>
		<td><?= GetMaxItem("FE_PLANIFICACIONESP")?></td>
		<td><?= GetMaxItem("FE_PLANIFICACION")?></td>
		<td><?= GetTotEvaluados("<", "FE_PLANIFICACION", GetMaxItem("FE_PLANIFICACION"))?></td>
		<td><?= GetTotEvaluados("=", "FE_PLANIFICACION", GetMaxItem("FE_PLANIFICACION"))?></td>
		<td><?= GetTotEvaluados(">", "FE_PLANIFICACION", GetMaxItem("FE_PLANIFICACION"))?></td>
	</tr>
</table>