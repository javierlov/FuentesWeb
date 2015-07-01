<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normativa/gral/", ":: General");

$list->addItem(new ItemList("LEY_25246.pdf", "Ley 25.246", "_blank", true));
$list->addItem(new ItemList("ley_24557.pdf", "Ley de Riesgos del Trabajo 24.557", "_blank", true));
$list->addItem(new ItemList("Ley_26773.pdf", "Ley de Régimen de reordenamiento de la LRT", "_blank", true));
$list->addItem(new ItemList("Decreto472-2014.pdf", "Decreto N° 472/2014", "_blank", true));
$list->addItem(new ItemList("decreto_1694_2009.pdf", "Decreto 1694/2009", "_blank", true));
$list->addItem(new ItemList("R125_2009_anexoI.pdf", "Resolución UIF Nº 125/2009", "_blank", true));
$list->addItem(new ItemList("res_11_2011.pdf", "Resolución UIF N° 11/2011", "_blank", true));
$list->addItem(new ItemList("RES_230-2011_UIF.pdf", "Resolución UIF N° 230/2011", "_blank", true));
$list->addItem(new ItemList("ResAFIP_3367.pdf", "Resolución AFIP N° 3367", "_blank", true));
$list->addItem(new ItemList("ResSRT_2553-2013.pdf", "Resolución SRT N° 2553/2013 - Atención al Público", "_blank", true));
$list->addItem(new ItemList("ResSRT_1313.pdf", "Resolución SRT N° 1313/2011", "_blank", true));
$list->addItem(new ItemList("ResSRT_733_2008.pdf", "Resolución SRT N° 733/2008", "_blank", true));
$list->addItem(new ItemList("ResSSN_35550_responsabilidad_civil.pdf", "Resolución SSN N° 35.550/2011", "_blank", true));
$list->addItem(new ItemList("disposicion2-2011_tramites_en_comisiones_medicas.pdf", "Disposición SRT N° 2/2011", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-externa";
?>