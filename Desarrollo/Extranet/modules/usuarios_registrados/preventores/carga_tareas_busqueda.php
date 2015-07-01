<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."../Common/miscellaneous/date_utils.php");


//validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
//validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));


set_time_limit(120);

$pagina = $_SESSION["BUSQUEDA_CARGA_TAREAS"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_CARGA_TAREAS"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$estado = "";
if (isset($_REQUEST["estado"]))
	$estado = $_REQUEST["estado"];

$_SESSION["BUSQUEDA_CARGA_TAREAS"] = array("buscar" => "S",
										   "cuit" =>$_REQUEST["cuit"],
										   "establecimiento" => $_REQUEST["establecimiento"],
										   "nombre" => $_REQUEST["razonSocial"],
										   "contrato" => $_REQUEST["contrato"],
										   "visitaDesde" => $_REQUEST["fechaDesde"],
										   "visitaHasta" => $_REQUEST["fechaHasta"],
										   "estado" => $estado,
										   "ob" => $ob,
										   "pagina" => $pagina);

$params = array(":idprev" => $_SESSION["idUsuario"]);
$where = "";
/*
if ($_REQUEST["cuit"] != "") {
	$params[":cuit"] = str_replace("-", "", $_REQUEST["cuit"]);
	$where.= " AND em_cuit = :cuit";
}
*/
if ($_REQUEST["contrato"] != "") {
	$params[":contrato"] = $_REQUEST["contrato"];
	$where.= " AND co_contrato = :contrato";
}

if ($_REQUEST["cuit"] != "") {
	$params[":cuit"] = $_REQUEST["cuit"];
	$where.= " AND em_cuit = :cuit";
}

if ($_REQUEST["establecimiento"] != -1) {
	$params[":idestablecimiento"] = $_REQUEST["establecimiento"];
	$where.= " AND es_id = :idestablecimiento";
}

if (isFechaValida($_REQUEST["fechaDesde"], false)) 
{
	$params[":fechaDesde"] = nullIfCero($_REQUEST["fechaDesde"]);
	$where.= " AND VD_FECHAVISITA >= :fechaDesde ";
}

if (isFechaValida($_REQUEST["fechaHasta"], false) and !($_REQUEST("fechaHasta")==""))
{
	$params[":fechaHasta"] = nullIfCero($_REQUEST["fechaHasta"]);
	$where.= " AND VD_FECHAVISITA <= :fechaHasta";
}

$sql =
	"SELECT   ¿vp_id?,vp_id ¿id2?, em_cuit ¿CUIT?, em_nombre ¿Razonsocial?, co_contrato ¿contrato?, es_nroestableci ¿nroestableci?, VP_FECHAVISITA ¿fechavisita?, vp_kms ¿kms?
			  
         FROM art.pit_firmantes pit,
 	          afi.aes_establecimiento aes,
	          afi.aco_contrato aco,
	          afi.aem_empresa aem,
	          hys.hvd_visitadeclarada hvd,
	          hys.hvp_visitapreventor hvp
	    WHERE em_id = vp_idempresa
	      AND em_id = co_idempresa
	      AND es_contrato = co_contrato
	      AND co_contrato = art.get_vultcontrato (em_cuit)
	      AND es_nroestableci = vp_establecimiento
	      AND TRUNC (vp_fechavisita) = vd_fechavisita(+)
	      AND vp_idpreventor = vd_idpreventor(+)
	      AND it_id = vp_idpreventor
	      AND vp_fechabaja IS NULL
	      AND it_id = :idprev
	      AND vp_origen IN ('P')  _EXC1_";

$grilla = new Grid();
$grilla->addColumn(new Column("E", 0, true, false, -1, "btnEditar", "/prevencion/modificacion-tarea", "", -1, true, -1, "Editar", false, "", "button", -1, -1,true));
$grilla->addColumn(new Column("B", 0, true, false, -1, "btnQuitar", "/prevencion/baja-tarea", "", -1, true, -1, "Dar de Baja", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("C.U.I.T"));
$grilla->addColumn(new Column("Razón Social"));
$grilla->addColumn(new Column("Contrato"));
$grilla->addColumn(new Column("Nro. Establecimiento"));
$grilla->addColumn(new Column("Fecha Visita"));
$grilla->addColumn(new Column("KMS"));
//$grilla->addColumn(new Column("Usu.Baja"));
//$grilla->addColumn(new Column("Fecha Baja"));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setShowTotalRegistros(true);
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