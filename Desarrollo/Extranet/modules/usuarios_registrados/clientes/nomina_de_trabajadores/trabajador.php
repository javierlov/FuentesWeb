<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


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


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));

$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT 1
		 FROM afi.act_cont_trab_noconf
		WHERE ct_fechabaja IS NULL
			AND ct_contrato = :contrato";
$noConfirmadoVisible = existeSql($sql, $params);

$tipoCliente = "isCliente";
if (isset($_SESSION["isAgenteComercial"]))
	$tipoCliente = "isAgenteComercial";

$establecimientos = "-1";
$isAlta = ($_REQUEST["id"] == -1);

if (($isAlta) and (!cuitAutorizado($_SESSION["idEmpresa"]))) {
?>
	<div class="TituloSeccion" style="display:block; width:712px;">Alta de Trabajador</div>
	<br />
	<div class="ContenidoSeccion" style="padding:4px;">Para efectuar altas y bajas de personal deberá realizarlo utilizando su clave fiscal a través del siguiente link: <a class="linkSubrayado" href="http://www.afip.gov.ar/" target="_blank">http://www.afip.gov.ar/</a> opción <i>Acceda con Clave Fiscal CUIT / CUIL / CDI</i>.</div>
	<div style="margin-top:336px;"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></div>
<?
	return;
}

$validarAltaTemprana = false;
if ($isAlta) {
	$params = array(":id" => $_SESSION["idEmpresa"]);
	$sql =
		"SELECT em_suss
			 FROM aem_empresa
			WHERE em_id = :id";
//	$validarAltaTemprana = (ValorSql($sql, "", $params) != 2);
	$validarAltaTemprana = false;
}
else {
	$curs = null;
	$params = array(":idempresa" => $_SESSION["idEmpresa"], ":id" => $_REQUEST["id"]);
	$sql = "BEGIN webart.get_trabajador(:data, :idempresa, :id); END;";
	$stmt = DBExecSP($conn, $curs, $sql, $params);
	$row = DBGetSP($curs);

	$params = array(":idrelacionlaboral" => $row["RELACIONLABORALID"]);
	$sql =
		"SELECT re_idestablecimiento
			 FROM cre_relacionestablecimiento
			WHERE re_idrelacionlaboral = :idrelacionlaboral";
	$stmt = DBExecSql($conn, $sql, $params);
	while ($rowEstablecimientos = DBGetQuery($stmt))
		$establecimientos.= ",".$rowEstablecimientos["RE_IDESTABLECIMIENTO"];

	if (!$row)
		echo '<p style="color:red">ERROR: Este trabajador no está asociado a la empresa '.$_SESSION["empresa"].'.</p>';
}

$dataTel = inicializarTelefonos(OCI_COMMIT_ON_SUCCESS, "tt_idtrabajador", $_REQUEST["id"], "tt", "att_telefonotrabajador", $_SESSION["usuario"]);
quitarTelefonosTemporales($dataTel);
copiarTelefonosATemp($dataTel, $_SESSION["usuario"]);

require_once("trabajador_combos.php");
?>
<style>
	#tipoContrato {margin-left:19px; max-width:512px;}
</style>
<script src="/modules/usuarios_registrados/clientes/js/nomina_trabajadores.js" type="text/javascript"></script>
<iframe id="iframeTrabajador" name="iframeTrabajador" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/nomina_de_trabajadores/procesar_trabajador.php" id="formTrabajador" method="post" name="formTrabajador" target="iframeTrabajador">
	<input id="domicilioManual" name="domicilioManual" type="hidden" value="<?= (!$isAlta)?$row["DOMICILIOMANUAL"]:"f"?>" />
	<input id="establecimientos" name="establecimientos" type="hidden" value="<?= $establecimientos?>" />
	<input id="fechaIngresoOld" name="fechaIngresoOld" type="hidden" value="<?= (!$isAlta)?$row["FECHAINGRESO"]:""?>" />
	<input id="id" name="id" type="hidden" value="<?= $_REQUEST["id"]?>">
	<input id="idCiuo" name="idCiuo" type="hidden" value="<?= (!$isAlta)?$row["CIUOID"]:""?>" />
	<input id="idProvincia" name="idProvincia" type="hidden" value="<?= (!$isAlta)?$row["PROVINCIAID"]:""?>" />
	<input id="idRelacionLaboral" name="idRelacionLaboral" type="hidden" value="<?= (!$isAlta)?$row["RELACIONLABORALID"]:""?>" />
	<div class="TituloSeccion" style="display:block; width:712px;"><?= ($isAlta)?"Alta":"Modificación"?> de Trabajador</div>
