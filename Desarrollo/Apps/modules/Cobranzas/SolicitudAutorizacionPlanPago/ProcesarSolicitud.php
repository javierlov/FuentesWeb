<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
	exit;
}
?>
<html>
	<head>
		<title>IntraWEB | Solicitud de Autorización de Plan de Pago - Comprobante</title>
		<script src="/js/functions.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	</head>
<body>
<?
$params = array(":usuario" => $_REQUEST["USERNAME"], ":id" => $_REQUEST["PP_ID"]);
if ($_REQUEST["PERMITE"] == "S")
	$sql =
		"UPDATE zpp_planpago
				SET pp_estado = art.deuda.get_codpreacuerdoaprobado,
						pp_fechaaprobado = ACTUALDATE,
						pp_usuaprobado = :usuario
		  WHERE pp_id = :id";
if ($_REQUEST["PERMITE"] == "N")
	$sql =
		"UPDATE zpp_planpago
				SET pp_fechabaja = ACTUALDATE,
						pp_usubaja = :usuario
		  WHERE pp_id = :id";
DBExecSql($conn, $sql, $params);

$params = array(":usuejecucion" => $_REQUEST["USERNAME"], ":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"UPDATE web.wtw_transaccionweb
			SET tw_fechaejecucion = SYSDATE,
					tw_usuejecucion = :usuejecucion,
					tw_fecharespuestamail = SYSDATE
	  WHERE tw_id = :id";
DBExecSql($conn, $sql, $params);

$sql =
	"SELECT pp_contrato contrato, em_nombre nombre, ap_idplan PLAN, ap_montodeuda deuda, ap_cuotadeuda cuotadeuda,
       		ap_cuotafinanc cuotafinanc, ap_anticipo anticipo, ap_quitaintfinanc quitaintfinanc,
       		ap_quitaintmorafinanc + ap_quitaintmoracont quitaintmora,
       		TO_CHAR(NVL(pp_fechaaprobado, pp_fechabaja), 'DD/MM/YYYY') fechaautorizacion, se_nombre,
					DECODE(pp_fechaaprobado, NULL, 'RECHAZADO', 'APROBADO') permite
  	 FROM aco_contrato, aem_empresa, zpp_planpago, zap_autorizacionplan, use_usuarios
 		WHERE pp_id = ap_idplan
   		AND pp_contrato = co_contrato
   		AND em_id = co_idempresa
   		AND NVL(pp_usuaprobado, pp_usubaja) = se_usuario
   		AND ap_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

BuildTable("Solicitud de Autorización de Plan de Pago", $conn, $sql,
					 array("Contrato", "Razón Social", "Plan", "Deuda", "Cuota Deuda", "Cuota Financiada", "Anticipo", "Quita Intereses Financieros", "Quita Intereses Mora", "Fecha Autorización", "Usuario Autorización", ""),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
					 "ProcesarSolicitud.php",
					 array("Imprimir", "Salir"),
					 array("PrintWebPage", "CloseWindow"),
					 "",
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>