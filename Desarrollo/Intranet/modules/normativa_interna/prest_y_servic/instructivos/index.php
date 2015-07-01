<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/prest_y_servic/instructivos/", ":: Instructivos");
$list->addItem(new ItemList("FFEP.pdf", "Fondo Fiduciario- Enfermedades Profesionales No Listadas", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/prest_y_servic/index.php";
?>