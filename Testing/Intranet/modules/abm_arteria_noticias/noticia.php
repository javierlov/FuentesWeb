<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/ortografia/phpwebcorrect.php");


$params = array(":idboletin" => $_REQUEST["idboletin"], ":posicion" => $_REQUEST["num"]);
$sql =
	"SELECT na_altoimagenes, na_anchoimagenes, na_colortitulo, na_id, na_nota, na_numeroplantilla, na_titulo
		 FROM rrhh.rna_noticiasarteria
		WHERE na_idboletin = :idboletin
			AND na_posicion = :posicion";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$numeroPlantilla = 1;
if ($row["NA_NUMEROPLANTILLA"] != "")
	$numeroPlantilla = $row["NA_NUMEROPLANTILLA"];

$dir = SITE_PATH."/modules/arteria_noticias/fondo_titulos/";
$files = array();

if (is_dir($dir))
	if ($gd = opendir($dir)) {
		while (($file = readdir($gd)) !== false)
			if (($file != ".") and ($file != "..") and (substr($file, 0, 12) == "fondo_grande")) {
				$files[] = substr($file, 13, 6);
			}
		closedir($gd);
	}

$modo = "e";		// Edición..

phpwc_init();
?>
<link href="/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
<iframe id="iframeNoticia" name="iframeNoticia" src="" style="display:none;"></iframe>
<div id="divMostrarPanelAbm" style="display:none;">
	<div align="right" style="margin-right:4px;">
		<img border="0" src="/modules/abm_arteria_noticias/images/mostrar.png" style="cursor:hand; margin-top:4px;" title="Click aquí para mostrar el panel de edición" onClick="mostrarPanelAbm()" />
	</div>
</div>
<div align="left" id="divPanelAbm" style="background-color:#eee;">
	<form action="/modules/abm_arteria_noticias/procesar_noticia.php" enctype="multipart/form-data" id="formNoticia" method="post" name="formNoticia" target="iframeNoticia">
		<input id="descripcion_imagenes" name="descripcion_imagenes" type="hidden" />
		<input id="idboletin" name="idboletin" type="hidden" value="<?= $_REQUEST["idboletin"]?>">
		<input id="num" name="num" type="hidden" value="<?= $_REQUEST["num"]?>">
		<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="20000000">
		<p style="margin-left:17px;">
			<label class="FormLabelAzul" for="titulo" style="margin-right:4px;">Título</label>
			<input class="FormInputText" id="titulo" name="titulo" style="width:632px;" title="Título" type="text" validar="true" value="<?= $row["NA_TITULO"]?>">
			<img border="0" src="/modules/abm_arteria_noticias/images/ocultar.png" style="cursor:hand; margin-left:28px; margin-top:4px;" title="Click aquí para ocultar el panel de edición" onClick="ocultarPanelAbm()" />
		</p>
		<p style="margin-left:4px; margin-top:4px;">
			<label class="FormLabelAzul" for="plantilla" style="margin-right:4px;">Plantilla</label>
			<select class="Combo" id="plantilla" name="plantilla">
				<option value="1" <?= ($numeroPlantilla == 1)?"selected":""?>>Plantilla 1</option>
				<option value="2" <?= ($numeroPlantilla == 2)?"selected":""?>>Plantilla 2</option>
				<option value="3" <?= ($numeroPlantilla == 3)?"selected":""?>>Plantilla 3</option>
				<option value="4" <?= ($numeroPlantilla == 4)?"selected":""?>>Plantilla 4</option>
			</select>
		</p>
		<p style="margin-left:15px; margin-top:4px;">
			<label class="FormLabelAzul" for="fondo" style="margin-right:4px;">Fondo</label>
			<select class="Combo" id="fondo" name="fondo" onChange="cambiaFondo()">
				<option value="" <?= ($row["NA_COLORTITULO"] == "")?"selected":""?>>- SELECCIONAR -</option>
