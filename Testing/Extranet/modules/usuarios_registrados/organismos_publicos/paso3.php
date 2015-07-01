<?
set_time_limit(1800);

// Llamo al procedure que elimina el RNS anterior..
if ((isset($_REQUEST["amw"])) and ($_REQUEST["amw"] == "t")) {
	$curs = null;
	$params = array(":contrato" => $_SESSION["contrato"],
									":periodo" => $_REQUEST["pp"],
									":usuario" => $_SESSION["idUsuario"]);
	$sql = "BEGIN emi.notas.do_actualizarmotrarweb(:contrato, :periodo, :usuario); END;";
	DBExecSP($conn, $curs, $sql, $params, false);
}


if ((isset($_REQUEST["ep"])) and ($_REQUEST["ep"] == "t"))
	$hayErrores = true;
else {
	$params = array(":transaccion" => $_REQUEST["id"]);
	$sql =
		"SELECT 1
			 FROM tmp.teop_errororganismopublico
			WHERE te_transaccion = :transaccion";
	$hayErrores = ExisteSql($sql, $params);
}

if (!$hayErrores) {
?>
<div class="TituloSeccion" style="display:block; width:730px;">Acceso exclusivo organismos públicos</div>
<div class="SubtituloSeccion" style="margin-top:8px;">Declaración Jurada de personal</div>
<div class="ContenidoSeccion" style="margin-top:16px;">
	<table align="center">
		<tr>
			<td><img border="0" src="modules/usuarios_registrados/images/tilde.gif"></td>
			<td>El archivo fue recibido de manera exitosa.</td>
		</tr>
	</table>		
	<p>Provincia ART verificará los datos contenidos, y posteriormente si los mismos son correctos le remitirá un e-mail a <b><?= $_SESSION["email"]?></b> informándole que puede descargar el Resúmen No Suss desde <a class="linkSubrayado" href="/index.php?pageid=46&page=informes_procesados.php">aquí</a>.</p>
	<p>En caso de no ser válido dicho e-mail le solicitamos nos contacte a la brevedad a <a class="linkSubrayado" href="mailto:emision@provart.com.ar">emision@provart.com.ar</a> indicando el Nº de Contrato o de CUIT y los datos a modificar, a fin de poder recibir el aviso de descarga del RNS.</p>
	<p>De surgir algún inconveniente con los datos remitidos nos comunicaremos en la próximas 72 hs.</p>
	<p><hr style="margin-bottom:24px;" /></p>
	<p><input class="btnVolver" type="button" value="" onClick="window.location.href = '/index.php?pageid=46&page=paso1.php'" /></p>
	<p align="center"><img border="0" src="modules/usuarios_registrados/images/paso4.gif"></p>
</div>
<?
}
else {
?>
<div class="TituloSeccion" style="display:block; width:720px;">Acceso exclusivo organismos públicos</div>
<div class="SubtituloSeccion" style="margin-top:8px;">Declaración Jurada de personal</div>
<div class="ContenidoSeccion" style="margin-top:64px;">
	<table align="center">
		<tr>
			<td><img border="0" src="modules/usuarios_registrados/images/cruz.gif"></td>
<?
	if ((isset($_REQUEST["ep"])) and ($_REQUEST["ep"] == "t")) {
?>
			<td style="color:#000; font-weight:bold;">Ocurrió un error en el procesamiento de los datos, por favor revise que la nómina no tenga registros con C.U.I.L. vacía o C.U.I.L. duplicada.</td>
<?
	}
	else {
?>
			<td>Hubo errores en la carga de los datos.</td>
			<td><a href="/modules/usuarios_registrados/organismos_publicos/exportar_errores.php?id=<?= $_REQUEST["id"]?>" target="_blank"><img border="0" src="modules/usuarios_registrados/images/exportar_errores.gif" style="margin-left:16px;"></a></td>
<?
	}
?>
		</tr>
	</table>
	<p>Para consultar cuál fue el problema y ver el detalle con la solución, puede consultar el <a class="linkSubrayado" href="/modules/usuarios_registrados/organismos_publicos/descargables/glosario.pdf" target="_blank">Glosario</a>.</p>
	<p><i>La operación no se pudo completar. comuníquese con el Sector de Emisión de Provincia ART al (011) 4819-2842, en el horario de 10 a 17 hs. o por correo electrónico a <a class="linkSubrayado" href="mailto:emision@provart.com.ar">emision@provart.com.ar</a>.</i></p><br><br><br><br><br><br><br><br>
	<p align="center"><img border="0" src="modules/usuarios_registrados/images/paso4.gif"></p>
	<p><input class="btnVolver" type="button" value="" onClick="window.location.href = '/index.php?pageid=46&page=paso1.php'" /></p>
</div>
<?
}
?>