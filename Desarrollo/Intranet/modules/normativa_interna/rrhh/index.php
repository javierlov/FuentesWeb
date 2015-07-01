<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/rrhh/", ":: RR.HH.");
$list->addItem(new ItemList("/normativa-interna/rrhh/instructivos/index.php", "Instructivos", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/rrhh/procedimientos/index.php", "Procedimientos", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/rrhh/formularios/index.php", "Formularios", "_self", false, true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna";
?>