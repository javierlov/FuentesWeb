<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");


$showProcessMsg = false;

$activa = "";
if (isset($_REQUEST["activa"]))
	$activa = "T";

$detalle = "";
if (isset($_REQUEST["detalle"]))
	$detalle = $_REQUEST["detalle"];

$titulo = "";
if (isset($_REQUEST["titulo"]))
	$titulo = $_REQUEST["titulo"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = "2";
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = false;
if (isset($_REQUEST["sb"]))
	if ($_REQUEST["sb"] == "T")
		$sb = true;
?>
<div>
	<form action="/index.php?pageid=48" id="formEncuestas" method="post" name="formEncuestas" target="iframeProcesando" onSubmit="ValidarForm(formEncuestas)">
		<input id="buscar" name="buscar" type="hidden" value="yes">
		<p style="margin-left:184px; margin-top:4px; position: relative;">
			<label for="titulo">Título</label>
			<input class="FormInputText" id="titulo" name="titulo" type="text" value="<?= $titulo?>" />
			<label for="detalle" style="margin-left:16px;">Detalle</label>
			<input class="FormInputText" id="detalle" name="detalle" type="text" value="<?= $detalle?>" />
			<label for="activa" style="margin-left:22px;">Activa</label>
			<input id="activa" name="activa" type="checkbox" <?= ($activa == "T"?"CHECKED":"")?> />
		</p>
		<p style="float:right; margin-bottom:16px; margin-top:16px;">
			<input class="BotonBlanco" id="btnBuscar" name="btnBuscar" style="margin-right:16px;" type="submit" value="Buscar">
			<input class="BotonBlanco" id="btnNuevo" name="btnNuevo" type="button" value="Nuevo" onClick="window.location.href='index.php?pageid=48&page=abm_encuesta.php'">
		</p>
	</form>
</div>
<div align="center" id="divContent" name="divContent">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array();
	$where = "";

	if ($activa != "")
		$where.= " AND en_activa = 'T'";
	if ($titulo != "") {
		$params[":titulo"] = "%".$titulo."%";
		$where.= " AND UPPER(en_titulo) LIKE UPPER(:titulo)";
	}
	if ($detalle != "") {
		$params[":detalle"] = "%".$detalle."%";
		$where.= " AND UPPER(en_detalle) LIKE UPPER(:detalle)";
	}
	$sql =
		"SELECT en_id ¿id?, en_titulo ¿titulo?, en_detalle ¿detalle?, DECODE(en_activa, 'T', 'SI', 'NO') ¿activa?, en_fechabaja ¿baja?
			FROM rrhh.ren_encuestas
		 WHERE 1 = 1 _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 0, true, false, -1, "BotonInformacion", "index.php?pageid=48&page=abm_encuesta.php", "GridFirstColumn"));
	$grilla->addColumn(new Column("Título"));
	$grilla->addColumn(new Column("Detalle"));
	$grilla->addColumn(new Column("Activa"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja(5, $sb, true);
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("en_fechabaja");
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw();
}
?>
</div>
<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img alt="Espere por favor..." border="0" src="/images/waiting.gif"></div>
<script>
	document.getElementById('titulo').focus();
</script>