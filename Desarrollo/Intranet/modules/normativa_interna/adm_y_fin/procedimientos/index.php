<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/adm_y_fin/procedimientos/", ":: Procedimientos");
$list->addItem(new ItemList("AF-14_Fondo_fijo.pdf", "ABM de Fondo Fijo", "_blank", true));
$list->addItem(new ItemList("AF-07_Audit_Med_de_Fact_y_Liq_de_Prest_Especie.pdf", "Auditoria Médica de Facturas y Liq. de Prest. en Especie", "_blank", true));
$list->addItem(new ItemList("AF-09_V.01_Cobranzas_y_su_Registracion.pdf", "Cobranzas", "_blank", true));
$list->addItem(new ItemList("AF-10_V.01_Liquidacion_y_Pago_de_Comisiones.pdf", "Comisiones", "_blank", true));
$list->addItem(new ItemList("AF-01_compras_y_contrataciones.pdf", "Compras y Contrataciones", "_blank", true));
$list->addItem(new ItemList("af_04_rev_08_procedimiento_fondo_fijo_de_caja_definitiva_rc.pdf", "Fondo Fijo de Caja", "_blank", true));
$list->addItem(new ItemList("AF-11_Guarda_y_Digitalizacion.pdf", "Guarda y Digit.", "_blank", true));
$list->addItem(new ItemList("AF-03_Medios_de_pago.pdf", "Medios de Pago", "_blank", true));
$list->addItem(new ItemList("AF-08_Pago_de_Prestaciones.pdf", "Pago de Prestaciones", "_blank", true));
$list->addItem(new ItemList("af_17_rev_01_elaboracion_del_presupuesto_y_su_control_definitiva_rc.pdf", "Presupuesto", "_blank", true));
$list->addItem(new ItemList("AF-02_Gastos_de_Viajes_y_de_Representacion_en_Comision_de_Servicio.pdf", "Reintegros y Anticipos por Gastos de Viaje", "_blank", true));
$list->addItem(new ItemList("AF-16_telefonia.pdf", "Telefonía", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/adm_y_fin/index.php";
?>