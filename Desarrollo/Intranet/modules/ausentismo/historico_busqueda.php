<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


set_time_limit(120);
setDateFormatOracle("DD/MM/YYYY HH24:MI");

$pagina = $_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["HISTORICO_AUSENTISMO_BUSQUEDA"] = array("empleado" => $_REQUEST["empleado"],
																									 "fechaAvisoDesde" => $_REQUEST["fechaAvisoDesde"],
																									 "fechaAvisoHasta" => $_REQUEST["fechaAvisoHasta"],
																									 "ob" => $ob,
																									 "motivo" => $_REQUEST["motivo"],
																									 "pagina" => $pagina);

$params = array();
$where = "";

if ($_REQUEST["empleado"] != -1) {
	$params[":empleado"] = $_REQUEST["empleado"];
	$where.= " AND UPPER(TRIM(ha_empleado)) = UPPER(TRIM(:empleado))";
}

if ($_REQUEST["fechaAvisoDesde"] != "") {
	$params[":fechaaltadesde"] = $_REQUEST["fechaAvisoDesde"];
	$where.= " AND ha_fechaalta >= TO_DATE(:fechaaltadesde, 'dd/mm/yyyy')";
}

if ($_REQUEST["fechaAvisoHasta"] != "") {
	$params[":fechaaltahasta"] = $_REQUEST["fechaAvisoHasta"];
	$where.= " AND ha_fechaalta <= TO_DATE(:fechaaltahasta, 'dd/mm/yyyy')";
}

if ($_REQUEST["motivo"] != -1) {
	$params[":idmotivoausencia"] = $_REQUEST["motivo"];
	$where.= " AND ha_idmotivoausencia = :idmotivoausencia";
}

$sql =
	"SELECT NULL ¿ha_id?,
					ha_fechaalta  ¿fechaalta?,
					ha_fechaavisojefe ¿fechaavisojefe?,
					UPPER(SUBSTR(ha_usualta, 1, 2)) || LOWER(SUBSTR(ha_usualta, 3, 1000)) ¿usualta?,
					¿ha_empleado?,
					¿ma_detalle?,
					DECODE(NVL(aa_observacionrespuesta, ''), '', ha_observaciones, ha_observaciones || '<br /><span style=\"color:#f00\">' || aa_usuariorespuesta || ' agrega el ' || aa_fecharespuesta ||': ' || aa_observacionrespuesta || '</span>') ¿observaciones?,
					DECODE(NVL(aa_enviomedico, ''), '', CASE WHEN ha_enviarmedico = 'T' THEN 'Sí' WHEN ha_enviarmedico = 'F' THEN 'No' ELSE '' END, '<span style=\"color:#f00\" title=\"Respuesta cargada por el jefe\">' || DECODE(aa_enviomedico, 'S', 'Sí', 'No') || ' - Rta.: ' || aa_fecharespuesta || '</span>') ¿enviarmedico?,
					ha_motivonoenviomedico ¿motivonoenviomedico?,
					UPPER(SUBSTR(se_usuario, 1, 2)) || LOWER(SUBSTR(se_usuario, 3, 1000)) ¿jefe?,
					DECODE(ha_informado, 'T', 'Informado', 'No Informado') ¿informado?
		 FROM rrhh.rha_ausencias, rrhh.rma_motivosausencia, use_usuarios, web.waa_avisosausentismo
		WHERE ha_idmotivoausencia = ma_id
			AND ha_idjefe = se_id(+)
			AND ha_id = aa_idausencia(+)
			AND ha_fechaavisojefe IS NOT NULL _EXC1_";
$grilla = new Grid(15, 200);
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "GridFirstColumn"));
$grilla->addColumn(new Column("Fecha de Aviso"));
$grilla->addColumn(new Column("Fecha Acción"));
$grilla->addColumn(new Column("Reportado por"));
$grilla->addColumn(new Column("Emp. Ausente"));
$grilla->addColumn(new Column("Motivo"));
$grilla->addColumn(new Column("Observación"));
$grilla->addColumn(new Column("Médico"));
$grilla->addColumn(new Column("Justificación"));
$grilla->addColumn(new Column("Usuario"));
$grilla->addColumn(new Column("Acciones"));
$grilla->setDecodeSpecialChars(true);
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setSql($sql);
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>