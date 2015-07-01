<?
$folder = "/modules/usuarios_registrados/agentes_comerciales/noticias/";
if (isset($_REQUEST["pg"])) {
	$file = $_SERVER["DOCUMENT_ROOT"].$folder.$_REQUEST["pg"];
	if (file_exists($file))
		require_once($_SERVER["DOCUMENT_ROOT"].$folder.$_REQUEST["pg"]);
	else
		echo "<script type='text/javascript'>window.location.href='/'</script>";
}
else
	echo "<script type='text/javascript'>window.location.href='/'</script>";
?>