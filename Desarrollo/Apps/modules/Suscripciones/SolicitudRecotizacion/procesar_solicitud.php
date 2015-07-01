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

$title = "Solicitud de permiso para realizar una recotización";
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
		"UPDATE afi.aae_autorizarrecotizacion
				SET ae_fechaautorizacion = SYSDATE,
						ae_autorizado = :autorizado,
						ae_usuarioautorizacion = :usuarioautorizo,
						ae_respuesta = :respuesta
		  WHERE ae_idtransaccionweb = :idtransaccion";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":id" => $_REQUEST["TRANSACCION"]);
	$sql =
		"UPDATE web.wtw_transaccionweb
				SET tw_fecharespuestamail = SYSDATE
			WHERE tw_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":idtransaccionweb" => $_REQUEST["TRANSACCION"]);
	$sql =
		"SELECT ae_idsolicitudcotizacion
			 FROM afi.aae_autorizarrecotizacion
			WHERE ae_idtransaccionweb = :idtransaccionweb";
	$idSolicitudCotizacion = valorSql($sql, 0, $params, 0);

	if ($_REQUEST["PERMITE"] == "S") {
		// Actualizo el estado de la solicitud..

		$params = array(":id" => $idSolicitudCotizacion);
		$sql =
			"SELECT 1
				 FROM asc_solicitudcotizacion
				WHERE sc_idcotizacion IS NOT NULL
					AND sc_id = :id";
		if (existeSql($sql, $params, 0))
			$estado = "02.1";
		else
			$estado = "00";

		$params = array(":estado" => $estado,
										":id" => $idSolicitudCotizacion,
										":idtransaccion" => $_REQUEST["TRANSACCION"],
										":usuario" => $_REQUEST["USERNAME"]);
		$sql =
			"UPDATE asc_solicitudcotizacion
					SET (sc_codmotivorevision, sc_edadpromedio, sc_establecimientos, sc_estado, sc_fecharevision, sc_idprobabilidadcierre, sc_motivorevision, sc_porcaumento, sc_porcdescuento,
							 sc_sector, sc_usuariorevision, sc_usuariosolicitud) =
			 (SELECT ae_codmotivorecotizacion, ae_edadpromedio, ae_establecimientos, :estado, SYSDATE, ae_idprobabilidadcierre, ae_observaciones, NULL, NULL,
							 ae_sector, :usuario, :usuario
				 FROM afi.aae_autorizarrecotizacion
			  WHERE ae_idtransaccionweb = :idtransaccion)
			  WHERE sc_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		actualizarRankingBNA($idSolicitudCotizacion, 0);

		$params = array(":id" => $idSolicitudCotizacion);
		$sql =
			"UPDATE asc_solicitudcotizacion
					SET sc_idzonageografica = (SELECT eu_idzonageografica
																			 FROM (SELECT eu_idzonageografica
																							 FROM afi.aeu_establecimientos
																							WHERE eu_idsolicitud = :id
																								AND eu_idzonageografica IS NOT NULL
																								AND eu_tiposolicitud = 1
																								AND eu_fechabaja IS NULL
																					 ORDER BY eu_id)
																			WHERE ROWNUM = 1)
				WHERE sc_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		actualizarRankingBNA($idSolicitudCotizacion, 0);

		$params = array(":estado" => $estado,
										":id" => $idSolicitudCotizacion,
										":idtransaccion" => $_REQUEST["TRANSACCION"],);
		$sql =
			"UPDATE aco_cotizacion
					SET co_establecimientos = (SELECT ae_establecimientos
																			 FROM afi.aae_autorizarrecotizacion
																			WHERE ae_idtransaccionweb = :idtransaccion),
							co_estado = :estado
			  WHERE co_id = (SELECT sc_idcotizacion
												 FROM asc_solicitudcotizacion
												WHERE sc_id = :id)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		actualizarRankingBNA($idSolicitudCotizacion, 0);

		$params = array(":id" => $idSolicitudCotizacion, ":idtransaccion" => $_REQUEST["TRANSACCION"],);
		$sql =
			"UPDATE acz_cotizador
					SET (cz_edadpromedio, cz_establecimientos, cz_idlocalidad, cz_idprobabilidadcierre, cz_idzonageografica, cz_sector) =
							(SELECT ae_edadpromedio, ae_establecimientos, (SELECT eu_idlocalidad
																															 FROM (SELECT eu_idlocalidad
																																			 FROM afi.aeu_establecimientos
																																			WHERE eu_idsolicitud = :id
																																				AND eu_idlocalidad IS NOT NULL
																																				AND eu_tiposolicitud = 1
																																				AND eu_fechabaja IS NULL
																																	 ORDER BY eu_id)
																															WHERE ROWNUM = 1), ae_idprobabilidadcierre,
										  (SELECT eu_idzonageografica
												 FROM (SELECT eu_idzonageografica
																 FROM afi.aeu_establecimientos
																WHERE eu_idsolicitud = :id
																	AND eu_idzonageografica IS NOT NULL
																	AND eu_tiposolicitud = 1
																	AND eu_fechabaja IS NULL
														 ORDER BY eu_id)
												WHERE ROWNUM = 1), ae_sector
								FROM afi.aae_autorizarrecotizacion
							 WHERE ae_idtransaccionweb = :idtransaccion)
			  WHERE cz_idcotizacion = (SELECT sc_idcotizacion
																	 FROM asc_solicitudcotizacion
																	WHERE sc_id = :id)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_REQUEST["PERMITE"] == "N") {
		$params = array(":id" => $idSolicitudCotizacion);
		$sql =
			"UPDATE asc_solicitudcotizacion
					SET sc_estado = '09'
			  WHERE sc_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		actualizarRankingBNA($idSolicitudCotizacion, 0);

		$params = array(":id" => $idSolicitudCotizacion);
		$sql =
			"UPDATE aco_cotizacion
					SET co_estado = '09'
			  WHERE co_id = (SELECT sc_idcotizacion
												 FROM asc_solicitudcotizacion
												WHERE sc_id = :id)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		actualizarRankingBNA($idSolicitudCotizacion, 0);
	}

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
	"SELECT sc_nrosolicitud, utiles.armar_cuit(sc_cuit), sc_razonsocial, TO_CHAR(ae_fechasolicitud, 'DD/MM/YYYY') ae_fechasolicitud,
					NVL(use.se_nombre, 'WEB: ' || ae_usuariosolicitud) ae_usuariosolicitud, NVL(ae_observaciones, ' ') ae_observaciones, ae_respuesta,
				  TO_CHAR(ae_fechaautorizacion, 'DD/MM/YYYY') ae_fechaautorizacion, use2.se_nombre ae_usuarioautorizacion,
				  DECODE(ae_autorizado ,'S', 'Autorizado', 'NO Autorizado') autorizo
		 FROM afi.aae_autorizarrecotizacion, use_usuarios use, use_usuarios use2, asc_solicitudcotizacion
		WHERE ae_usuariosolicitud = use.se_usuario(+)
			AND ae_idsolicitudcotizacion = sc_id
			AND ae_usuarioautorizacion = use2.se_usuario
			AND ae_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

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
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>