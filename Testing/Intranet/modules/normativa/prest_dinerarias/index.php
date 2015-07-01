<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normativa/prest_dinerarias/", ":: Prestaciones Dinerarias");
$list->addItem(new ItemList("ResSRT_1286.pdf", "Resolución SRT Nro. 1286/2011", "_blank", true));
$list->addItem(new ItemList("Res2010_0983.pdf", "Resolución SRT Nro. 983/2010", "_blank", true));
$list->addItem(new ItemList("Nota5698_resolucion_1268_11.pdf", "Nota Ref. Resolución SRT Nro. 1286/2011", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normativa/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=31" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>