<?
header("Content-type: application/vnd-ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=Respuestas_por_usuarios_".date("dmY").".xls");
header("Pragma: no-cache");
header("Expires: 0");
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


function getRespuestas($idEncuesta, $idPregunta, $usuario) {
	global $conn;

	$result = "";

	$params = array(":idencuesta" => $idEncuesta,
									":idpregunta" => $idPregunta,
									":usuario" => $usuario);
	$sql =
		"SELECT op_opcion
			 FROM rrhh.rop_opcionespreguntas, rrhh.rrp_respuestaspreguntas
			WHERE op_id = rp_idopcion
				AND rp_idencuesta = :idencuesta
				AND rp_idpregunta = :idpregunta
				AND rp_usuario = :usuario";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		$result.= $row["OP_OPCION"]." - ";

	return substr($result, 0, -3);
}


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT en_detalle
		 FROM rrhh.ren_encuestas
		WHERE en_id = :id";
$tituloEncuesta = valorSql($sql, "", $params);
?>
<table style="background-color:#ff8072;">
	<tr>
		<th><?= $tituloEncuesta?></th>
	</tr>
</table>
<table>
<?
$params = array(":idencuesta" => $_REQUEST["id"]);
$sql =
	"SELECT pe_pregunta
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_fechabaja IS NULL
			AND pe_idencuesta = :idencuesta";
$stmt = DBExecSql($conn, $sql, $params);
$num = 1;
while ($row = DBGetQuery($stmt)) {
?>
	<tr>
		<th align="left" style="background-color:#c2d560;">Pregunta <?= $num?> - <?= $row["PE_PREGUNTA"]?></th>
	</tr>
<?
	$num++;
}
?>
	<tr>
		<th></th>
<?
$params = array(":idencuesta" => $_REQUEST["id"]);
$sql =
	"SELECT 1
		 FROM rrhh.rpe_preguntasencuesta
		WHERE pe_fechabaja IS NULL
			AND pe_idencuesta = :idencuesta";
$stmt = DBExecSql($conn, $sql, $params);
$num = 1;
while ($row = DBGetQuery($stmt)) {
?>
		<th>Respuesta <?= $num?></th>
<?
	$num++;
}
?>
	</tr>
</table>
<table border=1>
<?
$params = array(":idencuesta" => $_REQUEST["id"]);
$sql =
	"SELECT DISTINCT rp_usuario, se_nombre
							FROM rrhh.rrp_respuestaspreguntas, use_usuarios
						 WHERE rp_usuario = se_id
							 AND rp_fechabaja IS NULL
							 AND rp_idencuesta = :idencuesta
					ORDER BY se_nombre";
$stmt = DBExecSql($conn, $sql, $params);
$num = 1;
while ($row = DBGetQuery($stmt)) {
?>
	<tr>
		<th align=left><?= $row["SE_NOMBRE"]?></th>
<?
	$params = array(":idencuesta" => $_REQUEST["id"]);
	$sql =
		"SELECT pe_id
			 FROM rrhh.rpe_preguntasencuesta
			WHERE pe_fechabaja IS NULL
				AND pe_idencuesta = :idencuesta";
	$stmt2 = DBExecSql($conn, $sql, $params);
	while ($row2 = DBGetQuery($stmt2)) {
?>
		<th align=left><?= getRespuestas($_REQUEST["id"], $row2["PE_ID"], $row["RP_USUARIO"])?></th>
<?
	}
?>
	</tr>
<?
}
?>
</table>