<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'BOLETÍN OFICIAL');
</script>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<?
$dir = substr(DATA_BOLETIN_OFICIAL_PATH, 0, -1);
$list = new ListOfItems("", "Boletines Anteriores");
$periodoActual = date("Y-m");
$periodos = array();

if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($ano = readdir($gd)) !== false)
			if (($ano != ".") and ($ano != "..") and (is_dir($dir."/".$ano)))
				if ($gd2 = opendir($dir."/".$ano)) {
					while (($mes = readdir($gd2)) !== false)
						if (($mes != ".") and ($mes != ".."))
							if ($ano."-".$mes != $periodoActual)
								array_push($periodos, $ano."-".$mes);
					closedir($gd2);
				}
		closedir($gd);
	}
rsort($periodos);		// Ordeno el array descendentemente..


foreach($periodos as $value) {
	$vals = split("-", $value);
	$list->addItem(new ItemList("/index.php?pageid=17&ano=".$vals[0]."&mes=".$vals[1], "Boletines del Mes de ".GetMonthName(date("m", strtotime($vals[1]."/1/2000")))." ".$vals[0], "_self", false, true));
}

$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/boletin_oficial/images/flecha.gif");
$list->setTitleAlign("center");
$list->draw();
?>
<p>&nbsp;</p>
<p align="center"><a href="index.php?pageid=14" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>
</body>