<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getNombreOriginal($nombre) {
	if (strpos($nombre, " (LEGAL)"))
		$nombre = str_replace(" (LEGAL)", "", $nombre);

	return $nombre;
}

function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

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
			 FROM afi.alt_lugartrabajo_pcp
			WHERE lt_idsolicitud = :idsolicitud
				AND UPPER(lt_calle) = UPPER(:calle)
				AND UPPER(lt_numero) = UPPER(:numero)
				AND UPPER(NVL(lt_piso, ' ')) = UPPER(:piso)
				AND UPPER(NVL(lt_departamento, ' ')) = UPPER(:departamento)";
	if ($_POST["id"] > 0) {
		$params[":id"] = $_POST["id"];
		$sql.= " AND lt_id <> :id";
	}
	if (existeSql($sql, $params)) {
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
	if (!validar())
		exit;


	if ($_POST["numero"] == "")
		$_POST["numero"] = "S/N";

	$curs = null;
	$params = array(":nid" => $_POST["id"],
									":nidsolicitud" => $_POST["idSolicitud"],
									":scalle" => substr($_POST["calle"], 0, 60),
									":scodigopostal" => $_POST["codigoPostal"],
									":sdepartamento" => $_POST["departamento"],
									":sidprovincia" => $_POST["idProvincia"],
									":slocalidad" => substr($_POST["localidad"], 0, 60),
									":snumero" => $_POST["numero"],
									":spiso" => $_POST["piso"],
									":susumodif" => "W_".substr($_SESSION["usuario"], 0, 18));
	$sql = "BEGIN webart.set_lugar_trabajo_afiliacion(:nid, :nidsolicitud, :scalle, :scodigopostal, :sdepartamento, :sidprovincia, :slocalidad, :snumero, :spiso, :susumodif); END;";
	DBExecSP($conn, $curs, $sql, $params, false);
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