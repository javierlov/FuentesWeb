<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'SINDICATO DEL SEGURO');
</script>
<div align="center">
<?
$list = new ListOfItems("/modules/sindicato/", "");
$list->addItem(new ItemList("link.htm", "Ejemplo 1", "_blank"));
$list->addItem(new ItemList("link.htm", "Ejemplo 2", "_blank"));
$list->setCols(1);
$list->setColsWidth(488);
$list->setImagePath("/modules/sindicato/flecha.gif");
$list->setShowTitle(false);
$list->draw();
?>
</div>