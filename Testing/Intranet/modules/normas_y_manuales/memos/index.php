<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<body link="#00539B" vlink="#00539B" alink="#00539B">

<table align="center" style="background:#00539B; border-color:#c0c0c0; border-style:solid; border-width:1px; color:#fff; font-size:12px; margin-top:40px; padding-bottom:3px; padding-left:4px; padding-right:4px; width:320px">
	<tr>
		<td align="center" face="Neo Sans"><b>SIN DOCUMENTOS ACTUALIZADOS</b></td>
	</tr>
</table>
<!--
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/memos/", ":: Memos");
$list->addItem(new ItemList("GIS_Traslados.pdf", "GIS - Traslados", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setImagePath("/modules/normas_y_manuales/download.bmp");
$list->draw();
?>
</div>
-->
<br>
<p align="center"><a href="/index.php?pageid=40" style="text-decoration: none; font-weight: 700"><< VOLVER</a></p>