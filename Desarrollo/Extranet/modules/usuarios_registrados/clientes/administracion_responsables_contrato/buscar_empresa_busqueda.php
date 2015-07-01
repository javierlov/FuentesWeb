<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(($_SESSION["isAdminTotal"]) or (validarPermisoClienteXModulo($_SESSION["idUsuario"], 66)));

if ((isset($_REQUEST["sd"])) and ($_REQUEST["sd"] == "t")) {		// Si es true le paso la empresa a la ventana del usuario..
?>
<script type="text/javascript">
	with (parent.document) {
		getElementById('contratos').value = getElementById('contratos').value + ',<?= $_REQUEST["id"]?>';
		getElementById('iframeEmpresas').src = '/modules/usuarios_registrados/clientes/administracion_responsables_contrato/empresas.php?c=' + getElementById('contratos').value;
	}
	parent.divWin.close();
</script>
<?
	exit;
}


$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$params = array();
$where = "";

if ($_REQUEST["a"] == "n") {		// Si es "n" es porque el usuario al que se quiere asociar una empresa NO es admin..
	$params[":idusuarioextranet"] = $_SESSION["idUsuario"];
	$where.=
		" AND co_contrato IN (SELECT cu_contrato
														FROM web.wcu_contratosxusuarios, web.wuc_usuariosclientes
													 WHERE cu_idusuario = uc_id
														 AND uc_idusuarioextranet = :idusuarioextranet
											 UNION ALL
													SELECT ".$_SESSION["contrato"]."
														FROM DUAL)";
}

if ($_REQUEST["contrato"] != "") {
	$params[":contrato"] = intval($_REQUEST["contrato"]);
	$where.= " AND co_contrato = :contrato";
}

if ($_REQUEST["cuit"] != "") {
	$params[":cuit"] = sacarGuiones($_REQUEST["cuit"]);
	$where.= " AND em_cuit = :cuit";
}

if ($_REQUEST["nombre"] != "") {
	$params[":nombre"] = "%".$_REQUEST["nombre"]."%";
	$where.= " AND UPPER(em_nombre) LIKE UPPER(:nombre)";
}

$sql =
	"SELECT ¿co_contrato?, ¿em_nombre?, art.utiles.armar_cuit(em_cuit) ¿cuit?, co_contrato ¿contrato?
		 FROM aem_empresa, aco_contrato
		WHERE em_id = co_idempresa
			AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1 _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("", 8, true, false, -1, "btnSeleccionar", $_SERVER["PHP_SELF"]."?sd=t", ""));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("CUIT"));
$grilla->addColumn(new Column("Contrato"));
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where));
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