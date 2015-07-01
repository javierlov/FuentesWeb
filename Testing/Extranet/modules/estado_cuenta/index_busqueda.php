<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

// Chequeo que tenga permiso para entrar a ver el estado de cuenta..
$params = array(":id" => $_SESSION["idUsuario"]);
$sql =
	"SELECT uw_estadocuenta
		 FROM auw_usuarioweb
		WHERE uw_id = :id";
if (ValorSql($sql, "", $params) != 1) {
	echo "Usted no tiene habilitada esta opción.";
	exit;
}


SetDateFormatOracle("DD/MM/YYYY");
set_time_limit(120);

$pagina = $_SESSION["BUSQUEDA_ESTADO_CUENTA"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_ESTADO_CUENTA"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["BUSQUEDA_ESTADO_CUENTA"] = array("buscar" => "S",
																						"contrato" => $_REQUEST["contrato"],
																						"cuit" => $_REQUEST["cuit"],
																						"ob" => $ob,
																						"pagina" => $pagina,
																						"razonSocial" => $_REQUEST["razonSocial"]);

$params = array();
$where = "";

$params[":identidad"] = $_SESSION["entidad"];
$where.= " AND ec_identidad = :identidad";

if ($_REQUEST["contrato"] != "") {
	$params[":contrato"] = $_REQUEST["contrato"];
	$where.= " AND co_contrato = :contrato";
}

if ($_REQUEST["cuit"] != "") {
	$params[":cuit"] = sacarGuiones($_REQUEST["cuit"]);
	$where.= " AND em_cuit = :cuit";
}

if ($_REQUEST["razonSocial"] != "") {
	$params[":razonsocial"] = "%".strtoupper($_REQUEST["razonSocial"])."%";
	$where.= " AND UPPER(em_nombre) LIKE :razonsocial";
}

$sql =
	"SELECT co_contrato ¿contrato?,
					art.utiles.armar_cuit(em_cuit) ¿cuit?,
					em_nombre ¿razon_social?,
					co_vigenciadesde ¿vig_desde?,
					co_vigenciahasta ¿vig_hasta?,
					ec_totalcapitas ¿total_capitas?,
					TO_CHAR(ec_totaldeuda, '$9,999,990.00') ¿total_deuda?,
					ec_ultproceso ¿ult_proceso?,
					co_contrato ¿archivo?,
					co_contrato ¿archivo817?,
					co_contrato ¿archivo801c?,
					CASE WHEN TO_NUMBER(ec_totaldeuda) > 0 THEN 'F' ELSE 'T' END ¿hidecol1?,
					CASE WHEN TO_NUMBER(ec_totaldeuda) > 0 THEN 'F' ELSE 'T' END ¿hidecol2?
		 FROM aem_empresa, aco_contrato, wec_estadocuentaweb
		WHERE em_id = co_idempresa
			AND co_contrato = ec_contrato _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("Contrato", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("CUIT", -1, true, false, -1, "", "", "colFecha", -1, false));
$grilla->addColumn(new Column("Razón Social"));
$grilla->addColumn(new Column("Vig. Desde", -1, true, false, -1, "", "", "colFecha", -1, false));
$grilla->addColumn(new Column("Vig. Hasta", -1, true, false, -1, "", "", "colFecha", -1, false));
$grilla->addColumn(new Column("Cant. Trab.", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("Deuda", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("Últ. Proc.", -1, true, false, -1, "", "", "colAlignRight", -1, false));
$grilla->addColumn(new Column("EC", -1, true, false, -1, "btnDescargar", "/index.php?pageid=42&rpt=ec", "GridFirstColumn", -1, false, -1, "Descargar Estado de Cuenta"));
$grilla->addColumn(new Column("F817", 0, true, false, -1, "btnDescargar", "/index.php?pageid=42&rpt=f817", "GridFirstColumn", -1, false, -1, "Descargar F817"));
$grilla->addColumn(new Column("F801C", 0, true, false, -1, "btnDescargar", "/index.php?pageid=42&rpt=f801c", "GridFirstColumn", -1, false, -1, "Descargar F801C"));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 10));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 11));
$grilla->setColsSeparator(true);
$grilla->setColsSeparatorColor("#c0c0c0");
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
$grilla->setRowsSeparatorColor("#c0c0c0");
$grilla->setShowProcessMessage(true);
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