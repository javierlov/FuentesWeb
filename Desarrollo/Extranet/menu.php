<?
function getStyleMenu() {
	if (isset($_SESSION["isCliente"]))
		return "background-image:url('/images/menu/fondo_grande.gif'); height:324px; width:185px;";
	else {
		return "background-image:url('/images/menu/fondo_chico.gif'); height:203px; width:185px;";

		if (!showBannerSSN())
			return "background-image:url('/images/menu/fondo_grande.gif'); height:324px; width:185px;";
		else
			return "background-image:url('/images/menu/fondo_chico.gif'); height:160px; width:185px;";
		}
}

function showBannerSSN() {
	if (isset($_SESSION["isCliente"])) return false;
	if (isset($_SESSION["isAbogado"])) return false;

	return true;
}

?>
<div style="margin-left:24px;">
	<div style="<?= getStyleMenu()?>">
		<div style="height:4px;"></div>
		<? require_once($_SERVER["DOCUMENT_ROOT"]."/get_menu.php");?>
	</div>
<?
if ((!isset($_SESSION["isCliente"])) or (showBannerSSN())) {
?>
	<a target="_blank" href="http://ssn.gob.ar/fwcm/"><img border="0" style="padding-bottom:4px; padding-top:4px;" src="/images/menu/bannerSSN.jpg" /></a>
	<div style="position:relative; z-index:0;">
		<object border="0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="100" width="185">
			<param name="movie" value="/images/telefonos.swf" />
			<param name="quality" value="High" />
			<param name="wmode" value="transparent" />
			<embed height="100" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/telefonos.swf" type="application/x-shockwave-flash" width="185">
		</object>
	</div>
<?
}
?>
</div>
<a href="http://www.gba.gov.ar/" target="_blank">
	<img border="0" id="tituloMarquesina" src="/images/menu/bannerBA.jpg" />
</a>
<!--
<img border="0" id="tituloMarquesina" src="/images/novedades.jpg" />
<? require_once($_SERVER["DOCUMENT_ROOT"]."/modules/novedades/novedades.php");?>
-->