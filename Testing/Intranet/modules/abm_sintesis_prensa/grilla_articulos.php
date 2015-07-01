<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


SetDateFormatOracle("DD/MM/YYYY");

if ((isset($_REQUEST["buscar"])) and (!isset($_REQUEST["firstcall"])))
	if ($showProcessMsg)
		FirstCallPageCode();

$fechaDesde = "";
if (isset($_REQUEST["FechaDesde"]))
	$fechaDesde = $_REQUEST["FechaDesde"];

$fechaHasta = "";
if (isset($_REQUEST["FechaHasta"]))
	$fechaHasta = $_REQUEST["FechaHasta"];

$fuente = "";
if (isset($_REQUEST["Fuente"]))
	$fuente = $_REQUEST["Fuente"];

$titulo = "";
if (isset($_REQUEST["Titulo"]))
	$titulo = $_REQUEST["Titulo"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = true;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "F")
		$sb = false;
?>
<script>
function setCalendar() {
	if (document.getElementById('Fecha') != null)
		Calendar.setup (
			{
				inputField: "Fecha",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFecha"
			}
  	);

	if (document.getElementById('FechaDesde') != null)
		Calendar.setup (
			{
				inputField: "FechaDesde",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaDesde"
			}
		);

	if (document.getElementById('FechaHasta') != null)
		Calendar.setup (
			{
				inputField: "FechaHasta",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaHasta"
			}
  	);
}
</script>
<form action="index.php?pageid=13" id="formArticulos" method="post" name="formArticulos" target="iframeProcesando" onSubmit="return ValidarForm(formArticulos)">
<input id="buscar" name="buscar" type="hidden" value="yes">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
	<tr>
		<td align="right" class="FormLabelAzul" width="144">Fecha Desde:&nbsp;</td>
		<td align="left" colspan="2" width="168"><input class="FormInputText" id="FechaDesde" maxlength="10" name="FechaDesde" size="12" type="text" title="Fecha Desde" validarFecha="true" value="<?= $fechaDesde?>"><input class="BotonFecha" id="btnFechaDesde" name="btnFechaDesde" type="button" value=""></td>
		<td align="right" class="FormLabelAzul" width="88">Fecha Hasta:&nbsp;</td>
		<td align="left" width="376"><input class="FormInputText" id="FechaHasta" maxlength="10" name="FechaHasta" size="12" type="text" title="Fecha Hasta" validarFecha="true" value="<?= $fechaHasta?>"><input class="BotonFecha" id="btnFechaHasta" name="btnFechaHasta" type="button" value=""></td>
	</tr>
	<tr>
		<td colspan="5" height="8"></td>
	</tr>
	<tr>
		<td align="right" class="FormLabelAzul">Fuente:&nbsp;</td>
		<td align="left" colspan="2"><input class="FormInputText" id="Fuente" name="Fuente" size="20" type="text" value="<?= $fuente?>"></td>
		<td align="right" class="FormLabelAzul">Título:&nbsp;</td>
		<td align="left"><input class="FormInputText" id="Titulo" name="Titulo" size="20" type="text" value="<?= $titulo?>"></td>
	</tr>
	<tr>
		<td colspan="5" height="16"></td>
	</tr>
	<tr>
		<td></td>
		<td><input class="BotonBlanco" name="btnBuscar" type="submit" value="Buscar"></td>
		<td><input class="BotonBlanco" name="btnNuevo" type="button" value="Nuevo" onClick="window.location.href='/index.php?pageid=13&page=articulo.php'"></td>
		<td colspan="2"></td>
	</tr>
	<tr>
		<td colspan="5" height="8"></td>
	</tr>
</table>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = "";

	if ($fechaDesde != "") {
		$params[":fechadesde"] = $_REQUEST["FechaDesde"];
		$where.= " AND ap_fecha >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
	}
	if ($fechaHasta != "") {
		$params[":fechahasta"] = $_REQUEST["FechaHasta"];
		$where.= " AND ap_fecha <= TO_DATE(:fechahasta, 'dd/mm/yyyy')";
	}
	if ($fuente != "") {
		$params[":fuente"] = "%".RemoveAccents($fuente)."%";
		$where.= " AND UPPER(ART.UTILES.reemplazar_acentos(ap_fuente)) LIKE UPPER(:fuente)";
	}
	if ($titulo != "") {
		$params[":titulo"] = "%".RemoveAccents($titulo)."%";
		$where.= " AND UPPER(ART.UTILES.reemplazar_acentos(ap_titulo)) LIKE UPPER(:titulo)";
	}

	$sql =
		"SELECT ¿ap_id?, ¿ap_fecha?, ¿ap_fuente?, ¿ap_titulo?, ap_fechabaja ¿baja?
			FROM rrhh.rap_articulosprensa
		 WHERE 1 = 1 _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/index.php?pageid=13&page=articulo.php", "GridFirstColumn"));
	$grilla->addColumn(new Column("Fecha"));
	$grilla->addColumn(new Column("Fuente"));
	$grilla->addColumn(new Column("Título"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja(5, $sb, false);
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("ap_fechabaja");
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
	setCalendar();
	document.getElementById('FechaDesde').focus();
</script>