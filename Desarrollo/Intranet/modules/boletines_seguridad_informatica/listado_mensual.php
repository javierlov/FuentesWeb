<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


$files = array();
$ano = date("Y");
$mes = date("m");

if (!isset($_REQUEST["mes"]))
	$_REQUEST["mes"] = $mes;
if (!isset($_REQUEST["ano"]))
	$_REQUEST["ano"] = $ano;

$dir = DATA_SEGURIDAD_INFORMATICA_PATH.$_REQUEST["ano"]."/".$_REQUEST["mes"]."/";
$relativeDir = DATA_SEGURIDAD_INFORMATICA_RELATIVE_PATH.$_REQUEST["ano"]."/".$_REQUEST["mes"]."/";
if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($file = readdir($gd)) !== false)
			if (($file != ".") and ($file != ".."))
				array_push($files, $file);
		closedir($gd);
	}
rsort($files, SORT_NUMERIC);


$list = new ListOfItems("", "Boletines del Mes de ".getMonthName($_REQUEST["mes"])." de ".$_REQUEST["ano"]);
foreach ($files as $value) {
	$arr = explode(".", $value);
	$list->addItem(new ItemList($relativeDir.$value, "Boletín Nº ".$arr[0], "_blank"));
}
$list->setCols(1);
$list->setImagePath("/modules/normativa_interna/images/item.bmp");
$list->draw();
?>
<a href="/boletin-seguridad-informatica/anteriores"><input class="btnVolver" type="button" value="" /></a>