<?
if ($validarAltaTemprana) {
?>
	<div class="ContenidoSeccion" style="color:#4d4d4f; margin-top:4px;">Recuerde que esta opción de cortesía que le provee Provincia ART debe iniciarse efectuando el Alta del trabajador a través de Mi Simplificación, e incluyendo al mismo en las Declaraciones Juradas respectivas.</div>
<?
}
?>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<p>Los campos marcados con un asterisco (*) son obligatorios.</p>
		<div style="margin-left:113px;">
			<label>C.U.I.L. (*)</label>
			<input autofocus id="cuil" maxlength="11" name="cuil" style="width:80px;" type="text" value="<?= (!$isAlta)?$row["CUIL"]:""?>" <?= ($isAlta)?'onBlur="recuperarDatosTrabajador(this.value)"':"" ?> />
		</div>
		<div style="margin-left:55px; margin-top:4px;">
			<label>Nombre y Apellido (*)</label>
			<input id="nombre" maxlength="60" name="nombre" style="text-transform:uppercase; width:320px;" type="text" value="<?= (!$isAlta)?$row["NOMBRE"]:""?>" />
		</div>
		<div style="margin-left:18px; margin-top:4px;">
			<label>Código de Alta Temprana (*)</label>
			<input id="codigoAltaTemprana" maxlength="20" name="codigoAltaTemprana" <?= ($isAlta)?"":"readonly"?> style="<?= ($isAlta)?"":"background-color:#ccc;"?> text-transform:uppercase; width:128px;" type="text" value="<?= (!$isAlta)?$row["CODIGOALTATEMPRANA"]:""?>" />
		</div>
		<div style="margin-left:128px; margin-top:4px;">
			<label>Sexo (*)</label>
			<?= $comboSexo->draw();?>
		</div>
		<div style="margin-left:85px; margin-top:4px;">
			<label>Nacionalidad (*)</label>
			<?= $comboNacionalidad->draw();?>
			<span id="spanOtraNacionalidad" style="margin-left:16px;">
				<label for="otraNacionalidad">Especificar</label>
				<input id="otraNacionalidad" maxlength="30" name="otraNacionalidad" style="width:120px;" type="text" value="<?= (!$isAlta)?$row["OTRANACIONALIDAD"]:""?>" />
			</span>
		</div>
		<div style="margin-left:42px; margin-top:4px;">
			<label>Fecha de Nacimiento (*)</label>
			<input id="fechaNacimiento" maxlength="10" name="fechaNacimiento" style="width:64px;" type="text" value="<?= (!$isAlta)?$row["FECHANACIMIENTO"]:""?>" />
			<input class="botonFecha" id="btnFechaNacimiento" name="btnFechaNacimiento" style="vertical-align:-4px;" type="button" value="" />
		</div>
		<div style="margin-left:91px; margin-top:4px;">
			<label>Estado Civil (*)</label>
			<?= $comboEstadoCivil->draw();?>
		</div>
		<div style="margin-left:121px; margin-top:4px;">
			<label>e-Mail</label>
			<input id="email" maxlength="250" name="email" style="margin-left:21px; width:320px;" title="e-Mail" type="text" validarEmail="true" value="<?= (!$isAlta)?$row["EMAIL"]:""?>" />
		</div>
		<div style="margin-top:4px;">
			<label>F. de Ingreso en la Empresa (*)</label>
			<input id="fechaIngreso" maxlength="10" name="fechaIngreso" style="width:64px;" type="text" value="<?= (!$isAlta)?$row["FECHAINGRESO"]:""?>" />
			<input class="botonFecha" id="btnFechaIngreso" name="btnFechaIngreso" style="vertical-align:-4px;" type="button" value="" />
		</div>
		<div style="margin-top:4px;">
			<input class="btnAltaMultiplesEstablecimientos" style="margin-left:2px;" type="button" value="" onClick="buscarEstablecimiento('<?= (!$isAlta)?$row["RELACIONLABORALID"]:"0"?>')" />
			<iframe frameborder="no" height="0" id="iframeEstablecimientos" name="iframeEstablecimientos" scrolling="no" src="/modules/usuarios_registrados/clientes/nomina_de_trabajadores/establecimientos.php?rl=<?= (!$isAlta)?$row["RELACIONLABORALID"]:"0"?>&e=<?= $establecimientos?>" width="720" onLoad="ajustarTamanoIframe(this, 64)"></iframe>
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:66px;">Tipo de Contrato</label>
			<?= $comboTipoContrato->draw();?>
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:124px;">Tarea</label>
			<input id="tarea" maxlength="150" name="tarea" style="margin-left:20px; width:400px;" type="text" value="<?= (!$isAlta)?$row["TAREA"]:""?>" />
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:120px;">Sector</label>
			<input id="sector" maxlength="150" name="sector" style="margin-left:20px; width:400px;" type="text" value="<?= (!$isAlta)?$row["SECTOR"]:""?>" />
		</div>
		<div style="margin-top:4px; position:relative;">
			<div align="left" style="float:right; max-width:400px; position:relative; top:-4px; width:480px;">
				<span id="ciuo" style="width:280px;"><?= ((!$isAlta) and ($row["CIUODESCRIPCION"] != ""))?$row["CIUODESCRIPCION"]:"Utilice el buscador para seleccionar el CIUO."?></span>
				<img border="0" id="imgQuitarCiuo" src="/modules/usuarios_registrados/images/cruz.gif" style="cursor:pointer; vertical-align:-8px; visibility:hidden;" title="Quitar CIUO" onClick="quitarCiuo()" />
			</div>
			<div>
				<label style="margin-left:127px;">CIUO</label>
				<input class="btnBuscar" id="buscarCiuo" style="margin-left:20px; vertical-align:-3px;" type="button" value="" onClick="agregarCiuo()" />
			</div>
		</div>
		<div style="margin-top:4px;">
			<label style="margin-left:78px;">Remuneración</label>
			<input id="remuneracion" maxlength="15" name="remuneracion" style="margin-left:20px; width:104px;" title="Remuneración" type="text" validarFloat="true" value="<?= (!$isAlta)?$row["REMUNERACION"]:""?>" onBlur="reemplazarPuntoXComa(this)" />
		</div>
