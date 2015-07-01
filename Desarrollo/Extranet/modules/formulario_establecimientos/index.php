<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


if (!isset($_SESSION["contrato"])) {
	header("Location: login.php");
	exit;
}

$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT em_nombre
		 FROM aco_contrato, aem_empresa
		WHERE co_idempresa = em_id
			AND co_contrato = :contrato";
$razonSocial = valorSql($sql, "", $params);
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Provincia ART ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			body {scrollbar-3dlight-color:#eee; scrollbar-arrow-color:#eee; scrollbar-darkshadow-color:#fff; scrollbar-face-color:#aaa; scrollbar-highlight-color:#aaa;
						scrollbar-shadow-color:#aaa; scrollbar-track-color:#e3e3e3;}
		</style>
		<link rel="shortcut icon" type="image/x-icon" href="../../favicoon.ico" />
	</head>

	<body link="#00539B" vlink="#00539B" alink="#00539B" topmargin="10" bottommargin="3" leftmargin="0" rightmargin="0" background="images/fnd.jpg">
		<table height="100%" width="100%">
			<tr>
				<td valign="top">
					<center>
						<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="755" height="612">
							<tr>
								<td align="center" height="86" width="755">
								<img border="0" src="images/top.jpg"></td>
							</tr>
							<tr>
								<td align="left">
									<table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="725" height="25">
										<tr>
											<td width="10"></td>
											<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font face="Trebuchet MS" style="font-size: 8pt"><span align="left">
											<b><font color="#00539B">Razón Social:</font>&nbsp; </b><?= $razonSocial?></span></td>
											<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
											<p align="right"><b><span align="right" style="text-decoration: none">
											<font color="#807F84" face="Trebuchet MS" style="font-size: 8pt">[<a href="#" onClick="window.location.href = 'logout.php';">Cerrar&nbsp;sesión</a>]</font></span></b></td>
										</tr>
									</table>
								</td>
							</tr>
							<tr>
								<td width="744" background="images/barraVerIzq.gif">
								<p align="center">
								<iframe name="conten" src="<?= $_REQUEST["fp"]?>" width="746" height="426" border="0" frameborder="0" scrolling="yes">
								El explorador no admite los marcos flotantes o no está configurado actualmente para mostrarlos.
								</iframe></td>
							</tr>
							<tr>
								<td width="755" height="5"></td>
							</tr>
							<tr>
								<td width="755" height="60">
									<p align="center"><map name="FPMap0">
									<area target="_top" href="http://www.provinciart.com.ar/" shape="rect" coords="616, 14, 719, 46">
									</map>
									<img border="0" src="images/bottom.jpg" usemap="#FPMap0"></td>
							</tr>
						</table>
					</center>
				</td>
			</tr>
		</table>
	</body>
</html>