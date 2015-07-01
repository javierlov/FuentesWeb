<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/corporativa/politicas/", ":: Pol�ticas");
$list->addItem(new ItemList("politica_de_inversiones.pdf", "Inversiones", "_blank", true));
$list->addItem(new ItemList("po-001_tipo_de_normativa.pdf", "Normativa: Tipo, Alcance y �mbito de Aplicaci�n", "_blank", true));
$list->addItem(new ItemList("PO-005_Politica_de_Calidad.pdf", "Pol�tica de Calidad", "_blank", true));
$list->addItem(new ItemList("politica_de_suscripcion.pdf", "Pol�tica de Suscripci�n", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/corporativa/index.php";
?>
