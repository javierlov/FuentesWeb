<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<title>Descargables</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body link="#00539B" vlink="#00539B" alink="#00539B">
		<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."descargables/formularios/", ":: Formularios");
$list->addItem(new ItemList("Acta_de_recepcion_entrega_de_vehiculos.doc", "Acta de Recepción-Entrega de Vehículos", "_blank", true));
$list->addItem(new ItemList("Afiliacion_Sindicato.doc", "Afiliación Sindicato", "_blank", true));
$list->addItem(new ItemList("Arqueo_de_Fondo_Fijo.xls", "Arqueo de Fondo Fijo", "_blank", true));
$list->addItem(new ItemList("Cambio_de_domicilio.doc", "Cambio de Domicilio", "_blank", true));
$list->addItem(new ItemList("carta_solicitud_de_documentacion_para_proveedor.doc", "Carta para Proveedores &#8211; Solicitud de Documentación", "_blank", true));
$list->addItem(new ItemList("Comprobante_de_caja.xls", "Comprobante de Caja", "_blank", true));
$list->addItem(new ItemList("Gastos_sin_Comprobantes.xls", "Detalle de Gastos sin Comprobante", "_blank", true));
$list->addItem(new ItemList("evaluacion_de_entrevista.doc", "Evaluación de Entrevista", "_blank", true));
$list->addItem(new ItemList("form_solicitud_inscripcion_proveedor.doc", "Formulario de Inscripción para Proveedores", "_blank", true));
$list->addItem(new ItemList("ModificacionProvinciaVida.jpg", "Formulario de Modificación Provincia Vida", "_blank", true));
$list->addItem(new ItemList("form_solicitud_de_pago_x_transferencia_electronica.pdf", "Formulario de Pago por Transferencia Electrónica", "_blank", true));
$list->addItem(new ItemList("Ganancias.pdf", "Ganancias", "_blank", true));
$list->addItem(new ItemList("Form572.pdf", "Instructivo Ganancias", "_blank", true));
$list->addItem(new ItemList("Orden_de_Comision_de_Servicio.doc", "Orden para la Realización de Comisiones de Servicio", "_blank", true));
$list->addItem(new ItemList("pedido_de_personal.doc", "Pedido de Personal", "_blank", true));
$list->addItem(new ItemList("perfil.doc", "Perfil", "_blank", true));
$list->addItem(new ItemList("Guarderia.xls", "Reintegro por Gastos de Guardería/Jardín", "_blank", true));
$list->addItem(new ItemList("Rendicion_Gastos_de_Viaje.doc", "Rendición de Gastos de Viaje", "_blank", true));
$list->addItem(new ItemList("Rendicion_Horas_extra.doc", "Rendición de Horas Extras", "_blank", true));
$list->addItem(new ItemList("Fondo_Fijo.xls", "Rendición/Reposición de Fondo Fijo", "_blank", true));
$list->addItem(new ItemList("Solicitud_ajuste_salarial.doc", "Solicitud Ajuste Salarial", "_blank", true));
$list->addItem(new ItemList("Adquisicion_y_Contratacion_de_ByS.xls", "Solicitud de Adquisición de Bienes y Servicios", "_blank", true));
$list->addItem(new ItemList("solicitud_alta_teletrabajo.doc", "Solicitud de Alta Teletrabajo", "_blank", true));
$list->addItem(new ItemList("Solicitud_de_actividades_de_capacitacion.doc", "Solicitud de Capacitación", "_blank", true));
$list->addItem(new ItemList("Solicitud_de_constitucion_AyF.doc", "Solicitud de Constitución o Modificación de Fondo Fijo", "_blank", true));
$list->addItem(new ItemList("Solicitud_Horas_extra.doc", "Solicitud de Horas Extras", "_blank", true));
$list->addItem(new ItemList("Licencia.doc", "Solicitud de Licencia", "_blank", true));
$list->addItem(new ItemList("solicitud_de_pago_por_transferencia_electronica.pdf", "Solicitud de Pago por Transferencia Electrónica", "_blank", true));
$list->addItem(new ItemList("solicitud_reintegros_gastos_medicacion_cronica.pdf", "Solicitud Reintegros - Gastos Medicación Crónica", "_blank", true));
$list->addItem(new ItemList("solicitud_telefonia_celular.pdf", "Solicitud Telefonía Celular", "_blank", true));
$list->addItem(new ItemList("SUAF_PS.2.4.doc", "SUAF PS.2.4 Asignaciones Familiares", "_blank", true));
$list->addItem(new ItemList("SUAF_PS.2.51.doc", "SUAF PS.2.51 Solic. Asig. Familiar por Ayuda Esc. Anual", "_blank", true));
$list->addItem(new ItemList("SUAF_PS.2.53.doc", "SUAF PS.2.53 Renuncia/Revocación de Renuncia", "_blank", true));
$list->addItem(new ItemList("SUAF_PS.2.55.doc", "SUAF PS.2.55 Novedades Unificadas", "_blank", true));
$list->addItem(new ItemList("SUAF_PS.2.57.doc", "SUAF PS.2.57 Nota de Reclamo", "_blank", true));
$list->addItem(new ItemList("SUAF_PS.2.61.doc", "SUAF PS.2.61 Notificación de Asig. Familiares", "_blank", true));
$list->setColsWidth(328);
$list->setImagePath("/modules/descargables/download.bmp");
$list->draw();
?>
		</div>
		<p>&nbsp;</p>
		<p align="center"><a href="index.php?pageid=37" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
	</body>
</html>