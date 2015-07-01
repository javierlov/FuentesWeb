<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


$title = "Solicitud para verificar deuda de una reafiliación";
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

// Valido que solo se pueda contestar el e-mail si el estado de la reafiliación está Pendiente de Autorizar por Cobranzas o Legales..
$params = array(":idtransaccionweb" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT 1
		 FROM art.ard_autorizarevisioncondeuda, asr_solicitudreafiliacion
		WHERE rd_idsolicitudrevision = sr_id
			AND sr_estadosolicitud IN('02.4', '02.6')
			AND rd_idtransaccionweb = :idtransaccionweb";
if (!ExisteSql($sql, $params)) {
	ShowError($title, "No es posible contestar la solicitud, ya que el estado de la solicitud de reafiliación cambió desde que le enviaron el e-mail.");
	exit;
}

$params = array(":id" => $_REQUEST["TRANSACCION"]);
$sql =
	"SELECT TO_CHAR(rd_fechaautorizacion, 'DD/MM/YYYY') fecha, DECODE(rd_autorizado, 'S', 'SI', 'NO') permite, se_nombre
		 FROM web.wtw_transaccionweb, art.ard_autorizarevisioncondeuda, use_usuarios
		WHERE tw_id = rd_idtransaccionweb
			AND rd_usuarioautorizo = se_usuario
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
  	 		 FROM DUAL) nota, TO_CHAR(rd_fechasolicitud, 'DD/MM/YYYY') rd_fechasolicitud, NVL(se_nombre, 'WEB: ' || rd_usuariosolicitud) rd_usuariosolicitud,
      	 NVL(rd_observacion, ' ') rd_observacion,
      	 '                                                            ' obs_cob, NULL permite
  	FROM art.ard_autorizarevisioncondeuda, use_usuarios, asr_solicitudreafiliacion, aem_empresa
	 WHERE rd_usuariosolicitud = se_usuario(+)
   	 AND rd_idsolicitudrevision = sr_id
   	 AND sr_cuit = em_cuit
  	 AND rd_idtransaccionweb = ".$_REQUEST["TRANSACCION"];

BuildTable($title, $conn, $sql,
           array("Nº Solicitud", "Contrato", "CUIT", "Razón Social", "Deuda", "Nota", "Fecha de Solicitud", "Usuario de Solicitud", "Observaciones", "Obs. Cobranzas", ""),
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1),
           array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
           "ProcesarSolicitud.php",
           array("Sí", "No"),
           array("si", "no"),
           "¿ Regularizó la deuda ?",
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
           array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>