<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


$title = "Solicitud de permiso para realizar una recotización";
if (!isset($_REQUEST["TRANSACCION"])) {
	ShowError($title, "Esta página sólo puede ser accedida desde el correo electrónico que le ha sido enviado.");
	exit;
}

if ((isset($_REQUEST["USUVAL"])) and ($_REQUEST["USUVAL"] == "F")) {
	$params = array(":usuario" => $_REQUEST["USERNAME"]);
	$sql =
		"SELECT se_nombre
			 FROM use_usuarios
			WHERE se_usuario = :usuario";

	ShowError($title, "Esta página sólo puede ser accedida por ".ValorSql($sql, "", $params).".");
	exit;
}

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT TO_CHAR(ae_fechaautorizacion, 'DD/MM/YYYY') fecha, DECODE(ae_autorizado, 'S', 'SI', 'NO') permite, se_nombre
		 FROM web.wtw_transaccionweb, afi.aae_autorizarrecotizacion, use_usuarios
		WHERE tw_id = ae_idtransaccionweb
			AND ae_usuarioautorizacion = se_usuario
			AND tw_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["FECHA"] != "") {
	ShowError($title, "Esta operación ya fue realizada el día ".$row["FECHA"]." por el usuario ".$row["SE_NOMBRE"].", el que ".$row["PERMITE"]." autorizó.");
	exit;
}
?>
<html>
	<head>
		<title>IntraWEB | <?= $title?></title>
		<script src="Solicitud.js" type="text/javascript"></script>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
<?
$sql = 
	"SELECT sc_nrosolicitud, utiles.armar_cuit(sc_cuit), sc_razonsocial, TO_CHAR(ae_fechasolicitud, 'DD/MM/YYYY') ae_fechasolicitud,
				  NVL(se_nombre, 'WEB: ' || ae_usuariosolicitud) ae_usuariosolicitud, NVL(ae_observaciones, ' ') ae_observaciones,
				  '                                                            ' respuesta, NULL permite
		 FROM afi.aae_autorizarrecotizacion, use_usuarios, asc_solicitudcotizacion
		WHERE ae_usuariosolicitud = se_usuario(+)
			AND ae_idsolicitudcotizacion = sc_id
			AND ae_idtransaccionweb = ".$_REQUEST["TRANSACCION"];
BuildTable($title,
					 $conn,
					 $sql,
					 array("Nº Solicitud", "C.U.I.T.", "Razón Social", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Respuesta", ""),
					 array(0, 0, 0, 0, 0, 0, 1, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 0),
					 "procesar_solicitud.php",
					 array("Sí", "No"),
					 array("si", "no"),
					 "¿ Regularizó la deuda ?",
					 array(0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>