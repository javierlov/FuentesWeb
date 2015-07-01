<?
validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 61));
?>
<div class="TituloSeccion" style="display:block; width:730px;">Alta de Nuevas Denuncias</div>
<div class="ContenidoSeccion" style="margin-top:20px;">
	<p>Estimado cliente:</p>
	<p>Para mejorar la gestión, calidad de los datos y plazos en la recuperación del accidentado, es necesario que en las próximas oportunidades realice la <b>denuncia de accidentes en forma telefónica a través del 0800-333-1333.</b></p>
	<p>Para ello deberá contar únicamente con los datos del trabajador y el accidente ocurrido.</p>
</div>
<p style="margin-top:30px;">
	<img border="0" src="/modules/usuarios_registrados/images/siguiente.jpg" style="cursor:pointer;" onClick="window.location.href = '/denuncia-siniestros/denuncia'" />
	<input class="btnVolver" type="button" value="" onClick="history.back(-1);" />
</p>