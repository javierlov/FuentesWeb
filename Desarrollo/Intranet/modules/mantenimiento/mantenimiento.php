<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


if (!hasPermiso(11)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}

$list = new ListOfItems("", "");
$list->addItem(new ItemList("/arteria-noticias-abm-busqueda/0", "ABM Arteria Noticias"));
$list->addItem(new ItemList("/articulos-abm-busqueda/0", "ABM Artículos"));
$list->addItem(new ItemList("/banners-abm-busqueda/0", "ABM Banners"));
$list->addItem(new ItemList("/beneficios-abm-busqueda/0", "ABM Beneficios"));
$list->addItem(new ItemList("/busquedas-corporativas-abm-busqueda/0", "ABM Búsquedas Corporativas"));
$list->addItem(new ItemList("/calendario-eventos-abm-busqueda/0", "ABM Calendario - Eventos"));
$list->addItem(new ItemList("/calendario-feriados-abm-busqueda/0", "ABM Calendario - Feriados"));
$list->addItem(new ItemList("/mantenimiento-descargables", "ABM Descargables"));
$list->addItem(new ItemList("/encuestas-abm-busqueda/0", "ABM Encuestas"));
$list->addItem(new ItemList("/mantenimiento-menu", "ABM Menú"));
$list->addItem(new ItemList("/nacimientos-abm-busqueda/0", "ABM Nacimientos"));
$list->addItem(new ItemList("/novedades-abm-busqueda/0", "ABM Novedades"));
$list->addItem(new ItemList("/usuarios-abm-busqueda/0", "ABM Usuarios"));
$list->addItem(new ItemList("/estadisticas-intranet", "Estadísticas Intranet"));
$list->addItem(new ItemList("/usuarios-aviso", "Usuarios de Aviso"));
$list->addItem(new ItemList("/vista-previa", "Vista Previa de la Portada"));

$list->setCols(1);
$list->setImagePath("/modules/mantenimiento/images/icono.jpg");
$list->setShowTitle(false);
$list->draw();
?>