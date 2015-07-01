<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normativa/prevencion/", ":: Prevención");
$list->addItem(new ItemList("Ley Nro 19587.pdf", "Ley N° 19.587 - Higiene y Seguridad en el Trabajo", "_blank", true));

$list->addItem(new ItemList("Decreto Nro 617-1997.pdf", "Decreto N° 617/1997", "_blank", true));
$list->addItem(new ItemList("Decreto Nro 911-1996.pdf", "Decreto N° 911/1996", "_blank", true));
$list->addItem(new ItemList("Decreto Nro 351-1979.pdf", "Decreto N° 351/1979", "_blank", true));

$list->addItem(new ItemList("Resolución SRT Nro 503-2014.pdf", "Resolución SRT N° 503/2014", "_blank", true));
$list->addItem(new ItemList("Res2013_771.pdf", "Resolución SRT N° 771/2013", "_blank", true));
$list->addItem(new ItemList("Res2012_0246.pdf", "Resolución SRT N° 246/2012", "_blank", true));
$list->addItem(new ItemList("Resolución Nro 85-2012.pdf", "Resolución SRT N° 85/2012", "_blank", true));
$list->addItem(new ItemList("Res84_2012.pdf", "Resolución SRT N° 84/2012", "_blank", true));
$list->addItem(new ItemList("Resolución SRT Nro 550-2011.pdf", "Resolución SRT N° 550/2011", "_blank", true));
$list->addItem(new ItemList("Resolución SRT Nro 475-2011.pdf", "Resolución SRT N° 475/2011", "_blank", true));
$list->addItem(new ItemList("ResSRT_301.pdf", "Resolución SRT N° 301/2011", "_blank", true));
$list->addItem(new ItemList("Res2011_0299.pdf", "Resolución SRT N° 299/2011", "_blank", true));
$list->addItem(new ItemList("Res2011_0065.pdf", "Resolución SRT N° 65/2011", "_blank", true));
$list->addItem(new ItemList("Res2010_0037.pdf", "Resolución SRT N° 37/2010", "_blank", true));
$list->addItem(new ItemList("Resolución SRT Nro 559-2009.pdf", "Resolución SRT N° 559/2009", "_blank", true));
$list->addItem(new ItemList("Res SRT 1579-2005.pdf", "Resolución SRT N° 1579/2005", "_blank", true));
$list->addItem(new ItemList("Res SRT 1-05.pdf", "Resolución SRT N° 1/2005", "_blank", true));
$list->addItem(new ItemList("Resolución SRT 743-2003.pdf", "Resolución SRT N° 743/2003", "_blank", true));
$list->addItem(new ItemList("Resolución SRT 497-2003.pdf", "Resolución SRT N° 497/2003", "_blank", true));
$list->addItem(new ItemList("Resolución SRT nro 230-2003.pdf", "Resolución SRT N° 230/2003", "_blank", true));
$list->addItem(new ItemList("Resolución SRT Nro 415-2002.pdf", "Resolución SRT N° 415/2002", "_blank", true));
$list->addItem(new ItemList("Resolución SRT Nro 552-2001.pdf", "Resolución SRT N° 552/2001", "_blank", true));
$list->addItem(new ItemList("Resolución SRT 35-98.pdf", "Resolución SRT N° 35/1998", "_blank", true));
$list->addItem(new ItemList("Resolución drt Nro 51-97.pdf", "Resolución SRT N° 51/1997", "_blank", true));

$list->addItem(new ItemList("DispSRT_1.pdf", "Disposición N° 1/2011", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->setImagePath("/modules/normativa_externa/images/download.bmp");
$list->draw();

$urlVolver = "/normativa-externa";
?>