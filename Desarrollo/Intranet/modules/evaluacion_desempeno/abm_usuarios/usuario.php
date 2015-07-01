<?
$alta = !isset($_REQUEST["id"]);
if (!$alta) {
	// Inserto el registro si no existe..
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT ue_anoevaluacion, ue_evaluado
			 FROM rrhh.hue_usuarioevaluacion
			WHERE ue_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$params = array(":evaluado" => $row["UE_EVALUADO"], ":ano" => $row["UE_ANOEVALUACION"]);
	$sql =
		"SELECT 1
			 FROM rrhh.hfe_formularioevaluacion2008
			WHERE fe_evaluado = :evaluado
				AND fe_anoevaluacion = :ano";
	if (!ExisteSql($sql, $params)) {
		$params = array(":usualta" => GetWindowsLoginName(true),
										":ano" => $row["UE_ANOEVALUACION"],
										":evaluado" => $row["UE_EVALUADO"]);
		$sql =
			"INSERT INTO rrhh.hfe_formularioevaluacion2008 (fe_id, fe_estado, fe_evaluado, fe_fechadesde, fe_fechahasta, fe_usualta, fe_fechaalta, fe_anoevaluacion)
																							SELECT NULL, 1, ue_evaluado, pa_fechadesde, pa_fechahasta, :usualta, SYSDATE, :ano
																								FROM rrhh.hpa_parametro, rrhh.hue_usuarioevaluacion
																							 WHERE pa_estado = 1
																								 AND pa_fechabaja IS NULL
																								 AND pa_ano = :ano
																								 AND ue_evaluado = :evaluado
																								 AND ue_fechabaja IS NULL
																								 AND ue_anoevaluacion = :ano";
		DBExecSql($conn, $sql, $params);
	}

	$params = array(":id" => $_REQUEST["id"]);
	$sql = 
		"SELECT evaluado.se_nombre evaluado, evaluador.se_nombre evaluador, TO_CHAR(fe_fechadesde, 'dd/mm/yyyy') fechadesde, TO_CHAR(fe_fechahasta, 'dd/mm/yyyy') fechahasta,
						supervisor.se_nombre supervisor, ue_anoevaluacion, ue_categoria, ue_estado, ue_evaluado, ue_evaluador, ue_notificacion, ue_supervisor
			 FROM rrhh.hue_usuarioevaluacion, rrhh.hfe_formularioevaluacion2008, use_usuarios evaluado, use_usuarios evaluador, use_usuarios supervisor
			WHERE ue_evaluado = fe_evaluado
				AND ue_anoevaluacion = fe_anoevaluacion
				AND ue_evaluado = evaluado.se_usuario
				AND ue_evaluador = evaluador.se_usuario(+)
				AND ue_supervisor = supervisor.se_usuario(+)
				AND ue_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
	$notificacion = explode(";", $row["UE_NOTIFICACION"].";-1;-1");

	$params = array(":usuario" => $notificacion[0]);
	$sql =
		"SELECT se_nombre
			 FROM use_usuarios
			WHERE se_usuario = :usuario";
	$notificacion1 = ValorSql($sql, "", $params);

	$params = array(":usuario" => $notificacion[1]);
	$sql =
		"SELECT se_nombre
			 FROM use_usuarios
			WHERE se_usuario = :usuario";
	$notificacion2 = ValorSql($sql, "", $params);
}
?>
<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
<link href="/modules/evaluacion_desempeno/abm_usuarios/css/style.css" rel="stylesheet" type="text/css" />
<style>
	.hintText {
		background-color: #FFFFCC;
		color: #000000;
		font-family: tahoma, verdana, arial;
		font-size: 12px;
		padding: 5px;
	}
