<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


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
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Pago Transferencia ::..</title>
		<script language="JavaScript" src="/js/md5.js"></script>
		<script language="JavaScript" src="js/pago_transferencia.js"></script>
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
	</head>

	<body background="images/fnd.jpg" topmargin="10">
		<form action="validar_login.php" id="formLogin" method="post" name="formLogin" onSubmit="encriptarPassword()">
		<table border="0" width="100%" cellspacing="0" cellpadding="0" height="100%">
			<tr>
				<td align="center" valign="top">
					<table border="0" width="755" height="422" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" bordercolor="#FFFFFF" id="table4">
						<tr>
							<td colspan="3" height="96" width="755"><p align="center"><img border="0" src="images/top.jpg" width="755" height="96" /></td>
						</tr>
						<tr>
							<td height="20" colspan="3"></td>
						</tr>
						<tr>
							<td height="19" width="318">
								<p align="right">
									<span style="font-weight: 700">
									<font face="Trebuchet MS" size="2" color="#807F84">Usuario</font></span><font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">:&nbsp;</font><font face="Trebuchet MS"></font>
								</p>
							</td>
							<td width="210" height="19">
								<font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">
									<input id="sr" name="sr" size="40" type="text" value="<?= $usuario?>" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080" />
								</font>
							</td>
							<td height="19" width="227">&nbsp;</td>
						</tr>
<?
if (!$_SESSION["cambiarPassword"]) {
?>
						<tr>
							<td height="20" width="318">
								<p align="right">
									<span style="font-weight: 700">
										<font face="Trebuchet MS" size="2" color="#807F84">Contraseña:&nbsp;</font>
									</span>
								</p>
							</td>
							<td width="210" height="20">
								<input id="ps" name="ps" size="40" type="password" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080" />
							</td>
							<td height="20" width="227"></td>
						</tr>
<?
}
if ($_SESSION["cambiarPassword"]) {
?>
						<tr>
							<td height="20" width="318">
								<p align="right">
									<span style="font-weight: 700">
									<font face="Trebuchet MS" size="2" color="#807F84">Contraseña Nueva</font></span><font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">:&nbsp; </font>
								</p>
							</td>
							<td width="210" height="20">
								<input id="psn" name="psn" size="40" type="password" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080"><font face="Trebuchet MS"></font>
							</td>
							<td height="20" width="227"></td>
						</tr>
						<tr>
							<td height="20" width="318">
								<p align="right">
									<span style="font-weight: 700">
									<font face="Trebuchet MS" size="2" color="#807F84">Confirmación</font></span><font face="Trebuchet MS" style="font-size: 10pt; font-weight:700" color="#807F84">:&nbsp; </font>
								</p>
							</td>
							<td width="210" height="20">
								<input id="cnf" name="cnf" size="40" type="password" style="font-family: Trebuchet MS; font-size: 8pt; border: 1px solid #808080; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; background-color: #FFFFFF; color:#808080"><font face="Trebuchet MS">
								</font>
							</td>
							<td height="20" width="227"></td>
						</tr>
<?
}
?>
						<tr>
							<td height="10" width="755" colspan="3"></td>
						</tr>
						<tr>
							<td colspan="3">
								<p align="center"><font face="Neo Sans" style="font-size: 9pt"><?= $_SESSION["msgError"]?></font></p>
							</td>
						</tr>
						<tr>
							<td height="20" width="318">&nbsp;</td>
							<td width="210" height="20">
								<p align="right">
									<input class="btnIngresar" name="btnIngresar" type="submit" value="">
								</p>
							</td>
							<td height="20" width="227">&nbsp;</td>
						</tr>
						<tr>
							<td height="10" colspan="3"></td>
						</tr>
						<tr>
							<td width="753" colspan="3">
							&nbsp;</td>
						</tr>
						<tr>
							<td width="755" colspan="3" height="100"></td>
						</tr>
						<tr>
							<td width="755" colspan="3" height="85">
							<map name="FPMap0">
							<area target="_top" href="http://www.bapro.com.ar/" shape="rect" coords="452, 19, 563, 65">
							<area target="_top" href="http://www.provinciart.com.ar/" shape="rect" coords="598, 22, 715, 62">
							</map>
							<img border="0" src="images/bottom.jpg" width="755" height="85" usemap="#FPMap0"></td>
						</tr>
						</table>
				<p></td>
				</tr>
			</table>
		</form>
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