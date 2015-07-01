<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


$showProcessMsg = false;

$ano = date("Y");
if (isset($_REQUEST["anoBusqueda"]))
	$ano = $_REQUEST["anoBusqueda"];

$competencias = -1;
if (isset($_REQUEST["competenciasBusqueda"]))
	$competencias = $_REQUEST["competenciasBusqueda"];

$estado = -1;
if (isset($_REQUEST["estadoBusqueda"]))
	$estado = $_REQUEST["estadoBusqueda"];

$evaluado = "";
if (isset($_REQUEST["evaluadoBusqueda"]))
	$evaluado = $_REQUEST["evaluadoBusqueda"];

$evaluador = "";
if (isset($_REQUEST["evaluadorBusqueda"]))
	$evaluador = $_REQUEST["evaluadorBusqueda"];

$notificacion = "";
if (isset($_REQUEST["notificacionBusqueda"]))
	$notificacion = $_REQUEST["notificacionBusqueda"];

$supervisor = "";
if (isset($_REQUEST["supervisorBusqueda"]))
	$supervisor = $_REQUEST["supervisorBusqueda"];

$pagina = 1;
if (isset($_REQUEST["pagina"]))
	$pagina = $_REQUEST["pagina"];

$ob = 2;
if (isset($_REQUEST["ob"]))
	$ob = $_REQUEST["ob"];

$sb = false;
if ((isset($_REQUEST["sb"])) and ($_REQUEST["sb"] == "T"))
	$sb = true;
?>
<link href="/modules/encuestas/evaluacion_desempeno/abm_usuarios/css/style.css" rel="stylesheet" type="text/css" />
<script>
	showTitle(true, 'ABM USUARIOS EVALUACIÓN DESEMPEÑO');
</script>
<form action="/index.php" id="formBuscaUsuarios" method="get" name="formBuscaUsuarios" onSubmit="return ValidarForm(formBuscaUsuarios)">
	<input id="pageid" name="pageid" type="hidden" value="57">
	<input id="rnd" name="rnd" type="hidden" value="<?= rand()?>">
	<input id="buscar" name="buscar" type="hidden" value="yes">
	<div align="center" id="divBuscarUsuarios">
		<label for="anoBusqueda" id="labelAno">Año</label>
		<input class="FormInputText" id="anoBusqueda" maxlength="4" name="anoBusqueda" title="Año" type="text" validar="true" validarEntero="true" value="<?= $ano ?>" />
		<label for="evaluadoBusqueda" id="labelEvaluado">Evaluado</label>
		<input class="FormInputText" id="evaluadoBusqueda" name="evaluadoBusqueda" type="text" value="<?= $evaluado ?>" />
		<label for="evaluadorBusqueda" id="labelEvaluador">Evaluador</label>
		<input class="FormInputText" id="evaluadorBusqueda" name="evaluadorBusqueda" type="text" value="<?= $evaluador ?>" />
		<label for="supervisorBusqueda" id="labelSupervisor">Supervisor</label>
		<input class="FormInputText" id="supervisorBusqueda" name="supervisorBusqueda" type="text" value="<?= $supervisor ?>" />
		<label for="notificacionBusqueda" id="labelNotificacion">Notificación</label>
		<input class="FormInputText" id="notificacionBusqueda" name="notificacionBusqueda" type="text" value="<?= $notificacion ?>" />
		<label for="competenciasBusqueda" id="labelCompetencias">Competencias</label>
		<select class="Combo" id="competenciasBusqueda" name="competenciasBusqueda"></select>
		<label for="estadoBusqueda" id="labelEstado">Estado</label>
		<select class="Combo" id="estadoBusqueda" name="estadoBusqueda"></select>
		<input class="BotonBlanco" id="btnNuevo" type="button" value="NUEVO" onClick="window.location.href='/index.php?pageid=58'" />
		<input class="BotonBlanco" id="btnBuscar" type="submit" value="BUSCAR" />
	</div>
	<div id="divGrilla">
<?
if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "yes")) {
	$params = array(":ano" => $ano);
	$where = "";

	if ($competencias != -1) {
		$params[":categoria"] = $competencias;
		$where.= " AND ue_categoria = :categoria";
	}

	if ($estado != -1) {
		$params[":estado"] = $estado;
		$where.= " AND ue_estado = :estado";
	}

	if ($evaluado != "") {
		$params[":evaluado"] = "%".RemoveAccents(str_replace("ñ", "Ñ", $evaluado))."%";
		$where.= " AND evaluado.se_buscanombre LIKE UPPER(:evaluado)";
	}

	if ($evaluador != "") {
		$params[":evaluador"] = "%".RemoveAccents(str_replace("ñ", "Ñ", $evaluador))."%";
		$where.= " AND evaluador.se_buscanombre LIKE UPPER(:evaluador)";
	}

	if ($notificacion != "") {
		$params[":notificacion"] = "%".RemoveAccents(str_replace("ñ", "Ñ", $notificacion))."%";
		$where.= " AND ue_notificacion LIKE UPPER(:notificacion)";
	}

	if ($supervisor != "") {
		$params[":supervisor"] = "%".RemoveAccents(str_replace("ñ", "Ñ", $supervisor))."%";
		$where.= " AND supervisor.se_buscanombre LIKE UPPER(:supervisor)";
	}

	$sql =
		"SELECT /*+ index(art.use_usuarios ndx_use_parabusqueda)*/
						ue_id ¿ue_id?,
						evaluado.se_nombre ¿evaluado?,
						evaluador.se_nombre ¿evaluador?,
						supervisor.se_nombre ¿supervisor?,
						¿ue_fechabaja?
			 FROM rrhh.hue_usuarioevaluacion, use_usuarios evaluado, use_usuarios evaluador, use_usuarios supervisor
			WHERE ue_evaluado = evaluado.se_usuario
				AND ue_evaluador = evaluador.se_usuario(+)
				AND ue_supervisor = supervisor.se_usuario(+)
				AND ue_anoevaluacion = :ano _EXC1_";
	$grilla = new Grid();
	$grilla->addColumn(new Column("", 8, true, false, -1, "BotonInformacion", "/index.php?pageid=58", "GridFirstColumn"));
	$grilla->addColumn(new Column("Evaluado"));
	$grilla->addColumn(new Column("Evaluador"));
	$grilla->addColumn(new Column("Supervisor"));
	$grilla->addColumn(new Column("", 0, false, true));
	$grilla->setBaja(5, $sb, false);
	$grilla->setColsSeparator(true);
	$grilla->setExtraConditions(array($where));
	$grilla->setFieldBaja("UE_FECHABAJA");
	$grilla->setOrderBy($ob);
	$grilla->setPageNumber($pagina);
	$grilla->setParams($params);
	$grilla->setRowsSeparator(true);
	$grilla->setSql($sql);
	$grilla->Draw();
}
?>
	</div>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "competenciasBusqueda";
$RCparams = array();
$RCquery = 
	"SELECT 'S' id, 'Mostrar' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'N', 'No Mostrar'
  	 FROM DUAL";
$RCselectedItem = $competencias;
FillCombo();

$RCfield = "estadoBusqueda";
$RCparams = array();
$RCquery = 
	"SELECT 1 id, 'Activo' detalle
		 FROM DUAL
UNION ALL
	 SELECT 0, 'Inactivo'
  	 FROM DUAL";
$RCselectedItem = $estado;
FillCombo();
?>
	document.getElementById('anoBusqueda').focus();
</script>