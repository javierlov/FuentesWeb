<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normativa/prest_dinerarias/", ":: Prestaciones Dinerarias");
$list->addItem(new ItemList("Nota5698_resolucion_1268_11.pdf", "Nota Ref. Resolución SRT N° 1286/2011", "_blank", true));
$list->addItem(new ItemList("ResSRT_1286.pdf", "Resolución SRT N° 1286/2011", "_blank", true));
$list->addItem(new ItemList("Res2010_0983.pdf", "Resolución SRT N° 983/2010", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-externa";
?>