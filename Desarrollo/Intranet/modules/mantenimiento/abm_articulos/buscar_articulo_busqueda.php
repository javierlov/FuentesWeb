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
	if (($_REQUEST["vigenciaDesde"] <> "") and (!isFechaValida($_REQUEST["vigenciaDesde"])))
		throw new Exception("El campo Vigencia Desde es inválido.");
	if (($_REQUEST["vigenciaHasta"] <> "") and (!isFechaValida($_REQUEST["vigenciaHasta"])))
		throw new Exception("El campo Vigencia Hasta es inválido.");


	$pagina = $_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["pagina"];
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = $_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["ob"];
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$sb = $_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"]["sb"];
	if (isset($_REQUEST["sb"]))
		$sb = ($_REQUEST["sb"] == "T");

	$_SESSION["BUSQUEDA_ARTICULO_BUSQUEDA"] = array("ob" => $ob,
																									"habilitarComentarios" => $_REQUEST["habilitarComentarios"],
																									"mostrarEnPortada" => $_REQUEST["mostrarEnPortada"],
																									"pagina" => $pagina,
																									"sb" => $sb,
																									"titulo" => $_REQUEST["titulo"],
																									"vigenciaDesde" => $_REQUEST["vigenciaDesde"],
																									"vigenciaHasta" => $_REQUEST["vigenciaHasta"],
																									"volanta" => $_REQUEST["volanta"]);

	$params = array();
	$where = "";

	if ($_REQUEST["id"] != 0) {
		$params[":id"] = $_REQUEST["id"];
		$where.= " AND ai_id = :id";
	}

	if (isset($_REQUEST["habilitarComentarios"])) {
		$where.= " AND ai_habilitarcomentarios = 'S'";
	}

	if (isset($_REQUEST["mostrarEnPortada"])) {
		$where.= " AND ai_mostrarenportada = 'S'";
	}

	if ($_REQUEST["titulo"] != "") {
		$params[":titulo"] = "%".$_REQUEST["titulo"]."%";
		$where.= " AND UPPER(ai_titulo) LIKE UPPER(:titulo)";
	}

	if ($_REQUEST["volanta"] != "") {
		$params[":volanta"] = "%".$_REQUEST["volanta"]."%";
		$where.= " AND UPPER(ai_volanta) LIKE UPPER(:volanta)";
	}

	if ($_REQUEST["vigenciaDesde"] != "") {
		$params[":vigenciaDesde"] = $_REQUEST["vigenciaDesde"];
		$where.= " AND TO_DATE(:vigenciaDesde, 'dd/mm/yyyy') BETWEEN TRUNC(ai_fechavigenciadesde) AND ai_fechavigenciahasta";
	}

	if ($_REQUEST["vigenciaHasta"] != "") {
		$params[":vigenciaHasta"] = $_REQUEST["vigenciaHasta"];
		$where.= " AND TO_DATE(:vigenciaHasta, 'dd/mm/yyyy') BETWEEN TRUNC(ai_fechavigenciadesde) AND ai_fechavigenciahasta";
	}

	$sql =
		"SELECT ¿ai_id?,
						ai_id ¿id2?,
						¿ai_titulo?,
						¿ai_volanta?,
						¿ai_fechavigenciadesde?,
						¿ai_fechavigenciahasta?,
						DECODE(ai_mostrarenportada, 'S', 'SÍ', 'NO') ¿mostrarportada?,
						DECODE(ai_habilitarcomentarios, 'S', 'SÍ', 'NO') ¿habilitarcomentarios?,
						¿ai_fechabaja?
			 FROM web.wai_articulosintranet
			WHERE 1 = 1 _EXC1_";
	$grilla = new Grid(15, 20);
	$grilla->addColumn(new Column("E", 40, true, false, -1, "gridBtnEditar", "/articulos-abm/", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column("V", 40, true, false, -1, "gridBtnVer", "/articulos/", "", -1, true, -1, "Ver"));
	$grilla->addColumn(new Column("Título"));
	$grilla->addColumn(new Column("Volanta"));
	$grilla->addColumn(new Column("Vigencia Desde", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Vigencia Hasta", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Mostrar en Portada", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Habilitar Comentarios", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja("ai_fechabaja", $sb, true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("ai_fechabaja");
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