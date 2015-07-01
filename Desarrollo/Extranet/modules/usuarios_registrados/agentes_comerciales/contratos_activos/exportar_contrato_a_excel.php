<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$_REQUEST["c"] = intval($_REQUEST["c"]);

validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion((validarContrato($_REQUEST["c"])));


$params = array(":contrato" => $_REQUEST["c"]);
$sql =
	"SELECT ac_codigo || ' - ' || ac_descripcion actividad,
					NVL(as_nombre, '-') asesoremision,
					dc_calle calle,
					(SELECT art.legales.get_cantjuiciosempresa(em_cuit, SYSDATE - 360, SYSDATE)
						 FROM DUAL) cantdemjudicinic,
					(SELECT COUNT(*)
						 FROM art.sex_expedientes
						WHERE NVL(ex_causafin, ' ') NOT IN('99', '95')
							AND ex_contrato = co_contrato
							AND ex_fechaaccidente >= SYSDATE - 360) cantidadsiniestrosdenunciados,
					(SELECT COUNT(*)
						 FROM art.sex_expedientes
						WHERE NVL(ex_causafin, ' ') IN('02')
							AND ex_contrato = co_contrato
							AND ex_recaida = 0
							AND ex_fechaaccidente >= SYSDATE - 360) cantidadsiniestrosrechazados,
					co_contrato contrato,
					NVL(dc_cpostala, dc_cpostal) codigopostal,
					em_cuit cuit,
					NVL(dc_departamento, '-') departamento,
					TO_CHAR(art.deuda.get_deudatotalconsolidada(co_contrato), '$9,999,999,990.00') deudatotal,
					webart.get_resumen_cobranza('S', 1, co_contrato) devengados,
					co_direlectronica email,
					as_mail emailasesoremision,
					use1.se_mail emailejecutivocomercial,
					gc_direlectronica emailgestor,
					it_email emailpreventor,
					NVL(aec1.ec_nombre, '-') ejecutivocomercial,
					webart.get_resumen_cobranza('S', 2, co_contrato) empleados,
					NVL((SELECT TO_CHAR(MAX(TRUNC(ae_fechahorainicio)))
								 FROM agenda.aae_agendaevento
								WHERE ae_idtipoevento = 193
									AND ae_idmotivoingreso = 5
									AND ae_contrato = co_contrato
									AND ae_idusuario = use1.se_id), '-') fechaultimavisita,
					NVL(gc_nombre, '-') gestor,
					NVL(ge_descripcion, '-') holding,
					webart.get_resumen_cobranza('S', 6, co_contrato) importereclamoafip,
					dc_localidad localidad,
					webart.get_resumen_cobranza('S', 3, co_contrato) masasalarial,
					NVL(dc_numero, 'S/N') numero,
					webart.get_resumen_cobranza('S', 4, co_contrato) pagos,
					art.utiles.armar_periodo(webart.get_resumen_cobranza('S', 5, co_contrato)) periodo,
					NVL(dc_piso, '-') piso,
					TO_CHAR(tc_porcmasa, '990.00') || '%' porcvariable,
					NVL(it_nombre, '-') preventor,
					pv_descripcion provincia,
					em_nombre razonsocial,
					(SELECT COUNT(*)
						 FROM art.sex_expedientes
						WHERE NVL(ex_causafin, ' ') NOT IN('99', '95')
							AND ex_posiblerecupero = 'S'
							AND ex_contrato = co_contrato
							AND ex_fechaaccidente >= SYSDATE - 360) recuperosiniestros,
					TO_CHAR(tc_alicuotapesos, '$9,999,999,990.00') sumafija,
					afi.get_telefonos('ATO_TELEFONOCONTRATO', co_contrato, 'L') telefonos,
					co_vigenciadesde vigenciadesde,
					co_vigenciahasta vigenciahasta,
					tc_vigenciatarifa vigenciatarifa
		 FROM aco_contrato, aem_empresa, cac_actividad, avc_vendedorcontrato, xev_entidadvendedor, xen_entidad, asu_sucursal, atc_tarifariocontrato, aec_ejecutivocuenta aec1, agc_gestorcuenta,
					adc_domiciliocontrato, cpv_provincias, age_grupoeconomico, use_usuarios use1, ias_asesoremision, pit_firmantes
		WHERE co_idempresa = em_id
			AND co_idactividad = ac_id
			AND co_contrato = vc_contrato
			AND vc_identidadvend = ev_id
			AND ev_identidad = en_id
			AND vc_idsucursal = su_id(+)
			AND co_contrato = tc_contrato
			AND co_idejecutivo = aec1.ec_id(+)
			AND co_idgestor = gc_id(+)
			AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
			AND su_fechabaja IS NULL
			AND en_fechabaja IS NULL
			AND vc_fechabaja IS NULL
			AND aec1.ec_fechabaja IS NULL
			AND co_contrato = dc_contrato
			AND dc_tipo = 'L'
			AND dc_provincia = pv_codigo(+)
			AND em_idgrupoeconomico = ge_id(+)
			AND ec_usuario = use1.se_usuario(+)
			AND co_idasesoremision = as_id(+)
			AND hys.get_preventor_referente(em_cuit, SYSDATE) = it_codigo(+)
			AND co_contrato = :contrato";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

