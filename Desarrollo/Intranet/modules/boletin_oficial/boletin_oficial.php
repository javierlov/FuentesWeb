<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$ano = date("Y");
$dir = DATA_BOLETIN_OFICIAL_PATH.date("Y/m/");
$folders = array();
$list = new ListOfItems("");
$mes = date("m");

if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($dia = readdir($gd)) !== false)
			if (($dia != ".") and ($dia != ".."))
				array_push($folders, $dia);
		closedir($gd);
	}
rsort($folders);		// Ordeno el array por fecha descendente..


foreach($folders as $value)
	$list->addItem(new ItemList("/boletin-oficial/".$ano."/".$mes."/".$value, "Boletín Oficial del día ".intval($value)." de ".getMonthName($mes)." de ".$ano, "_self", false, true));
$list->addItem(new ItemList("/boletin-oficial-historico", "Boletines Anteriores", "_self", false, true));

$list->setCols(2);
$list->setColsWidth(320);
$list->setShowTitle(false);
$list->setImagePath("/modules/boletin_oficial/images/flecha.gif");
$list->draw();
?>