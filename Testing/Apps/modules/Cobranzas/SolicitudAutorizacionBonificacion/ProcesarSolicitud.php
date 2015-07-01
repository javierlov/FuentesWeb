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

if ($_REQUEST["PERMITE"] == "S")
	$sql =
		"UPDATE emi.ieb_empresabonificacion
				SET eb_fechaautoriza = ACTUALDATE,
						eb_usuautoriza = :usuario
		  WHERE eb_idtransaccionweb = :idtransaccion";
if ($_REQUEST["PERMITE"] == "N")
	$sql =
		"UPDATE emi.ieb_empresabonificacion
				SET eb_fechabaja = ACTUALDATE,
						eb_usubaja = :usuario
		  WHERE eb_idtransaccionweb = :idtransaccion";
$params = array(":usuario" => $_REQUEST["USERNAME"], ":idtransaccion" => $_REQUEST["TRANSACCION"]);
DBExecSql($conn, $sql, $params);

$sql =
	"UPDATE web.wtw_transaccionweb
			SET tw_fechaejecucion = SYSDATE,
					tw_usuejecucion = :usuejecucion,
					tw_fecharespuestamail = SYSDATE
	  WHERE tw_id = :id";
$params = array(":usuejecucion" => $_REQUEST["USERNAME"], ":id" => $_REQUEST["TRANSACCION"]);
DBExecSql($conn, $sql, $params);

$sql = "BEGIN art.deuda.do_apruebabonificacionweb(:idtransaccion); END;";
$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
DBExecSql($conn, $sql, $params);

$sql =
	"SELECT art.afiliacion.get_ultcontrato(em_cuit) contrato, em_nombre nombre, em_cuit cuit, eb_periodovigenciadesde pdesde,
				  eb_periodovigenciahasta phasta, DECODE(eb_tipobonificacion, 'F', 'Fija', 'V', 'Variable', '') tipo,
				  DECODE(eb_aplica, 'C', 'Cuota Solamente', 'A', 'Cuota y Fondo', '') aplica, eb_fijocuota fijocuota, eb_fijofondo fijofondo,
				  eb_observaciones observaciones, TO_CHAR(NVL(eb_fechaautoriza, eb_fechabaja), 'DD/MM/YYYY') fechaautorizacion, se_nombre,
				  DECODE(eb_fechaautoriza, NULL, 'RECHAZADO', 'APROBADO') permite
		FROM aem_empresa, emi.ieb_empresabonificacion, use_usuarios
	 WHERE em_id = eb_idempresa
		  AND NVL(eb_usuautoriza, eb_usubaja) = se_usuario
		  AND eb_idtransaccionweb = ".$_REQUEST["TRANSACCION"];


BuildTable("Solicitud de Autorización de Bonificación", $conn, $sql,
				 array("Contrato", "Razón Social", "CUIT", "Periodo Desde", "Periodo Hasta", "Tipo", "Aplica a", "Importe Fijo Cuota", "Importe Fijo Fondo", "Observaciones", "Fecha Autorización", "Usuario Autorización", ""),
				 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
				 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
				 "ProcesarSolicitud.php",
				 array("Imprimir", "Salir"),
				 array("PrintWebPage", "CloseWindow"),
				 "",
				 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
				 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>