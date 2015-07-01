<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/adm_y_fin/instructivos/", ":: Instructivos");
$list->addItem(new ItemList("administracion_de_valores.pdf", "Administración de Valores", "_blank", true));
$list->addItem(new ItemList("afi_06_rev.01_instructivo_elaboracion_del_presupuesto_y_su_control.pdf", "Elaboración de Presupuesto", "_blank", true));
$list->addItem(new ItemList("envio_de_bolsines_a_delegaciones.pdf", "Envío de Bolsines a Delegaciones", "_blank", true));
$list->addItem(new ItemList("guarda_y_digitalizacion.pdf", "Guarda y Digitalización", "_blank", true));
$list->addItem(new ItemList("liquidacion_de_impuestos.pdf", "Liquidación de Impuestos", "_blank", true));
$list->addItem(new ItemList("traslado_documentacion_entre_edificios.pdf", "Traslado de Documentación", "_blank", true));
$list->addItem(new ItemList("uso_de_vehiculos.pdf", "Uso de Vehículos", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/adm_y_fin/index.php";
?>