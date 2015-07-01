<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


$title = "Solicitud de permiso para realizar un traspaso";
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
	"SELECT TO_CHAR(re_fechaautorizacion, 'DD/MM/YYYY') fecha, DECODE(re_autorizado, 'S', 'SI', 'NO') permite, se_nombre, sa_estado
		 FROM web.wtw_transaccionweb, afi.are_autorizarecepcion, use_usuarios, asa_solicitudafiliacion, art.ctb_tablas
		WHERE tw_id = re_idtransaccionweb
			AND re_usuarioautorizacion = se_usuario
			AND tb_clave = 'ESSOL'
			AND re_idsolicitud = sa_id
			AND tw_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["FECHA"] != "") {
	ShowError($title, "Esta operación ya fue realizada el día ".$row["FECHA"]." por el usuario ".$row["SE_NOMBRE"].", el que ".$row["PERMITE"]." autorizó.");
	exit;
}	
$sql =
	"SELECT sa_estado, sa_nombre, tb_descripcion
         FROM afi.are_autorizarecepcion, asa_solicitudafiliacion, ctb_tablas 
        WHERE tb_clave = 'ESSOL'
          AND tb_codigo = sa_estado  
          AND sa_id = re_idsolicitud
          AND re_idtransaccionweb = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);	
	
if ($row["SA_ESTADO"] <> "31") {
	ShowError($title, "Esta solicitud no esta en el estado correcto. El estado actual de la empresa ".$row["SA_NOMBRE"]." es (".$row["SA_ESTADO"].") ".$row["TB_DESCRIPCION"]." ");
	exit;	
}
?>
<html>
	<head>
		<title>IntraWEB | <?= $title?></title>
		<script src="Solicitud.js" type="text/javascript"></script>
		<link href="/styles/style.css" rel="stylesheet" type="text/css">
	</head>
	<body>
<?
$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql = 
	"SELECT sa_nombre
		 FROM afi.are_autorizarecepcion, use_usuarios, asa_solicitudafiliacion
		WHERE re_usuariosolicitud = se_usuario(+)
			AND re_idsolicitud = sa_id
			AND re_idtransaccionweb = :id";

$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$nombre = $row["SA_NOMBRE"];


$sql = 
	"SELECT sa_nrointerno, utiles.armar_cuit(sa_cuit), sa_nombre, UPPER(ca_descripcion), en_nombre ,TO_CHAR(re_fechasolicitud, 'DD/MM/YYYY') re_fechasolicitud,
				  NVL(se_nombre, 'WEB: ' || re_usuariosolicitud) re_usuariosolicitud, NVL(re_observaciones, ' ') re_observaciones,
				  '                                                            ' respuesta, NULL permite
		 FROM afi.are_autorizarecepcion, use_usuarios, asa_solicitudafiliacion, com.xen_entidad, xev_entidadvendedor, aca_canal
		WHERE re_usuariosolicitud = se_usuario(+)
		    AND ca_id = en_idcanal(+)
		    AND en_id = ev_identidad(+)
			AND ev_id = sa_identidadvendedor(+)
			AND re_idsolicitud = sa_id
			AND re_idtransaccionweb = ".$_REQUEST["TRANSACCION"];
BuildTable($title,
					 $conn,
					 $sql,
					 array("Nº Solicitud", "C.U.I.T.", "Razón Social", "Canal", "Entidad", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Respuesta", ""),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 1, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
					 "procesar_solicitud.php",
					 array("Sí", "No"),
					 array("si", "no"),
					 "¿Usted autoriza el inicio del trámite de Traspaso para la empresa {$nombre}, comprometiendo a quien corresponda a presentar el Formulario General de Riesgo Laboral?",
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));					 
?>
	</body>
</html>