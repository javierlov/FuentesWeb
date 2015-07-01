<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


register_shutdown_function("shutdownFunction");
function shutDownFunction() {
	global $conn;

	$timeout = false;
	if (isset($_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]))
		$timeout = (intval(time() - $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["horaInicioBusqueda"]) >= 10800);

	if ($timeout) {
?>
		<script type="text/javascript">
			window.parent.document.getElementById('divProcesando').style.display = 'none';
			alert('Se ha superado el límite del tiempo de espera. Pruebe aplicar algún filtro para hacer mas rápida la consulta.');
		</script>
<?
	}
}


validarSesion(isset($_SESSION["isAgenteComercial"]));

$pagina = $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = $_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"]["sb"];
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;

$_SESSION["BUSQUEDA_CONTRATOS_ACTIVOS"] = array("buscar" => "S",
																								"carteraMorosa" => isset($_REQUEST["carteraMorosa"])?"checked":"",
																								"contrato" => $_REQUEST["contrato"],
																								"cuit" => $_REQUEST["cuit"],
																								"fechaVigenciaDesde" => $_REQUEST["fechaVigenciaDesde"],
																								"holding" => $_REQUEST["holding"],
																								"horaInicioBusqueda" => time(),
																								"ob" => $ob,
																								"pagina" => $pagina,
																								"razonSocial" => $_REQUEST["razonSocial"],
																								"sb" => $sb,
																								"sector" => $_REQUEST["sector"],
																								"solicitudTraspaso" => isset($_REQUEST["solicitudTraspaso"])?"checked":"");

set_time_limit(10800);

$filtros = "";
$params = array(":idcanal" => $_SESSION["canal"], ":identidad" => $_SESSION["entidad"]);
$where = "";
$where2 = "";

if ($_SESSION["entidad"] != 9003) {
	if ($_SESSION["sucursal"] != "") {
		$params[":idsucursal"] = $_SESSION["sucursal"];
		$where.= " AND vc_idsucursal = :idsucursal";
	}

	if ($_SESSION["vendedor"] != "") {
		$params[":idvendedor"] = $_SESSION["vendedor"];
		$where.= " AND ev_idvendedor = :idvendedor";
	}
}

if ($_SESSION["entidad"] == 10) {		// Venta Directa..
	$params[":entidad2"] = 400;		// Banco Nación..
	$where.= ") OR (en_id = :entidad2";
}

if ($_REQUEST["contrato"] != "") {
	$filtros.= "<tr><td>- Contrato: <b>".$_REQUEST["contrato"]."</b></td></tr>";
	$params[":contrato"] = intval($_REQUEST["contrato"]);
	$where2.= " AND co_contrato = :contrato";
}

if ($_REQUEST["cuit"] != "") {
	$filtros.= "<tr><td>- C.U.I.T.: <b>".$_REQUEST["cuit"]."</b></td></tr>";
	$params[":cuit"] = sacarGuiones($_REQUEST["cuit"]);
	$where2.= " AND em_cuit = :cuit";
}

if ($_REQUEST["razonSocial"] != "") {
	$filtros.= "<tr><td>- Razón Social: <b>".$_REQUEST["razonSocial"]."</b></td></tr>";
	$params[":nombre"] = StringToUpper($_REQUEST["razonSocial"])."%";
	$where2.= " AND em_nombre LIKE :nombre";
}

if ($_REQUEST["fechaVigenciaDesde"] != "") {
	$filtros.= "<tr><td>- Fecha Vigencia Desde: <b>".$_REQUEST["fechaVigenciaDesde"]."</b></td></tr>";
	$params[":vigenciadesde"] = $_REQUEST["fechaVigenciaDesde"];
	$where2.= " AND co_vigenciadesde >= TO_DATE(:vigenciadesde, 'dd/mm/yyyy')";
}

