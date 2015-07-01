<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));


if (isset($_SESSION["isAgenteComercial"])) {
	validarSesion(validarContrato($_REQUEST["id"]));
	validarSesion(validarEntero($_REQUEST["id"]));
	if (isset($_REQUEST["id"])) {
		$id = $_REQUEST["id"];
		$params = array(":contrato" => $id);
		$sql = 
			"SELECT em_cuit, NVL(em_nombre, '-') empresa, NVL(co_idempresa, -1) idempresa
				 FROM aco_contrato, aem_empresa
				WHERE co_idempresa = em_id
					AND art.afiliacion.check_cobertura(co_contrato, SYSDATE) = 1
					AND co_contrato = :contrato";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);

		$_SESSION["contrato"] = $_REQUEST["id"];
		$_SESSION["cuit"] = $row["EM_CUIT"];
		$_SESSION["empresa"] = $row["EMPRESA"];
		$_SESSION["idEmpresa"] = $row["IDEMPRESA"];
	}
	else {
		$_SESSION["contrato"] = 0;
		$_SESSION["cuit"] = "";
		$_SESSION["empresa"] = "";
		$_SESSION["idEmpresa"] = 0;
	}
}


if (!isset($_SESSION["BUSQUEDA_DENUNCIA_CEM"]))
	$_SESSION["BUSQUEDA_DENUNCIA_CEM"] = array("buscar" => "S",
																						 "documento" => "",
																						 "fechaDenunciaDesde" => "",
																						 "fechaDenunciaHasta" => "",
																						 "fechaSiniestro" => "",
																						 "nombre" => "",
																						 "numeroDenunciaCEM" => "",
																						 "ob" => "4",
																						 "pagina" => 1);
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/denuncias_de_siniestros/buscar_en_cem_busqueda.php" id="formBusquedaCEM" method="post" name="formBusquedaCEM" target="iframeProcesando" onSubmit="return ValidarForm(formBusquedaCEM)">
	<div class="TituloSeccion" style="display:block; width:730px;">Finalización de Denuncias Iniciadas a través del 0800-333-1333</div>
	<div align="right" class="ContenidoSeccion" style="margin-right:4px; margin-top:5px;"><i>>> <a href="/denuncia-siniestros/terminos-y-condiciones">Términos y Condiciones de uso</a></i></div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<span>Seleccione en la grilla la denuncia iniciada a través de nuestro centro de Coordinación de Emergencias Médicas (CEM) para completar los datos en blanco.<br />Para agilizar la búsqueda, ingrese el número de denuncia que le otorgó el CEM; en caso de no recordarlo realice la búsqueda por número de documento o nombre del trabajador accidentado.</span>
		<div style="margin-left:36px; margin-top:16px;">
			<label for="numeroTransaccion">Nº de Denuncia CEM</label>
			<input id="numeroDenunciaCEM" name="numeroDenunciaCEM" style="width:98px;" title="Nº de Denuncia CEM" type="text" validarEntero="true" value="">
		</div>
		<div style="margin-top:4px;">
			<label for="documento">Documento del Trabajador</label>
			<input id="documento" name="documento" style="width:98px;" type="text" value="">
			<label for="nombre" style="margin-left:38px;">Nombre del Trabajador</label>
			<input id="nombre" name="nombre" style="width:264px;" type="text" value="">
		</div>
		<div style="margin-left:49px; margin-top:4px;">
			<label for="fechaSiniestro">Fecha de Siniestro</label>
			<input id="fechaSiniestro" maxlength="10" name="fechaSiniestro" style="width:64px;" title="Fecha de Siniestro" type="text" validarFecha="true" value="">
			<input class="botonFecha" id="btnFechaSiniestro" name="btnFechaSiniestro" style="vertical-align:-4px;" type="button">
			<label for="fechaDenunciaDesde" style="margin-left:20px;">Fecha de Denuncia</label>
			<label for="fechaDenunciaDesde">Desde</label>
			<input id="fechaDenunciaDesde" maxlength="10" name="fechaDenunciaDesde" style="width:64px;" title="Fecha de Denuncia Desde" type="text" validarFecha="true" value="">
			<input class="botonFecha" id="btnFechaDenunciaDesde" name="btnFechaDenunciaDesde" style="vertical-align:-4px;" type="button">
			<label for="fechaDenunciaHasta" style="margin-left:16px;">Hasta</label>
			<input id="fechaDenunciaHasta" maxlength="10" name="fechaDenunciaHasta" style="width:64px;" title="Fecha de Denuncia Hasta" type="text" validarFecha="true" value="">
			<input class="botonFecha" id="btnFechaDenunciaHasta" name="btnFechaDenunciaHasta" style="vertical-align:-4px;" type="button">
		</div>
		<div style="margin-bottom:16px; margin-top:8px;">
			<input class="btnBuscar" type="submit" value="" />
		</div>
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:-8px; margin-top:8px; overflow:auto; width:744px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
	<script type="text/javascript">
		Calendar.setup ({
			inputField: "fechaSiniestro",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaSiniestro"
		});
		Calendar.setup ({
			inputField: "fechaDenunciaDesde",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaDenunciaDesde"
		});
		Calendar.setup ({
			inputField: "fechaDenunciaHasta",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaDenunciaHasta"
		});
<?
	if ($_SESSION["BUSQUEDA_DENUNCIA_CEM"]["buscar"] == "S") {
?>
		document.getElementById('formBusquedaCEM').submit();
<?
	}
?>
		document.getElementById('numeroDenunciaCEM').focus();
	</script>
</form>