<?
if ($noConfirmadoVisible) {
?>
		<div style="margin-top:4px;">
			<label style="margin-left:23px;">No confirmado al puesto</label>
			<input <?= ((!$isAlta) and ($row["CONFIRMAPUESTO"] == "N"))?"checked":""?> id="noConfirmadoPuesto" name="noConfirmadoPuesto" style="margin-left:20px; vertical-align:-3px;" type="checkbox" />
		</div>
<?
}
elseif ($row["CONFIRMAPUESTO"] == "N") {
?>
		<input id="noConfirmadoPuesto" name="noConfirmadoPuesto" type="hidden" value="ok" />
<?
}
?>
		<div class="TituloTablaCeleste" <div style="margin-left:-4px; margin-top:16px;">Domicilio del Trabajador</div>
		<div>
<?
$hayDatos = ((!$isAlta) and ($row["CALLE"] != ""));
if (!$hayDatos) {
?>
			<p id="pSinDatosconocidos" style="margin-left:56px; margin-top:16px;">
				<span>- Sin Datos Conocidos -</span>
			</p>
<?
}
?>
			<div id="divDatosDomicilio" style="display:<?= ($hayDatos)?"block":"none"?>;">
				<div style="margin-left:24px; margin-top:16px;">
					<label for="calle">Calle</label>
					<input id="calle" maxlength="60" name="calle" readonly style="background-color:#ccc; width:500px;" type="text" value="<?= (!$isAlta)?$row["CALLE"]:""?>">
				</div>
				<div style="margin-left:8px; margin-top:4px;">
					<label for="numero">Número</label>
					<input id="numero" maxlength="6" name="numero" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["NUMERO"]:""?>">
					<label for="piso" style="margin-left:16px;">Piso</label>
					<input id="piso" maxlength="6" name="piso" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["PISO"]:""?>">
					<label for="departamento" style="margin-left:16px;">Departamento</label>
					<input id="departamento" maxlength="6" name="departamento" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["DEPARTAMENTO"]:""?>">
				</div>
				<div style="margin-top:4px;">
					<label for="localidad">Localidad</label>
					<input id="localidad" maxlength="85" name="localidad" readonly style="background-color:#ccc; width:320px;" type="text" value="<?= (!$isAlta)?$row["LOCALIDAD"]:""?>">
					<label for="codigoPostal" style="margin-left:16px;">Código Postal</label>
					<input id="codigoPostal" maxlength="5" name="codigoPostal" readonly style="background-color:#ccc; width:67px;" type="text" value="<?= (!$isAlta)?$row["CPOSTAL"]:""?>">
				</div>
				<div style="margin-top:4px;">
					<label for="provincia">Provincia</label>
					<input id="provincia" name="provincia" readonly style="background-color:#ccc; width:320px;" type="text" value="<?= (!$isAlta)?$row["PROVINCIA"]:""?>">
				</div>
			</div>
			<p style="margin-left:56px; margin-top:8px;">
				<img border="0" src="/modules/usuarios_registrados/images/boton_modificar_domicilio.jpg" style="cursor:pointer;" onClick="buscarDomicilio(true, 'pSinDatosconocidos', 'divDatosDomicilio', 'idProvincia', 'provincia', 'localidad', '', 'codigoPostal', 'calle', 'numero', 'piso', 'departamento', 'domicilioManual', 0, 680, 0, 0);">
			</p>
		</div>
		<div style="margin-left:-4px; margin-top:4px;">
			<iframe frameborder="no" height="0" id="iframeTelefonos" name="iframeTelefonos" scrolling="no" src="/functions/telefonos/telefonos.php?s=<?= $tipoCliente?>&idModulo=-1&idTablaPadre=<?= $_REQUEST["id"]?>&tablaTel=att_telefonotrabajador&campoClave=tt_idtrabajador&prefijo=tt" width="720" onLoad="ajustarTamanoIframe(this, 192)"></iframe>
		</div>
