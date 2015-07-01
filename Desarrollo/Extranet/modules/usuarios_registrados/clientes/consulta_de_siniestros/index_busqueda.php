<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 64));

set_time_limit(240);

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "1";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$sql =
	"SELECT 1
		 FROM comunes.cev_empresavip JOIN aco_contrato ON co_idempresa = ev_idempresa
		WHERE SYSDATE BETWEEN ev_vigenciadesde AND NVL(ev_vigenciahasta, TO_DATE('31/12/2999', 'dd/mm/yyyy'))
			AND co_contrato = :contrato";
$esEmpresaVip = existeSql($sql, array(":contrato" => $_SESSION["contrato"]));


$params = array(":contrato" => $_SESSION["contrato"]);
$where = "";

if ($_REQUEST["fechaDesde"] != "") {
	$params[":fechadesde"] = $_REQUEST["fechaDesde"];
	if ($_REQUEST["fecha"] == "FA")
		$where.= " AND NVL(ex_fecharecaida, ex_fechaaccidente) >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
	else
		$where.= " AND pv_fechacontrol >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
}

if ($_REQUEST["fechaHasta"] != "") {
	$params[":fechahasta"] = $_REQUEST["fechaHasta"];
	if ($_REQUEST["fecha"] == "FA")
		$where.= " AND ex_fechaaccidente < TO_DATE(:fechahasta, 'dd/mm/yyyy')";
	else
		$where.= " AND pv_fechacontrol < TO_DATE(:fechahasta, 'dd/mm/yyyy')";
}

if ($_REQUEST["cuil"] != "") {
	$params[":cuil"] = str_replace("-", "", $_REQUEST["cuil"]);
	$where.= " AND tj_cuil = :cuil";
}

if ($_REQUEST["nombre"] != "") {
	$params[":nombre"] = $_REQUEST["nombre"]."%";
	$where.= " AND UPPER(tj_nombre) LIKE UPPER(:nombre)";
}


