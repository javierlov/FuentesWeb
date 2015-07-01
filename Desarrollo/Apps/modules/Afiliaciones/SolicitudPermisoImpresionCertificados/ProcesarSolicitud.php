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
		<title>IntraWEB | Solicitud de Permiso para Imprimir Certificados de Cobertura sin Deuda - Comprobante</title>
		<script src="/js/functions.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/styles/mails.css" type="text/css" />
	</head>
	<body>
		<table class="Width600 GrisClaro"><tr><td>
<?
try {
	$params = array(":usuejecucion" => $_REQUEST["USERNAME"]);
	$sql =
		"UPDATE web.wtw_transaccionweb
				SET tw_fechaejecucion = SYSDATE,
						tw_usuejecucion = :usuejecucion
		  WHERE tw_id = ".$_REQUEST["TRANSACCION"];
	@DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$curs = NULL;
	$params = array(":contrato" => $_REQUEST["AD_CONTRATO"]);
	$sql = "BEGIN web.get_busca_deuda_certificado(SYSDATE, :contrato, :data); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params, true, 0);
	$row = DBGetSP($curs);
	$deuda = $row["DEUDATOTAL"];

	$params = array(":autorizado" => $_REQUEST["PERMITE"],
									":deudafinal" => formatFloat("0".str_replace(",", ".", $deuda)),
									":usuarioautorizo" => $_REQUEST["USERNAME"],
									":observacion" => $_REQUEST["OBS_COB"],
									":idtransaccion" => $_REQUEST["TRANSACCION"]);
	$sql = 
		"UPDATE art.aad_autorizacertificadodeuda
				SET ad_fechaautorizacion = SYSDATE,
						ad_fechavigencia = (SYSDATE + 15),
						ad_autorizado = :autorizado,
						ad_deudafinal = :deudafinal,
						ad_usuarioautorizo = :usuarioautorizo,
						ad_observacioncobranza = :observacion
		  WHERE ad_idtransaccionweb = :idtransaccion";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":contrato" => $_REQUEST["AD_CONTRATO"]);
	$sql =
		"UPDATE aad_autorizacertificadodeuda
				SET ad_fechavigencia = (SYSDATE + 15)
		  WHERE ad_contrato = :contrato
				AND ad_fechavigencia IS NULL";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":id" => $_REQUEST["TRANSACCION"]);
	$sql =
		"UPDATE web.wtw_transaccionweb
				SET tw_fecharespuestamail = SYSDATE
		  WHERE tw_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<p style='color:red;'>Ocurrió un error fatal y la solicitud no pudo ser procesada, por favor informe de este error a la Gerencia de Sistemas.</p>";
	echo "<p>".$e->getMessage()."</p>";
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}

$sql = 
	"SELECT ad_contrato, utiles.armar_cuit(em_cuit), em_nombre, tb_descripcion ad_tipocertificado, 
				  '$ ' || TO_CHAR(ad_deudainicial, 'FM99999999.00') ad_deudainicial, TO_CHAR(ad_fechasolicitud, 'DD/MM/YYYY') ad_fechasolicitud,
				  use1.se_nombre ad_usuariosolicitud, NVL(ad_observacion, ' ') ad_observacion, ad_observacioncobranza obs_cob,
				  TO_CHAR(ad_fechaautorizacion, 'DD/MM/YYYY') ad_fechaautorizacion, use2.se_nombre ad_usuarioautorizo,
				  DECODE(ad_autorizado, 'S', 'Autorizado', 'NO autorizado') permite
		 FROM art.aad_autorizacertificadodeuda, ctb_tablas, use_usuarios use1, use_usuarios use2, aco_contrato, aem_empresa
	  WHERE tb_clave = 'TCERT'
		  AND tb_codigo = ad_tipocertificado
		  AND ad_usuariosolicitud = use1.se_usuario
		  AND ad_usuarioautorizo = use2.se_usuario
		  AND ad_contrato = co_contrato
		  AND co_idempresa = em_id
		  AND ad_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

BuildTable("Solicitud de Permiso para Imprimir Certificados de Cobertura sin Deuda", $conn, $sql,
				 array("Contrato", "CUIT", "Razón Social", "Tipo de Certificado", "Deuda", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Obs. Cobranzas", "Fecha de autorización", "Usuario de autorización", ""),
				 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
				 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
				 "ProcesarSolicitud.php",
				 array("Imprimir", "Salir"),
				 array("PrintWebPage", "CloseWindow"),
				 "",
				 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
				 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>