</style>
<script type="text/javascript" src="/js/popup/dhtmlwindow.js"></script>
<script type="text/javascript" src="/modules/evaluacion_desempeno/js/abm.js"></script>
<script type="text/javascript" src="/modules/evaluacion_desempeno/js/hint_config_abm.js"></script>
<script>
	divWin = null;

	function seleccionarPersona(titulo, nombre, id) {
		if ((divWin == null) || (divWin.style.display == 'none')) {
			medioancho = 400;
			medioalto = document.body.offsetHeight - 400;
			divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=480px,height=120px,left=' + medioancho + 'px,top=' + medioalto + 'px,resize=1,scrolling=1');
		}

		myHint.hide();
		divWin.load('iframe', '/modules/evaluacion_desempeno/abm_usuarios/seleccionar_persona.php?titulo=' + titulo + '&objNombre=' + nombre + '&objId=' + id, titulo);
		divWin.show();
	}

	showTitle(true, 'ABM USUARIOS EVALUACIÓN DESEMPEÑO');
</script>
<iframe id="iframeUsuario" name="iframeUsuario" src="" style="display:none;"></iframe>
<form action="/modules/evaluacion_desempeno/abm_usuarios/procesar_usuario.php" id="formUsuario" method="post" name="formUsuario" target="iframeUsuario" onSubmit="return ValidarForm(formUsuario)">
	<input id="id" name="id" type="hidden" value="<?= ($alta)?"":$_REQUEST["id"]?>">
	<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($alta)?"A":"M"?>">
	<span id="spanComboUsuarios" style="display:none;">
		<select class="Combo" id="usuarios" name="usuarios" title="Usuario"></select>
	</span>
	<p>
		<label for="evaluado">Evaluado</label>
		<input class="FormInputText" id="evaluado" name="evaluado" title="Evaluado" type="text" validar="true" value="<?= ($alta)?"":$row["EVALUADO"] ?>" readonly />
		<input id="evaluadoId" name="evaluadoId" type="hidden" value="<?= ($alta)?-1:$row["UE_EVALUADO"] ?>" />
		<input class="BotonBlanco" id="btnSeleccionarEvaluado" type="button" value=". . ." <?= ($alta)?"":"DISABLED" ?> onClick="seleccionarPersona('Evaluado', 'evaluado', 'evaluadoId');" onMouseOver="myHint.show(0, this)" onMouseOut="myHint.hide()">
	</p>
	<p>
		<label for="ano">Año</label>
