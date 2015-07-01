<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


function cuitAutorizado($idEmpresa) {
	global $conn;

	$params = array(":id" => $idEmpresa);
	$sql =
		"SELECT 1
			 FROM art.aca_cuitautorizado, aem_empresa
			WHERE ca_cuit = em_cuit
				AND ca_fechabaja IS NULL
				AND em_id = :id";
	return (!existeSql($sql, $params));
}

function validar($validarAltaTemprana) {
	global $campoError;

	if ($_POST["cuil"] == "") {
		$campoError = "cuil";
		throw new Exception("Debe ingresar la C.U.I.L.");
	}

	if (!validarCuit($_POST["cuil"])) {
		$campoError = "cuil";
		throw new Exception("La C.U.I.L. ingresada es inválida");
	}

	if ($_POST["nombre"] == "") {
		$campoError = "nombre";
		throw new Exception("Debe ingresar el Nombre y Apellido.");
	}

	if (($validarAltaTemprana) and ($_POST["codigoAltaTemprana"] == "")) {
		$campoError = "codigoAltaTemprana";
		throw new Exception("Debe ingresar el Código de Alta Temprana.");
	}

	if ((!validarEntero(substr($_POST["codigoAltaTemprana"], 0, 8))) or (!validarEntero(substr($_POST["codigoAltaTemprana"], 8, 8))) or (!validarEntero(substr($_POST["codigoAltaTemprana"], 16, 8)))) {
		$campoError = "codigoAltaTemprana";
		throw new Exception("El Código de Alta Temprana debe ser un valor numérico.");
	}

	if ($_POST["sexo"] == -1) {
		$campoError = "sexo";
		throw new Exception("Debe elegir el Sexo.");
	}

	if ($_POST["nacionalidad"] == -1) {
		$campoError = "nacionalidad";
		throw new Exception("Debe elegir la Nacionalidad.");
	}

	if ($_POST["fechaNacimiento"] == "") {
		$campoError = "fechaNacimiento";
		throw new Exception("Debe ingresar la Fecha de Nacimiento.");
	}

	if (!isFechaValida($_POST["fechaNacimiento"])) {
		$campoError = "fechaNacimiento";
		throw new Exception("La Fecha de Nacimiento es inválida.");
	}

	if ($_POST["estadoCivil"] == -1) {
		$campoError = "estadoCivil";
		throw new Exception("Debe elegir el Estado Civil.");
	}

	if ($_POST["fechaIngreso"] == "") {
		$campoError = "fechaIngreso";
		throw new Exception("Debe ingresar la F. de Ingreso en la Empresa.");
	}

	if (!isFechaValida($_POST["fechaIngreso"])) {
		$campoError = "fechaIngreso";
		throw new Exception("La F. de Ingreso en la Empresa es inválida.");
	}

	return true;
}


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));

