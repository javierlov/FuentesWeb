<?
session_start();
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title>Contenido</title>
		<script>
			top.frames['encabezado'].document.getElementById('imgHeader').src = '/modules/regiones_sanitarias/imagenes/provincia_2_titulo.gif';

			function selectRegion(region) {
				top.frames['principal'].location.href = '/modules/regiones_sanitarias/region.php?region=' + region;
			}
		</script>
	</head>

	<body bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="25" style="text-align:center;">
		<div align="center" style="position:absolute; left:50%; margin-left:-380px; top:30px; width:760px;">
			<map name="FPMap0">
				<area coords="2, 4, 122, 126" href="#" shape="rect" onClick="selectRegion(1);">
				<area coords="138, 4, 258, 126" href="#" shape="rect" onClick="selectRegion(2);">
				<area coords="274, 4, 392, 126" href="#" shape="rect" onClick="selectRegion(3);">
				<area coords="408, 4, 526, 126" href="#" shape="rect" onClick="selectRegion(4);">
				<area coords="540, 4, 660, 126" href="#" shape="rect" onClick="selectRegion(5);">

				<area coords="72, 132, 192, 254" href="#" shape="rect" onClick="selectRegion(6);">
				<area coords="204, 132, 324, 254" href="#" shape="rect" onClick="selectRegion(7);">
				<area coords="340, 132, 458, 254" href="#" shape="rect" onClick="selectRegion(8);">
				<area coords="472, 132, 592, 254" href="#" shape="rect" onClick="selectRegion(9);">

				<area coords="2, 262, 122, 382" href="#" shape="rect" onClick="selectRegion(10);">
				<area coords="138, 262, 258, 382" href="#" shape="rect" onClick="selectRegion(11);">
				<area coords="274, 262, 392, 382" href="#" shape="rect" onClick="selectRegion(12);">
				<area coords="408, 262, 526, 382" href="#" shape="rect" onClick="selectRegion(13);">
				<area coords="540, 262, 660, 382" href="#" shape="rect" onClick="selectRegion(24);">
			</map>
			<img border="0" src="/modules/regiones_sanitarias/imagenes/provincia_2<?= ($_SESSION["RegionesSanitariasEditar"])?"ME":""?>.gif" usemap="#FPMap0" />
		</div>
		<div style="left:820px; position:absolute; top:3px;">
			<a target="_top" href="/regiones-sanitarias"><img border="0" src="/modules/regiones_sanitarias/imagenes/boton_volver.gif"></a>
		</div>
	</body>
</html>