<?
if (!isset($_SESSION["isAgenteComercial"])) {
?>
		<p style="margin-top:16px;">
<!--			<input class="btnGrabar" id="btnGuardar" style="display:<?= ($row)?"block":"none"?>;" type="button" value="" onClick="guardarTrabajador()" />-->
			<input class="btnGrabar" id="btnGuardar" type="button" value="" onClick="guardarTrabajador()" />
			<img border="0" id="imgProcesando" src="/modules/usuarios_registrados/images/procesando.gif" style="display:none; vertical-align:-3px;" title="Procesando.&#13;Aguarde un instante, por favor..." />
			<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-top:8px; padding:2px; width:192px;">&nbsp;Datos guardados exitosamente.</p>
		</p>
<?
}
?>
		<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
	</div>
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaIngreso",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaIngreso"
	});
	Calendar.setup ({
		inputField: "fechaNacimiento",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaNacimiento"
	});

	cambiaNacionalidad('<?= ((!$isAlta)?$row["NACIONALIDADID"]:-1)?>', document);
	document.getElementById('imgQuitarCiuo').style.visibility = '<?= ((!$isAlta) and ($row["CIUODESCRIPCION"] != ""))?"visible":"hidden"?>';

	setTimeout("ajustarTamanoIframe(document.getElementById('iframeTelefonos'), 192)", 2000);
</script>