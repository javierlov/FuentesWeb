<?
/*
mostrarItem(52, "NÓMINA DE TRABAJADORES");
mostrarItem(55, "CARGA MASIVA DE TRABAJADORES");
mostrarItem(58, "ESTADO DE SITUACIÓN DE PAGOS");
mostrarItem(60, "CARTILLA DE PRESTADORES");
mostrarItem(61, "DENUNCIAS DE SINIESTROS");
mostrarItem(64, "CONSULTA DE SINIESTROS");
mostrarItem(66, "ADMINISTRACIÓN DE USUARIOS");
mostrarItem(68, "PREVENCIÓN");
mostrarItem(69, "MI PERFIL");
mostrarItem(70, "CERTIFICADOS DE COBERTURA");
*/
?>
<style>
	.aClientes {color:#333; text-decoration:none;}
</style>
<div class="TituloSeccion" style="display:block; width:730px;">Acceso Clientes</div>
<div class="ContenidoSeccion">
	<p>Estimado cliente:</p>
	<p>Desde aquí, usted podrá realizar diversas operaciones y obtener toda la información necesaria para la gestión de su cobertura de riesgos del trabajo, durante las 24 hs., los 365 días del año.</p>
	<p>Accediendo a través del menú, usted podrá:</p>
<?
if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 70)) {
?>
	<p>> Emitir todo tipo de <b><a class="aClientes" href="/certificados-cobertura">CERTIFICADOS DE COBERTURA</a></b> -comunes, con cláusula de no repetición, con o sin nómina, con nómina total/parcial y simple/completa- seleccionando las opciones deseadas.</p>
<?
}

if (!$servidorContingenciaActivo) {
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 58)) {
?>
	<p>> Consultar el <b><a class="aClientes" href="/estado-situacion-pagos">ESTADO DE SITUACIÓN DE PAGOS</a></b> (alícuota, pagos, saldo deudor o acreedor), en cumplimiento de la Resolución Nº 441/06 de la S.R.T. Este estado de situación le informa, su situación de pagos a una determinada fecha.</p>
<?
	}
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 60)) {
?>
	<p>> Consultar la <b><a class="aClientes" href="/cartilla-prestadores">CARTILLA DE PRESTADORES</a></b> activos de Provincia ART por tipo de prestación. El acceso a la consulta es por provincia y localidad.</p>
<?
	}
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 61)) {
?>
	<p>> Informar vía web las <b><a class="aClientes" href="/denuncia-siniestros">DENUNCIAS DE SINIESTROS</a></b> (accidentes de trabajo y enfermedades profesionales). Por exigencias regulatorias, de todas formas usted deberá enviar el formulario en original a Provincia ART. Para ello, simplemente imprima el formulario web cuando termina de completarlo, fírmelo en original y envíelo.</p>
<?
	}
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 64)) {
?>
	<p>> Realizar <b><a class="aClientes" href="/consulta-siniestros">CONSULTA DE SINIESTROS</a></b> para conocer el estado de sus trabajadores siniestrados, filtrando por nombre, C.U.I.L. o rango de fechas (de accidente o de último control). La consulta le mostrará los datos relativos a la fecha de ocurrencia, tipo de accidente, tipo de tratamiento, fecha de recaída (si la hubo), últimas consultas registradas (si corresponde) y si está o no dado de alta.</p>
<?
	}
}
?>
</div>