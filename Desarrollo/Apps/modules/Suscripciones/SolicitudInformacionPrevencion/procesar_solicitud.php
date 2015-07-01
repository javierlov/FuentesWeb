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

$title = "Solicitud de información para completar una cotización";
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
	$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
	$sql =
		"SELECT si_idsolicitudcotizacion
			 FROM asi_solicitudinfoprevencion
			WHERE si_idtransaccionweb = :idtransaccion";
	$idSolicitud = ValorSql($sql, "", $params, 0);

	$params = array(":idtransaccion" => $_REQUEST["TRANSACCION"]);
	$sql =
		"SELECT sc_idcotizacion
			 FROM asc_solicitudcotizacion, asi_solicitudinfoprevencion
			WHERE sc_id = si_idsolicitudcotizacion
				AND si_idtransaccionweb = :idtransaccion";
	$idCotizacion = ValorSql($sql, 0, $params, 0);


	$trabajadoresExpuestos1 = floatval($_REQUEST["TRABAJADORES"]) * floatval($_REQUEST["PORCENTAJEEXPUESTOS1"]) / 100;
	$trabajadoresExpuestos2 = floatval($_REQUEST["TRABAJADORES"]) * floatval($_REQUEST["PORCENTAJEEXPUESTOS2"]) / 100;
	$trabajadoresExpuestos3 = floatval($_REQUEST["TRABAJADORES"]) * floatval($_REQUEST["PORCENTAJEEXPUESTOS3"]) / 100;
	$trabajadoresExpuestos4 = floatval($_REQUEST["TRABAJADORES"]) * floatval($_REQUEST["PORCENTAJEEXPUESTOS4"]) / 100;


	$costoTotalPeriodicos = floatval($_REQUEST["COSTOPERIODICOS1"]) + floatval($_REQUEST["COSTOPERIODICOS2"]) + 
													floatval($_REQUEST["COSTOPERIODICOS3"]) + floatval($_REQUEST["COSTOPERIODICOS4"]);
	$totalVisitas = floatval($_REQUEST["COSTOPROMEDIOVISITA"]) * floatval($_REQUEST["CANTIDADVISITASTOTALES"]);
	$costoTotalPrevencion = $costoTotalPeriodicos + $totalVisitas + floatval($_REQUEST["OTRASEROGACIONES"]);

	$params = array(":cantidadvisitastotales" => formatFloat(floatval($_REQUEST["CANTIDADVISITASTOTALES"])),
									":costoexamen1" => formatFloat(floatval($_REQUEST["COSTOEXAMEN1"])),
									":costoexamen2" => formatFloat(floatval($_REQUEST["COSTOEXAMEN2"])),
									":costoexamen3" => formatFloat(floatval($_REQUEST["COSTOEXAMEN3"])),
									":costoexamen4" => formatFloat(floatval($_REQUEST["COSTOEXAMEN4"])),
									":costoperiodicos1" => formatFloat(floatval($_REQUEST["COSTOPERIODICOS1"])),
									":costoperiodicos2" => formatFloat(floatval($_REQUEST["COSTOPERIODICOS2"])),
									":costoperiodicos3" => formatFloat(floatval($_REQUEST["COSTOPERIODICOS3"])),
									":costoperiodicos4" => formatFloat(floatval($_REQUEST["COSTOPERIODICOS4"])),
									":costopromediovisita" => formatFloat(floatval($_REQUEST["COSTOPROMEDIOVISITA"])),
									":costototalperiodicos" => formatFloat($costoTotalPeriodicos),
									":costototalprevencion" => formatFloat($costoTotalPrevencion),
									":otraserogaciones" => formatFloat(floatval($_REQUEST["OTRASEROGACIONES"])),
									":porcentajeexpuestos1" => formatFloat(floatval($_REQUEST["PORCENTAJEEXPUESTOS1"])),
									":porcentajeexpuestos2" => formatFloat(floatval($_REQUEST["PORCENTAJEEXPUESTOS2"])),
									":porcentajeexpuestos3" => formatFloat(floatval($_REQUEST["PORCENTAJEEXPUESTOS3"])),
									":porcentajeexpuestos4" => formatFloat(floatval($_REQUEST["PORCENTAJEEXPUESTOS4"])),
									":totalvisitas" => formatFloat((floatval($_REQUEST["COSTOPROMEDIOVISITA"]) * floatval($_REQUEST["CANTIDADVISITASTOTALES"]))),
									":trabajadoresexpuestos1" => formatFloat($trabajadoresExpuestos1),
									":trabajadoresexpuestos2" => formatFloat($trabajadoresExpuestos2),
									":trabajadoresexpuestos3" => formatFloat($trabajadoresExpuestos3),
									":trabajadoresexpuestos4" => formatFloat($trabajadoresExpuestos4),
									":usuario" => $_REQUEST["USERNAME"],
									":idtransaccion" => $_REQUEST["TRANSACCION"]);
	$sql = 
		"UPDATE asi_solicitudinfoprevencion
				SET si_cantidadvisitastotales = :cantidadvisitastotales,
						si_costoexamen1 = :costoexamen1,
						si_costoexamen2 = :costoexamen2,
						si_costoexamen3 = :costoexamen3,
						si_costoexamen4 = :costoexamen4,
						si_costoperiodicos1 = :costoperiodicos1,
						si_costoperiodicos2 = :costoperiodicos2,
						si_costoperiodicos3 = :costoperiodicos3,
						si_costoperiodicos4 = :costoperiodicos4,
						si_costopromediovisita = :costopromediovisita,
						si_costototalperiodicos = :costototalperiodicos,
						si_costototalprevencion = :costototalprevencion,
						si_fecharespuesta = SYSDATE,
						si_otraserogaciones = :otraserogaciones,
						si_porcentajeexpuestos1 = :porcentajeexpuestos1,
						si_porcentajeexpuestos2 = :porcentajeexpuestos2,
						si_porcentajeexpuestos3 = :porcentajeexpuestos3,
						si_porcentajeexpuestos4 = :porcentajeexpuestos4,
						si_totalvisitas = :totalvisitas,
						si_trabajadoresexpuestos1 = :trabajadoresexpuestos1,
						si_trabajadoresexpuestos2 = :trabajadoresexpuestos2,
						si_trabajadoresexpuestos3 = :trabajadoresexpuestos3,
						si_trabajadoresexpuestos4 = :trabajadoresexpuestos4,
						si_usurespuesta = :usuario
		  WHERE si_idtransaccionweb = :idtransaccion";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":usuejecucion" => $_REQUEST["USERNAME"], ":id" => $_REQUEST["TRANSACCION"]);
	$sql =
		"UPDATE web.wtw_transaccionweb
				SET tw_fechaejecucion = SYSDATE,
						tw_usuejecucion = :usuejecucion,
						tw_fecharespuestamail = SYSDATE
		  WHERE tw_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":costototalprevencion" => formatFloat($costoTotalPrevencion), ":idcotizacion" => $idCotizacion);
	$sql =
		"UPDATE acz_cotizador
				SET cz_examenesperiodicos = :costototalprevencion,
						cz_gastosprevtotalempresa = :costototalprevencion,
						cz_gastosprevcapitafija = (SELECT :costototalprevencion / co_canttrabajador / 12
																				 FROM aco_cotizacion
																				WHERE co_id = :idcotizacion)
		  WHERE cz_idcotizacion = :idcotizacion";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

