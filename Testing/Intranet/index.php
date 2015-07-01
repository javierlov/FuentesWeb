<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/error.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


// **   INICIO VALIDACIÓN CONEXIÓN CON BASE DE DATOS   **
$sql = "SELECT 1 FROM DUAL";
$stmt = DBExecSql($conn, $sql);
if (!$stmt)
	header("Location: mantenimiento.html");
// **   FIN VALIDACIÓN   **


$pageid = -1;
if (isset($_REQUEST["pageid"]))
	$pageid = intval($_REQUEST["pageid"]);

$hasPermiso = HasPermiso($pageid);
if (($pageid != -1) and (!$hasPermiso)) {
	ShowError(GetPageName($pageid), "Usted no tiene permiso para ingresar a esta página.");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<?= GetHead(GetPageTitle($pageid), array("style.css", "new_style.css"))?>
		<script>
			var _x_en_div;
			var _y_en_div;
			var _x;
			var _y;
			var isCtrl = false;
			var isIE = document.all?true:false;

			document.onmousemove = getMousePosition;


			function getMousePosition(ev) {
				if (isIE) {
					_x = event.clientX + document.body.scrollLeft;
					_y = event.clientY + document.body.scrollTop;

					_x_en_div = event.offsetX + document.body.scrollLeft;
					_y_en_div = event.offsetY + document.body.scrollTop;
				}
				else {
					_x = event.pageX;
					_y = event.pageY;

					_x_en_div = event.pageX;
					_y_en_div = event.pageY;
				}

				posX = _x;
				posY = _y;

        var  pos = Array(posX, posY);

				return pos;
			}

			function keyDown(event) {
				var keyCode = event.which;
				if (keyCode == undefined)
					keyCode = event.keyCode;

				if (keyCode == 17)
					isCtrl = true;

				if (((keyCode == 85) || (keyCode == 117)) && (isCtrl == true)) {
					document.getElementById('divBusqueda').style.display = 'block';
					document.getElementById('usrBusquedaRapida').focus();
				}


// ***  VISOR DE IMÁGENES  -  INICIO..
				if (isVisorImagenesVisible()) {
					if (keyCode == 27) {
						cerrarVisor = true;
						cerrarVisorImagenes()
					}

					if ((keyCode == 37) && (isFlechaAnteriorVisible())) {
						cerrarVisor = false;
						document.getElementById('divVisorImagenesFlechaAnterior').click();
						cerrarVisor = true;
					}

					if (keyCode == 38) {
						mostrarImagen(0);
					}

					if ((keyCode == 39) && (isFlechaSiguienteVisible())) {
						cerrarVisor = false;
						document.getElementById('divVisorImagenesFlechaSiguiente').click();
						cerrarVisor = true;
					}

					if (keyCode == 40) {
						mostrarImagen(arrVisorImagenes.length - 1);
					}
				}
// ***  VISOR DE IMÁGENES  -  FIN..
			}

			function keyUp(e) {
				if (e.keyCode == 17)
					isCtrl = false;
			}

			function load() {
				document.getElementById('ContentUrl').value = <?= (IsPublicPage($pageid))?"'--'":"window.location.href"?>;
				document.getElementById('PageId').value = <?= $pageid?>;
				setTimeout('movecube()', delay);
				resize();
			}

			function redirectToGestionSistemas() {
				topUrl = top.location.href;
				if (getUrlParamValue(window.location.href, "pageid") == '') {
					params = topUrl.substr(topUrl.indexOf('?') + 1, 10000);
					redirect = getUrlParamValue(topUrl, "gs");
					if (redirect == 't')
						window.location.href = '/index.php?pageid=38&' + params;
				}
			}

			function resize() {
				// Set content height..
				if (document.body.clientHeight > 0) {
					var screenHeight = document.body.clientHeight;
					var header = document.getElementById('tope').offsetHeight;
					var footer = document.getElementById('footer').offsetHeight;
					document.getElementById('content').style.height = (screenHeight - (header + footer) - 2) + "px";

					// Si se muestra la barra de scoll, corro el contenido un poco mas a la derecha para que quede centrado..
					if (document.getElementById('contentIn').offsetHeight > document.getElementById('content').offsetHeight)
						document.getElementById('contentIn').style.paddingLeft = '16px';
					else
						document.getElementById('contentIn').style.paddingLeft = '0';
				}
			}

			function showPermisosWindow() {
				if ((window.location.href != document.getElementById('ContentUrl').value) || (document.getElementById('PageId').value == -1)) {
					alert('Esta página es pública por lo tanto no se le puede configurar permisos.');
					return null;
				}

				OpenWindow('Modules/Permisos/permisos.php?pageid=' + document.getElementById('PageId').value, 'ProvartPopup', 720, 320, 'no', 'no');
			}


			redirectToGestionSistemas();

			try {
				showTitle(false);
			}
			catch (err) {
				//
			}
		</script>
	</head>
	<body onKeyDown="keyDown(event)" onKeyUp="keyUp(event)" onLoad="load()" onResize="resize()">
		<div>
			<input id="ContentUrl" type="hidden" value="">
			<input id="PageId" type="hidden" value="-1">
			<div id="tope"></div>
			<div align="center" id="fijo">
				<div id="header"><? require_once("header.php") ?></div>
				<div id="menu"><? require_once("menu.php") ?></div>
				<div align="left" id="title" style="display: none; height: 0;"><span id="titleText"></span></div>
			</div>
			<div id="divBusqueda" style="background-color:#dd8; display:none; height:40px; left:400px; margin-left:8px; margin-top:8px; position:absolute; top:200px; width:268px;">
				<form action="/index.php?pageid=5&buscar=yes&Sector=" id="formUsr" method="post" name="formUsr" style="margin-left:8px; margin-top:8px;">
					<label for="usrBusquedaRapida">Usuario</label>
					<input id="usrBusquedaRapida" name="usrBusquedaRapida" type="text" />
					<input type="submit" value="Buscar" />
				</form>
			</div>
			<div align="center" id="footer"><div id="footerIn"><? require_once("footer.php")?></div></div>
			<div id="content"><div id="contentIn"><? require_once(GetPagePath($pageid))?></div></div>
		</div>

<!-- ***  VISOR DE IMÁGENES  -  INICIO.. -->
		<div id="divVisorImagenes" onClick="cerrarVisorImagenes()">
			<div id="divVisorImagenesFondo"></div>
			<div id="divVisorImagenesImagen">
				<img id="imgVisorImagenesCargandoImagen" src="/images/loading_grande.gif" style="position:absolute;" />
				<img id="imgVisorImagenesImagen" />
			</div>
			<div id="divVisorImagenesFlechas" style="display:none;">
				<div id="divVisorImagenesFlechaAnterior" onMouseOut="mouseOutFlechas()" onMouseOver="mouseOverFlechas()"><img src="/images/anterior.gif" /></div>
				<div id="divVisorImagenesFlechaSiguiente" onMouseOut="mouseOutFlechas()" onMouseOver="mouseOverFlechas()"><img src="/images/siguiente.gif" /></div>
			</div>
		</div>
<!-- ***  VISOR DE IMÁGENES  -  FIN.. -->
	</body>
</html>