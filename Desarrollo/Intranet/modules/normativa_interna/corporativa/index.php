<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_manuales/corporativa/", ":: Corporativa");
$list->addItem(new ItemList("/normativa-interna/corporativa/codigos/index.php", "Códigos", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/corporativa/manuales/index.php", "Manuales", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/corporativa/politicas/index.php", "Políticas", "_self", false, true));
$list->addItem(new ItemList("/normativa-interna/corporativa/procedimientos/index.php", "Procedimientos", "_self", false, true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna";
?>