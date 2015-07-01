<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


if ((isset($_REQUEST["al"])) and ($_REQUEST["al"] == "t")) {		// Si es autologin..
	$origen = "";
	if (isset($_REQUEST["o"]))
		$origen = $_REQUEST["o"];
	header("Location: validar_login.php?o=".$origen."&al=t&contrato=".$_REQUEST["contrato"]."&cuit=".$_REQUEST["cuit"]."&lote=".$_REQUEST["lote"]);
	exit;
}

if (!isset($_SESSION["contrato"])) {
	header("Location: /index.php?pageid=47");
	exit;
}

$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT em_nombre
		 FROM aco_contrato, aem_empresa
		WHERE co_idempresa = em_id
			AND co_contrato = :contrato";
$razonSocial = ValorSql($sql, "", $params);
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
						<table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="755" height="612">
							<tr>
								<td align="center" height="86" width="755"><img border="0" src="images/top.jpg"></td>
							</tr>
							<tr>
								<td align="left">
									<table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="725" height="25">
							<tr>
								<td width="10"></td>
								<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><font face="Trebuchet MS" style="font-size: 8pt"><span align="left"><b><font color="#00A4E4">Razón Social:</font>&nbsp; </b><?= $razonSocial?></span></td>
								<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px"><p align="right"><b><span align="right" style="text-decoration: none"><font color="#807F84" face="Trebuchet MS" style="font-size: 8pt">[<a href="#" onClick="window.location.href = 'logout.php';">Cerrar&nbsp;sesión</a>]</font></span></b></td>
							</tr>
						</table>
				</td>
			</tr>
			<tr>
				<td width="744" background="images/barraVerIzq.gif"><p align="center">
					<iframe name="conten" src="grilla.php" width="746" height="426" border="0" frameborder="0" scrolling="yes"?>" width="746" height="426" border="0" frameborder="0" scrolling="yes">
						El explorador no admite los marcos flotantes o no está configurado actualmente para mostrarlos.
					</iframe>
				</td>
			</tr>
			<tr>
				<td width="755" height="5"></td>
			</tr>
			<tr>
				<td width="755" height="60"><p align="center"><map name="FPMap0"><area target="_top" href="http://www.provinciart.com.ar/" shape="rect" coords="616, 14, 719, 46"></map><img border="0" src="images/bottom.jpg" usemap="#FPMap0"></td>
			</tr>
		</table>
	</body>
</html>