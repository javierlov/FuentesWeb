<?
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 95));


function getTipoFormulario() {
	global $row;

	switch ($_REQUEST["t"]) {
		case "n":
			return "AO";
		case "p":
			return $row["TIPOFORMULARIO"];
		default:
			return strtoupper($_REQUEST["t"]);
	}
}

function getTipoResolucion($tipoResolucion) {
	$result = " (Resolución ";
	switch ($tipoResolucion) {
		case 1:
			$result.= "319/1999)";
			break;
		case 2:
			$result.= "35/1998)";
			break;
		case 3:
			$result.= "51/1997)";
			break;
		default:
			$result = "";
	}

	return $result;
}


SetDateFormatOracle("DD/MM/YYYY");

if (!isset($_REQUEST["r"]))		// Es el número de resolución por el que entra desde el menú..
	$_REQUEST["r"] = -1;

if (isset($_REQUEST["id"])) {		// [0] = id del aviso de obra, [1] = id del aviso de obra web, [2] = tipo de resolución..
	$ids = explode("_", $_REQUEST["id"]);
}
else {
	$ids = array(-1, -1, -1);
}

$isAlta = ($ids[0] == -1);
if ($isAlta)
	$titulo = "Nuevo Aviso de Obra";
else {
	$params = array(":contrato" => $_SESSION["contrato"], ":idavisoobra" => $ids[0], ":idavisoobraweb" => $ids[1]);
	$sql =
		"SELECT 1
			 FROM DUAL
			WHERE art.webart.exists_avisodeobra(:contrato, :idavisoobra, :idavisoobraweb) = 1";
	validarSesion(existeSql($sql, $params));

	$titulo = "";
	switch ($_REQUEST["t"]) {
		case "e":
			$titulo = "Extender Obra";
			break;
		case "m":
			$titulo = "Modificar Aviso";
			break;
		case "n":
			$titulo = "Nuevo Aviso";
			break;
		case "p":
			$titulo = "Presentación";
			break;
		case "s":
			$titulo = "Suspender Obra";
			break;
		case "sd":
			$titulo = "Suspender Definitivamente Obra";
			break;
	}
}
?>
<style>
	.bloque {border:solid #00539b 1px; margin-bottom:16px;}
	.cabecera {background-color:#00539b; color:#fff; cursor:pointer; font-weight:bold; padding:2px; padding-left:4px;}
	.check {vertical-align:-3px;}
	.datos {color:#000; display:inline-block;}
	.fila {padding:2px; padding-top:4px;}
	.obradorSiNo {font-size:13px; font-weight:bold; margin-bottom:8px; margin-left:160px;}
</style>
<script src="/modules/usuarios_registrados/clientes/js/aviso_obra.js?rnd=20040717" type="text/javascript"></script>
<iframe id="iframeAvisoObra" name="iframeAvisoObra" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/aviso_obra/procesar_aviso_obra.php" id="formAvisoObra" method="post" name="formAvisoObra" target="iframeAvisoObra" onSubmit="enviarForm()">
	<input id="idAvisoObra" name="idAvisoObra" type="hidden" value="<?= $ids[0]?>" />
	<input id="idAvisoObraWeb" name="idAvisoObraWeb" type="hidden" value="<?= $ids[1]?>" />
	<input id="origen" name="origen" type="hidden" value="<?= $_REQUEST["t"]?>" />
	<input id="telefonosCargados" name="telefonosCargados" type="hidden" value="" />
	<div class="TituloSeccion" style="width:730px;">Aviso de Obra - <?= $titulo?><span id="spanTipoResolucion"></span></div>
	<div class="ContenidoSeccion" style="margin-top:20px; width:712px;">
<?
$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT em_nombre razonsocial, art.utiles.armar_cuit(em_cuit) cuit, co_contrato contrato, art.utiles.armar_domicilio(dc_calle, dc_numero, dc_piso, dc_departamento, '') domiciliolegal,
					dc_localidad localidad, pv_descripcion provincia, dc_cpostala, dc_mail, dc_codareatelefonos, dc_telefonos, ac_codigo, ac_descripcion
		 FROM afi.aco_contrato, afi.aem_empresa, adc_domiciliocontrato adc, art.cpv_provincias, comunes.cac_actividad
		WHERE co_contrato = :contrato
			AND co_idempresa = em_id
			AND dc_contrato = co_contrato
			AND dc_tipo = 'L'
			AND dc_provincia = pv_codigo
			AND co_idactividad = ac_id(+)";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
		<div class="bloque">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>DATOS DE LA EMPRESA</span>
				<img align="right" border="0" src="/images/minus16.png" style="position:relative; top:-2px;" title="Contraer" />
			</div>
			<div id="divDatosEmpresa">
				<div class="fila">
					<label>Razón Social</label>
					<span class="datos"><?= $row["RAZONSOCIAL"]?></span>
					<label style="margin-left:16px;">C.U.I.T.</label>
					<span class="datos"><?= $row["CUIT"]?></span>
					<label style="margin-left:16px;">Contrato</label>
					<span class="datos"><?= $row["CONTRATO"]?></span>
				</div>
				<div class="fila">
					<label>Domicilio Legal</label>
					<span class="datos"><?= $row["DOMICILIOLEGAL"]?></span>
					<label style="margin-left:16px;">Localidad</label>
					<span class="datos"><?= $row["LOCALIDAD"]?></span>
					<label style="margin-left:16px;">Provincia</label>
					<span class="datos"><?= $row["PROVINCIA"]?></span>
				</div>
				<div class="fila">
					<label>C.P.A.</label>
					<span class="datos"><?= $row["DC_CPOSTALA"]?></span>
					<label style="margin-left:16px;">e-Mail</label>
					<span class="datos"><?= $row["DC_MAIL"]?></span>
					<label style="margin-left:16px;">Teléfono</label>
					<span class="datos"><?= $row["DC_CODAREATELEFONOS"]." ".$row["DC_TELEFONOS"]?></span>
				</div>
				<div class="fila">
					<label>C.I.I.U.</label>
					<span class="datos"><?= $row["AC_CODIGO"]?></span>
					<label style="margin-left:16px;">Actividad (C.I.I.U.)</label>
					<span class="datos"><?= $row["AC_DESCRIPCION"]?></span>
				</div>
			</div>
		</div>

<?
$params = array(":idavisoobraweb" => $ids[1]);
$sql =
	"SELECT art.utiles.armar_domicilio(es_calle, es_numero, es_piso, es_departamento, es_localidad) direccion, aw_idestab319 id, es_nombre nombre
		 FROM hys.haw_avisoobraweb, afi.aes_establecimiento
		WHERE aw_idestab319 = es_id
			AND aw_id = :idavisoobraweb";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
		<div class="bloque" id="divObradorMain" style="display:<?= (($_REQUEST["r"] == 1) or ($ids[2] == 1))?"block":"none"?>;">
			<input id="caracteristicasObrador" name="caracteristicasObrador" type="hidden" value="" />
			<input id="idObrador" name="idObrador" type="hidden" value="<?= $row["ID"]?>" />
			<input id="validarObrador" name="validarObrador" type="hidden" value="<?= (($_REQUEST["r"] == 1) or ($ids[2] == 1))?"S":"N"?>" />
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>OBRADOR</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divObrador" style="display:none;">
				<div class="fila">
					<label for="nombreObrador" style="margin-left:6px;">Nombre</label>
					<input id="nombreObrador" name="nombreObrador" readonly style="background-color:#ccc; width:624px;" type="text" value="<?= $row["NOMBRE"]?>">
				</div>
				<div style="margin-top:4px;">
					<label for="direccionObrador">Dirección</label>
					<input id="direccionObrador" name="direccionObrador" readonly style="background-color:#ccc; width:624px;" type="text" value="<?= $row["DIRECCION"]?>">
				</div>
				<p style="margin-left:57px; margin-top:8px;">
					<img border="0" src="/modules/usuarios_registrados/images/boton_seleccionar_obrador.jpg" style="cursor:pointer;" onClick="seleccionarObrador()">
				</p>
				<div class="fila">
					<label>¿ La obra cuenta con alguna de las siguientes características ?</label>
					<ul>
						<li>Trabajos de excavación.</li>
						<li>Trabajos de demolición.</li>
						<li>Trabajos en cercanías de alta y/o media tensión.</li>
						<li>Realiza izaje.</li>
						<li>Utilizan silletas y andamios.</li>
						<li>Obra de más de 1000m2 de superficie cubierta.</li>
						<li>Trabajos a más 4mts de altura con riesgo de caída al vacío.</li>
						<li>Utilizan ascensores, montacargas o montapersonas.</li>
					</ul>
				</div>
				<div class="fila obradorSiNo">
					<span id="spanObradorSi" style="cursor:pointer; padding:4px;" onClick="seleccionarCaracteristicasObrador('S')">SÍ</span>
					<span style="margin-left:8px; margin-right:8px;"></span>
					<span id="spanObradorNo" style="cursor:pointer; padding:4px;" onClick="seleccionarCaracteristicasObrador('N')">NO</span>
				</div>
			</div>
		</div>

<?
$params = array(":idavisoobra" => $ids[0], ":idavisoobraweb" => $ids[1]);
$sql =
	"SELECT es_calle calle, es_numero numero, es_localidad localidad, es_provincia idprovincia, pv_descripcion provincia, es_cpostal cpostal, es_cpostala cpostala,
					ao_observaciones observaciones, 'AO' tipoformulario, -1 resolucion, 1 tipoform, es_nroestableci nroestableci
		 FROM art.cpv_provincias, art.pao_avisoobra, afi.aem_empresa, afi.aco_contrato, afi.aes_establecimiento
		WHERE es_contrato = co_contrato
			AND em_id = co_idempresa
			AND em_cuit = ao_cuit
			AND es_nroestableci = ao_estableci
			AND co_contrato = art.get_vultcontrato(em_cuit)
			AND pv_codigo = es_provincia
			AND es_eventual = 'S'
			AND es_idestabeventual = 1
			AND ao_fechabaja IS NULL
			AND ao_id = :idavisoobra
UNION ALL
	 SELECT aw_calle, aw_numero, aw_localidad, aw_provincia, pv_descripcion, aw_cpostal, aw_cpa,
					aw_descripcionobra, aw_tipo, aw_resolucion, aw_tipoform, aw_estableci
		 FROM art.cpv_provincias, hys.haw_avisoobraweb
		WHERE aw_fechabaja IS NULL
			AND aw_idavisoobra IS NULL
			AND pv_codigo = aw_provincia
			AND aw_id = :idavisoobraweb";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$camposEnabled = (($_REQUEST["t"] == "e") or
									($_REQUEST["t"] == "m") or
									($_REQUEST["t"] == "n") or
								 (($_REQUEST["t"] == "p") and ($ids[0] == -1) and (($row["TIPOFORMULARIO"] == "AO") or
																																	 ($row["TIPOFORMULARIO"] == "E") or
																																	 ($row["TIPOFORMULARIO"] == "M") or
																																	 ($row["TIPOFORMULARIO"] == "R"))));

$camposDatosResponsableEnabled = (($_REQUEST["t"] == "e") or
																	($_REQUEST["t"] == "m") or
																	($_REQUEST["t"] == "n") or
																 (($_REQUEST["t"] == "p") and ($ids[0] == -1) and (($row["TIPOFORMULARIO"] == "AO") or
																																									 ($row["TIPOFORMULARIO"] == "E") or
																																									 ($row["TIPOFORMULARIO"] == "M") or
																																									 ($row["TIPOFORMULARIO"] == "R"))) or
																	($_REQUEST["t"] == "s") or
																	($_REQUEST["t"] == "sd"));

$fechaExtensionEnabled = ((($_REQUEST["t"] == "m") and ($row["TIPOFORMULARIO"] == "E")) or
													(($_REQUEST["t"] == "p") and ($ids[0] == -1) and ($row["TIPOFORMULARIO"] == "E")) or
													 ($_REQUEST["t"] == "e"));
$fechaExtensionVisible = (($row["TIPOFORMULARIO"] == "E") or ($_REQUEST["t"] == "e"));

$fechaFinalizacionEnabled = ((($_REQUEST["t"] == "m") and ($row["TIPOFORMULARIO"] == "AO")) or
															($_REQUEST["t"] == "n") or
														 (($_REQUEST["t"] == "p") and ($ids[0] == -1) and ($row["TIPOFORMULARIO"] == "AO")));

$fechaInicioEnabled = ((($_REQUEST["t"] == "m") and ($row["TIPOFORMULARIO"] == "AO")) or
												($_REQUEST["t"] == "n") or
											 (($_REQUEST["t"] == "p") and ($ids[0] == -1) and ($row["TIPOFORMULARIO"] == "AO")));

$fechaReinicioEnabled = ((($_REQUEST["t"] == "m") and (($row["TIPOFORMULARIO"] == "R") or ($row["TIPOFORMULARIO"] == "S"))) or
												 (($_REQUEST["t"] == "p") and ($ids[0] == -1) and (($row["TIPOFORMULARIO"] == "R") or ($row["TIPOFORMULARIO"] == "S"))) or
													($_REQUEST["t"] == "s"));
$fechaReinicioVisible = (($_REQUEST["t"] == "s") or ($row["TIPOFORMULARIO"] == "R") or ($row["TIPOFORMULARIO"] == "S"));

$fechaSuspensionEnabled = ((($_REQUEST["t"] == "m") and (($row["TIPOFORMULARIO"] == "S") or ($row["TIPOFORMULARIO"] == "SD"))) or
													 (($_REQUEST["t"] == "p") and ($ids[0] == -1) and (($row["TIPOFORMULARIO"] == "S") or ($row["TIPOFORMULARIO"] == "SD"))) or
														($_REQUEST["t"] == "s") or
														($_REQUEST["t"] == "sd"));
$fechaSuspensionVisible = (($_REQUEST["t"] == "s") or ($_REQUEST["t"] == "sd") or ($row["TIPOFORMULARIO"] == "S") or ($row["TIPOFORMULARIO"] == "SD"));

$formChico = ($row["TIPOFORM"] != 0)?"S":"N";
$tipoResolucion = getTipoResolucion(($row["RESOLUCION"] == "")?$_REQUEST["r"]:$row["RESOLUCION"]);
?>
		<input id="idProvincia" name="idProvincia" type="hidden" value="<?= $row["IDPROVINCIA"]?>" />
		<input id="idResolucion" name="idResolucion" type="hidden" value="<?= ($row["RESOLUCION"] == "")?$_REQUEST["r"]:$row["RESOLUCION"]?>" />
		<input id="numeroEstablecimiento" name="numeroEstablecimiento" type="hidden" value="<?= $row["NROESTABLECI"]?>" />
		<input id="tipoForm" name="tipoForm" type="hidden" value="<?= ($row["TIPOFORM"] == "")?(($_REQUEST["r"] == 1)?0:1):$row["TIPOFORM"]?>" />
		<input id="tipoFormulario" name="tipoFormulario" type="hidden" value="<?= getTipoFormulario()?>" />
		<div class="bloque">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>DATOS DE LA OBRA</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divDatosObra" style="display:none;">
				<div class="fila">
<?
$hayDatos = ($row["CALLE"] != "");
if (!$hayDatos) {
?>
	<p id="pSinDatosconocidos" style="margin-left:76px; margin-top:8px;">
		<span>- Sin Domicilio Cargado -</span>
	</p>
<?
}
?>
					<div id="divDatosDomicilio" style="display:<?= ($hayDatos)?"block":"none"?>;">
						<div style="margin-left:18px; margin-top:8px;">
							<label for="calle">Calle/Ruta</label>
							<input id="calle" maxlength="60" name="calle" readonly style="background-color:#ccc; width:464px;" type="text" value="<?= $row["CALLE"]?>">
							<label for="numero" style="margin-left:16px;">Nº/KM</label>
							<input id="numero" maxlength="6" name="numero" <?= ($isAlta)?"":"readonly"?> style="width:76px;" type="text" value="<?= $row["NUMERO"]?>">
						</div>
						<div style="margin-top:4px;">
							<label for="codigoPostal">Código Postal</label>
							<input id="codigoPostal" maxlength="5" name="codigoPostal" readonly style="background-color:#ccc; width:67px;" type="text" value="<?= $row["CPOSTAL"]?>">
							<label for="cpa" style="margin-left:16px;">C.P.A.</label>
							<input id="cpa" maxlength="8" name="cpa" readonly style="background-color:#ccc; width:67px;" type="text" value="<?= $row["CPOSTALA"]?>">
						</div>
						<div style="margin-left:24px; margin-top:4px;">
							<label for="localidad">Localidad</label>
							<input id="localidad" maxlength="85" name="localidad" readonly style="background-color:#ccc; width:464px;" type="text" value="<?= $row["LOCALIDAD"]?>">
						</div>
						<div style="margin-left:24px; margin-top:4px;">
							<label for="provincia">Provincia</label>
							<input id="provincia" name="provincia" readonly style="background-color:#ccc; width:464px;" type="text" value="<?= $row["PROVINCIA"]?>">
						</div>
					</div>
<?
	if ($isAlta) {
?>
					<p style="margin-left:80px; margin-top:8px;">
						<img border="0" src="/modules/usuarios_registrados/images/boton_modificar_domicilio.jpg" style="cursor:pointer;" onClick="buscarDomicilio(true, 'pSinDatosconocidos', 'divDatosDomicilio', 'idProvincia', 'provincia', 'localidad', 'cpa', 'codigoPostal', 'calle', 'numero', '', '', '', 0, 680, 0, 0);">
					</p>
<?
}
?>
				</div>
				<div class="fila">
					<label>Descripción detallada del tipo de obra</label>
					<input id="observaciones" maxlength="2000" name="observaciones" <?= ($isAlta)?"":"readonly"?> style="width:472px;" type="text" value="<?= $row["OBSERVACIONES"]?>" />
				</div>
			</div>
		</div>
<?
$params = array(":idavisoobra" => $ids[0], ":idavisoobraweb" => $ids[1]);
$sql =
	"SELECT aw_fechainicio fechainicio, aw_fechafindeobra fechafindeobra, aw_fechasuspension fechasuspension, aw_fechareinicio fechareinicio, aw_fechaextension fechaextension,
					aw_ingenieriacivil ingenieriacivil, aw_arquitectura arquitectura, aw_montajeindustrial montajeindustrial, aw_ductos ductos, aw_redes redes, aw_otras otras, aw_comitente comitente,
					aw_cuitcomitente cuitcomitente, aw_razonsocialcomitente razonsocialcomitente, aw_contratista contratista, aw_cuitcontratista cuitcontratista,
					aw_razonsocialcontratista razonsocialcontratista, aw_subcontratista subcontratista, aw_cuitsubcontratista cuitsubcontratista,
					aw_razonsocialsubcontratista razonsocialsubcontratista, aw_actividad actividad, aw_submuraciones submuraciones, aw_subsuelos subsuelos, aw_total total, aw_parcial parcial,
					aw_fechaexcavacion fechaexcavacion, aw_fechaexcavacionhasta fechaexcavacionhasta, aw_fechademolicion fechademolicion, aw_fechademolicionhasta fechademolicionhasta,
					aw_superficie superficie, aw_plantas plantas, aw_nombreresp nombreresp, aw_apellidoresp apellidoresp, aw_codarearesp codarearesp, aw_telefonoresp telefonoresp,
					aw_tipotelefonoresp tipotelefonoresp, aw_emailresp emailresp, aw_tipodocumentoresp tipodocumentoresp, aw_numerodocumentoresp numerodocumentoresp, aw_sexoresp sexoresp,
					'N' avisoobra, aw_excavacion503 excavacion503, aw_fechadesdeexcavacion503 fechadesdeexcavacion503, aw_fechahastaexcavacion503 fechahastaexcavacion503,
					aw_detalleexcavacion503 detalleexcavacion503, aw_estado estado
		 FROM hys.haw_avisoobraweb a
		WHERE aw_id = :idavisoobraweb
			AND aw_fechabaja IS NULL
			AND aw_idavisoobra IS NULL
UNION ALL
	 SELECT ao_fechainicio, ao_fechafindeobra, ao_fechasuspension, ao_fechareinicio, ao_fechaextension,
					ao_ingenieriacivil, ao_arquitectura, ao_montajeindustial, ao_ductos, ao_redes, ao_otras, ao_comitenteweb,
					ao_cuitcomitente, ao_razonsocialcomitente, ao_contratista, ao_cuitcontratista,
					ao_razonsocialcontratista, ao_subcontratista, ao_cuitsubcontratista,
					ao_razonsocialsubcontratista, ao_actividad, ao_submuraciones, ao_subsuelos, ao_total, ao_parcial,
					ao_fechaexcavacion, ao_fechaexcavacionhasta, ao_fechademolicion, ao_fechademolicionhasta,
					ao_superficie, ao_plantas, ao_nombreresp, ao_apellidoresp, ao_codarearesp, ao_telefonoresp,
					ao_tipotelefonoresp, ao_emailresp, ao_tipodocumentoresp, ao_numerodocumentoresp, ao_sexoresp,
					'S' avisoobra, ao_excavacion503, ao_fechadesdeexcavacion503, ao_fechahastaexcavacion503,
					ao_detalleexcavacion503, null aw_estado
		 FROM art.pao_avisoobra a, afi.aem_empresa, afi.aco_contrato, afi.aes_establecimiento
		WHERE es_contrato = co_contrato
			AND em_id = co_idempresa
			AND em_cuit = ao_cuit
			AND es_nroestableci = ao_estableci
			AND co_contrato = art.get_vultcontrato(em_cuit)
			AND es_eventual = 'S'
			AND es_idestabeventual = 1
			AND es_fechabaja IS NULL
			AND ao_fechabaja IS NULL
			AND ao_id = :idavisoobra";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

$fechaExtensionVisible = (($fechaExtensionVisible) or ($row["FECHAEXTENSION"] != ""));
$fechaReinicioVisible = (($fechaReinicioVisible) or ($row["FECHAREINICIO"] != ""));
$fechaSuspensionVisible = (($fechaSuspensionVisible) or ($row["FECHASUSPENSION"] != ""));
$estadoAvisoObraWeb = $row["ESTADO"];
?>
		<input id="fechaExtensionEnabled" name="fechaExtensionEnabled" type="hidden" value="<?= (($fechaExtensionEnabled) and ($fechaExtensionVisible))?"t":"f"?>" />
		<input id="fechaFinalizacionEnabled" name="fechaFinalizacionEnabled" type="hidden" value="<?= ($fechaFinalizacionEnabled)?"t":"f"?>" />
		<input id="fechaInicioEnabled" name="fechaInicioEnabled" type="hidden" value="<?= ($fechaInicioEnabled)?"t":"f"?>" />
		<input id="fechaReinicioEnabled" name="fechaReinicioEnabled" type="hidden" value="<?= (($fechaReinicioEnabled) and ($fechaReinicioVisible))?"t":"f"?>" />
		<input id="fechaSuspensionEnabled" name="fechaSuspensionEnabled" type="hidden" value="<?= (($fechaSuspensionEnabled) and ($fechaSuspensionVisible))?"t":"f"?>" />

		<div class="bloque">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>DATOS GENERALES</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divDatosGenerales" style="display:none;">
				<div class="fila">
					<label style="margin-left:13px;">Fecha de Inicio</label>
					<input id="fechaInicio" maxlength="10" name="fechaInicio" <?= ($fechaInicioEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHAINICIO"]?>" />
					<input class="<?= ($fechaInicioEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($fechaInicioEnabled)?"":"disabled"?> id="btnFechaInicio" name="btnFechaInicio" style="margin-right:4px; vertical-align:-5px;" type="button" value="" />
					<label>Fecha de Finalización</label>
					<input id="fechaFinalizacion" maxlength="10" name="fechaFinalizacion" <?= ($fechaFinalizacionEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHAFINDEOBRA"]?>" />
					<input class="<?= ($fechaFinalizacionEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($fechaFinalizacionEnabled)?"":"disabled"?> id="btnFechaFinalizacion" name="btnFechaFinalizacion" style="margin-right:4px; vertical-align:-5px;" type="button" value="" />
<?
if ($fechaSuspensionVisible) {
?>
					<label>Fecha de Suspensión</label>
					<input id="fechaSuspension" maxlength="10" name="fechaSuspension" <?= ($fechaSuspensionEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHASUSPENSION"]?>" />
					<input class="<?= ($fechaSuspensionEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($fechaSuspensionEnabled)?"":"disabled"?> id="btnFechaSuspension" name="btnFechaSuspension" style="vertical-align:-5px;" type="button" value="" />
<?
}
else {
?>
					<input id="fechaSuspension" name="fechaSuspension" type="hidden" value="<?= $row["FECHASUSPENSION"]?>" />
<?
}
?>
				</div>
				<div class="fila">
<?
if ($fechaReinicioVisible) {
?>
					<label>Fecha de Reinicio</label>
					<input id="fechaReinicio" maxlength="10" name="fechaReinicio" <?= ($fechaReinicioEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHAREINICIO"]?>" />
					<input class="<?= ($fechaReinicioEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($fechaReinicioEnabled)?"":"disabled"?> id="btnFechaReinicio" name="btnFechaReinicio" style="margin-right:14px; vertical-align:-5px;" type="button" value="" />
<?
}
else {
?>
					<input id="fechaReinicio" name="fechaReinicio" type="hidden" value="<?= $row["FECHAREINICIO"]?>" />
<?
}

if ($fechaExtensionVisible) {
?>
					<label>Fecha de Extensión</label>
					<input id="fechaExtension" maxlength="10" name="fechaExtension" <?= ($fechaExtensionEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHAEXTENSION"]?>" />
					<input class="<?= ($fechaExtensionEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($fechaExtensionEnabled)?"":"disabled"?> id="btnFechaExtension" name="btnFechaExtension" style="vertical-align:-5px;" type="button" value="" />
<?
}
else {
?>
					<input id="fechaExtension" name="fechaExtension" type="hidden" value="<?= $row["FECHAEXTENSION"]?>" />
<?
}
?>
				</div>
				<hr style="border:0; border-top:1px solid #00539b; height:0; width:96%;" />
				<div class="fila" id="divDatosGeneralesSuperficie">
					<label>Superficie a Construir</label>
					<input id="superficieConstruir" maxlength="7" name="superficieConstruir" <?= ($camposEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["SUPERFICIE"]?>" />
					<label style="margin-left:16px;">Número de Plantas</label>
					<input id="numeroPlantas" maxlength="4" name="numeroPlantas" <?= ($camposEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["PLANTAS"]?>" />
				</div>
			</div>
		</div>

		<div class="bloque" id="divIngenieriaCivilMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>INGENIERÍA CIVIL</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divIngenieriaCivil" style="display:none;">
				<div class="fila">
					<input <?= (substr($row["INGENIERIACIVIL"], 0, 1) == "S")?"checked":""?> class="check" id="caminos" name="caminos" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Caminos</label>
					<input <?= (substr($row["INGENIERIACIVIL"], 4, 1) == "S")?"checked":""?> class="check" id="tuneles" name="tuneles" style="margin-left:169px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Túneles</label>
					<input <?= (substr($row["INGENIERIACIVIL"], 8, 1) == "S")?"checked":""?> class="check" id="puertos" name="puertos" style="margin-left:242px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Puertos</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["INGENIERIACIVIL"], 1, 1) == "S")?"checked":""?> class="check" id="calles" name="calles" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Calles</label>
					<input <?= (substr($row["INGENIERIACIVIL"], 5, 1) == "S")?"checked":""?> class="check" id="obrasFerroviarias" name="obrasFerroviarias" style="margin-left:184px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Obras Ferroviarias</label>
					<input <?= (substr($row["INGENIERIACIVIL"], 9, 1) == "S")?"checked":""?> class="check" id="aeropuertos" name="aeropuertos" style="margin-left:180px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Aeropuertos</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["INGENIERIACIVIL"], 2, 1) == "S")?"checked":""?> class="check" id="autopistas" name="autopistas" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Autopistas</label>
					<input <?= (substr($row["INGENIERIACIVIL"], 6, 1) == "S")?"checked":""?> class="check" id="obrasHidraulicas" name="obrasHidraulicas" style="margin-left:160px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Obras Hidraúlicas</label>
					<input <?= (substr($row["INGENIERIACIVIL"], 10, 1) == "S")?"checked":""?> class="check" id="otras" name="otras" style="margin-left:186px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Otras</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["INGENIERIACIVIL"], 3, 1) == "S")?"checked":""?> class="check" id="puentes" name="puentes" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Puentes</label>
					<input <?= (substr($row["INGENIERIACIVIL"], 7, 1) == "S")?"checked":""?> class="check" id="tratamientoAgua" name="tratamientoAgua" style="margin-left:174px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Alcantarillas/Tratamiento de Agua y Afluentes</label>
				</div>
			</div>
		</div>

		<div class="bloque" id="divArquitecturaMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>ARQUITECTURA</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divArquitectura" style="display:none;">
				<div class="fila">
					<input <?= (substr($row["ARQUITECTURA"], 0, 1) == "S")?"checked":""?> class="check" id="viviendasUnifamiliares" name="viviendasUnifamiliares" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Viviendas Unifamiliares</label>
					<input <?= (substr($row["ARQUITECTURA"], 4, 1) == "S")?"checked":""?> class="check" id="edificiosOficina" name="edificiosOficina" style="margin-left:24px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Edificios de Oficina</label>
					<input <?= (substr($row["ARQUITECTURA"], 1, 1) == "S")?"checked":""?> class="check" id="edificiosPisosMultiples" name="edificiosPisosMultiples" style="margin-left:24px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Edificios de Pisos Múltiples</label>
					<input <?= (substr($row["ARQUITECTURA"], 5, 1) == "S")?"checked":""?> class="check" id="escuelas" name="escuelas" style="margin-left:19px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Escuelas</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["ARQUITECTURA"], 2, 1) == "S")?"checked":""?> class="check" id="obrasUrbanizacion" name="obrasUrbanizacion" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Obras Urbanización</label>
					<input <?= (substr($row["ARQUITECTURA"], 6, 1) == "S")?"checked":""?> class="check" id="hospitales" name="hospitales" style="margin-left:46px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Hospitales</label>
					<input <?= (substr($row["ARQUITECTURA"], 3, 1) == "S")?"checked":""?> class="check" id="edificiosComerciales" name="edificiosComerciales" style="margin-left:73px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Edificios Comerciales</label>
					<input <?= (substr($row["ARQUITECTURA"], 7, 1) == "S")?"checked":""?> class="check" id="otrasEdific" name="otrasEdific" style="margin-left:48px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Otras Edific. Urbanas Def.</label>
				</div>
			</div>
		</div>

		<div class="bloque" id="divMontajeIndustrialMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>MONTAJE INDUSTRIAL</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divMontajeIndustrial" style="display:none;">
				<div class="fila">
					<input <?= (substr($row["MONTAJEINDUSTRIAL"], 0, 1) == "S")?"checked":""?> class="check" id="destileria" name="destileria" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Destilería, Refinerías, Petroquímicas</label>
					<input <?= (substr($row["MONTAJEINDUSTRIAL"], 1, 1) == "S")?"checked":""?> class="check" id="generacionElectrica" name="generacionElectrica" style="margin-left:64px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Generación Eléctrica</label>
					<input <?= (substr($row["MONTAJEINDUSTRIAL"], 2, 1) == "S")?"checked":""?> class="check" id="obrasMineria" name="obrasMineria" style="margin-left:64px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Obras para la Minería</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["MONTAJEINDUSTRIAL"], 3, 1) == "S")?"checked":""?> class="check" id="industriaManufactureraUrbana" name="industriaManufactureraUrbana" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Industria Manufacturera Urbana</label>
					<input <?= (substr($row["MONTAJEINDUSTRIAL"], 4, 1) == "S")?"checked":""?> class="check" id="demasMontajesIndustriales" name="demasMontajesIndustriales" style="margin-left:89px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Demás Montajes Industriales</label>
				</div>
			</div>
		</div>

		<div class="bloque" id="divDuctosMain" style="display:<?= (($_REQUEST["r"] == 1) or ($ids[2] == 1))?"none":"block"?>;">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>DUCTOS</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divDuctos" style="display:none;">
				<div class="fila">
					<input <?= (substr($row["DUCTOS"], 0, 1) == "S")?"checked":""?> class="check" id="tuberias" name="tuberias" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Tuberías</label>
					<input <?= (substr($row["DUCTOS"], 1, 1) == "S")?"checked":""?> class="check" id="estaciones" name="estaciones" style="margin-left:64px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Estaciones</label>
					<input <?= (substr($row["DUCTOS"], 2, 1) == "S")?"checked":""?> class="check" id="ductosOtras" name="ductosOtras" style="margin-left:64px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Otras</label>
				</div>
			</div>
		</div>

		<div class="bloque" id="divRedesMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>REDES</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divRedes" style="display:none;">
				<div class="fila">
					<input <?= (substr($row["REDES"], 0, 1) == "S")?"checked":""?> class="check" id="transElectricaAltoVoltaje" name="transElectricaAltoVoltaje" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Trans. Eléctrica en Alto Voltaje</label>
					<input <?= (substr($row["REDES"], 1, 1) == "S")?"checked":""?> class="check" id="transElectricaBajoVoltaje" name="transElectricaBajoVoltaje" style="margin-left:160px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Trans. Eléctrica en Bajo Voltaje/Subestaciones</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["REDES"], 2, 1) == "S")?"checked":""?> class="check" id="comunicaciones" name="comunicaciones" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Comunicaciones</label>
					<input <?= (substr($row["REDES"], 3, 1) == "S")?"checked":""?> class="check" id="otrasObrasRedes" name="otrasObrasRedes" style="margin-left:243px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Otras Obras de Redes</label>
				</div>
			</div>
		</div>

		<div class="bloque" id="divOtrasConstruccionesMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>OTRAS CONSTRUCCIONES</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divOtrasConstrucciones" style="display:none;">
				<div class="fila">
					<input <?= (substr($row["OTRAS"], 0, 1) == "S")?"checked":""?> class="check" id="excavacionesSubterraneas" name="excavacionesSubterraneas" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Excavaciones Subterráneas</label>
					<input <?= (substr($row["OTRAS"], 1, 1) == "S")?"checked":""?> class="check" id="instalacionesHidraulicas" name="instalacionesHidraulicas" style="margin-left:200px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Instalaciones Hidraúlicas, Sanitarias y de Gas</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["OTRAS"], 2, 1) == "S")?"checked":""?> class="check" id="instalacionesElectromecanicas" name="instalacionesElectromecanicas" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Instalaciones Electromecánicas</label>
					<input <?= (substr($row["OTRAS"], 3, 1) == "S")?"checked":""?> class="check"  id="instalacionesAireAcondicionado" name="instalacionesAireAcondicionado" style="margin-left:180px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Instalaciones de Aire Acondicionado</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["OTRAS"], 4, 1) == "S")?"checked":""?> class="check" id="reparaciones" name="reparaciones" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Reparaciones/Refacciones</label>
					<input <?= (substr($row["OTRAS"], 5, 1) == "S")?"checked":""?> class="check" id="otrasObras" name="otrasObras" style="margin-left:208px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Otras Obras no Especificadas</label>
				</div>
			</div>
		</div>

		<div class="bloque" id="divActividadMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>ACTIVIDAD</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divActividad" style="display:none;">
				<div class="fila">
					<input <?= (substr($row["ACTIVIDAD"], 0, 1) == "S")?"checked":""?> class="check" id="excavacion" name="excavacion" type="checkbox" value="si" onClick="<?= ($camposEnabled)?"":"return false;"?> checkExcavacion(this.checked);" />
					<label>Excavación Res. 550</label>
					<label style="margin-left:8px;">Fecha Desde</label>
					<input id="fechaDesdeExcavacion" maxlength="10" name="fechaDesdeExcavacion" <?= ($camposEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHAEXCAVACION"]?>" />
					<input class="<?= ($camposEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($camposEnabled)?"":"disabled"?> id="btnFechaDesdeExcavacion" name="btnFechaDesdeExcavacion" style="vertical-align:-5px;" type="button" value="" />
					<label style="margin-left:8px;">Hasta</label>
					<input id="fechaHastaExcavacion" maxlength="10" name="fechaHastaExcavacion" <?= ($camposEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHAEXCAVACIONHASTA"]?>" />
					<input class="<?= ($camposEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($camposEnabled)?"":"disabled"?> id="btnFechaHastaExcavacion" name="btnFechaHastaExcavacion" style="vertical-align:-5px;" type="button" value="" />
					<input <?= ($row["SUBMURACIONES"] == "S")?"checked":""?> class="check" id="submuraciones" name="submuraciones" style="margin-left:4px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Submuraciones</label>
					<input <?= ($row["SUBSUELOS"] == "S")?"checked":""?> class="check" id="subsuelos" name="subsuelos" style="margin-left:4px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Subsuelos</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["ACTIVIDAD"], 1, 1) == "S")?"checked":""?> class="check" id="demolicion" name="demolicion" type="checkbox" value="si" onClick="<?= ($camposEnabled)?"":"return false;"?> checkDemolicion(this.checked);" />
					<label>Demolición Res. 550</label>
					<label style="margin-left:8px;">Fecha Desde</label>
					<input id="fechaDesdeDemolicion" maxlength="10" name="fechaDesdeDemolicion" <?= ($camposEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHADEMOLICION"]?>" />
					<input class="<?= ($camposEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($camposEnabled)?"":"disabled"?> id="btnFechaDesdeDemolicion" name="btnFechaDesdeDemolicion" style="vertical-align:-5px;" type="button" value="" />
					<label style="margin-left:8px;">Hasta</label>
					<input id="fechaHastaDemolicion" maxlength="10" name="fechaHastaDemolicion" <?= ($camposEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHADEMOLICIONHASTA"]?>" />
					<input class="<?= ($camposEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($camposEnabled)?"":"disabled"?> id="btnFechaHastaDemolicion" name="btnFechaHastaDemolicion" style="vertical-align:-5px;" type="button" value="" />
					<input <?= ($row["TOTAL"] == "S")?"checked":""?> class="check" id="total" name="total" style="margin-left:4px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Total</label>
					<input <?= ($row["PARCIAL"] == "S")?"checked":""?> class="check" id="parcial" name="parcial" style="margin-left:65px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Parcial</label>
				</div>
				<div class="fila">
					<input <?= ($row["EXCAVACION503"] == "S")?"checked":""?> class="check" id="excavacion503" name="excavacion503" type="checkbox" value="si" onClick="<?= ($camposEnabled)?"":"return false;"?> checkExcavacion503(this.checked);" />
					<label>Otras excavaciones con más de 1,2 mts. de profundidad, no incluidas en la Res. 550/11, ni túneles, galerías o minería.</label>
				</div>
				<div class="fila">
					<label style="margin-left:24px;">Fecha Desde</label>
					<input id="fechaDesdeExcavacion503" maxlength="10" name="fechaDesdeExcavacion503" <?= ($camposEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHADESDEEXCAVACION503"]?>" />
					<input class="<?= ($camposEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($camposEnabled)?"":"disabled"?> id="btnFechaDesdeExcavacion503" name="btnFechaDesdeExcavacion503" style="vertical-align:-5px;" type="button" value="" />
					<label style="margin-left:16px;">Hasta</label>
					<input id="fechaHastaExcavacion503" maxlength="10" name="fechaHastaExcavacion503" <?= ($camposEnabled)?"":"readonly"?> style="width:64px;" type="text" value="<?= $row["FECHAHASTAEXCAVACION503"]?>" />
					<input class="<?= ($camposEnabled)?"botonFecha":"botonFechaDeshabilitado"?>" <?= ($camposEnabled)?"":"disabled"?> id="btnFechaHastaExcavacion503" name="btnFechaHastaExcavacion503" style="vertical-align:-5px;" type="button" value="" />
					<label style="margin-left:16px;">Detallar</label>
					<input id="detallarExcavacion503" maxlength="2000" name="detallarExcavacion503" <?= ($isAlta)?"":"readonly"?> style="width:248px;" type="text" value="<?= $row["DETALLEEXCAVACION503"]?>" />
				</div>
				<div class="fila">
					<input <?= (substr($row["ACTIVIDAD"], 2, 1) == "S")?"checked":""?> class="check" id="albanileria" name="albanileria" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Albañilería</label>
					<input <?= (substr($row["ACTIVIDAD"], 8, 1) == "S")?"checked":""?> class="check" id="ascensores" name="ascensores" style="margin-left:98px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Ascensores, montacargas o montapersonas</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["ACTIVIDAD"], 3, 1) == "S")?"checked":""?> class="check" id="ha" name="ha" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>HºAº</label>
					<input <?= (substr($row["ACTIVIDAD"], 9, 1) == "S")?"checked":""?> class="check" id="pintura" name="pintura" style="margin-left:130px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Pintura</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["ACTIVIDAD"], 4, 1) == "S")?"checked":""?> class="check" id="montajesElectromecanicos" name="montajesElectromecanicos" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Montajes Electromecánicos</label>
					<input <?= (substr($row["ACTIVIDAD"], 10, 1) == "S")?"checked":""?> class="check" id="obraMas1000" name="obraMas1000" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>¿ Tiene la obra mas de 1000m² de sup. cubierta o se trabaja a mas de 4m. de altura ?</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["ACTIVIDAD"], 5, 1) == "S")?"checked":""?> class="check" id="instalacionesVarias" name="instalacionesVarias" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Instalaciones Varias</label>
					<input <?= (substr($row["ACTIVIDAD"], 11, 1) == "S")?"checked":""?> class="check" id="silletas" name="silletas" style="margin-left:43px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Silletas o Andamios Colgantes</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["ACTIVIDAD"], 6, 1) == "S")?"checked":""?> class="check" id="estructurasMetalicas" name="estructurasMetalicas" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Estructuras Metálicas</label>
					<input <?= (substr($row["ACTIVIDAD"], 12, 1) == "S")?"checked":""?> class="check" id="mediosIzaje" name="mediosIzaje" style="margin-left:38px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Medios de Izaje</label>
				</div>
				<div class="fila">
					<input <?= (substr($row["ACTIVIDAD"], 7, 1) == "S")?"checked":""?> class="check" id="electricidad" name="electricidad" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Electricidad</label>
					<input <?= (substr($row["ACTIVIDAD"], 13, 1) == "S")?"checked":""?> class="check" id="altaMediaTension" name="altaMediaTension" style="margin-left:93px;" type="checkbox" value="si" <?= ($camposEnabled)?"":"onClick='return false;'"?> />
					<label>Alta y Media Tensión</label>
				</div>
			</div>
		</div>

		<div class="bloque" id="divComitenteMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>COMITENTE - CONTRATISTA</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divComitente" style="display:none;">
				<div class="fila">
					<input <?= ($row["COMITENTE"] == "S")?"checked":""?> class="check" id="comitente" name="comitente" type="checkbox" value="si" onClick="<?= ($camposEnabled)?"":"return false;"?> checkComitente(this.checked);" />
					<label>Comitente</label>
					<label style="margin-left:72px;">C.U.I.T.</label>
					<input id="cuitComitente" maxlength="13" name="cuitComitente" <?= ($camposEnabled)?"":"readonly"?> style="width:80px;" type="text" value="<?= $row["CUITCOMITENTE"]?>" />
					<label style="margin-left:16px;">Razón Social</label>
					<input id="razonSocialComitente" name="razonSocialComitente" <?= ($camposEnabled)?"":"readonly"?> style="width:296px;" type="text" value="<?= $row["RAZONSOCIALCOMITENTE"]?>" />
				</div>
				<div class="fila">
					<input <?= ($row["CONTRATISTA"] == "S")?"checked":""?> class="check" id="contratistaPrincipal" name="contratistaPrincipal" type="checkbox" value="si" onClick="<?= ($camposEnabled)?"":"return false;"?> checkContratistaPrincipal(this.checked);" />
					<label>Contratista Principal</label>
					<label style="margin-left:16px;">C.U.I.T.</label>
					<input id="cuitContratistaPrincipal" maxlength="13" name="cuitContratistaPrincipal" <?= ($camposEnabled)?"":"readonly"?> style="width:80px;" type="text" value="<?= $row["CUITCONTRATISTA"]?>" />
					<label style="margin-left:16px;">Razón Social</label>
					<input id="razonSocialContratistaPrincipal" name="razonSocialContratistaPrincipal" <?= ($camposEnabled)?"":"readonly"?> style="width:296px;" type="text" value="<?= $row["RAZONSOCIALCONTRATISTA"]?>" />
				</div>
				<div class="fila">
					<input <?= ($row["SUBCONTRATISTA"] == "S")?"checked":""?> class="check" id="subcontratista" name="subcontratista" type="checkbox" value="si" onClick="<?= ($camposEnabled)?"":"return false;"?> checkSubcontratista(this.checked);" />
					<label>Subcontratista</label>
					<label style="margin-left:49px;">C.U.I.T.</label>
					<input id="cuitSubcontratista" maxlength="13" name="cuitSubcontratista" <?= ($camposEnabled)?"":"readonly"?> style="width:80px;" type="text" value="<?= $row["CUITSUBCONTRATISTA"]?>" />
					<label style="margin-left:16px;">Razón Social</label>
					<input id="razonSocialSubcontratista" name="razonSocialSubcontratista" <?= ($camposEnabled)?"":"readonly"?> style="width:296px;" type="text" value="<?= $row["RAZONSOCIALSUBCONTRATISTA"]?>" />
				</div>
			</div>
		</div>
<?
$params = array(":idavisoobra" => $ids[0], ":idavisoobraweb" => $ids[1]);
$sql =
	"SELECT ct_contacto contacto, ct_cargo cargo, ct_codareatelefonos codareatelefonos, ct_telefonos telefonos, ct_codareafax codareafax, ct_fax fax,
					ct_direlectronica direlectronica, ct_fechaalta fechaalta, ct_usualta usualta, ct_fechamodif fechamodif, ct_usumodif usumodif, ct_fechabaja fechabaja, ct_usubaja usubaja,
					ct_tipodocumento tipodocumento, ct_numerodocumento numerodocumento, ct_firmante firmante, ct_sexo sexo, ct_id idcontacto, TO_NUMBER(NULL) idcontactoweb
		 FROM afi.act_contacto, art.pao_avisoobra
		WHERE ao_fechabaja IS NULL
			AND ao_contacto = ct_id
			AND ao_id = :idavisoobra
UNION ALL
	 SELECT cw_contacto, cw_cargo, cw_codareatelefonos, cw_telefonos, cw_codareafax, cw_fax, cw_direlectronica, cw_fechaalta, cw_usualta, cw_fechamodif, cw_usumodif, cw_fechabaja,
					cw_usubaja, cw_tipodocumento, cw_numerodocumento, cw_firmante, cw_sexo, TO_NUMBER(NULL) idcontacto, cw_id idcontactoweb
		 FROM hys.hcw_contactoobraweb, hys.haw_avisoobraweb
		WHERE aw_fechabaja IS NULL
			AND aw_idavisoobra IS NULL
			AND aw_contactoaoweb = cw_id
			AND aw_id = :idavisoobraweb";
$stmt2 = DBExecSql($conn, $sql, $params);
$rowHYS = DBGetQuery($stmt2);


if ($rowHYS["IDCONTACTO"] != "") {
	$campoClave = "tn_idcontacto";
	$idTablaPadre = $rowHYS["IDCONTACTO"];
	$prefijo = "tn";
	$tablaTel = "atn_telefonocontacto";
}
else {
	$campoClave = "ta_idcontactoavisoobraweb";
	$idTablaPadre = $rowHYS["IDCONTACTOWEB"];
	$prefijo = "ta";
	$tablaTel = "hys.hta_telefonoavisoobraweb";
}

$dataTel = inicializarTelefonos(OCI_COMMIT_ON_SUCCESS, $campoClave, $idTablaPadre, $prefijo, $tablaTel, $_SESSION["usuario"]);
quitarTelefonosTemporales($dataTel);
copiarTelefonosATemp($dataTel, $_SESSION["usuario"]);

require_once("aviso_obra_combos.php");
?>
		<input id="idContacto" name="idContacto" type="hidden" value="<?= $rowHYS["IDCONTACTO"]?>" />
		<input id="idContactoWeb" name="idContactoWeb" type="hidden" value="<?= $rowHYS["IDCONTACTOWEB"]?>" />
		<input id="cargoHYSTmp" name="cargoHYSTmp" type="hidden" value="<?= $rowHYS["CARGO"]?>" />
		<input id="sexoHYSTmp" name="sexoHYSTmp" type="hidden" value="<?= $rowHYS["SEXO"]?>" />
		<input id="tipoDocumentoHYSTmp" name="tipoDocumentoHYSTmp" type="hidden" value="<?= $rowHYS["TIPODOCUMENTO"]?>" />
		<div class="bloque" id="divResponsableHysMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>RESPONSABLE HYS</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divResponsableHys" style="display:none;">
				<div class="fila">
					<label>Tipo Documento</label>
					<?= $comboTipoDocumentoHYS->draw();?>
					<label style="margin-left:15px;">Nº Documento</label>
					<input id="numeroDocumentoHYS" maxlength="11" name="numeroDocumentoHYS" <?= ($camposEnabled)?"":"readonly"?> style="width:72px;" type="text" value="<?= $rowHYS["NUMERODOCUMENTO"]?>" onBlur="if (!this.readOnly) cargarResponsableHYS(this.value);" />
					<label style="margin-left:12px;">Sexo</label>
					<?= $comboSexoHYS->draw();?>
				</div>
				<div class="fila">
					<label>Nombre y Apellido</label>
					<input id="nombreApellidoHYS" maxlength="100" name="nombreApellidoHYS" <?= ($camposEnabled)?"":"readonly"?> style="width:578px;" type="text" value="<?= $rowHYS["CONTACTO"]?>" />
				</div>
				<div class="fila">
					<label>Cargo</label>
					<?= $comboCargoHYS->draw();?>
					<label style="margin-left:8px;">e-Mail</label>
					<input id="emailHYS" maxlength="120" name="emailHYS" <?= ($camposEnabled)?"":"readonly"?> style="width:320px;" type="text" value="<?= $rowHYS["DIRELECTRONICA"]?>" />
				</div>
				<div class="fila">
					<iframe frameborder="no" height="0" id="iframeTelefonos" name="iframeTelefonos" scrolling="no" src="/functions/telefonos/telefonos.php?s=isCliente&idModulo=-1&idTablaPadre=<?= $idTablaPadre?>&tablaTel=<?= $tablaTel?>&campoClave=<?= $campoClave?>&prefijo=<?= $prefijo?>&r=<?= ($camposEnabled)?"f":"t"?>" width="704" onLoad="ajustarTamanoIframe(this, 192)"></iframe>
				</div>
			</div>
		</div>

		<div class="bloque" id="divResponsableDatosMain">
			<div class="cabecera" onClick="showHideDiv(this)">
				<span>RESPONSABLE DE LOS DATOS DECLARADOS EN EL FORMULARIO</span>
				<img align="right" border="0" src="/images/add16.png" style="position:relative; top:-2px;" title="Desplegar" />
			</div>
			<div id="divResponsableDatos" style="display:none;">
				<div class="fila">
					<label>Tipo Documento</label>
					<?= $comboTipoDocumento->draw();?>
					<label style="margin-left:8px;">Nº Documento</label>
					<input id="numeroDocumento" maxlength="11" name="numeroDocumento" <?= ($camposDatosResponsableEnabled)?"":"readonly"?> style="width:80px;" type="text" value="<?= $row["NUMERODOCUMENTORESP"]?>" />
					<label style="margin-left:8px;">Sexo</label>
					<?= $comboSexo->draw();?>
				</div>
				<div class="fila">
					<label>Nombre</label>
					<input id="nombre" maxlength="60" name="nombre" <?= ($camposDatosResponsableEnabled)?"":"readonly"?> style="width:281px;" type="text" value="<?= $row["NOMBRERESP"]?>" />
					<label style="margin-left:16px;">Apellido</label>
					<input id="apellido" maxlength="60" name="apellido" <?= ($camposDatosResponsableEnabled)?"":"readonly"?> style="width:276px;" type="text" value="<?= $row["APELLIDORESP"]?>" />
				</div>
				<div class="fila">
					<label>Código Área</label>
					<input id="codigoArea" maxlength="10" name="codigoArea" <?= ($camposDatosResponsableEnabled)?"":"readonly"?> style="width:80px;" type="text" value="<?= $row["CODAREARESP"]?>" />
					<label style="margin-left:16px;">Teléfono</label>
					<input id="telefono" maxlength="20" name="telefono" <?= ($camposDatosResponsableEnabled)?"":"readonly"?> style="width:160px;" type="text" value="<?= $row["TELEFONORESP"]?>" />
					<label style="margin-left:16px;">Tipo Teléfono</label>
					<?= $comboTipoTelefono->draw();?>
				</div>
				<div class="fila">
					<label>e-Mail</label>
					<input id="email" maxlength="100" name="email" <?= ($camposDatosResponsableEnabled)?"":"readonly"?> style="width:579px;" type="text" value="<?= $row["EMAILRESP"]?>" />
				</div>
			</div>
		</div>
		<div id="divBotones">
<?
if (($_REQUEST["t"] == "p") and ($isAlta)) {
?>
			<input class="btnDarDeBaja" id="btnDarDeBaja" name="btnDarDeBaja" type="button" value="" onClick="darBaja(<?= $ids[1]?>)" />
<?
}

if (((($_REQUEST["t"] == "p") and ($isAlta)) or ($_REQUEST["t"] != "p")) and ($estadoAvisoObraWeb != "R") ) {
?>
			<input class="btnGuardar" id="btnGuardar" name="btnGuardar" style="margin-left:16px;" type="submit" value="" />
			<img border="0" id="imgProcesando" src="/modules/usuarios_registrados/images/procesando.gif" style="display:none; vertical-align:-1px;" title="Procesando.&#13;Aguarde un instante, por favor..." />
<?
}
?>
		</div>
		<input class="btnVolver" type="button" value="" onClick="history.go(-2);" />
	</div>
	<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:680px;"></p>
	<div class="ContenidoSeccion" id="divErrores" style="display:none; margin-top:8px;">
		<table border="1" bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
			<tr>
				<td>
					<table cellpadding="4" cellspacing="0">
						<tr>
							<td><img border="0" src="/modules/usuarios_registrados/images/atencion.jpg"></td>
							<td>
								<font color="#000000">
									No es posible continuar mientras no se corrijan los siguientes errores:<br /><br />
									<span id="errores"></span>
								 </font>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
			<input id="foco" name="foco" readonly style="height:1px; width:1px;" type="checkbox" />
	</div>
</form>
<script type="text/javascript">
	Calendar.setup ({
		inputField: "fechaDesdeDemolicion",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesdeDemolicion"
	});
	Calendar.setup ({
		inputField: "fechaDesdeExcavacion",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesdeExcavacion"
	});
<?
if ($fechaExtensionVisible) {
?>
	Calendar.setup ({
		inputField: "fechaExtension",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaExtension"
	});
<?
}
?>
	Calendar.setup ({
		inputField: "fechaFinalizacion",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaFinalizacion"
	});
	Calendar.setup ({
		inputField: "fechaHastaDemolicion",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHastaDemolicion"
	});
	Calendar.setup ({
		inputField: "fechaDesdeExcavacion503",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaDesdeExcavacion503"
	});
	Calendar.setup ({
		inputField: "fechaHastaExcavacion503",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHastaExcavacion503"
	});
	Calendar.setup ({
		inputField: "fechaHastaExcavacion",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaHastaExcavacion"
	});
	Calendar.setup ({
		inputField: "fechaInicio",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaInicio"
	});
<?
if ($fechaReinicioVisible) {
?>
	Calendar.setup ({
		inputField: "fechaReinicio",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaReinicio"
	});
<?
}

if ($fechaSuspensionVisible) {
?>
	Calendar.setup ({
		inputField: "fechaSuspension",
		ifFormat  : "%d/%m/%Y",
		button    : "btnFechaSuspension"
	});
<?
}

if ($row["COMITENTE"] != "S") {
?>
	checkComitente(false);
<?
}

if ($row["CONTRATISTA"] != "S") {
?>
	checkContratistaPrincipal(false);
<?
}

if (substr($row["ACTIVIDAD"], 1, 1) != "S") {
?>
	checkDemolicion(false);
<?
}

if (substr($row["ACTIVIDAD"], 0, 1) != "S") {
?>
	checkExcavacion(false);
<?
}

if ($row["EXCAVACION503"] != "S") {
?>
	checkExcavacion503(false);
<?
}

if ($row["SUBCONTRATISTA"] != "S") {
?>
	checkSubcontratista(false);
<?
}
?>
	document.getElementById('spanTipoResolucion').innerHTML = '<?= $tipoResolucion?>';
<?
if (($_REQUEST["r"] == 1) or ($ids[2] == 1)) {		// Si es la res 319..
?>
	seleccionarCaracteristicasObrador('<?= $formChico?>');
<?
}
?>
	setTimeout("ajustarTamanoIframe(document.getElementById('iframeTelefonos'), 192)", 2000);
</script>