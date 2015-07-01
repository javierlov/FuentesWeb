<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";

	echo "getElementById('cuit').style.borderColor = '';";
	echo "getElementById('cuit').title = '';";
	echo "getElementById('nombre').style.borderColor = '';";
	echo "getElementById('nombre').title = '';";
	echo "getElementById('numero').style.borderColor = '';";
	echo "getElementById('numero').title = '';";
	echo "getElementById('numeroEstablecimiento').style.borderColor = '';";
	echo "getElementById('numeroEstablecimiento').title = '';";
	echo "getElementById('pSinDatosconocidos').style.border = '';";
	echo "getElementById('pSinDatosconocidos').title = '';";





	if ($_POST["numeroEstablecimiento"] == "") {
		echo "getElementById('numeroEstablecimiento').style.borderColor = '#f00';";
		echo "getElementById('numeroEstablecimiento').title = 'Debe ingresar el Nº de Establecimiento.';";
		$errores = true;
	}
	elseif (!validarEntero($_POST["numeroEstablecimiento"])) {
		echo "getElementById('numeroEstablecimiento').style.borderColor = '#f00';";
		echo "getElementById('numeroEstablecimiento').title = 'El Nº de Establecimiento es inválido.';";
		$errores = true;
	}
	else {
		$params = array(":cuit" => $_SESSION["cuit"], ":id" => $_POST["id"], ":nroestableci" => $_POST["numeroEstablecimiento"]);
		$sql =
			"SELECT 1
				 FROM SIN.set_establecimiento_temporal
				WHERE et_cuit = :cuit
					AND et_nroestableci = :nroestableci
					AND et_id <> :id
					AND et_fechabaja IS NULL";
		if (ExisteSql($sql, $params)) {
			echo "getElementById('numeroEstablecimiento').style.borderColor = '#f00';";
			echo "getElementById('numeroEstablecimiento').title = 'Ya existe ese Nº para otro establecimiento.';";
			$errores = true;
		}
	}

	if ($_POST["nombre"] == "") {
		echo "getElementById('nombre').style.borderColor = '#f00';";
		echo "getElementById('nombre').title = 'Debe ingresar el Nombre.';";
		$errores = true;
	}

	if ($_POST["cuit"] == "") {
		echo "getElementById('cuit').style.borderColor = '#f00';";
		echo "getElementById('cuit').title = 'Debe ingresar la C.U.I.T.';";
		$errores = true;
	}
	elseif (!validarCuit(sacarGuiones($_POST["cuit"]))) {
		echo "getElementById('cuit').style.borderColor = '#f00';";
		echo "getElementById('cuit').title = 'C.U.I.T. inválida.';";
		$errores = true;
	}

	if (sacarGuiones($_POST["cuit"]) == $_SESSION["cuit"]) {
		echo "getElementById('cuit').style.borderColor = '#f00';";
		echo "getElementById('cuit').title = 'La C.U.I.T. no puede ser la C.U.I.T. de su empresa.';";
		$errores = true;
	}

	if ($_POST["calle"] == "") {
		echo "getElementById('pSinDatosconocidos').style.border = '1px solid #f00';";
		echo "getElementById('pSinDatosconocidos').title = 'Debe indicar el Domicilio del establecimiento.';";
		$errores = true;
	}
	else {
		if ($_POST["numero"] == "") {
			echo "getElementById('numero').style.borderColor = '#f00';";
			echo "getElementById('numero').title = 'Debe ingresar el Número del Domicilio.';";
			$errores = true;
		}
	}

	if ($errores)
		echo "getElementById('errores').style.display = 'inline';";
	else
		echo "getElementById('errores').style.display = 'none';";

	echo "}";
	echo "</script>";

	return !$errores;
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));

try {
	if (!validar())
		exit;

	$curs = null;
	$params = array(":nid" => intval($_POST["id"]),
									":nidprovincia" => $_POST["idProvincia"],
									":nnumeroestablecimiento" => $_POST["numeroEstablecimiento"],
									":scalle" => $_POST["calle"],
									":scodigopostal" => $_POST["codigoPostal"],
									":scuit" => $_SESSION["cuit"],
									":scuittemporal" => sacarGuiones($_POST["cuit"]),
									":sdepartamento" => strtoupper($_POST["departamento"]),
									":slocalidad" => $_POST["localidad"],
									":snombre" => strtoupper($_POST["nombre"]),
									":snumero" => strtoupper($_POST["numero"]),
									":sobservaciones" => $_POST["observaciones"],
									":spiso" => strtoupper($_POST["piso"]),
									":stelefonos" => $_POST["telefonos"],
									":susuario" => "W_".$_SESSION["idUsuario"]);
	$sql ="BEGIN webart.set_establecimiento_temporal(:nid, :nidprovincia, :nnumeroestablecimiento, :scalle, :scodigopostal, :scuit, :scuittemporal, :sdepartamento, :slocalidad, :snombre, :snumero, :sobservaciones, :spiso, :stelefonos, :susuario); END;";
	DBExecSP($conn, $curs, $sql, $params, false);

	if (intval($_POST["id"]) < 1) {
		$params = array(":cuit" => $_SESSION["cuit"]);
		$sql = "SELECT MAX(et_id) FROM SIN.set_establecimiento_temporal WHERE et_cuit = :cuit";
		$_POST["id"] = ValorSql($sql, "", $params);
	}
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.location.href = '/modules/usuarios_registrados/clientes/denuncias_de_siniestros/administrar_establecimientos.php?buscar=yes&id=<?= $_POST["id"]?>';
	}

	setTimeout('redirect()', 2000);
	window.parent.document.getElementById('guardadoOk').style.display = 'inline';
<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT et_id id, et_nombre || ' (' || art.utiles.armar_domicilio(et_calle, et_numero, et_piso, et_departamento, NULL) || art.utiles.armar_localidad(et_cpostal, NULL, et_localidad, et_provincia) || ')' detalle
		 FROM sin.set_establecimiento_temporal
		WHERE et_fechabaja IS NULL
			AND et_cuit = :cuit
 ORDER BY 2";
$comboEstablecimientoTercero = new Combo($sql, "establecimientoTercero");
$comboEstablecimientoTercero->addParam(":cuit", $_SESSION["cuit"]);
?>
	parent.parent.window.document.getElementById('establecimientoTercero').parentNode.innerHTML = '<?= $comboEstablecimientoTercero->draw();?>';
</script>