<script>
function ocultarMensajeOk() {
	document.getElementById('spanMensaje').style.display = 'none';
}

function volver() {
	if (document.getElementById('TotalRegistros').value == 0)
		window.location.href='\index.php?pageid=7';
	else
		if (confirm('¿ Realmente desea volver ? NO se guardarán las modificaciones.'))
			window.location.href='\index.php?pageid=7';
}

showTitle(true, 'PARTE DIARIO');
</script>
<iframe id="iframeGestion" name="iframeGestion" src="" style="display:none;"></iframe>
<form action="/modules/ausentismo/procesar_gestion.php" id="formGestion" method="post" name="formGestion" target="iframeGestion" onSubmit="return ValidarForm(formGestion)">
<table border="0" cellpadding="0" cellspacing="1" width="100%">
	<tr bgcolor="#6E96BC" class="FormLabelBlanco10">
		<td></td>
		<td align="center" class="BordeCeldaGris" width="13%">Fecha</td>
		<td align="center" class="BordeCeldaGris" width="22%">Reportado por</td>
		<td align="center" class="BordeCeldaGris" width="20%">Emp. Ausente</td>
		<td align="center" class="BordeCeldaGris" width="22%">Motivo</td>
		<td align="center" class="BordeCeldaGris" width="7%">Obs.</td>
		<td align="center" class="BordeCeldaGris" width="7%">Médico</td>
		<td align="center" class="BordeCeldaGris" width="7%">Just.</td>
		<td align="center" class="BordeCeldaGris" width="16%">Usuario</td>
		<td align="center" class="BordeCeldaGris">Acciones</td>
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
while ($row = DBGetQuery($stmt)) {
	$cant++;
?>
	<tr class="FormLabelNegro10">
		<td><input id="id<?= $cant?>" name="id<?= $cant?>" size="20" type="hidden" value="<?= $row["HA_ID"] ?>"><input id="empleado<?= $cant?>" name="empleado<?= $cant?>" size="20" type="hidden" value="<?= $row["HA_EMPLEADO"] ?>"></td>
		<td align="center" class="BordeCeldaGris"><?= $row["FECHAALTA"] ?></td>
		<td class="BordeCeldaGris"><a class="FormLabelNegro10" href="/index.php?pageid=56&id=<?= $row["SE_ID"]?>" style="text-decoration: none"><?= $row["USUALTA"] ?></a></td>
		<td class="BordeCeldaGris"><?= $row["HA_EMPLEADO"] ?></td>
		<td class="BordeCeldaGris"><?= $row["MA_DETALLE"] ?></td>
		<td class="BordeCeldaGris" style="cursor:pointer;" title="<?= $row["HA_OBSERVACIONES"] ?>">Ver</td>
		<td align="center" class="BordeCeldaGris"><?= $row["ENVIARMEDICO"] ?></td>
		<td class="BordeCeldaGris" style="cursor:pointer;" title="<?= $row["HA_MOTIVONOENVIOMEDICO"] ?>">Ver</td>
<?
	if ($cant == 1) {
?>
		<td align="center" class="BordeCeldaGris" id="tdUsuario1" name="tdUsuario1"><select class="Combo" id="Usuario<?= $cant?>" name="Usuario<?= $cant?>" size="1" validar="true" title="Usuario"></select></td>
		<td align="center" class="BordeCeldaGris" id="tdAcciones1" name="tdAcciones1"><select class="Combo" id="Acciones<?= $cant?>" name="Acciones<?= $cant?>" size="1" validar="true" title="Acciones"></select></td>
<?
	}
	else {
?>
		<td align="center" class="BordeCeldaGris" id="tdUsuario<?= $cant?>" name="tdUsuario<?= $cant?>"></td>
		<td align="center" class="BordeCeldaGris" id="tdAcciones<?= $cant?>" name="tdAcciones<?= $cant?>"></td>
<?
	}
?>
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
<table border="0" cellpadding="0" cellspacing="0" width="100%">
	<tr height="16">
		<td colspan="4"></td>
	</tr>
	<tr>
		<td width="1000"><input id="TotalRegistros" name="TotalRegistros" size="20" type="hidden" value="<?= $cant ?>"></td>
		<td><input class="BotonBlanco" name="btnEnviar" type="submit" value="ENVIAR"></td>
		<td width="32"></td>
		<td><input class="BotonBlanco" name="btnVolver" type="button" value="VOLVER" onClick="volver()"></td>
	</tr>
	<tr height="8">
		<td colspan="4"></td>
	</tr>
	<tr>
		<td align="right" colspan="4"><span class="Mensaje" id="spanMensaje" name="spanMensaje" style="display:none" onMouseMove="ocultarMensajeOk()"><p>Los datos se guardaron correctamente.</span></td>
	</tr>
</table>
</form>
<script>
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

if ($cant > 0) {
	$RCfield = "Usuario1";
	$RCparams = array();
	$RCquery =
		"SELECT   se_id ID, UPPER(SUBSTR(se_usuario, 1, 2)) || LOWER(SUBSTR(se_usuario, 3, 1000)) detalle
   			 FROM use_usuarios
 				WHERE se_fechabaja IS NULL
	   			AND se_usuariogenerico = 'N'
		 ORDER BY 2";
	$RCselectedItem = -1;
	FillCombo();

	$RCfield = "Acciones1";
	$RCparams = array();
	$RCquery =
		"SELECT   'F' ID, 'No informa' detalle
  			 FROM DUAL
				UNION ALL
		 SELECT   'T' ID, 'Informa' detalle
  			 FROM DUAL
	 	 ORDER BY 2";
	$RCselectedItem = -1;
	FillCombo();
}

for ($i=2; $i<=$cant; $i++) {
?>
	// Copio los usuarios..
	var ddlClone = document.getElementById('Usuario1').cloneNode(true);
	ddlClone.id = 'Usuario<?= $i?>';
	ddlClone.name = 'Usuario<?= $i?>';
	document.getElementById('tdUsuario<?= $i?>').appendChild(ddlClone);

	// Copio las acciones..
	var ddlClone = document.getElementById('Acciones1').cloneNode(true);
	ddlClone.id = 'Acciones<?= $i?>';
	ddlClone.name = 'Acciones<?= $i?>';
	document.getElementById('tdAcciones<?= $i?>').appendChild(ddlClone);
<?
}
?>
</script>