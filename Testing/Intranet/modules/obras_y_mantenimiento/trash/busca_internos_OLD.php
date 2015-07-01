<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/DataBase/DB.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Agenda Telefónica</title>
<base target="_self">
<script>
function mOvr(src, clrOver) {
	if (!src.contains(event.fromElement)) {
		src.style.cursor = 'default';
		src.bgColor = clrOver;
	}
}

function mOut(src, clrIn) {
	if (!src.contains(event.toElement)) {
		src.style.cursor = 'default';
		src.bgColor = clrIn;
	}
}

function mClk(src) {
	if(event.srcElement.tagName == 'TD'){
		src.children.tags('A')[0].click();
	}
}
</script>
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

</head>

<body link="#000080" vlink="#000080" alink="#000080" topmargin="1" rightmargin="3" leftmargin="1" bottommargin="1">
<STYLE>
BODY { font-family:Verdana; font-size:8pt; }
</STYLE>

<form action="<?= $_SERVER["REQUEST_URI"]?>" method="get">

<font face="Verdana">

<?
$strURL = $_SERVER["REQUEST_URI"];		//The URL of this page so the form will work no matter what this file is named.

$strSearch = "";
if (isset($_REQUEST["search"]))
	$strSearch = $_REQUEST["search"];

$pepe = "";
if (isset($_REQUEST["Sch"]))
	$pepe = $_REQUEST["Sch"];
?>
</font>
<div align="center">
  <table border="0" width="770" cellspacing="1">
	<tr>
		<td width="100">
		&nbsp;</td>
		<td>
		<p align="right" style="margin-top: 0; margin-bottom: 0">
		<font face="Verdana">
		<a target="_blank" href="http://www.paginasdoradas.com.ar/Telefonos.action">
		<img border="0" src="/Images/Buscar_Usuarios/pag_amarillas.gif" width="21" height="22" align="center"></a></font></td>
		<td width="250" style="border-left-style: solid; border-left-width: 1px; padding-left: 4px; padding-right: 4px">
        <p align="left" style="margin-top: 0; margin-bottom: 0">
        <b><font color="#808080" face="Verdana" size="1">&nbsp;Guía Telefónica: 
		Páginas Doradas</font></b></td>
		<td width="86">
		&nbsp;</td>
		<td width="86">
		<p align="right" style="margin-top: 0; margin-bottom: 0">
        <font face="Verdana">
        <a target="_blank" href="http://ntdata/gps/Instructivo%20telefonico/Instructivo%20telefonico.htm">
		<img border="0" src="/Images/Buscar_Usuarios/tel.gif" width="25" height="21"></a></font></td>
		<td width="394" style="border-left-style: solid; border-left-width: 1px; padding-left: 4px; padding-right: 4px">
        <p style="margin-top: 0; margin-bottom: 0">
        <b>
		<font color="#808080" face="Verdana" size="1">Instructivo Telefónico</font></b></td>
	</tr>
	</table>
	<table border="0" width="770">
		<tr>
			<td bgcolor="#C0C0C0">
			<p align="center" style="margin-top: 0; margin-bottom: 0"><b>
		<font face="Verdana" size="2" color="#FFFFFF">Nombre o Apellido: </font>
		</b> 
<input name="search" value="<?= $strSearch ?>" size="20" style="color: #808080; font-size: 8pt; font-family: Verdana; font-weight: bold" /><b><font size="2" color="#FFFFFF" face="Verdana">&nbsp; Sector 
		o Gerencia:
</font><font size="2" color="#808080" face="Verdana">
<input name="Sch" value="<?= $pepe ?>" size="20" style="font-family: Verdana; font-size: 8pt; font-weight: bold; color: #808080"></font><font size="2" color="#FFFFFF" face="Verdana">&nbsp; </font></b>


<input type="submit" value="Buscar..." style="border:1px solid #999999; color: #808080; font-size: 8pt; font-family: Verdana; font-weight: bold; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; background-color:#FFFFFF" /></td>
		</tr>
	</table>
</div>
<div align="center">
<table border="0" width="770" cellspacing="0">
	<tr>
		<td width="256">
		<p align="center" style="margin-top: 0; margin-bottom: 0">
						<map name="FPMap0">
						<area href="mailto:mesadeayuda@provart.com.ar" shape="rect" coords="66, 28, 220, 40">
						</map>
						<img border="0" src="/Images/Buscar_Usuarios/banner_mesa_de_ayuda.gif" width="223" height="42" usemap="#FPMap0"></td>
		<td width="256" valign="top">
		<p align="center" style="margin-top: 0; margin-bottom: 0">
		<font face="Verdana">
					<img border="0" src="/Images/Buscar_Usuarios/banner_vigilancia.gif" width="191" height="29" align="center"></font></td>
		<td valign="top" width="257">
		<p align="center" style="margin-top: 0; margin-bottom: 0">
					<map name="FPMap1">
					<area href="mailto:mantenimiento@provart.com.ar" shape="rect" coords="52, 29, 214, 41">
					</map>
					<img border="0" src="/Images/Buscar_Usuarios/banner_mantenimiento.gif" width="215" height="42" usemap="#FPMap1"></td>
	</tr>
