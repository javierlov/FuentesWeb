<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function crearTabla($cols, $rows, $valor) {
	$html = "<table id=\"tableResultados\">";

	$fila = 0;
	foreach ($rows as $key => $value) {
		$fila++;

		if ($fila == 1) {		// Los títulos de las columnas..
			$html.= "<tr>";
			foreach ($cols as $keyCol => $valueCol)
				$html.= "<th>".$keyCol."</th>";
			$html.= "</tr>";
		}
		else {
			$class = (($fila % 2) == 0)?" class=\"alt\"":"";
			$html.= "<tr ".$class.">";

			$col = 0;
			foreach ($cols as $keyCol => $valueCol) {
				$col++;

				if ($col == 1) {
					$class = (substr($key, 0, 7) == "<a href")?"conLink":"sinLink";
					$html.= "<th class=\"".$class."\">".$key."</th>";
				}
				else {
					$html.= "<td>";
					if ($valor == "c")
						$html.= getStat($value.$valueCol);
					if ($valor == "t")
						$html.= getTiempoPromedio($value.$valueCol);
					$html.= "</td>";
				}
			}
			$html.= "</tr>";
		}
	}
	$html.= "</table>";

	return $html;
}

function getStat($condiciones) {
	global $conn;

	if (strpos($condiciones, "<<COMENTARIOS>>") !== false) {
		$sql =
			"SELECT COUNT(*)
				 FROM rrhh.rco_comentarios
				WHERE 1 = 1".$condiciones;
		$sql = str_replace("<<COMENTARIOS>>", "", $sql);
		$sql = str_replace("ei_tiempoentrada", "co_fechaalta", $sql);
		return valorSql($sql, 0);
	}
	else {
		$sql =
			"SELECT COUNT(*)
				 FROM (SELECT ei_idusuario, COUNT(*)
								 FROM web.wei_estadisticasintranet
								WHERE ei_url NOT LIKE '%-abm%'".
											$condiciones."
						 GROUP BY ei_idusuario)";
		return valorSql($sql, 0);
	}
}

function getTiempoPromedio($condiciones) {
	global $conn;

	if (strpos($condiciones, "<<COMENTARIOS>>") !== false) {
		return "";
	}
	else {
		$sql =
			"SELECT TRUNC((prom) * (24 * 60)) min, TRUNC(MOD((prom) * (24 * 60), TRUNC((prom) * (24 * 60))) * 60) sec
				 FROM (SELECT AVG(ei_tiemposalida - ei_tiempoentrada) prom
								 FROM web.wei_estadisticasintranet
								WHERE ei_url NOT LIKE '%-abm%'
									AND ei_tiemposalida IS NOT NULL".$condiciones.")";
	}
	$stmt = DBExecSql($conn, $sql);
	if (DBGetRecordCount($stmt) > 0) {
		$row = DBGetQuery($stmt);
		if (($row["SEC"] != 0) or ($row["MIN"] != 0)) {
			if ($row["SEC"] < 10)
				$row["SEC"] = "0".$row["SEC"];

			if (intval($row["MIN"]) >= 60) {
				return intval($row["MIN"] / 60)."h ".($row["MIN"] % 60)."m ".$row["SEC"]."s";
			}
			else
				return $row["MIN"]."m ".$row["SEC"]."s";
		}
		else
			return "";
	}
	else
		return "";
}

