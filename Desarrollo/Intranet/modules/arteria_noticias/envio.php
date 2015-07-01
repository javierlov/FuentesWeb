<?
$esEnvio = true;
$host = "http://".$_SERVER["HTTP_HOST"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<title>ARTeria Noticias</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
		<meta name="Author" content="Gerencia de Sistemas">
		<meta name="Description" content="ARTeria Noticias">
		<meta name="Language" content="Spanish">
		<meta name="Subject" content="ARTeria Noticias">
		<link href="<?= $host?>/styles/style.css" rel="stylesheet" type="text/css">
	</head>
	<body style="margin:0 0;">
<? require_once($_SERVER["DOCUMENT_ROOT"]."/modules/arteria_noticias/arteria_noticias.php");?>
	</body>
<?
// La linea de abajo va para que el servicio chequee si existe, de no existir es poque hubo algún error en el php, entonces no debe enviarse el boletín..
echo "<!-- ** PROVART - ENVIO BOLETIN ARTERIA - HTML OK ** -->";
?>
</html>