<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normativa/prest_en_especie/", ":: Prestaciones en Especie");
$list->addItem(new ItemList("Dec_492014_Enf_Prof.pdf", "Decreto N° 49/2014 - Enfermedades Profesionales", "_blank", true));
$list->addItem(new ItemList("658_96_Listado_EP.pdf", "Dec. 658/96 Listado de EP", "_blank", true));
$list->addItem(new ItemList("Res2013_762.pdf", "Resolución SRT N° 762/2013", "_blank", true));
$list->addItem(new ItemList("Res2011_1068.pdf", "Resolución SRT N° 1068/2011", "_blank", true));
$list->addItem(new ItemList("Res2010_1314.pdf", "Resolución SRT N° 1314/2010", "_blank", true));
$list->addItem(new ItemList("Res2010_1240.pdf", "Resolución SRT N° 1240/2010", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-externa";
?>