<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");

if (isset($_REQUEST["mdl"])) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/control_gestion/informes_de_gestion/".$_REQUEST["mdl"]);
	return false;
}
?>
<div align="center">
	<table width="770" cellspacing="0" cellpadding="0" id="table1">
		<tr>
			<td width="45" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right" style="margin-left: 7px"><b><font size="2"><img src="/modules/control_gestion/informes_de_gestion/images/usuario.jpg" width="26" height="28"></td>
			<td width="101" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font color="#808080" style="font-size: 10pt">Usuario Actual:</font></td>
			<td align="left" width="529" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font style="font-size: 8pt; " color="#000000"><?= GetUserName()?></font></td>
			<td width="54" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right">&nbsp;</td>
		</tr>
	</table>
</div>

<br />

<div align="center">
	<table width="652" cellspacing="0" cellpadding="0" id="table4">
		<tr>
			<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="21"><b><font size="2"><a href="/index.php?pageid=34"><img height="27" src="/modules/control_gestion/informes_de_gestion/images/administracion.jpg" title="Administración" width="30"></a></td>
			<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="72"><span style="font-weight: 700"><font size="3" color="#00A4E4">Administración</font></span></td>
			<td style="border-bottom-style: dotted; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font size="2"><p style="margin-top: 0; margin-bottom: 0">&nbsp;</td>
		</tr>
	</table>
</div>

<br />

<div align="center">
<?
$list = new ListOfItems("", "");
$list->addItem(new ItemList("/index.php?pageid=34&mdl=administracion_de_temas.php", "Administración de Temas", "_self"));
$list->addItem(new ItemList("/index.php?pageid=34&mdl=administracion_de_publicaciones.php", "Administración de Publicaciones", "_self"));
$list->addItem(new ItemList("/modules/control_gestion/permisos/permisos.php?o=i", "Administración de Accesos", "_self"));
$list->addItem(new ItemList("/index.php?pageid=34&mdl=administracion_de_estadisticas.php", "Administración de Estadísticas", "_self"));
$list->setCols(1);
$list->setColsWidth(600);
$list->setImagePath("/modules/control_gestion/informes_de_gestion/images/flecha.gif");
$list->setShowTitle(false);
$list->draw();
?>
</div>