<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	ShowError("Reemplazo de cheques - Cambio de beneficiario", "Esta página sólo puede ser accedida desde el correo electrónico que le ha sido enviado.");
	exit;
}

if ((isset($_REQUEST["USUVAL"])) and ($_REQUEST["USUVAL"] == "F")) {
	$params = array(":usuario" => $_REQUEST["USERNAME"]);
	$sql =
		"SELECT se_nombre
			 FROM use_usuarios
			WHERE se_usuario = :usuario";

	ShowError("Reemplazo de cheques - Cambio de beneficiario", "Esta página sólo puede ser accedida por ".ValorSql($sql, "", $params).".");
	exit;
}

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT tw_fechaejecucion
		 FROM web.wtw_transaccionweb
		WHERE tw_id = :id";
if (ValorSql($sql, "", $params) != "") {
	ShowError("Reemplazo de cheques - Cambio de beneficiario", "Esta acción ya ha sido realizada.");
	exit;
}
?>
<html>
	<head>
		<title>IntraWEB | Reemplazo de cheques - Cambio de beneficiario</title>
		<script src="ReemplazoCheques.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script src="/js/functions.js" type="text/javascript"></script>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
<body>
<?
$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT DISTINCT 1
							FROM aco_contrato
						 WHERE co_idempresa IN (SELECT em_id
																			FROM art.sex_expedientes, art.sle_liquiempsin, rce_chequeemitido, aem_empresa
																		 WHERE ce_id = le_idchequeemitido
																			 AND ex_siniestro = le_siniestro
																			 AND ex_orden = le_orden
																			 AND ex_recaida = le_recaida
																			 AND em_cuit = ex_cuit
																			 AND le_conpago = 50
																			 AND ce_id = (SELECT DISTINCT rb_idchequeemitido
																															 FROM teso.rrb_reemplazobeneficiario
																															WHERE rb_idtransaccionweb = :idtransaccion))
							 AND art.deuda.get_deudatotalconsolidada(co_contrato) > 0";
$tieneDeudaConsolidada = ExisteSql($sql, $params);

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT art.tesoreria.get_idfinancialscheque(rb_idchequeemitido)
		 FROM teso.rrb_reemplazobeneficiario, web.wtw_transaccionweb
		WHERE rb_idtransaccionweb = tw_id
			AND tw_id = :id";
$idfinancials = ValorSql($sql, "", $params);

$sql =
	"SELECT ce_numero,
					ce_beneficiario,
					TO_CHAR(ce_fechacheque, 'DD/MM/YYYY') as fecha,
					'$ ' || ce_monto as monto,
					ce_observaciones AS observaciones,
					(SELECT MAX(ai.description)
						 FROM ap.ap_invoice_payments_all@realfcl aip, ap.ap_invoices_all@realfcl ai
						WHERE ai.invoice_id = aip.invoice_id
							AND aip.check_id = ". $idfinancials .") as descripcion,
					LPAD(' ', LENGTH(ce_beneficiario)) as nuevo_beneficiario,
					rb_id,
					NULL cancelar,
					NULL compensar
		 FROM teso.rce_chequeemitido, teso.rrb_reemplazobeneficiario, web.wtw_transaccionweb
		WHERE rb_idtransaccionweb = tw_id
			AND ce_id = rb_idchequeemitido
			AND tw_id = ".$_REQUEST["TRANSACCION"];

BuildTable("Reemplazo de cheques - Cambio de beneficiario",
					 $conn,
					 $sql,
					 array("Número", "Benficiario", "Fecha", "Monto", "Observaciones", "Información del pago", "Nuevo beneficiario", "", "", (($tieneDeudaConsolidada)?"¿ Compensar ?":"")),
					 array(0, 0, 0, 0, 0, 0, 1, 0, 1, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 0, 0, (($tieneDeudaConsolidada)?1:0)),
					 "ReemplazoCheques_Procesar.php",
					 array("Aceptar", "Cancelar", "Salir"),
					 array("aceptar", "cancelar", "CloseWindow"),
					 "",
					 array(0, 0, 0, 0, 0, 0, 1, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 false,
					 false,
					 false,
					 array('text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'text', 'checkbox'));
?>
	</body>
</html>