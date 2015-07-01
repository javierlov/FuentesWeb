<script>
	window.parent.listaImagenes = new Array();
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":dia" => $_REQUEST["d"]);
if ($_REQUEST["a"] == "d")		// Si se pasa el día directamente..
	$sql =
		"SELECT TO_NUMBER(TO_CHAR(se_fechacumple, 'DD')) dia, se_foto, se_id, se_nombre
			 FROM art.use_usuarios
			WHERE TO_CHAR(se_fechacumple, 'DD/MM') = :dia
				AND se_fechabaja IS NULL
	 ORDER BY se_nombre";
else
	$sql =
		"SELECT TO_NUMBER(TO_CHAR(se_fechacumple, 'DD')) dia, se_foto, se_id, se_nombre
			 FROM art.use_usuarios
			WHERE TO_CHAR(se_fechacumple, 'DD/MM') = TO_CHAR(TO_DATE(:dia, 'dd/mm') ".(($_REQUEST["a"]=="a")?"-":"+")." 1, 'DD/MM')
				AND se_fechabaja IS NULL
	 ORDER BY se_nombre";
$stmt = DBExecSql($conn, $sql, $params);
$count = 0;
$html = "";
while ($row = DBGetQuery($stmt)) {
	$rutaFoto = base64_encode(IMAGES_FOTOS_PATH."cartel.jpg");
	if (is_file(IMAGES_FOTOS_PATH.$row["SE_FOTO"]))
		$rutaFoto = base64_encode(IMAGES_FOTOS_PATH.$row["SE_FOTO"]);

	$count++;
	$html.= '<div class="CuerpoArticulo" style="margin-bottom:4px;">';
	$html.= '<span class="LineaGris">';
	$html.= '<a class="CuerpoArticulo" href="/index.php?pageid=56&id='.$row["SE_ID"].'" style="text-decoration:none;" onMouseMove="moverImagen()" onMouseOut="ocultarImagen()" onMouseOver="cargarImagen('.$count.')">'.$row["SE_NOMBRE"].'</a>';
	$html.= '</span>';
	$html.= '</div>';
?>
with (window.parent) {
	listaImagenes[<?= $count?>] = new Image();
	listaImagenes[<?= $count?>].src = '/functions/get_image.php?file=<?= $rutaFoto?>';
}
<?
}
if ($count == 0)
	$html = '<div><span class="CuerpoArticulo" style="text-align:left;"><br><b>NO HAY CUMPLEAÑOS</b></span></div>';

if ($_REQUEST["a"] == "d")		// Si se pasa el día directamente..
	$dia = $_REQUEST["d"];
else {
	$params = array(":dia" => $_REQUEST["d"]);
	$sql = "SELECT TO_CHAR(TO_DATE(:dia, 'dd/mm') ".(($_REQUEST["a"]=="a")?"-":"+")." 1, 'DD/MM') FROM DUAL";
	$dia = ValorSql($sql, "", $params);
}
?>
	window.parent.dia = '<?= $dia?>';
	window.parent.document.getElementById('tableCumples').innerHTML = '<?= $html?>';
	window.parent.document.getElementById('imgDia').src = '/modules/portada/imagen_dia.php?d=<?= $dia?>';
	window.parent.document.getElementById('divProcesandoDia').style.display = 'none';
	window.parent.document.getElementById('tableSociales').style.display = 'block';
</script>