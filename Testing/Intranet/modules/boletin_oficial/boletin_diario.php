<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'BOLET�N OFICIAL');
</script>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<?
validarParametro(isset($_REQUEST["dia"]));

$dir = DATA_BOLETIN_OFICIAL_PATH.date("Y/m/");
$fecha = $_REQUEST["dia"]." de ".GetMonthName($_REQUEST["mes"])." de ".$_REQUEST["ano"];

$list = new ListOfItems("/modules/boletin_oficial/", "Bolet�n Oficial del d�a ".$fecha);

$list->addItem(new ItemList("ver_boletin.php?ano=".$_REQUEST["ano"]."&mes=".$_REQUEST["mes"]."&dia=".$_REQUEST["dia"]."&seccion=1&today=".date("YmdHis"), "Primera Secci�n: Legislaci�n y Avisos Oficiales", "_blank"));
$list->addItem(new ItemList("ver_boletin.php?ano=".$_REQUEST["ano"]."&mes=".$_REQUEST["mes"]."&dia=".$_REQUEST["dia"]."&seccion=2&today=".date("YmdHis"), "Segunda Secci�n: Comerciales/Judiciales", "_blank"));
$list->addItem(new ItemList("ver_boletin.php?ano=".$_REQUEST["ano"]."&mes=".$_REQUEST["mes"]."&dia=".$_REQUEST["dia"]."&seccion=3&today=".date("YmdHis"), "Tercera Secci�n: Contrataciones", "_blank"));

$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/boletin_oficial/images/flecha.gif");
$list->setTitleAlign("center");
$list->draw();
?>
	<p>&nbsp;</p>
	<p align="center"><a href="index.php?pageid=14" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
</body>