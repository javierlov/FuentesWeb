<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


// Valido que se haya logueado..
if (!isset($_SESSION["idUsuario"])) {
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php");
	exit;
}

$params = array(":id" => $_SESSION["idUsuario"]);
$sql =
	"SELECT pl_empleado
		 FROM rrhh.dpl_login
		WHERE pl_id = :id";
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Sistema de Gestión de RR.HH. ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<script language="JavaScript" src="js/evaluacion_puesto.js"></script>
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

<body link="#807F84" vlink="#807F84" alink="#807F84" topmargin="10" bottommargin="3" leftmargin="0" rightmargin="0" background="images/fnd.jpg">
	<table height="100%" width="100%">
		<tr>
			<td valign="top">
				<center>
					<table border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="755">
						<tr>
							<td align="center" height="62" width="755">
								<img border="0" src="images/top.jpg">
							</td>
						</tr>
						<tr>
							<td align="left">
								<table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" width="725" height="25">
									<tr>
										<td width="10"></td>
										<td style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
											<font face="Trebuchet MS" style="font-size: 8pt">
												<font color="#807F84"><b>Usuario</b></font>
												<span align="left"><b><font color="#807F84">:</font>&nbsp; </b><?= ValorSql($sql, "", $params)?></span>
										</td>
										<p align="right">
										<td width="76" align="right" style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
											<span id="volver">
												<font color="#6FB747" face="Trebuchet MS" style="font-size: 8pt; font-weight:700">&lt;&lt; <a href="#" onClick="volver()"><font color="#6FB747">Volver</font></a></font>
											</span>
										</td>
										<td width="84" align="right" style="border-bottom: 1px dotted #807F84; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
											<span align="right" style="text-decoration: none">
												<font color="#807F84" face="Trebuchet MS" style="font-size: 8pt; font-weight:700">[<a href="#" onClick="cerrarSesion()">Cerrar&nbsp;sesión</a>]</font>
											</span>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td width="744" valign="top">
								<p align="center">
									<iframe name="conten" src="conten.php" width="746" height="470" border="0" frameborder="0" scrolling="yes">
										El explorador no admite los marcos flotantes o no está configurado actualmente para mostrarlos.
									</iframe>
							</td>
						</tr>
						<tr>
							<td width="755" height="5"></td>
						</tr>
						<tr>
							<td width="755" height="50">
								<p align="center"><map name="FPMap0">
									<map name="FPMap0">
										<area target="_blank" href="http://www.grupoprovincia.com.ar/" shape="rect" coords="618, 6, 725, 41">
									</map>
									<img border="0" src="images/bottom.jpg" usemap="#FPMap0">
							</td>
						</tr>
					</table>
				</center>
			</td>
		</tr>
	</table>
</body>
</html>