<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'DESCARGABLES');
</script>
<?
if (isset($_REQUEST["fldr"]))
	require_once($_REQUEST["fldr"]);
else {
	$list = new ListOfItems("");
	$list->addItem(new ItemList("/index.php?pageid=37&fldr=lrt_y_prestaciones/lrt_y_prestaciones.php", "LRT y Prestaciones", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=37&fldr=formularios/formularios.php", "Formularios", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=37&fldr=modelos/modelos.php", "Modelos", "_self", false, true));	
	$list->addItem(new ItemList("/index.php?pageid=37&fldr=manual_induccion/manual_induccion.php", "Manual de Inducción", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=37&fldr=logos/logos.php", "Logos", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=37&fldr=otros/otros.php", "Otros", "_self", false, true));
	$list->setCols(1);
	$list->setColsWidth(320);
	$list->setShowTitle(false);
	$list->setImagePath("/modules/descargables/item.bmp");
	$list->draw();
}
?>