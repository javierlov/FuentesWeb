<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/corporativa/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("elaboracion_actualizacion_aprobacion.pdf", "Normativa: elaboración, actualización y aprobación", "_blank", true));
$list->addItem(new ItemList("GG-02_Plan_de_continuidad_del_negocio.pdf", "Plan de Continuidad del Negocio", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/corporativa/index.php";
?>