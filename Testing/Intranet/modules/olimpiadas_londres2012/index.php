<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
		<title>.:: Olimpiadas de Valores | Provincia ART ::.</title>
		<script language="JavaScript" src="/js/functions.js"></script>
		<link rel="stylesheet" href="style/style.css" type="text/css">
		<script>
			function enviarVoto(esAlta, form) {
				if (esAlta)
					enviar = true;
				else
					enviar = confirm('¿ Realmente desea modificar su voto ?');

				if (enviar)
					form.submit();
			}
		</script>
	</head>

<body>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<table cellpadding="0" width="738" cellspacing="0" align="center">
	<tr>
		<td height="103" colspan="3"><map name="FPMap1">
		<area href="http://www.artprov.com.ar/" shape="rect" coords="38, 33, 189, 82">
		</map><img border="0" src="images/top.jpg" usemap="#FPMap1"></td>
	</tr>
	<tr>
		<td height="20px"></td>	
	</tr>
	<tr>
		<td align="right"><span class="fontCeleste"><b>></b></span> <a target="_blank" href="posiciones_1eraEtapa.php">VER RESULTADOS PARCIALES 1era. ETAPA</a></td>
	</tr>
	<tr>
		<td height="20px"></td>	
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/excelencia.jpg"></p>
			<p style="padding-left:15px; padding-right:20px">Una persona excelente se destaca por estar continuamente orientada a la mejora y a la superación de los objetivos, a la puesta en práctica de nuevas maneras de hacer las cosas que mejoren los procesos y las formas de trabajo. Se esfuerza por estar atento a las necesidades propias y las del grupo, genera instancias de comunicación e intercambio con otras áreas y toma la iniciativa cuando surge algo nuevo o difícil.</p>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td class="Txt" style="padding-left:20px">
<?
$params = array(":idvotante" => GetUserID());
$sql =
	"SELECT jo_motivo, jo_votado
		 FROM rrhh.rjo_jjoo2012
		WHERE jo_idvotante = :idvotante
			AND jo_valor = 'E'
			AND jo_fase = 2";
$stmt = DBExecSql($conn, $sql, $params);
$rowE = DBGetQuery($stmt);
?>
			<form action="/modules/olimpiadas_londres2012/procesar_voto.php" id="formExcelencia" method="post" name="formExcelencia" target="iframeProcesando">
				<input id="fase" name="fase" type="hidden" value="2" />
				<input id="valor" name="valor" type="hidden" value="E" />
				<table cellpadding="1" cellspacing="1" align="left">
					<tr>
						<td><select id="usuariosE" name="usuariosE" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;"></select></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><textarea id="motivoE" name="motivoE" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; height:60px; width:600px;" cols="20" rows="1"><?= $rowE["JO_MOTIVO"]?></textarea></td>
						<td valign="bottom">
							<img id="votoEOk" src="images/tilde.png" style="display:none; height:44px; width:44px;" />
							<img src="images/votar.jpg" style="cursor:hand;" onClick="enviarVoto(('<?= $rowE["JO_VOTADO"]?>' == ''), formExcelencia)" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/servicio.jpg"></p>
			<p style="padding-left:15px; padding-right:20px">La persona que representa el servicio es quien se distingue por estar siempre atento a las necesidades del cliente –interno y/o externo-, brindando siempre la mejor respuesta, manteniendo el buen trato y usando estas habilidades de manera positiva para el sector y/o la compañía.</p>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td class="Txt" style="padding-left:20px">
<?
$params = array(":idvotante" => GetUserID());
$sql =
	"SELECT jo_motivo, jo_votado
		 FROM rrhh.rjo_jjoo2012
		WHERE jo_idvotante = :idvotante
			AND jo_valor = 'S'
			AND jo_fase = 2";
$stmt = DBExecSql($conn, $sql, $params);
$rowS = DBGetQuery($stmt);
?>
			<form action="/modules/olimpiadas_londres2012/procesar_voto.php" id="formServicio" method="post" name="formServicio" target="iframeProcesando">
				<input id="fase" name="fase" type="hidden" value="2" />
				<input id="valor" name="valor" type="hidden" value="S" />
				<table cellpadding="1" cellspacing="1" align="left">
					<tr>
						<td><select id="usuariosS" name="usuariosS" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;"></select></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><textarea id="motivoS" name="motivoS" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; height:60px; width:600px;" cols="20" rows="1"><?= $rowS["JO_MOTIVO"]?></textarea></td>
						<td valign="bottom">
							<img id="votoSOk" src="images/tilde.png" style="display:none; height:44px; width:44px;" />
							<img src="images/votar.jpg" style="cursor:hand;" onClick="enviarVoto(('<?= $rowS["JO_VOTADO"]?>' == ''), formServicio)" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/integridad.jpg"></p>
			<p style="padding-left:15px; padding-right:20px">Una persona íntegra es quien manifiesta el compromiso diario con la organización y sus objetivos. Motiva el respeto mutuo entre compañeros y hacia el resto de las personas con las que interactúa. Tiene en cuenta los estados de ánimo del resto, y está atenta a ellos para apoyar o escuchar a los demás. Es confiable con aquellas cosas que promete y suele tener un consejo o palabra de aliento cuando hace falta.</p> 
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td class="Txt" style="padding-left:20px">
<?
$params = array(":idvotante" => GetUserID());
$sql =
	"SELECT jo_motivo, jo_votado
		 FROM rrhh.rjo_jjoo2012
		WHERE jo_idvotante = :idvotante
			AND jo_valor = 'I'
			AND jo_fase = 2";
