<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/adm_y_fin/instructivos/", ":: Instructivos");
$list->addItem(new ItemList("traslado_documentacion_entre_edificios.pdf", "Traslado de Documentación", "_blank", true));
$list->addItem(new ItemList("uso_de_vehiculos.pdf", "Uso de Vehículos", "_blank", true));
$list->addItem(new ItemList("liquidacion_de_impuestos.pdf", "Liquidación de Impuestos", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=adm_y_fin/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>