$sql =
	"SELECT ex_siniestro || '/' || ex_orden || '/' || ex_recaida ¿siniestro?,
					tj_nombre ¿\"apellido y nombre\"?,
					tj_cuil ¿\"c.u.i.l.\"?,
					TO_CHAR(ex_fechaaccidente, 'dd/mm/yyyy') ¿\"fecha ocurrencia\"?,
					TO_CHAR(ex_fecharecaida, 'dd/mm/yyyy') ¿\"fecha recaída\"?,
					DECODE(ex_tipo, '1', 'Lugar de Trabajo', '2', 'In Itinere', '3', 'Enfermedad Profesional', 'Lugar de Trabajo') ¿\"tipo contingencia\"?,
					TO_CHAR(pv_fechacontrol, 'dd/mm/yyyy') ¿\"fecha último control\"?,
					TO_CHAR(art.web.get_fechaproximocontrol(ex_altamedica, pv_fechacontrol, pv_proximocontrol, pv_turno), 'dd/mm/yyyy') ¿\"fecha próxima consulta\"?,
					NVL(tratamiento.tb_descripcion, 'Ambulatorio') ¿tratamiento?,
					DECODE(ex_altamedica, NULL, 'NO', 'SI') ¿alta?,
					ca_descripcion ¿prestador?,
					TO_CHAR(ex_bajamedica, 'dd/mm/yyyy') ¿\"baja médica\"?,
					TO_CHAR(ex_altamedica, 'dd/mm/yyyy') ¿\"alta médica\"?,
					art.SIN.get_cantdiascaidos(ex_siniestro, ex_orden, ex_recaida) ¿días?,
					dg_descripcion ¿diagnóstico?,
					ex_brevedescripcion ¿descripción?,
					DECODE(ex_causafin, '02', 'RECHAZO', 'ACTIVO') ¿estado?,
					la_descripcion ¿agente?,
					ln_descripcion ¿naturaleza?,
					lf_descripcion ¿forma?,
					lz_descripcion ¿zona?,
					art.siniestro.get_motivorechazo(ex_siniestro, ex_orden, ex_recaida) ¿\"motivo rechazo\"?,
					SUBSTR(art.inca.get_descripcioninca(ex_siniestro, ex_orden, 'M'), 15, 7) ¿\"porc. incapacidad\"?,
					¿ex_fecharecepcion?,
					¿ex_observaciones?,
					art.utiles.armar_domicilio(ud_calle, ud_numero, ud_piso, ud_departamento, ud_localidad) ¿domic_denuncia?,
					DECODE(ex_gravedad, '1', 'LEVE', '2', 'MODERADO SIN INTERNACION', '3', 'MODERADO CON INTERNACION', '4', 'GRAVE', '5', 'MORTAL', 'LEVE') ¿gravedad?
		 FROM slf_lesionforma, slz_lesionzona, sln_lesionnaturaleza, sla_lesionagente, art.cpr_prestador, art.spv_parteevolutivo, ctj_trabajador, aem_empresa, art.sex_expedientes,
					art.cdg_diagnostico, art.ctb_tablas tratamiento, SIN.sud_ubicaciondenuncia
		WHERE ex_cuil = tj_cuil
			AND la_id(+) = ex_idagente
			AND ln_id(+) = ex_idnaturaleza
			AND lf_id(+) = ex_idforma
			AND lz_id(+) = ex_idzona
			AND NVL(ex_causafin, '0') NOT IN('99', '95')
			AND ex_siniestro = pv_siniestro(+)
			AND ex_orden = pv_orden(+)
			AND ex_recaida = pv_recaida(+)
			AND ex_diagnosticooms = dg_codigo(+)
			AND tratamiento.tb_codigo(+) = pv_tipotratamiento
			AND tratamiento.tb_clave(+) = 'TRATA'
			AND ex_id = ud_idexpediente(+)
			AND DECODE(ex_cuit, '34999032089', DECODE(ex_prestador, 18296, 9753, ex_prestador),ex_prestador) = ca_identificador(+)
			AND pv_nroparte(+) = art.amebpba.get_maxcontrol(ex_siniestro, ex_orden, ex_recaida) 
			AND em_cuit = ex_cuit
			AND ex_contrato = :contrato _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("Siniestro"));
$grilla->addColumn(new Column("Apellido y Nombre"));
$grilla->addColumn(new Column("C.U.I.L."));
$grilla->addColumn(new Column("Fecha Ocurrencia"));
$grilla->addColumn(new Column("Fecha Recaída"));
$grilla->addColumn(new Column("Tipo Contingencia"));
$grilla->addColumn(new Column("Fecha Últ. Control"));
$grilla->addColumn(new Column("Fecha Próx. Consulta"));
$grilla->addColumn(new Column("Tratamiento"));
$grilla->addColumn(new Column("Alta"));
$grilla->addColumn(new Column("Prestador"));
$grilla->addColumn(new Column("Baja Médica"));
$grilla->addColumn(new Column("Alta Médica"));
$grilla->addColumn(new Column("Días"));
$grilla->addColumn(new Column("Diagnóstico"));
$grilla->addColumn(new Column("Descripción"));
$grilla->addColumn(new Column("Estado"));
$grilla->addColumn(new Column("Agente"));
$grilla->addColumn(new Column("Naturaleza"));
$grilla->addColumn(new Column("Forma"));
$grilla->addColumn(new Column("Zona"));
$grilla->addColumn(new Column("Motivo Rechazo"));
$grilla->addColumn(new Column("% Incapacidad"));
$grilla->addColumn(new Column("Fecha Recepción"));
$grilla->addColumn(new Column("Observaciones"));
$grilla->addColumn(new Column("Domicilio Denuncia"));
$grilla->addColumn(new Column("Gravedad"));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();

$_SESSION["sqlConsultaSiniestros"] = $grilla->getSqlFinal(true);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('btnExportar').style.display = 'inline';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
<?
if ($grilla->recordCount() > 0) {
?>
	getElementById('divObservacion').style.display = 'block';
<?
}
?>
	}
</script>