<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	$errores = false;

	echo '<script src="/modules/usuarios_registrados/clientes/js/denuncia_siniestros.js" type="text/javascript"></script>';
	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["idTrabajador"] == -1) {
		echo "paso = 1;";
		echo "errores+= '- Apellido y Nombre vacío.<br />';";
		$errores = true;
	}

	if (!isFechaValida($_POST["fechaNacimiento"], false)) {
		if (!$errores)
			echo "paso = 1;";
		echo "errores+= '- Fecha Nacimiento vacía o errónea.<br />';";
		$errores = true;
	}
	else {
		$edad = dateDiff($_POST["fechaNacimiento"], date("d/m/Y"), "A");
		if (($edad <= 16) or ($edad >= 90)) {
			if (!$errores)
				echo "paso = 1;";
			echo "errores+= '- La edad del trabajador debe estar entre 16 y 90 años.<br />';";
			$errores = true;
		}
	}

	if ($_POST["fechaIngreso"] != "")
		if (!isFechaValida($_POST["fechaIngreso"])) {
			if (!$errores)
				echo "paso = 1;";
			echo "errores+= '- Fecha Ingreso a la Empresa errónea.<br />';";
			$errores = true;
		}

	if ($_POST["idProvincia"] == -1) {
		if (!$errores)
			echo "paso = 1;";
		echo "errores+= '- Domicilio vacío.<br />';";
		$errores = true;
	}

	if ($_POST["telefono"] == "") {
		if (!$errores)
			echo "paso = 1;";
		echo "errores+= '- Teléfono vacío.<br />';";
		$errores = true;
	}

	if ($_POST["numero"] == "") {
		if (!$errores)
			echo "paso = 1;";
		echo "errores+= '- Número de calle del domicilio vacío.<br />';";
		$errores = true;
	}

	if ($_POST["puesto"] == "") {
		if (!$errores)
			echo "paso = 1;";
		echo "errores+= '- Puesto vacío.<br />';";
		$errores = true;
	}

	if (($_POST["horaDesde"] == "-1") or ($_POST["minutoDesde"] == "-1") or ($_POST["horaHasta"] == "-1") and ($_POST["minutoHasta"] == "-1")) {
		if (!$errores)
			echo "paso = 1;";
		echo "errores+= '- Horario habitual de Trabajo vacío.<br />';";
		$errores = true;
	}

	if ($_POST["tipoSiniestro"] == -1) {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- Tipo de Siniestro vacío.<br />';";
		$errores = true;
	}

	if (!isFechaValida($_POST["fechaSiniestro"])) {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- Fecha Siniestro vacía o inválida.<br />';";
		$errores = true;
	}
	elseif (!fechaEnRango($_POST["fechaSiniestro"], "01/07/1996", date("d/m/Y"))) {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- La Fecha del Siniestro no puede ser posterior al día de hoy.<br />';";
		$errores = true;
	}

	if ($_POST["fechaRecaida"] != "") {
		if (!isFechaValida($_POST["fechaRecaida"])) {
			if (!$errores)
				echo "paso = 2;";
			echo "errores+= '- Fecha de Recaída inválida.<br />';";
			$errores = true;
		}
		elseif (!fechaEnRango($_POST["fechaRecaida"], "01/07/1996", date("d/m/Y"))) {
			if (!$errores)
				echo "paso = 2;";
			echo "errores+= '- La Fecha del Recaída no puede ser posterior al día de hoy.<br />';";
			$errores = true;
		}
	}

	if (($_POST["lugarOcurrencia"] == 1) and ($_POST["establecimientoPropio"] == -1) and (((isset($_POST["establecimientoTercero"])) and ($_POST["establecimientoTercero"] == -1)) or (!isset($_POST["establecimientoTercero"])))) {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- Debe seleccionar el Establecimiento Propio.<br />';";
		$errores = true;
	}

