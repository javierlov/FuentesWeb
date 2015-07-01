<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["tipoEstablecimiento"] == -1) {
		echo "errores+= '- El campo Tipo de Establecimiento es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["nombre"] == "") {
		echo "errores+= '- El campo Nombre es obligatorio.<br />';";
		$errores = true;
	}

	$params = array(":codigo" => $_POST["actividad"]);
	$sql =
		"SELECT 1
			 FROM cac_actividad
			WHERE ac_codigo = :codigo
				AND ac_fechabaja IS NULL";
	if (!ExisteSql($sql, $params)) {
		echo "errores+= '- La Actividad es inválida.<br />';";
		$errores = true;
	}

	if (($_POST["fechaInicioEstablecimiento"] != "") and (!isFechaValida($_POST["fechaInicioEstablecimiento"]))) {
		echo "errores+= '- La Fecha de Inicio del Establecimiento es inválida.<br />';";
		$errores = true;
	}

	if ($_POST["cantidadEmpleados"] == "") {
			echo "errores+= '- El campo Cantidad de Empleados es obligatorio.<br />';";
			$errores = true;
	}

	if ($_POST["cantidadEmpleados"] != "") {
		if (!validarEntero($_POST["cantidadEmpleados"])) {
			echo "errores+= '- El campo Cantidad de Empleados debe ser mayor o igual a 0.<br />';";
			$errores = true;
		}
	}

	if ($_POST["masaSalarial"] != "") {
		if (!validarNumero($_POST["masaSalarial"])) {
			echo "errores+= '- El campo Masa Salarial es inválido.<br />';";
			$errores = true;
		}
	}

	if ($_POST["superficie"] != "") {
		if (!validarNumero($_POST["superficie"])) {
			echo "errores+= '- El campo Superficie es inválido.<br />';";
			$errores = true;
		}

		if (intval($_POST["superficie"]) < 0) {
			echo "errores+= '- La Superficie debe ser mayor o igual a 0.<br />';";
			$errores = true;
		}

		if (intval($_POST["superficie"]) > 999999) {
			echo "errores+= '- La Superficie debe ser inferior a 1.000.000.<br />';";
			$errores = true;
		}
	}

	if (($_POST["tipoEstablecimiento"] == "O") and ($_POST["fechaFinObra"] == "")) {
		echo "errores+= '- El campo Fecha de Finalización de la Obra es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["fechaFinObra"] != "") {
		if (!isFechaValida($_POST["fechaFinObra"])) {
			echo "errores+= '- La Fecha de Finalización de la Obra es inválida.<br />';";
			$errores = true;
		}

		if (dateDiff(date("d/m/Y"), $_POST["fechaFinObra"]) < 0) {
			echo "errores+= '- La Fecha de Finalización de la Obra no puede ser anterior al día de hoy.<br />';";
			$errores = true;
		}
	}

	if ($_POST["codigoArea"] != "")
		if (!validarEntero($_POST["codigoArea"])) {
			echo "errores+= '- El campo Teléfono Laboral debe ser numérico.<br />';";
			$errores = true;
		}

	if ($_POST["telefono"] != "")
		if (!validarEntero($_POST["telefono"])) {
			echo "errores+= '- El campo Teléfono Laboral debe ser numérico.<br />';";
			$errores = true;
		}

	if ($_POST["interno"] != "")
		if (!validarEntero($_POST["interno"])) {
			echo "errores+= '- El campo Interno debe ser numérico.<br />';";
			$errores = true;
		}

	if ($_POST["codigoAreaFax"] != "")
		if (!validarEntero($_POST["codigoAreaFax"])) {
			echo "errores+= '- El campo Fax debe ser numérico.<br />';";
			$errores = true;
		}

	if ($_POST["fax"] != "")
		if (!validarEntero($_POST["fax"])) {
			echo "errores+= '- El campo Fax debe ser numérico.<br />';";
			$errores = true;
		}

	if ($_POST["calle"] == "") {
		echo "errores+= '- Debe cargar el domicilio.<br />';";
		$errores = true;
	}

	$params = array(":idsolicitud" => $_POST["idSolicitud"],
									":calle" => $_POST["calle"],
									":numero" => $_POST["numero"],
									":piso" => $_POST["piso"],
									":departamento" => $_POST["departamento"]);
	$sql =
		"SELECT 1
			 FROM ase_solicitudestablecimiento
			WHERE se_idsolicitud = :idsolicitud
				AND UPPER(se_calle) = UPPER(:calle)
				AND UPPER(se_numero) = UPPER(:numero)
				AND UPPER(NVL(se_piso, ' ')) = UPPER(:piso)
				AND UPPER(NVL(se_departamento, ' ')) = UPPER(:departamento)";
	if ($_POST["id"] > 0) {
		$params[":id"] = $_POST["id"];
		$sql.= " AND se_id <> :id";
	}
	if (ExisteSql($sql, $params)) {
		echo "errores+= '- Ya existe un establecimiento con ese Domicilio.<br />';";
		$errores = true;
	}


	if ($errores) {
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


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

try {
	if (isset($_POST["sinPersonal"]))
		$_POST["cantidadEmpleados"] = "0";

	if (!validar())
		exit;


	if ($_POST["esDomicilioLegal"] == "T")
		$_POST["nombre"].= " (LEGAL)";

	if ($_POST["numero"] == "")
		$_POST["numero"] = "S/N";

	$curs = null;
	$params = array(":dfechafinobra" => $_POST["fechaFinObra"],
									":dfechainicio" => $_POST["fechaInicioEstablecimiento"],
									":ncodigoarea" => $_POST["codigoArea"],
									":ncodigoareafax" => $_POST["codigoAreaFax"],
									":nempleados" => intval($_POST["cantidadEmpleados"]),
									":nfax" => $_POST["fax"],
									":nid" => $_POST["id"],
									":nidsolicitud" => $_POST["idSolicitud"],
									":ninterno" => intval($_POST["interno"]),
									":nmasasalarial" => formatFloat($_POST["masaSalarial"]),
									":nsuperficie" => formatFloat($_POST["superficie"]),
									":ntelefono" => $_POST["telefono"],
									":sactividad" => $_POST["actividad"],
									":scalle" => substr($_POST["calle"], 0, 60),
									":scodigopostal" => $_POST["codigoPostal"],
									":sdepartamento" => $_POST["departamento"],
									":sidprovincia" => $_POST["idProvincia"],
									":slocalidad" => $_POST["localidad"],
									":snombre" => $_POST["nombre"],
									":snumero" => $_POST["numero"],
									":sobservaciones" => substr($_POST["observaciones"], 0, 150),
									":spiso" => $_POST["piso"],
									":stipoestablecimiento" => $_POST["tipoEstablecimiento"],
									":susumodif" => "W_".$_SESSION["usuario"]);
	$sql ="BEGIN webart.set_establecimiento_afiliacion(TO_DATE(:dfechafinobra, 'dd/mm/yyyy'), TO_DATE(:dfechainicio, 'dd/mm/yyyy'), :ncodigoarea, :ncodigoareafax, :nempleados, :nfax, :nid, :nidsolicitud, :ninterno, :nmasasalarial, :nsuperficie, :ntelefono, :sactividad, :scalle, :scodigopostal, :sdepartamento, :sidprovincia, :slocalidad, :snombre, :snumero, :sobservaciones, :spiso, :stipoestablecimiento, :susumodif); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.parent.document.getElementById('iframeEstablecimientos').contentWindow.location.reload(true);
		window.parent.parent.divWin.close();
	}

	setTimeout('redirect()', 1500);
	window.parent.document.getElementById('guardadoOk').style.display = 'block';
</script>