<?
@session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


if (!isset($_SESSION["idUsuario"]))
	$_SESSION["idUsuario"] = -1;

if (!hasPermiso(4, $_SESSION["idUsuario"])) {
	@header("Location: ".LOCAL_PATH_PAGO_TRANSFERENCIA."login.php");
	validarParametro(false);
	exit;
}
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>..:: Pago Transferencia ::..</title>

		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />

		<script language="JavaScript" src="/js/validations.js"></script>
		<script language="JavaScript" src="js/pago_transferencia.js"></script>
		<script type="text/javascript">
			function inicial() {
<?
if (isset($_REQUEST["flpld"])) {
	unset($_REQUEST["flpld"]);
?>
	ocultar('capa1');
	ocultar('capa2');
	mostrar('capa3');
<?
}
else {
?>
	if (document.getElementById('mas').value == "76711059") {
		mostrar('capa1');
		mostrar('capa2');
		mostrar('capa3');
	}
	else {
		ocultar('capa1');
		ocultar('capa2');
		ocultar('capa3');
	}
<?
}
?>
			}

			function mostrar(nombreCapa) {
				document.getElementById(nombreCapa).style.display = "block";
			}

			function ocultar(nombreCapa) {
				document.getElementById(nombreCapa).style.display = "none";
			}
		</script>
	</head>
	<body alink="#336699" leftmargin="2" link="#336699" rightmargin="2" vlink="#336699" onLoad="inicial()">
		<iframe id="iframeGetFile" name="iframeGetFile" src="" style="display:none;"></iframe>
		<div align="center">
			<table bgcolor="#FFFFFF" width="710" id="table13">
				<tr>
					<td>
						<div align="center">
							<table cellspacing="0" cellpadding="0" width="710" id="table14">
								<tr>
									<td width="752" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#C0C0C0" bgcolor="#807F84">
										<span style="font-weight: 700"><font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">TRANSFERENCIAS</font></span>
									</td>
								</tr>
								<tr>
									<td style="padding-left: 4px; padding-right: 4px" height="5"></td>
								</tr>
							</table>
							<table cellspacing="0" cellpadding="0" width="710" id="table15">
								<tr>
									<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#0f539c" bordercolor="#808080">
										<p style="margin-left: 6px; margin-top:0; margin-bottom:0">
											<b>
												<font face="Trebuchet MS" style="font-size: 8pt">
													<font color="#FFFFFF">1.</font>
												</font>
												<font face="Trebuchet MS" style="font-size: 8pt" color="#336699">
													<a target="_self" href="javascript:mostrar('capa1')" onclick="mostrar('capa1');ocultar('capa2');ocultar('capa3');" ondblclick="ocultar('capa1')" id="mas" onChange="inicial()">
														<span style="text-decoration: none"><font color="#fff">[+]</font></span>
													</a>
												</font>
												<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">PENDIENTES</font>
											</b>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div id='capa1'>
							<table id="table16">
								<tr>
									<td width="700" height="5">
										<table border="0" width="710" cellspacing="0" cellpadding="0" id="table17">
											<tr>
												<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#807F84" bgcolor="#C0C0C0">
													<font face="Trebuchet MS" style="font-size: 8pt; font-weight:700" color="#FFFFFF">Fecha</font>
												</td>
												<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#807F84" bgcolor="#C0C0C0">
													<font face="Trebuchet MS" style="font-size: 8pt; font-weight:700" color="#FFFFFF">Archivo</font>
												</td>
												<td align="center" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bordercolor="#807F84" bgcolor="#C0C0C0">
													<font face="Trebuchet MS" style="font-size: 8pt; font-weight:700" color="#FFFFFF">Nº de Transferencia</font>
												</td>
												<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" bgcolor="#FFFFFF">&nbsp;</td>
											</tr>
<?
$sql =
	"SELECT ab_fechaalta, ab_id, ab_nombre, ab_path
		 FROM web.wab_archivobapro
		WHERE ab_tipo = 'P'
			AND ab_fechadescargado IS NULL
 ORDER BY ab_fechaalta";
