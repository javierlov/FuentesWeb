<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems("/modules/normativa_interna/corporativa/codigos/", ":: C�digos");
$list->addItem(new ItemList("codigo.php", "C�digo de Conducta", "_blank"));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/corporativa/index.php";
?>
