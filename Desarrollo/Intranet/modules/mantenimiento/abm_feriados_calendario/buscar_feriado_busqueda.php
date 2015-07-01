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
	if (($_REQUEST["fechaDesdeBusqueda"] <> "") and (!isFechaValida($_REQUEST["fechaDesdeBusqueda"])))
		throw new Exception("El campo Fecha Feriado Desde es inválido.");
	if (($_REQUEST["fechaHastaBusqueda"] <> "") and (!isFechaValida($_REQUEST["fechaHastaBusqueda"])))
		throw new Exception("El campo Fecha Feriado Hasta es inválido.");


	$pagina = $_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"]["pagina"];
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = $_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"]["ob"];
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$sb = $_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"]["sb"];
	if (isset($_REQUEST["sb"]))
		$sb = ($_REQUEST["sb"] == "T");

	$_SESSION["BUSQUEDA_FERIADOS_CALENDARIO"] = array("delegacion" => $_REQUEST["delegacionBusqueda"],
																										"fechaDesde" => $_REQUEST["fechaDesdeBusqueda"],
																										"fechaHasta" => $_REQUEST["fechaHastaBusqueda"],
																										"ob" => $ob,
																										"pagina" => $pagina,
																										"sb" => $sb);

	$params = array();
	$where = "";

	if ($_REQUEST["id"] != 0) {
		$params[":id"] = $_REQUEST["id"];
		$where.= " AND fd_id = :id";
	}

	if ($_REQUEST["delegacionBusqueda"] != -1) {
		$params[":iddelegacion"] = $_REQUEST["delegacionBusqueda"];
		$where.= " AND fd_iddelegacion LIKE :iddelegacion";
	}

	if ($_REQUEST["fechaDesdeBusqueda"] != "") {
		$params[":fechaDesde"] = $_REQUEST["fechaDesdeBusqueda"];
		$where.= " AND fd_fecha >= TO_DATE(:fechaDesde, 'dd/mm/yyyy')";
	}

	if ($_REQUEST["fechaHastaBusqueda"] != "") {
		$params[":fechaHasta"] = $_REQUEST["fechaHastaBusqueda"];
		$where.= " AND fd_fecha <= TO_DATE(:fechaHasta, 'dd/mm/yyyy')";
	}

	$sql =
		"SELECT ¿fd_id?,
						¿el_nombre?,
						¿fd_fecha?,
						¿fd_fechabaja?
			 FROM comunes.cfd_feriadosdelegaciones, del_delegacion
			WHERE fd_iddelegacion = el_id
				AND fd_fechabaja IS NULL _EXC1_";
	$grilla = new Grid(15, 20);
	$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEditar", "/calendario-feriados-abm/", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column("Delegación"));
	$grilla->addColumn(new Column("Fecha Feriado", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja("fd_fechabaja", $sb, true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("fd_fechabaja");
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