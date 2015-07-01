<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
	exit;
}

if ($_REQUEST["CANCELAR"] == "T")
	$title = "Cambio cancelado";
else
	$title = "Comprobante";
?>
<html>
	<head>
		<title>IntraWEB | Reemplazo de cheques - Cambio de beneficiario - <?= $title?></title>
		<script src="/js/functions.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	</head>
<body>
<?
$params = array(":id" => $_REQUEST["TRANSACCION"], ":usuejecucion" => $_REQUEST["USERNAME"]);
$sql =
	"UPDATE web.wtw_transaccionweb
			SET tw_fechaejecucion = SYSDATE,
					tw_usuejecucion = :usuejecucion
	  WHERE tw_id = :id";
DBExecSql($conn, $sql, $params);

$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql = 
	"SELECT rb_idchequeemitido
		 FROM teso.rrb_reemplazobeneficiario
		WHERE rb_idtransaccionweb = :idtransaccion";
$idCheque = ValorSql($sql, 0, $params);


if ($_REQUEST["CANCELAR"] == "T") {
	// Si está cancelado la acción de cambiar el beneficiario..
	$params = array(":idchequeemitido" => $idCheque);
	$sql = "DELETE FROM rrb_reemplazobeneficiario WHERE rb_idchequeemitido = :idchequeemitido";
	DBExecSql($conn, $sql, $params);

	$params = array(":id" => $idCheque);
	$sql =
		"UPDATE rce_chequeemitido
				SET ce_motivoreemplazo = NULL
		  WHERE ce_id = :id";
	DBExecSql($conn, $sql, $params);

	$sql = "SELECT 1 FROM DUAL";

	BuildTable("Reemplazo de cheques - Cambio de beneficiario cancelado",
						 $conn,
						 $sql,
						 array(""),
						 array(0),
						 array(0),
						 "",
						 array(0, 0, 0, 1, 0, 0),
						 "El cambio de beneficiario ha sido cancelado.",
						 array(0),
						 array(0));
}
else {		// Si está cambiando el beneficiario..
	// Guardo el primer benficiario..
	$params = array(":beneficiario" => substr($_REQUEST["NUEVO_BENEFICIARIO"], 0, 100),
									":idtransaccion" => $_REQUEST["TRANSACCION"],
									":usurespuesta" => $_REQUEST["USERNAME"]);
	$sql =
		"UPDATE teso.rrb_reemplazobeneficiario
				SET rb_fecharespuesta = SYSDATE,
						rb_usurespuesta = :usurespuesta,
						rb_beneficiario = UPPER(:beneficiario)
		  WHERE rb_idtransaccionweb = :idtransaccion";
	DBExecSql($conn, $sql, $params);

	foreach($_REQUEST as $nombre => $valor)		// En este foreach inserto los nuevos beneficiarios menos el primero..
		if (substr($nombre, 0, 19) == "NUEVO_BENEFICIARIO_") {
			$params = array(":beneficiario" => substr($valor, 0, 100),
											":idchequeemitido" => $idCheque,
											":idtransaccion" => $_REQUEST["TRANSACCION"],
											":usualta" => $_REQUEST["USERNAME"],
											":usurespuesta" => $_REQUEST["USERNAME"]);
			$sql = 
				"INSERT INTO rrb_reemplazobeneficiario (rb_id, rb_idchequeemitido, rb_fechaalta, rb_usualta, rb_fecharespuesta, rb_usurespuesta, rb_beneficiario, rb_idtransaccionweb)
																				VALUES (SEQ_RRB_ID.NEXTVAL, :idchequeemitido, ACTUALDATE, :usualta, SYSDATE, :usurespuesta, UPPER(:beneficiario), :idtransaccion)";
			DBExecSql($conn, $sql, $params);
		}

	$params = array(":id" => $_REQUEST["TRANSACCION"]);
	$sql =
		"UPDATE web.wtw_transaccionweb
				SET tw_fecharespuestamail = SYSDATE
			WHERE tw_id = :id";
	DBExecSql($conn, $sql, $params);

	if (isset($_REQUEST["COMPENSAR"])) {
		$params = array(":idcheque" => $idCheque, ":usuario" => $_REQUEST["USERNAME"]);
		$sql =
			"UPDATE art.sle_liquiempsin
					SET le_estado = 'P',
							le_aprobcobranzas = SUBSTR(:usuario, 1, 20),
							le_faprobcobranzas = SYSDATE
			  WHERE le_idchequeemitido = :idcheque
				  AND le_conpago = '50'";
		DBExecSql($conn, $sql, $params);
	}

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
					  tesoreria.get_nuevosbeneficiarios(".$idCheque.") as nuevo_beneficiario,
					  TO_CHAR(rb_fecharespuesta, 'DD/MM/YYYY') as rb_fecharespuesta,
					  rb_usurespuesta,
					  rb_id
			 FROM teso.rce_chequeemitido, teso.rrb_reemplazobeneficiario, web.wtw_transaccionweb
			WHERE rb_idtransaccionweb = tw_id
				AND ce_id = rb_idchequeemitido
				AND tw_id = ".$_REQUEST["TRANSACCION"];

	BuildTable("Reemplazo de cheques - Cambio de beneficiario",
						 $conn,
						 $sql,
						 array("Número", "Benficiario", "Fecha", "Monto", "Observaciones", "Información del pago", "Nuevo beneficiario", "Fecha de realización", "Usuario de realización", ""),
						 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
						 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
						 "ReemplazoCheques_Procesar.php",
						 array("Imprimir", "Salir"),
						 array("PrintWebPage", "CloseWindow"),
						 "",
						 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
						 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
}
?>
	</body>
</html>