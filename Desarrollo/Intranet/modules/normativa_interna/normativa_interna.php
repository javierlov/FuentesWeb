<link href="/modules/normativa_interna/css/normativa_interna.css" rel="stylesheet" type="text/css" />
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


if (isset($_REQUEST["fldr"]))
	require_once($_REQUEST["fldr"]);
else {
	$list = new ListOfItems("");
	$list->addItem(new ItemList("/normativa-interna/corporativa/index.php", "CORPORATIVA", "_self", false, true));

	$list->addItem(new ItemList("/normativa-interna/adm_y_fin/index.php", "ADMINISTRACIÓN Y FINANZAS", "_self", false, true));
	$list->addItem(new ItemList("/normativa-interna/analisis_y_control_de_gestion/index.php", "ANÁLISIS Y CONTROL DE GESTIÓN", "_self", false, true));
	$list->addItem(new ItemList("/normativa-interna/auditoria_interna/index.php", "AUDITORÍA INTERNA", "_self", false, true));
	$list->addItem(new ItemList("/normativa-interna/comercial/index.php", "COMERCIAL", "_self", false, true));
	$list->addItem(new ItemList("/normativa-interna/legales/index.php", "LEGALES", "_self", false, true));
	$list->addItem(new ItemList("/normativa-interna/prest_y_servic/index.php", "PRESTACIONES Y SERVICIOS", "_self", false, true));
	$list->addItem(new ItemList("/normativa-interna/prevencion/index.php", "PREVENCIÓN", "_self", false, true));
	$list->addItem(new ItemList("/normativa-interna/rrhh/index.php", "RR.HH.", "_self", false, true));
	$list->addItem(new ItemList("/normativa-interna/tecnica/index.php", "TÉCNICA", "_self", false, true));

	$list->setCols(1);
	$list->setShowTitle(false);
	$list->setImagePath("/modules/normativa_interna/images/item.bmp");
	$list->setItemsStyle("listaItemsBold");
	$list->draw();
}
?>
<div id="divMensaje">
	Las normativas aprobadas y vigentes se encuentran publicadas en la Intranet.<br> Las restantes están siendo revisadas y/o desarrolladas por GESTIÓN DE PROCESOS | <a href="mailto:gestiondeprocesos@provart.com.ar">gestiondeprocesos@provart.com.ar</a> 
</div>
<?
if (isset($_REQUEST["fldr"])) {
?>
	<a href="<?= $urlVolver?>"><input class="btnVolver" type="button" value="" /></a>
<?
}
?>