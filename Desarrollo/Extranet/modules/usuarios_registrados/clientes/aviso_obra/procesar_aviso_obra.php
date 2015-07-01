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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


function getActividades() {
	$result = (isset($_POST["excavacion"]))?"S":"N";
	$result.= (isset($_POST["demolicion"]))?"S":"N";
	$result.= (isset($_POST["albanileria"]))?"S":"N";
	$result.= (isset($_POST["ha"]))?"S":"N";
	$result.= (isset($_POST["montajesElectromecanicos"]))?"S":"N";
	$result.= (isset($_POST["instalacionesVarias"]))?"S":"N";
	$result.= (isset($_POST["estructurasMetalicas"]))?"S":"N";
	$result.= (isset($_POST["electricidad"]))?"S":"N";
	$result.= (isset($_POST["ascensores"]))?"S":"N";
	$result.= (isset($_POST["pintura"]))?"S":"N";
	$result.= (isset($_POST["obraMas1000"]))?"S":"N";
	$result.= (isset($_POST["silletas"]))?"S":"N";
	$result.= (isset($_POST["mediosIzaje"]))?"S":"N";
	$result.= (isset($_POST["altaMediaTension"]))?"S":"N";

	return $result;
}

function getDuctos() {
	$result = "";

	if (($_POST["idResolucion"] != 1) or ($_POST["validarObrador"] == "S")) {
		$result = (isset($_POST["tuberias"]))?"S":"N";
		$result.= (isset($_POST["estaciones"]))?"S":"N";
		$result.= (isset($_POST["ductosOtras"]))?"S":"N";
	}

	return $result;
}

function getObrasArquitectura() {
	$result = (isset($_POST["viviendasUnifamiliares"]))?"S":"N";
	$result.= (isset($_POST["edificiosPisosMultiples"]))?"S":"N";
	$result.= (isset($_POST["obrasUrbanizacion"]))?"S":"N";
	$result.= (isset($_POST["edificiosComerciales"]))?"S":"N";
	$result.= (isset($_POST["edificiosOficina"]))?"S":"N";
	$result.= (isset($_POST["escuelas"]))?"S":"N";
	$result.= (isset($_POST["hospitales"]))?"S":"N";
	$result.= (isset($_POST["otrasEdific"]))?"S":"N";

	return $result;
}

function getObrasIngenieriaCivil() {
	$result = (isset($_POST["caminos"]))?"S":"N";
	$result.= (isset($_POST["calles"]))?"S":"N";
	$result.= (isset($_POST["autopistas"]))?"S":"N";
	$result.= (isset($_POST["puentes"]))?"S":"N";
	$result.= (isset($_POST["tuneles"]))?"S":"N";
	$result.= (isset($_POST["obrasFerroviarias"]))?"S":"N";
	$result.= (isset($_POST["obrasHidraulicas"]))?"S":"N";
	$result.= (isset($_POST["tratamientoAgua"]))?"S":"N";
	$result.= (isset($_POST["puertos"]))?"S":"N";
	$result.= (isset($_POST["aeropuertos"]))?"S":"N";
	$result.= (isset($_POST["otras"]))?"S":"N";

	return $result;
}

function getObrasMontajeIndustrial() {
	$result = (isset($_POST["destileria"]))?"S":"N";
	$result.= (isset($_POST["generacionElectrica"]))?"S":"N";
	$result.= (isset($_POST["obrasMineria"]))?"S":"N";
	$result.= (isset($_POST["industriaManufactureraUrbana"]))?"S":"N";
	$result.= (isset($_POST["demasMontajesIndustriales"]))?"S":"N";

	return $result;
}

function getObrasRedes() {
	$result = (isset($_POST["transElectricaAltoVoltaje"]))?"S":"N";
	$result.= (isset($_POST["transElectricaBajoVoltaje"]))?"S":"N";
	$result.= (isset($_POST["comunicaciones"]))?"S":"N";
	$result.= (isset($_POST["otrasObrasRedes"]))?"S":"N";

	return $result;
}

function getOtrasConstrucciones() {
	$result = (isset($_POST["excavacionesSubterraneas"]))?"S":"N";
	$result.= (isset($_POST["instalacionesHidraulicas"]))?"S":"N";
	$result.= (isset($_POST["instalacionesElectromecanicas"]))?"S":"N";
	$result.= (isset($_POST["instalacionesAireAcondicionado"]))?"S":"N";
	$result.= (isset($_POST["reparaciones"]))?"S":"N";
	$result.= (isset($_POST["otrasObras"]))?"S":"N";

	return $result;
}

function getTipoObra($tipo) {
	switch ($tipo) {
		case "e":
			$titulo = "extensi�n";
			break;
		case "m":
			$titulo = "inicio";
			break;
		case "n":
			$titulo = "inicio";
			break;
		case "p":
			$titulo = "presentaci�n";
			break;
		case "s":
			$titulo = "suspensi�n";
			break;
		case "sd":
			$titulo = "suspensi�n definitiva";
			break;
	}
}

function tieneSoloNumeros($cadena) {
	for ($i=0; $i < strlen($cadena); $i++)
		if (!validarEntero($cadena[$i]))
		return false;

	return true;
}

