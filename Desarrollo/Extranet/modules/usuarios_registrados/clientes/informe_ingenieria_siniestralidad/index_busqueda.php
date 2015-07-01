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
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 94));


if ($_REQUEST["periodo"] == -1) {
?>
	<script type="text/javascript">
		window.parent.document.getElementById('divProcesando').style.display = 'none';
	</script>
<?
	exit;
}

set_time_limit(120);

$pagina = $_SESSION["BUSQUEDA_INFORMES_INGENIERIA_SINIESTRALIDAD"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_INFORMES_INGENIERIA_SINIESTRALIDAD"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["BUSQUEDA_INFORMES_INGENIERIA_SINIESTRALIDAD"] = array("buscar" => "S",
																																 "periodo" => $_REQUEST["periodo"],
																																 "ob" => $ob,
																																 "pagina" => $pagina);

$params = array(":contrato" => $_SESSION["contrato"], ":periodo" => $_REQUEST["periodo"]);
$sql =
	"SELECT ¿ii_id?,
					DECODE(ii_tipoarchivo, 'I', 'INFORME DE LA EMPRESA', DECODE(ii_tipoarchivo, '1', 'ANEXO 1', DECODE(ii_tipoarchivo, '2', 'ANEXO 2', '-'))) ¿tipoarchivo?
		 FROM web.wii_informesiys
		WHERE ii_contrato = :contrato
			AND ii_periodo = :periodo
			AND ii_fechabaja IS NULL";
$grilla = new Grid();
$grilla->addColumn(new Column("Ver", 0, true, false, -1, "btnEditar", "/modules/usuarios_registrados/clientes/informe_ingenieria_siniestralidad/ver_archivo.php", "", -1, true, -1, "Editar"));
$grilla->addColumn(new Column("Archivo"));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowProcessMessage(true);
$grilla->setShowTotalRegistros(true);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>