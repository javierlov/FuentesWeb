<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION[$_REQUEST["s"]]));
if ($_REQUEST["s"] == "isCliente")
	if (!isset($_REQUEST["idModulo"]))
		$_REQUEST["idModulo"] = -1;

$ob = "4";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$sb = true;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "F")
		$sb = false;

if (intval($_REQUEST["idTablaPadre"]) == 0)
	$_REQUEST["idTablaPadre"] = -1;

$url = "/functions/telefonos/abrir_ventana_edicion.php";
$url.= "?s=".$_REQUEST["s"];
$url.= "&campoClave=".$_REQUEST["campoClave"];
$url.= "&idModulo=".$_REQUEST["idModulo"];
$url.= "&idTablaPadre=".$_REQUEST["idTablaPadre"];
$url.= "&prefijo=".$_REQUEST["prefijo"];
$url.= "&tablaTel=".$_REQUEST["tablaTel"];
$url.= "&tipo=".$_REQUEST["tipo"];

$params = array(":tablapadreid" => $_REQUEST["idTablaPadre"],
								":tablatel" => $_REQUEST["tablaTel"],
								":tipo" => $_REQUEST["tipo"],
								":usuarioweb" => $_SESSION["usuario"]);
$sql =
	"SELECT ".(($_REQUEST["r"] == "t")?"":"¿mp_id?, ")."¿tt_descripcion?, ¿mp_area?, ¿mp_numero?, ¿mp_interno?
		 FROM tmp.tmp_telefonos, att_tipotelefono
		WHERE mp_idtipotelefono = tt_id(+)
			AND mp_usuarioweb = :usuarioweb
			AND mp_tablatel = :tablatel
			AND mp_tablapadreid = :tablapadreid
			AND mp_tipo = :tipo
			AND mp_estado <> 'B'";
$grilla = new Grid(10, 5);
if ($_REQUEST["r"] != "t")		// Si es readonly..
	$grilla->addColumn(new Column("E", 0, true, false, -1, "btnEditar", $url, "", -1, true, -1, "Editar"));
$grilla->addColumn(new Column("Tipo de Teléfono"));
$grilla->addColumn(new Column("Área"));
$grilla->addColumn(new Column("Número"));
$grilla->addColumn(new Column("Interno"));
$grilla->setColsSeparator(true);
$grilla->setOrderBy($ob);
$grilla->setPageNumber($pagina);
$grilla->setParams($params);
//$grilla->setRefreshIntoWindow(true);
$grilla->setRowsSeparator(true);
$grilla->setRowsSeparatorColor("#c0c0c0");
$grilla->setShowProcessMessage(true);
$grilla->setShowTotalRegistros(false);
$grilla->setSql($sql);
$grilla->setTableStyle("GridTableCiiu");
$grilla->setUseTmpIframe(true);
$grilla->Draw();
?>
<script type="text/javascript">
	with (window.parent.document) {
<?
if ($grilla->recordCount() > 0) {
?>	
	//	getElementById('divBtnAgregarTelefono').style.top = '0';
<?
}
?>
	parent.ajustarTamanoIframe(parent, 192);
	getElementById('divProcesando').style.display = 'none';
	getElementById('divContentGrid').innerHTML = document.getElementById('originalGrid').innerHTML;
	getElementById('divContentGrid').style.display = 'block';
	}
	
</script>