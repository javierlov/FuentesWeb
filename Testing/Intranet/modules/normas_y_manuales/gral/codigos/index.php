<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00A4E4" vlink="#00A4E4" alink="#00A4E4">
<div align="left">
<?
$list = new ListOfItems("/modules/normas_y_manuales/gral/codigos/", ":: C�digos");
$list->addItem(new ItemList("codigo.php", "C�digo de Conducta", "_blank"));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><font color="#807F84">El c�digo aprobado est� publicado en la Intranet.<br> Una nueva versi�n est� siendo revisada para su posterior publicaci�n.</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=gral/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>