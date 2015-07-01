<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	ShowError("Solicitud de Permiso para Imprimir Certificados de Cobertura sin Deuda", "Esta página sólo puede ser accedida desde el correo electrónico que le ha sido enviado.");
	exit;
}

if ((isset($_REQUEST["USUVAL"])) and ($_REQUEST["USUVAL"] == "F")) {
	$params = array(":usuario" => $_REQUEST["USERNAME"]);
	$sql =
		"SELECT se_nombre
			 FROM use_usuarios
			WHERE se_usuario = :usuario";

	ShowError("Solicitud de Permiso para Imprimir Certificados de Cobertura sin Deuda", "Esta página sólo puede ser accedida por ".ValorSql($sql, "", $params).".");
	exit;
}

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT TO_CHAR(ad_fechavigencia, 'DD/MM/YYYY') fecha, DECODE(ad_autorizado, 'S', 'SI', 'NO') permite, se_nombre
		 FROM web.wtw_transaccionweb, art.aad_autorizacertificadodeuda, use_usuarios
		WHERE tw_id = ad_idtransaccionweb
		  AND ad_usuarioautorizo = se_usuario
		  AND tw_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["FECHA"] != "") {
	ShowError("Solicitud de Permiso para Imprimir Certificados de Cobertura sin Deuda", "Esta operación ya fue realizada el día ".$row["FECHA"]." por el usuario ".$row["SE_NOMBRE"].", el que ".$row["PERMITE"]." autorizó.");
	exit;
}
?>
<html>
	<head>
		<title>IntraWEB | Solicitud de Permiso para Imprimir Certificados de Cobertura sin Deuda</title>
		<script src="Solicitud.js" type="text/javascript"></script>
		<link href="/styles/mails.css" rel="stylesheet" type="text/css" />
	</head>
<body>
<?
$sql = 
	"SELECT ad_contrato, utiles.armar_cuit(em_cuit), em_nombre, tb_descripcion ad_tipocertificado,
				  '$ ' || TO_CHAR(ad_deudainicial, 'FM99999999.00') ad_deudainicial, TO_CHAR(ad_fechasolicitud, 'DD/MM/YYYY') ad_fechasolicitud,
				  se_nombre ad_usuariosolicitud, NVL(ad_observacion, ' ') ad_observacion, '                                                            ' obs_cob,
				  NULL permite
		 FROM art.aad_autorizacertificadodeuda, ctb_tablas, use_usuarios, aco_contrato, aem_empresa
		WHERE tb_clave = 'TCERT'
		  AND tb_codigo = ad_tipocertificado
		  AND ad_usuariosolicitud = se_usuario
		  AND ad_contrato = co_contrato
		  AND co_idempresa = em_id
		  AND ad_idtransaccionweb = ".$_REQUEST["TRANSACCION"];
                     
BuildTable("Solicitud de Permiso para Imprimir Certificados de Cobertura sin Deuda", $conn, $sql,
           array("Contrato", "CUIT", "Razón Social", "Tipo de Certificado", "Deuda", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Obs. Cobranzas", ""),
           array(0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
           array(1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
           "ProcesarSolicitud.php",
           array("Sí", "No"),
           array("si", "no"),
           "¿ Permite la impresión de certificados de cobertura SIN DEUDA para este contrato ?",
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
           array(0, 0, 0, 0, 0, 0, 0, 0, 1, 0));
?>
	</body>
</html>