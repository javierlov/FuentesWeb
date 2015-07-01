<?
function DrawRow($firstRow, $rowSpan, $col1, $col2, $col3, $col4, $col5) {
	$id = "";
	if ($firstRow == "T")
		$id = 'id="firstRow"';
?>
	<tr>
<?
	if ($col1 != "") {
?>
		<td rowspan="<?= $rowSpan?>" <?= $id?>><?= $col1?></td>
		<td rowspan="<?= $rowSpan?>" <?= $id?>><?= $col2?></td>
<?
	}
?>
		<td <?= $id?>><?= $col3?></td>
		<td <?= $id?>><?= $col4?></td>
		<td <?= $id?>><?= $col5?></td>
	</tr>
<?
}


$sql =
	"SELECT useu2.se_nombre evaluador, useu.se_nombre evaluado, tb_descripcion puesto, fe_orientacion, fe_adaptibilidad, fe_equipo,
					fe_cliente, fe_liderazgo, fe_planificacion, fe_analitico, fe_orientacionesp, fe_adaptabilidadesp, fe_equipoesp, fe_clienteesp,
					fe_liderazgoesp, fe_planificacionesp, fe_analiticoesp,
					(SELECT ue_categoria
						FROM rrhh.hue_usuarioevaluacion
					  WHERE ue_evaluado = fe_evaluado
						  AND ue_anoevaluacion = :ano
						  AND ue_fechabaja IS NULL) esjefe
		FROM rrhh.hfe_formularioevaluacion2008 hfe, use_usuarios useu, use_usuarios useu2, ctb_tablas, computos.cse_sector cse,
				  computos.cse_sector cse2
	 WHERE fe_evaluado = useu.se_usuario
		  AND tb_clave(+) = 'USCAR'
		  AND tb_codigo(+) = useu.se_cargo
		  AND fe_evaluador = useu2.se_usuario
		  AND useu.se_idsector = cse.se_id
		  AND cse.se_idsectorpadre = cse2.se_id
		  AND fe_anoevaluacion = :ano
		  AND fe_fechabaja IS NULL
		  AND cse2.se_idsectorpadre = :idsectorpadre
ORDER BY 1, 2";
$params = array(":ano" => $_REQUEST["ano"], ":idsectorpadre" => $gerencia);
$stmt = DBExecSql($conn, $sql, $params);

$evaluadorAnterior = "";
while ($row = DBGetQuery($stmt)) {
	if ($evaluadorAnterior != $row["EVALUADOR"]) {
?>
</table>
<div id="evaluador"><?= $row["EVALUADOR"]?></div>
<table>
	<tr id="rowTitle">
		<td>Empleado</td>
		<td>Puesto</td>
		<td>Competencia</td>
		<td>Requerido</td>
		<td>Evaluado</td>
	</tr>
<?
	}

	DrawRow("T", (($row["ESJEFE"] == "S")?7:4), $row["EVALUADO"], $row["PUESTO"], "Orientación a los resultados", $row["FE_ORIENTACIONESP"], $row["FE_ORIENTACION"]);
	DrawRow("F", 0, "", "", "Adaptabilidad al cambio", $row["FE_ADAPTABILIDADESP"], $row["FE_ADAPTIBILIDAD"]);
	DrawRow("F", 0, "", "", "Trabajo en equipo", $row["FE_EQUIPOESP"], $row["FE_EQUIPO"]);
	DrawRow("F", 0, "", "", "Orientación al cliente", $row["FE_CLIENTEESP"], $row["FE_CLIENTE"]);

	if ($row["ESJEFE"] == "S") {
		DrawRow("F", 0, "", "", "Liderazgo", $row["FE_LIDERAZGOESP"], $row["FE_LIDERAZGO"]);
		DrawRow("F", 0, "", "", "Capacidad de Planificación y organización", $row["FE_PLANIFICACIONESP"], $row["FE_PLANIFICACION"]);
		DrawRow("F", 0, "", "", "Pensamiento analítico", $row["FE_ANALITICOESP"], $row["FE_ANALITICO"]);
	}

	$evaluadorAnterior = $row["EVALUADOR"];
}
?>
</table>