header("Content-type: application/vnd-ms-excel; charset=iso-8859-1");
header("Content-Disposition: attachment; filename=Contrato_".$_REQUEST["c"]."_".date("d_m_Y").".xls");
header("Pragma: no-cache");
header("Expires: 0");
?>
<table border=1>
	<tr>
		<td colspan="6" style="color:#00a4e4; font-size:11pt; margin-top:8px;"><b>Datos del Contrato</b></td>
	</tr>
	<tr>
		<td>Contrato</td>
		<td align="left" colspan="5"><b><?= $row["CONTRATO"]?></b></td>
	</tr>
	<tr>
		<td>Razón Social</td>
		<td align="left" colspan="5"><b><?= $row["RAZONSOCIAL"]?></b></td>
	</tr>
	<tr>
		<td>Vigencia Desde</td>
		<td align="left"><b><?= $row["VIGENCIADESDE"]?></b></td>
		<td>Vigencia Hasta</td>
		<td align="left" colspan="3"><b><?= $row["VIGENCIAHASTA"]?></b></td>
	</tr>
	<tr>
		<td valign="top">Actividad</td>
		<td align="left" colspan="5"><b><?= $row["ACTIVIDAD"]?></b></td>
	</tr>
</table>
<br />
<table border=1>
	<tr>
		<td colspan="6" style="color:#00a4e4; font-size:11pt; margin-top:8px;"><b>Domicilio Constituido</b></td>
	</tr>
	<tr>
		<td>Calle</td>
		<td align="left" colspan="5"><b><?= $row["CALLE"]?></b></td>
	</tr>
	<tr>
		<td>Número</td>
		<td align="left"><b><?= $row["NUMERO"]?></b></td>
		<td>Piso</td>
		<td align="left"><b><?= $row["PISO"]?></b></td>
		<td>Departamento</td>
		<td align="left"><b><?= $row["DEPARTAMENTO"]?></b></td>
	</tr>
	<tr>
		<td>Localidad</td>
		<td align="left"><b><?= $row["LOCALIDAD"]?></b></td>
		<td>Código Postal</td>
		<td align="left" colspan="3"><b><?= $row["CODIGOPOSTAL"]?></b></td>
	</tr>
	<tr>
		<td>Provincia</td>
		<td align="left" colspan="5"><b><?= $row["PROVINCIA"]?></b></td>
	</tr>
	<tr>
		<td>e-Mail Corporativo</td>
		<td align="left" colspan="5"><b><?= $row["EMAIL"]?></b></td>
	</tr>
	<tr>
		<td>Teléfonos</td>
		<td align="left" colspan="5"><b><?= $row["TELEFONOS"]?></b></td>
	</tr>
