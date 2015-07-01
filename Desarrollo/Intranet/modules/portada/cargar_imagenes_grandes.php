<script>
	with (window.parent) {
		var elements = document.getElementsByClassName('imagenesGrandes');
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


if ($_REQUEST["vp"] == "t")
	$sql =
		"SELECT ai_id, ai_imagengrande
			 FROM web.wai_articulosintranet
			WHERE ai_vistaprevia = 'S'
				AND ai_ubicacion = 0
				AND ai_mostrarenportada = 'S'
				AND ai_fechabaja IS NULL
	 ORDER BY ai_posicion";
else
	$sql =
		"SELECT ai_id, ai_imagengrande
			 FROM web.wai_articulosintranet
			WHERE art.actualdate BETWEEN TRUNC(ai_fechavigenciadesde) AND ai_fechavigenciahasta
				AND ai_ubicacion = 0
				AND ai_mostrarenportada = 'S'
				AND ai_fechabaja IS NULL
	 ORDER BY ai_posicion";
$stmt = DBExecSql($conn, $sql);
$i = 0;
while ($row = DBGetQuery($stmt)) {
	$imgGrande = IMAGES_ARTICULOS_PATH.$row["AI_ID"]."/".$row["AI_IMAGENGRANDE"];
	$imgGrande = "/functions/get_image.php?file=".base64_encode($imgGrande)."&mw=".$_REQUEST["w"];
?>
	elements[<?= $i?>].src = '<?= $imgGrande?>';
<?
	$i++;
}
?>
		sliderOptions.autoAdvance = true;
		imageSlider.reload();
	}
</script>