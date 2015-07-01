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


function moverImagen($img) {
	global $conn;

	if ($img != "") {
		$fileOrigen = IMAGES_EDICION_PATH.$img;
		$partes_ruta = pathinfo($img);
		$filename = $_POST["id"].".".$partes_ruta["extension"];
		$fileDest = IMAGES_BANNERS_PATH.$_POST["id"]."/".$filename;

		if (!file_exists(IMAGES_BANNERS_PATH.$_POST["id"]))
			makeDirectory(IMAGES_BANNERS_PATH.$_POST["id"]);

		unlink($fileDest);
		if (rename($fileOrigen, $fileDest)) {
			$params = array(":id" => $_POST["id"],
											":imagen" => $filename);
			$sql =
				"UPDATE rrhh.rbr_banners
						SET br_imagen = :imagen
					WHERE br_id = :id";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
		else
			unlink($fileOrigen);
	}
}

function validar($multiLink) {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["id"] == 0)		// Si es un alta valido que suba una imagen..
		if ($_POST["fileImg"] == "") {
			echo "errores+= '- Debe seleccionar una imagen.<br />';";
			$errores = true;
		}

	if (($_POST["link"] != "") and (substr($_POST["link"], 0, 7) != "mailto:"))
		if ($_POST["destino"] == -1) {
		echo "errores+= '- El campo Destino es obligatorio.<br />';";
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

	if ($multiLink == "S") {
		$arrGrupos = array();
		foreach($_REQUEST as $key => $value)
			if (substr($key, 0, 8) == "idGrupo_") {
				$num = substr($key, 8);
				if ((isset($_REQUEST["usuariosGrupo".$num])) and ($_REQUEST["bajaGrupo".$num] == "f"))
					$arrGrupos[] = $num;
			}

			$arrIdUsuarios = array();
			foreach($arrGrupos as $key)
				for ($i=0; $i<count($_REQUEST["usuariosGrupo".$key]); $i++)
					$arrIdUsuarios[] = $_REQUEST["usuariosGrupo".$key][$i];
			$arrIdUsuarios = array_count_values($arrIdUsuarios);

			$arrUsuarios = array();
			foreach($arrIdUsuarios as $key => $value)
				if ($value > 1) {
					$params = array(":id" => $key);
					$sql =
						"SELECT se_nombre
							 FROM use_usuarios
							WHERE se_id = :id";
				$arrUsuarios[] = valorSql($sql, "", $params);
				}

			if (count($arrUsuarios) == 1) {
				echo "errores+= '- El usuario ".implode($arrUsuarios)." está en mas de un grupo.<br />';";
				$errores = true;
			}

			if (count($arrUsuarios) > 1) {
				echo "errores+= '- Los usuarios ".implode(",", $arrUsuarios)." están en mas de un grupo.<br />';";
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
	$multiLink = (isset($_POST["multiLink"]))?"S":"N";
	$vistaPrevia = (isset($_POST["vistaPrevia"]))?"S":"N";

	if (!hasPermiso(89))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar($multiLink))
		exit;

	if ($_POST["id"] == 0) {		// Es un alta..
		$params = array(":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":multilink" => $multiLink,
										":posicion" => zeroIfEmpty($_POST["posicion"]),
										":target" => nullIfCero($_POST["destino"]),
										":url" => $_POST["link"],
										":urlsingrupo" => $_POST["linkSinGrupo"],
										":usualta" => getWindowsLoginName(true),
										":vistaprevia" => $vistaPrevia);
		$sql =
			"INSERT INTO rrhh.rbr_banners (br_fechavigenciadesde, br_fechavigenciahasta, br_id, br_multilink, br_posicion, br_target, br_url, br_urlsingrupo,
																		 br_usualta, br_vistaprevia)
														 VALUES (TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'), TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'), -1, :multilink, :posicion, :target, :url, :urlsingrupo,
																		 :usualta, :vistaprevia)";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		$sql = "SELECT MAX(br_id) FROM rrhh.rbr_banners";
		$_POST["id"] = valorSql($sql, -1, array(), 0);
	}
	else {		// Es una modificación..
		$params = array(":fechavigenciadesde" => $_POST["vigenciaDesde"],
										":fechavigenciahasta" => $_POST["vigenciaHasta"],
										":id" => $_POST["id"],
										":multilink" => $multiLink,
										":posicion" => zeroIfEmpty($_POST["posicion"]),
										":target" => nullIfCero($_POST["destino"]),
										":url" => $_POST["link"],
										":urlsingrupo" => $_POST["linkSinGrupo"],
										":usumodif" => getWindowsLoginName(true),
										":vistaprevia" => $vistaPrevia);
		$sql =
			"UPDATE rrhh.rbr_banners
					SET br_fechamodif = SYSDATE,
							br_fechavigenciadesde = TO_DATE(:fechavigenciadesde, 'DD/MM/YYYY'),
							br_fechavigenciahasta = TO_DATE(:fechavigenciahasta, 'DD/MM/YYYY'),
							br_multilink = :multilink,
							br_posicion = :posicion,
							br_target = :target,
							br_url = :url,
							br_urlsingrupo = :urlsingrupo,
							br_usumodif = :usumodif,
							br_vistaprevia = :vistaprevia
				WHERE br_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}


	// Guardo los grupos..
	if ($multiLink == "S") {
		foreach($_REQUEST as $key => $value)
			if (substr($key, 0, 8) == "idGrupo_") {
				$num = substr($key, 8);
				if ($_REQUEST["bajaGrupo".$num] == "t") {
					$idGrupo = -1;

					$params = array(":id" => $value, ":usubaja" => getWindowsLoginName(true));
					$sql =
						"UPDATE rrhh.rgb_gruposbanners
								SET gb_fechabaja = SYSDATE,
										gb_usubaja = :usubaja
							WHERE gb_id = :id";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}
				else {
					if ($value == -1) {		// Alta..
						$params = array(":idbanner" => $_POST["id"],
														":link" => $_REQUEST["linkGrupo".$num],
														":usualta" => getWindowsLoginName(true));
						$sql =
							"INSERT INTO rrhh.rgb_gruposbanners (gb_fechaalta, gb_idbanner, gb_link, gb_usualta)
																					 VALUES (SYSDATE, :idbanner, :link, :usualta)";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);

						$sql = "SELECT MAX(gb_id) FROM rrhh.rgb_gruposbanners";
						$idGrupo = valorSql($sql, -1, array(), 0);
					}
					else {		// Modificación..
						$idGrupo = $value;

						$params = array(":id" => $idGrupo,
														":link" => $_REQUEST["linkGrupo".$num],
														":usumodif" => getWindowsLoginName(true));
						$sql =
							"UPDATE rrhh.rgb_gruposbanners
									SET gb_fechamodif = SYSDATE,
											gb_link = :link,
											gb_usumodif = :usumodif
								WHERE gb_id = :id";
						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
					}
				}

				// Actualizo usuarios x grupos..
				$params = array(":idgrupobanner" => $idGrupo);
				$sql =
					"DELETE FROM rrhh.rug_usuariosxgruposbanners
								 WHERE ug_idgrupobanner = :idgrupobanner";
				DBExecSql($conn, $sql, $params, OCI_DEFAULT);

				if (isset($_REQUEST["usuariosGrupo".$num]))
					for ($i=0; $i<count($_REQUEST["usuariosGrupo".$num]); $i++) {
						$params = array(":idgrupobanner" => $idGrupo, ":idusuario" => $_REQUEST["usuariosGrupo".$num][$i]);
						$sql =
							"INSERT INTO rrhh.rug_usuariosxgruposbanners (ug_idgrupobanner, ug_idusuario)
																										VALUES (:idgrupobanner, :idusuario)";

						DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}
			}
	}
	else {
		$params = array(":idbanner" => $_POST["id"], ":usubaja" => getWindowsLoginName(true));
		$sql =
			"UPDATE rrhh.rgb_gruposbanners
					SET gb_fechabaja = SYSDATE,
							gb_usubaja = :usubaja
				WHERE gb_fechabaja IS NULL
					AND gb_idbanner = :idbanner";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}

	// Actualizo el orden de los banners..
	$params = array(":id" => $_POST["id"],
									":posicion" => zeroIfEmpty($_POST["posicion"]));
	$sql =
		"UPDATE rrhh.rbr_banners
				SET br_posicion = br_posicion + 1
			WHERE br_id <> :id
				AND br_posicion >= :posicion
				AND br_fechabaja IS NULL";
	DBExecSql($conn, $sql, $params, OCI_DEFAULT);

	// Muevo la imagen..
	if ($_POST["fileImg"] != "")
		moverImagen($_POST["fileImg"]);

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
	showMsgOk('/banners-abm-busqueda/<?= $_POST["id"]?>', window.parent);
</script>