<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function permisoEdicion() {
	$loginName = GetWindowsLoginName(true);

	return (($loginName == "AANGIOLILLO") or
					($loginName == "ALAPACO") or
					($loginName == "DBURGOS") or
					($loginName == "GGUEVARA") or
					($loginName == "GIECHEVERRIA") or
					($loginName == "NSTABILE") or
					($loginName == "NVALENTE") or
					($loginName == "SCOMAN") or
					($loginName == "VVARAS"));
}


if (!isset($_SESSION["RegionesSanitariasEditar"]))
	$_SESSION["RegionesSanitariasEditar"] = false;

$idEstadistica = logUrlIn($_SERVER["REQUEST_URI"]);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		<title>Contenido</title>
		<script language="JavaScript" src="/js/functions.js"></script>
		<script src="/modules/regiones_sanitarias/js/regiones_sanitarias.js?rnd=<?= date("Ymdhisu")?>" type="text/javascript"></script>
	</head>

	<body bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" style="text-align:center;" topmargin="5" onLoad="onLoadBody()">
		<input id="idEstadistica" type="hidden" value="<?= $idEstadistica?>" />
		<div align="center">
			<div style="left:0px; position:relative; top:0px;">
				<map name="FPMap0">
					<area coords="22, 4, 144, 126" href="#" shape="rect" onClick="selectProvincia(10);">
					<area coords="158, 4, 278, 126" href="#" shape="rect" onClick="selectProvincia(9);">
					<area coords="294, 4, 412, 126" href="#" shape="rect" onClick="selectProvincia(16);">
					<area coords="430, 4, 546, 126" href="#" shape="rect" onClick="selectProvincia(13);">
					<area coords="562, 4, 678, 126" href="#" shape="rect" onClick="selectProvincia(5);">
					<area coords="690, 4, 808, 126" href="#" shape="rect" onClick="selectProvincia(6);">

					<area coords="24, 132, 144, 254" href="#" shape="rect" onClick="selectProvincia(21);">
					<area coords="158, 132, 278, 254" href="#" shape="rect" onClick="selectProvincia(23);">
					<area coords="294, 132, 412, 254" href="#" shape="rect" onClick="selectProvincia(3);">
					<area coords="430, 132, 546, 254" href="#" shape="rect" onClick="selectProvincia(24);">
					<area coords="562, 132, 678, 254" href="#" shape="rect" onClick="selectProvincia(17)">
					<area coords="690, 132, 808, 254" href="#" shape="rect" onClick="selectProvincia(8);">

					<area coords="24, 262, 144, 382" href="#" shape="rect" onClick="selectProvincia(20);">
					<area coords="158, 262, 278, 382" href="#" shape="rect" onClick="selectProvincia(4);">
					<area coords="294, 262, 412, 382" href="#" shape="rect" onClick="selectProvincia(18);">
					<area coords="430, 262, 546, 382" href="#" shape="rect" onClick="selectProvincia(12);">
					<area coords="560, 262, 678, 382" href="#" shape="rect" onClick="selectProvincia(2);">
					<area coords="690, 262, 808, 382" href="#" shape="rect" onClick="selectProvincia(11);">

					<area coords="24, 392, 142, 542" href="#" shape="rect" onClick="selectProvincia(14);">
					<area coords="158, 392, 276, 542" href="#" shape="rect" onClick="selectProvincia(15);">
					<area coords="294, 392, 412, 542" href="#" shape="rect" onClick="selectProvincia(7);">
					<area coords="430, 392, 546, 512" href="#" shape="rect" onClick="selectProvincia(19);">
					<area coords="560, 392, 678, 512" href="#" shape="rect" onClick="selectProvincia(22);">
				</map>
				<img border="0" src="/modules/regiones_sanitarias/imagenes/provincias<?= ($_SESSION["RegionesSanitariasEditar"]?"ME":"")?>.gif" usemap="#FPMap0" />
			</div>
			<div style="margin-left:685px; position:relative; top:-130px;">
<?
if (permisoEdicion())
	if ($_SESSION["RegionesSanitariasEditar"]) {
?>
		<a href="/modules/regiones_sanitarias/cambiar_modo.php?modo=c"><img border="0" src="/modules/regiones_sanitarias/imagenes/modo_consulta.gif" /></a>
<?
	}
	else {
?>
		<a href="/modules/regiones_sanitarias/cambiar_modo.php?modo=e"><img border="0" src="/modules/regiones_sanitarias/imagenes/modo_edicion.gif" /></a>
<?
	}
else
	$_SESSION["RegionesSanitariasEditar"] = false;
?>
			</div>
		</div>
	</body>
</html>