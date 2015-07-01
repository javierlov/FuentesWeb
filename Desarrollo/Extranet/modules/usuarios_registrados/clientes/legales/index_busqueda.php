<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 33));


set_time_limit(120);

SetDateFormatOracle("DD/MM/YYYY");

$cuil = "";
if (isset($_REQUEST["cuil"]))
	$cuil = $_REQUEST["cuil"];

$estadoJuicio = -1;
if (isset($_REQUEST["estadoJuicio"]))
	$estadoJuicio = $_REQUEST["estadoJuicio"];

$fechaNotificacion = "";
if (isset($_REQUEST["fechaNotificacion"]))
	$fechaNotificacion = $_REQUEST["fechaNotificacion"];

$nombreAccidentado = "";
if (isset($_REQUEST["nombreAccidentado"]))
	$nombreAccidentado = $_REQUEST["nombreAccidentado"];

$orden = "";
if (isset($_REQUEST["orden"]))
	$orden = $_REQUEST["orden"];

$siniestro = "";
if (isset($_REQUEST["siniestro"]))
	$siniestro = $_REQUEST["siniestro"];


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array(":contrato" => $_SESSION["contrato"]);
$where = "";

if ($cuil != "") {
	$params[":cuil"] = str_replace("-", "", $cuil);
	$where.= " AND tj_cuil = :cuil";
}

if ($nombreAccidentado != "") {
	$params[":nombre"] = "%".$nombreAccidentado."%";
	$where.= " AND UPPER(tj_nombre) LIKE UPPER(:nombre)";
}

if ($estadoJuicio != -1) {
	$params[":idestado"] = $estadoJuicio;
	$where.= " AND jt_idestado = :idestado";
}

if ($fechaNotificacion != "") {
	$params[":fechanotificacionjuicio"] = $fechaNotificacion;
	$where.= " AND jt_fechanotificacionjuicio >= TO_DATE(:fechanotificacionjuicio, 'dd/mm/yyyy')";
}