<?
if ($alta) {
?>
	<select class="Combo" id="ano" name="ano" title="Año" validar="true"></select>
<?
}
else {
?>
	<input class="FormInputText" id="ano" name="ano" size="12" style="width:267px;" type="text" value="<?= $row["UE_ANOEVALUACION"] ?>" readonly />
<?
}
?>
	</p>
	<p>
		<label for="fechaDesde">Fecha Vigencia Desde</label>
		<input class="FormInputText" id="fechaDesde" maxlength="10" name="fechaDesde" size="12" type="text" title="Fecha Vigencia Desde" validar="true" validarFecha="true" value="<?= ($alta)?"":$row["FECHADESDE"]?>"><input class="BotonFecha" id="btnFechaDesde" name="btnFechaDesde" type="button" value="">
		<label for="fechaHasta" id="labelFechaHasta">Hasta</label>
		<input class="FormInputText" id="fechaHasta" maxlength="10" name="fechaHasta" size="12" type="text" title="Fecha Vigencia Hasta" validar="true" validarFecha="true" value="<?= ($alta)?"":$row["FECHAHASTA"]?>"><input class="BotonFecha" id="btnFechaHasta" name="btnFechaHasta" type="button" value="">
	</p>
	<p>
		<label for="estado">Estado</label>
		<select class="Combo" id="estado" name="estado" title="Estado" validar="true"></select>
	</p>
	<p>
		<label for="competencias">Competencias</label>
		<select class="Combo" id="competencias" name="competencias"></select>
	</p>
	<p>
		<label for="evaluador">Evaluador</label>
		<input class="FormInputText" id="evaluador" name="evaluador" type="text" value="<?= ($alta)?"":$row["EVALUADOR"] ?>" readonly />
		<input id="evaluadorId" name="evaluadorId" type="hidden" value="<?= ($alta)?-1:$row["UE_EVALUADOR"] ?>" />
		<input class="BotonBlanco" id="btnSeleccionarEvaluador" type="button" value=". . ." onClick="seleccionarPersona('Evaluador', 'evaluador', 'evaluadorId')" onMouseOver="myHint.show(1, this)" onMouseOut="myHint.hide()">
	</p>
	<p>
		<label for="supervisor">Supervisor</label>
		<input class="FormInputText" id="supervisor" name="supervisor" type="text" value="<?= ($alta)?"":$row["SUPERVISOR"] ?>" readonly />
		<input id="supervisorId" name="supervisorId" type="hidden" value="<?= ($alta)?-1:$row["UE_SUPERVISOR"] ?>" />
		<input class="BotonBlanco" id="btnSeleccionarSupervisor" type="button" value=". . ." onClick="seleccionarPersona('Supervisor', 'supervisor', 'supervisorId')" onMouseOver="myHint.show(2, this)" onMouseOut="myHint.hide()">
	</p>
	<p>
		<label for="notificacion1">Notificación 1</label>
		<input class="FormInputText" id="notificacion1" name="notificacion1" type="text" value="<?= ($alta)?"":$notificacion1 ?>" readonly />
		<input id="notificacion1Id" name="notificacion1Id" type="hidden" value="<?= ($alta)?-1:$notificacion[0] ?>" />
		<input class="BotonBlanco" id="btnSeleccionarNotificacion1" type="button" value=". . ." onClick="seleccionarPersona('Notificación 1', 'notificacion1', 'notificacion1Id')" onMouseOver="myHint.show(3, this)" onMouseOut="myHint.hide()">
	</p>
	<p>
		<label for="notificacion2">Notificación 2</label>
		<input class="FormInputText" id="notificacion2" name="notificacion2" type="text" value="<?= ($alta)?"":$notificacion2 ?>" readonly />
		<input id="notificacion2Id" name="notificacion2Id" type="hidden" value="<?= ($alta)?-1:$notificacion[1] ?>" />
		<input class="BotonBlanco" id="btnSeleccionarNotificacion2" type="button" value=". . ." onClick="seleccionarPersona('Notificación 2', 'notificacion2', 'notificacion2Id')" onMouseOver="myHint.show(4, this)" onMouseOut="myHint.hide()">
	</p>
	<p>
		<input class="BotonBlanco" id="btnGuardar" type="submit" value="Guardar">
		<input class="BotonBlanco" id="btnCancelar" type="button" value="Cancelar" onClick="history.go(-1);">
		<input class="BotonBlanco" id="btnDarBaja" type="button" value="Dar de Baja" <?= ($alta)?"DISABLED":""?> onClick="darBaja()">
	</p>
</form>
<div id="ABMWindow" name="ABMWindow" style="display:none"></div>
<div id="divCargandoDatos">Cargando datos temporales...<img class="imgTmp" /></div>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

if ($alta) {
	$RCfield = "ano";
	$RCparams = array();
	$RCquery =
		"SELECT 2008 id, 2008 detalle
			 FROM DUAL
	UNION ALL
	 	 SELECT 2009, 2009
  	 	 FROM DUAL
	UNION ALL
	 	 SELECT 2010, 2010
  	 	 FROM DUAL
 	 ORDER BY 2";
	$RCselectedItem = -1;
	FillCombo();
}

$RCfield = "estado";
$RCparams = array();
$RCquery =
	"SELECT 1 id, 'Activo' detalle
		 FROM DUAL
UNION ALL
	 SELECT 0, 'Inactivo'
  	 FROM DUAL";
$RCselectedItem = ($alta)?-1:$row["UE_ESTADO"];
FillCombo();

$RCfield = "competencias";
$RCparams = array();
$RCquery =
	"SELECT 'S' id, 'Mostrar' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'N', 'No Mostrar'
  	 FROM DUAL";
$RCselectedItem = ($alta)?-1:$row["UE_CATEGORIA"];
FillCombo();
?>
	document.body.style.cursor = 'wait';

	Calendar.setup (
		{
			inputField: "FechaDesde",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaDesde"
		}
 	);

	Calendar.setup (
		{
			inputField: "FechaHasta",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaHasta"
		}
 	);

	document.getElementById('btnDarBaja').style.display = '<?= ($alta)?"none":"inline"?>';
	setTimeout(llenarComboUsuarios, 70);
</script>