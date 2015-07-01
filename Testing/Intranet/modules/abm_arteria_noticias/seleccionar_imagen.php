<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


function uploadImage($arch, &$filename) {
	$tmpfile = $arch["tmp_name"];
	$partes_ruta = pathinfo($arch["name"]);
	$filename = IMAGES_ARTERIA_PATH."noticias\\".date("YmdHis").".".$partes_ruta["extension"];

	$uploadOk = false;
	if (is_uploaded_file($tmpfile))
		if (move_uploaded_file($tmpfile, $filename))
			$uploadOk = true;

	if (!$uploadOk)
		echo "<script>alert('Ocurrió error al guardar la imagen.');</script>";

	return $uploadOk;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<script language="JavaScript" src="/js/validations.js"></script>
	</head>
	<body style="background-color:#eee; margin:0px;">
		<form action="<?= $_SERVER["PHP_SELF"]?>" enctype="multipart/form-data" id="formImagen" method="post" name="formImagen" onSubmit="return ValidarForm(formImagen)">
			<input id="guardar" name="guardar" type="hidden" value="t" />
			<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="20000000">
			<input ext="gif,jpg,jpeg,png" id="imagen" name="imagen" style="background-color: #fff; border: 1px solid #808080; color: #808080;	cursor: pointer; font-family: Neo Sans; font-size: 8pt;	padding-bottom: 1px; padding-left: 4px;	padding-right: 4px;	padding-top: 1px;" size="40" title="Imagen" type="file" validar="true" validarImagen="true">
			<input style="background-color: #fff; border: 1px solid #808080; color: #808080;	cursor: pointer; font-family: Neo Sans; font-size: 8pt;	padding-bottom: 0px; padding-left: 4px;	padding-right: 4px;	padding-top: 0px;" type="submit" value="Subir imagen" />
		</form>
	</body>
<?
if ((isset($_POST["guardar"])) and ($_POST["guardar"] == "t")) {
	$file = "";
	if (uploadImage($_FILES["imagen"], $file)) {
		$partes_ruta = pathinfo(strtolower($file));
?>
	<script>
		parent.document.iframeNoticia.location = '/modules/abm_arteria_noticias/guardar_imagen_noticia.php?tipoop=a&idboletin=' + parent.document.getElementById('idboletin').value + '&num=' + parent.document.getElementById('num').value + '&imgName=<?= $partes_ruta["basename"]?>';
	</script>
<?
	}
}
?>
</html>