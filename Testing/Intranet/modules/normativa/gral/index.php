<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normativa/gral/", ":: General");
$list->addItem(new ItemList("ResSRT_2553-2013.pdf", "Res. SRT 2553/13 Atenci�n al p�blico", "_blank", true));
$list->addItem(new ItemList("ResSSN_35550_responsabilidad_civil.pdf", "Resoluci�n SSN Nro. 35.550/2011", "_blank", true));
$list->addItem(new ItemList("disposicion2-2011_tramites_en_comisiones_medicas.pdf", "Disposici�n SRT Nro. 2/2011", "_blank", true));
$list->addItem(new ItemList("ResSRT_1313.pdf", "Resoluci�n SRT Nro. 1313/2011", "_blank", true));
$list->addItem(new ItemList("ResAFIP_3367.pdf", "Resoluci�n AFIP 3367", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normativa/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=31" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>