<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normativa/afiliaciones/", ":: Afiliaciones");
$list->addItem(new ItemList("Res2011_1313.pdf", "Resolución SRT Nro. 1313/2011", "_blank", true));
$list->addItem(new ItemList("Res2010_0741.pdf", "Resolución SRT Nro. 741/2010", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normativa/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=31" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>