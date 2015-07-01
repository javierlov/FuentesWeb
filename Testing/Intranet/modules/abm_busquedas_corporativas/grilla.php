<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


SetDateFormatOracle("DD/MM/YYYY");

if ((isset($_REQUEST["buscar"])) and (!isset($_REQUEST["firstcall"])))
	if ($showProcessMsg)
		FirstCallPageCode();

$empresa = -1;
if (isset($_REQUEST["empresa"]))
	$empresa = $_REQUEST["empresa"];

$estado = -1;
if (isset($_REQUEST["estado"]))
	$estado = $_REQUEST["estado"];

$fechaAltaDesde = "";
if (isset($_REQUEST["fechaAltaDesde"]))
	$fechaAltaDesde = $_REQUEST["fechaAltaDesde"];

$fechaAltaHasta = "";
if (isset($_REQUEST["fechaAltaHasta"]))
	$fechaAltaHasta = $_REQUEST["fechaAltaHasta"];

$puesto = "";
if (isset($_REQUEST["puesto"]))
	$puesto = $_REQUEST["puesto"];

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
	if (document.getElementById('fechaAltaDesde') != null)
		Calendar.setup (
			{
				inputField: "fechaAltaDesde",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaAltaDesde"
			}
		);

	if (document.getElementById('fechaAltaHasta') != null)
		Calendar.setup (
			{
				inputField: "fechaAltaHasta",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaAltaHasta"
			}
  	);
}
</script>
<form action="index.php?pageid=63" id="formBusquedasCorporativas" method="post" name="formBusquedasCorporativas" target="iframeProcesando" onSubmit="return ValidarForm(formBusquedasCorporativas)">
	<input id="buscar" name="buscar" type="hidden" value="yes">
	<div align="left">
		<div style="margin-bottom:8px;">
			<label class="FormLabelAzul" for="puesto" style="margin-left:60px;">Puesto</label>
			<input class="FormInputText" id="puesto" name="puesto" size="40" type="text" value="<?= $puesto?>">
			<label class="FormLabelAzul" for="estado" style="margin-left:8px;">Estado</label>
			<select class="Combo" id="estado" name="estado"></select>
		</div>
		<div style="margin-bottom:8px;">
			<label class="FormLabelAzul" for="empresa" style="margin-left:51px;">Empresa</label>
			<select class="Combo" id="empresa" name="empresa"></select>
		</div>
		<div style="margin-bottom:8px;">
			<label class="FormLabelAzul" for="fechaAltaDesde" style="margin-left:2px;">Fecha Alta Desde</label>
			<input class="FormInputText" id="fechaAltaDesde" maxlength="10" name="fechaAltaDesde" size="12" type="text" title="Fecha Alta Desde" validarFecha="true" value="<?= $fechaAltaDesde?>"><input class="BotonFecha" id="btnFechaAltaDesde" name="btnFechaAltaDesde" type="button" value="">
			<label class="FormLabelAzul" for="fechaAltaHasta" style="margin-left:86px;">Fecha Alta Hasta</label>
			<input class="FormInputText" id="fechaAltaHasta" maxlength="10" name="fechaAltaHasta" size="12" type="text" title="Fecha Alta Hasta" validarFecha="true" value="<?= $fechaAltaHasta?>"><input class="BotonFecha" id="btnFechaAltaHasta" name="btnFechaAltaHasta" type="button" value="">
		</div>
		<div>
			<hr color="#c0c0c0" size="1" style="border-bottom-style:dotted; border-bottom-width: 1px; border-left-width:1px; border-right-width:1px; border-top-width:1px;">
		</div>
		<div align="right" style="margin-bottom:16px; margin-right:8px;">
			<input class="BotonBlanco" name="btnBuscar" type="submit" value="Buscar">
			<input class="BotonBlanco" name="btnNuevo" style="margin-left:8px;" type="button" value="Nuevo" onClick="window.location.href='/index.php?pageid=63&page=busqueda_corporativa.php'">
		</div>
	</div>
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = "";

	if ($empresa != -1) {
		$params[":idempresa"] = $empresa;
		$where.= " AND bc_idempresa = :idempresa";
	}
	if ($estado != -1) {
		$params[":idestado"] = $estado;
		$where.= " AND bc_idestado = :idestado";
	}
	if ($fechaAltaDesde != "") {
		$params[":fechaaltadesde"] = $fechaAltaDesde;
		$where.= " AND bc_fechaalta >= TO_DATE(:fechaaltadesde, 'dd/mm/yyyy')";
	}
	if ($fechaAltaHasta != "") {
		$params[":fechaaltahasta"] = $fechaAltaHasta;
		$where.= " AND TRUNC(bc_fechaalta) <= TO_DATE(:fechaaltahasta, 'dd/mm/yyyy')";
	}
	if ($puesto != "") {
		$params[":puesto"] = "%".RemoveAccents($puesto)."%";
		$where.= " AND UPPER(ART.UTILES.reemplazar_acentos(bc_puesto)) LIKE UPPER(:puesto)";
	}

	$sql =
		"SELECT ¿bc_id?, TO_NUMBER(bc_id) ¿id2?, ¿bc_puesto?, ¿em_nombre?, ¿ec_detalle?, TRUNC(bc_fechaalta) ¿fechaalta?, bc_fechabaja ¿baja?
			FROM rrhh.rbc_busquedascorporativas, rrhh.rec_estadosbusquedacorporativa, aem_empresa
		 WHERE bc_idestado = ec_id
		 AND bc_idempresa = em_id(+) _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/index.php?pageid=63&page=busqueda_corporativa.php", "GridFirstColumn"));
	$grilla->addColumn(new Column("Nº"));
	$grilla->addColumn(new Column("Puesto"));
	$grilla->addColumn(new Column("Empresa"));
	$grilla->addColumn(new Column("Estado"));
	$grilla->addColumn(new Column("Fecha Alta"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja(5, $sb, false);
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("bc_fechabaja");
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

$RCfield = "empresa";
$RCparams = array();
$RCquery =
	"SELECT em_id ID, em_nombre detalle
		FROM aem_empresa
	 WHERE em_idgrupoeconomico = 88
UNION ALL
	 SELECT -2, 'PROVINCIA A.R.T.'
		FROM DUAL
UNION ALL
	 SELECT -3, 'INVIERTA BUENOS AIRES'
		FROM DUAL
ORDER BY 2";
$RCselectedItem = $empresa;
FillCombo();

$RCfield = "estado";
$RCparams = array();
$RCquery =
	"SELECT ec_id ID, ec_detalle detalle
		FROM rrhh.rec_estadosbusquedacorporativa
	 WHERE ec_fechabaja IS NULL
ORDER BY 2";
$RCselectedItem = $estado;
FillCombo();
?>
	setCalendar();
	document.getElementById('puesto').focus();
</script>