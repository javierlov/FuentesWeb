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


	$pagina = $_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]["pagina"];
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = $_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]["ob"];
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$sb = $_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"]["sb"];
	if (isset($_REQUEST["sb"]))
		$sb = ($_REQUEST["sb"] == "T");

	$_SESSION["BUSQUEDA_ENCUESTA_BUSQUEDA"] = array("activa" => isset($_REQUEST["activaBusqueda"]),
																									"detalle" => $_REQUEST["detalleBusqueda"],
																									"ob" => $ob,
																									"pagina" => $pagina,
																									"sb" => $sb,
																									"titulo" => $_REQUEST["tituloBusqueda"],
																									"vigenciaDesde" => $_REQUEST["vigenciaDesdeBusqueda"],
																									"vigenciaHasta" => $_REQUEST["vigenciaHastaBusqueda"]);

	$params = array();
	$where = "";

	if ($_REQUEST["id"] != 0) {
		$params[":id"] = $_REQUEST["id"];
		$where.= " AND en_id = :id";
	}

	if (isset($_REQUEST["activaBusqueda"])) {
		$where.= " AND en_activa = 'T'";
	}

	if ($_REQUEST["detalleBusqueda"] != "") {
		$params[":detalle"] = "%".$_REQUEST["detalleBusqueda"]."%";
		$where.= " AND UPPER(en_detalle) LIKE UPPER(:detalle)";
	}

	if ($_REQUEST["tituloBusqueda"] != "") {
		$params[":titulo"] = "%".$_REQUEST["tituloBusqueda"]."%";
		$where.= " AND UPPER(en_titulo) LIKE UPPER(:titulo)";
	}

	if ($_REQUEST["vigenciaDesdeBusqueda"] != "") {
		$params[":vigenciaDesde"] = $_REQUEST["vigenciaDesdeBusqueda"];
		$where.= " AND TO_DATE(:vigenciaDesde, 'dd/mm/yyyy') BETWEEN TRUNC(en_fechavigenciadesde) AND en_fechavigenciahasta";
	}

	if ($_REQUEST["vigenciaHastaBusqueda"] != "") {
		$params[":vigenciaHasta"] = $_REQUEST["vigenciaHastaBusqueda"];
		$where.= " AND TO_DATE(:vigenciaHasta, 'dd/mm/yyyy') BETWEEN TRUNC(en_fechavigenciadesde) AND en_fechavigenciahasta";
	}

	$sql =
		"SELECT en_id ¿id?,
						en_id ¿id2?,
						en_titulo ¿titulo?,
						en_detalle ¿detalle?,
						DECODE(en_activa, 'T', 'SI', 'NO') ¿activa?,
						¿en_fechavigenciadesde?,
						¿en_fechavigenciahasta?,
						en_fechabaja ¿baja?
			 FROM rrhh.ren_encuestas
			WHERE 1 = 1 _EXC1_";
	$grilla = new Grid(15, 20);
	$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEditar", "/encuestas-abm/", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEstadisticas", "/encuestas-abm-estadisticas/", "", -1, true, -1, "Estadísticas"));
	$grilla->addColumn(new Column("Título"));
	$grilla->addColumn(new Column("Detalle"));
	$grilla->addColumn(new Column("Activa", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Vigencia Desde", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Vigencia Hasta", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja("en_fechabaja", $sb, true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("en_fechabaja");
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