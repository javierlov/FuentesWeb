<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/prest_y_servic/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("PM-02_ABM_Codigos_OMS_CIE_10_Rev.01.pdf", "ABM de Códigos OMSCIE10", "_blank", true));
$list->addItem(new ItemList("pm-08_v.01_auditoria_medica.pdf", "Auditoría Médica", "_blank", true));
$list->addItem(new ItemList("PM-09_V.01_CEM.pdf", "CEM", "_blank", true));
$list->addItem(new ItemList("gestion_de_prestadores.pdf", "Gestión de Prestadores", "_blank", true));
$list->addItem(new ItemList("PM-04_Gestion_de_Siniestros.pdf", "Gestión de Siniestros", "_blank", true));
$list->addItem(new ItemList("PM-06_Incap_Permanentes.pdf", "Incapacidades", "_blank", true));
$list->addItem(new ItemList("PM-05_V.01_Investigacion_de_Siniestros.pdf", "Investigación de Siniestros", "_blank", true));
$list->addItem(new ItemList("PM-03_V.02_Mesa_de_Ingreso_de_Datos.pdf", "Mesa de Ing. de Datos", "_blank", true));
$list->addItem(new ItemList("PM-01V.01_Prestaciones_Dinerarias.pdf", "Prestaciones Dinerarias", "_blank", true));
$list->addItem(new ItemList("PM-07_V.01_Recalificacion_Profesional.pdf", "Recalificación Profesional", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/prest_y_servic/index.php";
?>