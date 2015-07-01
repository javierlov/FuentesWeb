<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function validar() {
	if ($_POST["volanta"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Volanta.'); parent.document.getElementById('volanta').focus();</script>";
		return false;
	}

	if ($_POST["titulo"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Título.'); parent.document.getElementById('titulo').focus();</script>";
		return false;
	}

	if ($_POST["cuerpo"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Cuerpo.'); parent.document.getElementById('cuerpo').focus();</script>";
		return false;
	}

	if ($_POST["cuerpoFull"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Cuerpo Full.'); parent.document.getElementById('cuerpoFull').focus();</script>";
		return false;
	}

	if ($_POST["posicion"] == "") {
		echo "<script type='text/javascript'>alert('Por favor, complete el campo Posición.'); parent.document.getElementById('posicion').focus();</script>";
		return false;
	}
	if ((!validarEntero($_POST["posicion"]))) {
		echo "<script type='text/javascript'>alert('La posición ingresada es inválida.'); parent.document.getElementById('posicion').select(); parent.document.getElementById('posicion').focus();</script>";
		return false;
	}
	if (($_POST["posicion"] < 1) or ($_POST["posicion"] > 4)) {
		echo "<script type='text/javascript'>alert('La posición debe ser un valor numérico entre 1 y 4.'); parent.document.getElementById('posicion').select(); parent.document.getElementById('posicion').focus();</script>";
		return false;
	}

	return true;
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('btnGuardarAlta').style.display = 'inline';
		getElementById('imgGuardandoAlta').style.display = 'none';
	}
</script>
<?
try {
	if (!validar())
		exit;


	$params = array(":posicion" => $_POST["posicion"], ":usuario" => getWindowsLoginName(true));
	$sql =
		"UPDATE web.wax_articulosextranetedicion
				SET ax_baja = 'T'
			WHERE ax_usuario = :usuario
				AND ax_posicion = :posicion
				AND ax_baja = 'F'";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	$params = array(":carpetaimagenes" => $_POST["carpeta"],
									":cuerpo" => $_POST["cuerpo"],
									":cuerpofull" => $_POST["cuerpoFull"],
									":posicion" => $_POST["posicion"],
									":titulo" => $_POST["titulo"],
									":usuario" => GetWindowsLoginName(true),
									":volanta" => $_POST["volanta"]);
	$sql =
		"INSERT INTO web.wax_articulosextranetedicion (ax_carpetaimagenes, ax_cuerpo, ax_cuerpofull, ax_idarticuloextranet, ax_posicion, ax_titulo, ax_usuario, ax_volanta)
																					 VALUES (:carpetaimagenes, :cuerpo, :cuerpofull, -1, :posicion, :titulo, :usuario, :volanta)";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);


	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function finalizar() {
		window.parent.location.reload(true);
	}

	setTimeout('finalizar()', 2000);

	with (window.parent.document) {
		getElementById('imgGuardandoAlta').style.display = 'none';
		getElementById('imgAltaOk').style.display = 'inline';
	}
</script>