$stmt = DBExecSql($conn, $sql);
while ($row = DBGetQuery($stmt)) {
?>
	<tr id="file<?= $row["AB_ID"]?>">
		<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; border-bottom-style:dotted; border-bottom-width:1px" width="105">
			<font face="Trebuchet MS" style="font-size: 8pt"><?= $row["AB_FECHAALTA"]?></font>
		</td>
		<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; border-bottom-style:dotted; border-bottom-width:1px" width="420">
			<font face="Trebuchet MS" style="font-size: 8pt"><?= $row["AB_NOMBRE"]?></font>
		</td>
		<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px; border-bottom-style:dotted; border-bottom-width:1px" width="115">
			<font face="Trebuchet MS" style="font-size: 8pt"><?= $row["AB_ID"]?></font>
		</td>
		<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="39">
			<font face="Trebuchet MS"><a href="bajar_archivo.php?id=<?= $row["AB_ID"]?>&u=t&obj=file<?= $row["AB_ID"]?>" target="iframeGetFile"><img border="0" src="images/download.jpg" width="22" height="22"></a></font>
		</td>
	</tr>
<?
}
?>
										</table>
									</td>
								</tr>
							</table>
						</div>
						<div align="center">
							<table cellspacing="0" cellpadding="0" width="710" id="table18">
								<tr>
									<td width="700" style="padding-left: 4px; padding-right: 4px" height="5"></td>
								</tr>
								<tr>
									<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#0f539c" bordercolor="#808080">
										<p style="margin-left: 6px; margin-top:0; margin-bottom:0">
											<b>
												<font face="Trebuchet MS" style="font-size: 8pt">
													<font color="#FFFFFF">2.</font>
												</font>
												<font face="Trebuchet MS" style="font-size: 8pt" color="#336699">
													<a target="_self" href="javascript:mostrar('capa2')" onclick="mostrar('capa2');ocultar('capa1');ocultar('capa3')" id="mas" ondblclick="ocultar('capa2')" onChange="inicial()">
														<span style="text-decoration: none"><font color="#fff">[+]</font></span>
													</a>
												</font>
												<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">HISTÓRICOS</font>
											</b>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div id='capa2'>
							<table>
								<tr>
									<td width="712" height="5"></td>
								</tr>
								<tr>
									<td width="712">
										<iframe frameborder="no" height="0" id="iframeHistorico" name="iframeHistorico" scrolling="no" src="historico.php" width="712" onLoad="ajustarTamanoIframe(this)"></iframe>
									</td>
								</tr>
							</table>
						</div>
						<div align="center">
							<table cellspacing="0" cellpadding="0" width="710" id="table21">
								<tr>
									<td width="700" style="padding-left: 4px; padding-right: 4px" height="5"></td>
								</tr>
								<tr>
									<td width="752" style="padding-left: 4px; padding-right: 4px" height="10"></td>
								</tr>
								<tr>
									<td width="752" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bordercolor="#C0C0C0" bgcolor="#807F84">
										<span style="font-weight: 700">
											<font face="Trebuchet MS" style="font-size: 9pt" color="#FFFFFF">RESPUESTAS</font>
										</span>
									</td>
								</tr>
								<tr>
									<td style="padding-left: 4px; padding-right: 4px" height="5"></td>
								</tr>
								<tr>
									<td width="700" style="border-style: solid; border-width: 1px; padding-left: 4px; padding-right: 4px" bgcolor="#0f539c" bordercolor="#808080">
										<p style="margin-left: 6px; margin-top:0; margin-bottom:0">
											<b>
												<font face="Trebuchet MS" style="font-size: 8pt">
													<font color="#FFFFFF">3.</font>
												</font>
												<font face="Trebuchet MS" style="font-size: 8pt" color="#336699">
													<a target="_self" href="javascript:mostrar('capa3')" onclick="mostrar('capa3');ocultar('capa1');ocultar('capa2')" ondblclick="ocultar('capa3')" id="mas" onChange="inicial()">
														<span style="text-decoration: none"><font color="#fff">[+]</font></span>
													</a>
												</font>
												<font face="Trebuchet MS" style="font-size: 8pt" color="#FFFFFF">SUBIR ARCHIVOS</font>
											</b>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<form action="subir_archivo.php" enctype="multipart/form-data" id="formSubirArchivo" method="post" name="formSubirArchivo">
							<div id='capa3'>
								<table id="table22">	
									<tr>
										<td width="700">
											<table border="0" width="100%" cellspacing="0" cellpadding="0" id="table23">
												<tr colspan="3">
													<td width="700" height="5"></td>
												</tr>
												<tr>
													<td>
														<table border="0" width="710" cellspacing="0" cellpadding="0" id="table24">
															<tr>
																<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
																	<input id="archivo" name="archivo" style="float:left;" type="file" />
																</td>
																<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
																	<input class="btnCargar" id="btnCargar" name="btnCargar" type="submit" value="" style="float:left;" />
																</td>
																<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">&nbsp;</td>
															</tr>
															<tr>
																<td align="center" style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" colspan="4" height="10"></td>
															</tr>
															<tr>
																<td colspan="4">
																	<iframe frameborder="no" height="0" id="iframeRendiciones" name="iframeRendiciones" scrolling="no" src="rendiciones.php" width="712" onLoad="ajustarTamanoIframe(this)"></iframe>
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</div>
						</form>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>