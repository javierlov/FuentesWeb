<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/DataBase/DB.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/DataBase/DB_Funcs.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Consulta Internos</title>
<style type="text/css"> 
body{ 
scrollbar-face-color: #aaaaaa;  
scrollbar-highlight-color: #aaaaaa;  
scrollbar-shadow-color: #aaaaaa;  
scrollbar-3dlight-color: #eeeeee; 
scrollbar-arrow-color: #eeeeee;  
scrollbar-track-color: #e3e3e3;  
scrollbar-darkshadow-color: ffffff; 
} 
</style>
<script language="JavaScript" src="/Js/functions.js"></script>
</head>

<body topmargin="3" leftmargin="1" rightmargin="1">

<?
$sql =
	"SELECT TO_CHAR(se_fechacumple, 'dd/mm') cumple, cse3.se_descripcion gerencia, useu.se_contrato, useu.se_delegacion,
					useu.se_ejex, useu.se_ejey, useu.se_foto, useu.se_id, useu.se_interno, useu.se_nombre, useu.se_piso,
					cse.se_descripcion sector
  	 FROM use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
	  WHERE useu.se_idsector = cse.se_id
  	  AND cse.se_idsectorpadre = cse2.se_id
      AND cse2.se_idsectorpadre = cse3.se_id
      AND useu.se_id = ".$_REQUEST["id"]."
 ORDER BY useu.se_nombre";

$stmt = DBExecSql($conn, $sql);
$row = DBGetQuery($stmt);

$rutaFoto = IMAGES_FOTOS_RELATIVE_PATH."cartel.jpg";
if (is_file(IMAGES_FOTOS_PATH.$row["SE_FOTO"]))
	$rutaFoto = IMAGES_FOTOS_RELATIVE_PATH.$row["SE_FOTO"];

$iconTitila = IMAGES_BUSCAR_USUARIOS_RELATIVE_PATH."dentro_del_edificio.gif";
$iconRef = IMAGES_BUSCAR_USUARIOS_RELATIVE_PATH."referencia_azul.gif";
$strEstado = "E";
$strInOut = "Dentro del Edificio";
if ($row["SE_CONTRATO"] == 1) {
	if ($strEstado != "E") {
		$iconTitila = IMAGES_BUSCAR_USUARIOS_RELATIVE_PATH."fuera_del_edificio.gif";
		$iconRef = IMAGES_BUSCAR_USUARIOS_RELATIVE_PATH."referencia_rojo.gif";
		$strInOut = "Fuera del Edificio";
	}
}
else
	$strInOut = "(No Registra Datos)";

if ($row["SE_PISO"] != "")
	$strMapa = IMAGES_MAPAS_RELATIVE_PATH."piso".$row["SE_PISO"].".gif";
else {
	$sql =
		"SELECT el_mapa
  		 FROM del_delegacion
 			WHERE el_id = ".$row["SE_DELEGACION"];

	$strMapa = IMAGES_MAPAS_RELATIVE_PATH.ValorSql($sql);
}
?>
<div align="center">
<table border="0" width="770" cellspacing="0" cellpadding="0">
	<tr>
		<td align="right" valign="bottom">
			<div align="right">
				<table border="0" width="139" height="132" id="table1" background="/Images/Buscar_Usuarios/marco.jpg">
					<tr>
						<td>
							<table border="0" width="130" height="127" id="table2" cellspacing="0" cellpadding="0">
								<tr>
									<td align="center"><img border="0" src="<?= $rutaFoto ?>" align="right" width="117" height="115"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>	
			</div>
		</td>
		<td align="left" width="330" valign="top">
			<table border="0" width="100%" cellpadding="0">
				<tr>
					<td colspan="2" height="16"><p><font face="Verdana" color="#336699"><b><?= $row["SE_NOMBRE"] ?></b></font><hr color="#C0C0C0" size="1"></td>
				</tr>
				<tr>
					<td width="9%"><img border="0" src="/Images/Buscar_Usuarios/tel.jpg" align="left" width="23" height="19"></td>
					<td width="89%"><b><font face="Verdana" color="#336699" size="2"><?= $row["SE_INTERNO"] ?></font></b></td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="1" cellpadding="0">