</table>
</div>
</form>
<?
$where = "";
if ($strSearch != "")
	$where.= " AND UPPER(useu.se_nombre) LIKE UPPER('%".$strSearch."%')";
if ($pepe != "")
	$where.= " AND (UPPER(cse3.se_descripcion) LIKE UPPER('%".$pepe."%') OR UPPER(cse.se_descripcion) LIKE UPPER('%".$pepe."%'))";
if ($where == "")
	$where = " AND 1 = 2";

$sql =
	"SELECT cse3.se_descripcion gerencia, useu.se_id, useu.se_interno, useu.se_nombre, cse.se_descripcion sector
  	 FROM use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
	  WHERE useu.se_idsector = cse.se_id
  	  AND cse.se_idsectorpadre = cse2.se_id
      AND cse2.se_idsectorpadre = cse3.se_id".$where."
 ORDER BY useu.se_nombre";

$stmt = DBExecSql($conn, $sql);

$intCantidadRegistros = 0;
$msg = "";
while ($row = DBGetQuery($stmt)) {
	$intCantidadRegistros++;

	if ($intCantidadRegistros == 50) {
		$msg =  "Primeros 50 registros (orden alfabético):";
		break;
	}
}
	
if ($intCantidadRegistros != 0) {
?>
	<p style="margin-top: 0; margin-bottom: 0"></p>
	<div align="center"><?= $msg?>
	<table border="0" height="39" cellspacing="1" cellpadding="0" width="770">
	<tr>
	<th style="padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" height="12" width="14">
	<p style="margin-top: 0; margin-bottom: 0">
	</th>
	<th style="background-color: #999999; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" height="12">
	<p style="margin-top: 0; margin-bottom: 0">
	<font color="#FFFFFF" size="2" face="Verdana">Nombre</font></th>
	<th style="background-color: #999999; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" height="12">
	<p style="margin-top: 0; margin-bottom: 0">
	<font face="Verdana" size="2" color="#FFFFFF">Sector</font></th>
	<th style="background-color: #999999; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" height="12">
	<p style="margin-top: 0; margin-bottom: 0">
	<font face="Verdana" size="2" color="#FFFFFF">Gerencia</font></th>
	<th style="background-color: #999999; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px" height="12">
	<p style="margin-top: 0; margin-bottom: 0">
	<font color="#FFFFFF" size="2">Interno</font></th>
	</tr>
<?
	$stmt = DBExecSql($conn, $sql);
	$intCantidadRegistros = 0;
	while ($row = DBGetQuery($stmt)) {
?>
		<tr bgcolor="cccccc" onmouseout="mOut(this,'CCCCCC')" onmouseover="mOvr(this,'#6CADE5');">
		<td height="18" width="22" bgcolor="#FFFFFF">
    <form method="POST" action="informacion.php" target="_parent">
	<b>
	<p style="margin-top: 0; margin-bottom: 0">
	<input type="submit" name="id" value="<?= $row["SE_ID"] ?>" style="border:1px solid #FFFFFF; padding:0; background-color: #FFFFFF; width:22; height:23; color:#ABA4C4; cursor:hand; font-family:Verdana; font-size:1; background-image:url('/Images/Buscar_Usuarios/lupa.jpg')">
	</p>
	</td>
	</form>
		<td id="kun" height="18">
		<p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" size="1" color="#FFFFFF"><b>&nbsp;<?= $row["SE_NOMBRE"] ?>&nbsp;&nbsp;&nbsp;</b></font></td>
		<td height="18">
		<p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" size="1" color="#FFFFFF"><b>&nbsp;<?= $row["SECTOR"] ?>&nbsp;&nbsp;&nbsp;</b></font></td>
		<td height="18">
		<p style="margin-top: 0; margin-bottom: 0"><font face="Verdana" size="1" color="#FFFFFF"><b>&nbsp;<?= $row["GERENCIA"] ?>&nbsp;&nbsp;&nbsp;</b></font></td>
		<td height="18" align="center">
		<p style="margin-top: 0; margin-bottom: 0" align="center">
		<font face="Verdana" size="2" color="#FFFFFF"><b>&nbsp;<?= $row["SE_INTERNO"] ?>&nbsp;</b></font></td>
		<td height="18">
		</form>
		</td>
		</tr>
<?
		$intCantidadRegistros++;
		if ($intCantidadRegistros > 50)
			break;
	}
}
?>
	</table>
	</div>
	<font face="Verdana">
</font>
<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
</body>
</html>