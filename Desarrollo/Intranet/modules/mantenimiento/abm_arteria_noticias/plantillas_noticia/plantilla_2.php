<?
$params = array(":idnoticia" => $row["NA_ID"]);
$sql =
	"SELECT ia_descripcion, ia_extension, ia_id, ia_orden, na_altoimagenes, na_anchoimagenes
		 FROM rrhh.ria_imagenesarteria, rrhh.rna_noticiasarteria
		WHERE ia_idnoticia = na_id
			AND ia_idnoticia = :idnoticia
			AND ia_fechabaja IS NULL
 ORDER BY ia_orden";
$stmt = DBExecSql($conn, $sql, $params);
?>
<tr>
	<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%" height="102">
			<tr>
				<td width="8"></td>
				<td height="5"></td>
				<td width="8"></td>
			</tr>
			<tr>
				<td></td>
				<td class="CuerpoArticulo" valign="top">
					<p style="margin-left: 10px; margin-right: 10px; margin-top:0; margin-bottom:0"><?= $row["NA_NOTA"]->load()?></p>
					<p style="margin-left: 10px; margin-right: 10px; margin-top:0; margin-bottom:0">&nbsp;</p>
				</td>
				<td></td>
			</tr>
<?
$i = 0;

while ($rowImagen = DBGetQuery($stmt)) {
	$file = base64_encode(IMAGES_ARTERIA_PATH."noticias/".$row["NA_ID"]."_".$rowImagen["IA_ID"].".".$rowImagen["IA_EXTENSION"]);
	$img = "/functions/get_image.php?file=".$file."&mh=".$rowImagen["NA_ALTOIMAGENES"]."&mw=".$rowImagen["NA_ANCHOIMAGENES"];
?>
			<tr><td></td><td valign="top" align="center"><img src="<?= $img?>" /></td><td></td></tr>
			<tr><td></td><td class="CuerpoArticulo" valign="top" align="center" style="text-align:center;"><?= $rowImagen["IA_DESCRIPCION"]?></td><td></td></tr>
			<tr><td></td><td>&nbsp;</td><td></td></tr>
<?
	$i++;
}
?>
		</table>
	</td>
	<td valign="top" width="160">
		<iframe name="I1" src="/modules/arteria_noticias/notas.php?id=<?= $row["NA_ID"]?>&modo=<?= $modo?>" width="160" height="100%" frameborder="0" scrolling="yes" style="border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" onLoad="this.style.height = this.contentDocument.getElementById('tablaNota').offsetHeight + 24 + 'px'">El explorador no admite los marcos flotantes o no est� configurado actualmente para mostrarlos.</iframe>
	</td>
</tr>