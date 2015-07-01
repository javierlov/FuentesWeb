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
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getNombreOriginal($nombre) {
	if (strpos($nombre, " (LEGAL)"))
		$nombre = str_replace(" (LEGAL)", "", $nombre);

	return $nombre;
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
validarAccesoCotizacion($_REQUEST["idModulo"]);

$isAlta = ((!isset($_REQUEST["id"])) or ($_REQUEST["id"] < 1));
$telefono = array("", "", "");

if (!$isAlta) {
	// Traigo los datos del teléfono..
	$params = array(":idestablecimiento" => $_REQUEST["id"]);
	$sql =
		"SELECT sf_area, sf_numero, sf_interno
			 FROM asf_solicitudtelefonoestableci
			WHERE sf_idtipotelefono = 1
				AND sf_principal = 'S'
				AND sf_fechabaja IS NULL
				AND sf_idestablecimiento = :idestablecimiento";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt, 0);
	$telefono = $row;

	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT ac_codigo, ac_descripcion, pv_descripcion, se_calle, se_codareafax, se_codigosrt, se_cpostal, se_departamento, se_empleados, se_fax, se_fechafinobra, se_fechainicest,
						se_latitud, se_legal, se_localidad, se_longitud, se_nombre, se_masa, se_numero, se_observaciones, se_origendato, se_piso, se_provincia, se_superficie, se_tipoestablecimiento
			 FROM ase_solicitudestablecimiento, cac_actividad, cpv_provincias
			WHERE se_idactividad = ac_id(+)
				AND se_provincia = pv_codigo(+)
				AND se_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}
?>
<html>
	<head>
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<style type="text/css">
			* {margin:0; padding:0;}
			html, body {overflow:hidden; text-align:left;}
			label {display:inline-block; text-align:right; width:160px;}

			.labelDomicilio {width:56px;}

			#sinPersonal {margin-left:16px; vertical-align:-3px;}
			#spanCodigoPostal {margin-left:16px;}
			#spanDepartamento {margin-left:16px;}
			#spanPiso {margin-left:16px;}
			#spanSinPersonal {margin-left:-16px;}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script language="JavaScript" src="/modules/solicitud_afiliacion/js/afiliacion.js"></script>
		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
	</head>
	<body>
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/modules/solicitud_afiliacion/procesar_establecimiento.php" id="formEstablecimiento" method="post" name="formEstablecimiento" target="iframeProcesando">
			<input id="codigoSrt" name="codigoSrt" type="hidden" value="<?= (!$isAlta)?getNombreOriginal($row["SE_CODIGOSRT"]):""?>" />
			<input id="esDomicilioLegal" name="esDomicilioLegal" type="hidden" value="<?= (!$isAlta)?$row["SE_LEGAL"]:"F"?>" />
			<input id="id" name="id" type="hidden" value="<?= (!$isAlta)?$_REQUEST["id"]:""?>" />
			<input id="idModulo" name="idModulo" type="hidden" value="<?= $_REQUEST["idModulo"]?>" />
			<input id="idProvincia" name="idProvincia" type="hidden" value="<?= (!$isAlta)?$row["SE_PROVINCIA"]:""?>" />
			<input id="idSolicitud" name="idSolicitud" type="hidden" value="<?= $_REQUEST["idSolicitud"]?>" />
			<input id="origenDato" name="origenDato" type="hidden" value="<?= (!$isAlta)?$row["SE_ORIGENDATO"]:""?>" />
			<input id="tipoOp" name="tipoOp" type="hidden" value="<?= ($_REQUEST["id"] == -1)?"A":"M"?>" />
			<div class="TituloFndCeleste" style="height:18px; padding-left:8px; padding-top:2px; width:680px;">IDENTIFICACIÓN DEL ESTABLECIMIENTO</div>
			<p class="ContenidoSeccion" style="margin-top:4px;">
				<label>Tipo de Establecimiento (*)</label>
				<input <?= ($isAlta)?"":($row["SE_TIPOESTABLECIMIENTO"] == "O")?"checked":""?> id="tipoEstablecimiento" name="tipoEstablecimiento" style="margin-left:20px; vertical-align:-3px;" type="radio" value="O" onChange="cambiaEstablecimiento(this.value)" />
				<span>Obra en Construcción</span>
				<br />
				<input <?= ($isAlta)?"":($row["SE_TIPOESTABLECIMIENTO"] == "A")?"checked":""?> id="tipoEstablecimiento" name="tipoEstablecimiento" style="margin-left:184px; vertical-align:-3px;" type="radio" value="A" onChange="cambiaEstablecimiento(this.value)" />
				<span>Agro</span>
				<br />
				<input <?= ($isAlta)?"":($row["SE_TIPOESTABLECIMIENTO"] == "T")?"checked":""?> id="tipoEstablecimiento" name="tipoEstablecimiento" style="margin-left:184px; vertical-align:-3px;" type="radio" value="T" onChange="cambiaEstablecimiento(this.value)" />
				<span>Tercero</span>
				<br />
				<input <?= ($isAlta)?"":($row["SE_TIPOESTABLECIMIENTO"] == "P")?"checked":""?> id="tipoEstablecimiento" name="tipoEstablecimiento" style="margin-left:184px; vertical-align:-3px;" type="radio" value="P" onChange="cambiaEstablecimiento(this.value)" />
				<span>Ninguno de los Anteriores</span>
				<br />
			</p>
			<p style="margin-top:4px;">
				<label class="ContenidoSeccion">Nombre (*)</label>
				<input id="nombre" maxlength="100" name="nombre" style="width:432px;" type="text" value="<?= (!$isAlta)?getNombreOriginal($row["SE_NOMBRE"]):""?>" />
			</p>
			<p>
				<label class="ContenidoSeccion" style="vertical-align:7;">Actividad</label>
				<input id="actividad" maxlength="6" name="actividad" style="vertical-align:5; width:64px;" type="text" value="<?= (!$isAlta)?$row["AC_CODIGO"]:""?>" onBlur="getActividad('actividadDescripcion', this.value)" onKeyUp="getActividad('actividadDescripcion', this.value)" />
				<img id="ciiu1Buscar" src="/modules/solicitud_cotizacion/images/lupa.gif" style="cursor:pointer;" onClick="mostrarBuscarCiiuWin('actividad')" />
			</p>
			<p>
				<label class="ContenidoSeccion" style="vertical-align:4;">Descripción Actividad</label>
				<span class="input" id="actividadDescripcion" name="actividadDescripcion" style="background-color:#ccc; width:432px;"><?= (!$isAlta)?$row["AC_DESCRIPCION"]:""?></span>
			</p>
			<p>
				<label class="ContenidoSeccion">F. Inicio Establecimiento</label>
				<input id="fechaInicioEstablecimiento" maxlength="10" name="fechaInicioEstablecimiento" style="width:72px;" type="text" value="<?= (!$isAlta)?$row["SE_FECHAINICEST"]:""?>" />
				<input class="botonFecha" id="btnFechaInicioEstablecimiento" name="btnFechaInicioEstablecimiento" type="button" value="" />
				<i class="ContenidoSeccion">(dd/mm/aaaa)</i>
			</p>
			<p>
				<label class="ContenidoSeccion">Cantidad de Empleados (*)</label>
				<input id="cantidadEmpleados" maxlength="10" name="cantidadEmpleados" style="width:64px;" type="text" value="<?= (!$isAlta)?$row["SE_EMPLEADOS"]:""?>" onKeyUp="escribirEmpleados()" />
				<input <?= ($isAlta)?"":(intval($row["SE_EMPLEADOS"]) == 0)?"checked":""?> id="sinPersonal" name="sinPersonal" type="checkbox" value="ON" onClick="clicSinPersonal()" />
				<span class="ContenidoSeccion" id="spanSinPersonal">SIN PERSONAL</span>
			</p>
			<p>
				<label class="ContenidoSeccion">Masa Salarial</label>
				<input id="masaSalarial" maxlength="14" name="masaSalarial" style="width:108px;" type="text" value="<?= (!$isAlta)?$row["SE_MASA"]:""?>" onBlur="reemplazarPuntoXComa(this)" onKeyUp="reemplazarPuntoXComa(this)" />
			</p>
			<p>
				<label class="ContenidoSeccion">Superficie</label>
				<input id="superficie" maxlength="9" name="superficie" style="width:64px;" type="text" value="<?= (!$isAlta)?$row["SE_SUPERFICIE"]:""?>" />
				<span class="ContenidoSeccion">mt2</span>
			</p>
			<p>
				<label class="ContenidoSeccion" for="fechaFinObra" id="tituloFechaFinObra">F. Finalización de la Obra</label>
				<input id="fechaFinObra" maxlength="10" name="fechaFinObra" style="width:72px;" type="text" value="<?= (!$isAlta)?$row["SE_FECHAFINOBRA"]:""?>" />
				<input class="botonFecha" id="btnFechaFinObra" name="btnFechaFinObra" type="button" value="" />
				<i class="ContenidoSeccion">(dd/mm/aaaa)</i>
			</p>
			<p>
				<label class="ContenidoSeccion" for="telefono">Teléfono Laboral</label>
				<input id="codigoArea" maxlength="5" name="codigoArea" style="width:44px;" type="text" value="<?= $telefono[0]?>" />
				<span class="ContenidoSeccion">-</span>
				<input id="telefono" maxlength="12" name="telefono" style="width:201px;" type="text" value="<?= $telefono[1]?>" />
				<span class="ContenidoSeccion">Interno</span>
				<input id="interno" maxlength="10" name="interno" style="width:72px;" type="text" value="<?= $telefono[2]?>" />
			</p>
			<p>
				<label class="ContenidoSeccion" for="fax">Fax</label>
				<input id="codigoAreaFax" maxlength="5" name="codigoAreaFax" style="width:44px;" type="text" value="<?= (!$isAlta)?$row["SE_CODAREAFAX"]:""?>" />
				<span class="ContenidoSeccion">-</span>
				<input id="fax" maxlength="12" name="fax" style="width:201px;" type="text" value="<?= (!$isAlta)?$row["SE_FAX"]:""?>" />
			</p>
			<p style="margin-top:4px;">
				<label class="ContenidoSeccion" style="vertical-align:top;">Observaciones</label>
				<textarea id="observaciones" maxlength="150" name="observaciones" style="height:48px; width:432px;"><?= (!$isAlta)?$row["SE_OBSERVACIONES"]:""?></textarea>
			</p>
			<div class="TituloFndCeleste" style="height:18px; margin-top:8px; padding-left:8px; padding-top:2px; width:680px;">DOMICILIO (*)</div>
			<div style="margin-top:8px;">
<?
$hayDatos = ((!$isAlta) and ($row["SE_CALLE"] != ""));
if (!$hayDatos) {
?>
				<p class="ContenidoSeccion" id="pSinDatosconocidos">
					<span>- Sin Datos Conocidos -</span>
				</p>
<?
}
?>
				<div class="ContenidoSeccion" id="divDatosDomicilio" style="display:<?= ($hayDatos)?"block":"none"?>">
					<p style="margin-top:4px;">
						<label class="labelDomicilio" for="calle">Calle</label>
						<input id="calle" maxlength="60" name="calle" readonly style="background-color:#ccc; width:552px;" type="text" value="<?= (!$isAlta)?$row["SE_CALLE"]:""?>" />
					</p>
					<p style="margin-top:4px;">
						<label class="labelDomicilio" for="numero">Número</label>
						<input id="numero" maxlength="6" name="numero" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["SE_NUMERO"]:""?>" />
						<span id="spanPiso">Piso</span>
						<input id="piso" maxlength="6" name="piso" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["SE_PISO"]:""?>" />
						<span id="spanDepartamento">Departamento</span>
						<input id="departamento" maxlength="6" name="departamento" style="width:76px;" type="text" value="<?= (!$isAlta)?$row["SE_DEPARTAMENTO"]:""?>" />
					</p>
					<p style="margin-top:4px;">
						<label class="labelDomicilio" for="localidad">Localidad</label>
						<input id="localidad" maxlength="85" name="localidad" readonly style="background-color:#ccc; width:270px;" type="text" value="<?= (!$isAlta)?$row["SE_LOCALIDAD"]:""?>" />
						<span id="spanCodigoPostal">Código Postal</span>
						<input id="codigoPostal" maxlength="5" name="codigoPostal" readonly style="background-color:#ccc; width:67px;" type="text" value="<?= (!$isAlta)?$row["SE_CPOSTAL"]:""?>" />
					</p>
					<p style="margin-top:4px;">
						<label class="labelDomicilio" for="provincia">Provincia</label>
						<input id="provincia" name="provincia" readonly style="background-color:#ccc; width:552px;" type="text" value="<?= (!$isAlta)?$row["PV_DESCRIPCION"]:""?>" />
					</p>
					<p style="margin-left:16px; margin-top:8px;">
						<img src="/modules/usuarios_registrados/images/boton_modificar_domicilio.jpg" style="cursor:pointer;" onClick="buscarDomicilio(true, 'pSinDatosconocidos', 'divDatosDomicilio', 'idProvincia', 'provincia', 'localidad', '', 'codigoPostal', 'calle', 'numero', 'piso', 'departamento', '', 416, 680, 1, 8);" />
						<img src="/modules/usuarios_registrados/images/boton_mapa.jpg" style="cursor:pointer;" onClick="verMapa()" />
						<label class="labelDomicilio" for="latitud">Latitud</label>
						<input id="latitud" name="latitud" readonly style="background-color:#ccc; width:120px;" type="text" value="<?= (!$isAlta)?$row["SE_LATITUD"]:""?>" />
						<label class="labelDomicilio" for="longitud">Longitud</label>
						<input id="longitud" name="longitud" readonly style="background-color:#ccc; width:120px;" type="text" value="<?= (!$isAlta)?$row["SE_LONGITUD"]:""?>" />
					</p>
				</div>
			</div>
			<div style="margin-bottom:8px; margin-top:16px;">
				<input class="btnGrabar" style="margin-left:16px;" type="submit" value="" />
<?
if (!$isAlta) {
?>
				<input class="btnDarDeBaja" style="margin-left:16px;" type="button" value="" onClick="eliminarEstablecimiento(<?= $_REQUEST["id"]?>)" />
<?
}
?>
			</div>
		</form>
		<p id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:280px;">&nbsp;Datos guardados exitosamente.</p>
		<p id="borradoOk" style="background:#0f539c; color:#fff; display:none; margin-left:16px; margin-top:8px; padding:2px; width:440px;">&nbsp;El establecimiento fue dado de baja exitosamente.</p>
		<div id="divErrores" style="display:none;">
			<table border="1" bordercolor="#ff0000" align="center" cellpadding="6" cellspacing="0">
				<tr>
					<td>
						<table cellpadding="4" cellspacing="0">
							<tr>
								<td><img border="0" src="/images/atencion.jpg" /></td>
								<td class="ContenidoSeccion">
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
		<script type="text/javascript">
			Calendar.setup ({
				inputField: "fechaInicioEstablecimiento",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaInicioEstablecimiento"
			});
			Calendar.setup ({
				inputField: "fechaFinObra",
				ifFormat  : "%d/%m/%Y",
				button    : "btnFechaFinObra"
			});

			cambiaEstablecimiento('<?= (!$isAlta)?$row["SE_TIPOESTABLECIMIENTO"]:-1?>');
		</script>
	</body>
</html>