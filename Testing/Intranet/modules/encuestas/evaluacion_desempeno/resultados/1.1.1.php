<?
function GetEmpleados($where) {
	global $conn;

	$result = "";
	$sql =
		"SELECT se_nombre
			FROM rrhh.hfe_formularioevaluacion2008, use_usuarios
		 WHERE fe_evaluado = se_usuario
			  AND fe_anoevaluacion = :ano".$where."
			  AND fe_fechabaja IS NULL
	 ORDER BY 1";
	$params = array(":ano" => $_REQUEST["ano"]);
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt)) {
		$result.= $row["SE_NOMBRE"]."<br>";
	}

	return $result;
}

function GetCantidadEmpleados($where) {
	$sql =
		"SELECT COUNT(*)
			FROM rrhh.hfe_formularioevaluacion2008
		 WHERE fe_anoevaluacion = :ano
			  AND fe_fechabaja IS NULL".$where;
	$params = array(":ano" => $_REQUEST["ano"]);

	return ValorSql($sql, "", $params);
}
?>
<table>
	<tr id="rowTitle">
		<td>Evaluación</td>
		<td>Cantidad de empleados</td>
		<td>Detalle</td>
	</tr>
	<tr>
		<td>Desarrollo de competencias superior a lo requerido por el puesto</td>
		<td><?= GetCantidadEmpleados(" AND fe_int_competencia = 0")?></td>
		<td>
			<a href="#" onClick="showHideObj('detalle1')">Ver detalle</a>
			<div id="detalle1"><?= GetEmpleados(" AND fe_int_competencia = 0")?></div>
		</td>
	</tr>
	<tr>
		<td>Desarrollo de competencias en línea con lo requerido por el puesto</td>
		<td><?= GetCantidadEmpleados(" AND fe_int_competencia = 1")?></td>
		<td>-</td>
	</tr>
	<tr>
		<td>Desarrollo de competencias inferior a lo requerido por el puesto</td>
		<td><?= GetCantidadEmpleados(" AND fe_int_competencia = 2")?></td>
		<td>
			<a href="#" onClick="showHideObj('detalle2')">Ver detalle</a>
			<div id="detalle2"><?= GetEmpleados(" AND fe_int_competencia = 2")?></div>
		</td>
	</tr>
	<tr>
		<td>No evaluados</td>
		<td><?= GetCantidadEmpleados(" AND fe_int_competencia IS NULL")?></td>
		<td>-</td>
	</tr>
	<tr id="rowFooter">
		<td>Total de casos</td>
		<td><?= GetCantidadEmpleados("")?></td>
		<td>-</td>
	</tr>
</table>