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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function tamanoArchivo($fileSize) {
	if ($fileSize < 1024)
		return $fileSize." bytes";
	elseif ($fileSize < 1048576)
		return ($fileSize / 1024)." KB";
	else
		return ($fileSize / 1024 / 1024)." MB";
}

function subirArchivo($arch, $folder, $filename, $extensionesPermitidas, $maxFileSize, &$error, &$finalFilename) {
	$tmpfile = $arch["tmp_name"];
	$partes_ruta = pathinfo(strtolower($arch["name"]));

	if (!in_array($partes_ruta["extension"], $extensionesPermitidas)) {
		$error = "El archivo debe tener alguna de las siguientes extensiones: ".implode(" o ", $extensionesPermitidas).".";
		return false;
	}

	$filename = stringToLower($filename.".".$partes_ruta["extension"]);
	$finalFilename = $folder.$filename;

	if (!is_uploaded_file($tmpfile)) {
		$error = "El archivo no subió correctamente.";
		return false;
	}

	if (filesize($tmpfile) > $maxFileSize) {
		$error = "El archivo no puede ser mayor a ".tamanoArchivo($maxFileSize).".";
		return false;
	}

	if (!move_uploaded_file($tmpfile, $folder.$filename)) {
		$error = "El archivo no pudo ser guardado.";
		return false;
	}

	return true;
}

