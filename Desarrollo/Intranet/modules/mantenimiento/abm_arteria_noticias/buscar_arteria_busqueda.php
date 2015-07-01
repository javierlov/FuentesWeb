<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


set_time_limit(120);

try {
	if (($_REQUEST["ano"] != "") and (!validarEntero($_REQUEST["ano"])))
		throw new Exception("El campo Año es inválido.");
	if (($_REQUEST["numero"] != "") and (!validarEntero($_REQUEST["numero"])))
		throw new Exception("El campo Número es inválido.");
	if (($_REQUEST["fechaPublicacionDesde"] != "") and (!isFechaValida($_REQUEST["fechaPublicacionDesde"])))
		throw new Exception("El campo Fecha Publicación Desde es inválido.");
	if (($_REQUEST["fechaPublicacionHasta"] != "") and (!isFechaValida($_REQUEST["fechaPublicacionHasta"])))
		throw new Exception("El campo Fecha Publicación Hasta es inválido.");
	if (($_REQUEST["vigenciaDesde"] != "") and (!isFechaValida($_REQUEST["vigenciaDesde"])))
		throw new Exception("El campo Vigencia Desde es inválido.");
	if (($_REQUEST["vigenciaHasta"] != "") and (!isFechaValida($_REQUEST["vigenciaHasta"])))
		throw new Exception("El campo Vigencia Hasta es inválido.");


	$pagina = $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["pagina"];
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["ob"];
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$sb = $_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"]["sb"];
	if (isset($_REQUEST["sb"]))
		$sb = ($_REQUEST["sb"] == "T");

	$_SESSION["BUSQUEDA_ARTERIA_BUSQUEDA"] = array("ano" => $_REQUEST["ano"],
																								 "fechaPublicacionDesde" => $_REQUEST["fechaPublicacionDesde"],
																								 "fechaPublicacionHasta" => $_REQUEST["fechaPublicacionHasta"],
																								 "numero" => $_REQUEST["numero"],
																								 "ob" => $ob,
																								 "pagina" => $pagina,
																								 "sb" => $sb,
																								 "vigenciaDesde" => $_REQUEST["vigenciaDesde"],
																								 "vigenciaHasta" => $_REQUEST["vigenciaHasta"]);

	$params = array();
	$where = "";

	if ($_REQUEST["id"] != 0) {
		$params[":id"] = $_REQUEST["id"];
		$where.= " AND ba_id = :id";
	}

	if ($_REQUEST["ano"] != "") {
		$params[":ano"] = $_REQUEST["ano"];
		$where.= " AND ba_ano = :ano";
	}

	if ($_REQUEST["fechaPublicacionDesde"] != "") {
		$params[":fechadesde"] = $_REQUEST["fechaPublicacionDesde"];
		$where.= " AND ba_fecha >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
	}
	if ($_REQUEST["fechaPublicacionHasta"] != "") {
		$params[":fechahasta"] = $_REQUEST["fechaPublicacionHasta"];
		$where.= " AND TRUNC(ba_fecha) <= TO_DATE(:fechahasta, 'dd/mm/yyyy')";
	}

	if ($_REQUEST["numero"] != "") {
		$params[":numero"] = $_REQUEST["numero"];
		$where.= " AND ba_numero = :numero";
	}

	if ($_REQUEST["vigenciaDesde"] != "") {
		$params[":vigenciaDesde"] = $_REQUEST["vigenciaDesde"];
		$where.= " AND TO_DATE(:vigenciaDesde, 'dd/mm/yyyy') BETWEEN TRUNC(ba_fechavigenciadesde) AND ba_fechavigenciahasta";
	}

	if ($_REQUEST["vigenciaHasta"] != "") {
		$params[":vigenciaHasta"] = $_REQUEST["vigenciaHasta"];
		$where.= " AND TO_DATE(:vigenciaHasta, 'dd/mm/yyyy') BETWEEN TRUNC(ba_fechavigenciadesde) AND ba_fechavigenciahasta";
	}

	$sql =
		"SELECT ¿ba_id?,
						¿ba_ano?,
						¿ba_numero?,
						¿ba_fecha?,
						¿ba_fechavigenciadesde?,
						¿ba_fechavigenciahasta?,
					  DECODE(ba_estadoenvio, 'P', 'Pendiente', 'Enviado el ' || TO_CHAR(ba_fechaenvio)) ¿estadoenvio?,
						ba_fechabaja ¿baja?
			 FROM rrhh.rba_boletinesarteria
			WHERE 1 = 1 _EXC1_";
	$grilla = new Grid(15, 20);
	$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEditar", "/arteria-noticias-abm/", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column("Año", 80, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Número", 80, true, false, -1, "", "", "gridColAlignRight", -1, false));
	$grilla->addColumn(new Column("Fecha Publicación", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Fecha Vigencia Desde", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Fecha Vigencia Hasta", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Estado"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja("ba_fechabaja", $sb, true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("ba_fechabaja");
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