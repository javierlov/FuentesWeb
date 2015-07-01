<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/analisis_y_control_de_gestion/", ":: Procedimientos");
$list->addItem(new ItemList("atencion_al_publico.pdf", "Atención al Público", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/analisis_y_control_de_gestion/index.php";
?>