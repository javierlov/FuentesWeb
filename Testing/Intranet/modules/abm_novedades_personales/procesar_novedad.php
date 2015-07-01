<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");


function subirArchivo($arch, $folder, $filename, $extensionesPermitidas, &$error) {
	$tmpfile = $arch["tmp_name"];
	$partes_ruta = pathinfo(strtolower($arch["name"]));

	if (!in_array($partes_ruta["extension"], $extensionesPermitidas)) {
		$error = "El archivo debe tener alguna de las siguientes extensiones: ".implode(" o ", $extensionesPermitidas).".";
		return false;
	}

	$filename = StringToLower($filename.".".$partes_ruta["extension"]);

	if (!is_uploaded_file($tmpfile)) {
		$error = "El archivo no subió correctamente.";
		return false;
	}

	if (!move_uploaded_file($tmpfile, $folder.$filename)) {
		$error = "El archivo no pudo ser guardado.";
		return false;
	}

	return true;
}

function validar() {
	$errores = false;

	echo "<script>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["tipoNovedad"] == -1) {
		echo "errores+= '- Tipo de Novedad sin elegir.<br />';";
		$errores = true;
	}

	if (trim($_POST["titulo"]) == "") {
		echo "errores+= '- Título vacío.<br />';";
		$errores = true;
	}

	if (trim($_POST["texto"]) == "") {
		echo "errores+= '- Texto vacío.<br />';";
		$errores = true;
	}

	if ($errores) {
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErrores').style.display = 'block';";
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


try {
	SetDateFormatOracle("DD/MM/YYYY");

	if (!validar())
		exit;


	if ($_REQUEST["tipoOp"] == "A") {		// Alta..
		$params = array(":id" => -1,
										":texto" => $_REQUEST["texto"],
										":tiponovedad" => nullIfCero($_REQUEST["tipoNovedad"]),
										":titulo" => $_REQUEST["titulo"],
										":usualta" => GetWindowsLoginName());
		$sql =
			"INSERT INTO rrhh.rnp_novedadespersonales (np_id, np_tiponovedad, np_texto, np_fechaalta, np_usualta, np_titulo)
																				 VALUES (:id, :tiponovedad, :texto, SYSDATE, UPPER(:usualta), :titulo)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(np_id) FROM rrhh.rnp_novedadespersonales";
		$_REQUEST["id"] = ValorSql($sql, 0, array(), 0);
	}

	if ($_REQUEST["tipoOp"] == "M") {		// Modificación..
		$params = array(":id" => $_REQUEST["id"],
										":texto" => $_REQUEST["texto"],
										":tiponovedad" => nullIfCero($_REQUEST["tipoNovedad"]),
										":titulo" => $_REQUEST["titulo"],
										":usumodif" => GetWindowsLoginName());
		$sql =
			"UPDATE rrhh.rnp_novedadespersonales
					SET np_tiponovedad = :tiponovedad,
							np_texto = :texto,
							np_titulo = :titulo,
							np_fechamodif = SYSDATE,
							np_usumodif = UPPER(:usumodif)
				WHERE np_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_REQUEST["tipoOp"] == "B") {		// Baja..
		$params = array(":id" => $_REQUEST["id"], ":usubaja" => GetWindowsLoginName());
		$sql =
			"UPDATE rrhh.rnp_novedadespersonales
					SET np_fechabaja = SYSDATE,
							np_usubaja = UPPER(:usubaja)
				WHERE np_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_FILES["imagen"]["name"] != "") {
		$error = "";
		if (!subirArchivo($_FILES["imagen"], DATA_CELEBRACIONES_PATH, $_REQUEST["id"], array("gif", "jpeg", "jpg", "png"), $error))
			throw new Exception($error);
	}

	DBCommit($conn);
}
catch (Exception $e) {
	DBRollback($conn);
?>
<script>
	alert(unescape('<?= rawurlencode($e->getMessage())?>'));
</script>
<?
	exit;
}
?>
<script>
		with (window.parent) {
			document.getElementById('guardadoOk').style.display = 'block';
			location.href = '/index.php?pageid=20&buscar=yes';
		}
</script>