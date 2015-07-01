<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">
<div align="left">
<?
$list = new ListOfItems("/modules/normas_y_manuales/corporativa/codigos/", ":: Códigos");
$list->addItem(new ItemList("codigo.php", "Código de Conducta", "_blank"));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><font color="#807F84">El código aprobado está publicado en la Intranet.<br> Una nueva versión está siendo revisada para su posterior publicación.</font></p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p align="center"><a href="/index.php?pageid=40&fldr=corporativa/index.php" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>