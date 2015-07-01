<script>
function ocultarMensajeOk() {
	document.getElementById('spanMensaje').style.display = 'none';
}

function volver() {
	if (document.getElementById('totalRegistros').value == 0)
		window.location.href='\ausentismo';
	else
		if (confirm('¿ Realmente desea volver ? NO se guardarán las modificaciones.'))
			window.location.href = '\ausentismo';
}

</script>
<link href="/modules/ausentismo/css/gestion.css" rel="stylesheet" type="text/css" />
<iframe id="iframeGestion" name="iframeGestion" src="" style="display:none;"></iframe>
<form action="/modules/ausentismo/procesar_gestion.php" id="formGestion" method="post" name="formGestion" target="iframeGestion" onSubmit="return ValidarForm(formGestion)">
	<table cellpadding="0" cellspacing="1" width="100%">
		<tr bgcolor="#6E96BC" class="FormLabelBlanco10">
			<td></td>
			<td align="center" class="bordeCeldaGris" width="13%">Fecha</td>
			<td align="center" class="bordeCeldaGris" width="22%">Reportado por</td>
			<td align="center" class="bordeCeldaGris" width="20%">Emp. Ausente</td>
			<td align="center" class="bordeCeldaGris" width="22%">Motivo</td>
			<td align="center" class="bordeCeldaGris" width="7%">Obs.</td>
			<td align="center" class="bordeCeldaGris" width="7%">Médico</td>
			<td align="center" class="bordeCeldaGris" width="7%">Just.</td>
			<td align="center" class="bordeCeldaGris" width="16%">Usuario</td>
			<td align="center" class="bordeCeldaGris">Acciones</td>
		</tr>
<?
$sql =
	"SELECT CASE
						WHEN ha_enviarmedico = 'T' THEN 'Sí'
						WHEN ha_enviarmedico = 'F' THEN 'No'
						ELSE '&nbsp;'
					END enviarmedico, TO_CHAR(ha_fechaalta, 'dd/mm/yyyy HH24:MI') fechaalta, ha_empleado, ha_id,
					ha_motivonoenviomedico, ha_observaciones, ma_detalle, se_id,
					UPPER(SUBSTR(ha_usualta, 1, 2)) || LOWER(SUBSTR(ha_usualta, 3, 1000)) usualta
		 FROM rrhh.rha_ausencias, rrhh.rma_motivosausencia, use_usuarios
		WHERE ha_idmotivoausencia = ma_id
			AND UPPER(ha_usualta) = se_usuario
			AND ha_fechaavisojefe IS NULL
 ORDER BY ha_fechaalta DESC";
$stmt = DBExecSql($conn, $sql);

$cant = 0;

$totalRegistros = DBGetRecordCount($stmt);
require_once("gestion_combos.php");

while ($row = DBGetQuery($stmt)) {
	$cant++;
?>
	<tr class="FormLabelNegro10">
		<td><input id="id<?= $cant?>" name="id<?= $cant?>" size="20" type="hidden" value="<?= $row["HA_ID"] ?>"><input id="empleado<?= $cant?>" name="empleado<?= $cant?>" size="20" type="hidden" value="<?= $row["HA_EMPLEADO"]?>"></td>
		<td align="center" class="bordeCeldaGris"><?= $row["FECHAALTA"]?></td>
		<td class="bordeCeldaGris"><a class="FormLabelNegro10" href="/index.php?pageid=56&id=<?= $row["SE_ID"]?>"><?= $row["USUALTA"]?></a></td>
		<td class="bordeCeldaGris"><?= $row["HA_EMPLEADO"]?></td>
		<td class="bordeCeldaGris"><?= $row["MA_DETALLE"]?></td>
		<td class="bordeCeldaGris" title="<?= $row["HA_OBSERVACIONES"]?>">Ver</td>
		<td align="center" class="bordeCeldaGris"><?= $row["ENVIARMEDICO"]?></td>
		<td class="bordeCeldaGris" title="<?= $row["HA_MOTIVONOENVIOMEDICO"]?>">Ver</td>
		<td align="center" class="bordeCeldaGris" id="tdUsuario<?= $cant?>" name="tdUsuario<?= $cant?>">
			<?= $comboUsuario[$cant - 1]->draw();?>
		</td>
		<td align="center" class="bordeCeldaGris" id="tdAcciones<?= $cant?>" name="tdAcciones<?= $cant?>">
			<?= $comboAcciones[$cant - 1]->draw();?>
		</td>
	</tr>
<?
}
if ($cant == 0) {
?>
	<tr height="32">
		<td align="center" colspan="8">No hay partes pendientes.</td>
	</tr>
<?
}
?>
	</table>
	<table cellpadding="0" cellspacing="0" width="100%">
		<tr height="16">
			<td colspan="4"></td>
		</tr>
		<tr>
			<td width="1000"><input id="totalRegistros" name="totalRegistros" size="20" type="hidden" value="<?= $totalRegistros ?>" /></td>
			<td>
				<input id="btnEnviar" name="btnEnviar" type="submit" value="" />
				<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
			</td>
			<td width="32"></td>
			<td><input class="btnVolver" type="button" value="" onClick="volver()" /></td>
		</tr>
		<tr height="8">
			<td colspan="4"></td>
		</tr>
		<tr>
			<td align="right" colspan="4"><span class="Mensaje" id="spanMensaje" name="spanMensaje" onMouseMove="ocultarMensajeOk()"><p>Los datos se guardaron correctamente.</span></td>
		</tr>
	</table>
	<div id="divErroresForm">
		<img src="/images/atencion.png" />
		<span>No es posible continuar mientras no se corrijan los siguientes errores:</span>
		<br />
		<br />
		<span id="errores"></span>
		<input id="foco" name="foco" readonly type="checkbox" />
	</div>
</form>