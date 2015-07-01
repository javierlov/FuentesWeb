<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


validarParametro(isset($_REQUEST["dia"]));

$dir = DATA_BOLETIN_OFICIAL_PATH.date("Y/m/");
$fecha = intval($_REQUEST["dia"])." de ".getMonthName($_REQUEST["mes"])." de ".$_REQUEST["ano"];

$list = new ListOfItems("", "Bolet�n Oficial del d�a ".$fecha);

$list->addItem(new ItemList("/boletin-oficial-ver/".$_REQUEST["ano"]."/".$_REQUEST["mes"]."/".$_REQUEST["dia"]."/1", "Primera Secci�n: Legislaci�n y Avisos Oficiales", "_blank"));
$list->addItem(new ItemList("/boletin-oficial-ver/".$_REQUEST["ano"]."/".$_REQUEST["mes"]."/".$_REQUEST["dia"]."/2", "Segunda Secci�n: Comerciales/Judiciales", "_blank"));
$list->addItem(new ItemList("/boletin-oficial-ver/".$_REQUEST["ano"]."/".$_REQUEST["mes"]."/".$_REQUEST["dia"]."/3", "Tercera Secci�n: Contrataciones", "_blank"));

$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/boletin_oficial/images/flecha.gif");
$list->setTitleAlign("center");
$list->draw();
?>
<a href="/boletin-oficial"><input class="btnVolver" type="button" value="" /></a>