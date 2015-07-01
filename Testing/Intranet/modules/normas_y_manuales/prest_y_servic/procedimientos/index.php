<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/prest_y_servic/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("PM-01V.01_Prestaciones_Dinerarias.pdf", "Prest. Dinerarias", "_blank", true));
$list->addItem(new ItemList("PM-02_ABM_Codigos_OMS_CIE_10_Rev.01.pdf", "ABM de Códigos OMSCIE10", "_blank", true));
$list->addItem(new ItemList("PM-03_V.02_Mesa_de_Ingreso_de_Datos.pdf", "Mesa de Ing. de Datos", "_blank", true));
$list->addItem(new ItemList("PM-04_Gestion_de_Siniestros.pdf", "Gestión de Siniestros", "_blank", true));
$list->addItem(new ItemList("PM-05_V.01_Investigacion_de_Siniestros.pdf", "Inv. de Siniestros", "_blank", true));
$list->addItem(new ItemList("PM-07_V.01_Recalificacion_Profesional.pdf", "Recalificación Profesional", "_blank", true));
$list->addItem(new ItemList("pm-08_v.01_auditoria_medica.pdf", "Auditoría Médica", "_blank", true));
$list->addItem(new ItemList("PM-09_V.01_CEM.pdf", "CEM", "_blank", true));
$list->addItem(new ItemList("PM-06_Incap_Permanentes.pdf", "Incapacidades Permanentes", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=prest_y_servic/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>