if ($_REQUEST["sector"] == "pr") {
	$filtros.= "<tr><td>- Sector: <b>Privado</b></td></tr>";
	$where2.= " AND em_sector = 4";
}
if ($_REQUEST["sector"] == "pu") {
	$filtros.= "<tr><td>- Sector: <b>Público</b></td></tr>";
	$where2.= " AND em_sector IN (1, 2, 3, 5)";
}

if ($_REQUEST["holding"] != "") {
	$filtros.= "<tr><td>- Holding: <b>".$_REQUEST["holding"]."</b></td></tr>";
	$params[":holding"] = StringToUpper($_REQUEST["holding"])."%";
	$where2.= " AND ge_descripcion LIKE :holding";
}

if (isset($_REQUEST["carteraMorosa"])) {
	$filtros.= "<tr><td>- Cartera Morosa: <b>Sí.</b></td></tr>";
	$where2.= " AND art.deuda.get_deudatotalconsolidada(co_contrato) > 0";
}

if (isset($_REQUEST["solicitudTraspaso"])) {
	$filtros.= "<tr><td>- Con Solicitud de Traspaso (últimos 3 meses): <b>Sí.</b></td></tr>";
	$where2.= " AND EXISTS (SELECT 1
														FROM ate_traspasoegreso
													 WHERE te_codigo = '001'
														 AND te_fechabaja IS NULL
														 AND te_fecha > SYSDATE - 90
														 AND te_contrato = co_contrato)";
}

$sql =
	"SELECT /*+ RULE */ DISTINCT co_contrato ¿id?,
															 co_contrato ¿contrato?,
															 em_nombre ¿\"razón social\"?,
															 co_vigenciadesde ¿\"vigencia desde\"?,
															 co_vigenciahasta ¿\"vigencia hasta\"?,
															 ac_codigo || ' - ' || ac_descripcion ¿actividad?,
															 webart.get_resumen_cobranza('S', 5, co_contrato) ¿periodo?,
															 webart.get_resumen_cobranza('S', 2, co_contrato) ¿empleados?,
															 webart.get_resumen_cobranza('S', 3, co_contrato) ¿masa?,
															 tc_vigenciatarifa ¿\"vigencia tarifa\"?,
															 TO_CHAR(tc_alicuotapesos, '$9,999,999,990.00') ¿\"suma fija\"?,
															 TO_CHAR(tc_porcmasa, '990.00') || '%' ¿\"porcentaje variable\"?,
															 webart.get_resumen_cobranza('S', 1, co_contrato) ¿devengados?,
															 webart.get_resumen_cobranza('S', 4, co_contrato) ¿pagos?,
															 TO_CHAR(art.deuda.get_deudatotalconsolidada(co_contrato), '$9,999,999,990.00') ¿\"deuda total\"?,
															 ge_descripcion ¿holding?,
															 aec1.ec_nombre ¿\"ejecutivo comercial empresa\"?,
															 NVL((SELECT TO_CHAR(MAX(TRUNC(ae_fechahorainicio)))
																			FROM agenda.aae_agendaevento
																		 WHERE ae_idtipoevento = 193
																			 AND ae_idmotivoingreso = 5
																			 AND ae_contrato = co_contrato
																			 AND ae_idusuario = se_id), '-') ¿\"f. últ. visita ejec. comercial\"?,
															 NVL(gc_nombre, ''-'') ¿\"gestor cobranzas\"?,
															 NVL(it_nombre, '-') ¿preventor?,
															 NVL(as_nombre, '-') ¿\"asesor emisión\"?,
															 (SELECT COUNT(*)
																	FROM art.sex_expedientes
																 WHERE NVL(ex_causafin, ' ') NOT IN('99', '95')
																	 AND ex_contrato = co_contrato
																	 AND ex_fechaaccidente >= SYSDATE - 360) ¿\"# siniestros denunciados\"?,
															 (SELECT COUNT(*)
																	FROM art.sex_expedientes
																 WHERE NVL(ex_causafin, ' ') IN('02')
																	 AND ex_contrato = co_contrato
																	 AND ex_recaida = 0
																	 AND ex_fechaaccidente >= SYSDATE - 360) ¿\"# siniestros rechazados\"?,
															 (SELECT art.legales.get_cantjuiciosempresa(em_cuit, SYSDATE - 360, SYSDATE)
																	FROM DUAL) ¿\"# demandas judiciales inic.\"?
		 FROM aco_contrato, aem_empresa, avc_vendedorcontrato, xev_entidadvendedor, xen_entidad, asu_sucursal,  cac_actividad, atc_tarifariocontrato, aec_ejecutivocuenta aec1,
					agc_gestorcuenta, age_grupoeconomico, use_usuarios, pit_firmantes, ias_asesoremision
		WHERE co_idempresa = em_id
			AND co_contrato = vc_contrato
			AND vc_identidadvend = ev_id
			AND ev_identidad = en_id
			AND vc_idsucursal = su_id(+)
			AND co_idactividad = ac_id
			AND co_contrato = tc_contrato(+)
			AND co_idejecutivo = aec1.ec_id(+)
			AND co_idgestor = gc_id(+)
			AND em_idgrupoeconomico = ge_id(+)
			AND ec_usuario = se_usuario(+)
			AND hys.get_preventor_referente(em_cuit, SYSDATE) = it_codigo(+)
			AND co_idasesoremision = as_id(+)
			AND vc_fechabaja IS NULL
			AND TO_CHAR (SYSDATE, 'YYYYMM') BETWEEN vc_vigenciadesde AND NVL (vc_vigenciahasta, '299999')
			AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1 _EXC1_
			AND ((en_idcanal = :idcanal AND en_id = :identidad _EXC2_))";

