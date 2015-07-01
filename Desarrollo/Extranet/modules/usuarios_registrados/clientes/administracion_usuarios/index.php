<?
validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 66));

if (!isset($_SESSION["BUSQUEDA_ADMINISTRACION_USUARIOS"]))
	$_SESSION["BUSQUEDA_ADMINISTRACION_USUARIOS"] = array("buscar" => "N",
																												"email" => "",
																												"nombre" => "",
																												"ob" => "2",
																												"pagina" => 1,
																												"sb" => false);
?>
<link rel="stylesheet" href="/styles/style.css" type="text/css" />
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios_registrados/clientes/administracion_usuarios/index_busqueda.php" id="formBuscarUsuario" method="post" name="formBuscarUsuario" target="iframeProcesando">
	<div class="TituloSeccion" style="display:block; width:730px;">Administración de Usuarios</div>
	<div class="ContenidoSeccion" style="margin-top:20px;">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td>Gestione las altas, bajas y modificaciones de los usuarios que tendrán acceso a los datos y las funciones de su empresa. Podrá asignarles acceso a los distintos servicios de Provincia ART, y a uno o más establecimientos de su empresa.</td>
			</tr>
			<tr>
				<td height="20"></td>
			</tr>
			<tr>
				<td class="ContenidoSeccion" valign="top">
					<table cellpadding="0" cellspacing="5">
						<tr>
							<td align="right">Nombre</td>
							<td><input autofocus id="nombre" maxlength="80" name="nombre" style="width:240px;" type="text" value=""></td>
							<td></td>
							<td></td>
						</tr>
						<tr>
							<td align="right">e-Mail</td>
							<td><input id="email" maxlength="255" name="email" style="width:240px;" type="text" value=""></td>
							<td>&nbsp;</td>
							<td><input class="btnBuscar" style="vertical-align:-3px;" type="submit" value="" /></td>
						</tr>
					</table>
				</td>
			</tr>	
			<tr>
				<td class="ContenidoSeccion">&nbsp;</td>
			</tr>	
			<tr>
				<td class="ContenidoSeccion">Utilice el Nombre o e-Mail para buscar en el listado de Usuarios de su empresa. Si no especifica ningún filtro, la búsqueda traerá la lista de usuarios completa.</td>
			</tr>	
			<tr>
				<td class="ContenidoSeccion">&nbsp;</td>
			</tr>	
			<tr>
 				<td class="ContenidoSeccion"><img border="0" src="/modules/usuarios_registrados/images/alta_de_usuario.jpg" style="cursor:pointer;" onClick="window.location.href='/alta-usuario-2'" /></td>
			</tr>	
		</table>
	</div>
	<div align="center" id="divContentGrid" name="divContentGrid" style="margin-top:8px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
</form>
<script type="text/javascript">
<?
if ($_SESSION["BUSQUEDA_ADMINISTRACION_USUARIOS"]["buscar"] == "S") {
?>
	document.getElementById('formBuscarUsuario').submit();
<?
}
?>
</script>