function validar() {
	global $anoInicio;

	$errores = false;

	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	if ($_POST["tipo"] == -1) {
		echo "errores+= '- El campo Tipo es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["tipo"] == "a") {
		if ($_POST["anoDesdeAnual"] == "") {
			echo "errores+= '- El campo Año Desde es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!validarEntero($_POST["anoDesdeAnual"])) {
			echo "errores+= '- El campo Año Desde debe ser un número entero.<br />';";
			$errores = true;
		}
		elseif ($_POST["anoDesdeAnual"] < $anoInicio) {
			echo "errores+= '- El campo Año Desde debe ser mayor o igual a ".$anoInicio.".<br />';";
			$errores = true;
		}

		if ($_POST["anoHastaAnual"] == "") {
			echo "errores+= '- El campo Año Hasta es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!validarEntero($_POST["anoHastaAnual"])) {
			echo "errores+= '- El campo Año Hasta debe ser un número entero.<br />';";
			$errores = true;
		}

		if ($_POST["anoDesdeAnual"] > $_POST["anoHastaAnual"]) {
			echo "errores+= '- El Año Desde debe ser menor o igual al Año Hasta.<br />';";
			$errores = true;
		}

		if (($_POST["anoHastaAnual"] - $_POST["anoDesdeAnual"]) >= 20) {
			echo "errores+= '- No puede seleccionar un rango de mas de 20 años.<br />';";
			$errores = true;
		}
	}

	if ($_POST["tipo"] == "d") {
		if ($_POST["fechaDesdeDiaria"] == "") {
			echo "errores+= '- El campo Fecha Desde es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["fechaDesdeDiaria"])) {
			echo "errores+= '- El campo Fecha Desde debe ser una fecha válida.<br />';";
			$errores = true;
		}

		if ($_POST["fechaHastaDiaria"] == "") {
			echo "errores+= '- El campo Fecha Hasta es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!isFechaValida($_POST["fechaHastaDiaria"])) {
			echo "errores+= '- El campo Fecha Hasta debe ser una fecha válida.<br />';";
			$errores = true;
		}

		if (dateDiff($_POST["fechaHastaDiaria"], $_POST["fechaDesdeDiaria"]) > 0) {
			echo "errores+= '- La Fecha Hasta debe ser mayor o igual a la Fecha Desde.<br />';";
			$errores = true;
		}

		if (dateDiff($_POST["fechaDesdeDiaria"], $_POST["fechaHastaDiaria"]) > 29) {
			echo "errores+= '- No puede seleccionar un rango de mas de 30 días.<br />';";
			$errores = true;
		}
	}

	if ($_POST["tipo"] == "h") {
		if ($_POST["horaDesdeHoraria"] == -1) {
			echo "errores+= '- El campo Hora Desde es obligatorio.<br />';";
			$errores = true;
		}
		if ($_POST["horaHastaHoraria"] == -1) {
			echo "errores+= '- El campo Hora Hasta es obligatorio.<br />';";
			$errores = true;
		}
		if ($_POST["horaDesdeHoraria"] > $_POST["horaHastaHoraria"]) {
			echo "errores+= '- La Hora Hasta debe ser mayor o igual a la Hora Desde.<br />';";
			$errores = true;
		}
	}

	if ($_POST["tipo"] == "m") {
		if ($_POST["mesDesdeMensual"] == -1) {
			echo "errores+= '- El campo Mes Desde es obligatorio.<br />';";
			$errores = true;
		}

		if ($_POST["anoDesdeMensual"] == "") {
			echo "errores+= '- El campo Año Desde es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!validarEntero($_POST["anoDesdeMensual"])) {
			echo "errores+= '- El campo Año Desde debe ser un número entero.<br />';";
			$errores = true;
		}
		elseif ($_POST["anoDesdeMensual"] < $anoInicio) {
			echo "errores+= '- El campo Año Desde debe ser mayor o igual a ".$anoInicio.".<br />';";
			$errores = true;
		}

		if ($_POST["mesHastaMensual"] == -1) {
			echo "errores+= '- El campo Mes Hasta es obligatorio.<br />';";
			$errores = true;
		}

		if ($_POST["anoHastaMensual"] == "") {
			echo "errores+= '- El campo Año Hasta es obligatorio.<br />';";
			$errores = true;
		}
		elseif (!validarEntero($_POST["anoHastaMensual"])) {
			echo "errores+= '- El campo Año Hasta debe ser un número entero.<br />';";
			$errores = true;
		}

		if (intval($_POST["anoDesdeMensual"].$_POST["mesDesdeMensual"]) > intval($_POST["anoHastaMensual"].$_POST["mesHastaMensual"])) {
			echo "errores+= '- El Período Desde debe ser menor o igual al Período Hasta.<br />';";
			$errores = true;
		}

		if (dateDiff("01/".$_POST["mesDesdeMensual"]."/".$_POST["anoDesdeMensual"], "01/".$_POST["mesHastaMensual"]."/".$_POST["anoHastaMensual"], "M") > 24) {
			echo "errores+= '- No puede seleccionar un rango de mas de 24 meses.<br />';";
			$errores = true;
		}
	}


	if ($errores) {
		echo "body.style.cursor = 'default';";
		echo "getElementById('btnAplicar').style.display = 'inline';";
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
	$anoInicio = 2013;

	if (!hasPermiso(91))
		throw new Exception("Usted no tiene permiso para ingresar a este módulo.");

	if (!validar())
		exit;


	// Configuro las columnas..
	$cols = array("" => "");

	if ($_POST["tipo"] == "a") {
		for ($i=intval($_POST["anoDesdeAnual"]); $i<=$_POST["anoHastaAnual"]; $i++)
			$cols[$i] = " AND TO_CHAR(ei_tiempoentrada, 'YYYY') = ".$i;
	}

	if ($_POST["tipo"] == "d") {
		$dias = 0;
		while ($dias <= dateDiff($_POST["fechaDesdeDiaria"], $_POST["fechaHastaDiaria"])) {
			$fecha = incDays($_POST["fechaDesdeDiaria"], $dias);
			$dias++;

			$diaOk = true;
			if ($_POST["dias"] == "h") {		// Si hay que computar solo los días hábiles..
				$params = array(":fecha" => $fecha);
				$sql = "SELECT amebpba.isdiahabil(TO_DATE(:fecha, 'DD/MM/YYYY')) FROM DUAL";
				$diaOk = (valorSql($sql, "N", $params) == "S");
			}

			if ($diaOk)
				$cols[$fecha] = " AND TO_CHAR(ei_tiempoentrada, 'DD/MM/YYYY') = ".addQuotes($fecha);
		}
	}

	if ($_POST["tipo"] == "h") {
		for ($i=intval($_POST["horaDesdeHoraria"]); $i<=$_POST["horaHastaHoraria"]; $i++)
			$cols[($i<10)?"0".$i:$i] = " AND TO_CHAR(ei_tiempoentrada, 'HH24') = ".$i;
	}

	if ($_POST["tipo"] == "m") {
		$ano = $_POST["anoDesdeMensual"];
		$mes = $_POST["mesDesdeMensual"];
		while (($_POST["anoHastaMensual"].$_POST["mesHastaMensual"]) >= ($ano.$mes)) {
			$cols[$mes."/".$ano] = " AND TO_CHAR(ei_tiempoentrada, 'MM/YYYY') = ".addQuotes($mes."/".$ano);
			if ($mes == 12) {
				$mes = 1;
				$ano++;
			}
			else
				$mes++;
			$mes = ((strlen($mes)==1)?"0":"").$mes;
		}
	}


	// Creo la tabla general..
	$rows = array("" => "",
							  "Portada" => " AND ei_url = '/'",
								"Agenda Telefónica" => " AND ei_url LIKE '/contactos%'",
								"<a href=\"#\" id=\"aArteria\" onClick=\"mostrarArteria()\">ARTeria Noticias</a>" => " AND ei_url LIKE '/arteria-noticias%'",
								"<a href=\"#\" id=\"aArticulos\" onClick=\"mostrarArticulos()\">Artículos</a>" => " AND (ei_url LIKE '/articulos/%' OR ei_url LIKE '/articulos/n/%')",
								"Atención al Público" => " AND ei_url = '/atencion-publico'",
								"Ausentismo" => " AND ei_url = '/ausentismo'",
								"Autogestión" => " AND ei_url = '/modules/desempeno/desempeno.php'",
								"Banner Portada" => " AND ei_url = '/modules/portada/link.php?l=5'",
								"<a href=\"#\" id=\"aBeneficios\" onClick=\"mostrarBeneficios()\">Beneficios</a>" => " AND ei_url LIKE '/beneficios%'",
								"Boletín Oficial" => " AND ei_url LIKE '/boletin-oficial%'",
								"Búsqueda" => " AND ei_url LIKE '/buscar%'",
								"Campus Virtual" => " AND ei_url = '/campus-virtual'",
								"Cobertura Médica" => " AND ei_url = '/obras-sociales'",
								"<a href=\"#\" id=\"aComentarios\" onClick=\"mostrarComentarios()\">Comentarios</a>" => "<<COMENTARIOS>>",
								"Delivery" => " AND ei_url = '/delivery'",
								"Descargables" => " AND ei_url LIKE '/descargables%'",
								"Desempeño" => " AND ei_url LIKE '/modules/desempeno/%'",
								"Diccionarios" => " AND ei_url = '/diccionarios'",
								"Extranet" => " AND ei_url LIKE '%provinciart.com.ar%'",
								"Histórico Artículos" => " AND ei_url = '/historico-articulos'",
								"Informes Gestión" => " AND ei_url = '/informes-gestion'",
								"Intranet GB" => " AND ei_url LIKE '%intranetgb%'",
								"Mapa Interactivo" => " AND ei_url = '/mapa-interactivo'",
								"Mapa Prestadores" => " AND ei_url = '/mapa-prestadores'",
								"Nacimientos" => " AND ei_url LIKE '/nacimientos/%'",
								"Normativa Externa" => " AND ei_url LIKE '/normativa-externa%'",
								"Normativa Interna" => " AND ei_url LIKE '/normativa-interna%'",
								"Oracle" => " AND ei_url LIKE '/modules/oracle/%'",
								"Organigrama" => " AND ei_url = '/organigrama'",
								"Protección Datos Personales" => " AND ei_url = '/proteccion-datos-personales'",
								"Regiones Sanitarias" => " AND ei_url LIKE '%/regiones%'",
								"Sindicato del Seguro" => " AND ei_url LIKE '/modules/sindicato_seguro/%'",
								"Solicitud Mantenimiento" => " AND ei_url = '/solicitud-obras-mantenimiento'",
								"Solicitud Sistemas" => " AND ei_url = '/solicitud-sistemas'",
								"Sucursales" => " AND ei_url = '/sucursales'",
								"Tablero de Control" => " AND ei_url = '/tablero-control'");
	$html = crearTabla($cols, $rows, $_POST["valor"]);


	// Creo la tabla de artículos..
	$rows = array("" => "");
	$params = array();
	$sql =
		"SELECT ai_id, ai_titulo
			 FROM web.wai_articulosintranet
			WHERE ai_fechabaja IS NULL";

	if ($_POST["tipo"] == "a")
		$sql.=
			" AND ((TRUNC(ai_fechavigenciadesde) <= TO_DATE('31/12/".$_POST["anoHastaAnual"]."', 'dd/mm/yyyy'))
				 AND (TRUNC(ai_fechavigenciahasta) >= TO_DATE('01/01/".$_POST["anoDesdeAnual"]."', 'dd/mm/yyyy')))";

	if ($_POST["tipo"] == "d")
		$sql.=
			" AND ((TRUNC(ai_fechavigenciadesde) <= TO_DATE('".$_POST["fechaHastaDiaria"]."', 'dd/mm/yyyy'))
				 AND (TRUNC(ai_fechavigenciahasta) >= TO_DATE('".$_POST["fechaDesdeDiaria"]."', 'dd/mm/yyyy')))";

	if ($_POST["tipo"] == "m")
		$sql.=
			" AND ((TRUNC(ai_fechavigenciadesde) <= LAST_DAY(TO_DATE('01/".$_POST["mesHastaMensual"]."/".$_POST["anoHastaMensual"]."', 'dd/mm/yyyy')))
				 AND (TRUNC(ai_fechavigenciahasta) >= TO_DATE('01/".$_POST["mesDesdeMensual"]."/".$_POST["anoDesdeMensual"]."', 'dd/mm/yyyy')))";

	$sql.= " ORDER BY ai_titulo";

	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		$rows[$row["AI_TITULO"]." ".$row["AI_ID"]] = " AND (ei_url = '/articulos/".$row["AI_ID"]."' OR ei_url = '/articulos/n/".$row["AI_ID"]."')";

	$html2 = crearTabla($cols, $rows, $_POST["valor"]);
	$html2.= "<input class=\"btnVolver\" type=\"button\" value=\"\" onClick=\"ocultarArticulos()\" />";


	// Creo la tabla de beneficios..
	$rows = array("" => "");
	$params = array();
	$sql =
		"SELECT bn_idmenu, bn_nombre
			 FROM rrhh.rbn_beneficios
			WHERE bn_fechabaja IS NULL
	 ORDER BY bn_nombre";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		$rows[$row["BN_NOMBRE"]." ".$row["BN_IDMENU"]] = " AND ei_url = '/beneficios/".$row["BN_IDMENU"]."'";

	$html3 = crearTabla($cols, $rows, $_POST["valor"]);
	$html3.= "<input class=\"btnVolver\" type=\"button\" value=\"\" onClick=\"ocultarBeneficios()\" />";


	// Creo la tabla de ARTeria Noticias..
	$rows = array("" => "");
	$params = array();
	$sql =
		"SELECT ba_id, TRIM(TO_CHAR(ba_fecha, 'Day')) || ' ' || TO_NUMBER(TO_CHAR(ba_fecha, 'DD')) || ' de ' || TRIM(TO_CHAR(ba_fecha, 'Month')) || ' de ' || TO_CHAR(ba_fecha, 'YYYY') fecha
			 FROM rrhh.rba_boletinesarteria
			WHERE ba_fechabaja IS NULL
				AND ba_estadoenvio = 'E'";

	if ($_POST["tipo"] == "a")
		$sql.=
			" AND ((TRUNC(ba_fechavigenciadesde) <= TO_DATE('31/12/".$_POST["anoHastaAnual"]."', 'dd/mm/yyyy'))
				 AND (TRUNC(ba_fechavigenciahasta) >= TO_DATE('01/01/".$_POST["anoDesdeAnual"]."', 'dd/mm/yyyy')))";

	if ($_POST["tipo"] == "d")
		$sql.=
			" AND ((TRUNC(ba_fechavigenciadesde) <= TO_DATE('".$_POST["fechaHastaDiaria"]."', 'dd/mm/yyyy'))
				 AND (TRUNC(ba_fechavigenciahasta) >= TO_DATE('".$_POST["fechaDesdeDiaria"]."', 'dd/mm/yyyy')))";

	if ($_POST["tipo"] == "m")
		$sql.=
			" AND ((TRUNC(ba_fechavigenciadesde) <= LAST_DAY(TO_DATE('01/".$_POST["mesHastaMensual"]."/".$_POST["anoHastaMensual"]."', 'dd/mm/yyyy')))
				 AND (TRUNC(ba_fechavigenciahasta) >= TO_DATE('01/".$_POST["mesDesdeMensual"]."/".$_POST["anoDesdeMensual"]."', 'dd/mm/yyyy')))";

	$sql.= " ORDER BY ba_id DESC";

	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		$rows[$row["FECHA"]." ".$row["BA_ID"]] = " AND ei_url LIKE '/arteria-noticias/".$row["BA_ID"]."/%'";

	$html4 = crearTabla($cols, $rows, $_POST["valor"]);
	$html4.= "<input class=\"btnVolver\" type=\"button\" value=\"\" onClick=\"ocultarArteria()\" />";


	// Creo la tabla de Comentarios..
	$rows = array("" => "");

	// Para los Artículos..
	$params = array();
	$sql =
		"SELECT ai_id, ai_titulo
			 FROM web.wai_articulosintranet
			WHERE ai_fechabaja IS NULL";

	if ($_POST["tipo"] == "a")
		$sql.=
			" AND ((TRUNC(ai_fechavigenciadesde) <= TO_DATE('31/12/".$_POST["anoHastaAnual"]."', 'dd/mm/yyyy'))
				 AND (TRUNC(ai_fechavigenciahasta) >= TO_DATE('01/01/".$_POST["anoDesdeAnual"]."', 'dd/mm/yyyy')))";

	if ($_POST["tipo"] == "d")
		$sql.=
			" AND ((TRUNC(ai_fechavigenciadesde) <= TO_DATE('".$_POST["fechaHastaDiaria"]."', 'dd/mm/yyyy'))
				 AND (TRUNC(ai_fechavigenciahasta) >= TO_DATE('".$_POST["fechaDesdeDiaria"]."', 'dd/mm/yyyy')))";

	if ($_POST["tipo"] == "m")
		$sql.=
			" AND ((TRUNC(ai_fechavigenciadesde) <= LAST_DAY(TO_DATE('01/".$_POST["mesHastaMensual"]."/".$_POST["anoHastaMensual"]."', 'dd/mm/yyyy')))
				 AND (TRUNC(ai_fechavigenciahasta) >= TO_DATE('01/".$_POST["mesDesdeMensual"]."/".$_POST["anoDesdeMensual"]."', 'dd/mm/yyyy')))";

	$sql.= " ORDER BY ai_id DESC";

	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		$rows["[ART] ".$row["AI_TITULO"]." ".$row["AI_ID"]] = "<<COMENTARIOS>> AND co_idmodulo = 77 and co_idarticulo = ".$row["AI_ID"];

	// Para los Nacimientos..
	$params = array();
	$sql =
		"SELECT np_id, np_titulo
			 FROM rrhh.rnp_novedadespersonales
			WHERE np_tiponovedad = 'N'
				AND np_fechabaja IS NULL";

	if ($_POST["tipo"] == "a")
		$sql.=
			" AND ((TRUNC(np_fechavigenciadesde) <= TO_DATE('31/12/".$_POST["anoHastaAnual"]."', 'dd/mm/yyyy'))
				 AND (TRUNC(np_fechavigenciahasta) >= TO_DATE('01/01/".$_POST["anoDesdeAnual"]."', 'dd/mm/yyyy')))";

	if ($_POST["tipo"] == "d")
		$sql.=
			" AND ((TRUNC(np_fechavigenciadesde) <= TO_DATE('".$_POST["fechaHastaDiaria"]."', 'dd/mm/yyyy'))
				 AND (TRUNC(np_fechavigenciahasta) >= TO_DATE('".$_POST["fechaDesdeDiaria"]."', 'dd/mm/yyyy')))";

	if ($_POST["tipo"] == "m")
		$sql.=
			" AND ((TRUNC(np_fechavigenciadesde) <= LAST_DAY(TO_DATE('01/".$_POST["mesHastaMensual"]."/".$_POST["anoHastaMensual"]."', 'dd/mm/yyyy')))
				 AND (TRUNC(np_fechavigenciahasta) >= TO_DATE('01/".$_POST["mesDesdeMensual"]."/".$_POST["anoDesdeMensual"]."', 'dd/mm/yyyy')))";

	$sql.= " ORDER BY np_id DESC";

	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt))
		$rows["[NAC] ".$row["NP_TITULO"]." ".$row["NP_ID"]] = "<<COMENTARIOS>> AND co_idmodulo = 32 and co_idarticulo = ".$row["NP_ID"];

	$html5 = crearTabla($cols, $rows, $_POST["valor"]);
	$html5.= "<input class=\"btnVolver\" type=\"button\" value=\"\" onClick=\"ocultarComentarios()\" />";
}
catch (Exception $e) {
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		with (window.parent) {
			getElementById('btnAplicar').style.display = 'inline';
			getElementById('imgProcesando').style.display = 'none';
		}

		showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('btnAplicar').style.display = 'inline';
		getElementById('imgProcesando').style.display = 'none';

		getElementById('divResultados').innerHTML = '<?= $html?>';
		getElementById('divResultadosArteria').innerHTML = '<?= $html4?>';
		getElementById('divResultadosArticulos').innerHTML = '<?= $html2?>';
		getElementById('divResultadosBeneficios').innerHTML = '<?= $html3?>';
		getElementById('divResultadosComentarios').innerHTML = '<?= $html5?>';
	}
</script>