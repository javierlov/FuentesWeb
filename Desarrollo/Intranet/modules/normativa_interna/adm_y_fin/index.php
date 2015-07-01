<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normativa_interna/adm_y_fin/", ":: Administracion y Finanzas");
$list->addItem(new ItemList("/normativa-interna/adm_y_fin/formularios/index.php", "Formularios", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/adm_y_fin/instructivos/index.php", "Instructivos", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/adm_y_fin/manuales/index.php", "Manuales", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/adm_y_fin/procedimientos/index.php", "Procedimientos", "_self", false, true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna";
?>