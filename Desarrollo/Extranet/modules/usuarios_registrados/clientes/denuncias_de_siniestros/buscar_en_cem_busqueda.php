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
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));


SetDateFormatOracle("DD/MM/YYYY");

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "4";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$_SESSION["BUSQUEDA_DENUNCIA_CEM"] = array("buscar" => "S",
																					 "documento" => $_REQUEST["documento"],
																					 "fechaDenunciaDesde" => $_REQUEST["fechaDenunciaDesde"],
																					 "fechaDenunciaHasta" => $_REQUEST["fechaDenunciaHasta"],
																					 "fechaSiniestro" => $_REQUEST["fechaSiniestro"],
																					 "nombre" => $_REQUEST["nombre"],
																					 "numeroDenunciaCEM" => $_REQUEST["numeroDenunciaCEM"],
																					 "ob" => $ob,
																					 "pagina" => $pagina);

$params = array(":cuit" => $_SESSION["cuit"]);
$where = "";

if ($_REQUEST["numeroDenunciaCEM"] != "") {
	$params[":nro_cecap"] = intval($_REQUEST["numeroDenunciaCEM"]);
	$where.= " AND sa_nro_cecap = :nro_cecap";
}
if ($_REQUEST["documento"] != "") {
	$params[":documento"] = $_REQUEST["documento"];
	$where.= " AND tj_documento = :documento";
}
if ($_REQUEST["nombre"] != "") {
	$params[":nombre"] = $_REQUEST["nombre"];
	$where.= " AND UPPER(tj_nombre) LIKE UPPER(:nombre || '%')";
}
if ($_REQUEST["fechaSiniestro"] != "") {
	$params[":fechaaccidente"] = $_REQUEST["fechaSiniestro"];
	$where.= " AND ex_fechaaccidente = TO_DATE(:fechaaccidente, 'DD/MM/YYYY')";
}
if ($_REQUEST["fechaDenunciaDesde"] != "") {
	$params[":fechaaltadesde"] = $_REQUEST["fechaDenunciaDesde"];
	$where.= " AND sa_fechaalta >= TO_DATE(:fechaaltadesde, 'DD/MM/YYYY')";
}
if ($_REQUEST["fechaDenunciaHasta"] != "") {
	$params[":fechaaltahasta"] = $_REQUEST["fechaDenunciaHasta"];
	$where.= " AND sa_fechaalta <= TO_DATE(:fechaaltahasta, 'DD/MM/YYYY')";
}

$sql =
	"SELECT ¿sa_id?,
					¿sa_nro_cecap?,
					¿ex_cuil?,
					¿tj_nombre?,
					¿ex_fechaaccidente?,
					¿ex_horaaccidente?
		 FROM ctj_trabajador, art.sex_expedientes, SIN.ssa_solicitudasistencia
		WHERE ex_cuil = tj_cuil
			AND ex_id = sa_idexpediente
			AND ex_cuit = :cuit
			AND sa_nro_cecap IS NOT NULL
			AND NOT EXISTS(SELECT 1
											 FROM SIN.sde_denuncia
											WHERE de_idexpediente = ex_id)
			AND NOT EXISTS (SELECT 1
												FROM art.tmp_sdew_denuncia
											 WHERE ex_siniestro = ew_siniestro
												 AND ex_orden = ew_orden
												 AND ex_recaida = ew_recaida
												 AND ew_nro_cecap IS NOT NULL) _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "btnOk", "/denuncia-siniestros/denuncia/cem", "gridFirstColumn", -1, true, -1, "", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("Nº CEM", 104, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("C.U.I.L.", -1, true, false, -1, "", "", "gridColAlignCenter", -1, false));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("Fecha Siniestro", 160, true, false, -1, "", "", "gridColAlignCenter", -1, false));
$grilla->addColumn(new Column("Hora Siniestro", 152, true, false, -1, "", "", "gridColAlignCenter", -1, false));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>