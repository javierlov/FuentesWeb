<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/analisis_y_control_de_gestion/", ":: Procedimientos");
$list->addItem(new ItemList("AG-002_elaboracion_actualizacion_aprobacion.pdf", "Normativa: elaboración, actualización y aprobación", "_blank", true));
$list->addItem(new ItemList("AG-01_atencion_al_publico.pdf", "Atención al Público", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=analisis_y_control_de_gestion/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>