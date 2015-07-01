<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/comercial/manuales/", ":: Manuales");
$list->addItem(new ItemList("Sistema_Integrado_de_Comunicaciones.pdf", "Sistema Integrado de Comunicaciones", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/comercial/index.php";
?>