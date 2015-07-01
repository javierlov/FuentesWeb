<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/prest_y_servic/", ":: Prestaciones y Servicios");
$list->addItem(new ItemList("/normativa-interna/prest_y_servic/formularios/index.php", "Formularios", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/prest_y_servic/instructivos/index.php", "Instructivos", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/prest_y_servic/procedimientos/index.php", "Procedimientos", "_self", false, true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna";
?>