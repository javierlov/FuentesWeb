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


validarSesion(isset($_SESSION["isCliente"]));
validarSesion($_SESSION["isAdminTotal"]);

SetDateFormatOracle("DD/MM/YYYY");

$pagina = $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = $_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"]["sb"];
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;


$_SESSION["BUSQUEDA_ADMINISTRACION_RESPONSABLES_CONTRATO"] = array("buscar" => "S",
																																	 "contrato" => $_REQUEST["contrato"],
																																	 "cuit" => $_REQUEST["cuit"],
																																	 "email" => $_REQUEST["email"],
																																	 "empresa" => $_REQUEST["empresa"],
																																	 "estado2" => $_REQUEST["estado2"],
																																	 "nombre" => $_REQUEST["nombre"],
																																	 "ob" => $ob,
																																	 "pagina" => $pagina,
																																	 "sb" => $sb);


$params = array();
$where = "";

if (intval($_REQUEST["contrato"]) > 0) {
	$params[":contrato"] = intval($_REQUEST["contrato"]);
	$where.= " AND co_contrato = :contrato";
}

if ($_REQUEST["cuit"] != "") {
	$params[":cuit"] = sacarGuiones($_REQUEST["cuit"]);
	$where.= " AND em_cuit = :cuit";
}

if ($_REQUEST["email"] != "") {
	$params[":email"] = "%".$_REQUEST["email"]."%";
	$where.= " AND UPPER(uc_email) LIKE UPPER(:email)";
}

if ($_REQUEST["empresa"] != "") {
	$params[":empresa"] = "%".$_REQUEST["empresa"]."%";
	$where.= " AND em_nombre LIKE UPPER(:empresa)";
}

if ($_REQUEST["estado2"] != -1) {
	$params[":estado"] = $_REQUEST["estado2"];
	$where.= " AND ue_estado = UPPER(:estado)";
}

if ($_REQUEST["nombre"] != "") {
	$params[":nombre"] = "%".$_REQUEST["nombre"]."%";
	$where.= " AND UPPER(uc_nombre) LIKE UPPER(:nombre)";
}

$sql =
	"SELECT /*+ FULL(AEM_EMPRESA) */ ¿ue_id?,
					¿uc_nombre?,
					¿uc_email?,
					¿uc_telefonos?,
					¿em_cuit?,
					¿em_nombre?,
					¿uc_fechaalta?,
					¿uc_usualta?,
					DECODE(uc_esadminempresa, 'S', 'Sí', 'No') ¿adminempresa?,
					ue_fechaultimoacceso ¿fechaultimoacceso?,
					uc_fechabaja ¿baja?
		 FROM web.wue_usuariosextranet, web.wuc_usuariosclientes, web.wcu_contratosxusuarios, aco_contrato, aem_empresa
		WHERE ue_id = uc_idusuarioextranet(+)
			AND uc_id = cu_idusuario(+)
			AND cu_contrato = co_contrato(+)
			AND co_idempresa = em_id(+)
			AND ue_idmodulo = 49 _EXC1_";
$grilla = new Grid();
$grilla->addColumn(new Column("E", 0, true, false, -1, "btnEditar", "/edicion-usuario", "", -1, true, -1, "Editar", false, "", "button", -1, -1, true));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("e-Mail"));
$grilla->addColumn(new Column("Teléfono"));
$grilla->addColumn(new Column("CUIT"));
$grilla->addColumn(new Column("Empresa"));
$grilla->addColumn(new Column("Fecha Alta"));
$grilla->addColumn(new Column("Usuario Alta"));
$grilla->addColumn(new Column("Admin Empresa"));
$grilla->addColumn(new Column("F. Últ. Acceso"));
$grilla->addColumn(new Column("", 0, false, false));
$grilla->setBaja(5, $sb, false);
$grilla->setColsSeparator(true);
$grilla->setExtraConditions(array($where));
$grilla->setFieldBaja("uc_fechabaja");
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
		getElementById('linkToExcel').style.visibility = 'visible';
		getElementById('linkToExcel').href = '/modules/usuarios_registrados/clientes/administracion_responsables_contrato/exportar_a_excel.php?sql=<?= rawurlencode($grilla->getSqlFinal(true))?>';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>