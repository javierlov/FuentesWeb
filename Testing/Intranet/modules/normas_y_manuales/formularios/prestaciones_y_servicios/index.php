<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/formularios/prestaciones_y_servicios/", ":: Prestaciones y Servicios");
$list->addItem(new ItemList("Solicitud_incorporacion_prestadores.pdf", "Solicitud de Incorporación de Prestadores", "_blank", true));
$list->addItem(new ItemList("solicitud_aprobacion_presupuesto.doc", "Solicitud de Aprobación de Presupuesto", "_blank", true));
$list->addItem(new ItemList("notificacion_examenes_de_salud.pdf", "Notificación de Exámenes de Salud", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=formularios/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>