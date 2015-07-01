<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'MANTENIMIENTO INTRANET');
</script>
<?
$list = new ListOfItems("", "");
$list->addItem(new ItemList("/index.php?pageid=64", "ABM Arteria Noticias", "_self"));
$list->addItem(new ItemList("/index.php?pageid=13", "ABM Síntesis de Prensa", "_self"));
$list->addItem(new ItemList("/index.php?pageid=63", "ABM Búsquedas Laborales", "_self"));
$list->addItem(new ItemList("/index.php?pageid=20", "ABM Celebraciones", "_self"));
$list->addItem(new ItemList("/index.php?pageid=48", "ABM Encuestas", "_self"));
$list->addItem(new ItemList("/index.php?pageid=10", "ABM Novedades", "_self"));
$list->addItem(new ItemList("/index.php?pageid=24", "ABM Portada", "_self"));
$list->addItem(new ItemList("/index.php?pageid=1", "ABM Usuarios", "_self"));
$list->addItem(new ItemList("/index.php?pageid=57", "ABM Usuarios Evaluación Desempeño", "_self"));
$list->setCols(1);
$list->setColsWidth(344);
$list->setImagePath("/modules/mantenimiento/images/icono.jpg");
$list->setShowTitle(false);
$list->draw();
?>