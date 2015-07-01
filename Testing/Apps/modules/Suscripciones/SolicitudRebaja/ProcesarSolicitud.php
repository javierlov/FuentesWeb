<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


if (!isset($_REQUEST["TRANSACCION"])) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
	exit;
}
echo $_REQUEST["USERNAME"];
exit;

$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT au_idsolicitudrevision
		 FROM art.aau_autorizarevision
		WHERE au_idtransaccionweb = :idtransaccion";
$idSolicitud = ValorSql($sql, -1, $params);

$params = array(":id" => $idSolicitud);
$sql =
	"SELECT 'Solicitud de autorización: ' || ms_detalle
		 FROM asr_solicitudreafiliacion, ams_motivosolicreafiliacion
		WHERE sr_idmotivosolicitud = ms_id
			AND sr_id = :id";
$title = ValorSql($sql, "", $params);
?>
<html>
	<head>
		<title>IntraWEB | <?= $title?> - Comprobante</title>
		<script src="/js/functions.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	</head>
<body>
<?
$params = array(":autorizado" => $_REQUEST["PERMITE"],
								":usuarioautorizo" => $_REQUEST["USERNAME"],
								":observacion" => $_REQUEST["OBS_RESP"],
								":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql = 
	"UPDATE art.aau_autorizarevision
			SET au_fechaautorizacion = SYSDATE,
					au_autorizado = :autorizado,
					au_usuarioautorizo = :usuarioautorizo,
					au_observacionrespuesta = :observacion
	  WHERE au_idtransaccionweb = :idtransaccion";
DBExecSql($conn, $sql, $params);

$params = array(":usuejecucion" => $_REQUEST["USERNAME"], ":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"UPDATE web.wtw_transaccionweb
			SET tw_fechaejecucion = SYSDATE,
					tw_usuejecucion = :usuejecucion,
					tw_fecharespuestamail = SYSDATE
	  WHERE tw_id = :id";
DBExecSql($conn, $sql, $params);

// Actualizo el estado de la solicitud..
$params = array(":estado" => (($_REQUEST["PERMITE"] == "S")?"00":"05.2"), ":id" => $idSolicitud);
$sql =
	"UPDATE asr_solicitudreafiliacion
			SET sr_estadosolicitud = :estado
		WHERE sr_id = :id";
DBExecSql($conn, $sql, $params);

$params = array(":id" => $idSolicitud);
$sql =
	"SELECT 1
		 FROM asr_solicitudreafiliacion
		WHERE sr_id = :id
			AND sr_idmotivosolicitud IN(9, 21)";
$esReafiliacion = ExisteSql($sql, $params);

// Si es una reafiliación y si el jefe autoriza y tiene deuda le pido autorización a Cobranzas o a Legales según corresponda..
if (($esReafiliacion) and ($_REQUEST["PERMITE"] == "S")) {
	$curs = null;
	$params = array(":contrato" => $_REQUEST["SR_CONTRATO"]);
	$sql = "BEGIN web.get_busca_deuda_certificado(SYSDATE, :contrato, :data); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);
	$deuda = "0".$row["DEUDATOTAL"];
	if ($deuda > 0) {
		$params = array(":id" => $idSolicitud);
		$sql =
			"SELECT sr_usualta
				 FROM asr_solicitudreafiliacion
				WHERE sr_id = :id";
		$usuSolicitud = ValorSql($sql, "", $params);

		$curs = null;
		$params = array(":idsolicitud" => $idSolicitud,
										":ususolicitud" => $usuSolicitud,
										":deuda" => formatFloat($deuda),
										":gestor" => NULL,
										":observaciones" => NULL);
		$sql = "BEGIN intraweb.do_solicitarpermisorevision(:idsolicitud, :ususolicitud, :deuda, :gestor, :observaciones); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params, false);

		$params = array(":contrato" => $_REQUEST["SR_CONTRATO"]);
		$sql =
			"SELECT 1
				 FROM aco_contrato
				WHERE co_contrato = :contrato
					AND co_idestudio IS NOT NULL";
		if (ExisteSql($sql, $params))
			$estadoNuevo = "02.6";
		else
			$estadoNuevo = "02.4";

		$params = array(":estado" => $estadoNuevo, ":id" => $idSolicitud);
		$sql =
			"UPDATE asr_solicitudreafiliacion
					SET sr_estadosolicitud = :estado
				WHERE sr_id = :id";
		DBExecSql($conn, $sql, $params);
	}
}


$sql =
	"SELECT sr_nrosolicitud, sr_contrato, utiles.armar_cuit(sr_cuit), em_nombre, ms_detalle, TO_CHAR(au_fechasolicitud, 'DD/MM/YYYY') au_fechasolicitud, use1.se_nombre au_usuariosolicitud,
					NVL(au_observacion, ' ') au_observacion, au_observacionrespuesta obs_resp, TO_CHAR(au_fechaautorizacion, 'DD/MM/YYYY') au_fechaautorizacion, use2.se_nombre au_usuarioautorizo,
					DECODE(au_autorizado, 'S', 'Autorizado', 'NO autorizado') permite
	   FROM art.aau_autorizarevision, use_usuarios use1, use_usuarios use2, asr_solicitudreafiliacion, aem_empresa, ams_motivosolicreafiliacion
  	WHERE au_usuariosolicitud = use1.se_usuario
	    AND au_usuarioautorizo = use2.se_usuario
	    AND au_idsolicitudrevision = sr_id
   	  AND sr_cuit = em_cuit
   	  AND sr_idmotivosolicitud = ms_id
  	  AND au_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

BuildTable($title, $conn, $sql,
					 array("Nº Solicitud", "Contrato", "CUIT", "Razón Social", "Motivo", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Obs. Respuesta", "Fecha de autorización", "Usuario de autorización", ""),
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