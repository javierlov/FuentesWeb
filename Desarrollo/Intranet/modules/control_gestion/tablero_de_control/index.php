<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/control_gestion/tablero_de_control/ver_permisos.php");


$idEstadistica = logUrlIn($_SERVER["REQUEST_URI"]);
?>
<html>
	<head>
		<title>Tablero de Control</title>
		<script language="JavaScript" src="/js/functions.js"></script>
		<link href="/modules/control_gestion/tablero_de_control/css/tablero_de_control.css" rel="stylesheet" type="text/css" />
	</head>
	<body onLoad="onLoadBody()">
		<input id="idEstadistica" type="hidden" value="<?= $idEstadistica?>" />
		<div align="center">
<?
if (verPermisos()) {
?>

			<a href="/control-gestion-permisos/t"><img id="imgPermisos" src="/images/mostrar_permisos.png" title="Ir al módulo de permisos" /></a>
<?
}
?>
			<img src="/modules/control_gestion/tablero_de_control/images/titulo_tdc.gif" />
		</div>

		<div align="center">
			<img src="/modules/control_gestion/tablero_de_control/images/piramide.jpg" usemap="#FPMap0" border="0"/>
			<map name="FPMap0">
				<area coords="209,133,234,143,238,135,257,139,272,133,274,135,281,133,282,141,278,151,286,163,270,162,266,169,258,173,246,180,240,178,229,176,227,167,222,160,206,150,217,145" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=1" shape="polygon">
				<area coords="148,179,308,189" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=1" shape="rect">
				<area coords="148,160,193,178" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=1" shape="rect">

				<area coords="226,232,235,228,239,230,250,221,285,234,282,246,285,257,283,271,282,273,270,279,273,276,260,276,251,276,262,277,239,276,236,277,229,278,219,279,218,277,213,275,212,267,217,255,208,243,209,230" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=2" shape="polygon">
				<area coords="146,279,314,291" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=2" shape="rect">
				<area coords="146,261,187,280" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=2" shape="rect">

				<area coords="218,316,277,362" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=3" shape="rect">
				<area coords="146,368,308,379" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=3" shape="rect">
				<area coords="147,348,192,368" href="/modules/control_gestion/tablero_de_control/loguear_acceso.php?t=3" shape="rect">
			</map>
		</div>
	</body>
</html>