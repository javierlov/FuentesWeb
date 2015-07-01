<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


$files = array();
$ano = date("Y");
$mes = date("m");
$dir = DATA_SEGURIDAD_INFORMATICA_PATH;
$relativeDir = DATA_SEGURIDAD_INFORMATICA_RELATIVE_PATH;
if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($file = readdir($gd)) !== false)
			if (($file != ".") and ($file != ".."))
				if (is_dir($dir.$file))
					if ($gd2 = opendir($dir.$file)) {
						while (($file2 = readdir($gd2)) !== false)
							if (($file2 != ".") and ($file2 != ".."))
								array_push($files, $file."_".$file2);
						closedir($gd2);
					}
		closedir($gd);
	}
rsort($files);


$list = new ListOfItems("", "");
foreach ($files as $value) {
	$arr = explode("_", $value);
	$list->addItem(new ItemList("/boletin-seguridad-informatica/listado-mensual/".$arr[0]."/".$arr[1], "Boletines del Mes de ".getMonthName($arr[1])." de ".$arr[0]));
}
$list->setCols(1);
$list->setColsWidth(600);
$list->setImagePath("/modules/normativa_interna/images/item.bmp");
$list->setShowTitle(false);
$list->draw();
?>
<a href="/boletin-seguridad-informatica"><input class="btnVolver" type="button" value="" /></a>