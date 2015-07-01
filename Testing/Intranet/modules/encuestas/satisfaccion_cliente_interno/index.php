<?
function getGerencias() {
	global $conn;

	$result = GetUserIdSectorIntranet();

	$params = array(":id" => $result);
	$sql =
		"SELECT se_idsectorpadre
			 FROM computos.cse_sector
			WHERE se_id = :id";
	$id = ValorSql($sql, 0, $params);
	$result.= ",".$id;

	$params = array(":id" => $id);
	$sql =
		"SELECT se_idsectorpadre
			 FROM computos.cse_sector
			WHERE se_id = :id";
	$id = ValorSql($sql, 0, $params);
	$result.= ",".$id;

	$params = array(":id" => $id);
	$sql =
		"SELECT se_idsectorpadre
			 FROM computos.cse_sector
			WHERE se_id = :id";
	$id = ValorSql($sql, 0, $params);
	$result.= ",".$id;

	return $result;
}

function mostrarSector($idSector, $sector, $gerencia, &$primeroMarcado) {
	global $arrSectoresEvaluados;
	global $conn;
	global $sectorAEvaluar;

	$params = array(":sectorevaluado" => $idSector);
	$sql =
		"SELECT ea_usumodif
			 FROM rrhh.rea_encuestaclienteinterno
			WHERE ea_gerenciaevaluadora in (".getGerencias().")
				AND ea_sectorevaluado = :sectorevaluado";
	if (ValorSql($sql, "", $params) != "")
		$permiso = 2;
	else
		$permiso = (in_array($idSector, $arrSectoresEvaluados))?1:0;

	if ($permiso > 0) {
		if ((!$primeroMarcado) and ($permiso == 1)) {
			$css = "background-color:#79e067; color:#000;";
			$primeroMarcado = true;
			$sectorAEvaluar = $idSector;
		}
		else
			$css = "background-color:#d2d2d2; color:#fff;";
?>
		<div style="<?= $css?> padding:2px;" title="<?= $gerencia?>"><?= $sector?></div>
<?
	}
}


$arrSectoresEvaluados = array();
$sql =
	"SELECT ea_gerenciaevaluadora, ea_sectorevaluado
		 FROM rrhh.rea_encuestaclienteinterno
		WHERE ea_gerenciaevaluadora IN(".getGerencias().")";
$stmt = DBExecSql($conn, $sql, array());
while ($row = DBGetQuery($stmt)) {
	$arrSectoresEvaluados[] = $row["EA_SECTOREVALUADO"];
	$gerenciaEvaluadora = $row["EA_GERENCIAEVALUADORA"];
}

$sql =
	"SELECT 1
		 FROM rrhh.rea_encuestaclienteinterno
		WHERE ea_gerenciaevaluadora IN(".getGerencias().")
			AND ea_usumodif IS NULL";
$quedanEncuestasSinCompletar = ExisteSql($sql, array());
?>
<script>
	function sacarColor(obj, sacar) {
		if (sacar)
			obj.style.borderColor = '#808080';
	}

	showTitle(true, 'ENCUESTA DE SATISFACCIÓN - CLIENTE INTERNO');
