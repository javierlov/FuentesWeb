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

$title = "Solicitud para verificar deuda de una reafiliación";
?>
<html>
	<head>
		<title>IntraWEB | <?= $title?> - Comprobante</title>
		<script src="/js/functions.js" type="text/javascript"></script>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	</head>
<body>
<?
$params = array(":usuejecucion" => $_REQUEST["USERNAME"], ":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"UPDATE web.wtw_transaccionweb
			SET tw_fechaejecucion = SYSDATE,
					tw_usuejecucion = :usuejecucion
	  WHERE tw_id = :id";
DBExecSql($conn, $sql, $params);

$curs = null;
$params = array(":contrato" => $_REQUEST["SR_CONTRATO"]);
$sql = "BEGIN web.get_busca_deuda_certificado(SYSDATE, :contrato, :data); END;";
$stmt = DBExecSP($conn, $curs, $sql, $params);
$row = DBGetSP($curs);
$deuda = "0".$row["DEUDATOTAL"];

$params = array(":autorizado" => $_REQUEST["PERMITE"],
								":deudafinal" => formatFloat(str_replace(",", ".", $deuda)),
								":usuarioautorizo" => $_REQUEST["USERNAME"],
								":observacion" => $_REQUEST["OBS_COB"],
								":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql = 
	"UPDATE art.ard_autorizarevisioncondeuda
			SET rd_fechaautorizacion = SYSDATE,
					rd_autorizado = :autorizado,
					rd_deudafinal = :deudafinal,
					rd_usuarioautorizo = :usuarioautorizo,
					rd_observacioncobranza = :observacion
	  WHERE rd_idtransaccionweb = :idtransaccion";
DBExecSql($conn, $sql, $params);

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"UPDATE web.wtw_transaccionweb
			SET tw_fecharespuestamail = SYSDATE
	  WHERE tw_id = :id";
DBExecSql($conn, $sql, $params);

// Actualizo el estado de la solicitud..
$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
$sql =
	"UPDATE asr_solicitudreafiliacion
			SET sr_estadosolicitud = '00'
	  WHERE sr_id = (SELECT rd_idsolicitudrevision
										 FROM art.ard_autorizarevisioncondeuda
										WHERE rd_idtransaccionweb = :idtransaccion)";
DBExecSql($conn, $sql, $params);

if ($_REQUEST["PERMITE"] != "S") {		// Si no permite, le agrego la observación..
	$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
	$sql =
		"UPDATE asr_solicitudreafiliacion
				SET sr_observaciones = 'La cuenta tiene deuda NO REGULARIZADA. ' || sr_observaciones,
						sr_usuarioautorizacion = 'COMITE_2'
			WHERE sr_id = (SELECT rd_idsolicitudrevision
											 FROM art.ard_autorizarevisioncondeuda
											WHERE rd_idtransaccionweb = :idtransaccion)";
	DBExecSql($conn, $sql, $params);
}

$sql = 
	"SELECT sr_nrosolicitud, sr_contrato, utiles.armar_cuit(sr_cuit), em_nombre,
					'$ ' || TO_CHAR(rd_deudainicial, 'FM99999999.00') rd_deudainicial,
					(SELECT (SELECT DECODE(deuda, 0, NULL, 'Valores pendientes de acreditar por $ ' || REPLACE(TO_CHAR(deuda, 'FM9999999999.00'), '.', ','))
          					 FROM (SELECT NVL(SUM(va_importe), 0) deuda
                  					 FROM art.ctb_tablas, zva_valor
                 						WHERE va_idcontrato = (SELECT sr_contrato
                                          					 FROM asr_solicitudreafiliacion, art.ard_autorizarevisioncondeuda
                                         						WHERE sr_id = rd_idsolicitudrevision
                                           						AND rd_idtransaccionweb = ".$_REQUEST["TRANSACCION"].")
                   		AND va_fechabaja IS NULL
                   		AND tb_clave = 'ESVAL'
                   		AND tb_codigo = va_estado
                   		AND tb_especial1 = 'N'
                   		AND va_fecharechazo IS NULL))
       		|| ' - ' ||
       		(SELECT DECODE(monto, NULL, NULL, 'Valores rechazados por $ ' || REPLACE(TO_CHAR(monto, 'FM9999999999.00'), '.', ','))
             FROM (SELECT SUM(va_importe) monto
                     FROM zva_valor
                    WHERE va_estado = '03'
                      AND va_idcontrato = (SELECT sr_contrato
                                             FROM asr_solicitudreafiliacion, art.ard_autorizarevisioncondeuda
                                            WHERE sr_id = rd_idsolicitudrevision
                                              AND rd_idtransaccionweb = ".$_REQUEST["TRANSACCION"].")))
       		|| ' - ' ||
       		(SELECT DECODE(monto, 0, NULL, 'Valores pendientes de entrega por $ ' || REPLACE(TO_CHAR(monto, 'FM9999999999.00'), '.', ','))
             FROM (SELECT   NVL(SUM(pc_amortizacion + pc_interesfinanc) - art.deuda.get_valoresplan(pp_id), 0) monto
                       FROM art.ctb_tablas, zpc_plancuota, zpp_planpago
                      WHERE pc_idplanpago = pp_id
                        AND pp_estado = tb_codigo
                        AND tb_clave = 'ESPLA'
                        AND tb_especial1 = 'S'
                        AND tb_especial2 <> 'A'
                        AND pp_contrato = (SELECT sr_contrato
                                             FROM asr_solicitudreafiliacion, art.ard_autorizarevisioncondeuda
                                            WHERE sr_id = rd_idsolicitudrevision
                                              AND rd_idtransaccionweb = ".$_REQUEST["TRANSACCION"].")
                   GROUP BY pp_id))
  	 		  FROM DUAL) nota, TO_CHAR(rd_fechasolicitud, 'DD/MM/YYYY') rd_fechasolicitud,
  	 		  NVL(use1.se_nombre, 'WEB: ' || rd_usuariosolicitud) rd_usuariosolicitud, NVL(rd_observacion, ' ') rd_observacion, rd_observacioncobranza obs_cob,
					TO_CHAR(rd_fechaautorizacion, 'DD/MM/YYYY') rd_fechaautorizacion, use2.se_nombre rd_usuarioautorizo,
					DECODE(rd_autorizado, 'S', 'Autorizado', 'NO autorizado') permite
	   FROM art.ard_autorizarevisioncondeuda, use_usuarios use1, use_usuarios use2, asr_solicitudreafiliacion, aem_empresa
  	WHERE rd_usuariosolicitud = use1.se_usuario(+)
	    AND rd_usuarioautorizo = use2.se_usuario
	    AND rd_idsolicitudrevision = sr_id
   	  AND sr_cuit = em_cuit
  	  AND rd_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

BuildTable($title, $conn, $sql,
					 array("Nº Solicitud", "Contrato", "CUIT", "Razón Social", "Deuda", "Nota", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Obs. Cobranzas", "Fecha de autorización", "Usuario de autorización", ""),
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
           array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
           "ProcesarSolicitud.php",
           array("Imprimir", "Salir"),
           array("PrintWebPage", "CloseWindow"),
           "",
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>