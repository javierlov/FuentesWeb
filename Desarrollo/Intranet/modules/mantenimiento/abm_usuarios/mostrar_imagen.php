<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");

$img = IMAGES_EDICION_PATH.$_REQUEST["img"];
$img = "/archivo/".base64_encode($img);
?>
<script type="text/javascript">
	window.parent.document.getElementById('imgFoto').src = '<?= $img?>';
</script>