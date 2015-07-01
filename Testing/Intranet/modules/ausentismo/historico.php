<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


SetDateFormatOracle("DD/MM/YYYY HH24:MI");

$showProcessMsg = true;

// Por defecto muestro los resultados..
$_REQUEST["buscar"] = "yes";

$empleado = -1;
if (isset($_REQUEST["Empleado"]))
	$empleado = $_REQUEST["Empleado"];

$fechaAvisoDesde = ValorSql("SELECT TO_CHAR(actualdate - 30, 'dd/mm/yyyy') FROM DUAL");
if (isset($_REQUEST["FechaAvisoDesde"]))
	$fechaAvisoDesde = $_REQUEST["FechaAvisoDesde"];

$fechaAvisoHasta = "";
if (isset($_REQUEST["FechaAvisoHasta"]))
	$fechaAvisoHasta = $_REQUEST["FechaAvisoHasta"];

$motivo = -1;
if (isset($_REQUEST["Motivo"]))
	$motivo = $_REQUEST["Motivo"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "3_D_";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];
?>
<script languaje="javascript">
	showTitle(true, 'PARTE DIARIO');
</script>
<link href="/modules/ausentismo/styles/ausentismo_historico.css" rel="stylesheet" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/index.php?pageid=8" id="formHistorico" method="post" name="formHistorico" target="iframeProcesando" onSubmit="return ValidarForm(formHistorico)">
	<input id="buscar" name="buscar" type="hidden" value="yes">
	<p style="margin-bottom:4px;">
		<label class="FormLabelGrisChico" style="margin-right:4px;">Fecha Aviso Desde</label>
		<input class="FormInputTextDate" id="FechaAvisoDesde" maxlength="10" name="FechaAvisoDesde" title="Fecha Aviso Desde" type="text" validarFecha="true" value="<?= $fechaAvisoDesde?>"><input class="BotonFecha" id="btnFechaAvisoDesde" name="btnFechaAvisoDesde" style="vertical-align:-5px;" type="button" value="" />
		<label class="FormLabelGrisChico" style="margin-left:144px; margin-right:4px;">Fecha Aviso Hasta</label>
		<input class="FormInputTextDate" id="FechaAvisoHasta" maxlength="10" name="FechaAvisoHasta" title="Fecha Aviso Hasta" type="text" validarFecha="true" value="<?= $fechaAvisoHasta?>"><input class="BotonFecha" id="btnFechaAvisoHasta" name="btnFechaAvisoHasta" style="vertical-align:-5px;" type="button" value="" />
	</p>
	<p style="margin-bottom:4px;">
		<label class="FormLabelGrisChico" style="margin-left:1px; margin-right:4px;">Empleado Ausente</label>
		<select id="Empleado" name="Empleado" size="1" style="color: #808080; font-family: Neo Sans; font-size: 9pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"></select>
		<label class="FormLabelGrisChico" style="margin-left:76px; margin-right:4px;">Motivo</label>
		<select id="Motivo" name="Motivo" size="1" style="color: #808080; font-family: Neo Sans; font-size: 9pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF"></select>
	</p>
	<p>
		<input id="btnBuscar" name="btnBuscar" style="background-color:#ccc; border:1px solid #808080; color:#111; font-family:Neo Sans; font-size:8pt; margin-bottom:8px; margin-left:109px; margin-top:8px; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" type="submit" value="BUSCAR">
	</p>
