<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


set_time_limit(120);

try {
	if (($_REQUEST["vigenciaDesdeBusqueda"] <> "") and (!isFechaValida($_REQUEST["vigenciaDesdeBusqueda"])))
		throw new Exception("El campo Vigencia Desde es inválido.");
	if (($_REQUEST["vigenciaHastaBusqueda"] <> "") and (!isFechaValida($_REQUEST["vigenciaHastaBusqueda"])))
		throw new Exception("El campo Vigencia Hasta es inválido.");


	$pagina = $_SESSION["BUSQUEDA_NACIMIENTO_BUSQUEDA"]["pagina"];
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = $_SESSION["BUSQUEDA_NACIMIENTO_BUSQUEDA"]["ob"];
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$sb = $_SESSION["BUSQUEDA_NACIMIENTO_BUSQUEDA"]["sb"];
	if (isset($_REQUEST["sb"]))
		$sb = ($_REQUEST["sb"] == "T");

	$_SESSION["BUSQUEDA_NACIMIENTO_BUSQUEDA"] = array("ob" => $ob,
																										"pagina" => $pagina,
																										"sb" => $sb,
																										"textoBusqueda" => $_REQUEST["textoBusqueda"],
																										"vigenciaDesdeBusqueda" => $_REQUEST["vigenciaDesdeBusqueda"],
																										"vigenciaHastaBusqueda" => $_REQUEST["vigenciaHastaBusqueda"]);

	$params = array();
	$where = "";

	if ($_REQUEST["id"] != 0) {
		$params[":id"] = $_REQUEST["id"];
		$where.= " AND np_id = :id";
	}

	if ($_REQUEST["textoBusqueda"] != "") {
		$params[":texto"] = "%".$_REQUEST["textoBusqueda"]."%";
		$where.= " AND UPPER(np_texto) LIKE UPPER(:texto)";
	}

	if ($_REQUEST["vigenciaDesdeBusqueda"] != "") {
		$params[":vigenciaDesde"] = $_REQUEST["vigenciaDesdeBusqueda"];
		$where.= " AND TO_DATE(:vigenciaDesde, 'dd/mm/yyyy') BETWEEN TRUNC(np_fechavigenciadesde) AND np_fechavigenciahasta";
	}

	if ($_REQUEST["vigenciaHastaBusqueda"] != "") {
		$params[":vigenciaHasta"] = $_REQUEST["vigenciaHastaBusqueda"];
		$where.= " AND TO_DATE(:vigenciaHasta, 'dd/mm/yyyy') BETWEEN TRUNC(np_fechavigenciadesde) AND np_fechavigenciahasta";
	}

	$sql =
		"SELECT ¿np_id?,
						¿np_texto?,
						¿np_fechavigenciadesde?,
						¿np_fechavigenciahasta?,
						¿np_fechabaja?
			 FROM rrhh.rnp_novedadespersonales
			WHERE np_tiponovedad = 'N' _EXC1_";
	$grilla = new Grid(15, 20);
	$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEditar", "/nacimientos-abm/", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column("Texto"));
	$grilla->addColumn(new Column("Vigencia Desde", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Vigencia Hasta", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja("np_fechabaja", $sb, true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("np_fechabaja");
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setShowProcessMessage(true);
	$grilla->setShowTotalRegistros(true);
	$grilla->setSql($sql);
	$grilla->Draw();
}
catch (Exception $e) {
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
	</script>
<?
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('id').value = 0;		// Limpio el filtro por id, ya que solo se tiene que mostrar al guardar..
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>