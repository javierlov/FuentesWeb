<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/photo_gallery.php");


validarParametro(isset($_REQUEST["crp"]));

if (!isset($_REQUEST["pagina"]))
	$_REQUEST["pagina"] = 1;

$photoGallery = new PhotoGallery(5, DATA_FOTOS_PATH.base64_decode($_REQUEST["crp"]), 680, 3);
$photoGallery->setPageNumber($_REQUEST["pagina"]);
$photoGallery->setTitle(base64_decode($_REQUEST["ttl"]));
$photoGallery->Draw();
?>