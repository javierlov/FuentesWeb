<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normativa_interna/analisis_y_control_de_gestion/", ":: Análisis y Control de Gestión");
$list->addItem(new ItemList("/normativa-interna/analisis_y_control_de_gestion/procedimientos/index.php", "Procedimientos", "_self", false, true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna";
?>