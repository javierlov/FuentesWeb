<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));


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

if (!isset($_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"]))
	$_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"] = array("buscar" => "N",
																										"cuil" => "",
																										"establecimiento" => -1,
																										"nombre" => "",
																										"ob" => "3",
																										"pagina" => 1);
require_once("index_combos.php");
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<style>
	#establecimiento {max-width:616px;}
</style>
<script type="text/javascript">
	function submitForm() {
		resultado = ValidarForm(formNominaTrabajadores);
		if (resultado) {
			document.getElementById('divContentGrid').style.display = 'none';
			document.getElementById('divProcesando').style.display = 'block';
		}
		return resultado;
	}
</script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/nomina_de_trabajadores/index_busqueda.php" id="formNominaTrabajadores" method="post" name="formNominaTrabajadores" target="iframeProcesando" onSubmit="submitForm()">
	<div class="TituloSeccion" style="display:block; width:730px;">Nómina de Trabajadores</div>
	<div class="ContenidoSeccion" align=right style="margin-top:5px;"><i>>> <a href="/nomina-trabajadores/terminos-y-condiciones">Términos y Condiciones de uso</a></i></div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<p>
			Informe las altas, bajas y modificaciones de su personal para mantener la nómina de su empresa actualizada. Esto le facilitará la emisión de certificados de cobertura y la realización de otro tipo de consultas.
			Para modificar o dar de baja de la nómina a un trabajador, búsquelo en la nómina, y luego haga clic sobre el nombre del trabajador para acceder a sus detalles, desde allí podrá modificarlo o darlo de baja.
			Haga clic <a class="linkSubrayado" href="/certificados-cobertura">aquí</a> para acceder a imprimir certificados de cobertura de su personal.
		</p>
		<div style="margin-left:42px; margin-top:20px;">
			<label>Nombre</label>
			<input autofocus id="nombre" name="nombre" style="text-transform:uppercase; width:240px;" type="text" value="<?= $_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"]["nombre"]?>" />
		</div>
		<div style="margin-left:43px; margin-top:4px;">
			<label>C.U.I.L.</label>
			<input id="cuil" maxlength="13" name="cuil" style="width:80px;" title="CUIL" type="text" validarCuit="true" value="<?= $_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"]["cuil"]?>" />
		</div>
		<div style="margin-top:4px;">
			<label>Establecimiento</label>
			<?= $comboEstablecimiento->draw();?>
		</div>
		<p style="margin-left:88px; margin-top:8px;">
			<input class="btnBuscar" type="submit" value="" />
		</p>
		<p>
			<i>Utilice el Nombre, C.U.I.L. y Establecimiento para buscar en la nómina de personal. Si no especifica ningún filtro, la búsqueda traerá la nómina completa.</i>
		</p>
<?
if (!isset($_SESSION["isAgenteComercial"])) {
?>
		<p style="margin-top:8px;">
			<img border="0" src="/modules/usuarios_registrados/images/alta_de_trabajador.jpg" style="cursor:pointer;" onClick="window.location.href='/nomina-trabajadores/alta-trabajador'">
		</p>
<?
}
?>
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-left:10px; margin-top:8px; overflow:auto; width:720px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</form>
<script type="text/javascript">
<?
if ($_SESSION["BUSQUEDA_NOMINA_TRABAJADORES"]["buscar"] == "S") {
?>
	if (submitForm())
		document.getElementById('formNominaTrabajadores').submit();
<?
}
?>
</script>