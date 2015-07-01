<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


SetDateFormatOracle("DD/MM/YYYY");

if ((isset($_REQUEST["buscar"])) and (!isset($_REQUEST["firstcall"])))
	if ($showProcessMsg)
		FirstCallPageCode();

$ano = "";
if (isset($_REQUEST["ano"]))
	$ano = $_REQUEST["ano"];

$fechaPublicacionDesde = "";
if (isset($_REQUEST["fechaPublicacionDesde"]))
	$fechaPublicacionDesde = $_REQUEST["fechaPublicacionDesde"];

$fechaPublicacionHasta = "";
if (isset($_REQUEST["fechaPublicacionHasta"]))
	$fechaPublicacionHasta = $_REQUEST["fechaPublicacionHasta"];

$numero = "";
if (isset($_REQUEST["numero"]))
	$numero = $_REQUEST["numero"];

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
<script>
function setCalendar() {
	if (document.getElementById('fechaPublicacionDesde') != null)
		Calendar.setup (
			{
				inputField: "fechaPublicacionDesde",
				ifFormat  : "%d/%m/%Y",
				button    : "btnfechaPublicacionDesde"
			}
		);

	if (document.getElementById('fechaPublicacionHasta') != null)
		Calendar.setup (
			{
				inputField: "fechaPublicacionHasta",
				ifFormat  : "%d/%m/%Y",
				button    : "btnfechaPublicacionHasta"
			}
  	);
}
</script>
<form action="index.php?pageid=64" id="formBoletin" method="post" name="formBoletin" target="iframeProcesando" onSubmit="return ValidarForm(formBoletin)">
	<input id="buscar" name="buscar" type="hidden" value="yes">
	<div align="left">
		<div style="margin-bottom:8px;">
			<label class="FormLabelAzul" for="ano" style="margin-left:8px;">Año</label>
			<input class="FormInputText" id="ano" name="ano" size="4" type="text" value="<?= $ano?>">
			<label class="FormLabelAzul" for="numero" style="margin-left:8px;">Número</label>
			<input class="FormInputText" id="numero" name="numero" size="4" type="text" value="<?= $numero?>">
			<label class="FormLabelAzul" for="fechaPublicacionDesde" style="margin-left:8px;">Fecha Publicación Desde</label>
			<input class="FormInputText" id="fechaPublicacionDesde" maxlength="10" name="fechaPublicacionDesde" size="12" type="text" title="Fecha Publicación Desde" validarFecha="true" value="<?= $fechaPublicacionDesde?>"><input class="BotonFecha" id="btnfechaPublicacionDesde" name="btnfechaPublicacionDesde" type="button" value="">
			<label class="FormLabelAzul" for="fechaPublicacionHasta" style="margin-left:8px;">Fecha Publicación Hasta</label>
			<input class="FormInputText" id="fechaPublicacionHasta" maxlength="10" name="fechaPublicacionHasta" size="12" type="text" title="Fecha Publicación Hasta" validarFecha="true" value="<?= $fechaPublicacionHasta?>"><input class="BotonFecha" id="btnfechaPublicacionHasta" name="btnfechaPublicacionHasta" type="button" value="">
		</div>
		<div>
			<img src="modules/abm_arteria_noticias/images/linea_horizontal.jpg">
		</div>
		<div align="right" style="margin-bottom:16px; margin-right:8px;">
			<input class="BotonBlanco" name="btnBuscar" type="submit" value="Buscar">
			<input class="BotonBlanco" name="btnNuevo" style="margin-left:8px;" type="button" value="Nuevo" onClick=" if (confirm('Esta acción va a agregar un nuevo registro a la grilla.\n\n¿ Confirma el alta ?')) window.location.href='/index.php?pageid=64&page=boletin.php';">
		</div>
	</div>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = "";

	if ($ano != "") {
		$params[":ano"] = $ano;
		$where.= " AND ba_ano = :ano";
	}
	if ($fechaPublicacionDesde != "") {
		$params[":fechadesde"] = $fechaPublicacionDesde;
		$where.= " AND ba_fecha >= TO_DATE(:fechadesde, 'dd/mm/yyyy')";
	}
	if ($fechaPublicacionHasta != "") {
		$params[":fechahasta"] = $fechaPublicacionHasta;
		$where.= " AND TRUNC(ba_fecha) <= TO_DATE(:fechahasta, 'dd/mm/yyyy')";
	}
	if ($numero != "") {
		$params[":numero"] = $numero;
		$where.= " AND ba_numero = :numero";
	}

	$sql =
		"SELECT ¿ba_id?, ¿ba_ano?, ¿ba_numero?, ¿ba_fecha?,
					  DECODE(ba_estadoenvio, 'P', 'Pendiente', 'Enviado el ' || TO_CHAR(ba_fechaenvio)) ¿estadoenvio?, ba_fechabaja ¿baja?
			FROM rrhh.rba_boletinesarteria
		 WHERE 1 = 1 _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/index.php?pageid=64&page=boletin.php", "GridFirstColumn"));
	$grilla->addColumn(new Column("Año"));
	$grilla->addColumn(new Column("Número"));
	$grilla->addColumn(new Column("Fecha Publicación"));
	$grilla->addColumn(new Column("Estado"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja(6, $sb, false);
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("ba_fechabaja");
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
	document.getElementById('ano').focus();
</script>