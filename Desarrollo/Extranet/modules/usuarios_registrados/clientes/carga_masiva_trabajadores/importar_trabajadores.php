<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/excel/excel_reader2.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


function errorColumna($numColumna) {
	global $conn;

	$params = array(":idusuario" => $_SESSION["idUsuario"],
									":ipusuario" => $_SERVER["REMOTE_ADDR"],
									":numcolumna" => $numColumna);
	$sql =
		"SELECT 1
			 FROM tmp.tcm_cargamasivatrabajadoresweb
			WHERE cm_idusuario = :idusuario
				AND cm_ipusuario = :ipusuario
				AND SUBSTR(cm_errores, :numcolumna, 1) = '0'";
	return existeSql($sql, $params);
}

function importarTrabajadores() {
	global $conn;

	try {
		if ($_FILES["archivo"]["name"] == "")
			throw new Exception("Debe elegir el Archivo a subir.");

		if (!validarExtension($_FILES["archivo"]["name"], array("xls")))
			throw new Exception("El Archivo a subir debe ser de extensión \".xls\".");


		// Borro los registros temporales que se pudieran haber generado en otra oportunidad..
		$params = array(":idusuario" => $_SESSION["idUsuario"], ":ipusuario" => $_SERVER["REMOTE_ADDR"]);
		$sql =
			"DELETE FROM tmp.tcm_cargamasivatrabajadoresweb
						 WHERE cm_idusuario = :idusuario
							 AND cm_ipusuario = :ipusuario";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);


		error_reporting(E_ALL ^ E_NOTICE);
		$excel = new Spreadsheet_Excel_Reader($_FILES["archivo"]["tmp_name"]);

		for ($row=2; $row<=$excel->rowcount(); $row++) {		// Empiezo desde la 2, porque en la 1 viene la cabecera..
			// Meto los valores de las columnas en un array..
			$cols = array();
			for ($col=65; $col<=87; $col++)
				$cols[chr($col)] = trim($excel->val($row, chr($col)));

			// Si todas las columnas estan vacías lo tomo como un EOF y salgo del loop principal..
			$existeValor = false;
			foreach ($cols as $key => $value)
				if ($value != "")
					$existeValor = true;
			if (!$existeValor)
				break;


			// *** - INICIO VALIDACIONES..
			$errores = "11111111111111111111111";

			// Columna A - CUIL..
			if (!validarCuit($cols["A"]))
				$errores[0] = "0";

			// Columna B - Nombre..
			if ($cols["B"] == "")
				$errores[1] = "0";

			// Columna C - Sexo..
			if (($cols["C"] != "F") and ($cols["C"] != "M"))
				$errores[2] = "0";

			// Columna D - Nacionalidad..
			if ($cols["D"] != "") {
				$params = array(":descripcion" => $cols["D"]);
				$sql =
					"SELECT 1
						 FROM cna_nacionalidad
						WHERE na_fechabaja IS NULL
							AND UPPER(na_descripcion) = UPPER(:descripcion)";
				if (!existeSql($sql, $params))
					$errores[3] = "0";
			}

			// Columna E - Otra nacionalidad..
			$errores[4] = "1";

			// Columna F - Fecha de nacimiento..
			try {
				if (isFechaValida($cols["F"])) {
					$edad = dateDiff($cols["F"], date("d/m/Y"), "A");
					if (($edad < 16) or ($edad > 90))
						$errores[5] = "0";
				}
				else
					$errores[5] = "0";
			}
			catch (Exception $e) {
				$errores[5] = "0";
			}

			// Columna G - Estado Civil..
			if ($cols["G"] != "") {
				$params = array(":descripcion" => $cols["G"]);
				$sql =
					"SELECT 1
						 FROM ctb_tablas
						WHERE tb_clave = 'ESTAD'
							AND tb_fechabaja IS NULL
							AND UPPER(tb_descripcion) = UPPER(:descripcion)";
				if (!existeSql($sql, $params))
					$errores[6] = "0";
			}

			// Columna H - Fecha de ingreso..
			if (!isFechaValida($cols["H"]))
				$errores[7] = "0";

			// Columna I - Establecimiento..
			$errores[8] = "1";

			// Columna J - Tipo contrato..
			if ($cols["J"] != "") {
				$params = array(":descripcion" => $cols["J"]);
				$sql =
					"SELECT 1
						 FROM cmc_modalidadcontratacion
						WHERE mc_fechabaja IS NULL
							AND UPPER(mc_descripcion) = UPPER(:descripcion)";
				if (!existeSql($sql, $params))
					$errores[9] = "0";
			}

			// Columna K - Tarea..
			$errores[10] = "1";

			// Columna L - Sector..
			$errores[11] = "1";

			// Columna M - Código CIUO..
			if ($cols["M"] != "") {
				$params = array(":codigo" => $cols["M"]);
				$sql =
					"SELECT 1
						 FROM cci_ciuo
						WHERE ci_codigo = :codigo";
				if (!existeSql($sql, $params))
					$errores[12] = "0";
			}

			// Columna N - Remuneración..
			if ($cols["N"] != "")
				if (!validarNumero($cols["N"], true))
					$errores[13] = "0";

			// Columna O - Calle..
			if ($cols["O"] == "")
				$errores[14] = "0";

			// Columna P - Número..
			$errores[15] = "1";

			// Columna Q - Piso..
			$errores[16] = "1";

			// Columna R - Departamento..
			$errores[17] = "1";

			// Columna S - Código postal..
			if ($cols["S"] == "")
				$errores[18] = "0";
			else {
				$params = array(":codigopostal" => $cols["S"]);
				$sql =
					"SELECT 1
						 FROM cub_ubicacion
						WHERE ub_cpostal = :codigopostal";
				if (!existeSql($sql, $params))
					$errores[18] = "0";
			}

			// Columna T - Localidad..
			if (($cols["T"] != "") and ($cols["S"] != "")) {
				$params = array(":codigopostal" => $cols["S"], ":localidad" => $cols["T"]);
				$sql =
					"SELECT 1
						 FROM cub_ubicacion
						WHERE ub_cpostal = :codigopostal
							AND UPPER(ub_localidad) = UPPER(:localidad)";
				if (!existeSql($sql, $params))
					$errores[19] = "0";
			}

			// Columna U - Provincia..
			if (($cols["U"] != "") and ($cols["T"] != "") and ($cols["S"] != "")) {
				$params = array(":codigopostal" => $cols["S"], ":localidad" => $cols["T"], ":provincia" => $cols["U"]);
				$sql =
					"SELECT 1
						 FROM cub_ubicacion, cpv_provincias
						WHERE ub_provincia = pv_codigo
							AND ub_cpostal = :codigopostal
							AND UPPER(ub_localidad) = UPPER(:localidad)
							AND UPPER(pv_descripcion) = UPPER(:provincia)";
				if (!existeSql($sql, $params))
					$errores[20] = "0";
			}

			// Columna V - Fecha de baja..
			if ($cols["V"] != "")
				if (!isFechaValida($cols["V"]))
					$errores[21] = "0";

			// Columna W - No confirmado al puesto..
//			$errores[22] = "1";
			// *** - FIN VALIDACIONES..


			$params = array(":calle" => substr($cols["O"], 0, 60),
											":ciuo" => substr($cols["M"], 0, 4),
											":codigopostal" => substr($cols["S"], 0, 5),
/*											":confirmapuesto" => substr($cols["W"], 0, 1),*/
											":cuil" => substr($cols["A"], 0, 11),
											":departamento" => substr($cols["R"], 0, 20),
											":errores" => $errores,
											":establecimiento" => substr($cols["I"], 0, 100),
											":estadocivil" => $cols["G"],
											":fechabaja" => substr($cols["V"], 0, 10),
											":fechaingreso" => substr($cols["H"], 0, 10),
											":fechanacimiento" => substr($cols["F"], 0, 10),
											":fila" => $row,
											":idusuario" => $_SESSION["idUsuario"],
											":ipusuario" => $_SERVER["REMOTE_ADDR"],
											":localidad" => substr($cols["T"], 0, 60),
											":nacionalidad" => $cols["D"],
											":nombre" => substr($cols["B"], 0, 60),
											":numero" => substr($cols["P"], 0, 20),
											":otranacionalidad" => substr($cols["E"], 0, 30),
											":piso" => substr($cols["Q"], 0, 20),
											":provincia" => $cols["U"],
											":sector" => substr($cols["L"], 0, 150),
											":sexo" => substr($cols["C"], 0, 1),
											":sueldo" => substr($cols["N"], 0, 15),
											":tarea" => substr($cols["K"], 0, 150),
											":tipocontrato" => substr($cols["J"], 0, 100));
			$sql =
				"INSERT INTO tmp.tcm_cargamasivatrabajadoresweb
										 (cm_idusuario, cm_ipusuario, cm_fila, cm_cuil, cm_nombre, cm_sexo, cm_nacionalidad, cm_otranacionalidad, cm_fechanacimiento, cm_estadocivil, cm_fechaingreso,
										  cm_establecimiento, cm_tipocontrato, cm_tarea, cm_sector, cm_ciuo, cm_sueldo, cm_calle, cm_numero, cm_piso, cm_departamento, cm_codigopostal, cm_localidad,
										  cm_provincia, cm_fechabaja, cm_errores)
							VALUES (:idusuario, :ipusuario, :fila, :cuil, :nombre, :sexo, :nacionalidad, :otranacionalidad, :fechanacimiento, :estadocivil, :fechaingreso,
											:establecimiento, :tipocontrato, :tarea, :sector, :ciuo, :sueldo, :calle, :numero, :piso, :departamento, :codigopostal, :localidad,
											:provincia, :fechabaja, :errores)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}

		DBCommit($conn);
	}
	catch (Exception $e) {
		DBRollback($conn);
		echo "<script type='text/javascript'>history.back(); alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
		exit;
	}
}


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 55));


