<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	ShowError("Solicitud de Autorización de Bonificación", "Esta página sólo puede ser accedida desde el correo electrónico que le ha sido enviado.");
	exit;
}

if ((isset($_REQUEST["USUVAL"])) and ($_REQUEST["USUVAL"] == "F")) {
	$params = array(":usuario" => $_REQUEST["USERNAME"]);
	$sql =
		"SELECT se_nombre
			 FROM use_usuarios
			WHERE se_usuario = :usuario";

	ShowError("Solicitud de Autorización de Bonificación", "Esta página sólo puede ser accedida por ".ValorSql($sql, "", $params).".");
	exit;
}

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT TO_CHAR(NVL(eb_fechaautoriza, eb_fechabaja), 'DD/MM/YYYY') fecha, DECODE(eb_fechaautoriza, NULL, 'Rechazó', 'Aprobó') permite, se_nombre
		 FROM web.wtw_transaccionweb, emi.ieb_empresabonificacion, use_usuarios
		WHERE tw_id = eb_idtransaccionweb
			AND NVL(eb_usuautoriza, eb_usubaja) = se_usuario
			AND tw_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["FECHA"] != "") {
	ShowError("Solicitud de Autorización de Bonificación", "Esta operación ya fue realizada el día ".$row["FECHA"]." por el usuario ".$row["SE_NOMBRE"].", el que ".$row["PERMITE"]." la solicitud.");
	exit;
}

?>
<html>
	<head>
		<title>IntraWEB | Solicitud de Autorización de Bonificación</title>
		<script src="Solicitud.js" type="text/javascript"></script>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
<body>
<?
$sql = 
	"SELECT art.afiliacion.get_ultcontrato(em_cuit) contrato, em_nombre nombre, em_cuit cuit, eb_periodovigenciadesde pdesde,
				  eb_periodovigenciahasta phasta, DECODE(eb_tipobonificacion, 'F', 'Fija', 'V', 'Variable', '') tipo,
				  DECODE(eb_aplica, 'C', 'Cuota Solamente', 'A', 'Cuota y Fondo', '') aplica, eb_fijocuota fijocuota, eb_fijofondo fijofondo,
				  eb_observaciones observaciones, null permite
		 FROM aem_empresa, emi.ieb_empresabonificacion
		WHERE em_id = eb_idempresa
			AND eb_idtransaccionweb = ".$_REQUEST["TRANSACCION"];


BuildTable("Solicitud de Autorización de Plan de Pago", $conn, $sql,
					 array("Contrato", "Razón Social", "CUIT", "Periodo Desde", "Periodo Hasta", "Tipo", "Aplica a", "Importe Fijo Cuota", "Importe Fijo Fondo", "Observaciones", ""),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 0, 0),
					 "ProcesarSolicitud.php",
					 array("Aprobar", "Rechazar", "Salir"),
					 array("aprobar", "rechazar", "CloseWindow"),
					 "¿ Desea autorizar la bonificación ?",
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>