/*
Comentado a pedido de SSaire por e-mail del 27.4.2010..
	$params = array(":id" => $idCotizacion);
	$sql =
		"UPDATE aco_cotizacion
				SET co_estado = co_estadoanterior
		  WHERE co_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);
*/
	$params = array(":examenesperiodicos" => formatFloat($costoTotalPrevencion), ":id" => $idSolicitud);
	$sql =
		"UPDATE asc_solicitudcotizacion
				SET /*sc_estado = '02.1',*/
						sc_examenesperiodicos = :examenesperiodicos,
						sc_fechasusphasta = SYSDATE
			WHERE sc_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	actualizarRankingBNA($idSolicitud, 0);

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}


$sql = 
	"SELECT se_nombre,
					co_nrocotizacion || '/' || co_orden cotiorden,
					sc_nrosolicitud,
					(SELECT MAX(co_contrato)
						 FROM aco_contrato, aem_empresa
						WHERE co_idempresa = em_id
							AND em_cuit = sc_cuit) contrato,
					co_cuit,
					NVL(co_razonsocial, sc_razonsocial) empresa,
					ac_codigo || ' - ' || ac_descripcion ciiu,
					cz_actividadreal,
					zg_descripcion,
					cp_localidadcap,
					art.cotizacion.armar_campo_testigo_res559(cz_id),
					dt_frecuencia,
					cz_frecuenciaesperada,
					NVL(co_establecimientos, sc_establecimientos),
					ca_descripcion, en_nombre,
					su_descripcion,
					si_observaciones observaciones,
					art.cotizacion.armar_html_establecimientos(sc_id) establecimientos,
					NVL(co_canttrabajador, sc_canttrabajador) trabajadores,
					si_porcentajeexpuestos1, si_costoexamen1, si_trabajadoresexpuestos1, si_costoperiodicos1,
					si_porcentajeexpuestos2, si_costoexamen2, si_trabajadoresexpuestos2, si_costoperiodicos2,
					si_porcentajeexpuestos3, si_costoexamen3, si_trabajadoresexpuestos3, si_costoperiodicos3,
					si_porcentajeexpuestos4, si_costoexamen4, si_trabajadoresexpuestos4, si_costoperiodicos4,
					si_costototalperiodicos,
					si_costopromediovisita,
					sc_establecimientos,
					si_cantidadvisitastotales,
					si_totalvisitas,
					si_otraserogaciones,
					si_costototalprevencion
		 FROM asi_solicitudinfoprevencion, asc_solicitudcotizacion, aco_cotizacion, acz_cotizador, cac_actividad,
					asu_sucursal, xen_entidad, aca_canal, adt_datotarifador, art.ccp_codigopostal, afi.azg_zonasgeograficas,
					use_usuarios
		WHERE si_idsolicitudcotizacion = sc_id
			AND sc_idcotizacion = co_id(+)
			AND co_id = cz_idcotizacion(+)
			AND sc_idsucursal = su_id(+)
			AND sc_identidad = en_id
			AND sc_canal = ca_id
			AND sc_idactividad = ac_id
			AND co_idactividad = dt_idactividad(+)
			AND cz_idlocalidad = cp_id(+)
			AND NVL(cz_idzonageografica, sc_idzonageografica) = zg_id
			AND sc_usuasignado = se_usuario(+)
			AND si_idtransaccionweb = ".$_REQUEST["TRANSACCION"];
