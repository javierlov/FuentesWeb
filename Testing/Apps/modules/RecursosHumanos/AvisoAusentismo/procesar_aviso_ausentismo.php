<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
	exit;
}


$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT aa_idausencia
		 FROM web.waa_avisosausentismo
		WHERE aa_idtransaccionweb = :idtransaccion";
$idAusencia = ValorSql($sql, -1, $params);

$title = "INFORME DE AUSENTISMO";
?>
<html>
	<head>
		<title>IntraWEB | <?= $title?> - Comprobante</title>
		<script src="/js/functions.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	</head>
<body>
<?
$permite = "F";
if ($_REQUEST["PERMITE"] == "S")
	$permite = "T";

$params = array(":enviarmedico" => $permite, ":id" => $idAusencia);
$sql =
	"UPDATE rrhh.rha_ausencias
			SET ha_enviarmedico = :enviarmedico
		WHERE ha_id = :id";
DBExecSql($conn, $sql, $params);

$params = array(":enviomedico" => $_REQUEST["PERMITE"],
								":usuariorespuesta" => $_REQUEST["USERNAME"],
								":observacion" => $_REQUEST["OBSERVACIONES"],
								":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql =
	"UPDATE web.waa_avisosausentismo
			SET aa_fecharespuesta = SYSDATE,
					aa_enviomedico = :enviomedico,
					aa_usuariorespuesta = :usuariorespuesta,
					aa_observacionrespuesta = :observacion
		WHERE aa_idtransaccionweb = :idtransaccion";
DBExecSql($conn, $sql, $params);


$sql = 
	"SELECT ha_empleado, ma_detalle, se_nombre, ha_observaciones, aa_fecharespuesta, DECODE(aa_enviomedico, 'S', 'Sí', 'No') enviomedico, aa_observacionrespuesta
		 FROM rrhh.rha_ausencias, web.waa_avisosausentismo, rrhh.rma_motivosausencia, use_usuarios
		WHERE ha_id = aa_idausencia
			AND ma_id = ha_idmotivoausencia
			AND se_usuario = ha_usualta
			AND aa_idtransaccionweb = ".$_REQUEST["TRANSACCION"];
BuildTable($title, $conn, $sql,
					 array("EMPLEADO AUSENTE", "MOTIVO", "REPORTADO POR", "OBSERVACIONES", "FECHA RESPUESTA", "ENVIAR MÉDICO", "SUS OBSERVACIONES", ""),
					 array(0, 0, 0, 0, 0, 0, 0, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 0),
					 "",
					 array("Imprimir", "Salir"),
					 array("PrintWebPage", "CloseWindow"),
					 "",
					 array(0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>