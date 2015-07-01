<?
// El array contiene las 3 columnas de la tabla..
$arrCols = array(
	array("#8", "Consulta Mensajería Vocal", "Permite levantar los mensajes que se dejan en el correo de voz"),
	array("*60", "Desvío Inmediato", "Desvía el interno siempre:<br>*60 + numero de interno al que se desea enrutar las llamadas<br>Para Desvío a correo de voz *60 + #8"),
	array("*61", "Desvío sobre Ocupado", "Desvía el interno por ocupado.<br>*61 + número de interno al que se desea enrutar las llamadas<br>Para Desvío a correo de voz *63 + #8"),
	array("*62", "Desvío sobre No Respuesta", "Desvía el interno por no contesta.<br>*62 + número de interno al que se desea enrutar las llamadas<br>Para Desvío a correo de voz *62 + #8"),
	array("*63", "Desvío en ocupado / no respuesta", "Desvía el interno por ocupado y no contesta.<br>*63 + número de interno al que se desea enrutar las llamadas<br>Para Desvío a correo de voz *63 + #8"),
	array("*64", "Anulación Desvío", "Anula cualquiera de los Desvíos mencionados precedentemente."),
	array("*67", "Consulta Lista de Rellamadas", "Consulta internos que han llamado y no han respondido."),
	array("*70", "Marcación ultimo Número (bis)", "Redisca último número marcado."),
	array("*72", "Captura Llamada Dirigida", "Captura un llamado a un interno especificado<br>*72 + número de interno que se desea capturar."),
	array("*73", "Captura Llamada de Grupo", "Captura llamadas de internos que pertenecen a un mismo grupo."),
	array("*74", "Consulta Llamada en Espera", "Permite tomar una llamada en espera mientras se esta en comunicación."),
	array("*75", "Retención / Recuperación", "*75 se estaciona una llamada en ese interno.<br>*75 + número de interno. se estaciona en el interno marcado.<br>Desde cualquier otro interno se puede tomar esa llamada marcando el código más el numero en el que se estaciono la llamada."),
	array("*76", "Protección contra los Beeps", ""),
	array("*770", "Programación del contraste", "Permite regular el contraste del display de los teléfonos digitales *770 + flechas laterales."),
	array("*771", "Programación direct. indiv. term. analog.", "Permite almacenar números telefónicos en el teclado numérico de los teléfonos analógicos *771 + número de tecla + número de interno.<br>*771 + número de tecla + 9 + número externo"),
	array("*772", "Uso direct. indiv. term. analog.", "*772 + número de tecla. Marca número almacenado."),
	array("*773", "Cita/ despertador", "*773 + hora. min (24 hs.)"),
	array("*774", "Anul. Cita/ despertador", "Anula operación anterior"),
	array("*777", "Candado", "Para activar *777<br>Para desactivar *777 + 0000 (Clave por defecto)"),
	array("*778", "Modificación Contraseña", "Para cambiar la clave anterior."),
	array("*66", "Anula Desvío a Correo de Voz", "Desactiva el desvío automático al correo de voz."),
	array("*68", "Consulta Última Llamada", "Permite ver la última llamada recibida."));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<title>..:: INSTRUCTIVO TELEFÓNICO | PROVINCIA ART ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css"> 
			body,html { 
				scrollbar-face-color: #aaaaaa;  
				scrollbar-highlight-color: #aaaaaa;  
				scrollbar-shadow-color: #aaaaaa;  
				scrollbar-3dlight-color: #eeeeee; 
				scrollbar-arrow-color: #eeeeee;  
				scrollbar-track-color: #e3e3e3;  
				scrollbar-darkshadow-color: ffffff; 
			} 
		</style> 
	</head>
	<body link="#444444" alink="#444444" vlink="#444444">
		<div align="center">
			<img border="0" src="/modules/buscar_usuarios/images/top.gif" style="margin-top:25px;">
		</div>
		<div align="center">
			<table border="0" cellspacing="0" class="FormLabelNegroSinNegrita12" width="699">
				<tr>
					<td colspan="3" height="2"></td>
				</tr>
				<tr bgcolor="#0485BF" class="FormLabelBlancoGrande">
					<td align="center" width="9%">Número</td>
					<td align="center" width="38%">Significación Prefijo</td>
					<td align="center" width="53%">Información Prefijo</td>
				</tr>
				<tr>
					<td colspan="3" height="1"></td>
				</tr>
	<?
	foreach($arrCols as $cols) {
	?>
				<tr bgcolor="#807F84" class="FondoOnMouseOver FormLabelBlanco11" style="cursor:default;">
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

			<table width="699" border="0" align="center" cellpadding="0" cellspacing="0" bordercolor="#EEEEEE" bgcolor="#EEEEEE">
				<tr>
					<td colspan="2" height="3" bgcolor="#FFFFFF"></td>
				</tr>
				<tr>
						<td width="563" height="58" valign="bottom" bgcolor="#EEEEEE">
							<div align="right">
								<blockquote>
							<p align="left"><font size="2" face="neo sans"><a href="/" target="_blank">Intranet</a> | <a href="mailto:marketing@provart.com.ar" target="_blank">Consultas</a> | <a href="http://www.provinciart.com.ar" target="_blank">Web</a></font></p>
								</blockquote>
						</div>
					</td>
					<td width="137" bgcolor="#EEEEEE"><img src="images/Bottom.jpg" width="200" height="33" border="0" align="absbottom" usemap="#Intranet" /></td>
				</tr>
			</table>
			<map name="Intranet" id="Intranet"><area shape="rect" coords="6,0,187,36" href="/" target="_blank" /></map>
		</div>
	</body>
</html>