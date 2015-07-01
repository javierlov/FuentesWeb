<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");
require_once("header.php");


if ($_SESSION["pcpId"] == -1) {
	header("Location: /pcp");
	die();
}

if ($_SESSION["paso"] < 2) {
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

$params = array(":id_valida_pcp" => $_SESSION["pcpId"]);
$sql =
	"SELECT *
		 FROM afi.avr_valida_riesgo_pcp
		WHERE vr_id_valida_pcp = :id_valida_pcp";
$stmt = DBExecSql($conn, $sql, $params);
$rowAVR = DBGetQuery($stmt);
?>
<form action="/modules/varios/pcp/procesar_paso2.php" id="formPaso2" method="post" name="formPaso2" target="iframeProcesando" onSubmit="enviarForm()">
	<div id="datosGenerales">
		<div id="datosPaso">Paso 2 de 2</div>
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
		<div id="datosDivPedido">A continuación le solicitamos que valide/complete los siguiente datos:</div>
	</div>

	<div id="datosTareas">
		<div id="datosTitulo">DESCRIPCIÓN DE TAREAS Y RIESGOS LABORALES (POSEE CARÁCTER DE DECLARACIÓN JURADA DEL EMPLEADOR)</div>
		<div id="datosDivBreveDescripcionTareas">
			<label for="breveDescripcionTareas" id="labelBreveDescripcionTareas"><b>A. Breve descripción de tareas</b></label>
			<textarea autofocus id="breveDescripcionTareas" name="breveDescripcionTareas" maxlength="250"><?= $rowAVR["VR_DESCRIPCION"]?></textarea>
		</div>
		<div id="datosDivRiegoElectrico">
			<span><b>B. Riesgo Eléctrico</b></span>
			<div>
				<label for="electrico">¿ Posee sistema de protección para las personas en la instalación eléctrica ?</label>
				<label class="labelChico" for="electrico" id="labelElectrico" >SÍ</label>
				<input <?= ($rowAVR["VR_ELECTRICO"] == "S")?"checked":""?> id="electrico" name="electrico" type="radio" value="S" />
				<label class="labelChico" for="electrico" id="labelElectrico">NO</label>
				<input <?= ($rowAVR["VR_ELECTRICO"] == "N")?"checked":""?> id="electrico" name="electrico" type="radio" value="N" />
			</div>
		</div>
		<div id="datosDivRiegoIncendio">
			<span><b>C. Riesgo de Incendio</b></span>
			<div>
				<label for="incendio">¿ Posee algún dispositivo de extinción de incendio ?</label>
				<label class="labelChico" for="incendio" id="labelIncendio">SÍ</label>
				<input <?= ($rowAVR["VR_INCENDIO"] == "S")?"checked":""?> id="incendioS" name="incendio" type="radio" value="S" />
				<label class="labelChico" for="incendio" id="labelIncendio">NO</label>
				<input <?= ($rowAVR["VR_INCENDIO"] == "N")?"checked":""?> id="incendioN" name="incendio" type="radio" value="N" />
			</div>
			<div id="datosDivRiegoIncendioIndique">
				<span>Indique cual</span>
				<label class="labelChico" for="extintor" id="labelExtintor">Extintor portátil CO2</label>
				<input <?= ($rowAVR["VR_EXTINTOR"] == "1")?"checked":""?> id="extintor1" name="extintor" type="radio" value="1" />
				<label class="labelChico" for="extintor" id="labelExtintor">Extintor Triclase Polvo Químico</label>
				<input <?= ($rowAVR["VR_EXTINTOR"] == "2")?"checked":""?> id="extintor2" name="extintor" type="radio" value="2" />
				<label class="labelChico" for="extintor" id="labelExtintor">Extintor de Agua</label>
				<input <?= ($rowAVR["VR_EXTINTOR"] == "3")?"checked":""?> id="extintor3" name="extintor" type="radio" value="3" />
				<label class="labelChico" for="extintorCual" id="labelExtintor">Otros</label>
				<input class="input2" id="extintorCual" name="extintorCual" type="text" value="<?= $rowAVR["VR_EXTINTOR_CUAL"]?>" />
			</div>
		</div>
		<div id="datosDivRiegoQuimico">
			<span><b>D. Riesgo Químico</b></span>
			<div>
				<label>Indique que elementos químicos utiliza habitualmente:</label>
			</div>
			<div>
				<table width="100%">
					<tr>
						<td width="20%">Insecticidas</td>
						<td align="left" width="10%">
							<label for="insecticida">SÍ</label>
							<input <?= ($rowAVR["VR_INSECTICIDA"] == "S")?"checked":""?> id="insecticidaS" name="insecticida" type="radio" value="S" />
						</td>
						<td align="left" width="10%">
							<label for="insecticida">NO</label>
							<input <?= ($rowAVR["VR_INSECTICIDA"] == "N")?"checked":""?> id="insecticidaN" name="insecticida" type="radio" value="N" />
						</td>
						<td align="left" width="60%">
							<label for="insecticidaCual">¿ Cuáles ?</label>
							<input class="input2" id="insecticidaCual" name="insecticidaCual" type="text" value="<?= $rowAVR["VR_INSECTICIDA_CUAL"]?>">
						</td>
					</tr>
					<tr>
						<td>Bencina</td>
						<td>
							<label for="bencina">SÍ</label>
							<input <?= ($rowAVR["VR_BENCINA"] == "S")?"checked":""?> id="bencina" name="bencina" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="bencina">NO</label>
							<input <?= ($rowAVR["VR_BENCINA"] == "N")?"checked":""?> id="bencina" name="bencina" type="radio" value="N" />
						</td>
					</tr>
					<tr>
						<td>Raticidas</td>
						<td>
							<label for="raticida">SÍ</label>
							<input <?= ($rowAVR["VR_RATICIDA"] == "S")?"checked":""?> id="raticidaS" name="raticida" type="radio" value="S" />
						</td>
						<td>
							<label for="raticida">NO</label>
							<input <?= ($rowAVR["VR_RATICIDA"] == "N")?"checked":""?> id="raticidaN" name="raticida" type="radio" value="N" />
						</td>
						<td>
							<label for="raticidaCual">¿ Cuáles ?</label>
							<input class="input2" id="raticidaCual" name="raticidaCual" type="text" value="<?= $rowAVR["VR_RATICIDA_CUAL"]?>">
						</td>
					</tr>
					<tr>
						<td>Desinfectantes</td>
						<td>
							<label for="desinfectantes">SÍ</label>
							<input <?= ($rowAVR["VR_DESINFECTANTES"] == "S")?"checked":""?> id="desinfectantes" name="desinfectantes" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="desinfectantes">NO</label>
							<input <?= ($rowAVR["VR_DESINFECTANTES"] == "N")?"checked":""?> id="desinfectantes" name="desinfectantes" type="radio" value="N" />
						</td>
					</tr>
					<tr>
						<td>Detergentes</td>
						<td>
							<label for="detergentes">SÍ</label>
							<input <?= ($rowAVR["VR_DETERGENTES"] == "S")?"checked":""?> id="detergentes" name="detergentes" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="detergentes">NO</label>
							<input <?= ($rowAVR["VR_DETERGENTES"] == "N")?"checked":""?> id="detergentes" name="detergentes" type="radio" value="N" />
						</td>
					</tr>
					<tr>
						<td>Soda Caústica</td>
						<td>
							<label for="sodaCaustica">SÍ</label>
							<input <?= ($rowAVR["VR_SODACAUSTICA"] == "S")?"checked":""?> id="sodaCaustica" name="sodaCaustica" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="sodaCaustica">NO</label>
							<input <?= ($rowAVR["VR_SODACAUSTICA"] == "N")?"checked":""?> id="sodaCaustica" name="sodaCaustica" type="radio" value="N" />
						</td>
					</tr>
					<tr>
						<td>Desengrasante</td>
						<td>
							<label for="desengrasante">SÍ</label>
							<input <?= ($rowAVR["VR_DESENGRASANTE"] == "S")?"checked":""?> id="desengrasante" name="desengrasante" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="desengrasante">NO</label>
							<input <?= ($rowAVR["VR_DESENGRASANTE"] == "N")?"checked":""?> id="desengrasante" name="desengrasante" type="radio" value="N" />
						</td>
					</tr>
					<tr>
						<td>Hipoclorito de sodio (lavandina)</td>
						<td>
							<label for="hipocloritoDeSodio">SÍ</label>
							<input <?= ($rowAVR["VR_HIPOCLORITODESODIO"] == "S")?"checked":""?> id="hipocloritoDeSodio" name="hipocloritoDeSodio" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="hipocloritoDeSodio">NO</label>
							<input <?= ($rowAVR["VR_HIPOCLORITODESODIO"] == "N")?"checked":""?> id="hipocloritoDeSodio" name="hipocloritoDeSodio" type="radio" value="N" />
						</td>
					</tr>
					<tr>
						<td>Amoníaco</td>
						<td>
							<label for="amoniaco">SÍ</label>
							<input <?= ($rowAVR["VR_AMONIACO"] == "S")?"checked":""?> id="amoniaco" name="amoniaco" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="amoniaco">NO</label>
							<input <?= ($rowAVR["VR_AMONIACO"] == "N")?"checked":""?> id="amoniaco" name="amoniaco" type="radio" value="N" />
						</td>
					</tr>
					<tr>
						<td>Ácido clorhídrico muriático</td>
						<td>
							<label for="acidoMuriatico">SÍ</label>
							<input <?= ($rowAVR["VR_ACIDOMURIATICO"] == "S")?"checked":""?> id="acidoMuriatico" name="acidoMuriatico" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="acidoMuriatico">NO</label>
							<input <?= ($rowAVR["VR_ACIDOMURIATICO"] == "N")?"checked":""?> id="acidoMuriatico" name="acidoMuriatico" type="radio" value="N" />
						</td>
					</tr>
				</table>
			</div>
			<div>
				<label for="otroRiesgoQuimico" id="labelOtroRiesgoQuimico">Otros</label>
				<textarea id="otroRiesgoQuimico" name="otroRiesgoQuimico"><?= $rowAVR["VR_OTRORIESGOQUIMICO"]?></textarea>
			</div>
		</div>
		<div id="datosDivInstalacionesEdilicias">
			<span><b>E. Instalaciones Edilicias</b></span>
			<div>
				<label>Indique cuales de las siguientes situaciones posee en su vivienda:</label>
			</div>
			<div>
				<table width="100%">
					<tr>
						<td>Protecciones en borde de losas y balcones (barandas)</td>
						<td>
							<label for="proteccionBalcones">SÍ</label>
							<input <?= ($rowAVR["VR_PROTECCIONBALCONES"] == "S")?"checked":""?> id="proteccionBalcones" name="proteccionBalcones" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="proteccionBalcones">NO</label>
							<input <?= ($rowAVR["VR_PROTECCIONBALCONES"] == "N")?"checked":""?> id="proteccionBalcones" name="proteccionBalcones" type="radio" value="N" />
						</td>
					</tr>
					<tr>
						<td width="40%">Realizan tareas interiores en altura, a mas de 2.00 mts.</td>
						<td align="left" width="6%">
							<label for="interiorAltura">SÍ</label>
							<input <?= ($rowAVR["VR_INTERIORALTURA"] == "S")?"checked":""?> id="interiorAlturaS" name="interiorAltura" type="radio" value="S" />
						</td>
						<td align="left" width="6%">
							<label for="interiorAltura">NO</label>
							<input <?= ($rowAVR["VR_INTERIORALTURA"] == "N")?"checked":""?> id="interiorAlturaN" name="interiorAltura" type="radio" value="N" />
						</td>
						<td align="left" width="48%">
							<label for="interiorAlturaCual">¿ Cuáles ?</label>
							<input class="input2" id="interiorAlturaCual" name="interiorAlturaCual" type="text" value="<?= $rowAVR["VR_INTERIORALTURA_CUAL"]?>">
						</td>
					</tr>
					<tr>
						<td>Realizan tareas exteriores en altura, a mas de 2.00 mts.<br />(fachadas y frentes/contrafrentes)</td>
						<td>
							<label for="exteriorAltura">SÍ</label>
							<input <?= ($rowAVR["VR_EXTERIORALTURA"] == "S")?"checked":""?> id="exteriorAlturaS" name="exteriorAltura" type="radio" value="S" />
						</td>
						<td>
							<label for="exteriorAltura">NO</label>
							<input <?= ($rowAVR["VR_EXTERIORALTURA"] == "N")?"checked":""?> id="exteriorAlturaN" name="exteriorAltura" type="radio" value="N" />
						</td>
						<td>
							<label for="exteriorAlturaCual">¿ Cuáles ?</label>
							<input class="input2" id="exteriorAlturaCual" name="exteriorAlturaCual" type="text" value="<?= $rowAVR["VR_EXTERIORALTURA_CUAL"]?>">
						</td>
					</tr>
					<tr>
						<td>Escaleras con barandas</td>
						<td>
							<label for="escaleraBaranda">SÍ</label>
							<input <?= ($rowAVR["VR_ESCALERABARANDA"] == "S")?"checked":""?> id="escaleraBaranda" name="escaleraBaranda" type="radio" value="S" />
						</td>
						<td colspan="2">
							<label for="escaleraBaranda">NO</label>
							<input <?= ($rowAVR["VR_ESCALERABARANDA"] == "N")?"checked":""?> id="escaleraBaranda" name="escaleraBaranda" type="radio" value="N" />
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div id="datosDivRopaElementosTrabajo">
			<span><b>F. Ropa y elementos de trabajo</b></span>
			<div>
				<table width="100%">
					<tr>
						<td width="39.9%">Entrega indumentaria de trabajo<br />(Ejemplo: calzado, delantal, pantalón, camisa, vestido, etc.)</td>
						<td align="left" width="6%">
							<label for="indumentaria">SÍ</label>
							<input <?= ($rowAVR["VR_INDUMENTARIA"] == "S")?"checked":""?> id="indumentariaS" name="indumentaria" type="radio" value="S" />
						</td>
						<td align="left" width="6%">
							<label for="indumentaria">NO</label>
							<input <?= ($rowAVR["VR_INDUMENTARIA"] == "N")?"checked":""?> id="indumentariaN" name="indumentaria" type="radio" value="N" />
						</td>
						<td align="left" width="48.1%">
							<label for="indumentariaCual">¿ Cuáles ?</label>
							<input class="input2" id="indumentariaCual" name="indumentariaCual" type="text" value="<?= $rowAVR["VR_INDUMENTARIA_CUAL"]?>">
						</td>
					</tr>
					<tr>
						<td>Entrega de Elementos de protección personal<br />(Ejemplo: Guantes, etc.)</td>
						<td>
							<label for="proteccionPersonal">SÍ</label>
							<input <?= ($rowAVR["VR_PROTECCIONPERSONAL"] == "S")?"checked":""?> id="proteccionPersonalS" name="proteccionPersonal" type="radio" value="S" />
						</td>
						<td>
							<label for="proteccionPersonal">NO</label>
							<input <?= ($rowAVR["VR_PROTECCIONPERSONAL"] == "N")?"checked":""?> id="proteccionPersonalN" name="proteccionPersonal" type="radio" value="N" />
						</td>
						<td>
							<label for="proteccionPersonalCual">¿ Cuáles ?</label>
							<input class="input2" id="proteccionPersonalCual" name="proteccionPersonalCual" type="text" value="<?= $rowAVR["VR_PROTECCIONPERSONAL_CUAL"]?>">
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div id="guardarDiv">
		<input id="btnVolver" name="btnVolver" type="button" value="" onClick="window.location.href='/pcp'" />
		<input id="btnGuardar" name="btnGuardar" type="submit" value="GUARDAR DATOS E IMPRIMIR CONTRATO" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde un instante por favor..." />
	</div>
</form>

<script type="text/javascript">
<?
if ($rowAVP["VP_FECHAIMPRESION"] != "") {
?>
	deshabilitarControles(document.getElementById('datos'));
<?
}
?>
</script>
<?
require_once("footer.php");
?>