</form>
<div align="center" id="divContent" name="divContent" width="100%">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = "";

	if ($empleado != -1) {
		$params[":empleado"] = $empleado;
		$where.= " AND UPPER(TRIM(ha_empleado)) = UPPER(TRIM(:empleado))";
	}
	if ($fechaAvisoDesde != "") {
		$params[":fechaaltadesde"] = $fechaAvisoDesde;
		$where.= " AND ha_fechaalta >= TO_DATE(:fechaaltadesde, 'dd/mm/yyyy')";
	}
	if ($fechaAvisoHasta != "") {
		$params[":fechaaltahasta"] = $fechaAvisoHasta;
		$where.= " AND ha_fechaalta <= TO_DATE(:fechaaltahasta, 'dd/mm/yyyy')";
	}
	if ($motivo != -1) {
		$params[":idmotivoausencia"] = $motivo;
		$where.= " AND ha_idmotivoausencia = :idmotivoausencia";
	}

	$sql =
		"SELECT NULL ¿ha_id?,
						ha_fechaalta  ¿fechaalta?,
						ha_fechaavisojefe ¿fechaavisojefe?,
						UPPER(SUBSTR(ha_usualta, 1, 2)) || LOWER(SUBSTR(ha_usualta, 3, 1000)) ¿usualta?,
						¿ha_empleado?,
						¿ma_detalle?,
						DECODE(NVL(aa_observacionrespuesta, ''), '', ha_observaciones, ha_observaciones || '<br /><span style=\"color:#f00\">' || aa_usuariorespuesta || ' agrega el ' || aa_fecharespuesta ||': ' || aa_observacionrespuesta || '</span>') ¿observaciones?,
						DECODE(NVL(aa_enviomedico, ''), '', CASE WHEN ha_enviarmedico = 'T' THEN 'Sí' WHEN ha_enviarmedico = 'F' THEN 'No' ELSE '' END, '<span style=\"color:#f00\" title=\"Respuesta cargada por el jefe\">' || DECODE(aa_enviomedico, 'S', 'Sí', 'No') || ' - Rta.: ' || aa_fecharespuesta || '</span>') ¿enviarmedico?,
						ha_motivonoenviomedico ¿motivonoenviomedico?,
						UPPER(SUBSTR(se_usuario, 1, 2)) || LOWER(SUBSTR(se_usuario, 3, 1000)) ¿jefe?,
						DECODE(ha_informado, 'T', 'Informado', 'No Informado') ¿informado?
			 FROM rrhh.rha_ausencias, rrhh.rma_motivosausencia, use_usuarios, web.waa_avisosausentismo
			WHERE ha_idmotivoausencia = ma_id
				AND ha_idjefe = se_id(+)
				AND ha_id = aa_idausencia(+)
				AND ha_fechaavisojefe IS NOT NULL _EXC1_";
	$grilla = new Grid(15, 200);
	$grilla->addColumn(new Column("", 0, false, false, -1, "", "", "GridFirstColumn"));
	$grilla->addColumn(new Column("Fecha de Aviso"));
	$grilla->addColumn(new Column("Fecha Acción"));
	$grilla->addColumn(new Column("Reportado por"));
	$grilla->addColumn(new Column("Emp. Ausente"));
	$grilla->addColumn(new Column("Motivo"));
	$grilla->addColumn(new Column("Observación"));
	$grilla->addColumn(new Column("Médico"));
	$grilla->addColumn(new Column("Justificación"));
	$grilla->addColumn(new Column("Usuario"));
	$grilla->addColumn(new Column("Acciones"));
	$grilla->setColsSeparator(true);
	$grilla->setDecodeSpecialChars(true);
	$grilla->setEmptyStyle("AusentismoGridEmpty");
	$grilla->setExtraConditions(array($where));
	$grilla->setFooterStyle("AusentismoGridFooter");
	$grilla->setHeaderStyle("AusentismoGridHeader");
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRow1Style("AusentismoGridRow1");
	$grilla->setRow2Style("AusentismoGridRow2");
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->setTableStyle("AusentismoGridTable");
	$grilla->setTextStyle("AusentismoGridText");
	$grilla->setTitleStyle("AusentismoGridTitle");
	$grilla->Draw();
}
?>
</div>
<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img alt="Espere por favor..." border="0" src="/images/cargando.gif"></div>
<script>
function CopyContent() {
	try {
		window.parent.document.getElementById('divContent').innerHTML = document.getElementById('divContent').innerHTML;
		window.parent.document.getElementById('divProcesando').style.display = 'none';
	}
	catch(err) {
		//alert(err.description);
	}
}

<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "Empleado";
$RCparams = array();
$RCquery =
	"SELECT DISTINCT INITCAP(TRIM(ha_empleado)) id, INITCAP(TRIM(ha_empleado)) detalle
							FROM rrhh.rha_ausencias
					ORDER BY 2";
$RCselectedItem = $empleado;
FillCombo();

$RCfield = "Motivo";
$RCparams = array();
$RCquery =
	"SELECT ma_id id, ma_detalle detalle
		 FROM rrhh.rma_motivosausencia
		WHERE ma_fechabaja IS NULL
 ORDER BY 2";
$RCselectedItem = $motivo;
FillCombo();
?>

Calendar.setup (
	{
		inputField: "FechaAvisoDesde",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaAvisoDesde"
	}
);
Calendar.setup (
	{
		inputField: "FechaAvisoHasta",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaAvisoHasta"
	}
);

CopyContent();

try {
	document.getElementById('FechaAvisoDesde').focus();
}
catch (e) {
	//
}
</script>