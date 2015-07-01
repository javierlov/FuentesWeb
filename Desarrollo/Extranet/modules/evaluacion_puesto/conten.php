<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");


// Valido que se haya logueado..
if (!isset($_SESSION["idUsuario"])) {
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php");
	exit;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="es-ar" />
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Sistema de Gestión de RR.HH. ::..</title>
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<style type="text/css">
			body {scrollbar-3dlight-color:#eee; scrollbar-arrow-color:#eee; scrollbar-darkshadow-color:#fff; scrollbar-face-color:#aaa; scrollbar-highlight-color:#aaa;
						scrollbar-shadow-color:#aaa; scrollbar-track-color:#e3e3e3;}
		</style>
		<script type="text/javascript">
  		window.parent.document.getElementById('volver').style.display = 'none';
		</script>
	</head>
	<body link="#336699" vlink="#336699" alink="#336699" topmargin="3" bottommargin="3" leftmargin="0" rightmargin="0">
		<table border="0" width="100%" id="table1" cellspacing="0" cellpadding="0" height="100%">
			<tr>
				<td align="center">
					<table border="0" cellspacing="0" cellpadding="0" id="table2">
						<tr>
							<td><p align="center"><a href="descripcion_de_puesto/index.php"><img border="0" src="images/descripcion.jpg" title="Descripción de Puestos"></a></td>
<!--							<td><p align="center"><a href="capacitacion/index.php"><img border="0" src="images/Capacitacion.jpg" width="150" height="151"></a></td>-->
<?
if ($_SESSION["esAdministrador"]) {
?>
							<td><a href="abm_descripcion_de_puesto/buscar_usuario.php"><img border="0" style="margin-right: 15px; margin-left: 15px" src="images/administracion.jpg" title="Administración de Usuarios" ></a></td>
							<td><a href="resultados/index.php"><img border="0" src="images/reportes.jpg" title="Reportes"></a></td>
<?
}
?>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>