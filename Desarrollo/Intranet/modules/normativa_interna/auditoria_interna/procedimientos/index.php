<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/auditoria_interna/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("Desarrollo_de_Informes.pdf", "Desarrollo de Informes de Auditoría Interna", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/auditoria_interna/index.php";
?>