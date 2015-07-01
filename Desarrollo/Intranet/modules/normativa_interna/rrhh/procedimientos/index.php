<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/rrhh/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("ajustes_salariales_y_recategorizaciones.pdf", "Ajustes Salariales y Recategorizaciones", "_blank", true));
$list->addItem(new ItemList("autorizacion_y_liquidacion_de_horas_extras.pdf", "Autorizaci�n y Liquidaci�n de Horas Extras", "_blank", true));
$list->addItem(new ItemList("capacitacion_y_desarrollo.pdf", "Capacitaci�n y Desarrollo", "_blank", true));
$list->addItem(new ItemList("comunicacion_interna.pdf", "Comunicaci�n Interna", "_blank", true));
$list->addItem(new ItemList("gestion_de_reintegros.pdf", "Gesti�n de Reintegros Gastos de Guarder�a", "_blank", true));
$list->addItem(new ItemList("gestion_de_reintegros_por_gastos_de_medicacion_cronica.pdf", "Gesti�n de Reintegros por Gastos de Medicaci�n Cr�nica", "_blank", true));
$list->addItem(new ItemList("liquidacion_de_haberes.pdf", "Liquidaci�n de Haberes", "_blank", true));
$list->addItem(new ItemList("seleccion_incorporacion_transferencia_y_promocion_de_personal.pdf", "Selecci�n, Incorporaci�n, Transferencia y Promoci�n de Personal", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/rrhh/index.php";
?>