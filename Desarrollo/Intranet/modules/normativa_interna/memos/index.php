<link href="/modules/normativa_interna/css/normativa_interna.css" rel="stylesheet" type="text/css" />
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


$list = new ListOfItems(STORAGE_PATH."normas_y_procedimientos/memos/", ":: Memos");
//$list->addItem(new ItemList("GIL_Traslados.pdf", "GIL - Traslados", "_blank", true));

$list->setCols(1);
$list->setShowImage(false);
$list->draw();

$urlVolver = "/normativa-interna";
?>
<div id="divSinDocumentos">
	<b>SIN DOCUMENTOS ACTUALIZADOS</b>
</div>