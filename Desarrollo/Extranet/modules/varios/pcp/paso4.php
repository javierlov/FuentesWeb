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

<div id="datosDivPaso4">
	<div>
		<a href="/pcp-pdf/contrato" id="datosSpanFinal" target="_blank">
			<img class="imgPdf" src="/images/pdf.png" />
			<span>PDF Contrato</span>
		</a>
	</div>
	<div id="datosDivPaso4Pep">
		<a href="/pcp-pdf/pep" id="datosSpanFinal" target="_blank">
			<img class="imgPdf" src="/images/pdf.png" />
			<span>PDF PEP (Personas Expuestas Políticamente)</span>
		</a>
	</div>
</div>
<?
require_once("footer.php");
?>