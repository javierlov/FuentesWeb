<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."sistemas/manuales/");
$list->addItem(new ItemList("access97.pdf", "Access 97", "_blank", true));
$list->addItem(new ItemList("excel97.pdf", "Excel 97", "_blank", true));
$list->addItem(new ItemList("excel2000.pdf", "Excel 2000", "_blank", true));
$list->addItem(new ItemList("P_ART_Instructivo_Lexmark_2010.pdf", "Instructivo Impresora Lexmark 2010", "_blank", true));
$list->addItem(new ItemList("powerpoint97.pdf", "Power Point 97", "_blank", true));
$list->addItem(new ItemList("word97.pdf", "Word 97", "_blank", true));
$list->addItem(new ItemList("word2000.pdf", "Word 2000", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/instructivos/images/download.bmp");
$list->draw();
?>