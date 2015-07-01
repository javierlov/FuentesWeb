<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/adm_y_fin/formularios/", ":: Formularios");
$list->addItem(new ItemList("Actuarial_Imput.xls", "Actuarial Imput", "_blank", true));
$list->addItem(new ItemList("Asientos.xls", "Asientos", "_blank", true));
$list->addItem(new ItemList("Comparativo_de_precios_y_condiciones.xls", "Comparativo de Precios y Condiciones", "_blank", true));
$list->addItem(new ItemList("Control_Gastos.xls", "Control Gastos", "_blank", true));
$list->addItem(new ItemList("Control_Presupuestario_Grupo.xls", "Control Presupuestario Grupo", "_blank", true));
$list->addItem(new ItemList("Comparativo_de_Antecedentes.doc", "Comparativo de Antecedentes", "_blank", true));
$list->addItem(new ItemList("Cronograma_Presupuesto_Trianual.xls", "Cronograma Presupuesto Trianual", "_blank", true));
$list->addItem(new ItemList("Emision_Cobranza.xls", "Emisión Cobranza", "_blank", true));
$list->addItem(new ItemList("envio_de_correspondencia_por_bolsin.xls", "Envío de Correspondencia por Bolsín", "_blank", true));
$list->addItem(new ItemList("envio_de_correspondencia_por_bolsin_a_delegacion.xls", "Envío de Correspondencia por Bolsín a Delegación", "_blank", true));
$list->addItem(new ItemList("Estados.xls", "Estados", "_blank", true));
$list->addItem(new ItemList("Evaluacion_de_Calidad_de_Proveedor.doc", "Evaluación de Proveedores", "_blank", true));
$list->addItem(new ItemList("Financiero.xls", "Financiero", "_blank", true));
$list->addItem(new ItemList("Gastos_Explotacion.xls", "Gastos Explotación", "_blank", true));
$list->addItem(new ItemList("Gtos_explotacion_x_cc_Imput.xls", "Gastos Explotación x cc Imput", "_blank", true));
$list->addItem(new ItemList("Gtos_personal_x_cc_Imput.xls", "Gastos Personal x cc Imput", "_blank", true));
$list->addItem(new ItemList("Legales_Litigios_Presupuesto_Imput.xls", "Legales Litigios Presupuesto Imput", "_blank", true));
$list->addItem(new ItemList("Legales_Sumarios_Presupuesto_Imput.xls", "Legales Sumarios Presupuesto Imput", "_blank", true));
$list->addItem(new ItemList("Presentacion_Grupo.xls", "Presentación Grupo", "_blank", true));
$list->addItem(new ItemList("Prevencion_Imput.xls", "Prevención Imput", "_blank", true));
$list->addItem(new ItemList("Siniestros.xls", "Siniestros", "_blank", true));
$list->addItem(new ItemList("Siniestros_imput.xls", "Siniestros Imput", "_blank", true));
$list->addItem(new ItemList("Solicitud_de_contratacion_de_bys.xls", "Solicitud de contratación de bys", "_blank", true));

$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna/adm_y_fin/index.php";
?>