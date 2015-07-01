<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");


function showInformesDirectores() {
	$params = array(":idusuario" => getUserId());
	$sql =
		"SELECT 1
			 FROM web.wpe_permisosintranet
			WHERE pe_idusuario = :idusuario
				AND pe_idpagina = 78";
	return existeSql($sql, $params);
}

if (!showInformesDirectores()) {
	echo "Usted no tiene permiso para entrar a este módulo";
	exit;
}

?>
<div align="center">
	<table border="0" width="770" cellspacing="0" cellpadding="0" id="table1">
		<tr>
			<td width="45" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right" style="margin-left: 7px"><b><font face="Neo Sans" size="2"><img border="0" src="/modules/control_gestion/informes_de_gestion/images/usuario.jpg" width="26" height="28"></td>
			<td width="101" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font color="#808080" style="font-size: 10pt" face="Neo Sans">Usuario Actual:</font></td>
			<td align="left" width="529" style="border-bottom-style: solid; border-bottom-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font style="font-size: 8pt; " face="Neo Sans" color="#000000"><?= GetUserName()?></font></td>
		</tr>
	</table>
</div>
<br />
<br />
<?
$list = new ListOfItems(DATA_INFORMES_DIRECTORES);
$list->addItem(new ItemList("Presentacion_Directorio_Intranet_Septiembre.pdf", "Septiembre 2014", "_blank", true));
$list->addItem(new ItemList("Presentacion_Directorio_Intranet_Agosto.pdf", "Agosto 2014", "_blank", true));
$list->addItem(new ItemList("Presentacion_Directorio_Intranet_Julio.pdf", "Julio 2014", "_blank", true));
$list->addItem(new ItemList("Presentacion_Directorio_Intranet_Junio.pdf", "Junio 2014", "_blank", true));
$list->setCols(1);
$list->setColsWidth(320);
$list->setShowTitle(false);
$list->setImagePath("/modules/normativa_interna/images/item.bmp");
$list->draw();
?>