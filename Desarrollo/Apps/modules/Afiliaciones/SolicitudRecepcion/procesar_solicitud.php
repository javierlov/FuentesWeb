<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
	exit;
}

$title = "Solicitud de permiso para realizar un traspaso";
?>
<html>
	<head>
		<title>IntraWEB | <?= $title?> - Comprobante</title>
		<script src="/js/functions.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	</head>
<body>
<?
try {
	$params = array(":id" => $_REQUEST["TRANSACCION"], ":usuejecucion" => $_REQUEST["USERNAME"]);
	$sql =
		"UPDATE web.wtw_transaccionweb
				SET tw_fechaejecucion = SYSDATE,
						tw_usuejecucion = :usuejecucion
			WHERE tw_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":autorizado" => $_REQUEST["PERMITE"],
									":idtransaccion" => $_REQUEST["TRANSACCION"],
									":respuesta" => $_REQUEST["RESPUESTA"],
									":usuarioautorizo" => $_REQUEST["USERNAME"]);
	$sql =
		"UPDATE afi.are_autorizarecepcion
				SET re_fechaautorizacion = SYSDATE,
						re_autorizado = :autorizado,
						re_usuarioautorizacion = :usuarioautorizo,
						re_respuesta = :respuesta
		  WHERE re_idtransaccionweb = :idtransaccion";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":id" => $_REQUEST["TRANSACCION"]);
	$sql =
		"UPDATE web.wtw_transaccionweb
				SET tw_fecharespuestamail = SYSDATE
			WHERE tw_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);


	$params = array(":idtransaccionweb" => $_REQUEST["TRANSACCION"]);
	$sql =
		"SELECT re_idsolicitud
			FROM afi.are_autorizarecepcion
			WHERE re_idtransaccionweb = :idtransaccionweb";
		$idSolicitudCotizacion = ValorSql($sql, 0, $params, 0);

	if ($_REQUEST["PERMITE"] == "S") 
		$estado = "7.0";
	else
		$estado = "30";

	$params = array(":id" => $idSolicitudCotizacion,
			 ":estado" => $estado,
			 ":usuario" => $_REQUEST["USERNAME"]);
	$sql =
		"UPDATE asa_solicitudafiliacion
			SET sa_estado = :estado,
				sa_usumodif = :usuario,
				sa_fechamodif = SYSDATE
				WHERE sa_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type="text/javascript">
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}


$sql = 
	"SELECT sa_nrointerno, utiles.armar_cuit(sa_cuit), sa_nombre, TO_CHAR(re_fechasolicitud, 'DD/MM/YYYY') re_fechasolicitud,
				  NVL(use.se_nombre, 'WEB: ' || re_usuariosolicitud) re_usuariosolicitud, NVL(re_observaciones, ' ') re_observaciones, re_respuesta,
				  TO_CHAR(re_fechaautorizacion, 'DD/MM/YYYY') re_fechaautorizacion, use2.se_nombre re_usuarioautorizacion,
				  DECODE(re_autorizado ,'S', 'Autorizado', 'NO Autorizado') autorizo
		 FROM afi.are_autorizarecepcion, use_usuarios use, use_usuarios use2, asa_solicitudafiliacion
		WHERE re_usuariosolicitud = use.se_usuario(+)
			AND re_idsolicitud = sa_id
			AND re_usuarioautorizacion = use2.se_usuario
			AND re_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

BuildTable($title,
					 $conn,
					 $sql,
					 array("Nº Solicitud", "C.U.I.T.", "Razón Social", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Respuesta", "Fecha de autorización", "Usuario de autorización", "Resultado"),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
					 "procesar_solicitud.php",
					 array("Imprimir", "Salir"),
					 array("PrintWebPage", "CloseWindow"),
					 "",
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>