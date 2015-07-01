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


if (isset($_SESSION["isAgenteComercial"])) {
	validarSesion(validarContrato($_REQUEST["id"]));
	validarSesion(validarEntero($_REQUEST["id"]));
	if (isset($_REQUEST["id"])) {
		$id = $_REQUEST["id"];
		$params = array(":contrato" => $id);
		$sql = 
			"SELECT em_cuit, NVL(em_nombre, '-') empresa, NVL(co_idempresa, -1) idempresa
				 FROM aco_contrato, aem_empresa
				WHERE co_idempresa = em_id
					AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
					AND co_contrato = :contrato";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);

		$_SESSION["contrato"] = $_REQUEST["id"];
		$_SESSION["cuit"] = $row["EM_CUIT"];
		$_SESSION["empresa"] = $row["EMPRESA"];
		$_SESSION["idEmpresa"] = $row["IDEMPRESA"];
	}
	else {
		$_SESSION["contrato"] = 0;
		$_SESSION["cuit"] = "";
		$_SESSION["empresa"] = "";
		$_SESSION["idEmpresa"] = 0;
	}
}


SetDateFormatOracle("DD/MM/YYYY");

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$_SESSION["BUSQUEDA_DENUNCIA_SINIESTROS"] = array("buscar" => "S",
																									"documento" => $_REQUEST["documento"],
																									"fechaSiniestro" => $_REQUEST["fechaSiniestro"],
																									"nombre" => $_REQUEST["nombre"],
																									"numeroSiniestro" => $_REQUEST["numeroSiniestro"],
																									"numeroTransaccion" => $_REQUEST["numeroTransaccion"],
																									"ob" => $ob,
																									"pagina" => $pagina);

$params = array(":cuit" => $_SESSION["cuit"]);
$where = "";

if ($_REQUEST["numeroTransaccion"] != "") {
	$params[":transaccion"] = intval($_REQUEST["numeroTransaccion"]);
	$where.= " AND ew_transaccion = :transaccion";
}
if ($_REQUEST["fechaSiniestro"] != "") {
	$params[":fechasin"] = $_REQUEST["fechaSiniestro"];
	$where.= " AND ew_fechasin = TO_DATE(:fechasin, 'DD/MM/YYYY')";
}
if ($_REQUEST["documento"] != "") {
	$params[":documento"] = $_REQUEST["documento"];
	$where.= " AND jw_documento = :documento";
}
if ($_REQUEST["nombre"] != "") {
	$params[":nombre"] = $_REQUEST["nombre"];
	$where.= " AND jw_nombre LIKE UPPER(:nombre || '%')";
}
if ($_REQUEST["numeroSiniestro"] != "") {
	$params[":siniestro"] = intval($_REQUEST["numeroSiniestro"]);
	$where.= " AND ew_siniestro = :siniestro";
}

$sql =
	"SELECT ew_transaccion ¿id?,
					¿ew_transaccion?,
					NVL(acweb.tb_descripcion, 'Pendiente') ¿accion?,
					¿ew_fecharecepcion?,
					¿jw_nombre?,
					¿ew_siniestro?
		 FROM tmp_sdew_denuncia, tmp_ctjw_trabajador, ctb_tablas acweb
		WHERE ew_empleado = jw_id
			AND acweb.tb_codigo(+) = ew_accion
			AND acweb.tb_clave(+) = 'ACWEB'
			AND ew_cuit = :cuit _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/denuncia-siniestros/formulario", "gridFirstColumn", -1, true, -1, "", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("Nº Transacción"));
$grilla->addColumn(new Column("Acción"));
$grilla->addColumn(new Column("Fecha Recepción"));
$grilla->addColumn(new Column("Trabajador"));
$grilla->addColumn(new Column("Nº Siniestro"));
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