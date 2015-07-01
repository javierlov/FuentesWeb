<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/table_funcs.php");


$title = "Solicitud de información para completar una cotización";
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
	"SELECT TO_CHAR(si_fecharespuesta, 'DD/MM/YYYY') fecha, sc_estado, se_nombre
		 FROM web.wtw_transaccionweb, art.asi_solicitudinfoprevencion, asc_solicitudcotizacion, use_usuarios
		WHERE tw_id = si_idtransaccionweb
			AND si_idsolicitudcotizacion = sc_id
			AND si_usurespuesta = se_usuario(+)
			AND tw_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["SC_ESTADO"] != "02.2") {
	ShowError($title, "El estado de la cotización no permite su modificación.");
	exit;
}

if ($row["FECHA"] != "") {
	ShowError($title, "Esta operación ya fue realizada el día ".$row["FECHA"]." por el usuario ".$row["SE_NOMBRE"].".");
	exit;
}
?>
<html>
	<head>
		<title>IntraWEB | <?= $title?></title>
		<script src="solicitud.js?rnd=1" type="text/javascript"></script>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body onKeyUp="calcular()">
<?
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
					ca_descripcion,
					en_nombre,
					su_descripcion,
					si_observaciones observaciones,
					art.cotizacion.armar_html_establecimientos(sc_id) establecimientos,
					NVL(co_canttrabajador, sc_canttrabajador) trabajadores,
					NULL porcentajeexpuestos1, NULL costoexamen1, NULL trabajadoresexpuestos1, NULL costoperiodicos1,
					NULL porcentajeexpuestos2, NULL costoexamen2, NULL trabajadoresexpuestos2, NULL costoperiodicos2,
					NULL porcentajeexpuestos3, NULL costoexamen3, NULL trabajadoresexpuestos3, NULL costoperiodicos3,
					NULL porcentajeexpuestos4, NULL costoexamen4, NULL trabajadoresexpuestos4, NULL costoperiodicos4,
					NULL costototalperiodicos,
					NULL costopromediovisita,
					sc_establecimientos,
					NULL cantidadvisitastotales,
					NULL totalvisitas,
					NULL otraserogaciones,
					NULL costototalprevencion
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
BuildTable($title,
					 $conn,
					 $sql,
					 array("<b>Usuario Cotización</b>", "<b>Nro. Cotización</b>", "<b>Nro. Solicitud</b>", "<b>Contrato</b>",
								 "<b>CUIT</b>", "<b>Razón Social</b>", "<b>CIIU</b>", "<b>Actividad real</b>",
								 "<b>Zona geográfica</b>", "<b>Localidad</b>", "Testigo/Res 559", "Frecuencia del mercado",
								 "Frecuencia de la cotización", "Establecimientos", "Canal", "Entidad", "Sucursal", "Observaciones",
								 "Establecimientos", "<b>Trabajadores</b>",
								 "% Expuestos (agente 1)", "Costo exámen (agente 1)", "Trabajadores expuestos (agente 1)", "Costo períodicos (agente 1)",
								 "% Expuestos (agente 2)", "Costo exámen (agente 2)", "Trabajadores expuestos (agente 2)", "Costo períodicos (agente 2)",
								 "% Expuestos (agente 3)", "Costo exámen (agente 3)", "Trabajadores expuestos (agente 3)", "Costo períodicos (agente 3)",
								 "% Expuestos (agente 4)", "Costo exámen (agente 4)", "Trabajadores expuestos (agente 4)", "Costo períodicos (agente 4)",
								 "<b>COSTO TOTAL PERIÓDICOS</b>", "Costo Promedio por Visita", "Establecimientos",
								 "Cantidad de Visitas Totales", "<b>COSTO TOTAL VISITAS</b>", "<b>OTRAS EROGACIONES</b>",
								 "COSTO TOTAL PREVENCIÓN"),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 0, 1, 1, 0, 0, 1, 1, 0, 0, 1, 1, 0, 0, 0, 1, 0, 1, 0, 1, 0),
					 array(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
					 "procesar_solicitud.php",
					 array("Aceptar"),
					 array("aceptar"),
					 "",
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 1, 0, 0, 0, 0, 1, 0, 1, 0, 1, 0),
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
					 false,
					 array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0));
?>
		<div class="issuehdr" style="left:194px; position:absolute; top:55px;">
			<span>&nbsp;Histórico</span>
			<span><a href="historico_cuit.php?id=<?= $_REQUEST["TRANSACCION"]?>" target="historicoCuit">por CUIT</a></span>
			<span><a href="historico_ciiu.php?id=<?= $_REQUEST["TRANSACCION"]?>" target="historicoCiiu">por CIIU</a>&nbsp;</span>
		</div>
	</body>
</html>