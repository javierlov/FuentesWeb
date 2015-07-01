<?
SetDateFormatOracle("DD/MM/YYYY");

$sql = "SELECT TO_CHAR(SYSDATE, 'YYYY') FROM DUAL";
$ano = ValorSql($sql);

$params = array(":anio" => $ano, ":usuario" => GetWindowsLoginName(true));
$sql =
	"SELECT 1
		 FROM web.weu_encuesta_usuario
		WHERE eu_anio = :anio
			AND eu_fechabaja IS NULL
			AND eu_usuario = :usuario";
$usuarioHabilitado = ExisteSql($sql, $params);
?>
<script>
	function calcularPromedio() {
		var cantElementos = 0;
		var frm = document.getElementById("formEncuesta");
		var totalCalificaciones = 0;

		for (i=0; i<frm.elements.length; i++)
			if ((frm.elements[i].name.substring(0, 13) == 'calificacion_') && (frm.elements[i].value != '') && (!isNaN(frm.elements[i].value))) {
				cantElementos++;
				totalCalificaciones+= Number(frm.elements[i].value);
			}

		document.getElementById('promedioTotal').value = (totalCalificaciones / cantElementos).toFixed(2);
		if (isNaN(document.getElementById('promedioTotal').value))
			document.getElementById('promedioTotal').value = '';
	}

	function mostrarEncuesta(id) {
		iframeEncuesta.location.href = '/modules/encuestas/satisfaccion_cliente_interno_2/mostrar_encuesta.php?id=' + id;
	}

	function volver() {
		if (confirm('Si vuelve a la p�gina anterior no se guardaran los cambios realizados. �Desea continuar?'))
			window.location.href = '/encuesta-satisfaccion-cliente';
	}
</script>
<iframe id="iframeEncuesta" name="iframeEncuesta" src="" style="display:none;"></iframe>

<h1 style="color:#07367e;">PROGRAMA DE INCENTIVOS <?= ($ano - 1)?><br />ENCUESTA DE SATISFACCI�N CLIENTE INTERNO</h1>
<?
if (!$usuarioHabilitado) {
?>
	<h1 style="color:#f00; margin-top:80px;">ACCESO DENEGADO</h1>
	<h3>Usted no tiene encuestas para completar.</h3>
<?
	exit;
}
?>


<!-- Paso 1.. -->
<div id="divPaso1">
	<div style="border:2px #000 solid; margin-bottom:16px; margin-top:8px; padding:4px;">
		Se deber� completar una encuesta por cada sector a evaluar. Aparecer�n disponibles los sectores con los cuales se trabaj� en el per�odo ene-dic <?= ($ano - 1)?>.<br />
		Cualquier consulta, contactarse con Calidad.
	</div>
<?
$params = array(":usuario" => GetWindowsLoginName(true));
$sql =
	"SELECT DECODE(eu_estado, 'T', 'Terminado', 'Pendiente') estado, eu_fecha_vto fechavencimiento,
					CASE
						WHEN SYSDATE <= eu_fecha_vto THEN 'T'
						ELSE 'F'
					END habilitado, eu_id ID, se_descripcion sector
		 FROM web.weu_encuesta_usuario, computos.cse_sector
		WHERE eu_idsector = se_id
			AND eu_fechabaja IS NULL
			AND eu_usuario = :usuario
 ORDER BY se_descripcion";
$stmt = DBExecSql($conn, $sql, $params);
?>
	<table border="1" cellspacing="0" id="tableSectores" name="tableSectores" width="100%">
		<tr style="background-color:#c1c1c1; font-weight:bold;">
			<td align="center">Acceso</td>
			<td align="center">Sector</td>
			<td align="center">Estado</td>
			<td align="center">Fecha Vencimiento</td>
		</tr>
<?
while ($row = DBGetQuery($stmt)) {
?>
		<tr>
<?
	if ($row["HABILITADO"] == "T") {
?>
			<td align="center"><img border="0" src="/images/lupa.jpg" style="cursor:pointer;" onClick="mostrarEncuesta(<?= $row["ID"]?>)" /></td>
<?
	}
	else {
?>
			<td align="center"></td>
<?
	}
?>
			<td style="padding-left:4px;"><?= $row["SECTOR"]?></td>
			<td align="center"><?= $row["ESTADO"]?></td>
			<td align="center"><?= $row["FECHAVENCIMIENTO"]?></td>
		</tr>
<?
}
?>
	</table>
</div>


<!-- Paso 2.. -->
<div id="divPaso2" style="display:none;">
	<div style="border:2px #000 solid; margin-bottom:16px; margin-top:8px; padding:4px;">
		Clasificar del 1 al 10 (siendo 10 el mejor puntaje) cada aspecto. Si se eval�a con un puntaje menor a 7, se deber� completar un comentario que permita luego trabajar en mejoras.<br />
		Cualquier consulta, contactarse con Calidad.
	</div>
	<h2 id="hSectorEvaluado" style="margin-bottom:8px;">SECTOR EVALUADO: ---</h2>
	<div id="divEncuesta"></div>
</div>


<!-- Ventana de aviso de que se guardaron los datoss correctamente.. -->
<div id="divDatosOk" style="display:none;">
	<h2 style="color:#59c83e; margin-top:80px;">Los datos se guardaron correctamente.</h2>
</div>