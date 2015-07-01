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


	$pagina = $_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]["pagina"];
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = $_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]["ob"];
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$sb = $_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"]["sb"];
	if (isset($_REQUEST["sb"]))
		$sb = ($_REQUEST["sb"] == "T");

	$_SESSION["BUSQUEDA_BUSQUEDA_CORPORATIVA"] = array("empresaBusqueda" => -1,
																										 "estadoBusqueda" => -1,
																										 "ob" => $ob,
																										 "pagina" => $pagina,
																										 "puestoBusqueda" => $_REQUEST["puestoBusqueda"],
																										 "sb" => $sb,
																										 "vigenciaDesde" => $_REQUEST["vigenciaDesdeBusqueda"],
																										 "vigenciaHasta" => $_REQUEST["vigenciaHastaBusqueda"]);

	$params = array();
	$where = "";

	if ($_REQUEST["id"] != 0) {
		$params[":id"] = $_REQUEST["id"];
		$where.= " AND bc_id = :id";
	}

	if ($_REQUEST["empresaBusqueda"] != -1) {
		$params[":idempresa"] = $_REQUEST["empresaBusqueda"];
		$where.= " AND bc_idempresa = :idempresa";
	}

	if ($_REQUEST["estadoBusqueda"] != -1) {
		$params[":idestado"] = $_REQUEST["estadoBusqueda"];
		$where.= " AND bc_idestado = :idestado";
	}

	if ($_REQUEST["puestoBusqueda"] != "") {
		$params[":puesto"] = "%".RemoveAccents($_REQUEST["puestoBusqueda"])."%";
		$where.= " AND UPPER(art.utiles.reemplazar_acentos(bc_puesto)) LIKE UPPER(:puesto)";
	}

	if ($_REQUEST["vigenciaDesdeBusqueda"] != "") {
		$params[":vigenciaDesde"] = $_REQUEST["vigenciaDesdeBusqueda"];
		$where.= " AND TO_DATE(:vigenciaDesde, 'dd/mm/yyyy') BETWEEN TRUNC(bc_fechavigenciadesde) AND bc_fechavigenciahasta";
	}

	if ($_REQUEST["vigenciaHastaBusqueda"] != "") {
		$params[":vigenciaHasta"] = $_REQUEST["vigenciaHastaBusqueda"];
		$where.= " AND TO_DATE(:vigenciaHasta, 'dd/mm/yyyy') BETWEEN TRUNC(bc_fechavigenciadesde) AND bc_fechavigenciahasta";
	}

	$sql =
		"SELECT ¿bc_id?,
						TO_NUMBER(bc_id) ¿id2?,
						¿bc_puesto?,
						¿em_nombre?,
						¿ec_detalle?,
						¿bc_fechavigenciadesde?,
						¿bc_fechavigenciahasta?,
						¿bc_fechabaja?
			 FROM rrhh.rbc_busquedascorporativas, rrhh.rec_estadosbusquedacorporativa, aem_empresa
			WHERE bc_idestado = ec_id
				AND bc_idempresa = em_id(+)
				AND bc_fechabaja IS NULL _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEditar", "/busquedas-corporativas-abm/", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column("Nº"));
	$grilla->addColumn(new Column("Puesto"));
	$grilla->addColumn(new Column("Empresa"));
	$grilla->addColumn(new Column("Estado"));
	$grilla->addColumn(new Column("Vigencia Desde", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("Vigencia Hasta", 0, true, false, -1, "", "", "gridColAlignCenter", -1, false));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja("bc_fechabaja", $sb, true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("bc_fechabaja");
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