BuildTable($title, $conn, $sql,
					 array("<b>Usuario Cotización</b>", "<b>Nro. Cotización</b>", "<b>Nro. Solicitud</b>", "<b>Contrato</b>",
								 "<b>CUIT</b>", "<b>Razón Social</b>", "<b>CIIU</b>", "<b>Actividad real</b>",
								 "<b>Zona geográfica</b>", "<b>Localidad</b>", "Testigo/Res 559", "Frecuencia del mercado",
								 "Frecuencia de la cotización", "Establecimientos", "Canal", "Entidad", "Sucursal", "Observaciones",
								 "Establecimientos", "<b>Trabajadores</b>","% Expuestos (agente 1)", "Costo exámen (agente 1)",
								 "Trabajadores expuestos (agente 1)", "Costo períodicos (agente 1)", "% Expuestos (agente 2)",
								 "Costo exámen (agente 2)", "Trabajadores expuestos (agente 2)", "Costo períodicos (agente 2)",
								 "% Expuestos (agente 3)", "Costo exámen (agente 3)", "Trabajadores expuestos (agente 3)",
								 "Costo períodicos (agente 3)", "% Expuestos (agente 4)", "Costo exámen (agente 4)",
								 "Trabajadores expuestos (agente 4)", "Costo períodicos (agente 4)",
								 "<b>COSTO TOTAL PERIÓDICOS</b>", "Costo Promedio por Visita", "Establecimientos",
								 "Cantidad de Visitas Totales", "<b>COSTO TOTAL VISITAS</b>", "<b>OTRAS EROGACIONES</b>",
								 "COSTO TOTAL PREVENCIÓN"),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
					 "",
					 array("Imprimir", "Salir"),
					 array("PrintWebPage", "CloseWindow"),
					 "",
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 false,
					 false,
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
	</body>
</html>