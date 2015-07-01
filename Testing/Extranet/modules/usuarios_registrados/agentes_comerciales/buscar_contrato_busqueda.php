<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getPageId($destino) {
	switch ($destino) {
		case "cc":
			return 70;
		case "ds":
			return 61;
		case "nt":
			return 52;
		case "rc":
			return 79;
	}
}


$destino = "rc";
if (isset($_REQUEST["d"]))
	$destino = $_REQUEST["d"];


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarSesion((($destino == "rc") and ($_SESSION["entidad"] != 400)) or ($destino != "rc"));
SetDateFormatOracle("DD/MM/YYYY");

$pagina = $_SESSION["BUSQUEDA_BUSCAR_CONTRATO"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_BUSCAR_CONTRATO"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = $_SESSION["BUSQUEDA_BUSCAR_CONTRATO"]["sb"];
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;

$_SESSION["BUSQUEDA_BUSCAR_CONTRATO"] = array("buscar" => "S",
																							"contrato" => $_REQUEST["contrato"],
																							"cuit" => $_REQUEST["cuit"],
																							"ob" => $ob,
																							"pagina" => $pagina,
																							"sb" => $sb);

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

if ($_REQUEST["contrato"] != "") {
	$params[":contrato"] = intval($_REQUEST["contrato"]);
	$where2.= " AND co_contrato = :contrato";
}

if ($_REQUEST["cuit"] != "") {
	$params[":cuit"] = sacarGuiones($_REQUEST["cuit"]);
	$where2.= " AND em_cuit = :cuit";
}

$sql =
	"SELECT co_contrato ¿id?,
					¿co_contrato?,
					¿em_cuit?,
					¿em_nombre?,
					¿co_vigenciadesde?,
					¿co_vigenciahasta?,
				  NVL(co_totempleadosactual, co_totempleados) ¿co_totempleadosmayorcero?,
				  TO_CHAR(NVL(co_masatotalactual, co_masatotal), '$9,999,999,990.00') ¿masasalarial?,
				  art.utiles.armar_periodo(co_ultimoperiodocobranza) ¿periodo?
		 FROM aco_contrato, aem_empresa, avc_vendedorcontrato, xev_entidadvendedor, xen_entidad, asu_sucursal
		WHERE co_idempresa = em_id
			AND co_contrato = vc_contrato
			AND vc_identidadvend = ev_id
			AND ev_identidad = en_id
			AND vc_idsucursal = su_id(+)
			AND vc_vigenciahasta IS NULL
			AND vc_fechabaja IS NULL
			AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1 _EXC1_
			AND ((en_idcanal = :idcanal AND en_id = :identidad _EXC2_)";
if (($_SESSION["canal"] == 321) and ($destino == "rc"))		// Si es Venta Directa y entraron por el menú de RC..
	$sql.= " OR (en_idcanal = 323 AND en_id = 400))";
else
	$sql.= ")";

$grilla = new Grid();
$grilla->addColumn(new Column("V", 0, true, false, -1, "btnEditar", "/index.php?pageid=".getPageId($destino), "", -1, true, -1, "Ver"));
$grilla->addColumn(new Column("Contrato"));
$grilla->addColumn(new Column("C.U.I.T."));
$grilla->addColumn(new Column("Razón Social"));
$grilla->addColumn(new Column("Vig. Desde"));
$grilla->addColumn(new Column("Vig. Hasta"));
$grilla->addColumn(new Column("Cant. Trab."));
$grilla->addColumn(new Column("Masa Salarial"));
$grilla->addColumn(new Column("Mes/Año"));
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where2, $where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setRowsSeparator(true);
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