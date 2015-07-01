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


	$pagina = $_SESSION["BUSQUEDA_NOVEDAD"]["pagina"];
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = $_SESSION["BUSQUEDA_NOVEDAD"]["ob"];
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$sb = $_SESSION["BUSQUEDA_NOVEDAD"]["sb"];
	if (isset($_REQUEST["sb"]))
		$sb = ($_REQUEST["sb"] == "T");

	$_SESSION["BUSQUEDA_NOVEDAD"] = array("empleadoLista" => $_REQUEST["empleadoLista"],
																				"ob" => $ob,
																				"pagina" => $pagina,
																				"sb" => $sb,
																				"tipoMovimiento" => $_REQUEST["tipoMovimientoBusqueda"],
																				"vigenciaDesde" => $_REQUEST["vigenciaDesdeBusqueda"],
																				"vigenciaHasta" => $_REQUEST["vigenciaHastaBusqueda"]);

	$params = array();
	$where = "";

	if ($_REQUEST["id"] != 0) {
		$params[":id"] = $_REQUEST["id"];
		$where.= " AND hn_id = :id";
	}

	if ($_REQUEST["empleadoLista"] != "") {
		$params[":nombre"] = "%".strtoupper($_REQUEST["empleadoLista"])."%";
		$where.= " AND se_buscanombre LIKE :nombre";
	}

	if ($_REQUEST["tipoMovimientoBusqueda"] != -1) {
		$params[":tipomovimiento"] = $_REQUEST["tipoMovimientoBusqueda"];
		$where.= " AND hn_tipomovimiento = :tipomovimiento";
	}

	if ($_REQUEST["vigenciaDesdeBusqueda"] != "") {
		$params[":vigenciaDesde"] = $_REQUEST["vigenciaDesdeBusqueda"];
		$where.= " AND TO_DATE(:vigenciaDesde, 'dd/mm/yyyy') BETWEEN TRUNC(hn_fechavigenciadesde) AND hn_fechavigenciahasta";
	}

	if ($_REQUEST["vigenciaHastaBusqueda"] != "") {
		$params[":vigenciaHasta"] = $_REQUEST["vigenciaHastaBusqueda"];
		$where.= " AND TO_DATE(:vigenciaHasta, 'dd/mm/yyyy') BETWEEN TRUNC(hn_fechavigenciadesde) AND hn_fechavigenciahasta";
	}

	$sql =
		"SELECT ¿hn_id?,
						¿se_nombre?,
						DECODE(hn_tipomovimiento, 'A', 'Ingreso', 'B', 'Egreso', 'M', 'Pase de Sector', NULL) ¿tipomovimiento?,
						¿hn_fechavigenciadesde?,
						¿hn_fechavigenciahasta?
			 FROM rrhh.rhn_novedades, use_usuarios
			WHERE hn_idusuario = se_id
				AND hn_fechabaja IS NULL _EXC1_";
	$grilla = new Grid(15, 20);
	$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEditar", "/novedades-abm/", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column("Usuario"));
	$grilla->addColumn(new Column("Tipo Movimiento"));
	$grilla->addColumn(new Column("Vigencia Desde", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Vigencia Hasta", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja("hn_fechabaja", $sb, true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("hn_fechabaja");
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