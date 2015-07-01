<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normativa/afiliaciones/", ":: Afiliaciones");
$list->addItem(new ItemList("Res2011_1313.pdf", "Resolución SRT N° 1313/2011", "_blank", true));
$list->addItem(new ItemList("Res2010_0741.pdf", "Resolución SRT N° 741/2010", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-externa";
?>