$sql =
	"SELECT DISTINCT ljt_juicioentramite.jt_numerocarpeta ¿\"número carpeta\"?,
									 ljt_juicioentramite.jt_fechanotificacionjuicio ¿\"fecha notificación juicio\"?,
									 lej_estadojuicio.ej_descripcion ¿\"estado juicio\"?,
									 sex_expedientes.ex_siniestro ¿siniestro?,
									 sex_expedientes.ex_orden ¿orden?,
									 sex_expedientes.ex_fechaaccidente ¿\"fecha accidente\"?,
									 TO_CHAR(ljt_juicioentramite.jt_importedemandado, '$9,999,999,990.00') ¿\"importe demandado\"?,
									 ljt_juicioentramite.jt_demandante ¿demandante?,
									 ljt_juicioentramite.jt_demandado ¿demandado?,
									 lju_jurisdiccion.ju_descripcion ¿\"jurisdicción\"?,
									 art.legales.get_reclamo(lrt_reclamojuicioentramite.rt_idreclamo) ¿reclamo?,
									 aco_contrato.co_contrato ¿contrato?,
									 sex_expedientes.ex_cuit ¿\"c.u.i.t.\"?,
									 aem_empresa.em_nombre ¿nombre?,
									 ctj_trabajador.tj_cuil ¿\"c.u.i.l.\"?,
									 ctj_trabajador.tj_nombre ¿\"nombre accidentado\"?,
									 ljt_juicioentramite.jt_fechafinjuicio ¿\"fecha fin juicio\"?,
									 ljt_juicioentramite.jt_federal ¿federal?,
									 ljz_juzgado.jz_descripcion ¿juzgado?,
									 lfu_fuero.fu_descripcion ¿fuero?,
									 ljt_juicioentramite.jt_nroexpediente ¿\"nro. expediente\"?,
									 ljt_juicioentramite.jt_anioexpediente ¿\"año expediente\"?,
									 lbo_abogado1.bo_nombre ¿\"abogado actor\"?,
									 lbo_abogado.bo_nombre ¿\"estudio asignado\"?,
									 ljt_juicioentramite.jt_importesentencia ¿\"importe sentencia\"?
							FROM art.sex_expedientes sex_expedientes, afi.aco_contrato aco_contrato, afi.aem_empresa aem_empresa, comunes.ctj_trabajador ctj_trabajador, legales.lbo_abogado lbo_abogado,
									 legales.lej_estadojuicio lej_estadojuicio, legales.lfu_fuero lfu_fuero, legales.lju_jurisdiccion lju_jurisdiccion, legales.ljz_juzgado ljz_juzgado,
									 legales.ljt_juicioentramite ljt_juicioentramite, legales.lod_origendemanda lod_origendemanda, legales.lrt_reclamojuicioentramite lrt_reclamojuicioentramite,
									 legales.lsj_siniestrosjuicioentramite lsj_siniestrosjuicioentramite, legales.lbo_abogado lbo_abogado1
						 WHERE ((aco_contrato.co_idempresa = aem_empresa.em_id)
							 AND (sex_expedientes.ex_cuit = aem_empresa.em_cuit)
							 AND (lej_estadojuicio.ej_id = ljt_juicioentramite.jt_idestado)
							 AND (lfu_fuero.fu_id = ljt_juicioentramite.jt_idfuero)
							 AND (ljt_juicioentramite.jt_id = lod_origendemanda.od_idjuicioentramite)
							 AND (lod_origendemanda.od_id = lsj_siniestrosjuicioentramite.sj_idorigendemanda)
							 AND (lju_jurisdiccion.ju_id(+) = ljt_juicioentramite.jt_idjurisdiccion)
							 AND (ljz_juzgado.jz_id = ljt_juicioentramite.jt_idjuzgado)
							 AND (lbo_abogado.bo_id(+) = ljt_juicioentramite.jt_idabogado)
							 AND (lsj_siniestrosjuicioentramite.sj_siniestro = sex_expedientes.ex_siniestro
							 AND lsj_siniestrosjuicioentramite.sj_orden = sex_expedientes.ex_orden
							 AND lsj_siniestrosjuicioentramite.sj_recaida = sex_expedientes.ex_recaida)
							 AND (lrt_reclamojuicioentramite.rt_idjuicioentramite(+) = ljt_juicioentramite.jt_id)
							 AND (lbo_abogado1.bo_id(+) = lod_origendemanda.od_idabogado)
							 AND (ctj_trabajador.tj_cuil = sex_expedientes.ex_cuil))
							 AND (ljt_juicioentramite.jt_estadomediacion = 'J')
							 AND (lod_origendemanda.od_fechabaja IS NULL)
							 AND (lrt_reclamojuicioentramite.rt_fechabaja IS NULL)
							 AND (lsj_siniestrosjuicioentramite.sj_fechabaja IS NULL)
							 AND (sex_expedientes.ex_contrato = :contrato) _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("JD Nº"));
$grilla->addColumn(new Column("Fecha Notificación"));
$grilla->addColumn(new Column("Estado Juicio"));
$grilla->addColumn(new Column("Siniestro"));
$grilla->addColumn(new Column("Orden"));
$grilla->addColumn(new Column("Fecha Accidente"));
$grilla->addColumn(new Column("$ Demandado"));
$grilla->addColumn(new Column("Demandante"));
$grilla->addColumn(new Column("Demandado"));
$grilla->addColumn(new Column("Jurisdicción"));
$grilla->addColumn(new Column("Reclamo Descripción"));
$grilla->addColumn(new Column("Contrato"));
$grilla->addColumn(new Column("C.U.I.T."));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("C.U.I.L. Trabajador"));
$grilla->addColumn(new Column("Nombre Accidentado"));
$grilla->addColumn(new Column("Fecha Fin Juicio"));
$grilla->addColumn(new Column("Federal"));
$grilla->addColumn(new Column("Juzgado"));
$grilla->addColumn(new Column("Fuero"));
$grilla->addColumn(new Column("Expediente"));
$grilla->addColumn(new Column("Año"));
$grilla->addColumn(new Column("Abogado Actor"));
$grilla->addColumn(new Column("Estudio Asignado"));
$grilla->addColumn(new Column("Importe Sentencia"));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setShowTotalRegistros(true);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();

$_SESSION["sqlLegales"] = $grilla->getSqlFinal(true);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
<?
if ($grilla->recordCount() > 0) {
?>
	getElementById('btnExportar').style.display = 'inline';
<?
}
?>
	}
</script>