$stmt = DBExecSql($conn, $sql, $params);
$rowI = DBGetQuery($stmt);
?>
			<form action="/modules/olimpiadas_londres2012/procesar_voto.php" id="formIntegridad" method="post" name="formIntegridad" target="iframeProcesando">
				<input id="fase" name="fase" type="hidden" value="2" />
				<input id="valor" name="valor" type="hidden" value="I" />
				<table cellpadding="1" cellspacing="1" align="left">
					<tr>
						<td><select id="usuariosI" name="usuariosI" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;"></select></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><textarea id="motivoI" name="motivoI" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; height:60px; width:600px;" cols="20" rows="1"><?= $rowI["JO_MOTIVO"]?></textarea></td>
						<td valign="bottom">
							<img id="votoIOk" src="images/tilde.png" style="display:none; height:44px; width:44px;" />
							<img src="images/votar.jpg" style="cursor:hand;" onClick="enviarVoto(('<?= $rowI["JO_VOTADO"]?>' == ''), formIntegridad)" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/solidaridad.jpg"></p>
			<p style="padding-left:15px; padding-right:20px">Una persona solidaria está atenta a las necesidades y novedades del área y también a las de los demás. Brinda su colaboración desinteresadamente y siempre se puede contar con su ayuda. Piensa en los demás y en sus necesidades o preferencias, y busca constantemente que el equipo se reúna, solucione los conflictos y fortalezca su auto estima.</p>
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td class="Txt" style="padding-left:20px">
<?
$params = array(":idvotante" => GetUserID());
$sql =
	"SELECT jo_motivo, jo_votado
		 FROM rrhh.rjo_jjoo2012
		WHERE jo_idvotante = :idvotante
			AND jo_valor = 'O'
			AND jo_fase = 2";
$stmt = DBExecSql($conn, $sql, $params);
$rowO = DBGetQuery($stmt);
?>
			<form action="/modules/olimpiadas_londres2012/procesar_voto.php" id="formSolidaridad" method="post" name="formSolidaridad" target="iframeProcesando">
				<input id="fase" name="fase" type="hidden" value="2" />
				<input id="valor" name="valor" type="hidden" value="O" />
				<table cellpadding="1" cellspacing="1" align="left">
					<tr>
						<td><select id="usuariosO" name="usuariosO" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;"></select></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><textarea id="motivoO" name="motivoO" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; height:60px; width:600px;" cols="20" rows="1"><?= $rowO["JO_MOTIVO"]?></textarea></td>
						<td valign="bottom">
							<img id="votoOOk" src="images/tilde.png" style="display:none; height:44px; width:44px;" />
							<img src="images/votar.jpg" style="cursor:hand;" onClick="enviarVoto(('<?= $rowO["JO_VOTADO"]?>' == ''), formSolidaridad)" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="Txt">
			<p><img src="images/entusiasmo.jpg"></p>
			<p style="padding-left:15px; padding-right:20px">La persona entusiasta muestra actitud en la consecución de los objetivos. Motiva y brinda aliento a los demás para alcanzar las metas propias y de la organización. Siempre tiene “buena onda” y ve la parte positiva de las cosas. En los momentos difíciles, es el que mantiene la entereza y busca la unión del grupo para superar los inconvenientes.</p>  
		</td>
	</tr>
	<tr>
		<td height="10"></td>
	</tr>
	<tr>
		<td class="Txt" style="padding-left:20px">
<?
$params = array(":idvotante" => GetUserID());
$sql =
	"SELECT jo_motivo, jo_votado
		 FROM rrhh.rjo_jjoo2012
		WHERE jo_idvotante = :idvotante
			AND jo_valor = 'N'
			AND jo_fase = 2";
