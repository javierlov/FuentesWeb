<?
function DrawRow($firstRow, $empleado, $tipoObjetivo, $evaluacionId, $campo) {
	global $conn;

	$id = "";
	if ($firstRow == "T")
		$id = 'id="firstRow"';
?>
	<tr>
<?
	if ($empleado != "") {
?>
		<td rowspan="6" <?= $id?>><?= $empleado?></td>
<?
	}
?>
		<td <?= $id?>><?= $tipoObjetivo?></td>
<?
	// OBJETIVO 1..
	$objetivo1 = "";
	$sql =
		"SELECT ".$campo." campo
			FROM rrhh.hfo_formularioobjetivo
		 WHERE fo_id_formularioevaluacion = :idformularioevaluacion
			  AND fo_nroobjetivo = 1
			  AND fo_fechabaja IS NULL
	ORDER BY fo_id DESC";
	$params = array(":idformularioevaluacion" => $evaluacionId);
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
	$objetivo1.= "<li>".$row["CAMPO"]."</li>";

	// OBJETIVO 2..
	$objetivo2 = "";
	$sql =
		"SELECT ".$campo." campo
			FROM rrhh.hfo_formularioobjetivo
		 WHERE fo_id_formularioevaluacion = :idformularioevaluacion
			  AND fo_nroobjetivo = 2
			  AND fo_fechabaja IS NULL
	ORDER BY fo_id DESC";
	$params = array(":idformularioevaluacion" => $evaluacionId);
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
	$objetivo2.= "<li>".$row["CAMPO"]."</li>";
?>
		<td <?= $id?>><ul style="margin-bottom:4px; margin-left:16px;"><?= ($objetivo1 == "")?"-":$objetivo1?></ul></td>
		<td <?= $id?>><ul style="margin-bottom:4px; margin-left:16px;"><?= ($objetivo2 == "")?"-":$objetivo2?></ul></td>
	</tr>
<?
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
		<td>Tipo Objetivo</td>
		<td>Objetivo 1</td>
		<td>Objetivo 2</td>
	</tr>
<?
while ($row = DBGetQuery($stmt)) {
	DrawRow("T", $row["SE_NOMBRE"], "Descripción del objetivo", $row["FE_ID"], "fo_objetivo");
	DrawRow("F", "", "Resultado a obtener", $row["FE_ID"], "fo_resultado");
	DrawRow("F", "", "Indicador", $row["FE_ID"], "fo_indicador");
	DrawRow("F", "", "Plazo de ejecución", $row["FE_ID"], "fo_plazo");

	DrawRow("F", "", "Porcentaje de cumplimiento", $row["FE_ID"], "NVL(fo_porcentajecumplimiento, 0) || '%'");
	DrawRow("F", "", "Estado", $row["FE_ID"], "DECODE(fo_estado, 'A', 'Alcanzado', DECODE(fo_estado, 'N', 'No alcanzado', DECODE(fo_estado, 'E', 'En proceso', 'Suspendido')))");
}
?>
</table>