<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/list_of_items.php");
?>
<script>
	showTitle(true, 'NORMATIVA INTERNA');
</script>
<?
if (isset($_REQUEST["fldr"]))
	require_once($_REQUEST["fldr"]);
else {
	$list = new ListOfItems("");
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=corporativa/index.php", "Corporativa", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=prest_y_servic/index.php", "Prestaciones y Servicios", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=adm_y_fin/index.php", "Administración y Finanzas", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=tecnica/index.php", "Técnica", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=legales/index.php", "Legales", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=rrhh/index.php", "RR.HH.", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=prevencion/index.php", "Prevención", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=auditoria_interna/index.php", "Auditoría Interna", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=analisis_y_control_de_gestion/index.php", "Análisis y Control de Gestión", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=comercial/index.php", "Comercial", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=memos/index.php", "Memos", "_self", false, true));
	$list->addItem(new ItemList("/index.php?pageid=40&fldr=formularios/index.php", "Formularios", "_self", false, true));
	$list->setCols(1);
	$list->setColsWidth(320);
	$list->setShowTitle(false);
	$list->setImagePath("/modules/normas_y_manuales/item.bmp");
	$list->draw();
}
?>
<table id="tableMensaje" style="background:#807F84; border-color:#c0c0c0; border-style:solid; border-width:1px; color:#fff; font-size:12px; left:130px; margin-top:40px; padding-left:4px; padding-right:4px; position:relative; width:530px; height:50px">
	<tr>
		<td align="center" face="Neo Sans">Las normativas aprobadas y vigentes se encuentran publicadas en la Intranet. Las restantes están siendo revisadas y/o desarrolladas por Gestión de Procesos ( <a href="mailto:cmorgavi@provart.com.ar" style="color:#000000">CLAUDIO MORGAVI</a>&nbsp; int. 4632).</td>
	</tr>
</table>