<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="center">
<?
$list = new ListOfItems(STORAGE_PATH."descargables/manual_induccion/", ":: Manual de Inducción");
$list->addItem(new ItemList("Lineamientos_RH2014.pdf", "Lineamientos RRHH 2014", "_blank", true));
$list->addItem(new ItemList("Manual_de_induccion.pdf", "Manual", "_blank", true));
$list->setCols(1);
$list->setColsWidth(360);
$list->setImagePath("/modules/descargables/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="index.php?pageid=37" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>