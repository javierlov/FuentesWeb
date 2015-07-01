<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


define("MAX_ROWS", 6);
?>
<script>
	showTitle(true, 'NOVEDADES');
</script>
<body link="#807F84" vlink="#807F84" alink="#807F84">
<link href="/modules/novedades/css/style_novedades.css" rel="stylesheet" type="text/css" />
	<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-left: 7px">
		<tr>
			<td rowspan="2" width="450px" valign="top">
				<table width="100%">
					<tr>
						<td bgcolor="#807F84" class="FormLabelBlanco">&nbsp;Ingresos</td>
					</tr>
<?	
$params = array(":numrow" => MAX_ROWS);
$sql =
	"SELECT se_foto, se_id, se_nombre, sectorhasta
		 FROM (SELECT useu.se_foto, useu.se_id, useu.se_nombre, se2.se_descripcion sectorhasta
						 FROM rrhh.rhn_novedades, use_usuarios useu, computos.cse_sector se2
						WHERE hn_idusuario = useu.se_id
							AND hn_idsectorhasta = se2.se_id(+)
							AND hn_tipomovimiento = 'A'
							AND hn_fechabaja IS NULL
				 ORDER BY hn_fechaalta DESC)
		WHERE ROWNUM <= :numrow";
$stmt = DBExecSql($conn, $sql, $params);

$par = true;
while ($row = DBGetQuery($stmt)) {
	$par = !$par;

	$rutaFoto = base64_encode(IMAGES_FOTOS_PATH."cartel.jpg");
	$foto = $row["SE_FOTO"];

	if (is_file(IMAGES_FOTOS_PATH.$foto)) {
		$rutaFoto = base64_encode(IMAGES_FOTOS_PATH.$foto);
	}
?>
				<tr>
					<td bgcolor="<?= ($par)?"#EAEAEA":"#FFFFFF"?>" id="tdItems"?>
						<div style="margin-right: 1px"><img style="border-color:#8C8C8C" border="1" src="<?= "/functions/get_image.php?file=".$rutaFoto ?>" align="right" width="30" height="30"></div>
						<font color="#807F84"><a href="/index.php?pageid=56&id=<?= $row["SE_ID"]?>" style="text-decoration: none"?><i><?= $row["SE_NOMBRE"]?></i></a><br><?= $row["SECTORHASTA"]?>.</font>
					</td>
				</tr>
<?
}
?>
			</table>			
		</td>
		<td rowspan="2" width="40px" valign="top">&nbsp;</td>
		<td>
			<table width="100%">
				<tr>
					<td bgcolor="#807F84" class="FormLabelBlanco">&nbsp;Pases de Sector</td>
				</tr>
<?
$params = array(":numrow" => MAX_ROWS);
$sql =
	"SELECT se_id, se_nombre, sectordesde, sectorhasta
		 FROM (SELECT useu.se_id, useu.se_nombre, se1.se_descripcion sectordesde, se2.se_descripcion sectorhasta
						 FROM rrhh.rhn_novedades, use_usuarios useu, computos.cse_sector se1, computos.cse_sector se2
						WHERE hn_idusuario = useu.se_id
							AND hn_idsectordesde = se1.se_id(+)
							AND hn_idsectorhasta = se2.se_id(+)
							AND hn_tipomovimiento = 'M'
							AND hn_fechabaja IS NULL
				 ORDER BY hn_fechaalta DESC)
		WHERE ROWNUM <= :numrow";
$stmt = DBExecSql($conn, $sql, $params);

$par = true;
while ($row = DBGetQuery($stmt)) {
	$par = !$par;
?>
				<tr>
					<td bgcolor="<?= ($par)?"#EAEAEA":"#FFFFFF"?>" id="tdItems"?>
						<font color="#807F84"><a href="/index.php?pageid=56&id=<?= $row["SE_ID"]?>" style="text-decoration: none"?><i><?= $row["SE_NOMBRE"]?></i></a> <br>Pasa de <?= $row["SECTORDESDE"]?> a <?= $row["SECTORHASTA"]?>.</font>
					</td>
				</tr>	
<?
}
?>
			</table>			
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td height="10px"></td>
				</tr>
				<tr>
					<td bgcolor="#807F84" class="FormLabelBlanco">&nbsp;Egresos</td>
				</tr>
<?
$params = array(":numrow" => MAX_ROWS);
$sql =
	"SELECT se_nombre
		 FROM (SELECT se_nombre
						 FROM rrhh.rhn_novedades, use_usuarios
						WHERE hn_idusuario = se_id
							AND hn_tipomovimiento = 'B'
							AND hn_fechabaja IS NULL
				 ORDER BY hn_fechaalta DESC)
		WHERE ROWNUM <= :numrow";
$stmt = DBExecSql($conn, $sql, $params);

$par = true;
while ($row = DBGetQuery($stmt)) {
	$par = !$par;
?>
				<tr>
					<td bgcolor="<?= ($par)?"#EAEAEA":"#FFFFFF"?>" id="tdItems"?><font color="#807F84"><i><?= $row["SE_NOMBRE"]?></i>.</font></td>
				</tr>
<?
}
?>
			</table>
		</td>
	</tr>
</table>