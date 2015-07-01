<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'DICCIONARIOS');
</script>
<div align="center">
<?
$list = new ListOfItems("", "");
$list->addItem(new ItemList("http://www.rae.es/", "Diccionario Real Academia Española", "_blank"));
$list->addItem(new ItemList("http://www.buenasalud.com/dic/", "Diccionario Médico", "_blank"));
$list->setCols(1);
$list->setColsWidth(280);
$list->setImagePath("/modules/diccionarios/flecha.gif");
$list->setShowTitle(false);
$list->draw();
?>
</div>