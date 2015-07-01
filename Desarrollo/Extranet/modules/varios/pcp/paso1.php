<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");
require_once("header.php");


$params = array(":id" => $_SESSION["pcpId"]);
$sql =
	"SELECT *
		 FROM afi.avp_valida_pcp
		WHERE vp_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$rowAVP = DBGetQuery($stmt);

// Teléfonos Empleador..
$dataTel = inicializarTelefonos(OCI_COMMIT_ON_SUCCESS, "vt_id_valida_pcp", $_SESSION["pcpId"], "vt", "afi.avt_valida_telefono_pcp", $_SESSION["usuario"]);
quitarTelefonosTemporales($dataTel);
copiarTelefonosATemp($dataTel, $_SESSION["usuario"]);

require("paso1_combos.php");
?>
<form action="<?= ($_SESSION["pcpId"]==-1)?"/modules/varios/pcp/login.php":"/modules/varios/pcp/procesar_paso1.php"?>" id="formPaso1" method="post" name="formPaso1" target="iframeProcesando" onSubmit="enviarForm()">
<?
if ($_SESSION["pcpId"] == -1) {
?>
	<div id="datosCargaInicial">
		<div id="datosPaso">Paso 1 de 2</div>
		<div>Estimado cliente, por favor ingrese su N° de C.U.I.T. y N° de contrato de afiliación asignado mediante sorteo por la SRT.</div>
		<div id="datosDivCuit">
			<label class="labelGrande" for="cuit">C.U.I.T. N°</label>
			<input autofocus class="cuit" id="cuitInicial" maxlength="13" name="cuitInicial" type="text" value="<?= $rowAVP["VP_CUIT"]?>" />
		</div>
		<div>
			<label class="labelGrande" for="contrato">CONTRATO</label>
			<input class="contrato" id="contratoInicial" maxlength="7" name="contratoInicial" type="text" value="<?= $rowAVP["VP_CONTRATO"]?>" />
		</div>
		<div><input id="btnIngresar" name="btnIngresar" type="submit" value="INGRESAR" /></div>
	</div>
<?
}
else {
?>
	<div id="datosGenerales">
		<div id="datosPaso">Paso 1 de 2</div>
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

	<div id="datosEmpleador">
		<div id="datosTitulo">DATOS DEL EMPLEADOR</div>
		<div>
			<label for="nombre">Nombre y Apellido</label>
			<input autofocus id="nombre" maxlength="512" name="nombre" type="text" value="<?= $rowAVP["VP_NOMBREAPELLIDO"]?>" />
		</div>
		<div id="datosDomicilioConstituido">DOMICILIO CONSTITUIDO</div>
		<div id="datosDivCalle">
			<label for="calle">Calle</label>
			<input class="calle" id="calle" maxlength="60" name="calle" type="text" value="<?= $rowAVP["VP_CALLE"]?>" />
			<label class="labelChico" for="numero">Nº</label>
			<input class="numero" id="numero" maxlength="20" name="numero" type="text" value="<?= $rowAVP["VP_NUMERO"]?>" />
			<label class="labelChico" for="piso">Piso</label>
			<input class="piso" id="piso" maxlength="20" name="piso" type="text" value="<?= $rowAVP["VP_PISO"]?>" />
			<label class="labelChico" for="departamento">Departamento</label>
			<input class="departamento" id="departamento" maxlength="20" name="departamento" type="text" value="<?= $rowAVP["VP_DEPARTAMENTO"]?>" />
		</div>
		<div id="datosDivCodigoPostal">
			<label for="codigoPostal">Código Postal</label>
			<input class="codigoPostal" id="codigoPostal" maxlength="5" name="codigoPostal" type="text" value="<?= $rowAVP["VP_CPOSTAL"]?>" onBlur="cargarComboLocalidad('');" />
			<label class="labelChico" for="provincia">Provincia</label>
			<?= $comboProvincia->draw();?>
			<label class="labelChico" for="localidad">Localidad</label>
			<?= $comboLocalidadCombo->draw();?>
			<input class="localidad" id="localidad" maxlength="60" name="localidad" type="text" value="<?= $rowAVP["VP_LOCALIDAD"]?>" />
		</div>
		<div id="datosDivEmail">
			<label for="email">e-Mail</label>
			<input class="email" id="email" maxlength="200" name="email" type="text" value="<?= $rowAVP["VP_EMAIL"]?>" />
		</div>
		<div id="datosDivTelefonos">
			<iframe frameborder="no" height="0" id="iframeTelefonos" name="iframeTelefonos" scrolling="no" src="/functions/telefonos/telefonos.php?s=isNuevoPCP&idModulo=-1&idTablaPadre=<?= $_SESSION["pcpId"]?>&tablaTel=afi.avt_valida_telefono_pcp&campoClave=vt_id_valida_pcp&prefijo=vt<?= ($rowAVP["VP_FECHAIMPRESION"] != "")?"&r=t":""?>" width="944" onLoad="ajustarTamanoIframe(this, 80)"></iframe>
		</div>
	</div>

	<div id="datosLugaresTrabajo">
		<div id="datosTitulo">DETALLE DE LUGARES DE TRABAJO</div>
<?
	$params = array(":id_valida_pcp" => $_SESSION["pcpId"]);
	$sql =
		"SELECT *
			 FROM afi.avl_valida_lugartrabajo_pcp
			WHERE vl_id_valida_pcp = :id_valida_pcp
	 ORDER BY 1";
	$stmt = DBExecSql($conn, $sql, $params);	

	for ($i=1; $i<=5; $i++) {
		$rowAVL = DBGetQuery($stmt);

		// Lleno los combos..
		$sql =
			"SELECT 1
				 FROM DUAL
				WHERE 1 = 2";
		$comboLocalidad = new Combo($sql, "localidadCombo_".$i, $rowAVL["VL_LOCALIDAD"]);
		$comboLocalidad->setClass("localidadCombo");
		$comboLocalidad->setFirstItem("- INGRESE EL CÓDIGO POSTAL Y LA PROVINCIA -");
		$comboLocalidad->setOnChange("cambiarLocalidad(this.value, '_".$i."')");

		$sql =
			"SELECT pv_codigo id, pv_descripcion detalle
				 FROM cpv_provincias
				WHERE pv_fechabaja IS NULL
		 ORDER BY 2";
		$comboProvincia = new Combo($sql, "provincia_".$i, ($rowAVL["VL_PROVINCIA"]=="")?-1:$rowAVL["VL_PROVINCIA"]);
		$comboProvincia->setOnChange("cargarComboLocalidad('_".$i."')");

		// Teléfonos..
		$dataTel = inicializarTelefonos(OCI_COMMIT_ON_SUCCESS, "vt_id_valida_lugartrabajo_pcp", (($rowAVL)?$rowAVL["VL_ID"]:-$i), "vt", "afi.avt_valida_telefono_lt_pcp", $_SESSION["usuario"]);
		quitarTelefonosTemporales($dataTel);
		copiarTelefonosATemp($dataTel, $_SESSION["usuario"]);
?>
		<div class="datosDivLugarTrabajo" id="datosDivLugarTrabajo_<?= $i?>" style="display:<?= ($rowAVL)?"block":"none"?>;">
			<input id="lugarTrabajoVisible_<?= $i?>" name="lugarTrabajoVisible_<?= $i?>" type="hidden" value="<?= ($rowAVL)?"t":"f"?>" />
			<input id="idLugarTrabajo_<?= $i?>" name="idLugarTrabajo_<?= $i?>" type="hidden" value="<?= ($rowAVL)?$rowAVL["VL_ID"]:-$i?>" />
			<div id="datosDomicilioConstituido">LUGAR DE TRABAJO <?= $i?></div>
			<div id="datosDivCalle">
				<label for="calle_<?= $i?>">Calle</label>
				<input class="calle" id="calle_<?= $i?>" maxlength="60" name="calle_<?= $i?>" type="text" value="<?= $rowAVL["VL_CALLE"]?>" />
				<label class="labelChico" for="numero_<?= $i?>">Nº</label>
				<input class="numero" id="numero_<?= $i?>" maxlength="20" name="numero_<?= $i?>" type="text" value="<?= $rowAVL["VL_NUMERO"]?>" />
				<label class="labelChico" for="piso_<?= $i?>">Piso</label>
				<input class="piso" id="piso_<?= $i?>" maxlength="20" name="piso_<?= $i?>" type="text" value="<?= $rowAVL["VL_PISO"]?>" />
				<label class="labelChico" for="departamento_<?= $i?>">Departamento</label>
				<input class="departamento" id="departamento_<?= $i?>" maxlength="20" name="departamento_<?= $i?>" type="text" value="<?= $rowAVL["VL_DEPARTAMENTO"]?>" />
			</div>
			<div id="datosDivCodigoPostal">
				<label for="codigoPostal_<?= $i?>">Código Postal</label>
				<input class="codigoPostal" id="codigoPostal_<?= $i?>" maxlength="5" name="codigoPostal_<?= $i?>" type="text" value="<?= $rowAVL["VL_CPOSTAL"]?>" onBlur="cargarComboLocalidad('_<?= $i?>');" />
				<label class="labelChico" for="provincia_<?= $i?>">Provincia</label>
				<?= $comboProvincia->draw();?>
				<label class="labelChico" for="localidad_<?= $i?>">Localidad</label>
				<?= $comboLocalidad->draw();?>
				<input class="localidad" id="localidad_<?= $i?>" maxlength="60" name="localidad_<?= $i?>" type="text" value="<?= $rowAVL["VL_LOCALIDAD"]?>" />
			</div>
			<div id="datosDivEmail">
				<label for="email_<?= $i?>">e-Mail</label>
				<input class="email" id="email_<?= $i?>" maxlength="200" name="email_<?= $i?>" type="text" value="<?= $rowAVL["VL_EMAIL"]?>" />
			</div>
			<div id="datosDivTelefonos">
				<iframe frameborder="no" height="0" id="iframeTelefonos<?= $i?>" name="iframeTelefonos<?= $i?>" scrolling="no" src="/functions/telefonos/telefonos.php?s=isNuevoPCP&idModulo=-1&idTablaPadre=<?= (($rowAVL)?$rowAVL["VL_ID"]:-$i)?>&tablaTel=afi.avt_valida_telefono_lt_pcp&campoClave=vt_id_valida_lugartrabajo_pcp&prefijo=vt<?= ($rowAVP["VP_FECHAIMPRESION"] != "")?"&r=t":""?>" width="944" onLoad="ajustarTamanoIframe(this, 80)"></iframe>
			</div>
		</div>
		<script type="text/javascript">
			setLocalidad('<?= $rowAVP["VP_LOCALIDAD"]?>', '_<?= $i?>');
		</script>
	<?
	}
	?>
		<div id="datosDivAgregarLugarTrabajo"><a href="javascript:agregarLugarTrabajo()" id="datosLinkAgregarLugarTrabajo">[+] Agregar otro Lugar de Trabajo</a></div>
	</div>
	<div id="guardarDiv">
		<input id="btnGuardar" name="btnGuardar" type="submit" value="GUARDAR Y CONTINUAR" />
		<img id="imgProcesando" src="/images/loading.gif" title="Procesando, aguarde un instante por favor..." />
	</div>
<?
}
?>
</form>

<script type="text/javascript">
<?
if ((isset($_SESSION["pcpId"])) and ($_SESSION["pcpId"] != -1)) {
?>
	if (document.getElementById('datosDivLugarTrabajo_1') != null) {
		document.getElementById('datosDivLugarTrabajo_1').style.display = 'inline';
		document.getElementById('lugarTrabajoVisible_1').value = 't';
		setLocalidad('<?= $rowAVP["VP_LOCALIDAD"]?>', '');
	}

	if ((document.getElementById('datosCargaInicial') != null) && (document.getElementById('datosCargaInicial').style.display != 'none'))
		document.getElementById('formPaso1').submit();
<?
	if ($rowAVP["VP_FECHAIMPRESION"] != "") {
?>
		deshabilitarControles(document.getElementById('datos'));
<?
	}
}
?>
</script>
<?
require_once("footer.php");
?>