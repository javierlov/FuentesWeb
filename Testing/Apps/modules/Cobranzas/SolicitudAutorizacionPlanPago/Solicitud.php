<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	ShowError("Solicitud de Autorización de Plan de Pago", "Esta página sólo puede ser accedida desde el correo electrónico que le ha sido enviado.");
	exit;
}

if ((isset($_REQUEST["USUVAL"])) and ($_REQUEST["USUVAL"] == "F")) {
	$params = array(":usuario" => $_REQUEST["USERNAME"]);
	$sql =
		"SELECT se_nombre
			 FROM use_usuarios
			WHERE se_usuario = :usuario";
	ShowError("Solicitud de Autorización de Plan de Pago", "Esta página sólo puede ser accedida por ".ValorSql($sql, "", $params).".");
	exit;
}

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT TO_CHAR(NVL(pp_fechaaprobado, pp_fechabaja), 'DD/MM/YYYY') fecha, DECODE(pp_fechaaprobado, NULL, 'Rechazó', 'Aprobó') permite, se_nombre
		 FROM web.wtw_transaccionweb, zap_autorizacionplan, zpp_planpago, use_usuarios
		WHERE tw_id = ap_idtransaccionweb
			AND pp_id = ap_idplan
			AND NVL(pp_usuaprobado, pp_usubaja) = se_usuario
			AND tw_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["FECHA"] != "") {
	ShowError("Solicitud de Autorización de Plan de Pago", "Esta operación ya fue realizada el día ".$row["FECHA"]." por el usuario ".$row["SE_NOMBRE"].", el que ".$row["PERMITE"]." la solicitud.");
	exit;
}

$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT pp_contrato contrato, em_nombre nombre, ap_idplan PLAN, ap_montodeuda deuda, ap_cuotadeuda cuotadeuda,
					ap_cuotafinanc cuotafinanc, ap_anticipo anticipo, ap_quitaintfinanc quitaintfinanc,
					ap_quitaintmorafinanc + ap_quitaintmoracont quitaintmora, pp_id, NULL permite
		 FROM aco_contrato, aem_empresa, zpp_planpago, zap_autorizacionplan
		WHERE pp_id = ap_idplan
			AND pp_contrato = co_contrato
			AND em_id = co_idempresa
			AND ap_idtransaccionweb = :idtransaccion";
if (!ExisteSql($sql, $params)) {
	ShowError("Solicitud de Autorización de Plan de Pago", "No es posible realizar la acción.<br>Los datos de este plan de pago han sido modificados.");
	exit;
}
?>
<html>
	<head>
		<title>IntraWEB | Solicitud de Autorización de Plan de Pago</title>
		<script src="Solicitud.js" type="text/javascript"></script>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
<body>
<?
$sql = 
	"SELECT pp_contrato contrato, em_nombre nombre, ap_idplan PLAN, ap_montodeuda deuda, ap_cuotadeuda cuotadeuda,
       		ap_cuotafinanc cuotafinanc, ap_anticipo anticipo, ap_quitaintfinanc quitaintfinanc,
       		ap_quitaintmorafinanc + ap_quitaintmoracont quitaintmora, pp_id, NULL permite
  	 FROM aco_contrato, aem_empresa, zpp_planpago, zap_autorizacionplan
 		WHERE pp_id = ap_idplan
   		AND pp_contrato = co_contrato
   		AND em_id = co_idempresa
   		AND ap_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

BuildTable("Solicitud de Autorización de Plan de Pago", $conn, $sql,
					 array("Contrato", "Razón Social", "Plan", "Deuda", "Cuota Deuda", "Cuota Financiada", "Anticipo", "Quita Intereses Financieros", "Quita Intereses Mora", "", ""),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0),
					 "ProcesarSolicitud.php",
					 array("Aprobar", "Rechazar", "Salir"),
					 array("aprobar", "rechazar", "CloseWindow"),
					 "¿ Desea autorizar el plan de pago ?",
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>