<?
if ($row["SE_PISO"] != "") {
?>
	<tr>
		<td width="4%"><p align="right" style="margin-left: 0.05cm"><img border="0" src="/Images/Buscar_Usuarios/viñeta.jpg" width="10" height="7"></td>
		<td width="53%"><p style="margin-left: 0.05cm; margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt"><font color="#808080">Piso: </font><font color="#336699"><b><?= $row["SE_PISO"] ?></b></font></font></td>
	</tr>
<?
}
?>
				<tr>
					<td width="4%"><p align="right" style="margin-left: 0.05cm"><img border="0" src="/Images/Buscar_Usuarios/viñeta.jpg" width="10" height="7"></td>
					<td width="53%" valign="top"><p style="margin-left: 0.05cm; margin-top: 0; margin-bottom: 0"><font color="#808080" face="Verdana" style="font-size: 8pt">Gerencia: </font><font face="Verdana" style="font-size: 8pt"><?= $row["GERENCIA"] ?></font></td>
				</tr>
				<tr>
					<td width="4%"><p align="right" style="margin-left: 0.05cm"><img border="0" src="/Images/Buscar_Usuarios/viñeta.jpg" width="10" height="7"></td>
					<td width="53%"><p style="margin-left: 0.05cm; margin-top: 0; margin-bottom: 0"><font color="#808080" face="Verdana" style="font-size: 8pt">Sector: </font><font face="Verdana" style="font-size: 8pt"><?= $row["SECTOR"] ?></font></td>
				</tr>
			</table>
			<table border="0" width="100%" cellspacing="1" cellpadding="0">
				<tr>
					<td width="4%"><p align="right"><img border="0" src="/Images/Buscar_Usuarios/viñeta.jpg" width="10" height="7"></td>
					<td width="53%"><p style="margin-left: 0.05cm; margin-top: 0; margin-bottom: 0"><font face="Verdana" style="font-size: 8pt" color="#808080">Cumpleaños</font><font face="Verdana" style="font-size: 8pt"><font color="#808080">: </font><?= $row["CUMPLE"] ?></font></td>
				</tr>
			</table>
		</td>
		<td align="left" width="301" rowspan="3">
<?
if ($row["SE_PISO"] != "") {
?>
	<div align="right">
		<table border="0" height="100%" id="table1" width="100%">
			<tr>
				<td align="left" valign="top"><img id="Mapa" name="Mapa" src="<?= $strMapa ?>" /><img border="0" id="Coordenada" name="Coordenada" src="<?= $iconTitila ?>" style="position: relative; left:0; top:0; width: 8px; height:8px"><p></td>
			</tr>
		</table>
	</div>	
<?
}
else {
?>
	<table border="0" width="315" height="276">
		<tr>
			<td><img border="0" src="<?= $strMapa ?>"></td>
		</tr>
	</table>
<?
}
?>
		</td>
	</tr>
	<tr>
		<td width="0"><p>&nbsp;</td>
		<td align="left" width="330" height="82" valign="top">
<?
if ($row["SE_PISO"] != "") {
?>
	<div align="left">
		<table border="0" width="100%" cellspacing="0" cellpadding="0">
			<tr>
				<td align="right" width="4%"><img border="0" src="<?= $iconRef?>" width="8" height="8"></td>
				<td width="336"><p style="margin-left: 0.1cm"><font face="Verdana" style="font-size: 8pt; font-weight:700" color="#336699"><?= $strInOut?></font></td>
			</tr>
		</table>
	</div>
<?
}
?>
			<table border="0" width="100%" cellpadding="0">
				<tr>
					<td><hr color="#C0C0C0" size="1"></td>			
				</tr>
				<tr>
					<td><input type="hidden" name="id" size="19" value="<?= $row["SE_ID"]?>"><input type="button" value="Informar Datos Erróneos" name="btnInformar" style="font-family: Verdana; font-size: 8pt; color: #FFFFFF; font-weight: bold; word-spacing: 0; width: 180; border: 1px solid #808080; margin: 0; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" onClick="OpenWindow('errores.php?id=<?= $row["SE_ID"]?>', 'intranetWindow', 320, 160, 'no')"></td>
				</tr>
			</table>
		</div>
</body>
<script>
	if (document.getElementById('Mapa') != null) {
		document.getElementById('Mapa').onload = function() {
		document.getElementById('Coordenada').style.left = <?= $row["SE_EJEX"]?> - document.getElementById('Mapa').width - 4;
		document.getElementById('Coordenada').style.top = <?= $row["SE_EJEY"]?> - document.getElementById('Mapa').height + 4;
		}
	}
</script>
</html>