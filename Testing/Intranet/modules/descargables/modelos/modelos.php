<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<style>
	a:visited {
		color: #00539B;
	}
</style>
<div align="left">
<?
$list = new ListOfItems(STORAGE_PATH."descargables/modelos/", ":: Modelos");
$list->addItem(new ItemList("Carta.doc", "Carta", "_blank", true));
$list->addItem(new ItemList("Fax.doc", "Fax", "_blank", true));
$list->addItem(new ItemList("PART_guia Firma Digital.pdf", "Firma e-Mails", "_blank", true));
$list->addItem(new ItemList("Memo.doc", "Memo", "_blank", true));
$list->addItem(new ItemList("Modelo_de_Procedimiento.doc", "Modelo de Procedimiento", "_blank", true));
$list->addItem(new ItemList("Modelo_de_Informe.doc", "Modelo de Informe", "_blank", true));
$list->addItem(new ItemList("Minuta.doc", "Minuta", "_blank", true));
$list->addItem(new ItemList("PART_Modelo de presentacion.pptx", "Presentación (Color)", "_blank", true));
$list->addItem(new ItemList("Tarjetas_institucionales_de_presentacion.pptx", "Tarjetas Institucionales de Presentación", "_blank", true));
$list->setColsWidth(320);
$list->setImagePath("/modules/descargables/download.bmp");
$list->draw();
?>
</div>
<p>&nbsp;</p>
<p align="center"><a href="index.php?pageid=37" style="font-weight:700; text-decoration:none;"><< VOLVER</a></p>