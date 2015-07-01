<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
<html>
	<head>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<link href="/modules/arteria_noticias/css/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="/modules/abm_arteria_noticias/js/noticia.js"></script>
	</head>
	<body style="background-color:#eee; margin: 4 4;">
		<form id="formImagenes" name="formImagenes">
			<input id="idnoticia" type="hidden" value="<?= $_REQUEST["idnoticia"]?>" />
			<input id="tmpId" type="hidden" />
<?
$sql =
	"SELECT ia_descripcion, ia_extension, ia_id, ia_orden, na_altoimagenes, na_anchoimagenes
		 FROM rrhh.ria_imagenesarteria, rrhh.rna_noticiasarteria
		WHERE ia_idnoticia = na_id
			AND ia_idnoticia = :idnoticia
			AND ia_fechabaja IS NULL
 ORDER BY ia_orden";
$params = array(":idnoticia" => $_REQUEST["idnoticia"]);
$stmt = DBExecSql($conn, $sql, $params);
while ($row = DBGetQuery($stmt)) {
	$img = IMAGES_ARTERIA_PATH."noticias/".$_REQUEST["idnoticia"]."_".$row["IA_ID"].".".$row["IA_EXTENSION"];
	$img = "/functions/get_file.php?fl=".base64_encode($img);
?>
	<p>
		<a class="FormLabelAzul" href="<?= $img?>" style="cursor:hand; margin-right:4px;" target="_blank" title="Click para ver la imagen">Imagen <?= $row["IA_ORDEN"]?></a>
		<input class="FormInputText" id="descripcion_imagen_<?= $row["IA_ID"]?>" maxlength="128" name="descripcion_imagen_<?= $row["IA_ID"]?>" style="margin-right:8px; width:488px;" type="text" value="<?= $row["IA_DESCRIPCION"]?>">
		<a href="/modules/abm_arteria_noticias/guardar_imagen_noticia.php?tipoop=b&idnoticia=<?= $_REQUEST["idnoticia"]?>&id=<?= $row["IA_ID"]?>" onClick="return confirm('¿ Realmente desea quitar esta imagen ?')">
			<img alt="Quitar imagen" border="0" src="/modules/abm_arteria_noticias/images/quitar_imagen.png" style="cursor:hand; vertical-align:top;" />
		</a>
	</p>
<?
}
?>
		</form>
	</body>
</html>