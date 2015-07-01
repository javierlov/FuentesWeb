<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/comentarios/comentario.php");


function getImage($id) {
	$img = "";
	if (file_exists(DATA_CELEBRACIONES_PATH.$id.".gif"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$id.".gif");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$id.".jpeg"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$id.".jpeg");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$id.".jpg"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$id.".jpg");
	elseif (file_exists(DATA_CELEBRACIONES_PATH.$id.".png"))
		$img = base64_encode(DATA_CELEBRACIONES_PATH.$id.".png");

	if ($img == "")
		$img = base64_encode(DATA_CELEBRACIONES_PATH."default_image_nacimientos.jpg");

	return $img;
}


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT np_id, np_texto, NVL(np_titulo, '&nbsp;') np_titulo, CASE WHEN LENGTH(np_texto) > 47 THEN SUBSTR(np_titulo, 0, 47) || '...' ELSE np_texto END tituloparaemail
		 FROM rrhh.rnp_novedadespersonales
		WHERE np_tiponovedad = 'N'
			AND np_fechabaja IS NULL
			AND np_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

if ($row["NP_ID"] == "") {
	showErrorIntranet("", "Modo de acceso incorrecto.");
	return;
}
?>
<link href="/modules/nacimientos/css/nacimientos.css" rel="stylesheet" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div id="divNacimientos">
	<div id="divLeft">
		<div id="divTitulo"><?= htmlspecialchars_decode($row["NP_TITULO"], ENT_QUOTES)?></div>
		<div id="divTexto"><?= htmlspecialchars_decode($row["NP_TEXTO"], ENT_QUOTES)?></div>
		<? agregarCodigoComentario(32, $row["NP_ID"], $row["TITULOPARAEMAIL"])?>
	</div>
	<div id="divRight">
		<img id="imgFoto" src="/functions/get_image.php?file=<?= getImage($row["NP_ID"])?>" />
	</div>
</div>
<iframe id="iframeComentarios" name="iframeComentarios" src="/functions/comentarios/ver_comentarios.php?idmodulo=32&idarticulo=<?= $row["NP_ID"]?>"></iframe>