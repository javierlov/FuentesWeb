<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/legales/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("le-03_Gestion_de_Sumarios_de_la_SRT.pdf", "Gestión de Sumarios de la SRT", "_blank", true));
$list->addItem(new ItemList("LE-02_Juicios_Valuacion_Final.pdf", "Juicios: Valuación y Control", "_blank", true));
$list->addItem(new ItemList("le-01_mediaciones_y_juicios.pdf", "Mediaciones, Demandas y Juicios", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/legales/index.php";
?>