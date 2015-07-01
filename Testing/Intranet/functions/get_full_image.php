<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<title>Ver Foto</title>
		<link href="/Styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<img border="0" id="foto" name="foto" src="<?= "/functions/get_image.php?file=".$_REQUEST["file"]?>">
	</body>
<?
if ((isset($_REQUEST["rw"])) and ($_REQUEST["rw"] == "true")) {
?>
<script>
	document.getElementById('foto').onload = function() {
		var height = document.getElementById('foto').height;
		var width = document.getElementById('foto').width;
		window.resizeTo(width + 10, height + 29);
	}
</script>
<?
}
?>
</html>