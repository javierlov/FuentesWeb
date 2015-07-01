<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/rrhh/instructivos/", ":: Instructivos");
$list->addItem(new ItemList("instructivo_teletrabajo.pdf", "Teletrabajo", "_blank", true));
$list->addItem(new ItemList("contratos_de_locacion.pdf", "Contratos de Locación", "_blank", true));
$list->addItem(new ItemList("reintegro_por_gastos_gtes_subgtes_jefes.pdf", "Reintegros por Gastos", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/rrhh/index.php";
?>