</table>
<br />
<table border=1>
	<tr>
		<td colspan="6" style="color:#00a4e4; font-size:11pt; margin-top:8px;"><b>Datos de la Cuenta</b></td>
	</tr>
	<tr>
		<td>Período Mes/Año</td>
		<td align="left" colspan="5"><b><?= $row["PERIODO"]?></b></td>
	</tr>
	<tr>
		<td>Cant. Trabajadores</td>
		<td align="left" colspan="5"><b><?= $row["EMPLEADOS"]?></b></td>
	</tr>
	<tr>
		<td>Masa Salarial</td>
		<td align="left" colspan="5"><b><?= $row["MASASALARIAL"]?></b></td>
	</tr>
	<tr>
		<td>Vigencia Tarifa</td>
		<td align="left" colspan="5"><b><?= $row["VIGENCIATARIFA"]?></b></td>
	</tr>
	<tr>
		<td>Suma Fija</td>
		<td align="left" colspan="5"><b><?= $row["SUMAFIJA"]?></b></td>
	</tr>
	<tr>
		<td>Porc. Variable</td>
		<td align="left" colspan="5"><b><?= $row["PORCVARIABLE"]?></b></td>
	</tr>
	<tr>
		<td>Devengado Mes</td>
		<td align="left" colspan="5"><b><?= $row["DEVENGADOS"]?></b></td>
	</tr>
	<tr>
		<td>Pagado Mes</td>
		<td align="left" colspan="5"><b><?= $row["PAGOS"]?></b></td>
	</tr>
	<tr>
		<td>Deuda Total</td>
		<td align="left" colspan="5"><b><?= $row["DEUDATOTAL"]?></b></td>
	</tr>
	<tr>
		<td>Holding</td>
		<td align="left" colspan="5"><b><?= $row["HOLDING"]?></b></td>
	</tr>
</table>
<br />
<table border=1>
	<tr>
		<td colspan="6" style="color:#00a4e4; font-size:11pt; margin-top:8px;"><b>Documentación Faltante</b></td>
	</tr>
	<tr>
		<td colspan="6">En construcción.</td>
	</tr>
</table>
<br />
<table border=1>
	<tr>
		<td colspan="6" style="color:#00a4e4; font-size:11pt; margin-top:8px;"><b>Datos del Intermediario y Ejecutivos</b></td>
	</tr>
	<tr>
		<td>Ejecutivo Comercial de la Empresa</td>
		<td align="left" colspan="5"><b><?= $row["EJECUTIVOCOMERCIAL"]?></b><?= ($row["EMAILEJECUTIVOCOMERCIAL"]!="")?" (<a href=\"mailto:".$row["EMAILEJECUTIVOCOMERCIAL"]."\">".$row["EMAILEJECUTIVOCOMERCIAL"]."</a>)":""?></td>
	</tr>
	<tr>
		<td>Fecha última visita Ejecutivo Comercial</td>
		<td align="left" colspan="5"><b><?= $row["FECHAULTIMAVISITA"]?></b></td>
	</tr>
	<tr>
		<td>Gestor de Cobranzas</td>
		<td align="left" colspan="5"><b><?= $row["GESTOR"]?></b><?= ($row["EMAILGESTOR"]!="")?" (<a href=\"mailto:".$row["EMAILGESTOR"]."\">".$row["EMAILGESTOR"]."</a>)":""?></td>
	</tr>
	<tr>
		<td>Preventor</td>
		<td align="left" colspan="5"><b><?= $row["PREVENTOR"]?></b><?= ($row["EMAILPREVENTOR"]!="")?" (<a href=\"mailto:".$row["EMAILPREVENTOR"]."\">".$row["EMAILPREVENTOR"]."</a>)":""?></td>
	</tr>
	<tr>
		<td>Asesor de Emisión</td>
		<td align="left" colspan="5"><b><?= $row["ASESOREMISION"]?></b><?= ($row["EMAILASESOREMISION"]!="")?" (<a href=\"mailto:".$row["EMAILASESOREMISION"]."\">".$row["EMAILASESOREMISION"]."</a>)":""?></td>
	</tr>
</table>
<br />
<table border=1>
	<tr>
		<td colspan="6" style="color:#00a4e4; font-size:11pt; margin-top:8px;"><b>Datos Siniestrales (Último Año)</b></td>
	</tr>
	<tr>
		<td>Cantidad de Siniestros Denunciados</td>
		<td align="left" colspan="5"><b><?= $row["CANTIDADSINIESTROSDENUNCIADOS"]?></b></td>
	</tr>
	<tr>
		<td>Cantidad de Siniestros Rechazados</td>
		<td align="left" colspan="5"><b><?= $row["CANTIDADSINIESTROSRECHAZADOS"]?></b></td>
	</tr>
	<tr>
		<td>Cantidad de Demandas Judiciales Iniciadas</td>
		<td align="left" colspan="5" valign="top"><b><?= $row["CANTDEMJUDICINIC"]?></b></td>
	</tr>
</table>