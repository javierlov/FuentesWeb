<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/legales/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("le-01_mediaciones_y_juicios.pdf", "Mediaciones, Demandas y Juicios", "_blank", true));
$list->addItem(new ItemList("LE-02_Juicios_Valuacion_Final.pdf", "Juicios: Valuación y Control", "_blank", true));
$list->addItem(new ItemList("le-03_Gestion_de_Sumarios_de_la_SRT.pdf", "Gestión de Sumarios de la SRT", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=legales/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>