<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


SetDateFormatOracle("DD/MM/YYYY");

if ((isset($_REQUEST["buscar"])) and (!isset($_REQUEST["firstcall"])))
	if ($showProcessMsg)
		FirstCallPageCode();

$tipoNovedad = "-1";
if (isset($_REQUEST["TipoNovedad"]))
	$tipoNovedad = $_REQUEST["TipoNovedad"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];


$sb = false;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;
?>
<form action="index.php?pageid=20" id="formNovedades" method="post" name="formNovedades" target="iframeProcesando">
<input id="buscar" name="buscar" type="hidden" value="yes">
<table border="0" width="770" id="table1" cellspacing="0" cellpadding="0">
	<tr bgcolor="#C0C0C0" height="25">
		<td class="FormLabelBlanco" width="144">&nbsp;Tipo de Novedad:</td>
		<td width="144"><select class="Combo" id="TipoNovedad" name="TipoNovedad"></select></td>
		<td align="left"><input class="BotonBlanco" id="btnBuscar" name="btnBuscar" type="submit" value="BUSCAR"></td>
		<td align="center" width="196"><input class="BotonBlanco" name="btnAgregarNovedad" type="button" value="AGREGAR NOVEDAD" onClick="window.location.href='/index.php?pageid=20&page=novedad.php'"></td>
	</tr>
	<tr>
		<td width="143">&nbsp;</td>
		<td colspan="2">&nbsp;</td>
	</tr>
</table>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = " AND 1 = 1";

	if ($tipoNovedad != "-1") {
		$params[":tiponovedad"] = $tipoNovedad;
		$where = " AND np_tiponovedad = :tiponovedad";
	}

	$sql =
		"SELECT ¿np_id?, NVL(np_fechamodif, np_fechaalta) ¿fechamodificacion?,
						DECODE(np_tiponovedad, 'C', 'Casamiento', DECODE(np_tiponovedad, 'G', 'Graduación', 'Nacimiento')) ¿tiponovedad?,
						np_texto ¿texto?, ¿np_fechabaja?
			 FROM rrhh.rnp_novedadespersonales
		  WHERE 1 = 1 _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/index.php?pageid=20&page=novedad.php", "GridFirstColumn"));
	$grilla->addColumn(new Column("Fecha Modificación"));
	$grilla->addColumn(new Column("Tipo Novedad"));
	$grilla->addColumn(new Column("Texto", 0, true, false, -1, "", "", "", 50));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja(5, $sb, false);
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("np_fechabaja");
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw();
}
?>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "TipoNovedad";
$RCparams = array();
$RCquery =
	"SELECT 'C' id, 'Casamiento' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'G' id, 'Graduación' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'N' id, 'Nacimiento' detalle
		 FROM DUAL
 ORDER BY 2";
$RCselectedItem = (isset($_REQUEST["TipoNovedad"]))?$_REQUEST["TipoNovedad"]:"-1";
FillCombo();
?>
	with (document)
		if (getElementById('TipoNovedad') != null)
			getElementById('TipoNovedad').focus();
</script>