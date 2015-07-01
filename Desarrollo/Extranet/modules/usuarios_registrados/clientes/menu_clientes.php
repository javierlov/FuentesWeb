<?
/*
mostrarItem(52, "N�MINA DE TRABAJADORES");
mostrarItem(55, "CARGA MASIVA DE TRABAJADORES");
mostrarItem(58, "ESTADO DE SITUACI�N DE PAGOS");
mostrarItem(60, "CARTILLA DE PRESTADORES");
mostrarItem(61, "DENUNCIAS DE SINIESTROS");
mostrarItem(64, "CONSULTA DE SINIESTROS");
mostrarItem(66, "ADMINISTRACI�N DE USUARIOS");
mostrarItem(68, "PREVENCI�N");
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
	<p>Desde aqu�, usted podr� realizar diversas operaciones y obtener toda la informaci�n necesaria para la gesti�n de su cobertura de riesgos del trabajo, durante las 24 hs., los 365 d�as del a�o.</p>
	<p>Accediendo a trav�s del men�, usted podr�:</p>
<?
if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 70)) {
?>
	<p>> Emitir todo tipo de <b><a class="aClientes" href="/certificados-cobertura">CERTIFICADOS DE COBERTURA</a></b> -comunes, con cl�usula de no repetici�n, con o sin n�mina, con n�mina total/parcial y simple/completa- seleccionando las opciones deseadas.</p>
<?
}

if (!$servidorContingenciaActivo) {
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 58)) {
?>
	<p>> Consultar el <b><a class="aClientes" href="/estado-situacion-pagos">ESTADO DE SITUACI�N DE PAGOS</a></b> (al�cuota, pagos, saldo deudor o acreedor), en cumplimiento de la Resoluci�n N� 441/06 de la S.R.T. Este estado de situaci�n le informa, su situaci�n de pagos a una determinada fecha.</p>
<?
	}
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 60)) {
?>
	<p>> Consultar la <b><a class="aClientes" href="/cartilla-prestadores">CARTILLA DE PRESTADORES</a></b> activos de Provincia ART por tipo de prestaci�n. El acceso a la consulta es por provincia y localidad.</p>
<?
	}
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 61)) {
?>
	<p>> Informar v�a web las <b><a class="aClientes" href="/denuncia-siniestros">DENUNCIAS DE SINIESTROS</a></b> (accidentes de trabajo y enfermedades profesionales). Por exigencias regulatorias, de todas formas usted deber� enviar el formulario en original a Provincia ART. Para ello, simplemente imprima el formulario web cuando termina de completarlo, f�rmelo en original y env�elo.</p>
<?
	}
	if (validarPermisoClienteXModulo($_SESSION["idUsuario"], 64)) {
?>
	<p>> Realizar <b><a class="aClientes" href="/consulta-siniestros">CONSULTA DE SINIESTROS</a></b> para conocer el estado de sus trabajadores siniestrados, filtrando por nombre, C.U.I.L. o rango de fechas (de accidente o de �ltimo control). La consulta le mostrar� los datos relativos a la fecha de ocurrencia, tipo de accidente, tipo de tratamiento, fecha de reca�da (si la hubo), �ltimas consultas registradas (si corresponde) y si est� o no dado de alta.</p>
<?
	}
}
?>
</div>