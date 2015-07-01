<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");

if (!isset($_SESSION["identidad"]))
	$_SESSION["identidad"] = getWindowsLoginName(true);

$ano = date("Y");
$user = $_SESSION["identidad"];

$params = array(":evaluador" => $user, ":ano" => $ano);
$sql =
	"SELECT 1
		 FROM rrhh.rue_usuarioevaluacion
		WHERE ue_evaluador = UPPER(:evaluador)
			AND ue_anio = :ano";
$esEvaluador = (valorSql($sql, -1, $params) == 1);

$permisoCambioIdentidad = array("ALAPACO", "JBALESTRINI", "EVILA");

require_once("index_combos.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<?= getHead("Sistema de Evaluación de Desempeño", array("style.css?today=".date("Ymd"), "/modules/evaluacion_desempeno/css/evaluacion_desempeno.css"))?>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script language="JavaScript" src="/modules/evaluacion_desempeno/js/hint_config.js?rnd=<?= time()?>"></script>
		<script language="JavaScript" src="/modules/evaluacion_desempeno/js/evaluacion.js?rnd=<?= time()?>"></script>
	</head>

	<body>
		<iframe id="iframeEvaluacion" name="iframeEvaluacion" src="" style="display:none;"></iframe>
		<form action="/modules/evaluacion_desempeno/procesar_formulario.php" id="formEvaluacion" method="post" name="formEvaluacion" target="iframeEvaluacion">
			<input id="cerrarEvaluacion" name="cerrarEvaluacion" type="hidden" />
			<input id="evaluado" name="evaluado" type="hidden" />
			<input id="evaluador" name="evaluador" type="hidden" />
			<input id="supervisor" name="supervisor" type="hidden" />
			<div id="divPrincipal">
				<div id="divTitulo">
					<div id="divTituloTitulo"><b>SISTEMA DE EVALUACIÓN DEL DESEMPEÑO</b></div>
					<div id="divLogo"><img id="imgLogo" src="/modules/evaluacion_desempeno/images/logo_art.png" /></div>
					<div id="divNada"></div>
				</div>

				<div>
					<div class="divLinea2">
						<img id="imgUsuarioActual" src="/modules/evaluacion_desempeno/images/user.jpg" />
						<span><b>Usuario Actual</b></span>
						<span id="spanUsuarioActual"><?= getUserName($_SESSION["identidad"]) ?></span>
					</div>
					<div class="divLinea2">
						<table id="tableCombosTop">
							<tr>
								<td>Año</td>
								<td><?= $comboAno->draw();?></td>
							</tr>
							<tr>
								<td>Usuario a evaluar</td>
								<td><?= $comboUsuarioAEvaluar->draw();?></td>
							</tr>
							<tr>
								<td>Período</td>
								<td id="tdPeriodo">---</td>
							</tr>
						</table>
					</div>
					<div class="divLinea2">
<?
if (in_array(getWindowsLoginName(true), $permisoCambioIdentidad)) {
?>
	<img id="imgCambiarIdentidad" src="/modules/evaluacion_desempeno/images/cambiar_identidad.png" style="cursor:pointer;" title="Cambiar Identidad" onClick="cambiarIdentidad()" />
	<?
	}
	?>
<!--						<img id="imgVerResultados" src="/modules/evaluacion_desempeno/images/resultados.png" title="Ver Resultados" onClick="window.location.href='/modules/evaluacion_desempeno/resultados/'" />-->
						<img id="imgImprimirEvaluacion" src="/modules/evaluacion_desempeno/images/imprimir.jpg" title="Imprimir Evaluación" onClick="imprimirEvaluacion()" />
					</div>
					<div id="divNada"></div>
				</div>

				<div id="divLinea3">
					<div class="divLinea3" id="divDatosEvaluador">
						<div class="divTituloDatos"><b>DATOS EVALUADOR</b></div>
						<div class="divCamposDatos">
							<b>Nombre y Apellido</b>
							<span id="nombreEvaluador"></span>
						</div>
<!--						<div class="divCamposDatos">
							<b>Puesto</b>
							<span id="puestoEvaluador"></span>
						</div>-->
						<div class="divCamposDatos">
							<b>Área / Sector</b>
							<span id="sectorEvaluador"></span>
						</div>
						<div class="divCamposDatos">
							<b>Gerencia</b>
							<span id="gerenciaEvaluador"></span>
						</div>
					</div>

					<div class="divLinea3" id="divDatosEvaluado">
						<div class="divTituloDatos"><b>DATOS EVALUADO</b></div>
						<div class="divCamposDatos">
							<b>Nombre y Apellido</b>
							<span id="nombreEvaluado"></span>
						</div>
<!--						<div class="divCamposDatos">
							<b>Puesto</b>
							<span id="puestoEvaluado"></span>
						</div>-->
						<div class="divCamposDatos">
							<b>Área / Sector</b>
							<span id="sectorEvaluado"></span>
						</div>
						<div class="divCamposDatos">
							<b>Gerencia</b>
							<span id="gerenciaEvaluado"></span>
						</div>
					</div>
				</div>

				<div class="FormLabelRojo" id="divDatosNoCargados">
					<img src="/modules/evaluacion_desempeno/images/warning.gif" />
					<br />
					Su evaluación aún no está disponible.
					<br />
				</div>

				<div align="center" id="divDatos">
					<div id="divCompetenciasTitulo">
						<b>
							<span>Competencias</span>
						</b>
					</div>

					<div id="divCompetencias">
						<div id="divEvaluacionCompetenciasTitulo"><b>EVALUACIÓN DE COMPETENCIAS</b></div>
						<div id="divEvaluacionCompetenciasSubTitulo"><i>Teniendo en cuenta los requerimientos del puesto y los comportamientos observados, indicá para cada competencia el nivel en el que se encuentra el evaluado marcando en la casilla correspondiente.</i></div>
						<div id="divSPAC">
							<table id="grillaTabla">
								<tr>
									<th>COMPETENCIAS</th>
									<th>NIVELES</th>
									<th>OBSERVACIONES</th>
								</tr>
<?
$params = array(":anio" => $ano);
$sql =
	"SELECT ec_descripcion, ec_id
		 FROM rrhh.rec_evaluacioncompetencia
		WHERE ec_grupo = 'SPAC'
			AND ec_fechabaja IS NULL
			AND ec_anio = :anio
 ORDER BY ec_orden";
$stmt = DBExecSql($conn, $sql, $params);
$indexHint = 0;
while ($row = DBGetQuery($stmt)) {
?>
	<tr>
		<td class="grillaTablaColumna1"><b><?= $row["EC_DESCRIPCION"]?></b><br /><span id="spanNivelRequerido_<?= $row["EC_ID"]?>"></span></td>
		<td align="center">
<?
	for ($i=1; $i<=5; $i++) {
?>
		<input id="item_<?= $row["EC_ID"]?>" name="item_<?= $row["EC_ID"]?>" type="radio" value="<?= chr($i + 64)?>" />
		<span style="cursor:help;" onMouseOver="myHint.show(<?= $indexHint?>, this)" onMouseOut="myHint.hide()"><?= chr($i + 64)?></span>
<?
		$indexHint++;
	}
?>
		</td>
		<td align="center" class="grillaTablaColumna3">
			<textarea id="observaciones_<?= $row["EC_ID"]?>" maxlength="2000" name="observaciones_<?= $row["EC_ID"]?>" onKeyUp="contarCaracteresObservaciones(<?= $row["EC_ID"]?>); resizeTextarea(this);" onMouseUp="resizeTextarea(this)"></textarea><br />
			<div style="margin-bottom:8px; margin-top:-4px;">
				<span style="font-size:9px;">(máximo 2000 caracteres</span>
				<span style="font-size:9px;">restan <span id="caracteresRestantes_<?= $row["EC_ID"]?>">2000</span> caracteres)</span>
			</div>
		</td>
	</tr>
<?
}
?>
							</table>
						</div>

						<div align="left" id="divNoSPAC"></div>
					</div>

					<div id="divEvaluacionIntegradora">
						<div id="divEvaluacionIntegradoraCuadro1">EVALUACIÓN INTEGRADORA<br />DE COMPETENCIAS <span id="labelAno3"></span></div>
						<div id="divEvaluacionIntegradoraCuadro2">
							<p class="pEvaluacionIntegradoraCuadro2">
								<input disabled id="competencias" name="competencias" type="radio" value="1" />
								<span>El desarrollo de sus competencias es superior a lo requerido por el puesto</span>
							</p>
							<p class="pEvaluacionIntegradoraCuadro2">
								<input disabled id="competencias" name="competencias" type="radio" value="2" />
								<span>Presenta el nivel de desarrollo de las competencias requerido para el puesto</span>
							</p>
							<p class="pEvaluacionIntegradoraCuadro2">
								<input disabled id="competencias" name="competencias" type="radio" value="3" />
								<span>Falta desarrollar competencias para el nivel requerido para el puesto</span>
							</p>
						</div>
						<div id="divNada"></div>
					</div>

					<div id="divComentariosEvaluadorTitulo"><b>Comentarios Evaluador</b></div>
					<div><textarea id="comentariosEvaluador" maxlength="2000" name="comentariosEvaluador" onKeyUp="contarCaracteresComentarios(this, 'caracteresRestantesEvaluador'); resizeTextarea(this);" onMouseUp="resizeTextarea(this)"></textarea></div>
					<div style="margin-bottom:8px; margin-top:-4px;">
						<span style="font-size:9px;">(máximo 2000 caracteres</span>
						<span style="font-size:9px;">restan <span id="caracteresRestantesEvaluador">2000</span> caracteres)</span>
					</div>

					<div id="divComentariosEvaluadoTitulo"><b>Comentarios Evaluado</b></div>
					<div><textarea id="comentariosEvaluado" maxlength="2000" name="comentariosEvaluado" onKeyUp="contarCaracteresComentarios(this, 'caracteresRestantesEvaluado'); resizeTextarea(this);" onMouseUp="resizeTextarea(this)"></textarea></div>
					<div style="margin-bottom:8px; margin-top:-4px;">
						<span style="font-size:9px;">(máximo 2000 caracteres</span>
						<span style="font-size:9px;">restan <span id="caracteresRestantesEvaluado">2000</span> caracteres)</span>
					</div>

					<div id="divComentariosSupervisorTitulo"><b>Comentarios Supervisor</b></div>
					<div><textarea id="comentariosSupervisor" maxlength="2000" name="comentariosSupervisor" onKeyUp="contarCaracteresComentarios(this, 'caracteresRestantesSupervisor'); resizeTextarea(this);" onMouseUp="resizeTextarea(this)"></textarea></div>
					<div style="display:none; margin-bottom:8px; margin-top:-4px;">
						<span style="font-size:9px;">(máximo 2000 caracteres</span>
						<span style="font-size:9px;">restan <span id="caracteresRestantesSupervisor">2000</span> caracteres)</span>
					</div>

					<div id="divBotones">
						<input id="btnGuardar" name="btnGuardar" type="button" value="Guardar" onClick="guardarEvaluacion()" />
						<input id="btnMeNotifique" name="btnMeNotifique" type="button" value="Me Notifiqué" onClick="notificarEvaluacion()" />
						<input id="btnEnviarEvaluacion" name="btnEnviarEvaluacion" type="button" value="Enviar Evaluación" onClick="enviarEvaluacion()" />
						<img id="imgImprimirEvaluacion" src="/modules/evaluacion_desempeno/images/imprimir.jpg" title="Imprimir Evaluación" onClick="imprimirEvaluacion()" />
					</div>
				</div>

				<div>&nbsp;</div>

				<div id="divErroresForm">
					<img src="/images/atencion.png" />
					<span>No es posible continuar mientras no se corrijan los siguientes errores:</span>
					<br />
					<br />
					<span id="errores"></span>
					<input id="foco" name="foco" readonly type="checkbox" />
				</div>
			</div>
		</form>

		<script>
			cambiarUsuarioAEvaluar(document.getElementById('usuarioAEvaluar').value, <?= $ano?>);
		</script>
		<div id="msgOk" name="msgOk" style="display:none;">
			<p align="center"><br>&nbsp;<br><b>Los datos se guardaron correctamente.</b></p>
		</div>
	</body>
</html>