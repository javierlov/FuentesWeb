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
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 100));


set_time_limit(120);

$pagina = $_SESSION["BUSQUEDA_RGRL"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_RGRL"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["BUSQUEDA_RGRL"] = array("buscar" => "S",
																	 "ob" => $ob,
																	 "pagina" => $pagina);

$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT ¿es_nroestableci?,
					¿es_nombre?,
					art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, NULL, NULL) || ' ' || art.utiles.armar_localidad(es_cpostal, NULL, es_localidad, es_provincia) ¿domicilio?,
					es_id ¿editar?,
					DECODE(art.hys.get_validorelev463 (:contrato, es_nroestableci), NULL, '', es_id) ¿pdf?,
					DECODE(sf_id, NULL, 'btnRGRL', DECODE(sf_fechapasaje, NULL, 'btnRGRLOk', 'btnRGRL')) ¿buttonclass?,
					DECODE(art.hys.get_validorelev463 (:contrato, es_nroestableci), NULL, '', 'btnPdf') ¿buttonclass2?
		 FROM afi.aes_establecimiento, hys.hsf_solicitudfgrl
		WHERE es_id = sf_idestablecimiento(+)
			AND es_contrato = :contrato
			AND es_fechabaja IS NULL
			AND sf_fechabaja(+) IS NULL
			AND art.hys.get_requiererelev463(:contrato, es_nroestableci, 'N') = 'S'";
$grilla = new Grid();
$grilla->addColumn(new Column("Nº Est.", 104, true, false, -1, "", "", "gridColAlignRight", -1, false));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("Domicilio"));
$grilla->addColumn(new Column("Nueva Presentación", 0, true, false, -1, "", "/modules/usuarios_registrados/clientes/rgrl/abrir_ventana_rgrl.php", "", -1, true, -1, "", false, "", "button", -1, 6));
$grilla->addColumn(new Column("Presentado en la ART", 0, true, false, -1, "", "/modules/usuarios_registrados/clientes/rgrl/abrir_pdf.php", "", -1, true, -1, "", false, "", "button", -1, 7));
$grilla->addColumn(new Column("", 0, false));
$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 2));
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