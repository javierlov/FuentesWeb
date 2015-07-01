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
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getComputerAndUserName() {
//	$host = "SIN_HOST";
//	if (isset($_SERVER["REMOTE_HOST"]))
//		$host = $_SERVER["REMOTE_HOST"];
	$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
	return substr(strtoupper($_SESSION["usuario"]."/".$host), 0, 64);
}


validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
?>
<html>
	<head>
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
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/grid.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
		<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
		<script type="text/javascript">
			function aceptar() {
				if (document.getElementById('provincia2').value == 1) {		// Si es CABA..
					document.getElementById('divContentGrid').innerHTML = '';
					document.getElementById('divNoData').style.display = 'none';
				}
				else {
					if (document.getElementById('provincia2').value == -1) {
						document.getElementById('provincia2').focus();
						alert('Por favor, seleccione la provincia.');
						return;
					}
					if (document.getElementById('localidad2').value == '') {
						document.getElementById('localidad2').focus();
						alert('Por favor, seleccione la localidad.');
						return;
					}
					if (document.getElementById('calle2').value == '') {
						document.getElementById('calle2').focus();
						alert('Por favor, ingrese la calle.');
						return;
					}

					if (parent.document.getElementById('<?= $_REQUEST["objCalle"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objCalle"]?>').value = document.getElementById('calle2').value.toUpperCase();
					if (parent.document.getElementById('<?= $_REQUEST["objCp"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objCp"]?>').value = document.getElementById('codigoPostal2').value.toUpperCase();
					if (parent.document.getElementById('<?= $_REQUEST["objDatosDomicilio"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objDatosDomicilio"]?>').style.display = 'block';
					if (parent.document.getElementById('<?= $_REQUEST["objDepartamento"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objDepartamento"]?>').value = document.getElementById('departamento2').value.toUpperCase();
					if (parent.document.getElementById('<?= $_REQUEST["objDomicilioManual"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objDomicilioManual"]?>').value = 't';
					if (parent.document.getElementById('<?= $_REQUEST["objIdProvincia"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objIdProvincia"]?>').value = document.getElementById('provincia2').value.toUpperCase();
					if (parent.document.getElementById('<?= $_REQUEST["objLocalidad"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objLocalidad"]?>').value = document.getElementById('localidad2').value.toUpperCase();
					if (parent.document.getElementById('<?= $_REQUEST["objNumero"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objNumero"]?>').value = document.getElementById('numero2').value.toUpperCase();
					if (parent.document.getElementById('<?= $_REQUEST["objPiso"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objPiso"]?>').value = document.getElementById('piso2').value.toUpperCase();
					if (parent.document.getElementById('<?= $_REQUEST["objProvincia"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objProvincia"]?>').value = document.getElementById('provincia2').options[document.getElementById('provincia2').selectedIndex].text;
					if (parent.document.getElementById('<?= $_REQUEST["objSinDatosConocidos"]?>') != null)
						parent.document.getElementById('<?= $_REQUEST["objSinDatosConocidos"]?>').style.display = 'none';

				parent.divWin.close();
				}
			}

			function buscarLocalidad(idprovincia) {
				var height = 368;
				var width = 640;
				var left = 16;
				var top = 16;

				divWinLocalidad = null;
				divWinLocalidad = dhtmlwindow.open('divBoxLocalidad', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
				divWinLocalidad.load('iframe', '/functions/buscar_localidad.php?p=' + idprovincia, 'Buscar Localidad');
				divWinLocalidad.show();
			}

			function cambiaProvincia2(valor) {
				with (document)
					if (valor == 1) {		// CABA..
						getElementById('divMensajeCABA').style.display = 'block';
						getElementById('divNoCABA').style.display = 'none';
					}
					else {
						getElementById('divMensajeCABA').style.display = 'none';
						getElementById('divNoCABA').style.display = 'inline';

						getElementById('calle2').value = '';
						getElementById('codigoPostal2').value = '';
						getElementById('departamento2').value = '';
						getElementById('localidad2').value = '';
						getElementById('numero2').value = '';
						getElementById('piso2').value = '';
					}
			}

			function validarBusqueda(frm) {
				with (document) {
					var calle = '';
					if (getElementById('calle') != null) {
						getElementById('calle').value = getElementById('calle').value.toUpperCase();
						calle = getElementById('calle').value;
					}
					getElementById('cpa').value = getElementById('cpa').value.toUpperCase();
					getElementById('localidad').value = getElementById('localidad').value.toUpperCase();

					if (ValidarForm(frm))
						if ((calle == '') &&
							(getElementById('altura').value == '') &&
							(getElementById('codigoPostal').value == '') &&
							(getElementById('cpa').value == '') && (getElementById('localidad').value == '') &&
							(getElementById('provincia').value == '-1')) {
							alert('Por favor, seleccione algún filtro.');
							return false;
						}
						else {
							getElementById('divNoData').style.display = 'none';
							getElementById('divContentGrid').style.display = 'none';
							getElementById('divProcesando').style.display = 'block';
							getElementById('divMensaje2').style.display = 'block';
							return true;
						}
					else
						return false;
				}
			}
		</script>
	</head>
	<body style="margin:0; padding:0;">
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		<form action="/functions/buscar_domicilio_busqueda.php" id="formBuscarDomicilio" method="post" name="formBuscarDomicilio" target="iframeProcesando" onSubmit="return validarBusqueda(formBuscarDomicilio)">
			<input id="buscarcalle" name="buscarcalle" type="hidden" value="<?= $_REQUEST["buscarcalle"]?>">
			<input id="objCalle" name="objCalle" type="hidden" value="<?= $_REQUEST["objCalle"]?>">
			<input id="objCp" name="objCp" type="hidden" value="<?= $_REQUEST["objCp"]?>">
			<input id="objCpa" name="objCpa" type="hidden" value="<?= $_REQUEST["objCpa"]?>">
			<input id="objDatosDomicilio" name="objDatosDomicilio" type="hidden" value="<?= $_REQUEST["objDatosDomicilio"]?>">
			<input id="objDepartamento" name="objDepartamento" type="hidden" value="<?= $_REQUEST["objDepartamento"]?>">
			<input id="objDomicilioManual" name="objDomicilioManual" type="hidden" value="<?= $_REQUEST["objDomicilioManual"]?>">
			<input id="objIdProvincia" name="objIdProvincia" type="hidden" value="<?= $_REQUEST["objIdProvincia"]?>">
			<input id="objLocalidad" name="objLocalidad" type="hidden" value="<?= $_REQUEST["objLocalidad"]?>">
			<input id="objNumero" name="objNumero" type="hidden" value="<?= $_REQUEST["objNumero"]?>">
			<input id="objPiso" name="objPiso" type="hidden" value="<?= $_REQUEST["objPiso"]?>">
			<input id="objProvincia" name="objProvincia" type="hidden" value="<?= $_REQUEST["objProvincia"]?>">
			<input id="objSinDatosConocidos" name="objSinDatosConocidos" type="hidden" value="<?= $_REQUEST["objSinDatosConocidos"]?>">
			<div class="Text5" style="background-color:#216B94; color:#fff; padding:4px;">
				Ingrese algunos de los siguientes datos para ubicar el domicilio deseado.
			</div>
			<div style="background-color:#ecf5ff; padding-bottom:8px; padding-left:8px; padding-top:8px; position:relative; right:0;">
				<div style="margin-bottom:4px;">
					<label class="Text5" for="codigoPostal">Código Postal</label>
					<input id="codigoPostal" maxlength="4" name="codigoPostal" style="width:48px;" title="Código Postal" type="text" validarEntero="true" value="" />
					<label class="Text5" for="cpa" style="margin-left:16px;">C.P.A.</label>
					<input id="cpa" maxlength="10" name="cpa" style="text-transform:uppercase; width:123px;" type="text" value="" />
				</div>
				<div style="margin-bottom:4px; margin-left:28px;">
					<label class="Text5" for="localidad">Localidad</label>
					<input id="localidad" maxlength="128" name="localidad" style="text-transform:uppercase; width:240px;" type="text" value="" />
					<label class="Text5" for="provincia" style="margin-left:16px;">Provincia</label>
					<select id="provincia" name="provincia"></select>
				</div>
<?
if ($_REQUEST["buscarcalle"] == "t") {
?>
				<div style="margin-bottom:4px; margin-left:58px;">
					<label class="Text5" for="calle">Calle</label>
					<input id="calle" maxlength="128" name="calle" style="text-transform:uppercase; width:240px;" type="text" value=""/>
					<label class="Text5" for="tipoCalle" style="margin-left:16px;">Contiene</label>
					<input checked id="tipoCalle" name="tipoCalle" type="radio" value="c">
					<label class="Text5" for="tipoCalle" style="margin-left:4px;">Empieza</label>
					<input id="tipoCalle" name="tipoCalle" type="radio" value="e">
				</div>
				<div style="margin-bottom:4px; margin-left:40px;">
					<label class="Text5" for="altura">Número</label>
					<input id="altura" maxlength="10" name="altura" title="Número" style="width:120px;" type="text" validarEntero="true" value="" />
				</div>
<?
}
?>
				<input class="btnBuscar" style="margin-top:12px;" type="submit" value="">
			</div>
			<div class="Text5" id="divMensaje2" style="background-color:#216B94; color:#fff; display:none; padding:4px;"></div>
		</form>
		<div align="center" id="divContentGrid" name="divContentGrid" style="height:<?= ($_REQUEST["buscarcalle"] == "t")?236:285?>px; overflow:auto;"></div>
		<div align="center" id="divNoData" name="divNoData" style="display:none; height:<?= ($_REQUEST["buscarcalle"] == "t")?236:285?>px; overflow:auto;">
			<div align="left" class="Text5" id="divMensaje3" style="background-color:#216B94; color:#fff; padding:4px;"></div>
			<div align="left" style="margin-top:16px;">
				<p style="margin-left:24px; margin-top:8px;">
					<label class="Text5" for="provincia2">Provincia</label>
					<select id="provincia2" name="provincia2" onChange="cambiaProvincia2(this.value)"></select>
				</p>
				<div align="center" class="Text5" id="divMensajeCABA" style="background-color:#fdf2d3; color:#000; padding-right:16px; margin-top:32px; width:100%;">
					Para un domicilio en Capital Federal, todas las calles estan disponibles, por favor reintente la consulta omitiendo alguno de los filtros.<br />
					Si el inconveniente persiste, contáctese con Atención al Cliente.
				</div>
				<div class="Text5" id="divNoCABA" style="">
					<p style="margin-left:23px; margin-top:8px;">
						<label class="Text5" for="localidad2">Localidad</label>
						<input id="localidad2" maxlength="85" name="localidad2" readonly style="background-color:#ccc; text-transform:uppercase; width:280px;" type="text" value="" />
						<label class="Text5" for="codigoPostal2" style="margin-left:16px;">Código Postal</label>
						<input id="codigoPostal2" maxlength="4" name="codigoPostal2" readonly style="background-color:#ccc; text-transform:uppercase; width:67px;" type="text" value="" />
						<input class="btnBuscar" style="vertical-align:-3px;" type="button" value="" onClick="buscarLocalidad(document.getElementById('provincia2').value)" />
					</p>
					<p style="margin-left:53px; margin-top:8px;">
						<label class="Text5" for="calle2">Calle</label>
						<input id="calle2" maxlength="60" name="calle2" style="text-transform:uppercase; width:520px;" type="text" value="" />
					</p>
					<p style="margin-left:35px; margin-top:8px;">
						<label class="Text5" for="numero2">Número</label>
						<input id="numero2" maxlength="6" name="numero2" style="text-transform:uppercase; width:76px;" type="text" value="" />
						<label class="Text5" for="piso2" style="margin-left:16px;">Piso</label>
						<input id="piso2" maxlength="6" name="piso2" style="text-transform:uppercase; width:76px;" type="text" value="" />
						<label class="Text5" for="departamento2" style="margin-left:16px;">Departamento</label>
						<input id="departamento2" maxlength="6" name="departamento2" style="text-transform:uppercase; width:76px;" type="text" value="" />
					</p>
				</div>
				<p align="center" style="margin-top:12px;">
					<img border="0" src="/modules/usuarios_registrados/images/boton_aceptar.jpg" style="cursor:pointer;" onClick="aceptar()" />
				</p>
			</div>
		</div>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:8px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..." /></div>
		<script type="text/javascript">
<?
// FillCombos..
$excludeHtml = true;
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/refresh_combo.php");

$RCwindow = "window";

$RCfield = "provincia";
$RCparams = array();
$RCquery =
	"SELECT DISTINCT pv_codigo id, pv_descripcion detalle
							FROM cpv_provincias
					ORDER BY 2";
$RCselectedItem = -1;
FillCombo();
?>
			document.getElementById('codigoPostal').focus();
		</script>
	</body>
</html>