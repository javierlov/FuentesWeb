<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


set_time_limit(120);

if (!isset($_SESSION["BUSQUEDA_EMPLEADO"]))
	$_SESSION["BUSQUEDA_EMPLEADO"] = array("nombre" => "",
																				 "ob" => "2",
																				 "pagina" => 1,
																				 "sector" => "");

$pagina = $_SESSION["BUSQUEDA_EMPLEADO"]["pagina"];
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = $_SESSION["BUSQUEDA_EMPLEADO"]["ob"];
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$_SESSION["BUSQUEDA_EMPLEADO"] = array("nombre" => $_REQUEST["nombre"],
																			 "ob" => $ob,
																			 "pagina" => $pagina,
																			 "sector" => $_REQUEST["sector"]);

$msg = "";
$params = array();
$where = "";

if ($_REQUEST["nombre"] != "") {
	$params[":apenom"] = "%".removeAccents(str_replace("ñ", "Ñ", $_REQUEST["nombre"]))."%";
	$where.= " AND useu.se_buscanombre LIKE UPPER(:apenom)";
}

if ($_REQUEST["sector"] != "") {
	$params[":sector"] = "%".removeAccents($_REQUEST["sector"])."%";
	$where.= " AND (REPLACE(UPPER(ART.UTILES.reemplazar_acentos(cse3.se_descripcion)), 'Ñ', 'N') LIKE UPPER(:sector) OR REPLACE(UPPER(ART.UTILES.reemplazar_acentos(cse.se_descripcion)), 'Ñ', 'N') LIKE UPPER(:sector))";
}

if ($where == "")
	$where = " AND 1 = 2";

$sql =
	"SELECT /*+ index(art.use_usuarios ndx_use_parabusqueda)*/ useu.se_id ¿se_id?,
					useu.se_nombre ¿se_nombre?,
					cse.se_descripcion ¿sector?,
					cse3.se_descripcion ¿gerencia?,
					useu.se_interno ¿se_interno?
		 FROM art.use_usuarios useu, usc_sectores, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
		WHERE useu.se_idsector = cse.se_id
			AND useu.se_fechabaja IS NULL
			AND useu.se_sector = sc_codigo
			AND sc_visible = 'S'
			AND cse.se_visible = 'S'
-- 		    AND useu.se_sector NOT IN ('CALLCENT', 'BPAGOS', 'BAPRO', 'BANK', 'AUDGRUP', 'XUNILSA', 'GBPS', 'ESTJUD', 'DIMO', 'SML')
			AND (useu.se_usuariogenerico = 'N' OR useu.se_sector = 'RECEPCIO')
			AND cse.se_idsectorpadre = cse2.se_id
			AND cse2.se_idsectorpadre = cse3.se_id _EXC1_";
$grilla = new Grid(15, 15);
$grilla->addColumn(new Column(".", 40, true, false, -1, "gridBtnVer", "/contacto/", "", -1, true, -1, "Ver"));
$grilla->addColumn(new Column("Nombre"));
$grilla->addColumn(new Column("Sector"));
$grilla->addColumn(new Column("Gerencia"));
$grilla->addColumn(new Column("Interno"));
$grilla->setExtraConditions(array($where));
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
$grilla->setShowMessageNoResults((strlen($_REQUEST["nombre"]) <= 2) or (strlen($_REQUEST["sector"]) > 0));
$grilla->setShowProcessMessage(true);
$grilla->setShowTotalRegistros(true);
$grilla->setSql($sql);
$grilla->Draw();

if ((strlen($_REQUEST["nombre"]) > 2) and (strlen($_REQUEST["sector"]) == 0) and ($grilla->recordCount() == 0)) {
	$params = array();
	$sql = " AND (1=2";

	// Este for reemplaza cada caracter por un comodin..
	for ($i=0; $i<strlen($_REQUEST["nombre"]); $i++) {
		$texto = $_REQUEST["nombre"];
		$texto[$i] = "_";

		$params[":nombre1_".$i] = "%".removeAccents(strtoupper(str_replace("ñ", "Ñ", $texto)))."%";
		$sql.= " OR useu.se_buscanombre LIKE :nombre1_".$i;
	}

	// Este for quita el caracter en el que se está loopeando..
	for ($i=0; $i<strlen($_REQUEST["nombre"]); $i++) {
		$texto = substr($_REQUEST["nombre"], 0, $i).substr($_REQUEST["nombre"], $i + 1);

		$params[":nombre2_".$i] = "%".removeAccents(strtoupper(str_replace("ñ", "Ñ", $texto)))."%";
		$sql.= " OR useu.se_buscanombre LIKE :nombre2_".$i;
	}

	// Este for agrega un comodin antes de cada caracter..
	for ($i=0; $i<strlen($_REQUEST["nombre"]); $i++) {
		$texto = substr($_REQUEST["nombre"], 0, $i)."_".substr($_REQUEST["nombre"], $i);

		$params[":nombre3_".$i] = "%".removeAccents(strtoupper(str_replace("ñ", "Ñ", $texto)))."%";
		$sql.= " OR useu.se_buscanombre LIKE :nombre3_".$i;
	}

	$sql.= ")";
	$grilla->setParams($params);
	$grilla->setExtraConditions(array($sql));
	$msg = "<div id=\"divGridSinDatos\">No se encontraron datos con las caracteristicas buscadas, quizás quiso buscar a:</div>";
	$grilla->setShowMessageNoResults(true);
	$grilla->Draw();

	if ($grilla->recordCount() == 0)
		$msg = "";
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesando').style.display = 'none';
		getElementById('divContentGrid').innerHTML = '<?= $msg?>' + document.getElementById('originalGrid').innerHTML;
		getElementById('divContentGrid').style.display = 'block';
	}
</script>