<?
$img = "/modules/mantenimiento/abm_arteria_noticias/images/imagen_plantilla_3.jpg";
$nota = (is_object($row["NA_NOTA"]))?$row["NA_NOTA"]->load():"";

if (isset($row["NA_ID"])) {
	$params = array(":idnoticia" => $row["NA_ID"]);
	$sql =
		"SELECT ia_descripcion, ia_extension, ia_id, ia_orden, na_altoimagenes, na_anchoimagenes
			 FROM rrhh.ria_imagenesarteria, rrhh.rna_noticiasarteria
			WHERE ia_idnoticia = na_id
				AND ia_idnoticia = :idnoticia
				AND ia_fechabaja IS NULL
	 ORDER BY ia_orden";
	$stmt = DBExecSql($conn, $sql, $params);
	$primerRegistro = true;
	while ($rowImagen = DBGetQuery($stmt)) {
		if ($primerRegistro) {
			$file = base64_encode(IMAGES_ARTERIA_PATH."noticias/".$row["NA_ID"]."_".$rowImagen["IA_ID"].".".$rowImagen["IA_EXTENSION"]);
			$img = "/functions/get_image.php?file=".$file."&mh=".$rowImagen["NA_ALTOIMAGENES"]."&mw=".$rowImagen["NA_ANCHOIMAGENES"];
		}
		else {
			$file = base64_encode(IMAGES_ARTERIA_PATH."noticias/".$row["NA_ID"]."_".$rowImagen["IA_ID"].".".$rowImagen["IA_EXTENSION"]);
			$img2 = "/functions/get_image.php?file=".$file."&mh=".$rowImagen["NA_ALTOIMAGENES"]."&mw=".$rowImagen["NA_ANCHOIMAGENES"];

			$strReemplazo = '<div align="center" style="margin-bottom:16px; margin-top:16px;"><img src="'.$img2.'" /><br />';
			$strReemplazo.= '<span class="CuerpoArticulo" valign="top" style="text-align:center;">'.$rowImagen["IA_DESCRIPCION"].'</span>';
			$strReemplazo.= '</div>';

			$nota = str_replace("@imagen".$rowImagen["IA_ORDEN"]."@", $strReemplazo, $nota);
		}
		$primerRegistro = false;
	}
}
?>
<tr>
	<td valign="top">
		<table cellpadding="0" cellspacing="0" width="100%" height="350">
			<tr><td height="5"></td></tr>
			<tr>
				<td class="CuerpoArticulo" valign="top">
					<div id="imgizq" style="margin-left:10px;"><img align="left" src="<?= $img?>" style="margin-right:8px;" /></div>
					<p style="margin-left: 10px; margin-right: 10px; margin-top:0; margin-bottom:0">&nbsp;</p>
					<p style="margin-left: 10px; margin-right: 10px; margin-top:0; margin-bottom:0"><?= $nota?></p>
				</td>
			</tr>
		</table>
	</td>
	<td valign="top" width="160">
		<iframe name="I1" src="/modules/arteria_noticias/notas.php?id=<?= $row["NA_ID"]?>&modo=<?= $modo?>" width="160" height="100%" frameborder="0" scrolling="yes" style="border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" onLoad="this.style.height = this.contentDocument.getElementById('tablaNota').offsetHeight + 24 + 'px'">El explorador no admite los marcos flotantes o no está configurado actualmente para mostrarlos.</iframe>
	</td>
</tr>