<?
foreach ($files as $key => $value) {
	?>
	<option value="<?= $value?>" <?= ($row["NA_COLORTITULO"] == $value)?"selected":""?>><?= $value?></option>
<?
}
?>
			</select>
			<img id="imgFondoChica" src="/modules/arteria_noticias/fondo_titulos/fondo_chico_00ADEF.jpg" style="cursor:hand; height:13px; margin-left:16px; margin-right:16px; visibility:hidden; width:90px;" title="Click aquí para redimensionar la imagen" onClick="ajustarImagen(this, '13px', '90px')" />
			<img id="imgFondoGrande" src="/modules/arteria_noticias/fondo_titulos/fondo_grande_00ADEF.jpg" style="cursor:hand; height:21px; visibility:hidden; width:380px;" title="Click aquí para redimensionar la imagen" onClick="ajustarImagen(this, '21px', '380px')" />
		</p>
		<p style="margin-left:11px; margin-top:4px;">
			<label class="FormLabelAzul" for="cuerpo" style="margin-right:4px; vertical-align:top;">Cuerpo</label>
			<? phpwc_boton_corregir("cuerpo");?>
			<br />
			<textarea class="FormTextArea" id="cuerpo" name="cuerpo" style="height:200px; left:40px; width:632px;"><?= (is_object($row["NA_NOTA"]))?$row["NA_NOTA"]->load():""?></textarea>
			<div class="phpwc_div" id="phpwcDiv_cuerpo" onClick="phpwc_ocultarsug()"></div>
		</p>
		<div align="center">
				<hr color="#C0C0C0" width="98%" size="1" style="border-bottom-style:dotted; border-bottom-width: 1px; border-left-width:1px; border-right-width:1px; border-top-width:1px;">

		</div>
		<p style="margin-left:4px; margin-top:8px;">
			<label class="FormLabelAzul" for="" style="margin-right:4px;">Imágenes</label>
<?
if ($row["NA_ID"] == "") {
?>
				<img alt="Antes de agregar una imagen debe guardar la noticia por primera vez" border="0" src="/modules/abm_arteria_noticias/images/imagen_lockeada.png" style="vertical-align:middle;" />
<?
}
else {
?>
<!--
			<a href="/functions/edit_image.php?finalFunction=addImagen&minWidth=<?= $row["NA_ANCHOIMAGENES"]?>&maxWidth=<?= $row["NA_ANCHOIMAGENES"]?>&minHeight=<?= $row["NA_ALTOIMAGENES"]?>&maxHeight=<?= $row["NA_ALTOIMAGENES"]?>" target="_blank">
				<img alt="Agregar imagen" border="0" src="/modules/abm_arteria_noticias/images/agregar_imagen.png" style="cursor:hand; vertical-align:middle;" />
			</a>
-->
			<div style="left:104px; position:relative; top:-16px;">
				<div style="float:left; height:26px; padding-top:4px;">Imagen</div>
				<div style="float:left; margin-left:8px;">
					<iframe frameborder="0" id="iframeSubirImagen" name="iframeSubirImagen" scrolling="no" src="/modules/abm_arteria_noticias/seleccionar_imagen.php" style="height:26px; width:400px;"></iframe>
				</div>
			</div>
<?
}
?>
		</p>
<?
if ($row["NA_ID"] != "") {
?>
		<iframe frameborder="1" id="iframeImagenes" name="iframeImagenes" scrolling="yes" src="/modules/abm_arteria_noticias/imagenes.php?idnoticia=<?= $row["NA_ID"]?>" style="height:120px; margin-left:68px; width:640px;"></iframe>
<?
}
?>
		<p style="height:28px; margin-left:34px; margin-top:4px;">
			<input class="BotonBlanco" name="btnGuardar" type="button" value="Guardar" onClick="guardarNoticia()">
			<input class="BotonBlanco" name="btnVolver" style="margin-left:436px;" type="button" value="Volver al boletín" onClick="volverAlBoletin()">
		</p>
	</form>
</div>
<table cellpadding="0" cellspacing="0" align="center" width="745">
	<tr><td colspan="2"><img border="0" src="/modules/arteria_noticias/images/header.jpg"></td></tr>
	<tr><td align="left" colspan="2" class="TituloBlanco" height="42" background="/modules/arteria_noticias/fondo_titulos/fondo_grande_<?= $row["NA_COLORTITULO"]?>.jpg"><?= $row["NA_TITULO"]?></td></tr>
	<tr><td colspan="2" height="5"></td></tr>
<? include($_SERVER["DOCUMENT_ROOT"]."/modules/abm_arteria_noticias/plantillas_noticia/plantilla_".$numeroPlantilla.".php");?>
	<tr><td colspan="2" height="10"></td></tr>
	<tr>
		<td colspan="2" height="45">
			<table border="0" cellpadding="0" cellspacing="0" width="100%" background="/modules/arteria_noticias/images/footer.jpg" height="45">
				<tr>
					<td align="left" class="PieFecha">Miércoles 25 de Junio de 2010 - Año I - Número VIII</td>
					<td class="PieMenu"></td>
					<td align="right"><img border="0" src="/modules/arteria_noticias/images/logo_art.jpg"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>
<script>
	showTitle(true, 'ABM ARTERIA NOTICIAS - NOTICIA <?= $_REQUEST["num"]?>');
	cambiaFondo();

	document.getElementById('titulo').focus();
</script>