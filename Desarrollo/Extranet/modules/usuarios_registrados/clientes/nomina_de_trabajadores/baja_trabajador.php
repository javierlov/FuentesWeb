<?
function cuitAutorizado($idEmpresa) {
	global $conn;

	$params = array(":id" => $idEmpresa);
	$sql =
		"SELECT 1
			 FROM art.aca_cuitautorizado, aem_empresa
			WHERE ca_cuit = em_cuit
				AND ca_fechabaja IS NULL
				AND em_id = :id";
	return (!existeSql($sql, $params));
}

function getSexo($sexo) {
	switch ($sexo) {
		case 'F':
			return 'Femenino';
		case 'M':
			return 'Masculino';
		default:
			return '';
	}
}

validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));


if (!cuitAutorizado($_SESSION["idEmpresa"])) {
?>
	<div class="TituloSeccion" style="display:block; width:730px;">Baja de Trabajador</div>
	<br />
	<div class="ContenidoSeccion" style="padding:4px;">Para efectuar altas y bajas de personal deberá realizarlo utilizando su clave fiscal a través del siguiente link: <a class="linkSubrayado" href="http://www.afip.gov.ar/" target="_blank">http://www.afip.gov.ar/</a> opción <i>Acceda con Clave Fiscal CUIT / CUIL / CDI</i>.</div>
	<div style="margin-top:336px;"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></div>
<?
	return;
}

setDateFormatOracle("DD/MM/YYYY");

$curs = null;
$params = array(":idempresa" => $_SESSION["idEmpresa"], ":id" => $_REQUEST["id"]);
$sql = "BEGIN webart.get_trabajador(:data, :idempresa, :id); END;";
$stmt = DBExecSP($conn, $curs, $sql, $params);
$row = DBGetSP($curs);

if (!$row)
	echo '<p style="color:red">ERROR: Este trabajador no está asociado a la empresa '.$_SESSION["empresa"].'.</p>';
?>
<script src="/modules/usuarios_registrados/clientes/js/nomina_trabajadores.js" type="text/javascript"></script>
<iframe id="iframeTrabajador" name="iframeTrabajador" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/nomina_de_trabajadores/procesar_trabajador.php" id="formTrabajador" method="post" name="formTrabajador" target="iframeTrabajador">
	<input id="baja" name="baja" type="hidden" value="t" />
	<input id="contrato" name="contrato" type="hidden" value="<?= $row["CONTRATO"]?>" />
	<input id="domicilioManual" name="domicilioManual" type="hidden" value="f" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>" />
	<input id="idRelacionLaboral" name="idRelacionLaboral" type="hidden" value="<?= $row["RELACIONLABORALID"]?>" />
	<div class="TituloSeccion" style="display:block; width:730px;">Baja de Trabajador</div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<div class="TituloTablaCeleste"><?= $row["NOMBRE"]?></div>
		<table cellpadding="2" style="margin-top:16px;">
			<tr>
				<td valign="top">CUIL</td>
				<td style="font-weight:bold;" valign="top"><?= $row["CUIL"]?></td>
				<td style="width:24px;"></td>
				<td valign="top"></td>
				<td valign="top"></td>
			</tr>
			<tr>
				<td valign="top">Sexo</td>
				<td style="font-weight:bold;" valign="top"><?= getSexo($row["SEXO"])?></td>
				<td></td>
				<td valign="top">Nacionalidad</td>
				<td style="font-weight:bold;" valign="top"><?= $row["NACIONALIDAD"]?></td>
			</tr>
			<tr>
				<td valign="top">Fecha de Nacimiento</td>
				<td style="font-weight:bold;" valign="top"><?= $row["FECHANACIMIENTO"]?></td>
				<td></td>
				<td valign="top">Estado Civil</td>
				<td style="font-weight:bold;" valign="top"><?= $row["ESTADOCIVIL"]?></td>
			</tr>
			<tr>
				<td valign="top">Fecha de Ingreso en la Empresa</td>
				<td style="font-weight:bold;" valign="top"><?= $row["FECHAINGRESO"]?></td>
				<td></td>
				<td valign="top">Establecimiento</td>
				<td style="font-weight:bold;" valign="top"><?= $row["ESTABLECIMIENTO"]?></td>
			</tr>
			<tr>
				<td valign="top">Tipo de Contrato</td>
				<td style="font-weight:bold;" valign="top"><?= $row["MODALIDADCONTRATACION"]?></td>
				<td></td>
				<td valign="top">Tarea</td>
				<td style="font-weight:bold;" valign="top"><?= $row["TAREA"]?></td>
			</tr>
			<tr>
				<td valign="top">Sector</td>
				<td style="font-weight:bold;" valign="top"><?= $row["SECTOR"]?></td>
				<td></td>
				<td valign="top">CIUO</td>
				<td style="font-weight:bold;" valign="top"><?= $row["CIUODESCRIPCION"]?></td>
			</tr>
			<tr>
				<td valign="top">Remuneración</td>
				<td style="font-weight:bold;" valign="top">$ <?= $row["REMUNERACION"]?></td>
				<td></td>
				<td valign="top">Domicilio</td>
				<td style="font-weight:bold;" valign="top"><?= $row["DOMICILIO"]?></td>
			</tr>
		</table>
		<div style="margin-top:8px;">
			<label for="fechaEgreso">Fecha de Egreso de la Empresa</label>
			<input autofocus id="fechaEgreso" maxlength="10" name="fechaEgreso" style="width:80px;" type="text" value="" />
			<input class="botonFecha" id="btnFechaEgreso" name="btnFechaEgreso" type="button" value="" />
		</div>
		<div style="margin-top:12px;">
			<input class="btnGrabar" style="display:<?= ($row)?"block":"none"?>;" type="button" value="" onClick="bajaTrabajador()" />
			<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
			<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; float:left; margin-top:8px; padding:2px; width:192px;">&nbsp;Datos guardados exitosamente.</p>
		</div>
	</div>
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaEgreso",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaEgreso"
	});
</script>