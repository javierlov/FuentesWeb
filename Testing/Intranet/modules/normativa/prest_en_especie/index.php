<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normativa/prest_en_especie/", ":: Prestaciones en Especie");
$list->addItem(new ItemList("Dec_492014_Enf_Prof.pdf", "Dec 49/2014 Enfermedades Profesionales", "_blank", true));
$list->addItem(new ItemList("Res2013_762.pdf", "Resolución SRT Nro. 762/2013", "_blank", true));
$list->addItem(new ItemList("Res2011_1068.pdf", "Resolución SRT Nro. 1068/2011", "_blank", true));
$list->addItem(new ItemList("Res2010_1240.pdf", "Resolución SRT Nro. 1240/2010", "_blank", true));
$list->addItem(new ItemList("Res2010_1314.pdf", "Resolución SRT Nro. 1314/2010", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normativa/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=31" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>