<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/analisis_y_control_de_gestion/", ":: An�lisis y Control de Gesti�n");
$list->addItem(new ItemList("/index.php?pageid=40&fldr=analisis_y_control_de_gestion/procedimientos/index.php", "Procedimientos", "_self", false, true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/item.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>