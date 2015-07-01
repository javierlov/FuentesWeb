<!--ZOOMSTOP-->
<?
$sql =
	"SELECT mi_color, mi_id, mi_target, UPPER(mi_texto) mi_texto, mi_url
		 FROM web.wmi_menuintranet
		WHERE mi_idpadre = -1
			AND mi_activo = 'S'
			AND mi_fechabaja IS NULL
 ORDER BY mi_orden";
$stmt = DBExecSql($conn, $sql);
?>
<ul class="menu">
<?
while ($rowP = DBGetQuery($stmt)) {
?>
	<br />
		<li>
			<div class="divColor" style="background-color:#<?= $rowP["MI_COLOR"]?>;"></div>
			<div class="divItemMenu">
<?
	if ($rowP["MI_URL"] == "") {
?>
		<?= $rowP["MI_TEXTO"]?>
<?
	}
	else {
?>
		<a href="<?= $rowP["MI_URL"]?>" target="<?= $rowP["MI_TARGET"]?>"><?= $rowP["MI_TEXTO"]?></a>
<?
	}
?>
			</div>
			<div id="divNada"></div>
		</li>
	<table>
<?
	$params = array(":idpadre" => $rowP["MI_ID"]);
	$sql =
		"SELECT mi_id, mi_target, UPPER(mi_texto) mi_texto, mi_url
			 FROM web.wmi_menuintranet
			WHERE mi_idpadre = :idpadre
				AND mi_activo = 'S'
				AND mi_fechabaja IS NULL
	 ORDER BY mi_orden";
	$stmt2 = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt2)) {
		if (($row["MI_ID"] != 15) or (($row["MI_ID"] == 15) and (hasPermiso(11)))) {		// Si hay que dibujar el menú de mantenimiento de RRHH, veo si tiene permiso para verlo..
?>
		<tr>
			<td class="tdMenuImg"></td>
			<td><a class="menuLinkTexto" href="<?= $row["MI_URL"]?>" target="<?= $row["MI_TARGET"]?>"><?= $row["MI_TEXTO"]?></a></td>
		</tr>
		<tr>
			<td colspan="2" style="height:3px;"></td>
		</tr>
<?
		}
	}
?>
	</table>
<?
}
?>
</ul>
<!--
<div class="divTwitter">
	<a href="/modules/portada/link.php?l=3" target="_blank"><img class="imgTwitter" src="/images/twitter.jpg" /></a>
</div>
<div class="divFacebook">
	<a href="/modules/portada/link.php?l=4" target="_blank"><img class="imgFacebook" src="/images/facebook.jpg" /></a>
</div>
-->
<div class="divFacebook">
	<a href="mailto:prevenciondelfraude@provart.com.ar"><img class="imgFacebookx" src="/images/prevencion_fraude.jpg" /></a>
</div>
<!--ZOOMRESTART-->