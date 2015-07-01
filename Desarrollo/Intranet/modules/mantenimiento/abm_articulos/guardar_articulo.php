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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function moverImagen($tipo, $img) {
	global $conn;

	if ($img != "") {
		$fileOrigen = IMAGES_EDICION_PATH.$img;
		$partes_ruta = pathinfo($img);
		$filename = $_POST["id"]."_".$tipo.".".$partes_ruta["extension"];
		$fileDest = IMAGES_ARTICULOS_PATH.$_POST["id"]."/".$filename;

		if (!file_exists(IMAGES_ARTICULOS_PATH.$_POST["id"]))
			makeDirectory(IMAGES_ARTICULOS_PATH.$_POST["id"]);

		unlink($fileDest);
		if (rename($fileOrigen, $fileDest)) {
			$params = array(":id" => $_POST["id"],
											":img" => $filename);
			$sql =
				"UPDATE web.wai_articulosintranet
						SET ".(($tipo=="c")?"ai_rutaimagen":"ai_imagengrande")." = :img
					WHERE ai_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
		else
			unlink($fileOrigen);
	}
}

function subirArchivo($arch, $folder, $extensionesPermitidas, $maxFileSize, &$file, &$msgError) {
	$tmpfile = $arch["tmp_name"];
	$partes_ruta = pathinfo(strtolower($arch["name"]));

	$filename = $arch["name"];
	$ruta = $folder.$_POST["id"]."/";
	$file = $ruta.$filename;

	if (!makeDirectory($ruta)) {
		$msgError = "ERROR: No se puede crear la carpeta.";
		return false;
	}

	if (!in_array($partes_ruta["extension"], $extensionesPermitidas)) {
		$msgError = "ERROR: El archivo debe tener alguna de las siguientes extensiones: ".implode(" o ", $extensionesPermitidas).".";
		return false;
	}

	if (!is_uploaded_file($tmpfile)) {
		$msgError = "ERROR: El archivo no subió correctamente.";
		return false;
	}

	if (filesize($tmpfile) > $maxFileSize) {
		$msgError = "ERROR: El archivo no puede ser mayor a ".tamanoArchivo($maxFileSize).".";
		return false;
	}

	if (!move_uploaded_file($tmpfile, $file)) {
		$msgError = "ERROR: El archivo no pudo ser guardado.";
		return false;
	}

	return true;
}

function validar() {
	global $mostrarEnPortada;

	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if (!isset($_POST["tipo"])) {
		echo "errores+= '- El campo Tipo es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["tipo"] == "M")
		if ($_POST["cuerpo"] == "") {
			echo "errores+= '- El campo Cuerpo es obligatorio.<br />';";
			$errores = true;
		}

	if ($mostrarEnPortada == "S") {
		if ($_POST["ubicacion"] == -1) {
			echo "errores+= '- El campo Ubicación es obligatorio.<br />';";
			$errores = true;
		}

		if ($_POST["titulo"] == "") {
			echo "errores+= '- El campo Título es obligatorio.<br />';";
			$errores = true;
		}

		if (($_POST["posicion"] != "") and (!validarEntero($_POST["posicion"]))) {
			echo "errores+= '- El campo Posición debe ser numérico.<br />';";
			$errores = true;
		}

		if ($_POST["vigenciaDesde"] == "") {
			echo "errores+= '- El campo Vigencia Desde es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["vigenciaDesde"])) {
			echo "errores+= '- El campo Vigencia Desde debe ser una fecha válida.<br />';";
			$errores = true;
		}

		if ($_POST["vigenciaHasta"] == "") {
			echo "errores+= '- El campo Vigencia Hasta es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["vigenciaHasta"])) {
			echo "errores+= '- El campo Vigencia Hasta debe ser una fecha válida.<br />';";
			$errores = true;
		}

		if (dateDiff($_POST["vigenciaHasta"], $_POST["vigenciaDesde"]) > 0) {
			echo "errores+= '- La Vigencia Hasta debe ser mayor a la Vigencia Desde.<br />';";
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
	$habilitarComentarios = (isset($_POST["habilitarComentarios"]))?"S":"N";
	$mostrarEnPortada = (isset($_POST["mostrarEnPortada"]))?"S":"N";
	$vistaPrevia = (isset($_POST["vistaPrevia"]))?"S":"N";

	if (!hasPermiso(76))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;

	if ($_POST["destino"] == -1)
		$_POST["destino"] = "_self";

	if ($_POST["ubicacion"] == -1)
		$_POST["ubicacion"] = NULL;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":cuerpo" => $_POST["bajada"],
										":destino" => nullIfCero($_POST["destino"]),
										":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":habilitarcomentarios" => $habilitarComentarios,
										":mostrarenportada" => $mostrarEnPortada,
										":posicion" => zeroIfEmpty($_POST["posicion"]),
										":tipo" => $_POST["tipo"],
										":titulo" => $_POST["titulo"],
										":ubicacion" => $_POST["ubicacion"],
										":usualta" => getWindowsLoginName(true),
										":vistaprevia" => $vistaPrevia,
										":volanta" => $_POST["volanta"]);
		$sql =
			"INSERT INTO web.wai_articulosintranet (ai_cuerpo, ai_destino, ai_fechaalta, ai_fechavigenciadesde, ai_fechavigenciahasta, ai_habilitarcomentarios, ai_id,
																							ai_mostrarenportada, ai_posicion, ai_tipo, ai_titulo, ai_ubicacion, ai_usualta, ai_vistaprevia, ai_volanta)
																			VALUES (:cuerpo, :destino, SYSDATE, TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'), TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'), :habilitarcomentarios, -1,
																							:mostrarenportada, :posicion, :tipo, :titulo, :ubicacion, :usualta, :vistaprevia, :volanta)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(ai_id) FROM web.wai_articulosintranet";
		$_POST["id"] = valorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":cuerpo" => $_POST["bajada"],
										":destino" => $_POST["destino"],
										":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":habilitarcomentarios" => $habilitarComentarios,
										":id" => $_POST["id"],
										":mostrarenportada" => $mostrarEnPortada,
										":posicion" => zeroIfEmpty($_POST["posicion"]),
										":tipo" => $_POST["tipo"],
										":titulo" => $_POST["titulo"],
										":ubicacion" => $_POST["ubicacion"],
										":usumodif" => getWindowsLoginName(true),
										":vistaprevia" => $vistaPrevia,
										":volanta" => $_POST["volanta"]);
		$sql =
			"UPDATE web.wai_articulosintranet
					SET ai_cuerpo = :cuerpo,
							ai_destino = :destino,
							ai_fechabaja = NULL,
							ai_fechamodif = SYSDATE,
							ai_fechavigenciadesde = TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'),
							ai_fechavigenciahasta = TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'),
							ai_habilitarcomentarios = :habilitarcomentarios,
							ai_mostrarenportada = :mostrarenportada,
							ai_posicion = :posicion,
							ai_tipo = :tipo,
							ai_titulo = :titulo,
							ai_ubicacion = :ubicacion,
							ai_usubaja = NULL,
							ai_usumodif = :usumodif,
							ai_vistaprevia = :vistaprevia,
							ai_volanta = :volanta
				WHERE ai_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	if ($_POST["tipo"] == "M") {
		// Guardo el artículo propiamente dicho..
		$blobParamName = "the_clob";
		$sql =
			"UPDATE web.wai_articulosintranet
					SET ai_articulo = EMPTY_CLOB()
				WHERE ai_id = ".$_POST["id"]."
		RETURNING ai_articulo INTO :".$blobParamName;
		DBSaveLob($conn, $sql, $blobParamName, $_POST["cuerpo"], OCI_B_CLOB);
	}

	if ($_POST["tipo"] == "X")
		if ($_FILES["archivo"]["name"] != "") {
			$msgError = "";
			if (subirArchivo($_FILES["archivo"], DATA_ARTICULOS_ARCHIVOS_PATH, array("pdf"), 20971520, $file, $msgError)) {
				$params = array(":id" => $_POST["id"], ":nombrearchivo" => $_FILES["archivo"]["name"]);
				$sql =
					"UPDATE web.wai_articulosintranet
							SET ai_nombrearchivo = :nombrearchivo
						WHERE ai_id = :id";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);
			}
			else
				throw new Exception($msgError);
		}

	// Actualizo el orden de los artículos..
	$params = array(":id" => $_POST["id"],
									":posicion" => zeroIfEmpty($_POST["posicion"]),
									":ubicacion" => $_POST["ubicacion"]);
	$sql =
		"UPDATE web.wai_articulosintranet
				SET ai_posicion = ai_posicion + 1
			WHERE ai_id <> :id
				AND ai_posicion >= :posicion
				AND ai_posicion < 99
				AND ai_ubicacion = :ubicacion
				AND ai_fechabaja IS NULL";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	// Muevo las imagenes..
	moverImagen("c", $_POST["fileImgChica"]);
	moverImagen("g", $_POST["fileImgGrande"]);

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
	showMsgOk('/articulos-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>