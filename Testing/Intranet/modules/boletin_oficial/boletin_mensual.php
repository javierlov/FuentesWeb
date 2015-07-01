<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'BOLETÍN OFICIAL');
</script>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<?
validarParametro(isset($_REQUEST["mes"]));

$dir = DATA_BOLETIN_OFICIAL_PATH.date($_REQUEST["ano"]."/".$_REQUEST["mes"]."/");
$folders = array();
$list = new ListOfItems("", "Boletines ".GetMonthName($_REQUEST["mes"])." ".$_REQUEST["ano"]);

if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($dia = readdir($gd)) !== false)
			if (($dia != ".") and ($dia != ".."))
				array_push($folders, $dia);
		closedir($gd);
	}
rsort($folders);		// Ordeno el array descendentemente..


foreach($folders as $value)
	$list->addItem(new ItemList("/index.php?pageid=15&dia=".$value."&mes=".$_REQUEST["mes"]."&ano=".$_REQUEST["ano"], "Boletín Oficial del día ".$value." de ".GetMonthName($_REQUEST["mes"])." de ".$_REQUEST["ano"], "_self", false, true));

$list->setCols(2);
$list->setColsWidth(320);
$list->setImagePath("/modules/boletin_oficial/images/flecha.gif");
$list->setTitleAlign("left");
$list->draw();
?>
<p>&nbsp;</p>
<p><a href="index.php?pageid=16" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
</body>