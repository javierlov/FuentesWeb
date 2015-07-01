<?
if (!hasPermiso(99)) {
	showErrorIntranet("", "Usted no tiene permiso para ingresar a este módulo.");
	return;
}


$sql =
	"SELECT a.se_id, a.se_nombre, a.se_recibeemailintranet
		 FROM use_usuarios a, computos.cse_sector b
		WHERE a.se_idsector = b.se_id
			AND b.se_idsectorpadre = 15021
			AND a.se_fechabaja IS NULL
 ORDER BY 2";
$stmt = DBExecSql($conn, $sql);
?>
<link href="/modules/mantenimiento/css/usuarios_aviso.css" rel="stylesheet" type="text/css" />
<script src="/modules/mantenimiento/js/usuarios_aviso.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/mantenimiento/usuarios_aviso/guardar_usuarios_aviso.php" id="formUsuariosAviso" method="post" name="formUsuariosAviso" target="iframeProcesando">
	<div>
<?
while ($row = DBGetQuery($stmt)) {
?>
		<div class="fila">
			<label for="recibeEmail_<?= $row["SE_NOMBRE"]?>"><?= $row["SE_NOMBRE"]?></label>
			<input <?= ($row["SE_RECIBEEMAILINTRANET"] == "S")?"checked":""?> id="recibeEmail_<?= $row["SE_ID"]?>" name="recibeEmail_<?= $row["SE_ID"]?>" type="checkbox" value="ok" />
		</div>
<?
}
?>
	</div>
	<div id="divBotones">
		<input id="btnGuardar" name="btnGuardar" type="button" onClick="guardar()" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde unos segundos por favor..." />
		<input id="btnCancelar" name="btnCancelar" type="button" onClick="cancelar()" />
	</div>
	<div id="divErroresForm">
		<img src="/images/atencion.png" />
		<span>No es posible continuar mientras no se corrijan los siguientes errores:</span>
		<br />
		<br />
		<span id="errores"></span>
		<input id="foco" name="foco" readonly type="checkbox" />
	</div>
</form>