setDateFormatOracle("DD/MM/YYYY");
set_time_limit(1800);

importarTrabajadores();

$params = array(":idusuario" => $_SESSION["idUsuario"], ":ipusuario" => $_SERVER["REMOTE_ADDR"]);
$sql =
	"SELECT COUNT(*)
		 FROM tmp.tcm_cargamasivatrabajadoresweb
		WHERE cm_idusuario = :idusuario
			AND cm_ipusuario = :ipusuario
			AND INSTR(cm_errores, '0') = 0";
$correctos = valorSql($sql, 0, $params);
$hayCorrectos = ($correctos == 0)?false:true;
if ($correctos == 1)
	$correctos.= " registro correcto";
else
	$correctos.= " registros correctos";

$params = array(":idusuario" => $_SESSION["idUsuario"], ":ipusuario" => $_SERVER["REMOTE_ADDR"]);
$sql =
	"SELECT COUNT(*)
		 FROM tmp.tcm_cargamasivatrabajadoresweb
		WHERE cm_idusuario = :idusuario
			AND cm_ipusuario = :ipusuario
			AND INSTR(cm_errores, '0') > 0";
$incorrectos = valorSql($sql, 0, $params);
$hayErrores = ($incorrectos == 0)?false:true;
if ($incorrectos == 1)
	$incorrectos.= " registro incorrecto";
