<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00A4E4" vlink="#00A4E4" alink="#00A4E4">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/corporativa/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("GG-02_Plan_de_continuidad_del_negocio.pdf", "Plan de Continuidad del Negocio", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=corporativa/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>