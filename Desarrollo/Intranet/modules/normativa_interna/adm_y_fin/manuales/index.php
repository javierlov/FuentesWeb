<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/adm_y_fin/manuales/", ":: Manuales");
$list->addItem(new ItemList("Manual_de_usuario_Generacion_de_Solicitud_Interna_por_IP.pdf", "Compras - Manual de usuario Generacion de  Solicitud Interna por IP", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/adm_y_fin/index.php";
?>