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

if (!isset($_SESSION["BUSQUEDA_DENUNCIA_SINIESTROS"]))
	$_SESSION["BUSQUEDA_DENUNCIA_SINIESTROS"] = array("buscar" => "N",
																										"documento" => "",
																										"fechaSiniestro" => "",
																										"nombre" => "",
																										"numeroSiniestro" => "",
																										"numeroTransaccion" => "",
																										"ob" => "2_D_",
																										"pagina" => 1);
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/denuncias_de_siniestros/buscar_siniestro_busqueda.php" id="formDenunciasSiniestros" method="post" name="formDenunciasSiniestros" target="iframeProcesando" onSubmit="return ValidarForm(formDenunciasSiniestros)">
<?
if (isset($_REQUEST["id"])) {
?>
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"] ?>" />
<?
}
?>
	<div class="TituloSeccion" style="display:block; width:730px;">Consulta de Denuncias Realizadas</div>
	<div align="right" class="ContenidoSeccion" style="margin-right:4px; margin-top:5px;"><i>>> <a href="/denuncia-siniestros/terminos-y-condiciones">Términos y Condiciones de uso</a></i></div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<span>Utilice los siguientes filtros para buscar entre las denuncias realizadas. </span>
		<div style="margin-left:49px; margin-top:16px;">
			<label for="numeroTransaccion">Nº de Transacción</label>
			<input id="numeroTransaccion" maxlength="10" name="numeroTransaccion" style="width:80px;" title="Nº de Transacción" type="text" validarEntero="true" value="">
			<label for="fechaSiniestro" style="margin-left:16px;">Fecha de Siniestro</label>
			<input id="fechaSiniestro" maxlength="10" name="fechaSiniestro" style="width:64px;" title="Fecha de Siniestro" type="text" validarFecha="true" value="">
			<input class="botonFecha" id="btnFechaSiniestro" name="btnFechaSiniestro" style="vertical-align:-4px;" type="button">
			<label for="numeroSiniestro" style="margin-left:16px;">Nº de Siniestro</label>
			<input id="numeroSiniestro" name="numeroSiniestro" style="width:98px;" title="Nº de Siniestro" type="text" validarEntero="true" value="">
		</div>
		<div style="margin-top:4px;">
			<label for="documento">Documento del Trabajador</label>
			<input id="documento" name="documento" style="width:80px;" type="text" value="">
			<label for="nombre" style="margin-left:16px;">Nombre del Trabajador</label>
			<input id="nombre" name="nombre" style="width:290px;" type="text" value="">
		</div>
		<div style="margin-top:8px;">
			<input class="btnBuscar" type="submit" value="" />
			<span style="margin-left:16px;">Si no se especifica ningún filtro, se detalla la lista completa.</span>
		</div>
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:-10px; margin-top:8px; overflow:auto; width:728px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
	<script type="text/javascript">
		Calendar.setup (
			{
				inputField: "fechaSiniestro",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaSiniestro"
			}
		);

<?
if ($_SESSION["BUSQUEDA_DENUNCIA_SINIESTROS"]["buscar"] == "S") {
?>
		document.getElementById('formDenunciasSiniestros').submit();
<?
}
?>
		document.getElementById('numeroTransaccion').focus();
	</script>
</form>