<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normativa/prevencion/", ":: Prevención");
$list->addItem(new ItemList("Res2013_771.pdf", "Resolución SRT Nro. 771/2013", "_blank", true));
$list->addItem(new ItemList("Res2012_0246.pdf", "Resolución SRT Nro. 246/2012", "_blank", true));
$list->addItem(new ItemList("Res2011_0475.pdf", "Resolución SRT Nro. 475/2011", "_blank", true));
$list->addItem(new ItemList("Res2011_0399.pdf", "Resolución SRT Nro. 399/2011", "_blank", true));
$list->addItem(new ItemList("Res2011_0299.pdf", "Resolución SRT Nro. 299/2011", "_blank", true));
$list->addItem(new ItemList("Res2011_0065.pdf", "Resolución SRT Nro. 65/2011", "_blank", true));
$list->addItem(new ItemList("ResSRT_301.pdf", "Resolución SRT Nro. 301/2011", "_blank", true));
$list->addItem(new ItemList("ResSRT_550.pdf", "Resolución SRT Nro. 550/2011", "_blank", true));
$list->addItem(new ItemList("DispSRT_1.pdf", "Disposición 1/2011", "_blank", true));
$list->addItem(new ItemList("Res2010_0037.pdf", "Resolución SRT Nro. 37/2010", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normativa/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=31" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>