set_time_limit(300);
try {
	$campoError = "";
	$idTrabajador = $_POST["id"];

	$validarAltaTemprana = false;
	if ($idTrabajador == -1) {
		$params = array(":id" => $_SESSION["idEmpresa"]);
		$sql =
			"SELECT em_suss
				 FROM aem_empresa
				WHERE em_id = :id";
//	$validarAltaTemprana = (valorSql($sql, "", $params) != 2);
		$validarAltaTemprana = false;
	}

	if (!((isset($_POST["baja"])) and ($_POST["baja"] == "t")))		// Si no es una baja, valido..
		if (!validar($validarAltaTemprana))
			exit;


	// Valido que la empresa tenga autorización para de dar de alta o de baja trabajadores..
	if (($idTrabajador < 1) or ((isset($_POST["baja"])) and ($_POST["baja"] == "t")))
		if (!cuitAutorizado($_SESSION["idEmpresa"]))
			throw new Exception("Operación inválida.");

	if ($idTrabajador < 1) {		// Si es un alta valido el código de alta temprana..
//		$params = array(":id" => $_SESSION["idEmpresa"]);
//		$sql =
//			"SELECT em_suss
//				 FROM aem_empresa
//				WHERE em_id = :id";
//		if (ValorSql($sql, "", $params) != 2) {		// Se valida solo para empresas NO SUSS..
//			if (strlen($_POST["codigoAltaTemprana"]) <> 20)
			if ((strlen($_POST["codigoAltaTemprana"]) > 0) and (strlen($_POST["codigoAltaTemprana"]) <> 20)) {
				throw new Exception("El Código de Alta Temprana debe ser un número de 20 dígitos.");

			$params = array(":ca" => $_POST["codigoAltaTemprana"], ":cuil" => $_POST["cuil"]);
			$sql =
				"SELECT 1
					 FROM ctj_trabajador
					WHERE tj_cuil <> :cuil
						AND tj_ca = :ca";
			if (existeSql($sql, $params))
				throw new Exception("El Código de Alta Temprana ingresado ya está asociado a otra C.U.I.L.");
		}
	}

	// Valido que la fecha de ingreso del trabajador no tenga mas de dos meses de diferencia con la fecha actual..
	if (($idTrabajador < 1) or ((($idTrabajador > 0)) and ($_POST["fechaIngreso"] != $_POST["fechaIngresoOld"])))		// Si es un alta o si no es un alta y se modificó la fecha de ingreso..
		if ((dateDiff(date("d/m/Y"), $_POST["fechaIngreso"]) > 60) or (dateDiff(date("d/m/Y"), $_POST["fechaIngreso"]) < -60))
			throw new Exception("La Fecha de Ingreso no puede ser ni superior ni inferior en 2 meses a la fecha del día de hoy.");

	if ($_POST["domicilioManual"] == "t")
		$domicilio = $_POST["calle"]." ".$_POST["numero"]." ".$_POST["piso"]." ".$_POST["departamento"]." C.P. ".$_POST["codigoPostal"]." LOC. ".$_POST["localidad"]." PROV. ".$_POST["provincia"];
	else
		$domicilio = null;

	if ((isset($_POST["baja"])) and ($_POST["baja"] == "t")) {
		if ($_POST["fechaEgreso"] == "")
			throw new Exception("Debe ingresar la Fecha de Egreso de la Empresa.");

		if (!isFechaValida($_POST["fechaEgreso"]))
			throw new Exception("La Fecha de Egreso de la Empresa es inválida.");

		$curs = null;
		$params = array(":fechabaja" => $_POST["fechaEgreso"],
										":contrato" => $_SESSION["contrato"],
										":idrelacionlaboral" => nullIfCero($_POST["idRelacionLaboral"]),
										":idtrabajador" => $idTrabajador,
										":idusuario" => $_SESSION["idUsuario"]);
		$sql = "BEGIN webart.set_baja_trabajador(TO_DATE(:fechabaja, 'dd/mm/yyyy'), :contrato, :idrelacionlaboral, :idtrabajador, :idusuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	else {
		// Valido que tenga cargado al menos un establecimiento..
		if ($_POST["establecimientos"] == "-1")
			throw new Exception("Debe cargar al menos un (1) establecimiento.");


		$curs = null;
		$params = array(":cconfirmapuesto" => ((isset($_POST["noConfirmadoPuesto"]))?"N":"S"),
										":dfechabaja" => NULL,
										":dfechaingreso" => $_POST["fechaIngreso"],
										":dfechanacimiento" => $_POST["fechaNacimiento"],
										":ncontrato" => $_SESSION["contrato"],
										":nidmodalidadcontratacion" => nullIfCero($_POST["tipoContrato"]),
										":nidnacionalidad" => nullIfCero($_POST["nacionalidad"]),
										":nidrelacionlaboral" => nullIfCero($_POST["idRelacionLaboral"]),
										":nidtrabajador" => nullIfCero($idTrabajador),
										":nidusuario" => $_SESSION["idUsuario"],
										":nsueldo" => formatFloat(nullIfCero($_POST["remuneracion"])),
										":scalle" => $_POST["calle"],
										":scategoria" => NULL,
										":sciuo" => nullIfCero($_POST["idCiuo"]),
										":scodaltatemprana" => nullIfCero($_POST["codigoAltaTemprana"]),
										":scodareatelefono" => NULL,
										":scpostal" => $_POST["codigoPostal"],
										":scpostala" => NULL,
										":scuil" => $_POST["cuil"],
										":sdepartamento" => $_POST["departamento"],
										":sdocumento" => NULL,
										":sdomicilio" => $domicilio,
										":semail" => strtoupper($_POST["email"]),
										":sestablecimientos" => $_POST["establecimientos"],
										":sestadocivil" => $_POST["estadoCivil"],
										":slateralidad" => NULL,
										":slocalidad" => $_POST["localidad"],
										":snombre" => strtoupper($_POST["nombre"]),
										":snumero" => $_POST["numero"],
										":sotranacionalidad" => $_POST["otraNacionalidad"],
										":spiso" => $_POST["piso"],
										":sprovincia" => $_POST["idProvincia"],
										":ssector" => $_POST["sector"],
										":ssexo" => $_POST["sexo"],
										":starea" => $_POST["tarea"],
										":stelefono" => NULL);
		$sql = "BEGIN webart.set_trabajador(:data, :cconfirmapuesto, TO_DATE(:dfechabaja, 'dd/mm/yyyy'), TO_DATE(:dfechaingreso, 'dd/mm/yyyy'), TO_DATE(:dfechanacimiento, 'dd/mm/yyyy'), :ncontrato, :nidmodalidadcontratacion, :nidnacionalidad, :nidrelacionlaboral, :nidtrabajador, :nidusuario, :nsueldo, :scalle, :scategoria, :sciuo, :scodaltatemprana, :scodareatelefono, :scpostal, :scpostala, :scuil, :sdepartamento, :sdocumento, :sdomicilio, :semail, :sestablecimientos, :sestadocivil, :slateralidad, :slocalidad, :snombre, :snumero, :sotranacionalidad, :spiso, :sprovincia, :ssector, :ssexo, :starea, :stelefono); END;";

		$stmt = DBExecSP($conn, $curs, $sql, $params);
		$row = DBGetSP($curs);

		if (nullIfCero($idTrabajador) == NULL) {
			$sql = "SELECT MAX(tj_id) FROM ctj_trabajador";
			$idTrabajador = valorSql($sql, "", array());
		}

		if (($row["NUMEROERROR"] != "0") and ($row["NUMEROERROR"] != ""))
			throw new Exception($row["NUMEROERROR"]." - ".$row["DESCRIPCIONERROR"]);

		// Actualizo los teléfonos..
		$dataTel = inicializarTelefonos(OCI_COMMIT_ON_SUCCESS, "tt_idtrabajador", $idTrabajador, "tt", "att_telefonotrabajador", $_SESSION["usuario"]);
		copiarTempATelefonos($dataTel);

		$params = array(":id" => $idTrabajador);
		$sql =
			"UPDATE ctj_trabajador
					SET tj_telefono = SUBSTR(art.afi.get_telefonos('ATT_TELEFONOTRABAJADOR', tj_id), 1, 30)
				WHERE tj_id = :id";
		DBExecSql($conn, $sql, $params);
	}
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type="text/javascript">
		with (window.parent.document) {
			if (getElementById('<?= $campoError?>') != null) {
				getElementById('<?= $campoError?>').style.backgroundColor = '#f00';
				getElementById('<?= $campoError?>').style.color = '#fff';
				getElementById('<?= $campoError?>').focus();
			}
			alert(unescape('<?= rawurlencode($e->getMessage())?>'));
			body.style.cursor = 'default';
			getElementById('imgProcesando').style.display = 'none';
			getElementById('btnGuardar').style.display = 'inline';
			setTimeout("window.parent.document.getElementById('<?= $campoError?>').style.backgroundColor = ''; window.parent.document.getElementById('<?= $campoError?>').style.color = '';", 2000);
		}
	</script>
<?
	exit;
}

$params = "";
if (isset($_SESSION["isAgenteComercial"]))
	$params = "/".$_SESSION["contrato"];
?>
<script type="text/javascript">
	function volver() {
		window.parent.location.href = '/nomina-trabajadores<?= $params?>';
	}

	setTimeout('volver()', 2000);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
</script>