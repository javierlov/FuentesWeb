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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function moverImagen($img) {
	global $conn;

	$result = false;

	if ($img != "") {
		$fileOrigen = IMAGES_EDICION_PATH.$img;
		$partes_ruta = pathinfo($img);
		$fileDest = IMAGES_FOTOS_PATH.$_POST["usuario"].".".$partes_ruta["extension"];

		if (file_exists($fileDest))
			unlink($fileDest);

		if (rename($fileOrigen, $fileDest)) {
			$params = array(":foto" => $_POST["usuario"].".".$partes_ruta["extension"], ":id" => $_POST["id"]);
			$sql =
				"UPDATE use_usuarios
						SET se_foto = :foto
					WHERE se_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			$result = true;
		}
		else
			unlink($fileOrigen);
	}

	return $result;
}

function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";


	if (($_POST["fechaNacimiento"] != "") and (!isFechaValida($_POST["fechaNacimiento"]))) {
		echo "errores+= '- El campo Fecha Nacimiento debe ser una fecha válida.<br />';";
		$errores = true;
	}

	if (($_POST["piso"] != "") and (!validarEntero($_POST["piso"]))) {
		echo "errores+= '- El campo Piso debe ser un entero válido.<br />';";
		$errores = true;
	}

	if (($_POST["codigoInternoRRHH"] != "") and (!validarEntero($_POST["codigoInternoRRHH"]))) {
		echo "errores+= '- El campo Código Interno RRHH debe ser un entero válido.<br />';";
		$errores = true;
	}

	if ($_POST["legajoRRHH"] == "") {
		echo "errores+= '- El campo Legajo RRHH es obligatorio.<br />';";
		$errores = true;
	}
	elseif (!validarEntero($_POST["legajoRRHH"])) {
		echo "errores+= '- El campo Legajo RRHH debe ser un entero válido.<br />';";
		$errores = true;
	}

	if (($_POST["cuil"] != "") and (!validarCuit($_POST["cuil"]))) {
		echo "errores+= '- El campo C.U.I.L. debe ser una C.U.I.L. válida.<br />';";
		$errores = true;
	}

	if ($_POST["id"] == $_POST["respondeA"]) {
		echo "errores+= '- El usuario no puede responder a si mismo.<br />';";
		$errores = true;
	}

	if (($_POST["relacionLaboral"] == 1) and ($_POST["legajoRRHH"] != 0)) {
		$params = array(":id" => $_POST["id"], ":legajorrhh" => $_POST["legajoRRHH"]);
		$sql =
			"SELECT 1
				 FROM use_usuarios
				WHERE se_legajorrhh = :legajorrhh
					AND se_id <> :id";
		if (existeSql($sql, $params, 0)) {		// Valido que no exista el Nº de Legajo..
			echo "errores+= '- El Legajo RRHH ya fue asignado a otro usuario.<br />';";
			$errores = true;
		}
	}


	if ($errores) {
		echo "body.style.cursor = 'default';";
		echo "getElementById('btnGuardar').style.display = 'inline';";
		echo "getElementById('imgProcesando').style.display = 'none';";
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErroresForm').style.display = 'block';";
		echo "getElementById('foco').style.display = 'block';";
		echo "getElementById('foco').focus();";
		echo "getElementById('foco').style.display = 'none';";
	}
	else {
		echo "getElementById('divErroresForm').style.display = 'none';";
	}

	echo "}";
	echo "</script>";

	return !$errores;
}


try {
	if (!hasPermiso(2))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	$_POST["cuil"] = sacarGuiones($_POST["cuil"]);

	if (!validar())
		exit;

	$params = array(":cargo" => $_POST["cargo"],
									":contrato" => nullIfCero($_POST["relacionLaboral"]),
									":cuil" => nullIsEmpty($_POST["cuil"]),
									":delegacion" => nullIfCero($_POST["delegacion"]),
									":fechacumple" => nullIsEmpty($_POST["fechaNacimiento"]),
									":horarioatencion" => nullIsEmpty($_POST["horarioAtencion"]),
									":id" => $_POST["id"],
									":iddelegacionsede" => nullIfCero($_POST["edificio"]),
									":idsector" => nullIfCero($_POST["sector"]),
									":interno" => nullIsEmpty($_POST["interno"]),
									":legajo" => nullIsEmpty($_POST["codigoInternoRRHH"]),
									":legajorrhh" => nullIsEmpty($_POST["legajoRRHH"]),
									":piso" => nullIsEmpty($_POST["piso"]),
									":respondea" => nullIfCero($_POST["respondeA"]),
									":usumodif" => getWindowsLoginName(true));
	$sql =
		"UPDATE use_usuarios
				SET se_cargo = :cargo,
						se_contrato = :contrato,
						se_cuil = :cuil,
						se_delegacion = :delegacion,
						se_fechacumple = TO_DATE(:fechacumple, 'dd/mm/yyyy'),
						se_fechamodif = SYSDATE,
						se_horarioatencion = :horarioatencion,
						se_iddelegacionsede = :iddelegacionsede,
						se_idsector = :idsector,
						se_interno = :interno,
						se_legajo = :legajo,
						se_legajorrhh = :legajorrhh,
						se_piso = :piso,
						se_respondea = :respondea,
						se_usumodif = :usumodif
			WHERE se_id = :id";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	if (moverImagen($_POST["fileFoto"]))
		sendEmail("Se ha cargado la foto del usuario ".$_POST["usuario"].".", "Contacto Web", "Nueva foto cargada desde la intranet", array("aangiolillo@provart.com.ar"), array(), array());

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
	</script>
<?
	exit;
}
?>
<script language="JavaScript" src="/js/functions.js"></script>
<script type="text/javascript">
	showMsgOk('/usuarios-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>