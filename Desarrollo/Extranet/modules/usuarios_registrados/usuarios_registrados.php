<?
if ((isset($_SESSION["isAbogado"])) and ($_SESSION["isAbogado"])) {
	echo "<script type='text/javascript'>window.location.href = '/SeleccionAplicacion'</script>";
	exit;
}
if ((isset($_SESSION["isAgenteComercial"])) and ($_SESSION["isAgenteComercial"])) {
	echo "<script type='text/javascript'>window.location.href = '/bienvenida'</script>";
	exit;
}
if ((isset($_SESSION["isCliente"])) and ($_SESSION["isCliente"])) {
	echo "<script type='text/javascript'>window.location.href = '/bienvenida-cliente'</script>";
	exit;
}
if ((isset($_SESSION["isOrganismoPublico"])) and ($_SESSION["isOrganismoPublico"])) {
	echo "<script type='text/javascript'>window.location.href = '/bienvenida-organismos-publicos'</script>";
	exit;
}
if ((isset($_SESSION["isPreventor"])) and ($_SESSION["isPreventor"])) {
	echo "<script type='text/javascript'>window.location.href = '/bienvenida-preventores'</script>";
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
										<a href="/acceso-exclusivo-clientes"><img border="0" src="/modules/usuarios_registrados/images/clientes.jpg" /></a>
									</td>
								</tr>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/acceso-exclusivo-agentes-comerciales"><img border="0" src="/modules/usuarios_registrados/images/agentes_comerciales.jpg" /></a>
									</td>
								</tr>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/acceso-exclusivo-estudios-juridicos"><img border="0" src="/modules/usuarios_registrados/images/estudios_juridicos.jpg" /></a>
									</td>
								</tr>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/acceso-exclusivo-organismos-publicos"><img border="0" src="/modules/usuarios_registrados/images/organismos_publicos.jpg" /></a>
									</td>
								</tr>
								<tr>
									<td style="border-bottom: 1px dotted #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="284">
										<a href="/acceso-exclusivo-preventores"><img border="0" src="/modules/usuarios_registrados/images/preventores.jpg" /></a>
									</td>
								</tr>
								<tr>
									<td height="80">
										<noscript>
											<span style="color:#f00; font-size:11px;">
												Usted tiene JavaScript desactivado.<br />
												Para navegar correctamente por el sitio web debe tener activado JavaScript.<br />
												Haga <a class="linkSubrayado" href="/javascript" target="_blank">clic aquí</a> para conocer mas.
											</span>
										</noscript>
<?
preg_match("/MSIE (.*?);/", $_SERVER['HTTP_USER_AGENT'], $matches);

if (count($matches) > 1) {
	//Then we're using IE
	$version = $matches[1];

	switch(true) {
		case ($version<=9):
			//IE 9 or under!
?>
			<span style="color:#f00; font-size:11px;">
				Usted está usando Internet Explorer <?= $version?>.<br />
				Para navegar correctamente por el sitio web debe tener instalado Internet Explorer 10 o superior.<br />
				Los navegadores recomendados son Chrome (descargar <a class="linkSubrayado" href="https://www.google.com/chrome/browser" target="_blank">aquí</a>) o
				Firefox (descargar <a class="linkSubrayado" href="https://www.mozilla.org/es-AR/firefox/new/" target="_blank">aquí</a>).
			</span>
<?
			break;
	}
}
?>
									</td>
								</tr>
							</table>
						</td>
						<td style="padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px" width="335">
							<div align="center">
								<table border="0" cellpadding="0" cellspacing="0" width="240" height="80">
									<tr>
										<td width="88" style="border-left:1px solid #807F83; border-top:1px solid #807F83; border-bottom:1px solid #807F83; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px">
											<p align="center">
											<a target="_blank" title="Relevamiento de Riesgos Laborales" href="/modules/formulario_establecimientos/login.php">
												<img border="0" src="/modules/usuarios_registrados/images/banner.jpg" />
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
												<a href="/examenes-medicos-periodicos"><img border="0" src="/modules/usuarios_registrados/images/Res_37.gif"></a>
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

<div id="banner1HomePage" style="height:110px; left:0px; position:absolute; top:320px; width:240px; z-index:0;">
	<object border="0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="110" name="obj1" width="240">
		<param name="movie" value="/images/banner1.swf" />
		<param name="quality" value="High" />
		<param name="wmode" value="transparent" />
		<embed height="110" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner1.swf" type="application/x-shockwave-flash" width="240">
	</object>
</div>
<div id="banner2HomePage" style="height:110px; left:246px; position:absolute; top:320px; width:240px; z-index:0;">
	<object border="0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0" height="110" name="obj2" width="240">
		<param name="movie" value="/images/banner2.swf" />
		<param name="quality" value="High" />
		<param name="wmode" value="transparent" />
		<embed height="110" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner2.swf" type="application/x-shockwave-flash" width="240">
	</object>
</div>
<div id="divBanner3HomePage" style="left:508px; position:absolute; top:320px;">
	<embed height="110" name="obj3" pluginspage="http://www.macromedia.com/go/getflashplayer" quality="High" src="/images/banner3.swf" type="application/x-shockwave-flash" width="240" />
</div>