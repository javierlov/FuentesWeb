<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/tecnica/procedimientos/", ":: Técnica");
$list->addItem(new ItemList("TE-01_V.01_Recepcion_de_Solicitudes_de_Afiliacion.pdf", "Solicitudes de Afiliación", "_blank", true));
$list->addItem(new ItemList("TE-02_V01_Emision_Endosos_y_Baja_de_Contratos_de_Cobertura.pdf", "Endosos y Contratos", "_blank", true));
$list->addItem(new ItemList("TE-04_V.01_Tarifas_Determinacion_y_Aprobacion.pdf", "Tarifas", "_blank", true));
$list->addItem(new ItemList("TE-05_V.01_Certificados_de_Cobertura.pdf", "Certificados de Cobertura", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=tecnica/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>