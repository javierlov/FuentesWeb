<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION[$_REQUEST["s"]]));

SetDateFormatOracle("DD/MM/YYYY");

$isAlta = ($_REQUEST["idTelefono"] < 1);
if (!$isAlta) {
	$params = array(":id" => $_REQUEST["idTelefono"]);
	$sql =
		"SELECT mp_area, mp_idtipotelefono, mp_interno, mp_numero, mp_observacion, mp_principal, mp_registrotelid, mp_tipo
			 FROM tmp.tmp_telefonos
			WHERE mp_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}

require_once("telefono_combos.php");
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script type="text/javascript">
			function eliminarTelefono() {
				if (confirm('¿ Realmente desea dar de baja este teléfono ?'))
					with (document) {
						getElementById('baja').value = 't';
						getElementById('formTelefono').submit();
					}
			}

			function setearNumero() {
				with (document) {
					if (getElementById('tipoTelefono').value == 3)
						getElementById('prefijoCelular').style.display = 'inline';
					else
						getElementById('prefijoCelular').style.display = 'none';
				}
			}
		</script>
	</head>
	<body style="text-align:left;" onLoad="setearNumero();">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/functions/telefonos/procesar_telefono.php" id="formTelefono" method="post" name="formTelefono" target="iframeProcesando">
			<input id="baja" name="baja" type="hidden" value="f">
			<input id="campoClave" name="campoClave" type="hidden" value="<?= $_REQUEST["campoClave"]?>">
			<input id="id" name="id" type="hidden" value="<?= $_REQUEST["idTelefono"]?>">
			<input id="idModulo" name="idModulo" type="hidden" value="<?= $_REQUEST["idModulo"]?>">
			<input id="idRegistroTel" name="idRegistroTel" type="hidden" value="<?= (!$isAlta)?$row["MP_REGISTROTELID"]:0?>">
			<input id="idTablaPadre" name="idTablaPadre" type="hidden" value="<?= $_REQUEST["idTablaPadre"]?>">
			<input id="prefijo" name="prefijo" type="hidden" value="<?= $_REQUEST["prefijo"]?>">
			<input id="s" name="s" type="hidden" value="<?= $_REQUEST["s"]?>">
			<input id="tablaTel" name="tablaTel" type="hidden" value="<?= $_REQUEST["tablaTel"]?>">
			<input id="tipo" name="tipo" type="hidden" value="<?= $_REQUEST["tipo"]?>">
			<div style="margin-top:8px;">
				<div style="margin-bottom:2px; margin-left:0px;">
					<label class="ContenidoSeccion" for="tipoTelefono">Tipo de Teléfono (*)</label>
					<?= $comboTipoTelefono->draw();?>
				</div>
				<div style="margin-bottom:2px; margin-left:63px;">
					<label class="ContenidoSeccion" for="area">Área (*)</label>
					<input id="area" maxlength="5" name="area" style="margin-left:2px; width:48px;" type="text" value="<?= (!$isAlta)?$row["MP_AREA"]:""?>" />
				</div>
				<div style="margin-bottom:2px; margin-left:45px;">
					<label class="ContenidoSeccion" for="numero">Número (*)</label>
					<input id="prefijoCelular" name="prefijoCelular" readonly style="display:none; margin-left:2px; width:24px;" type="text" value="15" />
					<input id="numero" maxlength="8" name="numero" style="margin-left:2px; width:184px;" type="text" value="<?= (!$isAlta)?$row["MP_NUMERO"]:""?>" />
				</div>
				<div style="margin-bottom:2px; margin-left:48px;">
					<label class="ContenidoSeccion" for="interno">Interno</label>
					<input id="interno" maxlength="10" name="interno" style="margin-left:23px; width:88px;" type="text" value="<?= (!$isAlta)?$row["MP_INTERNO"]:""?>" />
				</div>
				<div style="margin-bottom:2px; margin-left:41px;">
					<label class="ContenidoSeccion" for="principal">Principal</label>
					<input <?= ((!$isAlta) and ($row["MP_PRINCIPAL"] == "S"))?"checked":""?> id="principal" name="principal" style="margin-left:23px; vertical-align:-3px;" title="Indica si es el teléfono principal para contactarse" type="checkbox" value="on" />
				</div>
				<div style="margin-bottom:2px; margin-left:9px;">
					<label class="ContenidoSeccion" for="observaciones">Observaciones</label>
					<input id="observaciones" maxlength="100" name="observaciones" style="margin-left:23px; width:216px;" type="text" value="<?= (!$isAlta)?$row["MP_OBSERVACION"]:""?>" />
				</div>
				<div style="margin-bottom:8px; margin-top:16px;">
					<input class="btnGrabar" style="margin-left:16px;" type="submit" value="">
<?
if (!$isAlta) {
?>
					<input class="btnDarDeBaja" style="margin-left:34px;" type="button" value="" onClick="eliminarTelefono()" />
<?
}
?>
				</div>
<?
if (($isAlta) and ($_REQUEST["prefijo"] == "tt")) {
?>
				<div class="ContenidoSeccion" style="color:#000;">
					<b>
						<span style="margin-left:4px;">NOTA: Usted puede repetir esta operación tantas</span><br />
						<span style="margin-left:48px;">veces como teléfonos distintos tenga el</span>
						<span style="margin-left:48px;">trabajador.</span>
					</b>
				</div>
<?
}
?>
			</div>
		</form>
		<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:280px;">&nbsp;Datos guardados exitosamente.</p>
		<p id="borradoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:280px;">&nbsp;El teléfono fue dado de baja.</p>
		<div id="divErrores" style="display:none; margin-left:8px; width:360px;">
			<table border="1" bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
				<tr>
					<td>
						<table cellpadding="4" cellspacing="0">
							<tr>
								<td><img border="0" src="/images/atencion.jpg"></td>
								<td class="ContenidoSeccion">
									<font color="#000000">
										No es posible continuar mientras no se corrijan los siguientes errores:<br /><br />
										<span id="errores"></span>
									</font>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<input id="foco" name="foco" readonly style="height:1px; width:1px;" type="checkbox" />
		<script type="text/javascript">
			setTimeout('setearNumero()', 500);
		</script>
	</body>
</html>