<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));

SetDateFormatOracle("DD/MM/YYYY");

$isAlta = (!isset($_REQUEST["id"]));

if (!$isAlta) {
	$params = array(":id" => $_REQUEST["id"]);
	$sql =
		"SELECT et_calle, et_cpostal, et_cpostala, et_cuit_temporal, et_departamento, et_localidad, et_nombre, et_numero, et_nroestableci, et_observaciones, et_piso, et_provincia, et_telefonos
			 FROM SIN.set_establecimiento_temporal
			WHERE et_id = :id";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/portada.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<style type="text/css">
			* {
				margin: 0;
				padding: 0;
			}

			html, body {
				background-color: #FFF;
				overflow: hidden;
			}
		</style>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script src="/js/functions.js" type="text/javascript"></script>
	</head>
	<body style="margin:0; padding:0;">
	<iframe id="iframeEstablecimiento" name="iframeEstablecimiento" src="" style="display:none;"></iframe>
	<form action="/modules/usuarios_registrados/clientes/denuncias_de_siniestros/procesar_establecimiento.php" id="formEstablecimiento" method="post" name="formEstablecimiento" target="iframeEstablecimiento">
	<input id="id" name="id" type="hidden" value="<?= (!$isAlta)?$_REQUEST["id"]:-1?>">
	<input id="idProvincia" name="idProvincia" type="hidden" value="<?= (!$isAlta)?$row["ET_PROVINCIA"]:-1?>" />
	<div class="TituloSeccion" style="display:block; margin-left:4px; margin-right:4px; width:100%;"><?= ($isAlta)?"Alta":"Modificación"?> de Establecimiento de 3ro.</div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<div style="margin-left:69px;">
			<label>Nº</label>
			<input id="numeroEstablecimiento" maxlength="8" name="numeroEstablecimiento" style="width:64px;" type="text" value="<?= (!$isAlta)?$row["ET_NROESTABLECI"]:""?>" />
		</div>
		<div style="margin-left:38px; margin-top:4px;">
			<label>Nombre</label>
			<input id="nombre" maxlength="100" name="nombre" style="text-transform:uppercase; width:480px;" type="text" value="<?= (!$isAlta)?$row["ET_NOMBRE"]:""?>" />
		</div>
		<div style="margin-left:38px; margin-top:4px;">
			<label>C.U.I.T.</label>
			<input id="cuit" maxlength="13" name="cuit" style="width:88px;" type="text" value="<?= (!$isAlta)?$row["ET_CUIT_TEMPORAL"]:""?>" />
		</div>
		<div style="margin-left:28px; margin-top:4px;">
			<label>Teléfonos</label>
			<input id="telefonos" maxlength="60" name="telefonos" style="width:480px;" type="text" value="<?= (!$isAlta)?$row["ET_TELEFONOS"]:""?>" />
		</div>
		<div style="margin-left:0px; margin-top:4px;">
			<label style="float:left; vertical-align:top;">Observaciones<br />(máx. 150<br />caracteres)</label>
			<textarea id="observaciones" maxlength="150" name="observaciones" style="height:44px; width:480px;" /><?= (!$isAlta)?$row["ET_OBSERVACIONES"]:""?></textarea>
		</div>
		<div class="TituloTablaCeleste" style="margin-top:16px; width:100%">Domicilio</div>
		<div>
<?
$hayDatos = ((!$isAlta) and ($row["ET_CALLE"] != ""));
if (!$hayDatos) {
?>
			<p id="pSinDatosconocidos" style="margin-left:88px; margin-top:16px; width:144px;">
				<span>- Sin Datos Conocidos -</span>
			</p>
<?
}
?>
			<div id="divDatosDomicilio" style="display:<?= ($hayDatos)?"block":"none"?>;">
				<div style="margin-left:24px; margin-top:16px;">
					<label for="calle">Calle</label>
					<input id="calle" maxlength="60" name="calle" readonly style="background-color:#ccc; width:512px;" type="text" value="<?= (!$isAlta)?$row["ET_CALLE"]:""?>">
				</div>
				<div style="margin-left:8px; margin-top:4px;">
					<label for="numero">Número</label>
					<input id="numero" maxlength="6" name="numero" style="text-transform:uppercase; width:76px;" type="text" value="<?= (!$isAlta)?$row["ET_NUMERO"]:""?>">
					<label for="piso" style="margin-left:16px;">Piso</label>
					<input id="piso" maxlength="6" name="piso" style="text-transform:uppercase; width:76px;" type="text" value="<?= (!$isAlta)?$row["ET_PISO"]:""?>">
					<label for="departamento" style="margin-left:16px;">Departamento</label>
					<input id="departamento" maxlength="6" name="departamento" style="text-transform:uppercase; width:76px;" type="text" value="<?= (!$isAlta)?$row["ET_DEPARTAMENTO"]:""?>">
				</div>
				<div style="margin-top:4px;">
					<label for="localidad">Localidad</label>
					<input id="localidad" maxlength="85" name="localidad" readonly style="background-color:#ccc; width:320px;" type="text" value="<?= (!$isAlta)?$row["ET_LOCALIDAD"]:""?>">
					<label for="codigoPostal" style="margin-left:40px;">Código Postal</label>
					<input id="codigoPostal" maxlength="5" name="codigoPostal" readonly style="background-color:#ccc; width:67px;" type="text" value="<?= (!$isAlta)?$row["ET_CPOSTAL"]:""?>">
				</div>
				<div style="margin-top:4px;">
					<label for="provincia">Provincia</label>
					<input id="provincia" name="provincia" readonly style="background-color:#ccc; width:320px;" type="text" value="<?= (!$isAlta)?$row["ET_PROVINCIA"]:""?>">
				</div>
			</div>
			<p style="margin-left:88px; margin-top:8px;">
				<img border="0" src="/modules/usuarios_registrados/images/boton_modificar_domicilio.jpg" style="cursor:pointer;" onClick="buscarDomicilio(true, 'pSinDatosconocidos', 'divDatosDomicilio', 'idProvincia', 'provincia', 'localidad', '', 'codigoPostal', 'calle', 'numero', 'piso', 'departamento', 'domicilioManual', 352, 680, 8, 8);">
			</p>
		</div>
		<p style="margin-top:16px;">
			<input class="btnGrabar" type="submit" value="" />
			<span id="guardadoOk" style="background:#0f539c; color:#fff; display:none; margin-top:8px; padding:2px; width:192px;">Datos guardados exitosamente.</span>
			<span id="errores" style="border:solid 0px #0f539c; color:#f00; display:none; margin-top:8px; padding:2px;">
				<img border="0" src="/modules/usuarios_registrados/images/atencion.jpg" style="height:21px; vertical-align:-4px; width:19px;">
				Hay errores en la carga de los datos.
			</span>
			<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
		</p>
	</div>
</form>
<script type="text/javascript">
	document.getElementById('numeroEstablecimiento').focus();
</script>