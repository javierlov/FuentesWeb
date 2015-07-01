<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");


if (!isset($_SESSION["idUsuario"])) {
?>
	<script type="text/javascript">
		window.location.href = '/modules/admin_users_web/login.php';
	</script>
<?
	exit;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Provincia ART ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css"> 
			body {
				scrollbar-face-color: #aaaaaa;
				scrollbar-highlight-color: #aaaaaa;
				scrollbar-shadow-color: #aaaaaa;
				scrollbar-3dlight-color: #eeeeee;
				scrollbar-arrow-color: #eeeeee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: ffffff;
			}
		</style> 
	</head>
	<body link="#00A4E4" vlink="#00A4E4" alink="#00A4E4" topmargin="10" bottommargin="3" leftmargin="0" rightmargin="0" background="images/fnd.jpg">
		<table height="100%" width="100%">
			<tr>
				<td valign="top">
					<center>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="755" height="422">
							<tr>
								<td align="center" height="96" width="755"><img border="0" src="images/top.jpg" width="755" height="96"></td>
							</tr>
							<tr>
								<td align="left">
									<table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="725" height="25">
										<tr>
											<td width="10"></td>
											<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font face="Trebuchet MS" style="font-size: 8pt"><font color="#00A4E4"><b>Usuario</b></font><span align="left"><b><font color="#00A4E4">:</font>&nbsp; <?= $_SESSION["usuario"]?></b></span></td>
											<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right"><b><span align="right" style="text-decoration: none"><font color="#807F84" face="Trebuchet MS" style="font-size: 8pt">[<a href="#" onClick="window.location.href = 'logout.php';">Cerrar&nbsp;sesión</a>]</font></span></b></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="744" background="images/barraVerIzq.gif">
									<p align="center">
<?
if (isset($_REQUEST["pageid"]))
	$pageid = $_REQUEST["pageid"];
else
	$pageid = 1;

$url = $_SERVER["DOCUMENT_ROOT"]."/modules/admin_users_web/";
switch ($pageid) {
	case 1:
		$url.= "main.php";
		break;
	case 2:
		$url.= "usuario.php";
		break;
}

require_once($url);
?>
									</p>
									</td>
							</tr>
							<tr>
								<td width="755" height="5"></td>
							</tr>
							<tr>
								<td width="755" height="85">
									<p align="center">
										<map name="FPMap0">
											<area target="_top" href="http://www.provinciart.com.ar/" shape="rect" coords="589, 19, 715, 63">
											<area target="_top" coords="450, 19, 563, 65" shape="rect" href="http://www.bapro.com.ar/">
										</map>
										<img border="0" src="images/bottom.jpg" width="755" height="85" usemap="#FPMap0">
								</td>
							</tr>
						</table>
					</center>
				</td>
			</tr>
		</table>
	</body>
</html>