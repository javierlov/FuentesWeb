<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/comercial/", ":: Comercial");
$list->addItem(new ItemList("/normativa-interna/comercial/manuales/index.php", "Manuales", "_self", false, true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna";
?>