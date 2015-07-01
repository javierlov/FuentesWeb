<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


function getArticulo($pos) {
	global $conn;

	$params = array(":posicion" => $pos);
	$sql =
		"SELECT ae_cuerpo, ae_id, ae_rutaimagen, ae_target, UPPER(ae_titulo) titulo, ae_volanta
			 FROM web.wae_articulosextranet
			WHERE ae_posicion = :posicion
				AND ae_fechabaja IS NULL
	 ORDER BY NVL(ae_fechamodif, ae_fechaalta) DESC";
	$stmt = DBExecSql($conn, $sql, $params);
	return DBGetQuery($stmt);
}

function setUrlAmigable($titulo) {
	return StringToLower(RemoveAccents(str_replace(" ", "-", $titulo))).".html";
}

$art1 = getArticulo(1);
$art2 = getArticulo(2);
$art3 = getArticulo(3);
$art4 = getArticulo(4);
?>
<style type="text/css">
	.Cuerpo:hover a {
		color: #00539B;
	}
</style>
<div style="height:120px; left:0px; position:absolute; top:12px; width:368px;">
	<div class="Volanta"><?= $art1["AE_VOLANTA"]?></div>
	<div class="Titular" style="margin-bottom:4px; margin-top:4px;"><?= $art1["TITULO"]?></div>
	<div class="Cuerpo"><?= $art1["AE_CUERPO"]?><a href="/articulos/<?= $art1["AE_ID"]?>-<?= setUrlAmigable($art1["TITULO"])?>" style="text-decoration:none;" target="<?= $art1["AE_TARGET"]?>">[+]</a></div>
</div>
<div class="LineaVertical" style="height:132px; left:376px; position:absolute; top:12px;"></div>
<div style="left:392px; position:absolute; top:12px; width:360px;">
	<div class="Volanta"><?= $art2["AE_VOLANTA"]?></div>
	<div class="Titular"><p style="margin-bottom:4px; margin-top:4px;"><?= $art2["TITULO"]?></div>
	<div class="Cuerpo"><?= $art2["AE_CUERPO"]?><a href="/articulos/<?= $art2["AE_ID"]?>-<?= setUrlAmigable($art2["TITULO"])?>" style="text-decoration:none;" target="<?= $art2["AE_TARGET"]?>">[+]</a></div>
</div>

<div class="LineaHorizontal" style="left:0px; position:absolute; top:144px; width:744px;">&nbsp;</div>

<div style="height:120px; left:0px; position:absolute; top:168px; width:344px;">
	<div class="Volanta"><?= $art3["AE_VOLANTA"]?></div>
	<div class="Titular" style="margin-bottom:4px; margin-top:4px;"><?= $art3["TITULO"]?></div>
	<div class="Cuerpo"><?= $art3["AE_CUERPO"]?><a href="/articulos/<?= $art3["AE_ID"]?>-<?= setUrlAmigable($art3["TITULO"])?>" style="text-decoration:none;" target="<?= $art3["AE_TARGET"]?>">[+]</a></div>
</div>
<div class="LineaVertical" style="height:132px; left:352px; position:absolute; top:168px;"></div>
<div style="left:368px; position:absolute; top:168px; width:384px;">
	<div class="Volanta"><?= $art4["AE_VOLANTA"]?></div>
	<div class="Titular"><p style="margin-bottom:4px; margin-top:4px;"><?= $art4["TITULO"]?></div>
	<div class="Cuerpo"><?= $art4["AE_CUERPO"]?><a href="/articulos/<?= $art4["AE_ID"]?>-<?= setUrlAmigable($art4["TITULO"])?>" style="text-decoration:none;" target="<?= $art4["AE_TARGET"]?>">[+]</a></div>
</div>
<div id="divBanner1HomePage" style="left:0px; position:absolute; top:320px;">
	<embed height="110" name="obj1" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner1.swf" type="application/x-shockwave-flash" width="240">
</div>
<div id="divBanner2HomePage" style="left:256px; position:absolute; top:320px;">
	<embed height="110" name="obj2" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner2.swf" type="application/x-shockwave-flash" width="240">
</div>
<!--
<div id="divBanner3HomePage" style="left:508px; position:absolute; top:320px;">
	<embed height="110" name="obj3" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner3.swf" type="application/x-shockwave-flash" width="240">
</div>
-->
<div id="banner3HomePage" width="240" height="110" style="left:508px; position:absolute; top:320px;">
	<a href="http://www.provincialeasing.com.ar/" target="_blank"><img border="0" src="/images/banner3.jpg"></a>
</div>