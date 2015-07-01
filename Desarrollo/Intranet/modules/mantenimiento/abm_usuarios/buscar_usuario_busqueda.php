<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


set_time_limit(120);


try {
	if (($_REQUEST["legajoBusqueda"] <> "") and (!validarEntero($_REQUEST["legajoBusqueda"])))
		throw new Exception("El campo Legajo es inválido.");


	$pagina = $_SESSION["BUSQUEDA_USUARIO_BUSQUEDA"]["pagina"];
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = $_SESSION["BUSQUEDA_USUARIO_BUSQUEDA"]["ob"];
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	$sb = $_SESSION["BUSQUEDA_USUARIO_BUSQUEDA"]["sb"];
	if (isset($_REQUEST["sb"]))
		$sb = ($_REQUEST["sb"] == "T");

	$_SESSION["BUSQUEDA_USUARIO_BUSQUEDA"] = array("legajoBusqueda" => $_REQUEST["legajoBusqueda"],
																								 "nombreBusqueda" => $_REQUEST["nombreBusqueda"],
																								 "ob" => $ob,
																								 "pagina" => $pagina,
																								 "sb" => $sb);

	$params = array();
	$where = "";

	if ($_REQUEST["id"] != 0) {
		$params[":id"] = $_REQUEST["id"];
		$where.= " AND useu.se_id = :id";
	}

	if ($_REQUEST["legajoBusqueda"] != "") {
		$params[":legajorrhh"] = $_REQUEST["legajoBusqueda"];
		$where.= " AND useu.se_legajorrhh = :legajorrhh";
	}

	if ($_REQUEST["nombreBusqueda"] != "") {
		$params[":nombre"] = "%".strtoupper($_REQUEST["nombreBusqueda"])."%";
		$where.= " AND useu.se_buscanombre LIKE :nombre";
	}

	$sql =
		"SELECT useu.se_id ¿id?,
						useu.se_nombre ¿nombre?,
						cse.se_descripcion ¿sector?,
						uscar.tb_descripcion ¿cargo?,
						el_nombre ¿delegacion?,
						useu.se_fechabaja ¿fechabaja?,
						DECODE(useu.se_fechabaja, NULL, 'F', 'T') ¿hidecol1?
			 FROM use_usuarios useu, computos.cse_sector cse, del_delegacion, ctb_tablas uscar
			WHERE useu.se_idsector = cse.se_id(+)
				AND useu.se_delegacion = el_id(+)
				AND uscar.tb_clave = 'USCAR'
				AND uscar.tb_codigo = useu.se_cargo
				AND cse.se_visible = 'S'
				AND (useu.se_usuariogenerico = 'N' OR useu.se_sector = 'RECEPCIO') _EXC1_";
	$grilla = new Grid(15, 20);
	$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnEditar", "/usuarios-abm/", "", -1, true, -1, "Editar"));
	$grilla->addColumn(new Column("Nombre"));
	$grilla->addColumn(new Column("Sector"));
	$grilla->addColumn(new Column("Cargo"));
	$grilla->addColumn(new Column("Delegación"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "", -1, true, 1));
	$grilla->setBaja("useu.se_fechabaja", $sb, true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("useu.se_fechabaja");
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