<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


$title = "Solicitud de Permiso para Cargar una Solicitud de Reafiliación";
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

$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT ms_detalle
		 FROM asr_solicitudreafiliacion, art.aau_autorizarevision, ams_motivosolicreafiliacion
		WHERE sr_id = au_idsolicitudrevision
			AND sr_idmotivosolicitud = ms_id
			AND au_idtransaccionweb = :idtransaccion";
$motivo = ValorSql($sql, "", $params);
$title = "Solicitud de autorización: ".$motivo;

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT TO_CHAR(au_fechaautorizacion, 'DD/MM/YYYY') fecha, DECODE(au_autorizado, 'S', 'SI', 'NO') permite, se_nombre
		 FROM web.wtw_transaccionweb, art.aau_autorizarevision, use_usuarios
		WHERE tw_id = au_idtransaccionweb
			AND au_usuarioautorizo = se_usuario
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
	"SELECT sr_nrosolicitud,
					sr_contrato,
					utiles.armar_cuit(sr_cuit),
					em_nombre,
					ms_detalle,
					TO_CHAR(au_fechasolicitud, 'DD/MM/YYYY') au_fechasolicitud,
					se_nombre au_usuariosolicitud,
					NVL(au_observacion, ' ') au_observacion,
      	  '                                                            ' obs_resp,
					NULL permite
		 FROM art.aau_autorizarevision, use_usuarios, asr_solicitudreafiliacion, aem_empresa, ams_motivosolicreafiliacion
		WHERE au_usuariosolicitud = se_usuario
			AND au_idsolicitudrevision = sr_id
			AND sr_cuit = em_cuit
			AND sr_idmotivosolicitud = ms_id
			AND au_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

BuildTable($title, $conn, $sql,
           array("Nº Solicitud", "Contrato", "CUIT", "Razón Social", "Motivo", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Obs. Respuesta", ""),
           array(0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
           array(1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
           "ProcesarSolicitud.php",
           array("Sí", "No"),
           array("si", "no"),
           "¿ Permite ".$motivo." para este contrato ?",
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>