function validar($filename) {
	global $fileCV;
	global $fileFoto;

	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if (trim($_POST["nombre1"]) == "") {
		echo "errores+= '- Nombre 1 vacío.<br />';";
		$errores = true;
	}

	if (trim($_POST["apellido1"]) == "") {
		echo "errores+= '- Apellido 1 vacío.<br />';";
		$errores = true;
	}

	if (!isFechaValida($_POST["fechaNacimiento"], false)) {
		echo "errores+= '- Fecha de Nacimiento vacía o errónea.<br />';";
		$errores = true;
	}

	if (!isset($_POST["sexo"])) {
		echo "errores+= '- Sexo sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["estadoCivil"] == -1) {
		echo "errores+= '- Estado Civil sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["paisNacimiento"] == -1) {
		echo "errores+= '- País de Nacimiento sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["nacionalidad"] == -1) {
		echo "errores+= '- Nacionalidad sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["tipoDocumento"] == -1) {
		echo "errores+= '- Tipo de Documento sin elegir.<br />';";
		$errores = true;
	}

	if (trim($_POST["numeroDocumento"]) == "") {
		echo "errores+= '- Nro. de Documento vacío.<br />';";
		$errores = true;
	}
	else {
		if (!validarEntero($_POST["numeroDocumento"])) {
			echo "errores+= '- El Nro. de Documento es inválido.<br />';";
			$errores = true;
		}
	}

	if (trim($_POST["telefonoFijo"]) == "") {
		echo "errores+= '- Teléfono Fijo vacío.<br />';";
		$errores = true;
	}

	if (trim($_POST["email"]) != "") {
		$params = array(":email" => $_POST["email"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			echo "errores+= '- El e-Mail es inválido.<br />';";
			$errores = true;
		}
	}

	if (trim($_POST["calle"]) == "") {
		echo "errores+= '- Calle vacía.<br />';";
		$errores = true;
	}

	if (trim($_POST["numeroCalle"]) == "") {
		echo "errores+= '- Número de la Calle vacío.<br />';";
		$errores = true;
	}

	if (trim($_POST["cp"]) == "") {
		echo "errores+= '- CP vacío.<br />';";
		$errores = true;
	}

	if ($_POST["localidad"] == -1) {
		echo "errores+= '- Localidad sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["provincia"] == -1) {
		echo "errores+= '- Provincia sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["pais"] == -1) {
		echo "errores+= '- País sin elegir.<br />';";
		$errores = true;
	}

	if ($_FILES["foto"]["name"] != "") {
		$error = "";
		if (!subirArchivo($_FILES["foto"], DATA_CV_PATH, $filename, array("jpg"), 71680, $error, $fileFoto)) {
			echo "errores+= '- ".$error."<br />';";
			$errores = true;
		}
	}

//------------------------------------------------------------------------------------

	if ($_POST["nivelFormacion1"] == -1) {
		echo "errores+= '- Nivel 1 sin elegir.<br />';";
		$errores = true;
	}

	if (!isset($_POST["completo1"])) {
		echo "errores+= '- Completo 1 sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["titulo1"] == -1) {
		echo "errores+= '- Título 1 sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["institucion1"] == -1) {
		echo "errores+= '- Institución 1 sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["carrera1"] == -1) {
		echo "errores+= '- Carrera 1 sin elegir.<br />';";
		$errores = true;
	}

	if ($_POST["formacion2visible"] == "t") {
		if ($_POST["nivelFormacion2"] == -1) {
			echo "errores+= '- Nivel 2 sin elegir.<br />';";
			$errores = true;
		}

		if (!isset($_POST["completo2"])) {
			echo "errores+= '- Completo 2 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["titulo2"] == -1) {
			echo "errores+= '- Título 2 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["institucion2"] == -1) {
			echo "errores+= '- Institución 2 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["carrera2"] == -1) {
			echo "errores+= '- Carrera 2 sin elegir.<br />';";
			$errores = true;
		}
	}

	if ($_POST["formacion3visible"] == "t") {
		if ($_POST["nivelFormacion3"] == -1) {
			echo "errores+= '- Nivel 3 sin elegir.<br />';";
			$errores = true;
		}

		if (!isset($_POST["completo3"])) {
			echo "errores+= '- Completo 3 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["titulo3"] == -1) {
			echo "errores+= '- Título 3 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["institucion3"] == -1) {
			echo "errores+= '- Institución 3 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["carrera3"] == -1) {
			echo "errores+= '- Carrera 3 sin elegir.<br />';";
			$errores = true;
		}
	}

	if ($_POST["formacion4visible"] == "t") {
		if ($_POST["nivelFormacion4"] == -1) {
			echo "errores+= '- Nivel 4 sin elegir.<br />';";
			$errores = true;
		}

		if (!isset($_POST["completo4"])) {
			echo "errores+= '- Completo 4 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["titulo4"] == -1) {
			echo "errores+= '- Título 4 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["institucion4"] == -1) {
			echo "errores+= '- Institución 4 sin elegir.<br />';";
			$errores = true;
		}

		if ($_POST["carrera4"] == -1) {
			echo "errores+= '- Carrera 4 sin elegir.<br />';";
			$errores = true;
		}
	}

//------------------------------------------------------------------------------------

	if (!isFechaValida($_POST["fechaDesde1"], false)) {
		echo "errores+= '- Fecha desde 1 vacía o errónea.<br />';";
		$errores = true;
	}

	if ($_POST["fechaHasta1"] != "")
		if (!isFechaValida($_POST["fechaHasta1"], false)) {
			echo "errores+= '- Fecha hasta 1 errónea.<br />';";
			$errores = true;
		}

	if (trim($_POST["empresa1"]) == "") {
		echo "errores+= '- Empresa 1 vacía.<br />';";
		$errores = true;
	}

	if (trim($_POST["tareas1"]) == "") {
		echo "errores+= '- Descripción Tareas 1 vacía.<br />';";
		$errores = true;
	}

	if ($_POST["experienciaLaboral2visible"] == "t") {
		if (!isFechaValida($_POST["fechaDesde2"], false)) {
			echo "errores+= '- Fecha desde 2 vacía o errónea.<br />';";
			$errores = true;
		}

		if ($_POST["fechaHasta2"] != "")
			if (!isFechaValida($_POST["fechaHasta2"], false)) {
				echo "errores+= '- Fecha hasta 2 errónea.<br />';";
				$errores = true;
			}

		if (trim($_POST["empresa2"]) == "") {
			echo "errores+= '- Empresa 2 vacía.<br />';";
			$errores = true;
		}

		if (trim($_POST["tareas2"]) == "") {
			echo "errores+= '- Descripción Tareas 2 vacía.<br />';";
			$errores = true;
		}
	}

	if ($_POST["experienciaLaboral3visible"] == "t") {
		if (!isFechaValida($_POST["fechaDesde3"], false)) {
			echo "errores+= '- Fecha desde 3 vacía o errónea.<br />';";
			$errores = true;
		}

		if ($_POST["fechaHasta3"] != "")
			if (!isFechaValida($_POST["fechaHasta3"], false)) {
				echo "errores+= '- Fecha hasta 3 errónea.<br />';";
				$errores = true;
			}

		if (trim($_POST["empresa3"]) == "") {
			echo "errores+= '- Empresa 3 vacía.<br />';";
			$errores = true;
		}

		if (trim($_POST["tareas3"]) == "") {
			echo "errores+= '- Descripción Tareas 3 vacía.<br />';";
			$errores = true;
		}
	}

	if ($_POST["experienciaLaboral4visible"] == "t") {
		if (!isFechaValida($_POST["fechaDesde4"], false)) {
			echo "errores+= '- Fecha desde 4 vacía o errónea.<br />';";
			$errores = true;
		}

		if ($_POST["fechaHasta4"] != "")
			if (!isFechaValida($_POST["fechaHasta4"], false)) {
				echo "errores+= '- Fecha hasta 4 errónea.<br />';";
				$errores = true;
			}

		if (trim($_POST["empresa4"]) == "") {
			echo "errores+= '- Empresa 4 vacía.<br />';";
			$errores = true;
		}

		if (trim($_POST["tareas4"]) == "") {
			echo "errores+= '- Descripción Tareas 4 vacía.<br />';";
			$errores = true;
		}
	}

//------------------------------------------------------------------------------------

	if ($_POST["fechaCurso1"] != "")
		if (!isFechaValida($_POST["fechaCurso1"], false)) {
			echo "errores+= '- Fecha de Curso 1 errónea.<br />';";
			$errores = true;
		}

	if ($_POST["curso2visible"] == "t") {
		if ($_POST["fechaCurso2"] != "")
			if (!isFechaValida($_POST["fechaCurso2"], false)) {
				echo "errores+= '- Fecha de Curso 2 errónea.<br />';";
				$errores = true;
			}
	}

	if ($_POST["curso3visible"] == "t") {
		if ($_POST["fechaCurso3"] != "")
			if (!isFechaValida($_POST["fechaCurso3"], false)) {
				echo "errores+= '- Fecha de Curso 3 errónea.<br />';";
				$errores = true;
			}
	}

	if ($_POST["curso4visible"] == "t") {
		if ($_POST["fechaCurso4"] != "")
			if (!isFechaValida($_POST["fechaCurso4"], false)) {
				echo "errores+= '- Fecha de Curso 4 errónea.<br />';";
				$errores = true;
			}
	}

//------------------------------------------------------------------------------------

	if (trim($_POST["remuneracion"]) != "") {
		if (!validarNumero($_POST["remuneracion"])) {
			echo "errores+= '- La Remuneración pretendida es inválida.<br />';";
			$errores = true;
		}
	}

	if ($_FILES["cv"]["name"] != "") {
		$error = "";
		if (!subirArchivo($_FILES["cv"], DATA_CV_PATH, $filename, array("doc", "docx", "pdf"), 10485760, $error, $fileCV)) {
			echo "errores+= '- ".$error."<br />';";
			$errores = true;
		}
	}

	if (!isset($_SESSION["captcha"])) {
		echo "errores+= '- El captcha es inválido, por favor refrésquelo.<br />';";
		$errores = true;
	}
	elseif ($_POST["captcha"] != $_SESSION["captcha"]) {
		echo "errores+= '- El captcha es erróneo.<br />';";
		$errores = true;
	}


	if ($errores) {
		echo "getElementById('imgEnviar').style.display = 'inline';";
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


try {
	$fileCV = "";
	$fileFoto = "";
	$filename = date("Ymd")."_".$_SERVER["REQUEST_TIME"];

	if (!validar($filename)) {
		if (file_exists($fileCV))
			unlink($fileCV);
		if (file_exists($fileFoto))
			unlink($fileFoto);
		exit;
	}


	switch($_REQUEST["p"]) {
		case "a":
			$procedencia = "WEB_ART";
			break;
		case "b":
			$procedencia = "WEB_BURSATIL";
			break;
		case "f":
			$procedencia = "WEB_FONDOS";
			break;
		case "g":
			$procedencia = "WEB_GRUPO";
			break;
		case "i":
			$procedencia = "WEB_INBA";
			break;
		case "l":
			$procedencia = "WEB_LEASING";
			break;
		case "m":
			$procedencia = "WEB_MANDATOS";
			break;
		case "p":
			$procedencia = "WEB_PAGOS";
			break;
		case "s":
			$procedencia = "WEB_SEGUROS";
			break;
		case "v":
			$procedencia = "WEB_VIDA";
			break;
	}


	$params = array(":tipo_doc" => nullIfCero(substr($_POST["tipoDocumento"], 0, 8)),
									":nro_doc" => nullIfCero(substr($_POST["numeroDocumento"], 0, 30)),
									":nombre_1" => nullIfCero(substr(strtoupper($_POST["nombre1"]), 0, 25)),
									":nombre_2" => nullIfCero(substr(strtoupper($_POST["nombre2"]), 0, 25)),
									":apellido_1" => nullIfCero(substr(strtoupper($_POST["apellido1"]), 0, 25)),
									":apellido_2" => nullIfCero(substr(strtoupper($_POST["apellido2"]), 0, 25)),
									":fec_nacimiento" => nullIfCero($_POST["fechaNacimiento"]),
									":sexo" => $_POST["sexo"],
									":estado_civil" => nullIfCero(substr($_POST["estadoCivil"], 0, 30)),
									":fec_estado_civil" => nullIfCero(""),
									":apellido_casada" => nullIfCero(""),
									":pais_nacimiento" => nullIfCero(substr($_POST["paisNacimiento"], 0, 20)),
									":nacionalidad" => nullIfCero(substr($_POST["nacionalidad"], 0, 20)),
									":fec_ingreso_pais" => nullIfCero(""),
									":cantidad_hijos" => nullIfCero(""),
									":foto_postulante" => nullIfCero(""),
									":referencias" => nullIfCero(""),
									":procedencia" => substr($procedencia, 0, 100),
									":remuneracion_pretendida" => nullIfCero(floatval($_POST["remuneracion"])),
									":telefono_personal" => nullIsEmpty(substr(trim($_POST["telefonoFijo"]), 0, 20)),
									":telefono_celular" => nullIfCero(substr($_POST["telefonoCelular"], 0, 20)),
									":email" => nullIfCero(substr($_POST["email"], 0, 100)),
									":dir_calle" => nullIfCero(substr($_POST["calle"], 0, 30)),
									":dir_nro" => nullIsEmpty(substr($_POST["numeroCalle"], 0, 8)),
									":dir_piso" => nullIfCero(substr($_POST["piso"], 0, 8)),
									":dir_dpto" => nullIfCero(substr($_POST["departamento"], 0, 8)),
									":dir_torre" => nullIfCero(substr($_POST["torre"], 0, 8)),
									":dir_manzana" => nullIfCero(substr($_POST["manzana"], 0, 8)),
									":dir_sector" => nullIfCero(substr($_POST["sector"], 0, 8)),
									":cod_postal" => nullIfCero(substr($_POST["cp"], 0, 12)),
									":localidad" => nullIfCero(substr($_POST["localidad"], 0, 30)),
									":provincia" => nullIfCero(substr($_POST["provincia"], 0, 20)),
									":partido" => nullIfCero(substr($_POST["partido"], 0, 30)),
									":zona" => nullIfCero(""),
									":pais" => nullIfCero(substr($_POST["pais"], 0, 20)),

									":nivel_1" => nullIfCero(substr($_POST["nivelFormacion1"], 0, 40)),
									":completo_1" => $_POST["completo1"],
									":titulo_1" => nullIfCero(substr($_POST["titulo1"], 0, 40)),
									":institucion_1" => nullIfCero(substr($_POST["institucion1"], 0, 100)),
									":carrera_1" => nullIfCero(substr($_POST["carrera1"], 0, 60)),
									":fecha_desde_1" => nullIfCero(""),
									":fecha_hasta_1" => nullIfCero(""),
									":promedio_1" => nullIfCero(""),
									":rango_1" => nullIfCero(""),
									":estudia_actualmente_1" => nullIfCero(""),

									":nivel_2" => nullIfCero(($_POST["formacion2visible"] == "t")?substr($_POST["nivelFormacion2"], 0, 40):""),
									":completo_2" => nullIfCero((($_POST["formacion2visible"] == "t") and (isset($_POST["completo2"])))?$_POST["completo2"]:0),
									":titulo_2" => nullIfCero(($_POST["formacion2visible"] == "t")?substr($_POST["titulo2"], 0, 40):""),
									":institucion_2" => nullIfCero(($_POST["formacion2visible"] == "t")?substr($_POST["institucion2"], 0, 100):""),
									":carrera_2" => nullIfCero(($_POST["formacion2visible"] == "t")?substr($_POST["carrera2"], 0, 60):""),
									":fecha_desde_2" => nullIfCero(""),
									":fecha_hasta_2" => nullIfCero(""),
									":promedio_2" => nullIfCero(""),
									":rango_2" => nullIfCero(""),
									":estudia_actualmente_2" => nullIfCero(""),

									":nivel_3" => nullIfCero(($_POST["formacion3visible"] == "t")?substr($_POST["nivelFormacion3"], 0, 40):""),
									":completo_3" => nullIfCero((($_POST["formacion3visible"] == "t") and (isset($_POST["completo3"])))?$_POST["completo3"]:0),
									":titulo_3" => nullIfCero(($_POST["formacion3visible"] == "t")?substr($_POST["titulo3"], 0, 40):""),
									":institucion_3" => nullIfCero(($_POST["formacion3visible"] == "t")?substr($_POST["institucion3"], 0, 100):""),
									":carrera_3" => nullIfCero(($_POST["formacion3visible"] == "t")?substr($_POST["carrera3"], 0, 60):""),
									":fecha_desde_3" => nullIfCero(""),
									":fecha_hasta_3" => nullIfCero(""),
									":promedio_3" => nullIfCero(""),
									":rango_3" => nullIfCero(""),
									":estudia_actualmente_3" => nullIfCero(""),

									":nivel_4" => nullIfCero(($_POST["formacion4visible"] == "t")?substr($_POST["nivelFormacion4"], 0, 40):""),
									":completo_4" => nullIfCero((($_POST["formacion4visible"] == "t") and (isset($_POST["completo4"])))?$_POST["completo4"]:0),
									":titulo_4" => nullIfCero(($_POST["formacion4visible"] == "t")?substr($_POST["titulo4"], 0, 40):""),
									":institucion_4" => nullIfCero(($_POST["formacion4visible"] == "t")?substr($_POST["institucion4"], 0, 100):""),
									":carrera_4" => nullIfCero(($_POST["formacion4visible"] == "t")?substr($_POST["carrera4"], 0, 60):""),
									":fecha_desde_4" => nullIfCero(""),
									":fecha_hasta_4" => nullIfCero(""),
									":promedio_4" => nullIfCero(""),
									":rango_4" => nullIfCero(""),
									":estudia_actualmente_4" => nullIfCero(""),

									":cargo_anterior_1" => nullIfCero(substr(strtoupper($_POST["cargoAnterior1"]), 0, 50)),
									":empresa_1" => nullIfCero(substr(strtoupper($_POST["empresa1"]), 0, 60)),
									":tarea_desempenada_1" => nullIfCero(substr(strtoupper($_POST["tareas1"]), 0, 200)),
									":fec_desde_1" => nullIfCero($_POST["fechaDesde1"]),
									":fec_hasta_1" => nullIfCero($_POST["fechaHasta1"]),
									":remuneracion_1" => nullIfCero(""),
									":causa_baja_1" => "Desconocido",

									":cargo_anterior_2" => nullIfCero(($_POST["experienciaLaboral2visible"] == "t")?substr(strtoupper($_POST["cargoAnterior2"]), 0, 50):""),
									":empresa_2" => nullIfCero(($_POST["experienciaLaboral2visible"] == "t")?substr(strtoupper($_POST["empresa2"]), 0, 60):""),
									":tarea_desempenada_2" => nullIfCero(($_POST["experienciaLaboral2visible"] == "t")?substr(strtoupper($_POST["tareas2"]), 0, 200):""),
									":fec_desde_2" => nullIfCero(($_POST["experienciaLaboral2visible"] == "t")?$_POST["fechaDesde2"]:""),
									":fec_hasta_2" => nullIfCero(($_POST["experienciaLaboral2visible"] == "t")?$_POST["fechaHasta2"]:""),
									":remuneracion_2" => nullIfCero(""),
									":causa_baja_2" => nullIfCero(($_POST["experienciaLaboral2visible"] == "t")?"Desconocido":""),

									":cargo_anterior_3" => nullIfCero(($_POST["experienciaLaboral3visible"] == "t")?substr(strtoupper($_POST["cargoAnterior3"]), 0, 50):""),
									":empresa_3" => nullIfCero(($_POST["experienciaLaboral3visible"] == "t")?substr(strtoupper($_POST["empresa3"]), 0, 60):""),
									":tarea_desempenada_3" => nullIfCero(($_POST["experienciaLaboral3visible"] == "t")?substr(strtoupper($_POST["tareas3"]), 0, 200):""),
									":fec_desde_3" => nullIfCero(($_POST["experienciaLaboral3visible"] == "t")?$_POST["fechaDesde3"]:""),
									":fec_hasta_3" => nullIfCero(($_POST["experienciaLaboral3visible"] == "t")?$_POST["fechaHasta3"]:""),
									":remuneracion_3" => nullIfCero(""),
									":causa_baja_3" => nullIfCero(($_POST["experienciaLaboral3visible"] == "t")?"Desconocido":""),

									":cargo_anterior_4" => nullIfCero(($_POST["experienciaLaboral4visible"] == "t")?substr(strtoupper($_POST["cargoAnterior4"]), 0, 50):""),
									":empresa_4" => nullIfCero(($_POST["experienciaLaboral4visible"] == "t")?substr(strtoupper($_POST["empresa4"]), 0, 60):""),
									":tarea_desempenada_4" => nullIfCero(($_POST["experienciaLaboral4visible"] == "t")?substr(strtoupper($_POST["tareas4"]), 0, 200):""),
									":fec_desde_4" => nullIfCero(($_POST["experienciaLaboral4visible"] == "t")?$_POST["fechaDesde4"]:""),
									":fec_hasta_4" => nullIfCero(($_POST["experienciaLaboral4visible"] == "t")?$_POST["fechaHasta4"]:""),
									":remuneracion_4" => nullIfCero(""),
									":causa_baja_4" => nullIfCero(($_POST["experienciaLaboral4visible"] == "t")?"Desconocido":""),

									":idioma_1" => nullIfCero(substr($_POST["idioma1"], 0, 30)),
									":lee_nivel_1" => nullIfCero(substr($_POST["leeNivel1"], 0, 30)),
									":habla_nivel_1" => nullIfCero(substr($_POST["hablaNivel1"], 0, 30)),
									":escribe_nivel_1" => nullIfCero(substr($_POST["escribeNivel1"], 0, 30)),

									":idioma_2" => nullIfCero(($_POST["idioma2visible"] == "t")?substr($_POST["idioma2"], 0, 30):""),
									":lee_nivel_2" => nullIfCero(($_POST["idioma2visible"] == "t")?substr($_POST["leeNivel2"], 0, 30):""),
									":habla_nivel_2" => nullIfCero(($_POST["idioma2visible"] == "t")?substr($_POST["hablaNivel2"], 0, 30):""),
									":escribe_nivel_2" => nullIfCero(($_POST["idioma2visible"] == "t")?substr($_POST["escribeNivel2"], 0, 30):""),

									":idioma_3" => nullIfCero(($_POST["idioma3visible"] == "t")?substr($_POST["idioma3"], 0, 30):""),
									":lee_nivel_3" => nullIfCero(($_POST["idioma3visible"] == "t")?substr($_POST["leeNivel3"], 0, 30):""),
									":habla_nivel_3" => nullIfCero(($_POST["idioma3visible"] == "t")?substr($_POST["hablaNivel3"], 0, 30):""),
									":escribe_nivel_3" => nullIfCero(($_POST["idioma3visible"] == "t")?substr($_POST["escribeNivel3"], 0, 30):""),

									":idioma_4" => nullIfCero(($_POST["idioma4visible"] == "t")?substr($_POST["idioma4"], 0, 30):""),
									":lee_nivel_4" => nullIfCero(($_POST["idioma4visible"] == "t")?substr($_POST["leeNivel4"], 0, 30):""),
									":habla_nivel_4" => nullIfCero(($_POST["idioma4visible"] == "t")?substr($_POST["hablaNivel4"], 0, 30):""),
									":escribe_nivel_4" => nullIfCero(($_POST["idioma4visible"] == "t")?substr($_POST["escribeNivel4"], 0, 30):""),

									":desc_curso_1" => nullIfCero(substr(strtoupper($_POST["nombreCurso1"]), 0, 50)),
									":tipo_curso_1" => nullIfCero(substr($_POST["tipoCurso1"], 0, 50)),
									":fec_curso_1" => nullIfCero($_POST["fechaCurso1"]),
									":instituto_1" => nullIfCero(substr($_POST["instituto1"], 0, 200)),

									":desc_curso_2" => nullIfCero(($_POST["curso2visible"] == "t")?substr(strtoupper($_POST["nombreCurso2"]), 0, 50):""),
									":tipo_curso_2" => nullIfCero(($_POST["curso2visible"] == "t")?substr($_POST["tipoCurso2"], 0, 50):""),
									":fec_curso_2" => nullIfCero(($_POST["curso2visible"] == "t")?$_POST["fechaCurso2"]:""),
									":instituto_2" => nullIfCero(($_POST["curso2visible"] == "t")?substr($_POST["instituto2"], 0, 200):""),

									":desc_curso_3" => nullIfCero(($_POST["curso3visible"] == "t")?substr(strtoupper($_POST["nombreCurso3"]), 0, 50):""),
									":tipo_curso_3" => nullIfCero(($_POST["curso3visible"] == "t")?substr($_POST["tipoCurso3"], 0, 50):""),
									":fec_curso_3" => nullIfCero(($_POST["curso3visible"] == "t")?$_POST["fechaCurso3"]:""),
									":instituto_3" => nullIfCero(($_POST["curso3visible"] == "t")?substr($_POST["instituto3"], 0, 200):""),

									":desc_curso_4" => nullIfCero(($_POST["curso4visible"] == "t")?substr(strtoupper($_POST["nombreCurso4"]), 0, 50):""),
									":tipo_curso_4" => nullIfCero(($_POST["curso4visible"] == "t")?substr($_POST["tipoCurso4"], 0, 50):""),
									":fec_curso_4" => nullIfCero(($_POST["curso4visible"] == "t")?$_POST["fechaCurso4"]:""),
									":instituto_4" => nullIfCero(($_POST["curso4visible"] == "t")?substr($_POST["instituto4"], 0, 200):""),

									":esp_tipo_1" => nullIfCero(substr($_POST["tipo1"], 0, 40)),
									":esp_elemento_1" => nullIfCero(substr($_POST["elemento1"], 0, 40)),
									":esp_nivel_1" => nullIfCero(substr($_POST["nivelEspecializacion1"], 0, 40)),

									":esp_tipo_2"=> nullIfCero(($_POST["especializacion2visible"] == "t")?substr($_POST["tipo2"], 0, 40):""),
									":esp_elemento_2" => nullIfCero(($_POST["especializacion2visible"] == "t")?substr($_POST["elemento2"], 0, 40):""),
									":esp_nivel_2" => nullIfCero(($_POST["especializacion2visible"] == "t")?substr($_POST["nivelEspecializacion2"], 0, 40):""),

									":esp_tipo_3"=> nullIfCero(($_POST["especializacion3visible"] == "t")?substr($_POST["tipo3"], 0, 40):""),
									":esp_elemento_3" => nullIfCero(($_POST["especializacion3visible"] == "t")?substr($_POST["elemento3"], 0, 40):""),
									":esp_nivel_3" => nullIfCero(($_POST["especializacion3visible"] == "t")?substr($_POST["nivelEspecializacion3"], 0, 40):""),

									":esp_tipo_4"=> nullIfCero(($_POST["especializacion4visible"] == "t")?substr($_POST["tipo4"], 0, 40):""),
									":esp_elemento_4" => nullIfCero(($_POST["especializacion4visible"] == "t")?substr($_POST["elemento4"], 0, 40):""),
									":esp_nivel_4" => nullIfCero(($_POST["especializacion4visible"] == "t")?substr($_POST["nivelEspecializacion4"], 0, 40):""),

									":foto" => nullIfCero(($_FILES["foto"]["name"] != "")?$filename.".".pathinfo(strtolower($_FILES["foto"]["name"]), PATHINFO_EXTENSION):""),
									":cv" => nullIfCero(($_FILES["cv"]["name"] != "")?$filename.".".pathinfo(strtolower($_FILES["cv"]["name"]), PATHINFO_EXTENSION):""));
	$sql =
		"INSERT INTO rrhh.rcv_curriculumvitae
								(tipo_doc, nro_doc, nombre_1, nombre_2, apellido_1, apellido_2, fec_nacimiento, sexo, estado_civil, fec_estado_civil, apellido_casada,
								 pais_nacimiento, nacionalidad, fec_ingreso_pais, cantidad_hijos, foto_postulante, fecha_ingreso_curriculum, referencias, procedencia, remuneracion_pretendida,
								 telefono_personal, telefono_celular, email, dir_calle, dir_nro, dir_piso, dir_dpto, dir_torre, dir_manzana, dir_sector, cod_postal, localidad, provincia,
								 partido, zona, pais, nivel_1, completo_1, titulo_1, institucion_1, carrera_1, fecha_desde_1, fecha_hasta_1, promedio_1, rango_1, estudia_actualmente_1,
								 nivel_2, completo_2, titulo_2, institucion_2, carrera_2, fecha_desde_2, fecha_hasta_2, promedio_2, rango_2, estudia_actualmente_2, nivel_3, completo_3,
								 titulo_3, institucion_3, carrera_3, fecha_desde_3, fecha_hasta_3, promedio_3, rango_3, estudia_actualmente_3, nivel_4, completo_4, titulo_4, institucion_4,
								 carrera_4, fecha_desde_4, fecha_hasta_4, promedio_4, rango_4, estudia_actualmente_4, cargo_anterior_1, empresa_1, tarea_desempenada_1,
								 fec_desde_1, fec_hasta_1, remuneracion_1, causa_baja_1, cargo_anterior_2, empresa_2, tarea_desempenada_2,
								 fec_desde_2, fec_hasta_2, remuneracion_2, causa_baja_2, cargo_anterior_3, empresa_3, tarea_desempenada_3,
								 fec_desde_3, fec_hasta_3, remuneracion_3, causa_baja_3, cargo_anterior_4, empresa_4, tarea_desempenada_4,
								 fec_desde_4, fec_hasta_4, remuneracion_4, causa_baja_4, idioma_1, lee_nivel_1, habla_nivel_1, escribe_nivel_1, idioma_2, lee_nivel_2,
								 habla_nivel_2, escribe_nivel_2, idioma_3, lee_nivel_3, habla_nivel_3, escribe_nivel_3, idioma_4, lee_nivel_4, habla_nivel_4, escribe_nivel_4, desc_curso_1,
								 tipo_curso_1, fec_curso_1, instituto_1, desc_curso_2, tipo_curso_2, fec_curso_2, instituto_2, desc_curso_3,
								 tipo_curso_3, fec_curso_3, instituto_3, desc_curso_4, tipo_curso_4, fec_curso_4, instituto_4, esp_tipo_1, esp_elemento_1,
								 esp_nivel_1, esp_tipo_2, esp_elemento_2, esp_nivel_2, esp_tipo_3, esp_elemento_3, esp_nivel_3, esp_tipo_4, esp_elemento_4, esp_nivel_4, fecha_alta,
								 foto, cv)
				 VALUES (:tipo_doc, :nro_doc, :nombre_1, :nombre_2, :apellido_1, :apellido_2, TO_DATE(:fec_nacimiento, 'dd/mm/yyyy'), :sexo, :estado_civil, :fec_estado_civil, :apellido_casada,
								 :pais_nacimiento, :nacionalidad, :fec_ingreso_pais, :cantidad_hijos, :foto_postulante, SYSDATE, :referencias, :procedencia, :remuneracion_pretendida,
								 :telefono_personal, :telefono_celular, :email, :dir_calle, :dir_nro, :dir_piso, :dir_dpto, :dir_torre, :dir_manzana, :dir_sector, :cod_postal, :localidad, :provincia,
								 :partido, :zona, :pais, :nivel_1, :completo_1, :titulo_1, :institucion_1, :carrera_1, :fecha_desde_1, :fecha_hasta_1, :promedio_1, :rango_1, :estudia_actualmente_1,
								 :nivel_2, :completo_2, :titulo_2, :institucion_2, :carrera_2, :fecha_desde_2, :fecha_hasta_2, :promedio_2, :rango_2, :estudia_actualmente_2, :nivel_3, :completo_3,
								 :titulo_3, :institucion_3, :carrera_3, :fecha_desde_3, :fecha_hasta_3, :promedio_3, :rango_3, :estudia_actualmente_3, :nivel_4, :completo_4, :titulo_4, :institucion_4,
								 :carrera_4, :fecha_desde_4, :fecha_hasta_4, :promedio_4, :rango_4, :estudia_actualmente_4, :cargo_anterior_1, :empresa_1, :tarea_desempenada_1,
								 TO_DATE(:fec_desde_1, 'dd/mm/yyyy'), :fec_hasta_1, :remuneracion_1, :causa_baja_1, :cargo_anterior_2, :empresa_2, :tarea_desempenada_2,
								 TO_DATE(:fec_desde_2, 'dd/mm/yyyy'), :fec_hasta_2, :remuneracion_2, :causa_baja_2, :cargo_anterior_3, :empresa_3, :tarea_desempenada_3,
								 TO_DATE(:fec_desde_3, 'dd/mm/yyyy'), :fec_hasta_3, :remuneracion_3, :causa_baja_3, :cargo_anterior_4, :empresa_4, :tarea_desempenada_4,
								 TO_DATE(:fec_desde_4, 'dd/mm/yyyy'), :fec_hasta_4, :remuneracion_4, :causa_baja_4, :idioma_1, :lee_nivel_1, :habla_nivel_1, :escribe_nivel_1, :idioma_2, :lee_nivel_2,
								 :habla_nivel_2, :escribe_nivel_2, :idioma_3, :lee_nivel_3, :habla_nivel_3, :escribe_nivel_3, :idioma_4, :lee_nivel_4, :habla_nivel_4, :escribe_nivel_4, :desc_curso_1,
								 :tipo_curso_1, TO_DATE(:fec_curso_1, 'dd/mm/yyyy'), :instituto_1, :desc_curso_2, :tipo_curso_2, TO_DATE(:fec_curso_2, 'dd/mm/yyyy'), :instituto_2, :desc_curso_3,
								 :tipo_curso_3, TO_DATE(:fec_curso_3, 'dd/mm/yyyy'), :instituto_3, :desc_curso_4, :tipo_curso_4, TO_DATE(:fec_curso_4, 'dd/mm/yyyy'), :instituto_4, :esp_tipo_1,
								 :esp_elemento_1, :esp_nivel_1, :esp_tipo_2, :esp_elemento_2, :esp_nivel_2, :esp_tipo_3, :esp_elemento_3, :esp_nivel_3, :esp_tipo_4, :esp_elemento_4, :esp_nivel_4, SYSDATE,
								 :foto, :cv)";
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
?>
<script type="text/javascript">
	alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	with (window.parent.document) {
		getElementById('imgProcesando').style.display = 'none';
		getElementById('imgEnviar').style.display = 'inline';
	}
</script>
<?
	exit;
}
?>
<script src="/js/functions.js" type="text/javascript"></script>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('formEnviarCV').reset();
		getElementById('guardadoOk').style.display = 'block';
		getElementById('imgProcesando').style.display = 'none';
		getElementById('imgEnviar').style.display = 'inline';
	}
	recargarCaptcha(window.parent.document.getElementById('imgCaptcha'));
</script>