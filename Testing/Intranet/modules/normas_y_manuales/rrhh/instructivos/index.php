<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/rrhh/instructivos/", ":: Instructivos");
$list->addItem(new ItemList("instructivo_teletrabajo.pdf", "Teletrabajo", "_blank", true));
$list->setCols(1);
$list->setColsWidth(390);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=rrhh/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>