<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/tecnica/instructivos/", ":: Instructivos");
$list->addItem(new ItemList("Minuta_comite_de_negocios.pdf", "Minuta Comité de Negocios", "_blank", true));
$list->addItem(new ItemList("revision_de_precios.pdf", "Revisión de Precios", "_blank", true));
$list->addItem(new ItemList("cotizaciones_especiales.pdf", "Cotizaciones Especiales", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/tecnica/index.php";
?>