/*	if ($_POST["idProvinciaAccidente"] == -1) {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- Domicilio de ocurrencia del accidente vacío.<br />';";
		$errores = true;
	}*/
	if ($_POST["calleAccidente"] == "") {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- Calle de ocurrencia del accidente vacío.<br />';";
		$errores = true;
	}

	if ($_POST["numeroAccidente"] == "") {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- Número de calle del domicilio de ocurrencia del accidente vacío.<br />';";
		$errores = true;
	}

	if ($_POST["codigoPostalAccidente"] == -1) {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- Provincia de ocurrencia del accidente vacío.<br />';";
		$errores = true;
	}

	if ($_POST["provinciaAccidente"] == -1) {
		if (!$errores)
			echo "paso = 2;";
		echo "errores+= '- Provincia de ocurrencia del accidente vacío.<br />';";
		$errores = true;
	}

	if ($_POST["establecimientoAccidente"] == 0)
		if ($_POST["cuitContratista"] == "") {
			if (!$errores)
				echo "paso = 2;";
			echo "errores+= '- C.U.I.T. Contratista vacío.<br />';";
			$errores = true;
		}

	if ($_POST["cuitContratista"] != "")
		if (!validarCuit($_POST["cuitContratista"])) {
			if (!$errores)
				echo "paso = 2;";
			echo "errores+= '- C.U.I.T. Contratista inválido.<br />';";
			$errores = true;
		}



	if ($_POST["descripcionHecho"] == "") {
		if (!$errores)
			echo "paso = 3;";
		echo "errores+= '- Descripción del Hecho vacío.<br />';";
		$errores = true;
	}

	if ($_POST["formaAccidente"] == -1) {
		if (!$errores)
			echo "paso = 3;";
		echo "errores+= '- Forma del Accidente vacío.<br />';";
		$errores = true;
	}

	if ($_POST["agenteMaterial"] == -1) {
		if (!$errores)
			echo "paso = 3;";
		echo "errores+= '- Agente Material vacío.<br />';";
		$errores = true;
	}

	if ($_POST["parteCuerpoLesionada"] == -1) {
		if (!$errores)
			echo "paso = 3;";
		echo "errores+= '- Parte del Cuerpo Lesionada vacío.<br />';";
		$errores = true;
	}

	if ($_POST["naturalezaLesion"] == -1) {
		if (!$errores)
			echo "paso = 3;";
		echo "errores+= '- Naturaleza de la Lesión vacía.<br />';";
		$errores = true;
	}

	if ($_POST["gravedadPresunta"] == -1) {
		if (!$errores)
			echo "paso = 3;";
		echo "errores+= '- Gravedad Presunta vacía.<br />';";
		$errores = true;
	}

	if ($_POST["dni"] != "")
		if (!validarEntero($_POST["dni"])) {
			if (!$errores)
				echo "paso = 5;";
			echo "errores+= '- El D.N.I. del Denunciante es inválido.<br />';";
			$errores = true;
		}


	if ($errores) {
		echo "window.parent.paso = paso;";
		echo "window.parent.cambiarPaso(paso);";
		echo "getElementById('imgProcesando').style.display = 'none';";
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErrores').style.display = 'inline';";
		echo "getElementById('foco').style.display = 'block';";
		echo "getElementById('foco').focus();";
		echo "getElementById('foco').style.display = 'none';";
	}
	else {
		echo "getElementById('divErrores').style.display = 'none';";
	}

	echo "}";
	echo "</script>";

	return !$errores;
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));

