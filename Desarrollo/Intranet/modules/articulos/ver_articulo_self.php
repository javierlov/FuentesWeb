<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/comentarios/comentario.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ai_articulo, ai_cuerpo, ai_destino, ai_habilitarcomentarios, ai_id, ai_nombrearchivo, ai_tipo, ai_titulo
		 FROM web.wai_articulosintranet
		WHERE ai_fechabaja IS NULL
			AND ai_id = :id";
$stmt = DBExecSql($conn, $sql, $params);

if (DBGetRecordCount($stmt) == 0) {
	echo '<h2 id="divError">Este artículo no está disponible.</h2>';
	return;
}
$row = DBGetQuery($stmt);


// INICIO - Harcodeo para notas en particular..
if ($_REQUEST["id"] == 9547) {
?>
	<script>
		window.location.href = '/modules/portada/link.php?l=5&id=141';
	</script>
<?
	exit;
}

if ($_REQUEST["id"] == 9562) {
?>
	<script>
		window.location.href = '/archivo/RDovU3RvcmFnZV9JbnRyYW5ldC8vdmFyaW9zL3Byb2RlXzIwMTUvcGF1dGFzX2dlbmVyYWxlc19wcm9kZV9jb3BhX2FtZXJpY2FfMjAxNS5wZGY=';
	</script>
<?
	exit;
}
// FIN - Harcodeo para notas en particular..


if ($row["AI_HABILITARCOMENTARIOS"] == "S") {
?>
<link href="/functions/comentarios/css/comentarios.css" rel="stylesheet" type="text/css" />
<?
}

if ($row["AI_TIPO"] != "X") {
?>
<link href="/modules/articulos/css/articulos.css" rel="stylesheet" type="text/css" />
<div id="divCuerpoArticulo"><?= str_replace('<video ', '<video autoplay ', preg_replace("/[\n|\r|\n\r]/i", "", $row["AI_ARTICULO"]->load()))?></div>
<br />
<?
}

if ($row["AI_HABILITARCOMENTARIOS"] == "S") {
	agregarCodigoComentario(77, $row["AI_ID"], $row["AI_TITULO"]);
?>
	<iframe id="iframeComentarios" name="iframeComentarios" src="/functions/comentarios/ver_comentarios.php?idmodulo=77&idarticulo=<?= $row["AI_ID"]?>"></iframe>
<?
}
?>
<script type="text/javascript">
<?
if ($row["AI_TIPO"] == "X") {
?>
	var w = screen.width * 0.8;
	var h = screen.height * 0.8;
	OpenWindow('/archivo/<?= base64_encode(DATA_ARTICULOS_ARCHIVOS_PATH.$row["AI_ID"]."/".$row["AI_NOMBREARCHIVO"])?>', 'intranetWindow', w, h);
//	window.location.href = '/';
	window.history.go(-1);
<?
}
elseif ($row["AI_DESTINO"] == "_self") {
?>
	with (document.getElementById('divTituloSeccion')) {
		innerText = '<?= $row["AI_TITULO"]?>';
		if (innerText == '')
			parentNode.style.display = 'none';
	}
<?
}
else {
?>
	var w = screen.width * 0.8;
	var h = screen.height * 0.8;
	OpenWindow('/articulos/n/<?= $_REQUEST["id"]?>', 'intranetWindow', w, h);
//	window.location.href = '/';
	window.history.go(-1);
<?
}
?>
</script>