$stmt = DBExecSql($conn, $sql, $params);
$rowN = DBGetQuery($stmt);
?>
			<form action="/modules/olimpiadas_londres2012/procesar_voto.php" id="formEntusiasmo" method="post" name="formEntusiasmo" target="iframeProcesando">
				<input id="fase" name="fase" type="hidden" value="2" />
				<input id="valor" name="valor" type="hidden" value="N" />
				<table cellpadding="1" cellspacing="1" align="left">
					<tr>
						<td><select id="usuariosN" name="usuariosN" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;"></select></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td><textarea id="motivoN" name="motivoN" style="border:1px solid #676767; color:#676767; font-size:9pt; font-family:Neo Sans; margin-right:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px; height:60px; width:600px;" cols="20" rows="1"><?= $rowN["JO_MOTIVO"]?></textarea></td>
						<td valign="bottom">
							<img id="votoNOk" src="images/tilde.png" style="display:none; height:44px; width:44px;" />
							<img src="images/votar.jpg" style="cursor:hand;" onClick="enviarVoto(('<?= $rowN["JO_VOTADO"]?>' == ''), formEntusiasmo)" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
	<tr>
		<td height="25px"></td>
	</tr>	
	<tr>
		<td><img src="images/footer.jpg"></td>
	</tr>
</table>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "usuariosE";
$RCparams = array(":usuario" => GetWindowsLoginName(true));
$RCquery = 
	"SELECT se_id id, se_nombre detalle
     FROM use_usuarios
    WHERE se_fechabaja IS NULL
      AND se_usuariogenerico = 'N'
      AND se_idsector IS NOT NULL
			AND se_usuario <> :usuario
			AND (SELECT COUNT(*)
						 FROM rrhh.rjo_jjoo2012
						WHERE jo_valor = 'E'
							AND jo_fase = 1
							AND jo_votado = se_id
							AND jo_fechabaja IS NULL) > 1
 ORDER BY 2";
$RCselectedItem = $rowE["JO_VOTADO"];
FillCombo(true, 0, "-- Seleccionar compañero --");

$RCfield = "usuariosS";
$RCparams = array(":usuario" => GetWindowsLoginName(true));
$RCquery = 
	"SELECT se_id id, se_nombre detalle
     FROM use_usuarios
    WHERE se_fechabaja IS NULL
      AND se_usuariogenerico = 'N'
      AND se_idsector IS NOT NULL
			AND se_usuario <> :usuario
			AND (SELECT COUNT(*)
						 FROM rrhh.rjo_jjoo2012
						WHERE jo_valor = 'S'
							AND jo_fase = 1
							AND jo_votado = se_id
							AND jo_fechabaja IS NULL) > 1
 ORDER BY 2";
$RCselectedItem = $rowS["JO_VOTADO"];
FillCombo(true, 0, "-- Seleccionar compañero --");

$RCfield = "usuariosI";
$RCparams = array(":usuario" => GetWindowsLoginName(true));
$RCquery = 
	"SELECT se_id id, se_nombre detalle
     FROM use_usuarios
    WHERE se_fechabaja IS NULL
      AND se_usuariogenerico = 'N'
      AND se_idsector IS NOT NULL
			AND se_usuario <> :usuario
			AND (SELECT COUNT(*)
						 FROM rrhh.rjo_jjoo2012
						WHERE jo_valor = 'I'
							AND jo_fase = 1
							AND jo_votado = se_id
							AND jo_fechabaja IS NULL) > 1
 ORDER BY 2";
$RCselectedItem = $rowI["JO_VOTADO"];
FillCombo(true, 0, "-- Seleccionar compañero --");

$RCfield = "usuariosO";
$RCparams = array(":usuario" => GetWindowsLoginName(true));
$RCquery = 
	"SELECT se_id id, se_nombre detalle
     FROM use_usuarios
    WHERE se_fechabaja IS NULL
      AND se_usuariogenerico = 'N'
      AND se_idsector IS NOT NULL
			AND se_usuario <> :usuario
			AND (SELECT COUNT(*)
						 FROM rrhh.rjo_jjoo2012
						WHERE jo_valor = 'O'
							AND jo_fase = 1
							AND jo_votado = se_id
							AND jo_fechabaja IS NULL) > 1
 ORDER BY 2";
$RCselectedItem = $rowO["JO_VOTADO"];
FillCombo(true, 0, "-- Seleccionar compañero --");

$RCfield = "usuariosN";
$RCparams = array(":usuario" => GetWindowsLoginName(true));
$RCquery = 
	"SELECT se_id id, se_nombre detalle
     FROM use_usuarios
    WHERE se_fechabaja IS NULL
      AND se_usuariogenerico = 'N'
      AND se_idsector IS NOT NULL
			AND se_usuario <> :usuario
			AND (SELECT COUNT(*)
						 FROM rrhh.rjo_jjoo2012
						WHERE jo_valor = 'N'
							AND jo_fase = 1
							AND jo_votado = se_id
							AND jo_fechabaja IS NULL) > 1
 ORDER BY 2";
$RCselectedItem = $rowN["JO_VOTADO"];
FillCombo(true, 0, "-- Seleccionar compañero --");
?>
	document.getElementById('usuariosE').focus();
</script>
</body>
</html>