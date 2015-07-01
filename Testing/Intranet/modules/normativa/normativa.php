<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'NORMATIVA');
</script>

<?
if (isset($_REQUEST["fldr"]))
	require_once($_REQUEST["fldr"]);
else {
	$list = new ListOfItems("");
	$list->addItem(new ItemList("/index.php?pageid=31&fldr=gral/index.php", "General", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=31&fldr=afiliaciones/index.php", "Afiliaciones", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=31&fldr=prest_dinerarias/index.php", "Prestaciones Dinerarias", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=31&fldr=prest_en_especie/index.php", "Prestaciones en Especie", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=31&fldr=prevencion/index.php", "Prevención", "_self", false, true));
	$list->setCols(1);
	$list->setColsWidth(320);
	$list->setShowTitle(false);
	$list->setImagePath("/modules/normativa/item.bmp");
	$list->draw();
}
?>
<table style="background:#807F84; border-color:#c0c0c0; border-style:solid; border-width:1px; color:#fff; font-size:12px; left:150px; margin-top:40px; padding-bottom:3px; padding-left:4px; padding-right:4px; position:relative; width:460px;">
	<tr>
		<td align="center" face="Neo Sans">
			Toda la normativa vigente actualizada y comunicada se encuentra publicada en:<br>
			<a target="_blank" href="http://www.uart.org.ar/uarthome.asp"><img border="0" src="/modules/normativa/uartLink.gif" onmouseout="javascript:this.src='/modules/normativa/uartLink.gif'" onmouseover="javascript:this.src='/modules/normativa/uartLink_a.gif'"></a>
		</td>
	</tr>
</table>