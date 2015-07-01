<iframe id="iframeArchivo" name="iframeArchivo" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:720px;">Acceso exclusivo organismos públicos</div>
<div class="SubtituloSeccion" style="margin-top:8px;">Declaración Jurada de personal</div>
<div class="ContenidoSeccion" id="divPaso1" style="margin-top:16px;">
	<div><b>Proceso de carga de datos:</b></div>
	Descargue en su computadora la plantilla en formato Microsoft Excel haciendo clic aquí <a target="_blank" href="/modules/usuarios_registrados/organismos_publicos/descargables/plantilla.xls"><img src="/modules/usuarios_registrados/images/icoEXCEL.gif"></a><br />
	En esta plantilla deberá cargar o pegar los datos obligatorios <span style="color:#f00;">(aquellos que se encuentran en rojo)</span>.<br /><br />
	- C.U.I.L. o de no existir D.N.I. y Sexo<br />
	- Cantidad de días Trabajados<br />
	- Remuneración Imponible<br />
	- Periodo
	<p>
		Una vez que complete los datos obligatorios, grabe el archivo en su computadora.<br />
		Haga clic en examinar, localice el archivo y haga clic en subir. El archivo empezará a cargar.
	</p>
	<p>
		Recuerde que luego podrá completar los datos no obligatorios. Tenga en cuenta que de no remitirlos en esta oportunidad, Provincia ART podrá solicitárselos para la realización de
		trámites administrativos; los mismos son: Apellido y Nombre, Fecha de Ingreso, Dirección (Calle, Número, Piso, Departamento, Código Postal, Localidad, Provincia), Tarea y Fecha de
		Nacimiento.
	</p>
	<form action="/modules/usuarios_registrados/organismos_publicos/procesar_archivo.php" enctype="multipart/form-data" id="formArchivo" method="post" name="formArchivo" target="iframeArchivo">
		<input id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" type="hidden" value="10485760" />
		<table>
			<tr>
				<td><input class="InputText" id="archivo" name="archivo" style="width:710px;" type="file" value="" /></td>
			</tr>
			<tr>
				<td>
					<span>El archivo debe ser menor a 10Mb.</span>
					<input class="btnCargar" style="margin-left:428px;" type="button" value="" onClick="subirArchivo()" />
				</td>
			</tr>
		</table>
	</form>
	<br />
	<p><input class="btnVolver" type="button" value="" onClick="window.location.href = '/bienvenida-organismos-publicos'" /></p>
	<p align="center"><img border="0" src="/modules/usuarios_registrados/images/paso1.gif" /></p>
</div>
<div class="ContenidoSeccion" id="divPaso2" style="display:none; margin-top:16px;">
	<br />
	<br />
	<br />
	<br />
	<p align="center"><img border="0" id="imgProcesando" name="imgProcesando" src="/modules/usuarios_registrados/images/working.gif" /></p>
	<p align="center"><i>Procesando datos, por favor espere.</i></p>
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br />
	<br >
	<p align="center"><img border="0" src="/modules/usuarios_registrados/images/paso2.gif" /></p>
</div>