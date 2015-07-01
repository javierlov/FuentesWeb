<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$idEstadistica = logUrlIn($_SERVER["REQUEST_URI"]);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Atención al Público | PROVINCIA ART ::..</title>
		<script language="JavaScript" src="/js/functions.js"></script>
		<style type="text/css">
			body, html {color:#626464; font-family:Arial; scrollbar-3dlight-color:#eee; scrollbar-arrow-color:#eee; scrollbar-darkshadow-color:#fff; scrollbar-face-color:#aaa; scrollbar-highlight-color:#aaa; scrollbar-shadow-color:#aaa; scrollbar-track-color:#e3e3e3;}
		</style>
	</head>

	<body onLoad="onLoadBody()">
		<input id="idEstadistica" type="hidden" value="<?= $idEstadistica?>" />
		<table cellspacing="0" cellpadding="0" width="600" bgcolor="#FFFFFF" align="center">
			<tr>
				<td height="50px"></td>
			</tr>
			<tr>
				<td>				
					<a href="/normativa-externa"><img border="0" src="/modules/atencion_publico/images/normativa.jpg" onmouseout="javascript:this.src='/modules/atencion_publico/images/normativa.jpg'" onmouseover="javascript:this.src='/modules/atencion_publico/images/normativa_a.jpg'"></a>
				</td>
			</tr>
			<tr>
				<td>				
					<a href="http://www.provinciart.com.ar/sic/"><img border="0" src="/modules/atencion_publico/images/sic.jpg" onmouseout="javascript:this.src='/modules/atencion_publico/images/sic.jpg'" onmouseover="javascript:this.src='/modules/atencion_publico/images/sic_a.jpg'"></a>
				</td>
			</tr>
			<tr>
				<td>				
					<a href="/campus-virtual"><img border="0" src="/modules/atencion_publico/images/capacitaciones.jpg" onmouseout="javascript:this.src='/modules/atencion_publico/images/capacitaciones.jpg'" onmouseover="javascript:this.src='/modules/atencion_publico/images/capacitaciones_a.jpg'"></a>
				</td>
			</tr>
			<tr>
				<td height="70px"></td>
			</tr>
			<tr>
				<td align="right"><img src="/modules/atencion_publico/images/logo_ART.jpg"></td>
			</tr>
		</table>
	</body>
</html>