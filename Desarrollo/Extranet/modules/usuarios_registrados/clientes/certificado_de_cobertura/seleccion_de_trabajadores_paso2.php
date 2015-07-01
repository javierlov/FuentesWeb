<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 70));


set_time_limit(120);
if (isset($_REQUEST["tipoCertificado"])) {
	// ***  INICIO VALIDACIONES  ***
	try {

		if ($_REQUEST["tipoCertificado"] == "cccr") {
			if ($_REQUEST["razonSocial"] == "") {
				$_SESSION["certificadoCobertura"]["campoError"] = "razonSocial";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar la Razón Social.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if ((($_REQUEST["calle"] != "") or ($_REQUEST["numero"] != "") or ($_REQUEST["localidad"] != "")) and ($_REQUEST["calle"] == "")) {
				$_SESSION["certificadoCobertura"]["campoError"] = "calle";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar la Calle.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if ((($_REQUEST["calle"] != "") or ($_REQUEST["numero"] != "") or ($_REQUEST["localidad"] != "")) and ($_REQUEST["numero"] == "")) {
				$_SESSION["certificadoCobertura"]["campoError"] = "numero";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar el Número.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if ((($_REQUEST["calle"] != "") or ($_REQUEST["numero"] != "") or ($_REQUEST["localidad"] != "")) and ($_REQUEST["localidad"] == "")) {
				$_SESSION["certificadoCobertura"]["campoError"] = "localidad";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar la Localidad.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}
		}

		if ($_REQUEST["tipoCertificado"] == "cce") {
			if ($_REQUEST["fechaSalida"] == "") {
				$_SESSION["certificadoCobertura"]["campoError"] = "fechaSalida";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar la Fecha de Salida.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if (!isFechaValida($_POST["fechaSalida"])) {
				$_SESSION["certificadoCobertura"]["campoError"] = "fechaSalida";
				$_SESSION["certificadoCobertura"]["msgError"] = "La Fecha de Salida es inválida.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if ($_REQUEST["fechaRegreso"] == "") {
				$_SESSION["certificadoCobertura"]["campoError"] = "fechaRegreso";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar la Fecha de Regreso.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if (!isFechaValida($_POST["fechaRegreso"])) {
				$_SESSION["certificadoCobertura"]["campoError"] = "fechaRegreso";
				$_SESSION["certificadoCobertura"]["msgError"] = "La Fecha de Regreso es inválida.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if ($_REQUEST["pais"] == -1) {
				$_SESSION["certificadoCobertura"]["campoError"] = "pais";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar el País.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if ($_REQUEST["destino"] == "") {
				$_SESSION["certificadoCobertura"]["campoError"] = "destino";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar el Destino.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if ($_REQUEST["asistenciaViajero"] == "") {
				$_SESSION["certificadoCobertura"]["campoError"] = "asistenciaViajero";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar la Asistencia al Viajero.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}

			if ($_REQUEST["formaViaje"] == -1) {
				$_SESSION["certificadoCobertura"]["campoError"] = "formaViaje";
				$_SESSION["certificadoCobertura"]["msgError"] = "Debe ingresar la Forma de Viaje.";
				throw new Exception($_SESSION["certificadoCobertura"]["msgError"]);
			}
		}
	}
	catch (Exception $e) {
		echo "<script type='text/javascript'>history.back();</script>";
		exit;
	}
	// ***  FIN VALIDACIONES  ***


	// Elimino estos campos si no hubo errores..
	unset($_SESSION["certificadoCobertura"]["campoError"]);
	unset($_SESSION["certificadoCobertura"]["msgError"]);

	// Guardo los valores en variables de sesión..
	$_SESSION["certificadoCobertura"]["tipoCertificado"] = $_REQUEST["tipoCertificado"];

	$_SESSION["certificadoCobertura"]["razonSocial"] = $_REQUEST["razonSocial"];
	$_SESSION["certificadoCobertura"]["calle"] = $_REQUEST["calle"];
	$_SESSION["certificadoCobertura"]["numero"] = $_REQUEST["numero"];
	$_SESSION["certificadoCobertura"]["piso"] = $_REQUEST["piso"];
	$_SESSION["certificadoCobertura"]["departamento"] = $_REQUEST["departamento"];
	$_SESSION["certificadoCobertura"]["codigoPostal"] = $_REQUEST["codigoPostal"];
	$_SESSION["certificadoCobertura"]["localidad"] = $_REQUEST["localidad"];
	$_SESSION["certificadoCobertura"]["idprovincia"] = $_REQUEST["idprovincia"];
	$_SESSION["certificadoCobertura"]["agendar"] = isset($_REQUEST["agendar"]);

	$_SESSION["certificadoCobertura"]["fechaSalida"] = $_REQUEST["fechaSalida"];
	$_SESSION["certificadoCobertura"]["fechaRegreso"] = $_REQUEST["fechaRegreso"];
	$_SESSION["certificadoCobertura"]["pais"] = $_REQUEST["pais"];
	$_SESSION["certificadoCobertura"]["destino"] = $_REQUEST["destino"];
	$_SESSION["certificadoCobertura"]["asistenciaViajero"] = $_REQUEST["asistenciaViajero"];
	$_SESSION["certificadoCobertura"]["formaViaje"] = $_REQUEST["formaViaje"];
	$_SESSION["certificadoCobertura"]["observaciones"] = $_REQUEST["observaciones"];

	$_SESSION["certificadoCobertura"]["seleccionNomina"] = (isset($_REQUEST["seleccionNomina"]))?$_REQUEST["seleccionNomina"]:"";
	$_SESSION["certificadoCobertura"]["tipoNomina"] = (isset($_REQUEST["tipoNomina"]))?$_REQUEST["tipoNomina"]:"";

	if (($_SESSION["certificadoCobertura"]["agendar"]) and (!$servidorContingenciaActivo)) {		// Si tildó agendar, y si no es la base de datos de contingencia..
		$params = array(":calle" => substr($_REQUEST["calle"], 0, 60),
										":codigopostal" => $_REQUEST["codigoPostal"],
										":departamento" => substr($_REQUEST["departamento"], 0, 20),
										":idempresa" => $_SESSION["idEmpresa"],
										":idprovincia" => $_REQUEST["idprovincia"],
										":localidad" => substr($_REQUEST["localidad"], 0, 60),
										":numero" => $_REQUEST["numero"],
										":piso" => $_REQUEST["piso"],
										":razonsocial" => substr($_REQUEST["razonSocial"], 0, 500),
										":usualta" => substr($_SESSION["usuario"], 0, 20));
		$sql =
			"INSERT INTO web.wde_datosempresascomitentes (de_calle, de_codigopostal, de_departamento, de_fechaalta, de_id, de_idempresa, de_idprovincia, de_localidad, de_numero, de_piso, de_razonsocial, de_usualta)
																						VALUES (:calle, :codigopostal, :departamento, SYSDATE, -1, :idempresa, :idprovincia, :localidad, :numero, :piso, :razonsocial, :usualta)";
		DBExecSql($conn, $sql, $params);
	}
}

if ((isset($_REQUEST["seleccionNomina"])) and ($_REQUEST["seleccionNomina"] != "p"))
	require_once("seleccion_de_trabajadores_paso3.php");
else {
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");

	$showProcessMsg = false;

	$cuil = "";
	if (isset($_REQUEST["cuil"]))
		$cuil = $_REQUEST["cuil"];

	$establecimiento = -1;
	if (isset($_REQUEST["establecimiento"]))
		$establecimiento = $_REQUEST["establecimiento"];

	$nombre = "";
	if (isset($_REQUEST["nombre"]))
		$nombre = $_REQUEST["nombre"];

	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];

	$ob = "1";
	if (isset($_REQUEST["ob"]))
		$ob = $_REQUEST["ob"];

	require_once("seleccion_de_trabajadores_paso2_combos.php");
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<style>
	#establecimiento {width:488px;}
</style>
<script src="/modules/usuarios_registrados/clientes/js/certificados.js" type="text/javascript"></script>
<iframe id="iframePaso2" name="iframePaso2" src="" style="display:none;"></iframe>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>

<div class="TituloSeccion" style="display:block; width:730px;">Selección de Trabajadores</div>
<div class="ContenidoSeccion" style="margin-top:15px;">
<table cellpadding="0" cellspacing="0">
	<tr>
		<td>Busque los trabajadores que desea incluir en el certificado. Recuerde que si su nómina está desactualizada, puede ponerla al día mediante la aplicación Nómina de Trabajadores.</td>
	</tr>
	<tr>
		<td height="20"></td>
	</tr>
	<tr>
		<td class="ContenidoSeccion" width="493" valign="top">
			<form action="/index.php" id="formSeleccionarTrabajadores" method="get" name="formSeleccionarTrabajadores">
				<input id="buscar" name="buscar" type="hidden" value="s" />
				<input id="page" name="page" type="hidden" value="certificado_de_cobertura/seleccion_de_trabajadores_paso2.php" />
				<input id="pageid" name="pageid" type="hidden" value="50" />
				<table cellpadding="0" cellspacing="5">
					<tr>
						<td>Nombre</td>
						<td><input id="nombre" name="nombre" style="text-transform:uppercase; width:480px;" type="text" value="<?= $nombre?>"></td>
						<td>&nbsp;</td>
					</tr>	
					<tr>
						<td>C.U.I.L.</td>
						<td><input id="cuil" maxlength="13" name="cuil" style="width:80px;" type="text" value="<?= $cuil?>"></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Establecimiento&nbsp;</td>
						<td><?= $comboEstablecimiento->draw();?></td>
						<td><input class="btnBuscar" type="submit" value="" /></td>
					</tr>
				</table>
			</form>
<?
	if (!$servidorContingenciaActivo) {
?>
			<form action="/modules/usuarios_registrados/clientes/certificado_de_cobertura/validar_archivo.php" enctype="multipart/form-data" id="formArchivo" method="post" name="formArchivo" target="iframeProcesando">
				<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="20971520" />
				<div style="border: #0f539c solid 1px; margin-left:-12px; margin-top:4px; padding-left:20px; width:680px;">
					<div style="background-color:#0f539c; color:#fff; font-weight:bold; margin-bottom:8px; margin-left:-20px; padding:1px; padding-left:20px;">Carga automática de CUILES desde un archivo</div>
					<label>Archivo</label>
					<input class="InputText" id="archivo" name="archivo" style="margin-right:16px; width:400px;" type="file" value="" />
					<input class="btnCargar" id="btnCargar" style="vertical-align:-3px;" type="button" value="" onClick="subirArchivo()" />
					<input class="btnVerEjemplo" id="btnVerEjemplo" name="btnVerEjemplo" style="margin-left:16px; vertical-align:-3px;" type="button" value="" onClick="verEjemplo()" />
					<img border="0" id="imgProcesando" src="/modules/usuarios_registrados/images/procesando.gif" style="display:none; vertical-align:-3px;" title="Procesando.&#13;Aguarde un instante, por favor..." />
					<br />
					<span style="margin-top:4px;">El archivo debe ser menor a 20Mb.</span>
					<div id="divErrores" style="display:none; font-weight:bold; margin-bottom:8px; margin-top:8px;">
						<div style="color:#f00;">En las siguientes filas se detectaron CUILES de trabajadores que no están dados de alta en su empresa: <span id="spanErrores"></span></div>
						<div style="color:#000; margin-top:4px;">
							<label>¿ Desea cargar solo los registros válidos ?</label>
							<a href="/modules/usuarios_registrados/clientes/certificado_de_cobertura/procesar_archivo.php" style="color:#000; margin-left:16px;" target="iframeProcesando">SI</a>
							<a href="#" style="color:#000; margin-left:16px;" onClick="cancelarProcesoArchivo()">NO</a>
						</div>
					</div>
					<div id="divCargaOk" style="color:#4ab418; display:none; font-weight:bold; margin-top:8px;">Los CUILES fueron cargados correctamente.</div>
					<div id="divSinRegistros" style="color:#f00; display:none; font-weight:bold; margin-top:8px;">No se encontraron CUILES para cargar.</div>
				</div>
			</form>
<?
	}
?>
		</td>
	</tr>	
	<tr>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td>
			<p>Utilice el Nombre, C.U.I.L. y Establecimiento para buscar en la Nómina de Trabajadores. Si no especifica ningún filtro, la búsqueda traerá la nómina de trabajadores completa. Para seleccionar un trabajador, simplemente márquelo. Luego de finalizada la selección de trabajadores presione el botón <b>Continuar</b>.</p>
			<p>Cantidad de Trabajadores seleccionados <font color="#CC0000"><b><span id="cantidadTrabajadoresSeleccionados" name="cantidadTrabajadoresSeleccionados"><?= count($_SESSION["certificadoCobertura"]["trabajadores"])?></span></b></font></p>
			<p>
				<img border="0" src="/modules/usuarios_registrados/images/limpiar.jpg" style="cursor:pointer; vertical-align:middle" onClick="limpiarTrabajadoresSeleccionados()" />
				<span id="divMsgLimpiarOk" style="background-color:#5ec5ee; border:#000 1px solid; color:#000; cursor:default; font-size:10pt; padding-left:4px; padding-right:4px; visibility:hidden;">&nbsp;Listado limpiado exitosamente.</span>
				<br />
				<i>(Utilice el botón Limpiar para eliminar la lista de trabajadores seleccionados)</i>
			</p>
		</td>
	</tr>	
	<tr>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td>
			<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; overflow:auto; width:720px;">
				<form id="form" name="form">
<?
	if ((isset($_REQUEST["buscar"])) and ($_REQUEST["buscar"] == "s")) {
		$params = array(":idempresa" => $_SESSION["idEmpresa"]);
		$where = "";

		if ($cuil != "") {
			$params[":cuil"] = str_replace("-", "", $cuil);
			$where.= " AND tj_cuil = :cuil";
		}

		if ($nombre != "") {
			$params[":nombre"] = "%".$nombre."%";
			$where.= " AND UPPER(tj_nombre) LIKE UPPER(:nombre)";
		}

		if ($establecimiento != -1) {
			$params[":idestablecimiento"] = $establecimiento;
			$where.= " AND es_id = :idestablecimiento";
		}

		$sql =
			"SELECT ¿tj_nombre?, ¿tj_cuil?, ¿es_nombre?, ¿rl_tarea?, ¿tj_id?, 'xchecked' ¿checked?
				 FROM ctj_trabajador, crl_relacionlaboral, cre_relacionestablecimiento, aes_establecimiento
				WHERE tj_id = rl_idtrabajador
					AND rl_id = re_idrelacionlaboral(+)
					AND re_idestablecimiento = es_id(+)
					AND rl_contrato = art.afiliacion.get_contratovigente((SELECT em_cuit
																																	FROM aem_empresa
																																 WHERE em_id = :idempresa), SYSDATE) _EXC1_";
		$grilla = new Grid();
		$grilla->addColumn(new Column("Trabajador"));
		$grilla->addColumn(new Column("CUIL"));
		$grilla->addColumn(new Column("Establecimiento"));
		$grilla->addColumn(new Column("Tarea"));
		$grilla->addColumn(new Column(" ", 1, true, false, -1, "btnSeleccionar", "/modules/usuarios_registrados/clientes/certificado_de_cobertura/seleccionar_trabajador.php", "", -1, true, -1, "", false, "", "checkbox", 6));
		$grilla->addColumn(new Column("", -1, false));
		$grilla->setExtraConditions(array($where));
		$grilla->setOrderBy($ob);
		$grilla->setPageNumber($pagina);
		$grilla->setParams($params);
		$grilla->setSql($sql);
		$grilla->setTableStyle("GridTableCiiu");
		$grilla->setUseTmpIframe(true);
		$grilla->Draw();
	}
?>
				</form>
			</div>
			<div align="center" id="divProcesando" name="divProcesando" <?= ($showProcessMsg)?"show='ok'":""?> style="display:none"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
			<script type="text/javascript">
				function CopyContent() {
					try {
						window.parent.document.getElementById('divContentGrid').innerHTML = document.getElementById('divContentGrid').innerHTML;
					}
					catch(err) {
						//
					}
<?
if ($showProcessMsg) {
?>
					if (document.getElementById('originalGrid') != null)
						document.getElementById('originalGrid').style.display = 'block';
					document.getElementById('divProcesando').style.display = 'none';
<?
}
?>
				}

				CopyContent();
				checkGridTrabajadores();
			</script>
		</td>
	</tr>	
	<tr>
		<td>&nbsp;</td>
	</tr>	
	<tr>
		<td><img border="0" src="/modules/usuarios_registrados/images/continuar.jpg" style="cursor:pointer;" onClick="validarSegundoPaso(<?= ($_SESSION["certificadoCobertura"]["tipoCertificado"] == "cce")?"true":"false"?>)" /></td>
	</tr>	
	<tr>
		<td><input class="btnVolver" type="button" value="" onClick="window.location.href = '/certificados-cobertura/<?= $_SESSION["contrato"]?>';" /></td>
	</tr>
</table>
</div>
<?
}
?>