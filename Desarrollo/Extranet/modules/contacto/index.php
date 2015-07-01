<script src="/modules/contacto/js/contacto.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:730px;">Contacto</div>
<div class="ContenidoSeccion" style="margin-top:8px;">
	<p style="margin-top: 0; margin-bottom: 0">Por favor, complete el siguiente formulario para que un representante pueda ayudarlo.</p>
	<div style="float:left; margin-top:6px; width:264px;">
		<img border="0" src="/modules/contacto/images/oficinas.bmp" />
		<br />
		<img border="0" src="/modules/contacto/images/datos.jpg" usemap="#mapMail" />
		<span id="spanMsgOk" style="color:#877f87; font-size:8pt; visibility:hidden;"><br /><br />Gracias por comunicarse, a la brevedad será contactado.</span>
		<div align="center" style="margin-top:176px;">
			<a href="http://www.srt.gov.ar/" target="_blank"><img border="0" src="/modules/contacto/images/srt.jpg"/></a>
		</div>
	</div>
	<div style="background-color:#ccc; background-image:url(/modules/contacto/images/pie_mensajes.jpg); background-position:bottom; background-repeat:no-repeat; float:left; margin-top:6px; min-height:240px;">
		<img border="0" src="/modules/contacto/images/mensajes.jpg" style="width:450px;" />

		<form action="/index.php?pageid=24" id="formContacto" method="post" name="formContacto" target="iframeProcesando">
			<input id="solapa" name="solapa" type="hidden" value="e" />

			<div style="margin-left:32px; margin-top:4px;">
				<label>Tipo de mensaje</label>
				<input checked id="tipo" name="tipo" style="margin-left:24px; vertical-align:-3px;" type="radio" value="consulta" />
				<label>CONSULTA</label>
				<input id="tipo" name="tipo" style="vertical-align:-3px;" type="radio" value="reclamo" />
				<label>RECLAMO</label>
			</div>

			<div style="margin-top:4px;">
				<span style="cursor:default; padding-left:33px;">
					<a href="javascript:cambiarSolapa('e')"><img border="0" id="aEmpresa" name="aEmpresa" src="/modules/contacto/images/empresas_a.bmp" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)"></a>
				</span>
				<span style="cursor:default; padding-left:5px;">
					<a href="javascript:cambiarSolapa('t')"><img border="0" id="aTrabajador" name="aTrabajador" src="/modules/contacto/images/trabajador.bmp" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)"></a>
				</span>
				<span style="cursor:default; padding-left:5px;">
					<a href="javascript:cambiarSolapa('p')"><img border="0" id="aPrestador" name="aPrestador" src="/modules/contacto/images/prestador_proveedor.bmp" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)"></a>
				</span>
				<span style="cursor:default; padding-left:5px;">
					<a href="javascript:cambiarSolapa('o')"><img border="0" id="aOtros" name="aOtros" src="/modules/contacto/images/otros.bmp" onMouseOut="mouseOut(this)" onMouseOver="mouseOver(this)"></a>
				</span>
			</div>

			<div id="divEmpresa">
				<div style="margin-left:32px; margin-top:2px;">
					<input autofocus id="eRazonSocial" maxlength="256" name="eRazonSocial" placeholder="Razón Social *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="eCuit" maxlength="13" name="eCuit" placeholder="C.U.I.T. *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="eNombreApellido" maxlength="256" name="eNombreApellido" placeholder="Nombre y Apellido *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="eCargo" maxlength="256" name="eCargo" placeholder="Cargo *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="eEmail" maxlength="256" name="eEmail" placeholder="e-Mail *" style="width:328px;" type="email" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="eTelefono" maxlength="256" name="eTelefono" placeholder="Teléfono *" style="width:328px;" type="tel" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="eDireccion" maxlength="256" name="eDireccion" placeholder="Dirección *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<select id="eMotivo" name="eMotivo" style="width:338px;">
						<option value="-1">- Seleccione el motivo -</option>
						<option value="Atención al cliente">Atención al cliente</option>
						<option value="Afiliaciones y contratos">Afiliaciones y contratos</option>
						<option value="Solicitud de cotización">Solicitud de cotización</option>
						<option value="Prevención">Prevención</option>
						<option value="Exámenes médicos">Exámenes médicos</option>
						<option value="Prestaciones en Especie">Prestaciones en especie</option>
						<option value="Prestaciones Dinerarias">Prestaciones dinerarias</option>
						<option value="Comisión Médica y O.H. y V.">Comisión médica y O.H. y V.</option>
						<option value="Otros">Otros</option>						
					</select>
				</div>
			</div>

			<div id="divTrabajador" style="display:none;">
				<div style="margin-left:32px; margin-top:2px;">
					<input id="tNombreApellido" maxlength="256" name="tNombreApellido" placeholder="Nombre y Apellido *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="tCuil" maxlength="13" name="tCuil" placeholder="C.U.I.L. o D.N.I. *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="tEmail" maxlength="256" name="tEmail" placeholder="e-Mail *" style="width:328px;" type="email" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="tTelefono" maxlength="256" name="tTelefono" placeholder="Teléfono *" style="width:328px;" type="tel" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="tDireccion" maxlength="256" name="tDireccion" placeholder="Dirección *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<select id="tMotivo" name="tMotivo" style="width:338px;">
						<option value="-1">- Seleccione el motivo -</option>
						<option value="Prevención">Prevención</option>
						<option value="Exámenes Médicos">Exámenes médicos</option>
						<option value="Prestaciones en Especie">Prestaciones en especie</option>
						<option value="Prestaciones Dinerarias">Prestaciones dinerarias</option>
						<option value="Comisión Médica y O.H. y V.">Comisión médica y O.H. y V.</option>
						<option value="Otros">Otros</option>
					</select>
				</div>
			</div>

			<div id="divPrestador" style="display:none;">
				<div style="margin-left:32px; margin-top:2px;">
					<input id="pRazonSocial" maxlength="256" name="pRazonSocial" placeholder="Razón Social *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="pCuit" maxlength="13" name="pCuit" placeholder="C.U.I.T. *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="pNombreApellido" maxlength="256" name="pNombreApellido" placeholder="Nombre y Apellido *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="pCargo" maxlength="256" name="pCargo" placeholder="Cargo *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="pEmail" maxlength="256" name="pEmail" placeholder="e-Mail *" style="width:328px;" type="email" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="pTelefono" maxlength="256" name="pTelefono" placeholder="Teléfono *" style="width:328px;" type="tel" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<select id="pMotivo" name="pMotivo" style="width:338px;">
						<option value="-1">- Seleccione el motivo -</option>
						<option value="Pagos">Pagos</option>
						<option value="Incorporación de prestador">Incorporación de prestador</option>
						<option value="Autorizaciones">Autorizaciones</option>
						<option value="Otros">Otros</option>
					</select>
				</div>
			</div>

			<div id="divOtros" style="display:none;">
				<div style="margin-left:32px; margin-top:2px;">
					<input id="oRazonSocial" maxlength="256" name="oRazonSocial" placeholder="Razón Social *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="oNombreApellido" maxlength="256" name="oNombreApellido" placeholder="Nombre y Apellido *" style="width:328px;" type="text" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="oEmail" maxlength="256" name="oEmail" placeholder="e-Mail *" style="width:328px;" type="email" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<input id="oTelefono" maxlength="256" name="oTelefono" placeholder="Teléfono *" style="width:328px;" type="tel" value="" />
				</div>
				<div style="margin-left:32px; margin-top:2px;">
					<select id="oMotivo" name="oMotivo" style="width:338px;">
						<option value="-1">- Seleccione el motivo -</option>
						<option value="Fundación Provincia ART">Fundación Provincia ART</option>
						<option value="Recursos Humanos">Recursos Humanos</option>
						<option value="Marketing y Publicidad">Marketing y Publicidad</option>
					</select>
				</div>
			</div>
			<p style="margin-left:32px; margin-top:2px;">
				<textarea id="mensaje" name="mensaje" placeholder="Mensaje *" style="height:30px; margin-right:8px; width:328px;"></textarea>
			</p>
			<p style="margin-left:32px;">
				<input id="captcha" maxlength="16" name="captcha" placeholder="Captcha *" style="width:80px;" type="text" value="" />
				<img border="0" id="imgCaptcha" src="/functions/captcha.php" style="margin-left:16px; vertical-align:-8px;" />
				<img border="0" src="/images/reload.png" style="cursor:pointer; margin-left:4px; vertical-align:-6px;" title="Recargar captcha" onClick="recargarCaptcha(document.getElementById('imgCaptcha'))" />
				<input class="btnEnviar" id="btnEnviar" style="margin-left:16px; vertical-align:-4px;" type="submit" value="" />
				<span id="spanPuntos" style="display:none;"><img border="0" src="/images/loading.gif" title="Enviando mensaje..." /></span>
				<input class="btnBorrar" name="btnBorrar" style="margin-left:8px; vertical-align:-4px;" type="reset" value="" />
			</p>
			<p style="margin-left:280px;">
				<p style="margin-top: 0; margin-bottom: 6px; margin-left: 32px;">&nbsp;&nbsp; * Datos obligatorios</p>
			</p>
		</form>
	</div>
</div>
<map name="mapMail">
	<area href="mailto:info@provart.com.ar" shape="rect" coords="13, 67, 112, 79">
</map>