<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function enviarEmailSituacionAfiliatoria($msgError) {
	global $conn;

	$emailTo = $_SESSION["emailAvisoArt"];
	$subject = "Empresa con situación afiliatoria complicada";
	$body = getFileContent($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_cotizacion/plantillas/email_situacion_afiliatoria.html");

	$params = array(":id" => $_SESSION["canal"]);
	$sql = "SELECT ca_codigo || ' - ' || ca_descripcion FROM aca_canal WHERE ca_id = :id";
	$body = str_replace("@canal@", ValorSql($sql, "", $params, 0), $body);

	$params = array(":id" => $_SESSION["entidad"]);
	$sql = "SELECT en_codbanco || ' - ' || en_nombre FROM xen_entidad WHERE en_id = :id";
	$body = str_replace("@entidad@", ValorSql($sql, "", $params, 0), $body);

	if ($_SESSION["sucursal"] != "") {
		$params = array(":id" => $_SESSION["sucursal"]);
		$sql = "SELECT su_codsucursal || ' - ' || su_descripcion FROM asu_sucursal WHERE su_id = :id";
		$body = str_replace("@sucursal@", ValorSql($sql, "", $params, 0), $body);
	}
	else
		$body = str_replace("@sucursal@", "", $body);

	$params = array(":id" => $_POST["artTmp"]);
	$sql = "SELECT ar_nombre FROM aar_art WHERE ar_id = :id";
	$body = str_replace("@artactual@", ValorSql($sql, "", $params, 0), $body);

	$vendedor = "";
	if (isset($_POST["codigoVendedor"])) {
		$params = array(":vendedor" => IIF(($_POST["codigoVendedor"] == ""), "0", $_POST["codigoVendedor"]));
		$sql = "SELECT ve_vendedor || ' - ' || ve_nombre FROM xve_vendedor WHERE ve_vendedor = :vendedor";
		$vendedor = ValorSql($sql, "", $params, 0);
	}
	elseif ($_SESSION["vendedor"] == "") {
		$params = array(":identidad" => $_SESSION["entidad"]);
		$sql =
			"SELECT ve_vendedor || ' - ' || ve_nombre
				 FROM xev_entidadvendedor, xve_vendedor
				WHERE ve_id = ev_idvendedor
					AND ev_fechabaja IS NULL
					AND ve_fechabaja IS NULL
					AND ve_vendedor = '0'
					AND ev_identidad = :identidad";
		$vendedor = ValorSql($sql, "", $params, 0);
	}
	$body = str_replace("@codigovendedor@", $vendedor, $body);

	$params = array(":codigo" => $_POST["statusBcra"]);
	$sql = "SELECT tb_descripcion FROM ctb_tablas WHERE tb_clave = 'STBCR' AND tb_codigo = :codigo";
	$body = str_replace("@statusbcra@", ValorSql($sql, "", $params, 0), $body);

	$params = array(":codigo" => $_POST["statusSrtTmp"]);
	$sql = "SELECT tb_descripcion FROM ctb_tablas WHERE tb_clave = 'STSRT' AND tb_codigo = :codigo";
	$body = str_replace("@statussrt@", ValorSql($sql, "", $params, 0), $body);

	$body = str_replace("@cantidadestablecimientos@", $_POST["cantidadEstablecimientos"], $body);
	$body = str_replace("@ciiu1@", $_POST["ciiu1"], $body);
	$body = str_replace("@contacto@", $_POST["contacto"], $body);
	$body = str_replace("@cuit@", $_POST["cuit"], $body);
	$body = str_replace("@edadpromedio@", $_POST["edadPromedio"], $body);
	$body = str_replace("@email@", $_POST["email"], $body);
	$body = str_replace("@emailComercializador@", $_SESSION["email"], $body);
	$body = str_replace("@error@", $msgError, $body);
	$body = str_replace("@masasalarial1@", $_POST["masaSalarial1"], $body);
	$body = str_replace("@periodo@", $_POST["periodo"], $body);
	$body = str_replace("@razonsocial@", $_POST["razonSocial"], $body);
	$body = str_replace("@resultadomensualtrabajador@", $_POST["resultadoMensualPorTrabajador"], $body);
	$body = str_replace("@sector@", $_POST["sector"], $body);
	$body = str_replace("@sumafija@", $_POST["calculoSumaFija"], $body);
	$body = str_replace("@telefono@", $_POST["telefono"], $body);
	$body = str_replace("@trabajadores1@", $_POST["totalTrabajadores1"], $body);
	$body = str_replace("@usuario@", $_SESSION["usuario"], $body);
	$body = str_replace("@variable@", $_POST["calculoVariable"], $body);

	// Agrego los datos del CIIU 2..
	$str = "";
	if ($_POST["ciiu2"] != "") {
		$str.= "Cod. CIIU (2): ".$_POST["ciiu2"]."<br />";
		$str.= "Cant. Trabajadores (2): ".$_POST["totalTrabajadores2"]."<br />";
		$str.= "Masa Salarial (2): ".$_POST["masaSalarial2"]."<br />";
	}
	$body = str_replace("@ciiu2@", $str, $body);

	// Agrego los datos del CIIU 3..
	$str = "";
	if ($_POST["ciiu3"] != "") {
		$str.= "Cod. CIIU (3): ".$_POST["ciiu3"]."<br />";
		$str.= "Cant. Trabajadores (3): ".$_POST["totalTrabajadores3"]."<br />";
		$str.= "Masa Salarial (3): ".$_POST["masaSalarial3"]."<br />";
	}
	$body = str_replace("@ciiu3@", $str, $body);

	// Agrego los datos de la competencia..
	$str = "";
	switch ($_POST["rDatosCompetencia"]) {
		case "":
			$str = "Sin Dato<br />";
			break;
		case "A":
			$str = "Solo pago total mensual: ".$_POST["soloPagoTotalMensual"]."<br />";
			break;
		case "N":
			$str = "Formulario 931 Costo Fijo: ".$_POST["alicuotaCompetenciaSumaFija"]."<br />";
			$str.= "Formulario 931 Costo Variable: ".$_POST["alicuotaCompetenciaVariable"]."<br />";
			break;
		case "S":
			$str = "Alícuota Competencia Costo Fijo: ".$_POST["formulario931CostoFijo"]."<br />";
			$str.= "Alícuota Competencia Costo Variable: ".$_POST["formulario931CostoVariable"]."<br />";
			break;
	}
	$body = str_replace("@datoscompetencia@", $str, $body);

	// Agrego los datos de los establecimientos..
	$str = "";
	$params = array(":usualta" => $_SESSION["usuario"]);
	$sql =
		"SELECT '= ' || zg_descripcion || ', ' || cp_localidadcap || ', ' || ta_detalle || ', ' || ac_codigo || ', ' || TO_CHAR(eu_trabajadores) establecimiento
			 FROM afi.aeu_establecimientos, afi.azg_zonasgeograficas, art.ccp_codigopostal, afi.ata_tipoactividad, cac_actividad
			WHERE eu_idzonageografica = zg_id(+)
				AND eu_idlocalidad = cp_id(+)
				AND eu_idtipoactividad = ta_id(+)
				AND eu_idactividad = ac_id(+)
				AND eu_idsolicitud = -1
				AND eu_usualta = :usualta
				AND eu_usuarioweb = 'T'
				AND eu_fechabaja IS NULL";
	$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	$i = 1;
	while ($row = DBGetQuery($stmt)) {
		$str.= $i.$row["ESTABLECIMIENTO"]."<br />";
		$i++;
	}
	$body = str_replace("@establecimientos@", $str, $body);

	SendEmail($body, "Web", $subject, array($emailTo), array(), array(), "H");
}

function formatPeriodo($periodo) {
	if (!is_int(substr($periodo, 4, 1)))
		$periodo = substr_replace($periodo, "", 4, 1);

	return $periodo;
}

function getIdActividad($codigo) {
	global $conn;

	if ($codigo == "")
		return NULL;
	else {
		$params = array(":codigo" => intval($codigo));
		$sql =
			"SELECT ac_id
				 FROM cac_actividad
				WHERE ac_codigo = TO_NUMBER(:codigo)
					AND ac_fechabaja IS NULL";
		return ValorSql($sql, 0, $params, 0);
	}
}

function mostrarAumento($tope) {
?>
<script src="/js/validations.js" type="text/javascript"></script>
<script src="/modules/solicitud_cotizacion/js/cotizacion.js" type="text/javascript"></script>
<script type="text/javascript">
	with (window.parent.document) {
		mostrarBotonGuardar(window.parent.document);

		// Inhabilito el ciiu1, la masa salarial y la cantidad de trabajadores, porque ya presionó el botón "Obtener Cotización"..
		getElementById('btnGuardar').className = 'btnGuardar';
		getElementById('ciiu1').readOnly = true;
		getElementById('ciiu1Buscar').style.display = 'none';
		getElementById('masaSalarial1').readOnly = true;
		getElementById('masaSalarial2').readOnly = true;
		getElementById('masaSalarial3').readOnly = true;
		getElementById('totalTrabajadores1').readOnly = true;
		getElementById('totalTrabajadores2').readOnly = true;
		getElementById('totalTrabajadores3').readOnly = true;

		// Muestro los objetos relacionados con el aumento..
		getElementById('btnGuardar').className = 'btnGuardar';
		getElementById('spanTopeAumento').innerHTML = '<?= $tope?>';
		getElementById('tableAumento').style.display = 'block';
		getElementById('tableAumento2').style.display = 'block';
		getElementById('tableValoresFinales').style.display = 'block';
		getElementById('topeAumento').value = '<?= $tope?>';
		getElementById('aumentoValor').select();
		getElementById('aumentoValor').focus();
	}

	// Calculo los valores..
	calcularAumento(window.parent.document);
</script>
<?
}

function mostrarAumentoYDescuento($tope) {
	$tope = explode("_", $tope);
?>
<script src="/js/validations.js" type="text/javascript"></script>
<script src="/modules/solicitud_cotizacion/js/cotizacion.js" type="text/javascript"></script>
<script type="text/javascript">
	with (window.parent.document) {
		mostrarBotonGuardar(window.parent.document);

		// Inhabilito el ciiu1, la masa salarial y la cantidad de trabajadores, porque ya presionó el botón "Obtener Cotización"..
		getElementById('btnGuardar').className = 'btnGuardar';
		getElementById('ciiu1').readOnly = true;
		getElementById('ciiu1Buscar').style.display = 'none';
		getElementById('masaSalarial1').readOnly = true;
		getElementById('masaSalarial2').readOnly = true;
		getElementById('masaSalarial3').readOnly = true;
		getElementById('totalTrabajadores1').readOnly = true;
		getElementById('totalTrabajadores2').readOnly = true;
		getElementById('totalTrabajadores3').readOnly = true;

		getElementById('btnGuardar').className = 'btnGuardar';
		getElementById('tableValoresFinales').style.display = 'block';

		// Muestro los objetos relacionados con el aumento..
		getElementById('spanTopeAumento').innerHTML = '<?= $tope[1]?>';
		getElementById('tableAumento').style.display = 'block';
		getElementById('tableAumento2').style.display = 'block';
		getElementById('topeAumento').value = '<?= $tope[1]?>';
		getElementById('aumentoValor').select();
		getElementById('aumentoValor').focus();

		// Muestro los objetos relacionados con el descuento..
		getElementById('spanTopeDescuento').innerHTML = '<?= $tope[0]?>';
		getElementById('tableDescuento').style.display = 'block';
		getElementById('tableDescuento2').style.display = 'block';
		getElementById('topeDescuento').value = '<?= $tope[0]?>';
		getElementById('descuentoValor').select();
		getElementById('descuentoValor').focus();
	}

	// Calculo los valores..
	calcularAumento(window.parent.document);
	calcularDescuento(window.parent.document);
</script>
<?
}

function mostrarDescuento($tope) {
?>
<script src="/js/validations.js" type="text/javascript"></script>
<script src="/modules/solicitud_cotizacion/js/cotizacion.js" type="text/javascript"></script>
<script type="text/javascript">
	with (window.parent.document) {
		mostrarBotonGuardar(window.parent.document);

		// Inhabilito el ciiu1, la masa salarial y la cantidad de trabajadores, porque ya presionó el botón "Obtener Cotización"..
		getElementById('btnGuardar').className = 'btnGuardar';
		getElementById('ciiu1').readOnly = true;
		getElementById('ciiu1Buscar').style.display = 'none';
		getElementById('masaSalarial1').readOnly = true;
		getElementById('masaSalarial2').readOnly = true;
		getElementById('masaSalarial3').readOnly = true;
		getElementById('totalTrabajadores1').readOnly = true;
		getElementById('totalTrabajadores2').readOnly = true;
		getElementById('totalTrabajadores3').readOnly = true;

		// Muestro los objetos relacionados con el descuento..
		getElementById('btnGuardar').className = 'btnGuardar';
		getElementById('spanTopeDescuento').innerHTML = '<?= $tope?>';
		getElementById('tableDescuento').style.display = 'block';
		getElementById('tableDescuento2').style.display = 'block';
		getElementById('tableValoresFinales').style.display = 'block';
		getElementById('topeDescuento').value = '<?= $tope?>';
		getElementById('descuentoValor').select();
		getElementById('descuentoValor').focus();
	}

	// Calculo los valores..
	calcularDescuento(window.parent.document);
</script>
<?
}

function setCampanaF931() {
	global $conn;

	$curs = null;
	$params = array(":naumento" => 0,
									":ncanttrabajador" => intval($_POST["totalTrabajadores"]),
									":ndescuento" => 0,
									":nidciiu" => nullIfCero(getIdActividad($_POST["ciiu1"])),
									":nmasasalarial" => floatval($_POST["masaSalarialSinSac"]));
	$sql = "BEGIN webart.get_valor_online(:nidciiu, :nmasasalarial, :ncanttrabajador, :ndescuento, :naumento, :data); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params, true, 0);
	$row = DBGetSP($curs);
	if (floatval($row["SUMAFIJA"]) < 0.6)
		$row["SUMAFIJA"] = 0.6;

	$curs2 = null;
	$params2 = array(":naumento" => 121,
									 ":ncanttrabajador" => intval($_POST["totalTrabajadores"]),
									 ":ndescuento" => 0,
									 ":nidciiu" => nullIfCero(getIdActividad($_POST["ciiu1"])),
									 ":nmasasalarial" => floatval($_POST["masaSalarialSinSac"]));
	$sql2 = "BEGIN webart.get_valor_online(:nidciiu, :nmasasalarial, :ncanttrabajador, :ndescuento, :naumento, :data); END;";
	$stmt2 = DBExecSP($conn, $curs2, $sql2, $params2, true, 0);
	$row2 = DBGetSP($curs2);

	// Traigo los límites de la SSN..
	$params3 = array(":idactividad" => getIdActividad($_POST["ciiu1"]));
	$sql3 =
		"SELECT ls_minimo minimo, ls_maximo maximo
			 FROM als_limitessn
			WHERE ls_idactividad IN(SELECT ac_id
																FROM cac_actividad
															 WHERE ac_codigo = SUBSTR(art.hys.get_codactividadrevdos(:idactividad), 1, 1))
				AND ls_fechadesde <= SYSDATE
				AND ls_fechahasta >= SYSDATE
				AND ls_fechabaja IS NULL";
	$stmt3 = DBExecSql($conn, $sql3, $params3, OCI_DEFAULT);
	$row3 = DBGetQuery($stmt3);
	if ($row3["MINIMO"] == "")
		$row3["MINIMO"] = 0;
	if ($row3["MAXIMO"] == "")
		$row3["MAXIMO"] = 0;

	$params = array("identidad" => $_SESSION["entidad"], "idSucursal" => $_SESSION["sucursal"]);
	$sql =
		"SELECT ca_descuento
			 FROM afi.aca_campania931
			WHERE ca_identidad = :identidad
				AND SYSDATE < ca_fechahasta
				AND ca_fechabaja IS NULL
				AND NOT EXISTS (SELECT 1
													FROM comercial.cse_sucursalexcluidof931
												 WHERE se_identidad = ca_identidad
													 AND se_idsucursal = :idSucursal)";
?>
<script src="/js/validations.js" type="text/javascript"></script>
<script src="/modules/solicitud_cotizacion/js/cotizacion.js" type="text/javascript"></script>
<script type="text/javascript">
	alert('Por favor, indique la Alícuota FINAL.');

	// Deshabilito todos los campos, porque ya presionó el botón "Obtener Cotización"..
	window.parent.lockControls(true, true);

	with (window.parent.document) {
		mostrarBotonGuardar(window.parent.document);

		var minimoSSN = <?= $row3["MINIMO"]?>;
		var maximoSSN = <?= $row3["MAXIMO"]?>;

		// Muestro los objetos relacionados con la campaña F931..
		getElementById('campanaF931').value = 'N';
		getElementById('spanAlicuotaF931').innerHTML = 'Alícuota F931 <?= ValorSql($sql, "", $params, 0)?>% Descuento';

		getElementById('porcVarTarifario').value = (<?= $row["PORCVARIABLE"]?>).toFixed(3);
		getElementById('porcVarF931').value = <?= ((floatval($_POST["calculoVariable"]) * (floatval($_POST["masaSalarialSinSac"]) / intval($_POST["totalTrabajadores"]))) + (floatval($_POST["calculoSumaFija"]) - 0.6)) / (floatval($_POST["masaSalarialSinSac"]) / intval($_POST["totalTrabajadores"]))?>;
		getElementById('porcVarF931').value = (getElementById('porcVarF931').value * (1 - (<?= ValorSql($sql, "0", $params, 0)?> / 100))).toFixed(3);
		if (Number(getElementById('porcVarTarifario').value * 0.3) > Number(getElementById('porcVarF931').value))
			getElementById('porcVarF931').value = Number(getElementById('porcVarTarifario').value * 0.3).toFixed(3);

		// No dejo que el porcentaje variable del F931 pase los límites de la SSN..
		if ((minimoSSN == 0) && (maximoSSN == 0))
			throw new Error('No se encontraron los límites ante la SSN.');

		if (Number(getElementById('porcVarF931').value) < minimoSSN)
			getElementById('porcVarF931').value = minimoSSN;

		if (Number(getElementById('porcVarF931').value) > maximoSSN)
			getElementById('porcVarF931').value = maximoSSN;


		getElementById('porcVarDescuento').value = (<?= $row["PORCVARIABLE"]?> * (1 - (25 / 100))).toFixed(3);

//		getElementById('porcVarAumento').value = (<?= $row["PORCVARIABLE"]?> * (1 + (80 / 100))).toFixed(3);
		getElementById('porcVarAumento').value = (<?= $row2["PORCVARIABLE"]?>).toFixed(3);

		getElementById('costoFinalTarifario').value = (<?= $row["COSTOCAPITAS"]?>).toFixed(2);
		getElementById('costoMensualTarifario').value = (<?= $row["COSTOMENSUAL"]?>).toFixed(0);
		getElementById('costoAnualTarifario').value = (<?= $row["COSTOANUAL"]?>).toFixed(0);
		getElementById('costoFinalF931').value = ((getElementById('porcVarF931').value /100) * <?= floatval($_POST["masaSalarialSinSac"])?> / <?= intval($_POST["totalTrabajadores"])?> + 0.6).toFixed(2);
		getElementById('costoMensualF931').value = (((getElementById('porcVarF931').value / 100) * <?= floatval($_POST["masaSalarialSinSac"])?>) + 0.6 * <?= intval($_POST["totalTrabajadores"])?>).toFixed(0);
		getElementById('costoAnualF931').value = (((getElementById('porcVarF931').value / 100) * <?= floatval($_POST["masaSalarialSinSac"])?> * 13) + 0.6 * <?= intval($_POST["totalTrabajadores"])?> * 12).toFixed(0);
		getElementById('costoFinalDescuento').value = ((getElementById('porcVarDescuento').value /100) * <?= floatval($_POST["masaSalarialSinSac"])?> / <?= intval($_POST["totalTrabajadores"])?>  + 0.6).toFixed(2);
		getElementById('costoMensualDescuento').value = (((getElementById('porcVarDescuento').value / 100) * <?= floatval($_POST["masaSalarialSinSac"])?>) + 0.6 * <?= intval($_POST["totalTrabajadores"])?>).toFixed(0);
		getElementById('costoAnualDescuento').value = (((getElementById('porcVarDescuento').value / 100) * <?= floatval($_POST["masaSalarialSinSac"])?> * 13) + 0.6 * <?= intval($_POST["totalTrabajadores"])?> * 12).toFixed(0);
		getElementById('costoFinalAumento').value = ((getElementById('porcVarAumento').value / 100) * <?= floatval($_POST["masaSalarialSinSac"])?> / <?= intval($_POST["totalTrabajadores"])?> + 0.6).toFixed(2);
		getElementById('costoMensualAumento').value = (((getElementById('porcVarAumento').value / 100) * <?= floatval($_POST["masaSalarialSinSac"])?>) + 0.6 * <?= intval($_POST["totalTrabajadores"])?>).toFixed(0);
		getElementById('costoAnualAumento').value = (((getElementById('porcVarAumento').value / 100) * <?= floatval($_POST["masaSalarialSinSac"])?> * 13) + 0.6 * <?= intval($_POST["totalTrabajadores"])?> * 12).toFixed(0);
<?
	$params = array(":canttrabajador" => intval($_POST["totalTrabajadores"]),
									":cuit" => $_POST["cuit"],
									":idactividad" => nullIfCero(getIdActividad($_POST["ciiu1"])),
									":usuario" => "W_".$_SESSION["usuario"]);
	$sql =
		"SELECT cotizacion.get_descuento(:cuit, :canttrabajador, :idactividad, :usuario)
			 FROM DUAL";
?>
		getElementById('descuentoTopeF931').value = <?= ValorSql($sql, 0, $params, 0)?>;
		getElementById('spanAlicuotaDescuento').innerHTML = 'Alícuota ' + getElementById('descuentoTopeF931').value + '% Descuento';

		if (getElementById('descuentoTopeF931').value > 0)
			getElementById('trDescuento').style.display = 'block';
		else
			getElementById('trDescuento').style.display = 'none';
<?
	$params = array(":emplecoti" => intval($_POST["totalTrabajadores"]),
									":idactividad" => nullIfCero(getIdActividad($_POST["ciiu1"])),
									":masacoti" => floatval($_POST["masaSalarialSinSac"]),
									":porcvariable" => floatval($row["PORCVARIABLE"]),
									":sumafija" => floatval($row["SUMAFIJA"]),
									":usuario" => "W_".$_SESSION["usuario"]);
	$sql =
		"SELECT art.cotizacion.get_aumento(:idactividad, :masacoti, :emplecoti, :sumafija, :porcvariable, :usuario)
			 FROM DUAL";
?>
		getElementById('aumentoTopeF931').value = <?= ValorSql($sql, 0, $params, 0)?>;
//		getElementById('spanAlicuotaAumento').innerHTML = 'Alícuota ' + getElementById('aumentoTopeF931').value + '% Aumento';

		if (getElementById('aumentoTopeF931').value > 0)
			getElementById('trAumento').style.display = 'block';
		else
			getElementById('trAumento').style.display = 'none';

		getElementById('spanTopesAlicuotaFinal').innerHTML = '(Entre ' + getTopeMinimoF931() + '% y ' + getTopeMaximoF931() + '%)';
		getElementById('sumaFijaF931').value = (<?= $row["SUMAFIJA"]?>).toFixed(2);

//		edSumaAseguradaRCF931.Value    := 0;
//		edAlicuotaVariableRCF931.Value := 0;
//		edMasaSalarialRCF931.Value     := floatval($_POST["masaSalarialSinSac"]);
//		edCuotaInicialRCF931.Value     := 0;

		getElementById('tableCampanaF931').style.display = 'block';

		getElementById('aumento').value = 0;
		getElementById('descuento').value = 0;

		getElementById('alicuotaFinalF931').focus();
	}
</script>
<?
}


$back = false;
try {
	SetDateFormatOracle("DD/MM/YYYY");

	validarSesion(isset($_SESSION["isAgenteComercial"]));

	$_POST["razonSocial"] = substr($_POST["razonSocial"], 0, 60);

	if (!isset($_POST["suscribePolizaRC"]))
		$_POST["suscribePolizaRC"] = "N";
	if (!isset($_POST["observaciones"]))
		$_POST["observaciones"] = "";

	if ($_POST["id"] == "") {		// Es un alta..
		if (!$_SESSION["altaCotizaciones"])
			throw new Exception("Usted no tiene permiso para dar de alta solicitudes de cotización.");

		if ($_POST["statusSrtTmp"] < 1)
			throw new Exception("Por favor ingrese el Status ante la SRT.");

		$codigoVendedor = NULL;
		if (isset($_POST["codigoVendedor"])) {
			// Valido que el código de vendedor ingresado exista..
			$params = array(":identidad" => $_SESSION["entidad"], ":vendedor" => $_POST["codigoVendedor"]);
			$sql =
				"SELECT 1
					 FROM xve_vendedor, xev_entidadvendedor
					WHERE ve_id = ev_idvendedor
						AND ev_identidad = :identidad
						AND ve_vendedor = :vendedor
						AND ev_fechabaja IS NULL
						AND ve_fechabaja IS NULL";
			if (!ExisteSql($sql, $params, 0))
				throw new Exception("El código de vendedor es inválido.");
			$codigoVendedor = IIF(($_POST["codigoVendedor"] == ""), 0, $_POST["codigoVendedor"]);
		}

		$idVendedor = NULL;
		if ($codigoVendedor != NULL) {
			$params = array("entidad" => $_SESSION["entidad"], ":vendedor" => $codigoVendedor);
			$sql =
				"SELECT MAX(ve_id)
					 FROM xve_vendedor, xev_entidadvendedor
					WHERE ve_id = ev_idvendedor
						AND ev_identidad = :entidad
						AND ve_vendedor = :vendedor
						AND ev_fechabaja IS NULL
						AND ve_fechabaja IS NULL";
			$idVendedor = ValorSql($sql, "", $params, 0);
		}
		elseif ($_SESSION["vendedor"] != "")
			$idVendedor = $_SESSION["vendedor"];

		// Hago esto para que tenga los mismos valores que si se cargara la solicitud desde Delphi..
		if ($_POST["statusBcra"] == -1)
			$_POST["statusBcra"] = -99;
		if ($_POST["statusBcra"] == 0)
			$_POST["statusBcra"] = -1;

		$prestacionesEspeciales = "N";
		if (isset($_POST["prestacionesEspeciales"])) {
			$prestacionesEspeciales = "S";
			$_POST["observaciones"] = "Cotizar con Otras Erogaciones, pasar a Comite II.  ".$_POST["observaciones"];
		}

		$sumaAseguradaRC = NULL;
		if (isset($_POST["sumaAseguradaRC"]))
			$sumaAseguradaRC = $_POST["sumaAseguradaRC"];

		$curs = null;
		$params = array(":cbajapordeuda" => $_POST["bajaPorDeuda"],
										":ccampanaF931" => $_POST["campanaF931"],
										":cdatoscompetencia" => $_POST["rDatosCompetencia"],
										":cprestacionesespeciales" => $prestacionesEspeciales,
										":csuscribepolizarc" => $_POST["suscribePolizaRC"],
										":naumento" => floatval($_POST["aumento"]),
										":ncantidadtrabajadores" => intval($_POST["totalTrabajadores"]),
										":ndescuento" => floatval($_POST["descuento"]),
										":nedadpromedio" => IIF(($_POST["edadPromedio"] == ""), -1, intval($_POST["edadPromedio"])),
										":nestablecimientos" => intval($_POST["cantidadEstablecimientos"]),
										":nidartanterior" => nullIsEmpty($_POST["artTmp"]),
										":nidcanal" => $_SESSION["canal"],
										":nidciiu" => nullIfCero(getIdActividad($_POST["ciiu1"])),
										":nidciiu2" => nullIfCero(getIdActividad($_POST["ciiu2"])),
										":nidciiu3" => nullIfCero(getIdActividad($_POST["ciiu3"])),
										":nidentidad" => $_SESSION["entidad"],
										":nidholding" => nullIfCero($_POST["idHolding"]),
										":nidsector" => nullIfCero($_POST["sector"]),
										":nidstatusbcra" => nullIsEmpty($_POST["statusBcra"]),
										":nidstatussrt" => nullIsEmpty($_POST["statusSrtTmp"]),
										":nidvendedor" => $idVendedor,
										":nidzonageografica" => $_POST["zonaGeografica"],
										":nmasasalarial" => floatval($_POST["masaSalarialSinSac"]),
										":nresultadomensualtrabajador" => round(floatval($_POST["resultadoMensualPorTrabajador"]), 4),
										":nsumaaseguradarc" => $sumaAseguradaRC,
										":sactividadreal" => $_POST["actividadReal"],
										":scontacto" => $_POST["contacto"],
										":scuit" => $_POST["cuit"],
										":semail" => $_POST["email"],
										":speriodo" => formatPeriodo($_POST["periodo"]),
										":srazonsocial" => $_POST["razonSocial"],
										":susualta" => $_SESSION["usuario"]);
		$sql = "BEGIN webart.get_validacion_solicitud(:data, :cbajapordeuda, :ccampanaF931, :cdatoscompetencia, :cprestacionesespeciales, :csuscribepolizarc, :naumento, :ncantidadtrabajadores, :ndescuento, :nedadpromedio, :nestablecimientos, :nidartanterior, :nidcanal, :nidciiu, :nidciiu2, :nidciiu3, :nidentidad, :nidholding, :nidsector, :nidstatusbcra, :nidstatussrt, :nidvendedor, :nidzonageografica, :nmasasalarial, :nresultadomensualtrabajador, :nsumaaseguradarc, :sactividadreal, :scontacto, :scuit, :semail, :speriodo, :srazonsocial, :susualta); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params);
		$row = DBGetSP($curs);

		$motivoNoAutoCotizacion = $row["MOTIVONOAUTOCOTIZACION"];
		$verificaTecnica = $row["VERIFICATECNICA"];
		if ($_SESSION["autoCotizacion"] == 0)
			$verificaTecnica = "S";

		if (trim($row["ADVERTENCIA"]) != "")
			echo "<script type='text/javascript'>alert(unescape('".rawurlencode($row["ERROR"])."'));</script>";
		elseif ($row["NUMEROERROR"] == -3) {		// Si el error es -3 indica que se tienen que mostrar los datos de la campaña F931 del año 2012..
			setCampanaF931();
			exit;
		}
		elseif ($row["NUMEROERROR"] == -1) {		// Si el error es -1 indica que se le tiene que permitir al usuario cargar un descuento..
			mostrarDescuento($row["ERROR"]);
			exit;
		}
		elseif ($row["NUMEROERROR"] == -2) {		// Si el error es -2 indica que se le tiene que permitir al usuario cargar un aumento..
			mostrarAumento($row["ERROR"]);
			exit;
		}
		elseif ($row["NUMEROERROR"] == -12) {		// Si el error es -12 indica que se le tiene que permitir al usuario cargar un aumento y un descuento..
			mostrarAumentoYDescuento($row["ERROR"]);
			exit;
		}
		elseif (intval($row["NUMEROERROR"]) != "0") {
			if (($row["NUMEROERROR"] >= 1) and ($row["NUMEROERROR"] <= 13)) {		// Son los números de error de la vieja función get_validacion..
				$back = true;
				enviarEmailSituacionAfiliatoria($row["NUMEROERROR"]." - ".$row["ERROR"]);
			}
			throw new Exception($row["ERROR"]." (Nº Error ".$row["NUMEROERROR"].")");
		}


		// Determino si el pedido es una solicitud de cotización o una revisión de precio..
		$esRevision = ((($_POST["statusSrtTmp"] == 5) or ($_POST["statusSrtTmp"] == 6) or ($_POST["statusSrtTmp"] == 7)) and ($_POST["artTmp"] == 51));

		if ($esRevision)
			$tipoSolicitud = "R";
		else
			$tipoSolicitud = "C";

		$huboErrores = "N";
		if ((intval($row["NUMEROERROR"]) != 0) and ($row["ADVERTENCIA"] != "A"))
			$huboErrores = "S";

		$curs = null;
		$params = array(":actividadreal" => $_POST["actividadReal"],
										":art" => nullIfCero($_POST["artTmp"]),
										":autocotizacion" => $_SESSION["autoCotizacion"],
										":bajapordeuda" => $_POST["bajaPorDeuda"],
										":calculosumafija" => floatval($_POST["calculoSumaFija"]),
										":calculovariable" => floatval($_POST["calculoVariable"]),
										":canal" => $_SESSION["canal"],
										":chuboerrores" => $huboErrores,
										":ciiu1" => getIdActividad($_POST["ciiu1"]),
										":ciiu2" => getIdActividad($_POST["ciiu2"]),
										":ciiu3" => getIdActividad($_POST["ciiu3"]),
										":contacto" => $_POST["contacto"],
										":costofijoform931" => floatval($_POST["formulario931CostoFijo"]),
										":costovariableform931" => floatval($_POST["formulario931CostoVariable"]),
										":cprestacionesespeciales" => $prestacionesEspeciales,
										":cstatussrtautomatico" => $_POST["statusSrtAutomatico"],
										":csuscribepolizarc" => $_POST["suscribePolizaRC"],
										":ctiposolicitud" => $tipoSolicitud,
										":cuit" => $_POST["cuit"],
										":cverificatecnica" => $verificaTecnica,
										":datoscompetencia" => $_POST["rDatosCompetencia"],
										":edadpromedio" => intval($_POST["edadPromedio"]),
										":email" => $_POST["email"],
										":entidad" => $_SESSION["entidad"],
										":establecimientos" => intval($_POST["cantidadEstablecimientos"]),
										":holding" => nullIfCero($_POST["idHolding"]),
										":idusuario" => $_SESSION["idUsuario"],
										":masasalarial1" => floatval($_POST["masaSalarial1"]),
										":masasalarial2" => floatval($_POST["masaSalarial2"]),
										":masasalarial3" => floatval($_POST["masaSalarial3"]),
										":masasalarialsinsac" => floatval($_POST["masaSalarialSinSac"]),
										":naumento" => zeroIfEmpty($_POST["aumento"]),
										":naumentotope" => nullIfCero($_POST["topeAumento"]),
										":ndescuento" => zeroIfEmpty($_POST["descuento"]),
										":ndescuentotope" => nullIfCero($_POST["topeDescuento"]),
										":nidzonageografica" => $_POST["zonaGeografica"],
										":nsumaaseguradarc" => $_POST["sumaAseguradaRC"],
										":observaciones" => substr($_POST["observaciones"], 0, 2048),
										":periodo" => formatPeriodo($_POST["periodo"]),
										":razonsocial" => $_POST["razonSocial"],
										":resultadomensualportrabajador" => floatval($_POST["resultadoMensualPorTrabajador"]),
										":sector" => nullIfCero($_POST["sector"]),
										":smotivonoautocotizacion" => $motivoNoAutoCotizacion,
										":solopagototalmensual" => floatval($_POST["soloPagoTotalMensual"]),
										":statusbcra" => $_POST["statusBcra"],
										":statussrt" => nullIsEmpty($_POST["statusSrtTmp"]),
										":sucursal" => nullIfCero($_SESSION["sucursal"]),
										":sumafijacompetencia" => floatval($_POST["alicuotaCompetenciaSumaFija"]),
										":telefono" => $_POST["telefono"],
										":totaltrabajadores" => intval($_POST["totalTrabajadores"]),
										":totaltrabajadores1" => intval($_POST["totalTrabajadores1"]),
										":totaltrabajadores2" => intval($_POST["totalTrabajadores2"]),
										":totaltrabajadores3" => intval($_POST["totalTrabajadores3"]),
										":usuario" => $_SESSION["usuario"],
										":variablecompetencia" => floatval($_POST["alicuotaCompetenciaVariable"]),
										":vendedor" => $idVendedor);
		$sql = "BEGIN webart.set_solicitud_cotizacion(:chuboerrores, :cprestacionesespeciales, :csuscribepolizarc, :cstatussrtautomatico, :ctiposolicitud, :cverificatecnica, :ciiu1, :ciiu2, :ciiu3, :sumafijacompetencia, :variablecompetencia, :naumento, :naumentotope, :calculosumafija, :calculovariable, :totaltrabajadores, :ndescuento, :ndescuentotope, :establecimientos, :entidad, :costofijoform931, :costovariableform931, :art, :holding, :nidzonageografica, :masasalarialsinsac, :resultadomensualportrabajador, :solopagototalmensual, :totaltrabajadores1, :totaltrabajadores2, :totaltrabajadores3, :edadpromedio, :masasalarial1, :masasalarial2, :masasalarial3, :nsumaaseguradarc, :autocotizacion, :idusuario, :canal, :sucursal, :vendedor, :bajapordeuda, :cuit, :smotivonoautocotizacion, :razonsocial, :sector, :actividadreal, :contacto, :email, :observaciones, :periodo, :telefono, :statusbcra, :statussrt, :datoscompetencia, :usuario); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params);
		$row = DBGetSP($curs);

		if (!$esRevision) {
			$params = array(":cuit" => $_POST["cuit"]);
			$sql =
				"SELECT sc_id, sc_nrosolicitud
					 FROM asc_solicitudcotizacion
					WHERE sc_cuit = :cuit
			 ORDER BY 1 DESC";
			$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			$row = DBGetQuery($stmt);

			$id = $row["SC_ID"];
			$nroSol = $row["SC_NROSOLICITUD"];


			// Guardo los datos de la campaña F931..
			if ($_POST["campanaF931"] == "N") {
				$params = array(":finalporcmasa" => floatval($_POST["alicuotaFinalF931"]),
												":finalportrabajador" => ((floatval($_POST["alicuotaFinalF931"]) /100) * floatval($_POST["masaSalarialSinSac"]) / intval($_POST["totalTrabajadores"]) + 0.6),
												":finalsumafija" => floatval($_POST["sumaFijaF931"]),
												":id" => $id);
				$sql =
					"UPDATE asc_solicitudcotizacion
							SET sc_campania931 = 'S',
									sc_finalsumafija = :finalsumafija,
									sc_finalporcmasa = :finalporcmasa,
									sc_finalportrabajador = :finalportrabajador,
									sc_porcaumento = NULL,
									sc_porcaumentotope = NULL,
									sc_porcdescuento = NULL,
									sc_porcdescuentotope = NULL
						WHERE sc_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				actualizarRankingBNA($id, 0);
			}


			$blobParamName = "the_clob";
			$fileName = IMAGENES_STATUS_BCRA.$_POST["cuit"]."\\".$_POST["cuit"].".html";
			if (file_exists($fileName)) {
				$sql =
					"UPDATE asc_solicitudcotizacion
							SET sc_htmlbcra = EMPTY_BLOB()
					  WHERE sc_id = ".$id."
				RETURNING sc_htmlbcra INTO :".$blobParamName;
				DBSaveLob($conn, $sql, $blobParamName, getFileContent($fileName), OCI_B_BLOB);
				actualizarRankingBNA($id, 0);

				// Borro los archivos temporales..
				unlink($fileName);
			}

			$params = array(":cuit" => $_POST["cuit"]);
			$sql =
				"SELECT os_nombreimagen
					 FROM web.wos_obtenerstatusbcra
					WHERE os_cuit = :cuit";
			$fileName = IMAGENES_STATUS_BCRA.$_POST["cuit"]."\\".ValorSql($sql, "", $params, 0);
			if (file_exists($fileName))
				unlink($fileName);
		
			$params = array(":id" => $id);
			$sql =
				"SELECT sc_finalportrabajador
					 FROM asc_solicitudcotizacion
					WHERE sc_id = :id";
			$autoCotizacion = (ValorSql($sql, "", $params, 0) != "");
		
			$modulo = "C";
			$txtRevision = " ";
		}
		else {
			$params = array(":cuit" => $_POST["cuit"]);
			$sql =
				"SELECT sr_id, sr_nrosolicitud
					 FROM asr_solicitudreafiliacion
					WHERE sr_cuit = :cuit
		 ORDER BY 1 DESC";
			$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			$row = DBGetQuery($stmt);

			$id = $row["SR_ID"];
			$nroSol = $row["SR_NROSOLICITUD"];

			$autoCotizacion = ($_SESSION["autoCotizacion"] == 1);
			$modulo = "R";
			$txtRevision = " (Revisión de precio) ";
		}
	}
	else {		// Si entra por acá es porque se está modificando un aumento o descuento NADA MAS..
		// Determino si el pedido es una solicitud de cotización o una revisión de precio..
		$esRevision = ((($_POST["statusSrtTmp"] == 5) or ($_POST["statusSrtTmp"] == 6) or ($_POST["statusSrtTmp"] == 7)) and ($_POST["artTmp"] == 51));

		$autoCotizacion = true;
		$id = $_POST["id"];
		$modulo = "C";
		$nroSol = ValorSql("SELECT sc_nrosolicitud FROM asc_solicitudcotizacion WHERE sc_id = :id", "", array(":id" => $id), 0);
		$tipoSolicitud = ($esRevision)?"R":"C";
		$txtRevision = " ";

		if (!$esRevision) {
			// Traigo el porcentaje y la masa con el descuento o aumento..
			$curs = null;
			$params = array(":naumento" => zeroIfEmpty(floatval($_POST["aumentoValor"])),
											":ncanttrabajador" => intval($_POST["totalTrabajadores"]),
											":ndescuento" => zeroIfEmpty(floatval($_POST["descuentoValor"])),
											":nidciiu" => nullIfCero(getIdActividad($_POST["ciiu1"])),
											":nmasasalarial" => floatval($_POST["masaSalarialSinSac"]));
			$sql = "BEGIN webart.get_valor_online(:nidciiu, :nmasasalarial, :ncanttrabajador, :ndescuento, :naumento, :data); END;";
			$stmt = DBExecSP($conn, $curs, $sql, $params, true, 0);
			$row = DBGetSP($curs);

			// Actualizo la solicitud de cotización..
			$params = array(":finalporcmasa" => $row["PORCVARIABLE"],
											":finalportrabajador" => $row["COSTOCAPITAS"],
											":id" => $id,
											":porcaumento" => $_POST["aumentoValor"],
											":porcaumentotope" => $_POST["topeAumento"],
											":porcdescuento" => $_POST["descuentoValor"],
											":porcdescuentotope" => $_POST["topeDescuento"]);
			$sql =
				"UPDATE asc_solicitudcotizacion
						SET sc_finalporcmasa = :finalporcmasa,
								sc_finalportrabajador = :finalportrabajador,
								sc_porcaumento = :porcaumento,
								sc_porcaumentotope = :porcaumentotope,
								sc_porcdescuento = :porcdescuento,
								sc_porcdescuentotope = :porcdescuentotope
				  WHERE sc_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			actualizarRankingBNA($id, 0);
		}
	}


	// Preparo el envío del e-mail..
	$params = array(":idsolicitud" => $id, ":tipo" => $tipoSolicitud);
	$sql = "SELECT art.cotizacion.get_mailnotificacomercial(:tipo, :idsolicitud) FROM DUAL";
	$emailTo = ValorSql($sql, "", $params, 0);
	if ($emailTo == "") {
		$emailTo = "evila@provart.com.ar";
		$subject = "[Error] - Cotización WEB Nº ".$nroSol.$txtRevision;
	}
	else {
		if ($autoCotizacion)
			$subject = "Cotización WEB Nº ".$nroSol.$txtRevision;
		elseif (($_POST["statusSrtTmp"] == 6) or ($_POST["statusSrtTmp"] == 7) or ($_POST["statusBcra"] == 4) or ($_POST["statusBcra"] == 5) or ($_POST["statusBcra"] == 6))
			$subject = "Revisar solicitud de cotización Nº ".$nroSol.$txtRevision;
		else
			$subject = "Aviso: Solicitud de cotización Nº ".$nroSol.$txtRevision." pasa a Suscripción";
	}

	$params = array(":id" => $_SESSION["canal"]);
	$sql = "SELECT ca_codigo || ' - ' || ca_descripcion FROM aca_canal WHERE ca_id = :id";
	$canal = ValorSql($sql, "", $params, 0);

	$params = array(":id" => $_SESSION["entidad"]);
	$sql = "SELECT en_codbanco || ' - ' || en_nombre FROM xen_entidad WHERE en_id = :id";
	$entidad = ValorSql($sql, "", $params, 0);

	if ($autoCotizacion) {
		$body = "<html><body><p>Se ha cargado una cotización desde la Web".$txtRevision."</p>";
		$body.= "<p>Nº de Solicitud: <b>".$nroSol."</b></p>";
		$body.= "<p>Canal: <b>".$canal."</b></p>";
		$body.= "<p>Entidad: <b>".$entidad."</b></p>";
		$body.= "<p>e-Mail de contacto: <b>".$_SESSION["email"]."</b></p>";
		$body.= "</body></html>";
	}
	else {
		$body = "<html><body><p>Tiene una solicitud de cotización".$txtRevision."del Canal ".$canal.", Entidad ".$entidad.", usuario: ".$_SESSION["usuario"]."</p>";

		if (($_POST["statusSrtTmp"] == 6) or ($_POST["statusSrtTmp"] == 7) or ($_POST["statusBcra"] == 4) or ($_POST["statusBcra"] == 5) or ($_POST["statusBcra"] == 6))
			$body.= "<p>Para ser revisada.</p>";
		else
			$body.= "<p>Para ser revisada directamente por Suscripción.</p>";

		$body.= "</body></html>";
	}

	SendEmail($body, "Web", $subject, array($emailTo), array(), array(), "H", (($esRevision)?"ASR":"ASC"), $id, $_SESSION["email"]);
	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script src='/modules/solicitud_cotizacion/js/cotizacion.js' type='text/javascript'></script>";
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));";
	echo "mostrarBotonGuardar(window.parent.document);";
	if ($back)
		echo "window.parent.location.href = '".$_REQUEST["paginaAnterior"]."';";
	echo "</script>";
	exit;
}
?>
<script type="text/javascript">
<?
if ($esRevision)
	$sql = "SELECT NULL FROM DUAL";
else
	$sql =
		"SELECT sc_finalportrabajador
			 FROM asc_solicitudcotizacion
			WHERE sc_id = ".$id;

if (ValorSql($sql) == "") {
?>
	window.parent.location.href = '/index.php?pageid=27&buscar=yes&numero=<?= $nroSol?>&i=k';		// insert=ok..
<?
}
else {
?>
	window.parent.location.href = '/index.php?pageid=28&id=<?= $modulo.$id?>&i=k';		// insert=ok..
<?
}
?>
</script>