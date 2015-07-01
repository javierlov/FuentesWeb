<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title>.:: Olimpiadas de Valores | Provincia ART ::.</title>
		<link rel="stylesheet" href="style/style.css" type="text/css">
		<link rel="stylesheet" href="style/tabla.css" type="text/css">
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<script type="text/javascript" src="/js/popup/dhtmlwindow.js"></script>
		<script>
			function verComentarios(votado, valor) {
				height = 200;
				width = 600;

				var left = 120;
				var top = 120 + 8;

				if (document.body.offsetWidth < (left + width))
					left = document.body.offsetWidth - width - 20;
				if (left < 0)
					left = 8;

				if ((document.body.offsetHeight - 48) < (top + height))
					top = document.body.offsetHeight - height - 48;
				if (top < 0)
					top = 8;

				divWin = null;
				divWin = dhtmlwindow.open('divBoxComentarios', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1, scrolling=1');
				divWin.load('iframe', '/modules/olimpiadas_londres2012/ver_comentarios.php?fase=2&votado=' + votado + '&valor=' + valor, '*');
				divWin.show();
			}
		</script>
	</head>

<body>
<table cellpadding="0" width="738" cellspacing="0" align="center">
	<tr>
		<td height="103"><map name="FPMap1">
		<area href="http://www.artprov.com.ar/" shape="rect" coords="38, 33, 189, 82">
		</map><img border="0" src="images/top.jpg" usemap="#FPMap1"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/posiciones.jpg"></p>
			<p style="padding-left:15px; padding-right:20px">Resultados parciales. Totalidad de personas votadas y razones por las que cada uno fue elegido.</p>
		</td>
	</tr>
	<tr>
		<td height="25"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/excelencia.jpg"></p>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center">
			<iframe frameborder="0" src="/modules/olimpiadas_londres2012/tabla_posiciones_xxx.php?v=E" style="height:132px; width:524px;"></iframe>
		</td>
	</tr>
	<tr>
		<td height="25px"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/servicio.jpg"></p>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center">
			<iframe frameborder="0" src="/modules/olimpiadas_londres2012/tabla_posiciones_xxx.php?v=S" style="height:128px; width:524px;"></iframe>
		</td>
	</tr>
	<tr>
		<td height="25px"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/integridad.jpg"></p>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center">
			<iframe frameborder="0" src="/modules/olimpiadas_londres2012/tabla_posiciones_xxx.php?v=I" style="height:128px; width:524px;"></iframe>
		</td>
	</tr>
	<tr>
		<td height="25px"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/solidaridad.jpg"></p>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center">
			<iframe frameborder="0" src="/modules/olimpiadas_londres2012/tabla_posiciones_xxx.php?v=O" style="height:128px; width:524px;"></iframe>
		</td>
	</tr>
	<tr>
		<td height="25px"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/entusiasmo.jpg"></p>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td align="center">
			<iframe frameborder="0" src="/modules/olimpiadas_londres2012/tabla_posiciones_xxx.php?v=N" style="height:128px; width:524px;"></iframe>
		</td>
	</tr>
	<tr>
		<td height="25px"></td>
	</tr>		
	<tr>
		<td><img src="images/footer.jpg"></td>
	</tr>
</table>
</body>
</html>