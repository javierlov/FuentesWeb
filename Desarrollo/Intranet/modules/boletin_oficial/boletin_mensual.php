<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


validarParametro(isset($_REQUEST["mes"]));

$dir = DATA_BOLETIN_OFICIAL_PATH.date($_REQUEST["ano"]."/".$_REQUEST["mes"]."/");
$folders = array();
$list = new ListOfItems("", "Boletines ".GetMonthName($_REQUEST["mes"])." ".$_REQUEST["ano"]);

if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($dia = readdir($gd)) !== false)
			if (($dia != ".") and ($dia != ".."))
				array_push($folders, $dia);
		closedir($gd);
	}
rsort($folders);		// Ordeno el array descendentemente..


foreach($folders as $value)
	$list->addItem(new ItemList("/boletin-oficial/".$_REQUEST["ano"]."/".$_REQUEST["mes"]."/".$value, "Boletín Oficial del día ".intval($value)." de ".getMonthName($_REQUEST["mes"])." de ".$_REQUEST["ano"], "_self", false, true));

$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/boletin_oficial/images/flecha.gif");
$list->setTitleAlign("center");
$list->draw();
?>
<a href="/boletin-oficial-historico"><input class="btnVolver" type="button" value="" /></a>