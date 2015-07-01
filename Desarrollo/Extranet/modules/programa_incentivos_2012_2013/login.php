<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");

if (isset($_SESSION["idUsuario"]))
	header("Location: ".LOCAL_PATH_PROGRAMA_INCENTIVOS."puntos.php");
if (!isset($_SESSION["cambiarPassword"]))
	$_SESSION["cambiarPassword"] = false;
if (!isset($_SESSION["fieldError"]))
	$_SESSION["fieldError"] = "";
if (!isset($_SESSION["msgError"]))
	$_SESSION["msgError"] = "";

$usuario = "";
if (isset($_REQUEST["sr"]))
	$usuario = $_REQUEST["sr"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<script language="JavaScript" src="/js/md5.js"></script>
		<style type="text/css">
			body {
				scrollbar-3dlight-color: #eee;
				scrollbar-arrow-color: #eee;
				scrollbar-darkshadow-color: #fff;
				scrollbar-face-color: #aaa;
				scrollbar-highlight-color: #aaa;
				scrollbar-shadow-color: #aaa;
				scrollbar-track-color: #e3e3e3;
			}
		</style>
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
	</head>
	<body background="images/fnd.jpg" topmargin="10">
		<div align="center">
			<div><img border="0" src="images/top.jpg" /></div>
			<div style="background-color:#fff; height:240px; width:755px;">
				<form action="/modules/programa_incentivos_2012_2013/validar_login.php" id="formLogin" method="post" name="formLogin" onSubmit="encriptarPassword()">
					<div style="margin-left:24px; padding-top:40px;">
						<span style="color:#807f84; font-face:Trebuchet MS; font-size:14px; font-weight:700;">Usuario</span>
						<input id="sr" name="sr" size="40" type="text" value="<?= $usuario?>" />
					</div>
<?
if (!$_SESSION["cambiarPassword"]) {
?>
					<div style="margin-top:4px;">
						<span style="color:#807f84; font-face:Trebuchet MS; font-size:14px; font-weight:700;">Contraseña</span>
						<input id="ps" name="ps" size="40" type="password" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080" />
					</div>
<?
}
if ($_SESSION["cambiarPassword"]) {
?>
					<div style="margin-left:-40px; margin-top:4px;">
						<span style="font-weight: 700"><font face="Trebuchet MS" size="2" color="#807F84">Contraseña nueva</font></span>
						<input id="psn" name="psn" size="40" type="password" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080" />
					</div>
					<div style="margin-left:-12px; margin-top:4px;">
						<span style="font-weight: 700"><font face="Trebuchet MS" size="2" color="#807F84">Confirmación</font></span>
						<input id="cnf" name="cnf" size="40" type="password" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080" />
					</div>
<?
}
?>
					<div align="left" style="color:#f00; margin-left:308px; margin-top:8px;">
						<?= $_SESSION["msgError"]?>
					</div>
					<div style="margin-left:64px; margin-top:8px;">
						<input id="btnIngresar" name="btnIngresar" type="submit" value="INGRESAR" style="background-color:#ccc; border:1px solid #808080; color:#808080; font-family:Trebuchet MS; font-size:10pt; padding-bottom:1px; padding-left:4px; padding-right:4px; padding-top:1px;" />
					</div>
				</form>
			</div>
			<div style="background-color:#fff; height:48px; width:755px;">
				<img border="0" src="images/empresas.jpg" />
			</div>
			<div>
				<map name="FPMap0">
					<area target="_blank" href="http://www.bapro.com.ar/" shape="rect" coords="615, 11, 721, 39">
				</map>
				<img border="0" src="images/bottom.jpg" usemap="#FPMap0" />
			</div>
		</div>
		<script type="text/javascript">
			obj = document.getElementById('<?= $_SESSION["fieldError"]?>');
			if (obj != null) {
				obj.style.borderColor = '#f00';
				obj.focus();
			}
			else
				document.getElementById('sr').focus();
		</script>
	</body>
</html>