<?
function getStyleMenu() {
	if (isset($_SESSION["isCliente"]))
		return "background-image:url('/images/menu/fondo_grande.gif'); height:324px; width:185px;";
	else
		return "background-image:url('/images/menu/fondo_chico.gif'); height:160px; width:185px;";
}
?>
<div style="margin-left:24px;">
	<div style="<?= getStyleMenu()?>">
		<div style="height:4px;"></div>
		<? require_once($_SERVER["DOCUMENT_ROOT"]."/get_menu.php");?>
	</div>
<?
if (!isset($_SESSION["isCliente"])) {
?>
	<a target="_blank" href="http://ssn.gob.ar/fwcm/"><img src="/images/menu/bannerSSN.jpg" border="0"></a>
	<embed height="100" name="obj2" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/telefonos.swf" type="application/x-shockwave-flash" width="185">
<?
}
?>
</div>
<img border="0" id="tituloMarquesina" src="/images/novedades.jpg" />
<? require_once($_SERVER["DOCUMENT_ROOT"]."/modules/novedades/novedades.php");?>