<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/rrhh/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("ajustes_salariales_y_recategorizaciones.pdf", "Ajustes Salariales y Recategorizaciones", "_blank", true));
$list->addItem(new ItemList("gestion_de_reintegros.pdf", "Gestión de Reintegros Gastos de Guardería", "_blank", true));
$list->addItem(new ItemList("capacitacion_y_desarrollo.pdf", "Capacitación y Desarrollo", "_blank", true));
$list->addItem(new ItemList("gestion_de_reintegros_por_gastos_de_medicacion_cronica.pdf", "Gestión de Reintegros por Gastos de Medicación Crónica", "_blank", true));
$list->addItem(new ItemList("seleccion_incorporacion_transferencia_y_promocion_de_personal.pdf", "Selección, Incorporación, Transferencia y Promoción de Personal", "_blank", true));
$list->addItem(new ItemList("autorizacion_y_liquidacion_de_horas_extras.pdf", "Autorización y Liquidación de Horas Extras", "_blank", true));
$list->addItem(new ItemList("comunicacion_interna.pdf", "Comunicación Interna", "_blank", true));
$list->addItem(new ItemList("liquidacion_de_haberes.pdf", "Liquidación de Haberes", "_blank", true));
$list->setCols(1);
$list->setColsWidth(390);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=rrhh/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>