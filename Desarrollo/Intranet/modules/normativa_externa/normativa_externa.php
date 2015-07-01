<link href="/modules/normativa_externa/css/normativa_externa.css" rel="stylesheet" type="text/css" />
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


if (isset($_REQUEST["fldr"]))
	require_once($_REQUEST["fldr"]);
else {
	$list = new ListOfItems("");
	$list->addItem(new ItemList("/normativa-externa/gral/index.php", "GENERAL", "_self", false, true));
	$list->addItem(new ItemList("/normativa-externa/afiliaciones/index.php", "AFILIACIONES", "_self", false, true));
	$list->addItem(new ItemList("/normativa-externa/prest_dinerarias/index.php", "PRESTACIONES DINERARIAS", "_self", false, true));
	$list->addItem(new ItemList("/normativa-externa/prest_en_especie/index.php", "PRESTACIONES EN ESPECIE", "_self", false, true));
	$list->addItem(new ItemList("/normativa-externa/prevencion/index.php", "PREVENCIÓN", "_self", false, true));

	$list->setCols(1);
	$list->setShowTitle(false);
	$list->setImagePath("/modules/normativa_interna/images/item.bmp");
	$list->setItemsStyle("listaItemsBold");
	$list->draw();
}
?>
<div id="divMensaje">
	Toda la normativa vigente actualizada y comunicada se encuentra publicada en:&nbsp;&nbsp;<a href="http://www.uart.org.ar" target="_blank">www.uart.org.ar</a>
</div>
<?
if (isset($_REQUEST["fldr"])) {
?>
	<a href="<?= $urlVolver?>"><input class="btnVolver" type="button" value="" /></a>
<?
}
?>