</script>
<?
$usuario = strtoupper(GetWindowsLoginName());
if (!(($usuario == "SSAIRE") or ($usuario == "RRODRIGUEZ") or ($usuario == "FMFIRENZE") or ($usuario == "JPRECAS") or
			($usuario == "GDRAGANI") or ($usuario == "GLOPEZ") or ($usuario == "SAVENDAÑO") or
			($usuario == "VDOMINGUEZ") or ($usuario == "BRUSSO") or ($usuario == "PATLANTE") or ($usuario == "PAIMAR") or
			($usuario == "FPEREZ"))) {
?>
<div align="center" style="margin-top:120px;">
	<p><span style="color:#f00; font-size:20px;"><?= $usuario?>: Usted no tiene permiso para entrar a este módulo.</span></p>
</div>
<?
}
elseif (!$quedanEncuestasSinCompletar) {
?>
<div align="center" style="margin-top:120px;">
	<p>
	<img border="0" src="/images/provart_blanco.png">
	<br />
	<span class="Pie">Le agradece su tiempo y colaboración.</span>
	</p>
</div>
<?
}
else {
?>
<div>
	<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
	<form action="/modules/encuestas/satisfaccion_cliente_interno/procesar_encuesta.php" id="formEncuesta" method="post" name="formEncuesta" target="iframeProcesando">
		<div style="cursor:default; float:left; height:416px;">
<?
$primeroMarcado = false;
$sectorAEvaluar = "";
mostrarSector(5014, "Análisis y Control de Gestión", "Gerencia de Análisis y Control de Gestión", $primeroMarcado);
mostrarSector(19028, "Calidad", "Gerencia de Análisis y Control de Gestión", $primeroMarcado);
mostrarSector(15029, "Administración de Personal y Sueldos", "Gerencia de Recursos Humanos", $primeroMarcado);
mostrarSector(21030, "Capacitación, Empleos y Comunicaciones", "Gerencia de Recursos Humanos", $primeroMarcado);
mostrarSector(23032, "Desarrollo - planes de acción de las gerencias", "Gerencia de Sistemas", $primeroMarcado);
mostrarSector(11042, "Marketing y Publicidad", "Gerencia General", $primeroMarcado);
mostrarSector(89123, "Seguridad informática", "Gerencia General", $primeroMarcado);
?>
		</div>
		<div style="float:left; height:416px; width:8px;"></div>
		<div style="float:left; width:280px;">
			<p style="margin-bottom:8px;">
				<label class="FormLabelAzul" style="margin-right:8px;">Sector a evaluar</label>
			</p>
			<p style="margin-bottom:4px;">
				<label class="FormLabelAzul" style="font-size:20px; margin-right:8px;">Tiempo de respuesta</label>
			</p>
			<p style="margin-bottom:8px;">
				<label class="FormLabel" style="margin-right:8px;">Cumplimiento dentro de plazos acordados</label>
			</p>
			<p style="margin-bottom:8px;">
				<label class="FormLabel" style="margin-right:8px;">Plazos de respuesta acorde a las necesidades y expectativas del área</label>
			</p>
			<p style="margin-bottom:4px;">
				<label class="FormLabelAzul" style="font-size:20px; margin-right:8px;">Calidad de respuesta</label>
			</p>
			<p style="margin-bottom:8px;">
				<label class="FormLabel" style="margin-right:8px;">Adecuación de la respuesta al requerimiento efectuado</label>
			</p>
			<p style="margin-bottom:8px;">
				<label class="FormLabel" style="margin-right:8px;">La respuesta del sector agrega valor, realiza propuestas y/o sugerencias de mejora</label>
			</p>
			<p style="margin-bottom:4px;">
				<label class="FormLabelAzul" style="font-size:20px; margin-right:8px;">Cordialidad en la respuesta</label>
			</p>
			<p style="margin-bottom:8px;">
				<label class="FormLabel" style="margin-right:8px;">Amabilidad y cortesía en el trato</label>
			</p>
			<p style="margin-bottom:8px;">
				<label class="FormLabel" style="margin-right:8px;">Predisposición y disponibilidad</label>
			</p>
		</div>
		<div style="float:left;">
			<input id="gerenciaEvaluadora" name="gerenciaEvaluadora" type="hidden" value="<?= $gerenciaEvaluadora?>" />
			<input id="sectorEvaluado" name="sectorEvaluado" type="hidden" value="<?= $sectorAEvaluar?>" />
			<p>
<?
$params = array(":id" => $sectorAEvaluar);
$sql =
	"SELECT se_descripcion
		 FROM computos.cse_sector
		WHERE se_id = :id";
?>
				<span id="sectorAEvaluar" style="color:#676767; font-family:Trebuchet MS; font-size:11pt;"><?= ValorSql($sql, "", $params)?></span>
			</p>
			<p style="margin-top:30px;">
				<select id="cumplimientoPlazos" name="cumplimientoPlazos" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom:1px;" onBlur="sacarColor(this, (this.value != -1));"></select>
			</p>
			<p style="margin-top:8px;">
				<select id="plazosRespuesta" name="plazosRespuesta" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom:1px;" onBlur="sacarColor(this, (this.value != -1));"></select>
			</p>
			<p style="margin-top:34px;">
				<select id="adecuacionRespuesta" name="adecuacionRespuesta" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom:1px;" onBlur="sacarColor(this, (this.value != -1));"></select>
			</p>
			<p style="margin-top:8px;">
				<select id="respuestaAgregaValor" name="respuestaAgregaValor" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom:1px;" onBlur="sacarColor(this, (this.value != -1));"></select>
			</p>
			<p style="margin-top:36px;">
				<select id="amabilidad" name="amabilidad" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom:1px;" onBlur="sacarColor(this, (this.value != -1));"></select>
			</p>
			<p style="margin-top:4px;">
				<select id="predisposicion" name="predisposicion" style="color: #676767; font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom:1px;" onBlur="sacarColor(this, (this.value != -1));"></select>
			</p>
		</div>
		<div style="float:left;">
			<p style="margin-top:8px;">
				<label class="FormLabelAzul" style="font-size:20px; margin-right:24px; vertical-align:32px;">Comentarios</label>
				<textarea class="FormTextArea" id="comentarios" name="comentarios" style="height:48px; width:363px;" onBlur="sacarColor(this, (this.value != ''));"></textarea>
			</p>
			<p style="margin-top:16px;">
				<input class="BotonBlanco" id="btnGuardar" name="btnGuardar" type="button" value="   Guardar   " onClick="document.getElementById('formEncuesta').submit();" />
				<span id="spanMsgError" style="color:#f00; display:none; margin-left:18px;">Complete los campos marcados en rojo.</span>
			</p>
		</div>
		<div style="clear:both;"></div>
	</form>
</div>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "cumplimientoPlazos";
$RCparams = array();
$RCquery =
	"SELECT 1 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo <> '0'
			AND tb_codigo <> '10'
			AND tb_fechabaja IS NULL
UNION ALL
	 SELECT 2 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo = '10'
			AND tb_fechabaja IS NULL
 ORDER BY 1, 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "plazosRespuesta";
$RCparams = array();
$RCquery =
	"SELECT 1 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo <> '0'
			AND tb_codigo <> '10'
			AND tb_fechabaja IS NULL
UNION ALL
	 SELECT 2 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo = '10'
			AND tb_fechabaja IS NULL
 ORDER BY 1, 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "adecuacionRespuesta";
$RCparams = array();
$RCquery =
	"SELECT 1 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo <> '0'
			AND tb_codigo <> '10'
			AND tb_fechabaja IS NULL
UNION ALL
	 SELECT 2 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo = '10'
			AND tb_fechabaja IS NULL
 ORDER BY 1, 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "respuestaAgregaValor";
$RCparams = array();
$RCquery =
	"SELECT 1 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo <> '0'
			AND tb_codigo <> '10'
			AND tb_fechabaja IS NULL
UNION ALL
	 SELECT 2 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo = '10'
			AND tb_fechabaja IS NULL
 ORDER BY 1, 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "amabilidad";
$RCparams = array();
$RCquery =
	"SELECT 1 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo <> '0'
			AND tb_codigo <> '10'
			AND tb_fechabaja IS NULL
UNION ALL
	 SELECT 2 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo = '10'
			AND tb_fechabaja IS NULL
 ORDER BY 1, 2";
$RCselectedItem = -1;
FillCombo();

$RCfield = "predisposicion";
$RCparams = array();
$RCquery =
	"SELECT 1 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo <> '0'
			AND tb_codigo <> '10'
			AND tb_fechabaja IS NULL
UNION ALL
	 SELECT 2 orden, tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'EDSCI'
			AND tb_codigo = '10'
			AND tb_fechabaja IS NULL
 ORDER BY 1, 2";
$RCselectedItem = -1;
FillCombo();
?>
	document.getElementById('cumplimientoPlazos').focus();
</script>
<?
}
?>