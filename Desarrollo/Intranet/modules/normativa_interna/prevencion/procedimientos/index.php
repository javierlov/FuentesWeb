<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/prevencion/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("PV-01_Prevencion_de_riesgos.pdf", "Prevención de riesgos", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/prevencion/index.php";
?>