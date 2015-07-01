<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<link href="/modules/abm_encuestas/css/style_encuestas.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
<?
$params = array(":idpregunta" => $_REQUEST["preguntaid"]);
$sql =
	"SELECT DISTINCT op_opcion, rp_idopcion
							FROM rrhh.rrp_respuestaspreguntas, rrhh.rop_opcionespreguntas
						 WHERE rp_idopcion = op_id
							 AND rp_idpregunta = :idpregunta
							 AND TRIM(rp_observaciones) IS NOT NULL
					ORDER BY rp_idopcion";
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
?>
	<table id="tableObservaciones">
		<tr>
			<td colspan="3" id="tituloOpcion"><?= $row["OP_OPCION"]?></td>
		</tr>
		<tr>
			<td id="tituloObservaciones">Fecha</td>
			<td id="tituloObservaciones">Usuario</td>
			<td id="tituloObservaciones">Observación</td>
		</tr>
<?
	$params = array(":idpregunta" => $_REQUEST["preguntaid"], ":id" => $row["RP_IDOPCION"]);
	$sql =
		"SELECT NVL(rp_fechamodif, rp_fechaalta) fecha, se_nombre, rp_observaciones
			 FROM rrhh.rrp_respuestaspreguntas, rrhh.rop_opcionespreguntas, use_usuarios
			WHERE rp_idopcion = op_id
				AND rp_usuario = se_id
				AND rp_idpregunta = :idpregunta
				AND op_id = :id
				AND TRIM(rp_observaciones) IS NOT NULL
	 ORDER BY rp_idopcion, fecha DESC, se_nombre";
	$stmt2 = DBExecSql($conn, $sql, $params);
	while ($row2 = DBGetQuery($stmt2)) {
?>
		<tr>
			<td id="celdaObservaciones"><?= $row2["FECHA"]?></td>
			<td id="celdaObservaciones"><?= $row2["SE_NOMBRE"]?></td>
			<td id="celdaObservaciones"><?= $row2["RP_OBSERVACIONES"]?></td>
		</tr>
<?
	}
?>
	</table>
<?
}
?>
	</body>
</html>