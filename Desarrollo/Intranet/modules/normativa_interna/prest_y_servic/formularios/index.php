<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/prest_y_servic/formularios/", ":: Formularios");
$list->addItem(new ItemList("notificacion_examenes_de_salud.pdf", "Notificaci�n de Ex�menes de Salud", "_blank", true));
$list->addItem(new ItemList("solicitud_de_aprobacion_de_presupuesto.doc", "Solicitud de Aprobaci�n de Presupuesto", "_blank", true));
$list->addItem(new ItemList("Solicitud_incorporacion_prestadores.pdf", "Solicitud de Incorporaci�n de Prestadores", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/prest_y_servic/index.php";
?>