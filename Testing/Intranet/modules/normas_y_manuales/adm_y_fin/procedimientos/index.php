<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/adm_y_fin/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("AF-09_V.01_Cobranzas_y_su_Registracion.pdf", "Cobranzas", "_blank", true));
$list->addItem(new ItemList("AF-10_V.01_Liquidacion_y_Pago_de_Comisiones.pdf", "Comisiones", "_blank", true));
$list->addItem(new ItemList("AF-11_Guarda_y_Digitalizacion.pdf", "Guarda y Digit.", "_blank", true));
$list->addItem(new ItemList("AF-08_Pago_de_Prestaciones.pdf", "Pago de Prestaciones", "_blank", true));
$list->addItem(new ItemList("AF-02_Gastos_de_Viajes_y_de_Representacion_en_Comision_de_Servicio.pdf", "Gastos de Viaje", "_blank", true));
$list->addItem(new ItemList("AF-02_Gastos_de_Viajes_y_de_Representacion_en_Comision_de_Servicio_2013.pdf", "Gastos de Viaje 2013", "_blank", true));
$list->addItem(new ItemList("AF-16_telefonia.pdf", "Telefonía", "_blank", true));
$list->addItem(new ItemList("AF-14_Fondo_fijo.pdf", "ABM de Fondo Fijo", "_blank", true));
$list->addItem(new ItemList("AF-04_ Fondo_fijo_de_caja.pdf", "Fondo Fijo de Caja", "_blank", true));
$list->addItem(new ItemList("AF-03_Medios_de_pago.pdf", "Medios de Pago", "_blank", true));
$list->addItem(new ItemList("AF-01_compras_y_contrataciones.pdf", "Compras y Contrataciones", "_blank", true));
$list->addItem(new ItemList("AF-07_Audit_Med_de_Fact_y_Liq_de_Prest_Especie.pdf", "Auditoria Médica de Facturas y Liq. de Prest. en Especie", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=adm_y_fin/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>