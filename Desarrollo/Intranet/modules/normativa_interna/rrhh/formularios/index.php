<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");

$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/rrhh/formularios/", ":: Formularios");

$list->addItem(new ItemList("Solicitud_de_Alta_Contrato.doc", "Solicitud de Alta Contrato", "_blank", true));
$list->addItem(new ItemList("Solicitud_de_Modificacion_Contrato.doc", "Solicitud de Modificacion Contrato", "_blank", true));
$list->addItem(new ItemList("Solicitud_de_Baja_Contrato.doc", "Solicitud de Baja Contrato", "_blank", true));
$list->addItem(new ItemList("Legajo_proveedor.xls", "Legajo Proveedor", "_blank", true));
$list->addItem(new ItemList("Informacion_contrato_carta_oferta_proveedor.xls", "Información Contrato Carta Oferta Proveedor", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/rrhh/index.php";
?>