else
	$incorrectos.= " registros incorrectos";

$params = array(":idempresa" => $_SESSION["idEmpresa"],
								":idusuario" => $_SESSION["idUsuario"],
								":ipusuario" => $_SERVER["REMOTE_ADDR"]);
$sql =
	"SELECT COUNT(*)
		 FROM (SELECT DISTINCT tj_cuil
											FROM ctj_trabajador, crl_relacionlaboral, aco_contrato, tmp.tcm_cargamasivatrabajadoresweb
										 WHERE tj_id = rl_idtrabajador
											 AND rl_contrato = co_contrato
											 AND tj_cuil = cm_cuil
											 AND co_idempresa = :idempresa
											 AND cm_idusuario = :idusuario
											 AND cm_ipusuario = :ipusuario
											 AND INSTR(cm_errores, '0') = 0)";
$altas = valorSql($sql, 0, $params);
if ($altas == 1)
	$altas.= " alta";
else
	$altas.= " altas";
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<script src="/modules/usuarios_registrados/clientes/js/carga_masiva_trabajadores.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/carga_masiva_trabajadores/importar_trabajadores_busqueda.php" id="formImportarTrabajadores" method="post" name="formImportarTrabajadores" target="iframeProcesando">
	<div class="TituloSeccion" style="display:block; width:730px;">Importación Masiva de Trabajadores</div>
	<div class="ContenidoSeccion" align=right style="margin-top:5px;"><i>>> <a href="/nomina-trabajadores/terminos-y-condiciones">Términos y Condiciones de uso</a></i></div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td><span style="color:green;"><?= $correctos?></span>, <span style="color:red;"><?= $incorrectos?></span>, <?= $altas?>.<br />Sólo serán importados los registros correctos.</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
			</tr>	
		</table>
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:296px; overflow:auto; width:720px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
</form>
<?
if ($hayErrores) {
?>
<div class="ContenidoSeccion" style="margin-top:20px;">
	Se detectaron los siguientes errores:
	<ul>
<?
	if (errorColumna(1))		// C.U.I.L...
		echo "<li>C.U.I.L. faltante o incorrecta.</li>";

	if (errorColumna(2))		// Nombre..
		echo "<li>Nombre faltante.</li>";

	if (errorColumna(3))		// Sexo..
		echo "<li>Sexo faltante o incorrecto (F / M).</li>";

	if (errorColumna(4))		// Nacionalidad..
		echo "<li>Nacionalidad incorrecta (puede consultar aquí el listado válido).</li>";

//	if (errorColumna(5))		// Otra nacionalidad..
//		echo "<li></li>";

	if (errorColumna(6)) {		// Fecha de nacimiento..
		echo "<li>Fecha de Nacimiento faltante o incorrecta (dd/mm/yyyy).</li>";
		echo "<li>La edad del trabajador debe estar entre 16 y 90 años.</li>";
	}

	if (errorColumna(7))		// Estado civil..
		echo "<li>Estado Civil incorrecto (puede consultar aquí el listado válido).</li>";

	if (errorColumna(8))		// Fecha de ingreso..
		echo "<li>Fecha de Ingreso faltante o incorrecta (dd/mm/yyyy).</li>";

//	if (errorColumna(9))		// Establecimiento..
//		echo "<li></li>";

	if (errorColumna(10))		// Tipo de contrato..
		echo "<li>Tipo de Contrato incorrecto (puede consultar aquí el listado válido).</li>";

//	if (errorColumna(11))		// Tarea..
//		echo "<li></li>";

//	if (errorColumna(12))		// Sector..
//		echo "<li></li>";

	if (errorColumna(13))		// Código CIUO..
		echo "<li>Código CIUO incorrecto.</li>";

	if (errorColumna(14))		// Sueldo..
		echo "<li>Remuneración incorrecta (99999,99).</li>";

	if (errorColumna(15))		// Calle..
		echo "<li>Calle faltante.</li>";

//	if (errorColumna(16))		// Número..
//		echo "<li></li>";

//	if (errorColumna(17))		// Piso..
//		echo "<li></li>";

//	if (errorColumna(18))		// Departamento..
//		echo "<li></li>";

	if (errorColumna(19))		// Código postal..
		echo "<li>Código Postal faltante o incorrecto.</li>";

	if (errorColumna(20))		// Localidad..
		echo "<li>Localidad incorrecta.</li>";

	if (errorColumna(21))		// Provincia..
		echo "<li>Provincia incorrecta.</li>";

	if (errorColumna(22))		// Fecha de baja..
		echo "<li>Fecha de Baja incorrecta (dd/mm/yyyy).</li>";

//	if (errorColumna(23))		// No confirmado al puesto..
//		echo "<li></li>";
?>
	</ul>
</div>
<br />
<?
}
if ($hayCorrectos) {
?>
	<p id="guardadoOk" style="background:#0f539c; color:#fff; visibility:hidden; float:left; padding:2px; width:272px;">&nbsp;Datos guardados exitosamente.</p>
	<br />
	<img border="0" id="btnImportar" src="/modules/usuarios_registrados/images/importar.jpg" style="cursor:pointer; margin-left:16px; vertical-align:middle;" onClick="importar()" />
	<span id="spanMsgEspera" style="display:none; float:left;">
		<img src="/images/loading.gif" style="vertical-align:-4px;" />
		<span class="ContenidoSeccion" style="color:#29b407;"><b>Procesando, aguarde un instante por favor...</b></span>
	</span>
<?
}
?>
<input class="btnVolver" id="btnVolver" type="button" value="" onClick="history.back(-1);" />
<script type="text/javascript">
	formImportarTrabajadores.submit();
</script>