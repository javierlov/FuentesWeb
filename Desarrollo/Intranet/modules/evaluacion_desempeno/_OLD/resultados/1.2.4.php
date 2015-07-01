<?
function GetMaxItem($field) {
	global $conn;
	global $gerencia;

	$result = "";
	$sql =
		"SELECT ".$field.", COUNT(*)
			FROM rrhh.hfe_formularioevaluacion2008, use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2
		 WHERE fe_evaluado = useu.se_usuario
			  AND useu.se_idsector = cse.se_id
			  AND cse.se_idsectorpadre = cse2.se_id
			  AND fe_anoevaluacion = :ano
			  AND fe_fechabaja IS NULL
			  AND cse2.se_idsectorpadre = :idsectorpadre
			  AND ".$field." IS NOT NULL
	GROUP BY ".$field."
	ORDER BY 2 DESC";
	$params = array(":ano" => $_REQUEST["ano"], ":idsectorpadre" => $gerencia);

	return ValorSql($sql, "", $params);
}

function GetTotEvaluados($eval, $field, $max) {
	global $conn;
	global $gerencia;

	$result = "";
	$sql =
		"SELECT COUNT(*)
			FROM rrhh.hfe_formularioevaluacion2008, use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2
		 WHERE fe_evaluado = useu.se_usuario
			  AND useu.se_idsector = cse.se_id
			  AND cse.se_idsectorpadre = cse2.se_id
			  AND fe_anoevaluacion = :ano
			  AND fe_fechabaja IS NULL
			  AND cse2.se_idsectorpadre = :idsectorpadre
			  AND ".$field." IS NOT NULL
			  AND ".$field." ".$eval." '".$max."'";
	$params = array(":ano" => $_REQUEST["ano"], ":idsectorpadre" => $gerencia);

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
		<td><?= GetTotEvaluados("<", "FE_ADAPTIBILIDAD", GetMaxItem("FE_ADAPTIBILIDAD"))?></td>
		<td><?= GetTotEvaluados("=", "FE_ADAPTIBILIDAD", GetMaxItem("FE_ADAPTIBILIDAD"))?></td>
		<td><?= GetTotEvaluados(">", "FE_ADAPTIBILIDAD", GetMaxItem("FE_ADAPTIBILIDAD"))?></td>
	</tr>
</table>