try {
	$_POST["cuitContratista"] = sacarGuiones($_POST["cuitContratista"]);

	if (!validar())
		exit;


	$params = array(":id" => $_POST["idTrabajador"]);
	$sql =
		"SELECT tj_cuil, tj_nombre
			 FROM ctj_trabajador
			WHERE tj_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
	$apellidoNombreTrabajador = $row["TJ_NOMBRE"];
	$cuilTrabajador = $row["TJ_CUIL"];

/*
	if ($_POST["fechaRecaida"] != "") {
		$_POST["fechaRecaida"] = substr($_POST["fechaRecaida"], 6, 4)."/".substr($_POST["fechaRecaida"], 3, 3).substr($_POST["fechaRecaida"], 0, 2);
	}
Lo de arriba no me acuerdo porque lo hice, pero parece no hacer mas falta..
*/

	$curs = null;
	$params = array(":dew_epmanifestacion" => nullIsEmpty($_POST["fechaRecaida"]),
									":dew_fechasin" => $_POST["fechaSiniestro"],
									":djw_fec_ingreso" => nullIsEmpty($_POST["fechaIngreso"]),
									":djw_fec_nacimiento" => $_POST["fechaNacimiento"],
									":new_dnidenunciante" => $_POST["dni"],
									":new_idestablecimientotercero" => nullIfCero($_POST["establecimientoTercero"]),
									":new_prestadorid" => nullIfCero($_POST["idPrestador"]),
									":new_tiposiniestro" => $_POST["tipoSiniestro"],
									":njw_idtrabajador" => nullIfCero($_POST["idTrabajador"]),
									":sew_agente" => $_POST["agenteMaterial"],
									":sew_cuit" => $_SESSION["cuit"],
									":sew_descripcion" => substr($_POST["descripcionHecho"], 0, 250),
									":sew_denunciante" => $_POST["denunciante"],
									":sew_domicilioestable" => $_POST["calleAccidente"]." ".$_POST["numeroAccidente"]." ".$_POST["pisoAccidente"]." ".$_POST["departamentoAccidente"]." ".$_POST["codigoPostalAccidente"]." ".$_POST["localidadAccidente"]." ".$_POST["provinciaAccidente"],
									":sew_establecimiento" => nullIfCero($_POST["establecimientoPropio"]),
									":sew_establepropio" => IIF(($_POST["establecimientoAccidente"] == -1), 0, 1),
									":sew_forma" => $_POST["formaAccidente"],
									":sew_gravedad" => $_POST["gravedadPresunta"],
									":sew_horasin" => IIF((($_POST["horaAccidente"] == -1) or ($_POST["minutoAccidente"] == -1)), NULL ,$_POST["horaAccidente"].":".$_POST["minutoAccidente"]),
									":sew_horjornadadesde" => IIF((($_POST["horaJornadaLaboralDesde"] == -1) or ($_POST["minutoJornadaLaboralDesde"] == -1)), NULL, $_POST["horaJornadaLaboralDesde"].":".$_POST["minutoJornadaLaboralDesde"]),
									":sew_horjornadahasta" => IIF((($_POST["horaJornadaLaboralHasta"] == -1) or ($_POST["minutoJornadaLaboralHasta"] == -1)), NULL, $_POST["horaJornadaLaboralHasta"].":".$_POST["minutoJornadaLaboralHasta"]),
									":sew_lugarcalle" => $_POST["calleAccidente"],
									":sew_lugarcpostal" => $_POST["codigoPostalAccidente"],
									":sew_lugardenuncia" => $_POST["lugar"],
									":sew_lugarlocalidad" => $_POST["localidadAccidente"],
									":sew_lugarnro" => $_POST["numeroAccidente"],
									":sew_lugarocurrencia" => nullIfCero($_POST["lugarOcurrencia"]),
									":sew_lugarprovincia" => $_POST["idProvinciaAccidente"],
									":sew_manohabil" => $_POST["manoHabil"],
									":sew_multiple" => $_POST["siniestroMultiple"],
									":sew_naturaleza" => $_POST["naturalezaLesion"],
									":sew_otrolugar" => substr($_POST["lugarOcurrenciaOtros"], 0, 100),
									":sew_prestadordomicilio" => $_POST["domicilioPrestador"],
									":sew_prestadornombre" => $_POST["razonSocialPrestador"],
									":sew_prestadortelefono" => $_POST["telefonoPrestador"],
									":sew_tareaaccidente" => nullIsEmpty(substr($_POST["tareasAccidente"], 0, 100)),
									":sew_transito" => nullIfCero($_POST["accidenteTransito"]),
									":sew_zona" => $_POST["parteCuerpoLesionada"],
									":sjw_calle" => $_POST["calle"],
									":sjw_codpostal" => $_POST["codigoPostal"],
									":sjw_cuitcontratista" => trim($_POST["cuitContratista"]),
									":sjw_departamento" => nullIsEmpty($_POST["departamento"]),
									":sjw_documento" => $cuilTrabajador,
									":sjw_estcivil" => nullIfCero($_POST["estadoCivil"]),
									":sjw_horarioinicio" => $_POST["horaDesde"].":".$_POST["minutoDesde"],
									":sjw_horariofin" => $_POST["horaHasta"].":".$_POST["minutoHasta"],
									":sjw_localidad" => $_POST["localidad"],
									":sjw_nacionalidad" => nullIfCero($_POST["nacionalidad"]),
									":sjw_nombre" => $apellidoNombreTrabajador,
									":sjw_numero" => $_POST["numero"],
									":sjw_piso" => nullIsEmpty($_POST["piso"]),
									":sjw_provincia" => $_POST["idProvincia"],
									":sjw_puesto" => $_POST["puesto"],
									":sjw_sexo" => nullIfCero($_POST["sexo"]),
									":sjw_telefono" => nullIsEmpty(substr($_POST["telefono"], 0, 30)),
									":sjw_tipodoc" => 1,
									":new_nro_cecap" => nullIfCero($_POST["numeroCecap"]));
	$sql ="BEGIN webart.set_denuncia_siniestro(:data, TO_DATE(:dew_epmanifestacion, 'dd/mm/yyyy'), TO_DATE(:dew_fechasin, 'dd/mm/yyyy'), TO_DATE(:djw_fec_ingreso, 'dd/mm/yyyy'), TO_DATE(:djw_fec_nacimiento, 'dd/mm/yyyy'), :new_dnidenunciante, :new_idestablecimientotercero, :new_prestadorid, :new_tiposiniestro, :njw_idtrabajador, :sew_agente, :sew_cuit, :sew_descripcion, :sew_denunciante, :sew_domicilioestable, :sew_establecimiento, :sew_establepropio, :sew_forma, :sew_gravedad, :sew_horasin, :sew_horjornadadesde, :sew_horjornadahasta, :sew_lugarcalle, :sew_lugarcpostal, :sew_lugardenuncia, :sew_lugarlocalidad, :sew_lugarnro, :sew_lugarocurrencia, :sew_lugarprovincia, :sew_manohabil, :sew_multiple, :sew_naturaleza, :sew_otrolugar, :sew_prestadordomicilio, :sew_prestadornombre, :sew_prestadortelefono, :sew_tareaaccidente, :sew_transito, :sew_zona, :sjw_calle, :sjw_codpostal, :sjw_cuitcontratista, :sjw_departamento, :sjw_documento, :sjw_estcivil, :sjw_horarioinicio, :sjw_horariofin, :sjw_localidad, :sjw_nacionalidad, :sjw_nombre, :sjw_numero, :sjw_piso, :sjw_provincia, :sjw_puesto, :sjw_sexo, :sjw_telefono, :sjw_tipodoc, :new_nro_cecap); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);
}
catch (Exception $e) {
?>
<script type="text/javascript">
	alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	with (window.parent.document) {
		getElementById('imgProcesando').style.display = 'none';
		getElementById('btnEnviar').style.display = 'inline';
	}
</script>
<?
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.location.href = '/denuncia-siniestros/formulario/<?= $row["IDDENUNCIA"]?>';
	}

	setTimeout('redirect()', 2000);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
</script>