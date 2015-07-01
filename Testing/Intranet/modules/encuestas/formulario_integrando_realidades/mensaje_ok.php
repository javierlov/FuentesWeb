<?
if ($_REQUEST["result"] == "ok")
	$msg = "<img src='images/mensaje_ok.jpg' />";
else
	$msg = "Ocurrió el siguiente error: ".$_REQUEST["result"];
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		<title>.: Integrando Realidades :.</title>
	</head>
	<body bgcolor="#C0C0C0" style="margin-left: 0px; margin-top: 0px;">
		<table border="0" cellpadding="0" cellspacing="0">
			<tr>
				<td valign="top"><p align="center"><b><font face="Verdana" color="#FFFFFF" size="3"><?= $msg?></font></b></td>
			</tr>
		</table>
	</body>
</html>