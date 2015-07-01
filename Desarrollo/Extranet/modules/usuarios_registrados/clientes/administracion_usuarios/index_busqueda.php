<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 66));

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = false;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;

$_SESSION["BUSQUEDA_ADMINISTRACION_USUARIOS"] = array("buscar" => "S",
																											"email" => $_REQUEST["email"],
																											"nombre" => $_REQUEST["nombre"],
																											"ob" => $ob,
																											"pagina" => $pagina,
																											"sb" => $sb);


$params = array(":contrato" => $_SESSION["contrato"], "idusuarioextranet" => $_SESSION["idUsuario"]);
$where = "";

if ($_REQUEST["email"] != "") {
	$params[":email"] = "%".$_REQUEST["email"]."%";
	$where.= " AND UPPER(uc_email) LIKE UPPER(:email)";
}

if ($_REQUEST["nombre"] != "") {
	$params[":nombre"] = $_REQUEST["nombre"]."%";
	$where.= " AND UPPER(uc_nombre) LIKE UPPER(:nombre)";
}

$sql =
	"SELECT ¿uc_idusuarioextranet?, ¿uc_nombre?, ¿uc_email?, ¿uc_cargo?, uc_fechabaja ¿baja?
		 FROM web.wuc_usuariosclientes, web.wcu_contratosxusuarios
		WHERE uc_id = cu_idusuario
			AND uc_esadmintotal = 'N'
			AND uc_idusuarioextranet <> :idusuarioextranet
			AND cu_contrato = :contrato _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column(" ", 0, true, false, -1, "btnEditar", "/edicion-usuario-2", "", -1, true, -1, "Editar", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("e-Mail"));
$grilla->addColumn(new Column("Cargo"));
$grilla->addColumn(new Column("", 0, false, true));
$grilla->setBaja(5, $sb, false);
$grilla->setExtraConditions(array($where));
$grilla->setFieldBaja("uc_fechabaja");
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