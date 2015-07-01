<?
$sql =
	"SELECT useu.se_nombre evaluado, fe_comentarioevaluado, useu2.se_nombre evaluador, fe_comentarioevaluador,
					useu3.se_nombre supervisor, fe_comentariosupervisor
		FROM rrhh.hfe_formularioevaluacion2008, use_usuarios useu, use_usuarios useu2, use_usuarios useu3, computos.cse_sector cse,
				  computos.cse_sector cse2
	 WHERE fe_evaluado = useu.se_usuario
		  AND fe_evaluador = useu2.se_usuario
		  AND fe_supervisor = useu3.se_usuario(+)
		  AND useu.se_idsector = cse.se_id
		  AND cse.se_idsectorpadre = cse2.se_id
		  AND fe_anoevaluacion = :ano
		  AND fe_fechabaja IS NULL
		  AND cse2.se_idsectorpadre = :idsectorpadre
ORDER BY 1";
$params = array(":ano" => $_REQUEST["ano"], ":idsectorpadre" => $gerencia);
$stmt = DBExecSql($conn, $sql, $params);
?>
<table>
	<tr id="rowTitle">
		<td>Empleado</td>
		<td>Comentario</td>
		<td>Evaluador</td>
		<td>Comentario</td>
		<td>Supervisor</td>
		<td>Comentario</td>
	</tr>
<?
while ($row = DBGetQuery($stmt)) {
?>
	<tr>
		<td><?= $row["EVALUADO"]?></td>
		<td><?= $row["FE_COMENTARIOEVALUADO"]?></td>
		<td><?= $row["EVALUADOR"]?></td>
		<td><?= $row["FE_COMENTARIOEVALUADOR"]?></td>
		<td><?= $row["SUPERVISOR"]?></td>
		<td><?= $row["FE_COMENTARIOSUPERVISOR"]?></td>
	</tr>
<?
}
?>
</table>