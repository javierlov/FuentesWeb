<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


if (!isset($_SESSION["idUsuario"]))
	$_SESSION["idUsuario"] = -1;
if (!hasPermiso(4, $_SESSION["idUsuario"])) {
?>
	<script type="text/javascript">
		window.location.href = '/modules/pago_transferencia/login.php';
	</script>
<?
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
				scrollbar-face-color: #aaa;
				scrollbar-highlight-color: #aaa;
				scrollbar-shadow-color: #aaa;
				scrollbar-3dlight-color: #eee;
				scrollbar-arrow-color: #eee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: #fff;
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
											<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
												<font face="Trebuchet MS" style="font-size: 8pt">
													<font color="#00A4E4"><b>Usuario</b></font>
													<span align="left"><b><font color="#00A4E4">:</font>&nbsp; </b><?= $_SESSION["usuario"]?></span>
												</font>
											</td>
											<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
												<p align="right">
													<b>
														<span align="right" style="text-decoration: none">
															<font color="#807F84" face="Trebuchet MS" style="font-size: 8pt">[<a href="#" onClick="window.location.href = 'logout.php';">Cerrar&nbsp;sesión</a>]</font>
														</span>
													</b>
												</p>
											</td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="744" background="images/barraVerIzq.gif">
									<p align="center">
										<iframe name="conten" src="pago_transferencia.php" width="746" height="323" border="0" frameborder="0">
										El explorador no admite los marcos flotantes o no está configurado actualmente para mostrarlos.
										</iframe>
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