<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // HTTP/1.1
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

validarIngresoPrimeraVez();

$pageid = -1;
if (isset($_REQUEST["pageid"]))
	$pageid = intval($_REQUEST["pageid"]);

$idEstadistica = '';
if ($pageid != 90)		// La página 90 es el error 404, asi que no hace falta loguearlo..
	$idEstadistica = logUrlIn($_SERVER["REQUEST_URI"]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<?= getHead(getPageTitle($pageid), array("form_elements.css", "general.css", "grid.css", "header.css", "list_of_items.css", "/functions/comentarios/css/comentarios.css", "style.css"))?>
		<script type="text/javascript">
			redirectToGestionSistemas();
		</script>
	</head>
	<body onKeyDown="keyDown(event)" onKeyUp="keyUp(event)" onLoad="onLoadBody()" onResize="resizeBody()">
		<iframe id="iframeGeneral" name="iframeGeneral" src="" style="display:none;"></iframe>
		<input id="idEstadistica" type="hidden" value="<?= $idEstadistica?>" />
		<input id="iPaginaPublica" type="hidden" value="<?= (isPublicPage($pageid))?"t":"f"?>" />
		<div align="center" id="divContainer">
			<div id="divHeader"><? require_once("header.php") ?></div>
			<div id="divMain">
				<div id="divMenu"><? require_once("menu.php") ?></div>
				<div id="divContenido"><? require_once(getPagePath($pageid))?></div>
				<div id="divNada"></div>
			</div>
		</div>
<!--ZOOMSTOP-->
<!-- ***  VISOR DE IMÁGENES  -  INICIO.. -->
		<div id="divVisorImagenes" onClick="cerrarVisorImagenes()">
			<div id="divVisorImagenesFondo"></div>
			<div id="divVisorImagenesImagen">
				<img id="imgVisorImagenesCargandoImagen" src="/images/visor_imagenes/loading_grande.gif" style="position:absolute;" />
				<img id="imgVisorImagenesImagen" />
			</div>
			<div id="divVisorImagenesFlechas" style="display:none;">
				<div id="divVisorImagenesFlechaAnterior" onMouseOut="mouseOutFlechas()" onMouseOver="mouseOverFlechas()"><img src="/images/visor_imagenes/anterior.gif" /></div>
				<div id="divVisorImagenesFlechaSiguiente" onMouseOut="mouseOutFlechas()" onMouseOver="mouseOverFlechas()"><img src="/images/visor_imagenes/siguiente.gif" /></div>
			</div>
		</div>
<!-- ***  VISOR DE IMÁGENES  -  FIN.. -->

<!-- ***  MENSAJE DE ERROR  -  INICIO.. -->
		<div id="divMsgError">
			<div id="divMsgErrorFondo">
				<div id="divMsgErrorFormulario">
					<div id="divMsgErrorIcono"><img src="/images/icono_error.png" /></div>
					<div id="divMsgErrorTexto"></div>
					<div id="divNada"></div>
					<div id="divMsgErrorBoton"><input id="btnAceptar" type="button" onClick="aceptarMsgError()" /></div>
				</div>
			</div>
		</div>
<!-- ***  MENSAJE DE ERROR  -  FIN.. -->

<!-- ***  MENSAJE DE OK  -  INICIO.. -->
		<div id="divMsgOk">
			<div id="divMsgOkIcono"><img src="/images/icono_ok.png" /></div>
			<div id="divMsgOkTexto">La operación finalizó exitosamente.</div>
			<div id="divNada"></div>
			<div id="divMsgOkBoton"><input id="btnAceptar" type="button" onClick="aceptarMsgOk()" /></div>
		</div>
<!-- ***  MENSAJE DE OK  -  FIN.. -->
<!--ZOOMRESTART-->
	</body>
</html>