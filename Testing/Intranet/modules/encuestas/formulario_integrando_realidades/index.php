<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");

$params = array(":idusuario" => GetUserID());
$sql =
	"SELECT *
		 FROM intra.oir_integrandorealidades
		WHERE ir_idusuario = :idusuario";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$existeRegistro = ($row["IR_ID"] != "");
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Subject" content="Intranet - Formulario Integrando Realidades" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<title>Formulario Integrando Realidades</title>
		<style type="text/css"> 
			body {
				scrollbar-face-color: #aaaaaa;
				scrollbar-highlight-color: #aaaaaa;
				scrollbar-shadow-color: #aaaaaa;
				scrollbar-3dlight-color: #eeeeee;
				scrollbar-arrow-color: #eeeeee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: #ffffff;
			}
		</style>
		<script language="JavaScript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/ventana.js"></script>
	</head>
<body bgcolor="#808080">
<form action="procesar.php" id="formIntegrando" method="POST" name="formIntegrando" onSubmit="return validarForm()">
<div align="center">
	<table border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>&nbsp;</td>
			<td bgcolor="#FFFFFF">
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td align="center"><p align="center"><img border="0" src="images/encabezado.jpg" width="698" height="60" align="middle" hspace="0"></td>
					</tr>
					<tr>
						<td height="10"></td>
					</tr>
				</table>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td width="82"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/bienvenido.jpg" width="72" height="15"></td>
						<td width="232"><font color="#336699" face="Verdana"><span style="font-size: 8pt; font-weight: 700">&nbsp;<?= GetUserName()?></span></font></td>
						<td width="48"><img border="0" src="images/sector.jpg" width="51" height="15"></td>
						<td width="336"><font color="#336699" face="Verdana"><span style="font-size: 8pt; font-weight: 700">&nbsp;<?= GetUserSectorNuevo()?></span></font></td>
					</tr>
				</table>
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p1.jpg"></td>
					</tr>
					<tr>
						<td width="4%"><p style="margin-left: 15px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/SI.jpg" width="20" height="20"></td>
						<td width="2%"><input id="p1" name="p1" type="radio" value="S" onClick="seleccionaPrimerPregunta()" <?= (($existeRegistro) and ($row["IR_RESPUESTA1"] == "S"))?"checked":""?>></td>
						<td width="1%">&nbsp;</td>
						<td width="3%"><img border="0" src="images/NO.jpg" width="20" height="20"></td>
						<td width="3%"><input id="p1" name="p1" type="radio" value="N" onClick="seleccionaPrimerPregunta()" <?= (($existeRegistro) and ($row["IR_RESPUESTA1"] == "N"))?"checked":""?>></td>
						<td width="84%">&nbsp;</td>
					</tr>
				</table>
				<table>	
					<tr>
						<td width="188" colspan="2"><img border="0" src="images/2.jpg" width="354" height="15"></td>
						<td><p align="center">&nbsp;</td>
						<td width="6%"><p align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="188" colspan="2"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p2.jpg" width="604" height="22"></td>
						<td><p align="center"><input id="check1" name="check1" type="checkbox" <?= (($existeRegistro) and ($row["IR_OPCION1"] == "S"))?"checked":""?>></p></td>
						<td width="6%"><p align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="188" colspan="2"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p3.jpg" width="396" height="16"></td>
						<td><p align="center"><input id="check2" name="check2" type="checkbox" value="ON" <?= (($existeRegistro) and ($row["IR_OPCION2"] == "S"))?"checked":""?>></td>
						<td width="6%"><p align="center">&nbsp;</td>	
					</tr>
					<tr>
						<td width="188" colspan="2"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p4.jpg" width="540" height="19"></td>
						<td><p align="center"><input id="check3" name="check3" type="checkbox" value="ON" <?= (($existeRegistro) and ($row["IR_OPCION3"] == "S"))?"checked":""?>></td>
						<td width="6%"><p align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="188" colspan="2"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p5.jpg" width="300" height="20"></td>
						<td><p align="center"><input id="check4" name="check4" type="checkbox" value="ON" <?= (($existeRegistro) and ($row["IR_OPCION4"] == "S"))?"checked":""?>></td>
						<td width="6%"><p align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="188" colspan="2"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p6.jpg" width="342" height="20"></td>
						<td><p align="center"><input id="check5" name="check5" type="checkbox" value="ON" <?= (($existeRegistro) and ($row["IR_OPCION5"] == "S"))?"checked":""?>></td>
						<td width="6%"><p align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="188" colspan="2"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p7.jpg" width="597" height="22"></td>
						<td><p align="center"><input id="check6" name="check6" type="checkbox" value="ON" <?= (($existeRegistro) and ($row["IR_OPCION6"] == "S"))?"checked":""?>></td>
						<td width="6%"><p align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="15%" valign="top"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p8.jpg" width="93" height="21"></td>
						<td width="73%"><textarea cols="80" id="Otras" name="Otras" rows="3" style="color: #808080; font-size: 8pt; font-family: Verdana"><?= ($existeRegistro)?$row["IR_OTRAS"]:""?></textarea></td>
						<td colspan="2">&nbsp;</td>
					</tr>
				</table>	
				<table border="0" width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td colspan="6"><p style="margin-left: 10px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/p9.jpg" width="301" height="16"></td>
					</tr>
					<tr>
						<td width="4%"><p style="margin-left: 15px; margin-top: 0; margin-bottom: 0"><img border="0" src="images/SI.jpg" width="20" height="20"></td>
						<td width="2%"><input id="p2" name="p2" type="radio" value="S" <?= (($existeRegistro) and ($row["IR_RESPUESTA2"] == "S"))?"checked":""?>></td>
						<td width="1%">&nbsp;</td>
						<td width="3%"><img border="0" src="images/NO.jpg" width="20" height="20"></td>
						<td width="3%"><input id="p2" name="p2" type="radio" value="N" <?= (($existeRegistro) and ($row["IR_RESPUESTA2"] == "N"))?"checked":""?>></td>
						<td width="84%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6"><p align="left">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6"><p align="center"><input type="submit" value="Enviar Datos" name="B1" style="color: #808080; font-size: 8pt; font-family: Verdana; font-weight: bold; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; text-align:center"></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td><map name="FPMap0"><area href="mailto:integrando@provart.com.ar" shape="rect" coords="482, 21, 635, 35"></map><img border="0" src="images/pie.jpg" width="698" height="43" usemap="#FPMap0"></td>
					</tr>
				</table>
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td><img border="0" src="images/pie_con_logo.jpg" width="698" height="111" hspace="0" align="middle"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</div>
<?
if (isset($_REQUEST["result"]))
	require_once("ventana_flotante.php");
?>
</FORM>
</body>
<?
if ((!$existeRegistro) or ($row["IR_RESPUESTA1"] != "S")) {
?>
	<script>
		seleccionaPrimerPregunta();
	</script>
<?
}
?>
</html>