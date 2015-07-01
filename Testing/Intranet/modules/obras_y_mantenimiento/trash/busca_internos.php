<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/DataBase/DB.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/Provart/Grid.php");


$nombre = "";
if (isset($_REQUEST["Nombre"]))
	$nombre = $_REQUEST["Nombre"];

$sector = "";
if (isset($_REQUEST["Sector"]))
	$sector = $_REQUEST["Sector"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<title>Agenda Telefónica</title>
<base target="_self">
<link href="/Styles/style.css" rel="stylesheet" type="text/css">
</head>
<body alink="#000080" link="#000080" vlink="#000080">
<form action="busca_internos.php?buscar=yes" method="get">
<input id="buscar" name="buscar" type="hidden" value="yes">
<div align="center">
	<table border="0" width="770" cellspacing="1">
		<tr>
			<td width="100"></td>
			<td align="right"><a href="http://www.paginasdoradas.com.ar/Telefonos.action" target="_blank"><img border="0" height="22" src="/Images/Buscar_Usuarios/pag_amarillas.gif" width="21"></a></td>
			<td style="border-left-style: solid; border-left-width: 1px;"><b><font color="#808080" face="Verdana" size="1">&nbsp;Guía Telefónica: Páginas Doradas</font></b></td>
			<td width="88"></td>
			<td align="right"><font face="Verdana"><a href="http://ntdata/gps/Instructivo%20telefonico/Instructivo%20telefonico.htm" target="_blank"><img border="0" height="21" src="/Images/Buscar_Usuarios/tel.gif" width="25"></a></font></td>
			<td style="border-left-style: solid; border-left-width: 1px;"><b><font color="#808080" face="Verdana" size="1">&nbsp;Instructivo Telefónico</font></b></td>
			<td width="100"></td>
		</tr>
	</table>
	<table bgcolor="#C0C0C0" border="0" cellpadding="1" cellspacing="1" width="770">
		<tr>
			<td width="24"></td>
			<td align="right" class="FormLabelBlanco">Nombre o Apellido:</td>
			<td><input class="FormInputText" id="Nombre" name="Nombre" type="text" value="<?= $nombre ?>" /></td>
			<td width="24"></td>
			<td align="right" class="FormLabelBlanco">Sector o Gerencia:</td>
			<td><input class="FormInputText" id="Sector" name="Sector" type="text" value="<?= $sector ?>"></font></td>
			<td width="24"></td>
			<td><input type="submit" value="Buscar" style="border:1px solid #999999; color: #808080; font-size: 8pt; font-family: Verdana; font-weight: bold; padding-left:4px; padding-right:4px; padding-top:1px; padding-bottom:1px; background-color:#FFFFFF" /></td>
			<td width="24"></td>
		</tr>
	</table>
</div>
<div align="center">
	<table border="0" width="770" cellspacing="0">
		<tr>
			<td width="24"></td>
			<td>
				<map name="FPMap0">
					<area coords="66, 28, 220, 40" href="mailto:mesadeayuda@provart.com.ar" shape="rect">
				</map>
				<img border="0" height="42" src="/Images/Buscar_Usuarios/banner_mesa_de_ayuda.gif" usemap="#FPMap0" width="223">
			</td>
			<td width="24"></td>
			<td valign="top"><img border="0" height="29" src="/Images/Buscar_Usuarios/banner_vigilancia.gif" width="191"></td>
			<td width="24"></td>
			<td valign="top">
				<map name="FPMap1">
					<area coords="52, 29, 214, 41" href="mailto:mantenimiento@provart.com.ar" shape="rect">
				</map>
				<img border="0" height="42" src="/Images/Buscar_Usuarios/banner_mantenimiento.gif" usemap="#FPMap1" width="215">
			</td>
			<td width="24"></td>
		</tr>
	</table>
</div>
</form>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$where = "";
	if ($nombre != "")
		$where.= " AND UPPER(useu.se_nombre) LIKE UPPER('%".$nombre."%')";
	if ($sector != "")
		$where.= " AND (UPPER(cse3.se_descripcion) LIKE UPPER('%".$sector."%') OR UPPER(cse.se_descripcion) LIKE UPPER('%".$sector."%'))";
	if ($where == "")
		$where = " AND 1 = 2";

	$sql =
		"SELECT useu.se_id, useu.se_nombre, cse.se_descripcion sector, cse3.se_descripcion gerencia, useu.se_interno
	  	 FROM use_usuarios useu, computos.cse_sector cse, computos.cse_sector cse2, computos.cse_sector cse3
		  WHERE useu.se_idsector = cse.se_id
  		  AND cse.se_idsectorpadre = cse2.se_id
      	AND cse2.se_idsectorpadre = cse3.se_id".$where."
 	 ORDER BY useu.se_nombre";

	$grilla = new Grid(array("", "Nombre", "Sector", "Gerencia", "Interno"), array(8, 0, 0, 0, 0),
										 array("BotonInformacion", "", "", "", ""), array("informacion.php", "", "", "", ""));
	$grilla->setColsSeparator(true);
	$grilla->setExtraFields("&Nombre=".$nombre."&Sector=".$sector);
	$grilla->setPageNumber($pagina);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw();
}
?>
</body>
</html>