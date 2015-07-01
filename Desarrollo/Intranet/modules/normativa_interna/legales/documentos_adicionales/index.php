<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/legales/documentos_adicionales/", ":: Documentos Adicionales");
$list->addItem(new ItemList("LEY_25246.pdf", "Ley 25.246", "_blank", true));
$list->addItem(new ItemList("R125_2009_anexoI.pdf", "Resolución UIF Nº 125/2009", "_blank", true));
$list->addItem(new ItemList("res_11_2011.pdf", "Resolución UIF N° 11/2011", "_blank", true));
$list->addItem(new ItemList("RES_230-2011_UIF.pdf", "Resolución UIF N° 230/2011", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/legales/index.php";
?>