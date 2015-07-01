<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/comentarios/comentario.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ai_articulo, ai_cuerpo, ai_destino, ai_habilitarcomentarios, ai_id, ai_titulo
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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html class="fondoBlank">
	<head>
		<?= getHead(getPageTitle(-1), array("form_elements.css", "general.css", "style.css", "/modules/articulos/css/articulos.css"))?>
<?
if ($row["AI_HABILITARCOMENTARIOS"] == "S") {
?>
	<link href="/functions/comentarios/css/comentarios.css" rel="stylesheet" type="text/css" />
<?
}
?>
	</head>
	<body class="fondoBlank">
		<div id="divFondoSeccion" style=""><div id="divTituloSeccion"><?= $row["AI_TITULO"]?></div></div>
		<div><?= str_replace('<video ', '<video autoplay ', preg_replace("/[\n|\r|\n\r]/i", "", $row["AI_ARTICULO"]->load()))?></div>
<?
if ($row["AI_HABILITARCOMENTARIOS"] == "S") {
	agregarCodigoComentario(77, $row["AI_ID"], $row["AI_TITULO"]);
?>
	<iframe id="iframeComentarios" name="iframeComentarios" src="/functions/comentarios/ver_comentarios.php?idmodulo=77&idarticulo=<?= $row["AI_ID"]?>"></iframe>
<?
}
?>
	</body>
</html>