function validar() {
	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	/* Inicio - BLOQUE OBRADOR */
	if ($_POST["validarObrador"] == "S") {
		if ($_POST["idObrador"] == -1) {
			echo "errores+= '[OBRADOR] - Debe seleccionar al obrador.<br />';";
			$errores = true;
		}

		if (($_POST["idObrador"] > 0) and ($_POST["caracteristicasObrador"] == "")) {
			echo "errores+= '[OBRADOR] - Debe contestar la pregunta.<br />';";
			$errores = true;
		}
	}
	/* Fin - BLOQUE OBRADOR */


	/* Inicio - BLOQUE DATOS DE LA OBRA */
	if ($_POST["calle"] == "") {
		echo "errores+= '[DATOS DE LA OBRA] - El campo Domicilio es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["numero"] == "") {
		echo "errores+= '[DATOS DE LA OBRA] - El campo N�/KM es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["observaciones"] == "") {
		echo "errores+= '[DATOS DE LA OBRA] - Debe indicar la Descripci�n detallada del tipo de obra.<br />';";
		$errores = true;
	}
	/* Fin - BLOQUE DATOS DE LA OBRA */


	// Validaci�n 2..
	if ($_POST["fechaInicioEnabled"] == "t") {
		if ($_POST["fechaInicio"] == "") {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Inicio es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["fechaInicio"])) {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Inicio debe ser una fecha v�lida.<br />';";
			$errores = true;
		}
	}

	// Validaci�n 2..
	if ($_POST["fechaFinalizacionEnabled"] == "t") {
		if ($_POST["fechaFinalizacion"] == "") {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Finalizaci�n es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["fechaFinalizacion"])) {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Finalizaci�n debe ser una fecha v�lida.<br />';";
			$errores = true;
		}
	}

	// Validaci�n 6..
	if (dateDiff($_POST["fechaInicio"], $_POST["fechaFinalizacion"]) < 0) {
		echo "errores+= '[DATOS GENERALES] - La Fecha de Inicio debe ser menor o igual a la Fecha de Finalizaci�n.<br />';";
		$errores = true;
	}

	// Validaci�n 6.1..
	if ($_POST["tipoForm"] == 0)		// Si es el formulario corto de la Res. 319..
		if (dateDiff($_POST["fechaInicio"], $_POST["fechaFinalizacion"]) > 6) {
			echo "errores+= '[DATOS GENERALES] - La Fecha de Finalizaci�n no puede ser mas de seis d�as posterior a la Fecha de Inicio.<br />';";
			$errores = true;
		}

	if ($_POST["fechaSuspensionEnabled"] == "t") {
		// Validaci�n 2..
		if ($_POST["fechaSuspension"] == "") {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Suspensi�n es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["fechaSuspension"])) {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Suspensi�n debe ser una fecha v�lida.<br />';";
			$errores = true;
		}

		// Validaci�n 6..
		if (dateDiff($_POST["fechaInicio"], $_POST["fechaSuspension"]) < 0) {
			echo "errores+= '[DATOS GENERALES] - La Fecha de Inicio debe ser menor o igual a la Fecha de Suspensi�n.<br />';";
			$errores = true;
		}

		// Validaci�n 9..
		if ($_POST["fechaExtensionEnabled"] == "t") {
			if ((dateDiff($_POST["fechaInicio"], $_POST["fechaSuspension"]) < 0) and (dateDiff($_POST["fechaExtension"], $_POST["fechaSuspension"]) < 0)) {
				echo "errores+= '[DATOS GENERALES] - La Fecha de Suspensi�n debe ser mayor a la Fecha de Inicio o a la Fecha de Extensi�n.<br />';";
				$errores = true;
			}

			if ((dateDiff($_POST["fechaSuspension"], $_POST["fechaFinalizacion"]) < 0) and (dateDiff($_POST["fechaSuspension"], $_POST["fechaExtension"]) < 0)) {
				echo "errores+= '[DATOS GENERALES] - La Fecha de Suspensi�n debe ser menor a la Fecha de Finalizaci�n o a la Fecha de Extensi�n.<br />';";
				$errores = true;
			}
		}
		else {
			if ($_POST["fechaExtension"] == "") {
				if (dateDiff($_POST["fechaSuspension"], $_POST["fechaFinalizacion"]) < 0) {
					echo "errores+= '[DATOS GENERALES] - La Fecha de Suspensi�n debe ser menor a la Fecha de Finalizaci�n.<br />';";
					$errores = true;
				}
			}
			else {
				if (dateDiff($_POST["fechaSuspension"], $_POST["fechaExtension"]) < 0) {
					echo "errores+= '[DATOS GENERALES] - La Fecha de Suspensi�n debe ser menor a la Fecha de Extensi�n.<br />';";
					$errores = true;
				}
			}
		}
	}

	if ($_POST["fechaReinicioEnabled"] == "t") {
		// Validaci�n 2..
		if ($_POST["fechaReinicio"] == "") {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Reinicio es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["fechaReinicio"])) {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Reinicio debe ser una fecha v�lida.<br />';";
			$errores = true;
		}

		// Validaci�n 6..
		if (dateDiff($_POST["fechaInicio"], $_POST["fechaReinicio"]) < 0) {
			echo "errores+= '[DATOS GENERALES] - La Fecha de Inicio debe ser menor o igual a la Fecha de Reinicio.<br />';";
			$errores = true;
		}

		// Validaci�n 6.1..
		if ($_POST["fechaExtension"] == "") {
			if (dateDiff($_POST["fechaReinicio"], $_POST["fechaFinalizacion"]) < 0) {
				echo "errores+= '[DATOS GENERALES] - La Fecha de Fin debe ser mayor o igual a la Fecha de Reinicio.<br />';";
				$errores = true;
			}
		}
		else {
			if (dateDiff($_POST["fechaReinicio"], $_POST["fechaExtension"]) < 0) {
				echo "errores+= '[DATOS GENERALES] - La Fecha de Extensi�n debe ser mayor o igual a la Fecha de Reinicio.<br />';";
				$errores = true;
			}
		}

		// Validaci�n 8..
		if (($_POST["fechaSuspensionEnabled"] == "t") and (dateDiff($_POST["fechaSuspension"], $_POST["fechaReinicio"]) < 0)) {
			echo "errores+= '[DATOS GENERALES] - La Fecha de Reinicio debe ser mayor a la Fecha de Suspensi�n.<br />';";
			$errores = true;
		}
	}

	// Validaci�n 3..
	if (($_POST["fechaExtensionEnabled"] == "t") and ($_POST["tipoFormulario"] != "R") and ($_POST["origen"] != "r")) {
		if($_POST["fechaExtension"] == "") {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Extensi�n es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["fechaExtension"])) {
			echo "errores+= '[DATOS GENERALES] - El campo Fecha de Extensi�n debe ser una fecha v�lida.<br />';";
			$errores = true;
		}
	}

	if ($_POST["fechaExtensionEnabled"] == "t") {
		// Validaci�n 6..
		if (dateDiff($_POST["fechaInicio"], $_POST["fechaExtension"]) < 0) {
			echo "errores+= '[DATOS GENERALES] - La Fecha de Inicio debe ser menor o igual a la Fecha de Extensi�n.<br />';";
			$errores = true;
		}

		// Validaci�n 7..
		if (dateDiff($_POST["fechaFinalizacion"], $_POST["fechaExtension"]) < 0) {
			echo "errores+= '[DATOS GENERALES] - La Fecha de Extensi�n debe ser mayor a la Fecha de Finalizaci�n.<br />';";
			$errores = true;
		}
	}

	if ($_POST["caracteristicasObrador"] != "N") {
		// Validaci�n 4..
		if ($_POST["superficieConstruir"] == "") {
			echo "errores+= '[DATOS GENERALES] - El campo Superficie a Construir es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 4..
		if (!validarEntero($_POST["superficieConstruir"], true)) {
			echo "errores+= '[DATOS GENERALES] - El campo Superficie a Construir debe ser n�mero entero.<br />';";
			$errores = true;
		}

		// Validaci�n 4..
		if ($_POST["numeroPlantas"] == "") {
			echo "errores+= '[DATOS GENERALES] - El campo N�mero de Plantas es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 4..
		if (!validarEntero($_POST["numeroPlantas"])) {
			echo "errores+= '[DATOS GENERALES] - El campo N�mero de Plantas debe ser un n�mero entero.<br />';";
			$errores = true;
		}
	}

	if ($_POST["caracteristicasObrador"] != "N") {
		// Validaci�n 5..
		if ((!isset($_POST["caminos"])) and (!isset($_POST["tuneles"])) and (!isset($_POST["puertos"])) and (!isset($_POST["calles"])) and (!isset($_POST["obrasFerroviarias"])) and
				(!isset($_POST["aeropuertos"])) and (!isset($_POST["autopistas"])) and (!isset($_POST["obrasHidraulicas"])) and (!isset($_POST["otras"])) and (!isset($_POST["puentes"]))and
				(!isset($_POST["tratamientoAgua"])) and (!isset($_POST["viviendasUnifamiliares"])) and (!isset($_POST["edificiosOficina"])) and (!isset($_POST["edificiosPisosMultiples"])) and
				(!isset($_POST["escuelas"])) and (!isset($_POST["obrasUrbanizacion"])) and (!isset($_POST["hospitales"])) and (!isset($_POST["edificiosComerciales"])) and
				(!isset($_POST["otrasEdific"])) and (!isset($_POST["destileria"])) and (!isset($_POST["generacionElectrica"])) and (!isset($_POST["obrasMineria"])) and
				(!isset($_POST["industriaManufactureraUrbana"])) and (!isset($_POST["demasMontajesIndustriales"])) and (!isset($_POST["tuberias"])) and (!isset($_POST["estaciones"])) and
				(!isset($_POST["ductosOtras"])) and (!isset($_POST["transElectricaAltoVoltaje"])) and (!isset($_POST["transElectricaBajoVoltaje"])) and (!isset($_POST["comunicaciones"])) and
				(!isset($_POST["otrasObrasRedes"])) and (!isset($_POST["excavacionesSubterraneas"])) and (!isset($_POST["instalacionesHidraulicas"])) and
				(!isset($_POST["instalacionesElectromecanicas"])) and (!isset($_POST["instalacionesAireAcondicionado"])) and (!isset($_POST["reparaciones"])) and (!isset($_POST["otrasObras"]))) {
			echo "errores+= '- Debe seleccionar alg�n item de los grupos INGENIER�A CIVIL, ARQUITECTURA, MONTAJE INDUSTRIAL, DUCTOS, REDES u OTRAS CONSTRUCCIONES.<br />';";
			$errores = true;
		}


		if (($_POST["fechaDesdeExcavacion"] != "") and (!isFechaValida($_POST["fechaDesdeExcavacion"]))) {
			echo "errores+= '[ACTIVIDAD] - El campo Excavaci�n Fecha Desde debe ser una fecha v�lida.<br />';";
			$errores = true;
		}

		if (($_POST["fechaHastaExcavacion"] != "") and (!isFechaValida($_POST["fechaHastaExcavacion"]))) {
			echo "errores+= '[ACTIVIDAD] - El campo Excavaci�n Fecha Hasta debe ser una fecha v�lida.<br />';";
			$errores = true;
		}

		if (($_POST["fechaDesdeDemolicion"] != "") and (!isFechaValida($_POST["fechaDesdeDemolicion"]))) {
			echo "errores+= '[ACTIVIDAD] - El campo Demolici�n Fecha Desde debe ser una fecha v�lida.<br />';";
			$errores = true;
		}

		if (($_POST["fechaHastaDemolicion"] != "") and (!isFechaValida($_POST["fechaHastaDemolicion"]))) {
			echo "errores+= '[ACTIVIDAD] - El campo Demolici�n Fecha Hasta debe ser una fecha v�lida.<br />';";
			$errores = true;
		}
		
		// Validaci�n 10..
		if (isset($_POST["excavacion"])) {
			if ($_POST["fechaDesdeExcavacion"] == "") {
				echo "errores+= '[ACTIVIDAD] - El campo Excavaci�n Fecha Desde es obligatorio.<br />';";
				$errores = true;
			}

			if ($_POST["fechaHastaExcavacion"] == "") {
				echo "errores+= '[ACTIVIDAD] - El campo Excavaci�n Fecha Hasta es obligatorio.<br />';";
				$errores = true;
			}
			
			if (!isset($_POST["submuraciones"]) and !isset($_POST["subsuelos"])) {
				echo "errores+= '[ACTIVIDAD] - El campo submuraci�n o subsuelo debe estar seleccionado, si esta seleccionado excavaci�n.<br />';";
				$errores = true;
			}
			
			
			if (dateDiff($_POST["fechaDesdeExcavacion"], $_POST["fechaHastaExcavacion"]) < 0) {
				echo "errores+= '[ACTIVIDAD] - La Fecha Desde Excavaci�n debe ser menor a la Fecha Hasta Excavaci�n.<br />';";
				$errores = true;
			}

			// Validaci�n 11..
			if (dateDiff($_POST["fechaInicio"], $_POST["fechaDesdeExcavacion"]) < 0) {
				echo "errores+= '[ACTIVIDAD] - La Fecha Desde Excavaci�n debe ser mayor o igual a la Fecha de Inicio.<br />';";
				$errores = true;
			}

			// Validaci�n 12..
			if ($_POST["fechaExtension"] != "") {
				if (dateDiff($_POST["fechaExtension"], $_POST["fechaDesdeExcavacion"]) < 0) {
					echo "errores+= '[ACTIVIDAD] - La Fecha Desde Excavaci�n debe ser menor o igual a la Fecha de Extensi�n.<br />';";
					$errores = true;
				}
			}
			else {
				if (dateDiff($_POST["fechaFinalizacion"], $_POST["fechaDesdeExcavacion"]) > 0) {
					echo "errores+= '[ACTIVIDAD] - La Fecha Desde Excavaci�n debe ser menor o igual a la Fecha de Finalizaci�n.<br />';";
					$errores = true;
				}
			}

			// Validaci�n 13..
			if ($_POST["fechaExtension"] != "") {
				if (dateDiff($_POST["fechaExtension"], $_POST["fechaHastaExcavacion"]) < 0) {
					echo "errores+= '[ACTIVIDAD] - La Fecha Hasta Excavaci�n debe ser menor o igual a la Fecha de Extensi�n.<br />';";
					$errores = true;
				}
			}
			else {
				if (dateDiff($_POST["fechaFinalizacion"], $_POST["fechaHastaExcavacion"]) > 0) {
					echo "errores+= '[ACTIVIDAD] - La Fecha Hasta Excavaci�n debe ser menor o igual a la Fecha de Finalizaci�n.<br />';";
					$errores = true;
				}
			}
		}

		// Validaci�n 14..
		if (isset($_POST["demolicion"])) {
			if ($_POST["fechaDesdeDemolicion"] == "") {
				echo "errores+= '[ACTIVIDAD] - El campo Demolici�n Fecha Desde es obligatorio.<br />';";
				$errores = true;
			}

			if ($_POST["fechaHastaDemolicion"] == "") {
				echo "errores+= '[ACTIVIDAD] - El campo Demolici�n Fecha Hasta es obligatorio.<br />';";
				$errores = true;
			}

			if (dateDiff($_POST["fechaDesdeDemolicion"], $_POST["fechaHastaDemolicion"]) < 0) {
				echo "errores+= '[ACTIVIDAD] - La Fecha Desde Demolici�n debe ser menor a la Fecha Hasta Demolici�n.<br />';";
				$errores = true;
			}
			
			if (!isset($_POST["total"]) and !isset($_POST["parcial"])) {
				echo "errores+= '[ACTIVIDAD] - El campo total o parcial debe estar seleccionado, si esta seleccionado demolici�n.<br />';";
				$errores = true;
			}

			// Validaci�n 15..
			if (dateDiff($_POST["fechaInicio"], $_POST["fechaDesdeDemolicion"]) < 0) {
				echo "errores+= '[ACTIVIDAD] - La Fecha Desde Demolici�n debe ser mayor o igual a la Fecha de Inicio.<br />';";
				$errores = true;
			}

			// Validaci�n 16..
			if ($_POST["fechaExtension"] != "") {
				if (dateDiff($_POST["fechaExtension"], $_POST["fechaDesdeDemolicion"]) < 0) {
					echo "errores+= '[ACTIVIDAD] - La Fecha Desde Demolici�n debe ser menor o igual a la Fecha de Extensi�n.<br />';";
					$errores = true;
				}
			}
			else {
				if (dateDiff($_POST["fechaFinalizacion"], $_POST["fechaDesdeDemolicion"]) > 0) {
					echo "errores+= '[ACTIVIDAD] - La Fecha Desde Demolici�n debe ser menor o igual a la Fecha de Finalizaci�n.<br />';";
					$errores = true;
				}
			}

			// Validaci�n 17..
			if ($_POST["fechaExtension"] != "") {
				if (dateDiff($_POST["fechaExtension"], $_POST["fechaHastaDemolicion"]) < 0) {
					echo "errores+= '[ACTIVIDAD] - La Fecha Hasta Demolici�n debe ser menor o igual a la Fecha de Extensi�n.<br />';";
					$errores = true;
				}
			}
			else {
				if (dateDiff($_POST["fechaFinalizacion"], $_POST["fechaHastaDemolicion"]) > 0) {
					echo "errores+= '[ACTIVIDAD] - La Fecha Hasta Demolici�n debe ser menor o igual a la Fecha de Finalizaci�n.<br />';";
					$errores = true;
				}
			}
		}

		// Validaci�n 17.1..
		if (isset($_POST["excavacion503"])) {
			if ($_POST["fechaDesdeExcavacion503"] == "") {
				echo "errores+= '[ACTIVIDAD] - El campo Fecha Desde de Otras Excavaciones es obligatorio.<br />';";
				$errores = true;
			}
			if ($_POST["fechaHastaExcavacion503"] == "") {
				echo "errores+= '[ACTIVIDAD] - El campo Fecha Hasta de Otras Excavaciones es obligatorio.<br />';";
				$errores = true;
			}
			if (dateDiff($_POST["fechaDesdeExcavacion503"], $_POST["fechaHastaExcavacion503"]) < 0) {
				echo "errores+= '[ACTIVIDAD] - El campo Fecha Hasta de Otras Excavaciones debe ser posterior al campo Fecha Desde de Otras Excavaciones.<br />';";
				$errores = true;
			}
			if ($_POST["detallarExcavacion503"] == "") {
				echo "errores+= '[ACTIVIDAD] - El campo Detallar de Otras Excavaciones es obligatorio.<br />';";
				$errores = true;
			}
		}


		// Validaci�n 18..
		if (isset($_POST["comitente"])) {
			if (($_POST["cuitComitente"] == "") and ($_POST["razonSocialComitente"] == "")) {
				echo "errores+= '[COMITENTE - CONTRATISTA] - Debe ingresar la C.U.I.T. o la Raz�n Social del Comitente.<br />';";
				$errores = true;
			}

			if (($_POST["cuitComitente"] != "") and (!validarCuit($_POST["cuitComitente"]))) {
				echo "errores+= '[COMITENTE - CONTRATISTA] - La C.U.I.T. del Comitente es inv�lida.<br />';";
				$errores = true;
			}
		}

		// Validaci�n 19..
		if (isset($_POST["contratistaPrincipal"])) {
			if (($_POST["cuitContratistaPrincipal"] == "") and ($_POST["razonSocialContratistaPrincipal"] == "")) {
				echo "errores+= '[COMITENTE - CONTRATISTA] - Debe ingresar la C.U.I.T. o la Raz�n Social del Contratista Principal.<br />';";
				$errores = true;
			}

			if (($_POST["cuitContratistaPrincipal"] != "") and (!validarCuit($_POST["cuitContratistaPrincipal"]))) {
				echo "errores+= '[COMITENTE - CONTRATISTA] - La C.U.I.T. del Contratista Principal es inv�lida.<br />';";
				$errores = true;
			}
		}

		// Validaci�n 20..
		if (isset($_POST["subcontratista"])) {
			if (($_POST["cuitSubcontratista"] == "") and ($_POST["razonSocialSubcontratista"] == "")) {
				echo "errores+= '[COMITENTE - CONTRATISTA] - Debe ingresar la C.U.I.T. o la Raz�n Social del Subcontratista.<br />';";
				$errores = true;
			}

			if (($_POST["cuitSubcontratista"] != "") and (!validarCuit($_POST["cuitSubcontratista"]))) {
				echo "errores+= '[COMITENTE - CONTRATISTA] - La C.U.I.T. del Subcontratista es inv�lida.<br />';";
				$errores = true;
			}
		}

		// Validaci�n 21..
		if ($_POST["tipoDocumentoHYS"] == -1) {
			echo "errores+= '[RESPONSABLE HYS] - El campo Tipo Documento es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 21..
		if ($_POST["numeroDocumentoHYS"] == "") {
			echo "errores+= '[RESPONSABLE HYS] - El campo N� Documento es obligatorio.<br />';";
			$errores = true;
		}
		else if (($_POST["tipoDocumentoHYS"] == "CUIL") and (!validarCuit($_POST["numeroDocumentoHYS"]))) {
			echo "errores+= '[RESPONSABLE HYS] - El campo N� Documento no es una C.U.I.L. v�lida.<br />';";
			$errores = true;
		}
		else if (($_POST["tipoDocumentoHYS"] == "CUIT") and (!validarCuit($_POST["numeroDocumentoHYS"]))) {
			echo "errores+= '[RESPONSABLE HYS] - El campo N� Documento no es una C.U.I.T. v�lida.<br />';";
			$errores = true;
		}

		// Validaci�n 21..
		if ($_POST["sexoHYS"] == -1) {
			echo "errores+= '[RESPONSABLE HYS] - El campo Sexo es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 21..
		if ($_POST["nombreApellidoHYS"] == "") {
			echo "errores+= '[RESPONSABLE HYS] - El campo Nombre y Apellido es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 21..
		if ($_POST["cargoHYS"] == -1) {
			echo "errores+= '[RESPONSABLE HYS] - El campo Cargo es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 21..
		if ($_POST["emailHYS"] == "") {
			echo "errores+= '[RESPONSABLE HYS] - El campo e-Mail es obligatorio.<br />';";
			$errores = true;
		}
		else {
			$params = array(":email" => $_POST["emailHYS"]);
			$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
			if (ValorSql($sql, "", $params) != "S") {
				echo "errores+= '[RESPONSABLE HYS] - El e-Mail cargado debe ser v�lido.<br />';";
				$errores = true;
			}
		}

		// Validaci�n 21..
		if ($_POST["telefonosCargados"] != "t") {
			echo "errores+= '[RESPONSABLE HYS] - Debe tener cargado al menos un tel�fono.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["tipoDocumento"] == -1) {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo Tipo Documento es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["numeroDocumento"] == "") {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo N� Documento es obligatorio.<br />';";
			$errores = true;
		}
		else if (($_POST["tipoDocumento"] == "CUIL") and (!validarCuit($_POST["numeroDocumento"]))) {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo N� Documento no es una C.U.I.L. v�lida.<br />';";
			$errores = true;
		}
		else if (($_POST["tipoDocumento"] == "CUIT") and (!validarCuit($_POST["numeroDocumento"]))) {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo N� Documento no es una C.U.I.T. v�lida.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["sexo"] == -1) {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo Sexo es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["nombre"] == "") {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo Nombre es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["apellido"] == "") {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo Apellido es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["codigoArea"] == "") {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo C�digo �rea es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["telefono"] == "") {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo Tel�fono es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if (!tieneSoloNumeros($_POST["telefono"])) {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo Tel�fono solo puede contener caracteres num�ricos.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["tipoTelefono"] == -1) {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo Tipo Tel�fono es obligatorio.<br />';";
			$errores = true;
		}

		// Validaci�n 22..
		if ($_POST["email"] == "") {
			echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El campo e-Mail es obligatorio.<br />';";
			$errores = true;
		}
		else {
			$params = array(":email" => $_POST["email"]);
			$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
			if (valorSql($sql, "", $params) != "S") {
				echo "errores+= '[RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO] - El e-Mail cargado debe ser v�lido.<br />';";
				$errores = true;
			}
		}
	}

	if ($errores) {
		echo "getElementById('imgProcesando').style.display = 'none';";
		echo "getElementById('btnGuardar').style.display = 'inline';";
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


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 95));

try {
	if ((!isset($_POST["idObrador"])) or ($_POST["idObrador"] == ""))
		$_POST["idObrador"] = -1;

	if ($_POST["idAvisoObra"] == "")
		$_POST["idAvisoObra"] = -1;

	if (!isset($_POST["cargoHYS"]))
		$_POST["cargoHYS"] = $_POST["cargoHYSTmp"];
	if (!isset($_POST["sexoHYS"]))
		$_POST["sexoHYS"] = $_POST["sexoHYSTmp"];
	if (!isset($_POST["tipoDocumentoHYS"]))
		$_POST["tipoDocumentoHYS"] = $_POST["tipoDocumentoHYSTmp"];



	$_POST["cuitContratistaPrincipal"] = trim($_POST["cuitContratistaPrincipal"]);

	if (!validar())
		exit;


	$curs = null;
	$params = array(":calle" => $_POST["calle"],
									":codprov" => $_POST["idProvincia"],
									":contrato" => $_SESSION["contrato"],
									":estableci" => $_POST["numeroEstablecimiento"],
									":cpa" => $_POST["cpa"],
									":cpostal" => $_POST["codigoPostal"],
									":desctipoobra" => $_POST["observaciones"],
									":fechafin" => $_POST["fechaFinalizacion"],
									":fechainicio" => $_POST["fechaInicio"],
									":fextension" => $_POST["fechaExtension"],
									":freinicio" => $_POST["fechaReinicio"],
									":fsuspension" => $_POST["fechaSuspension"],
									":idavisoobraweb" => nullIfCero($_POST["idAvisoObraWeb"]),
									":idestab319" => nullIfCero($_POST["idObrador"]),
									":idresolucion" => nullIfCero($_POST["idResolucion"]),
									":localidad" => $_POST["localidad"],
									":numero" => $_POST["numero"],
									":plantas" => $_POST["numeroPlantas"],
									":superficie" => $_POST["superficieConstruir"],
									":tipo" => $_POST["tipoFormulario"],
									":tipoform" => $_POST["tipoForm"],
									":usuario" => $_SESSION["usuario"]);
	$sql = "BEGIN art.hys_avisoobraweb.do_guardaravisodeobra(:data, :idavisoobraweb, :contrato, :estableci, TO_DATE(:fechainicio, 'DD/MM/YYYY'), TO_DATE(:fechafin, 'DD/MM/YYYY'), TO_DATE(:fextension, 'DD/MM/YYYY'), TO_DATE(:fsuspension, 'DD/MM/YYYY'), TO_DATE(:freinicio, 'DD/MM/YYYY'), :calle, :numero, :localidad, :codprov, :cpostal, :cpa, :desctipoobra, :superficie, :plantas, :tipo, :idresolucion, :idestab319, :tipoform, :usuario); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params, true, 0);
	$row = DBGetSP($curs);

	$curs = null;
	$params = array(":idavisoobraweb" => $row["ID"],
									":obrasdearquitectura" => getObrasArquitectura(),
									":obrasdeductos" => getDuctos(),
									":obrasderedes" => getObrasRedes(),
									":obrasingcivil" => getObrasIngenieriaCivil(),
									":obrasmontajeindustrial" => getObrasMontajeIndustrial(),
									":otrasconstrucciones" => getOtrasConstrucciones(),
									":usuario" => $_SESSION["usuario"]);
	$sql = "BEGIN art.hys_avisoobraweb.do_guardarcaracteristicasobra(:idavisoobraweb, :obrasingcivil, :obrasdearquitectura, :obrasmontajeindustrial, :obrasdeductos, :obrasderedes, :otrasconstrucciones, :usuario); END;";
	DBExecSP($conn, $curs, $sql, $params, false, 0);

	$curs = null;
	$params = array(":actividades" => getActividades(),
									":detalleexcavacion503" => $_POST["detallarExcavacion503"],
									":excavacion503" => (isset($_POST["excavacion503"]))?"S":"N",
									":fechademolicion" => $_POST["fechaDesdeDemolicion"],
									":fechademolicionhasta" => $_POST["fechaHastaDemolicion"],
									":fechadesdeexcavacion503" => $_POST["fechaDesdeExcavacion503"],
									":fechaexcavacion" => $_POST["fechaDesdeExcavacion"],
									":fechaexcavacionhasta" => $_POST["fechaHastaExcavacion"],
									":fechahastaexcavacion" => $_POST["fechaHastaExcavacion503"],
									":idavisoobraweb" => $row["ID"],
									":parcial" => (isset($_POST["parcial"]))?"S":"N",
									":submuracion" => (isset($_POST["submuraciones"]))?"S":"N",
									":subsuelos" => (isset($_POST["subsuelos"]))?"S":"N",
									":total" => (isset($_POST["total"]))?"S":"N",
									":usuario" => $_SESSION["usuario"]);
	$sql = "BEGIN art.hys_avisoobraweb.do_guardaractividadaejecutar(:idavisoobraweb, :submuracion, :subsuelos, :total, :parcial, :actividades, TO_DATE(:fechademolicion, 'DD/MM/YYYY'), TO_DATE(:fechademolicionhasta, 'DD/MM/YYYY'), TO_DATE(:fechaexcavacion, 'DD/MM/YYYY'), TO_DATE(:fechaexcavacionhasta, 'DD/MM/YYYY'), :excavacion503, TO_DATE(:fechadesdeexcavacion503, 'DD/MM/YYYY'), TO_DATE(:fechahastaexcavacion, 'DD/MM/YYYY'), :detalleexcavacion503, :usuario); END;";
	DBExecSP($conn, $curs, $sql, $params, false, 0);

	$curs = null;
	$params = array(":comitente" => (isset($_POST["comitente"]))?"S":"N",
									":contratista" => (isset($_POST["contratistaPrincipal"]))?"S":"N",
									":cuitcomitente" => $_POST["cuitComitente"],
									":cuitcontratista" => $_POST["cuitContratistaPrincipal"],
									":cuitsubcontratista" => $_POST["cuitSubcontratista"],
									":idavisoobraweb" => $row["ID"],
									":razonsocialcomitente" => $_POST["razonSocialComitente"],
									":razonsocialcontratista" => $_POST["razonSocialContratistaPrincipal"],
									":razonsocialsubcontratista" => $_POST["razonSocialSubcontratista"],
									":subcontratista" => (isset($_POST["subcontratista"]))?"S":"N",
									":usuario" => $_SESSION["usuario"]);
	$sql = "BEGIN art.hys_avisoobraweb.do_guardaractores(:idavisoobraweb, :comitente, :cuitcomitente, :razonsocialcomitente, :contratista, :cuitcontratista, :razonsocialcontratista, :subcontratista, :cuitsubcontratista, :razonsocialsubcontratista, :usuario); END;";
	DBExecSP($conn, $curs, $sql, $params, false, 0);

	$params = array(":idcontactoweb" => $_POST["idContactoWeb"]);
	$sql = "SELECT SUBSTR(art.afi.get_telefonos('HTA_TELEFONOAVISOOBRAWEB', :idcontactoweb), 1, 60) FROM DUAL";
	$telefono = valorSql($sql, "", $params, 0);


	if ($_POST["caracteristicasObrador"] != "N") {
		$curs = null;
		$params = array(":area" => NULL,
										":cargo" => $_POST["cargoHYS"],
										":codareafax" => NULL,
										":contacto" => $_POST["nombreApellidoHYS"],
										":direlectronica" => $_POST["emailHYS"],
										":fax" => NULL,
										":idavisoobraweb" => $row["ID"],
										":idcontactoweb" => $_POST["idContactoWeb"],
										":nrodocumento" => $_POST["numeroDocumentoHYS"],
										":sexo" => $_POST["sexoHYS"],
										":telefono" => $telefono,
										":tipodocumento" => $_POST["tipoDocumentoHYS"],
										":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN art.hys_avisoobraweb.do_guardarcontactoweb(:idavisoobraweb, :idcontactoweb, :cargo, :contacto, :area, :telefono, :codareafax, :fax, :direlectronica, :sexo, :tipodocumento, :nrodocumento, :usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false, 0);

		$curs = null;
		$params = array(":apellido" => $_POST["apellido"],
										":codarea" => $_POST["codigoArea"],
										":direlectronica" => $_POST["email"],
										":idavisoobraweb" => $row["ID"],
										":nombre" => $_POST["nombre"],
										":nrodocumento" => $_POST["numeroDocumento"],
										":sexo" => $_POST["sexo"],
										":telefono" => $_POST["telefono"],
										":tipodocumento" => $_POST["tipoDocumento"],
										":tipotelefono" => $_POST["tipoTelefono"],
										":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN art.hys_avisoobraweb.do_guardarresponsableform(:idavisoobraweb, :nombre, :apellido, :codarea, :telefono, :tipotelefono, :direlectronica, :tipodocumento, :nrodocumento, :sexo, :usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false, 0);
	}

	// Actualizo los tel�fonos..
	if ($_POST["idAvisoObra"] <> -1) {		// afi.act_contacto..
		$dataTel = inicializarTelefonos(OCI_DEFAULT, "tn_idcontacto", $_POST["idContacto"], "tn", "atn_telefonocontacto", $_SESSION["usuario"]);
		copiarTempATelefonos($dataTel);

		$params = array(":idcontacto" => $_POST["idContacto"], ":usualta" => substr($_SESSION["usuario"], 0, 20));
		$sql =
			"UPDATE atn_telefonocontacto
					SET tn_idcontacto = :idcontacto
				WHERE tn_idcontacto = -1
					AND tn_usualta = :usualta
					AND tn_fechaalta > SYSDATE - 1";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}
	else {		// hys.hta_telefonoavisoobraweb..
		$dataTel = inicializarTelefonos(OCI_DEFAULT, "ta_idcontactoavisoobraweb", $_POST["idContactoWeb"], "ta", "hys.hta_telefonoavisoobraweb", $_SESSION["usuario"]);
		copiarTempATelefonos($dataTel);

		$params = array(":idavisoobraweb" => $row["ID"]);
		$sql =
			"SELECT cw_id 
				 FROM hys.hcw_contactoobraweb, hys.haw_avisoobraweb
				WHERE aw_contactoaoweb = cw_id
					AND aw_id = :idavisoobraweb";
		$idContactoAvisoObraWeb = valorSql($sql, -1, $params, 0);

		$params = array(":idcontactoavisoobraweb" => $idContactoAvisoObraWeb, ":usualta" => substr($_SESSION["usuario"], 0, 20));
		$sql =
			"UPDATE hys.hta_telefonoavisoobraweb
					SET ta_idcontactoavisoobraweb = :idcontactoavisoobraweb
				WHERE ta_idcontactoavisoobraweb = -1
					AND ta_usualta = :usualta
					AND ta_fechaalta > SYSDATE - 1";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);
	}


	DBCommit($conn);
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	function redirect() {
		window.parent.location.href = '/aviso-obra';
	}

	setTimeout('redirect()', 10000);

	with (window.parent.document) {
		getElementById('guardadoOk').innerHTML = 'El Aviso de <?= getTipoObra($_POST["origen"])?> de Obra que Ud. ha completado fue exitosamente enviado a las oficinas de Construcci�n de Provincia ART. El mismo ser� evaluado en el t�rmino de las pr�ximas 24 a 48hrs., luego se le enviar� un e-mail con el formulario o puede verificar el estado de situaci�n de su documentaci�n en este sitio.';
		getElementById('guardadoOk').style.display = 'block';

		getElementById('divBotones').style.display = 'none';
	}
</script>