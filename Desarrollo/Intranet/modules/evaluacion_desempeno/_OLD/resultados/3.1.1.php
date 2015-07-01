<?
function DrawRows($empleado, $evaluacionId) {
	global $conn;

	$sql =
		"SELECT ROWNUM ID, cm_mejora
			FROM (SELECT cm_mejora
						  FROM rrhh.hcm_compromisomejora, rrhh.hfe_formularioevaluacion2008
						 WHERE cm_id_formularioevaluacion = fe_id
							 AND fe_id = :id
							 AND cm_fechabaja IS NULL
							 AND cm_mejora IS NOT NULL
							 AND fe_anoevaluacion = :ano
							 AND fe_fechabaja IS NULL
					ORDER BY cm_id)";
	$params = array(":id" => $evaluacionId, ":ano" => $_REQUEST["ano"]);
	$stmt = DBExecSql($conn, $sql, $params);
	$tot = DBGetRecordCount($stmt);
	while ($row = DBGetQuery($stmt)) {
		echo '<tr>';
		$id = "";
		if ($row["ID"] == 1) {
			$id = 'id="firstRow"';
			echo '<td rowspan="'.$tot.'" '.$id.'>'.$empleado.'</td>';
		}
		echo '<td '.$id.'>'.$row["ID"].'</td>';
		echo '<td '.$id.'>'.$row["CM_MEJORA"].'</td>';
		echo '</tr>';
	}
}


$sql =
	"SELECT fe_id, se_nombre
		FROM rrhh.hfe_formularioevaluacion2008, use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2
	 WHERE fe_evaluado = useu.se_usuario
		  AND useu.se_idsector = cse.se_id
		  AND cse.se_idsectorpadre = cse2.se_id
		  AND fe_anoevaluacion = :ano
		  AND fe_fechabaja IS NULL
		  AND cse2.se_idsectorpadre = :idsectorpadre
ORDER BY 2";
$params = array(":ano" => $_REQUEST["ano"], ":idsectorpadre" => $gerencia);
$stmt = DBExecSql($conn, $sql, $params);
?>
<table>
	<tr id="rowTitle">
		<td>Empleado</td>
		<td>Nº</td>
		<td>Compromiso</td>
	</tr>
<?
while ($row = DBGetQuery($stmt)) {
	DrawRows($row["SE_NOMBRE"], $row["FE_ID"]);
}
?>
</table>