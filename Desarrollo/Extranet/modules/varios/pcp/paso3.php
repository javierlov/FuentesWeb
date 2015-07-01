<?
require_once("header.php");


if ($_SESSION["pcpId"] == -1) {
	header("Location: /pcp");
	die();
}

if ($_SESSION["paso"] < 3) {
	header("Location: /pcp");
	die();
}

$params = array(":id" => $_SESSION["pcpId"]);
$sql =
	"SELECT *
		 FROM afi.avp_valida_pcp
		WHERE vp_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$rowAVP = DBGetQuery($stmt);

$_SESSION["paso"] = 4;
?>
<div id="datosGenerales">
	<div id="datosDivCuit">
		<label class="labelGrande" for="cuit">C.U.I.T. N°</label>
		<input class="cuit" id="cuit" maxlength="13" name="cuit" readonly type="text" value="<?= $rowAVP["VP_CUIT"]?>" />
	</div>
	<div>
		<label class="labelGrande" for="contrato">CONTRATO</label>
		<input class="contrato" id="contrato" maxlength="7" name="contrato" readonly type="text" value="<?= $rowAVP["VP_CONTRATO"]?>" />
		<label class="labelChico labelGrande">VIGENCIA</label>
		<label for="vigenciaDesde">Desde el</label>
		<input class="fecha" id="vigenciaDesde" maxlength="10" name="vigenciaDesde" readonly type="text" value="<?= $rowAVP["VP_VIGENCIADESDE"]?>" />
		<label class="labelChico" for="vigenciaHasta">Hasta el</label>
		<input class="fecha" id="vigenciaHasta" maxlength="10" name="vigenciaHasta" readonly type="text" value="<?= $rowAVP["VP_VIGENCIAHASTA"]?>" />
	</div>
</div>

<div id="datosDivFinal">
	<span id="datosSpanFinal">Los datos han sido guardados correctamente.</span>
	<div id="datosDivAdvertenciaFinal">
		<b>IMPORTANTE:</b><br />
		Le informamos que la modificación de datos de su contrato <b>SOLAMENTE</b> será procesada una vez que la correspondiente documentación firmada por usted se encuentre en poder de esta Aseguradora.<br />
		Lo puede acercar a nuestras oficinas comerciales o bien remitirlo en forma gratuita a través de Correo Argentino:<br /><br />
		> Apartado Especial Nº 4 Suc. Nº 1 (Av. de Mayo CP 1084), en sobre tamaño máximo 15 x 23 cm.
	</div>
	<div id="datosDivBotonFinal"><input id="btnGuardar" name="btnGuardar" type="button" value="CONFIRMACIÓN DE LOS DATOS E IMPRESIÓN DE CONTRATO" onClick="window.location.href = '/pcp-4'" /></div>
</div>
<?
require_once("footer.php");
?>