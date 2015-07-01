<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


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
	$vals = explode("-", $value);
	$list->addItem(new ItemList("/boletin-oficial/".$vals[0]."/".$vals[1], "Boletines del Mes de ".getMonthName(date("m", strtotime($vals[1]."/1/2000")))." ".$vals[0], "_self", false, true));
}

$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/boletin_oficial/images/flecha.gif");
$list->setTitleAlign("center");
$list->draw();
?>
<a href="/boletin-oficial"><input class="btnVolver" type="button" value="" /></a>