<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/tecnica/procedimientos/", ":: Técnica");
$list->addItem(new ItemList("TE-05_V.01_Certificados_de_Cobertura.pdf", "Certificados de Cobertura", "_blank", true));
$list->addItem(new ItemList("TE-02_V01_Emision_Endosos_y_Baja_de_Contratos_de_Cobertura.pdf", "Endosos y Contratos", "_blank", true));
$list->addItem(new ItemList("TE-01_V.01_Recepcion_de_Solicitudes_de_Afiliacion.pdf", "Solicitudes de Afiliación", "_blank", true));
$list->addItem(new ItemList("TE-04_V.01_Tarifas_Determinacion_y_Aprobacion.pdf", "Tarifas", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/tecnica/index.php";
?>