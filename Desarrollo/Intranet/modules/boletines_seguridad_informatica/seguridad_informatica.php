<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


$files = array();
$ano = date("Y");
$mes = date("m");
$dir = DATA_SEGURIDAD_INFORMATICA_PATH.date("Y/m/");
$relativeDir = DATA_SEGURIDAD_INFORMATICA_RELATIVE_PATH.date("Y/m/");
if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($file = readdir($gd)) !== false)
			if (($file != ".") and ($file != ".."))
				array_push($files, $file);
		closedir($gd);
	}
rsort($files, SORT_NUMERIC);


$list = new ListOfItems("", "Boletines del Mes");
foreach ($files as $value) {
	$arr = explode(".", $value);
	$list->addItem(new ItemList($relativeDir.$value, "Boletín Nº ".$arr[0], "_blank"));
}

$list->addItem(new ItemList("/boletin-seguridad-informatica/anteriores", "Boletínes Anteriores"));

$list->setCols(1);
$list->setImagePath("/modules/normativa_interna/images/item.bmp");
$list->draw();
?>