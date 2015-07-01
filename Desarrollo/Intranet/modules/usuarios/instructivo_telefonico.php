<?
// El array contiene las 3 columnas de la tabla..
$arrCols = array(
	array("#8", "Consulta Mensajer�a Vocal", "Permite levantar los mensajes que se dejan en el correo de voz"),
	array("*60", "Desv�o Inmediato", "Desv�a el interno siempre:<br>*60 + numero de interno al que se desea enrutar las llamadas<br>Para Desv�o a correo de voz *60 + #8"),
	array("*61", "Desv�o sobre Ocupado", "Desv�a el interno por ocupado.<br>*61 + n�mero de interno al que se desea enrutar las llamadas<br>Para Desv�o a correo de voz *63 + #8"),
	array("*62", "Desv�o sobre No Respuesta", "Desv�a el interno por no contesta.<br>*62 + n�mero de interno al que se desea enrutar las llamadas<br>Para Desv�o a correo de voz *62 + #8"),
	array("*63", "Desv�o en ocupado / no respuesta", "Desv�a el interno por ocupado y no contesta.<br>*63 + n�mero de interno al que se desea enrutar las llamadas<br>Para Desv�o a correo de voz *63 + #8"),
	array("*64", "Anulaci�n Desv�o", "Anula cualquiera de los Desv�os mencionados precedentemente."),
	array("*67", "Consulta Lista de Rellamadas", "Consulta internos que han llamado y no han respondido."),
	array("*70", "Marcaci�n ultimo N�mero (bis)", "Redisca �ltimo n�mero marcado."),
	array("*72", "Captura Llamada Dirigida", "Captura un llamado a un interno especificado<br>*72 + n�mero de interno que se desea capturar."),
	array("*73", "Captura Llamada de Grupo", "Captura llamadas de internos que pertenecen a un mismo grupo."),
	array("*74", "Consulta Llamada en Espera", "Permite tomar una llamada en espera mientras se esta en comunicaci�n."),
	array("*75", "Retenci�n / Recuperaci�n", "*75 se estaciona una llamada en ese interno.<br>*75 + n�mero de interno. se estaciona en el interno marcado.<br>Desde cualquier otro interno se puede tomar esa llamada marcando el c�digo m�s el numero en el que se estaciono la llamada."),
	array("*76", "Protecci�n contra los Beeps", ""),
	array("*770", "Programaci�n del contraste", "Permite regular el contraste del display de los tel�fonos digitales *770 + flechas laterales."),
	array("*771", "Programaci�n direct. indiv. term. analog.", "Permite almacenar n�meros telef�nicos en el teclado num�rico de los tel�fonos anal�gicos *771 + n�mero de tecla + n�mero de interno.<br>*771 + n�mero de tecla + 9 + n�mero externo"),
	array("*772", "Uso direct. indiv. term. analog.", "*772 + n�mero de tecla. Marca n�mero almacenado."),
	array("*773", "Cita/ despertador", "*773 + hora. min (24 hs.)"),
	array("*774", "Anul. Cita/ despertador", "Anula operaci�n anterior"),
	array("*777", "Candado", "Para activar *777<br>Para desactivar *777 + 0000 (Clave por defecto)"),
	array("*778", "Modificaci�n Contrase�a", "Para cambiar la clave anterior."),
	array("*66", "Anula Desv�o a Correo de Voz", "Desactiva el desv�o autom�tico al correo de voz."),
	array("*68", "Consulta �ltima Llamada", "Permite ver la �ltima llamada recibida."));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<title>..:: INSTRUCTIVO TELEF�NICO | PROVINCIA ART ::..</title>
		<link href="/css/grid.css" rel="stylesheet" type="text/css" />
		<link href="/css/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css"> 
			body, html {scrollbar-3dlight-color:#eee; scrollbar-arrow-color:#eee; scrollbar-darkshadow-color:#fff; scrollbar-face-color:#aaa; scrollbar-highlight-color:#aaa;
									scrollbar-shadow-color:#aaa; scrollbar-track-color:#e3e3e3;}
			.tableDatos {color:#000; font-family:Arial; font-size:12px;}
			.trDatos {color:#fff; font-family:Arial; font-size:10pt;}
			.trTitulo {color:#fff; font-family:Arial; font-size:14px; font-weight:bold;}
		</style>
	</head>
	<body link="#444" alink="#444" vlink="#444">
		<div align="center">
			<img src="/modules/usuarios/images/top.gif" style="margin-top:25px;">
		</div>
		<div align="center">
			<table cellspacing="0" class="tableDatos" width="699">
				<tr>
					<td colspan="3" height="2"></td>
				</tr>
				<tr bgcolor="#0485BF" class="trTitulo">
					<td align="center" width="9%">N�mero</td>
					<td align="center" width="38%">Significaci�n Prefijo</td>
					<td align="center" width="53%">Informaci�n Prefijo</td>
				</tr>
				<tr>
					<td colspan="3" height="1"></td>
				</tr>
	<?
	foreach($arrCols as $cols) {
	?>
				<tr bgcolor="#807F84" class="gridFondoOnMouseOver trDatos" style="cursor:default;">
					<td align="center" width="9%"><?= $cols[0]?></td>
					<td align="center" width="38%"><?= $cols[1]?></td>
					<td align="left" width="53%"><?= $cols[2]?></td>
				</tr>
				<tr>
					<td colspan="3" height="1"></td>
				</tr>
	<?
	}
	?>
			</table>

			<table width="699" align="center" cellpadding="0" cellspacing="0" bordercolor="#EEEEEE" bgcolor="#EEEEEE">
				<tr>
					<td width="563" height="5" valign="bottom" bgcolor="#EEEEEE"></td>
				</tr>
			</table>
			<map name="Intranet" id="Intranet"><area shape="rect" coords="6,0,187,36" href="/" target="_blank" /></map>
		</div>
	</body>
</html>