// Algunas columnas se ocultan porque se quiere que aparezcan en la exportación a excel, pero no en la vista web..
$grilla = new Grid();
$grilla->addColumn(new Column("V", 0, true, false, -1, "btnEditar", "/contratos-activos/contrato", "", -1, true, -1, "Ver", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("Contrato", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Razón Social"));
$grilla->addColumn(new Column("Vig. Desde", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
$grilla->addColumn(new Column("Vig. Hasta", 0, false));
$grilla->addColumn(new Column("Actividad", 0, false));
$grilla->addColumn(new Column("Mes/Año", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
$grilla->addColumn(new Column("Cant. Trab.", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Masa Salarial", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Vigencia Tarifa", 0, false));
$grilla->addColumn(new Column("Suma Fija", 0, false));
$grilla->addColumn(new Column("Porcentaje Variable", 0, false));
$grilla->addColumn(new Column("Cuota Devengada", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Pagado", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Deuda Total", -1, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Holding", 0, false));
$grilla->addColumn(new Column("Ejecutivo Comercial Empresa", 0, false));
$grilla->addColumn(new Column("Fecha Última Visita Ejecutivo Comercial", 0, false));
$grilla->addColumn(new Column("Gestor Cobranzas", 0, false));
$grilla->addColumn(new Column("Preventor", 0, false));
$grilla->addColumn(new Column("Asesor Emisión", 0, false));
$grilla->addColumn(new Column("Cantidad Siniestros Denunciados", 0, false));
$grilla->addColumn(new Column("Cantidad Siniestros Rechazados", 0, false));
$grilla->addColumn(new Column("Cantidad Demandas Judiciales Iniciadas", 0, false));
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where2, $where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setShowProcessMessage(true);
$grilla->setSql($sql);
$grilla->Draw();

$_SESSION["contratosActivosHeader"] = "<table border=0><tr><td style='font-family:Verdana; font-size:16px;' valign=top><b>Contratos Activos<br /><span style='font-family:Verdana; font-size:12px;'>Filtros:</span></b></td></tr>".$filtros."</table>";
$_SESSION["sqlContratos"] = str_replace("CO_CONTRATO ID,", "", $grilla->getSqlFinal(true));
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('btnExportar').style.display = 'inline';
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>