<?
SetDateFormatOracle("DD/MM/YYYY");

if ((isset($_REQUEST["noti"])) and ($_REQUEST["noti"] == "s")) {
	try {
		$params = array(":idusuario" => GetUserID());
		$sql =
			"SELECT 1
				 FROM rrhh.rno_notificaciones
				WHERE no_idusuario = :idusuario";

		if (!ExisteSql($sql, $params)) {		// Alta..
			$params = array(":idusuario" => GetUserID(), ":pcmanualpla" => GetPCName());
			$sql =
				"INSERT INTO rrhh.rno_notificaciones (no_idusuario, no_manualpla, no_pcmanualpla)
																			VALUES (:idusuario, SYSDATE, :pcmanualpla)";
			DBExecSql($conn, $sql, $params);
		}
		else {		// Modificación..
			$params = array(":idusuario" => GetUserID(), ":pcmanualpla" => GetPCName());
			$sql =
				"UPDATE rrhh.rno_notificaciones
						SET no_manualpla = SYSDATE,
								no_pcmanualpla = :pcmanualpla
					WHERE no_idusuario = :idusuario";
			DBExecSql($conn, $sql, $params);
		}
	}
	catch (Exception $e) {
		DBRollback($conn);
		echo "<script>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
		exit;
	}
}


$params = array(":idusuario" => GetUserID());
$sql =
	"SELECT no_manualpla
		 FROM rrhh.rno_notificaciones
		WHERE no_idusuario = :idusuario";
$fechaAceptacion = ValorSql($sql, "", $params);
?>
<script>
	function descargar() {
		OpenWindow('/functions/get_file.php?fl=RjovU3RvcmFnZV9JbnRyYW5ldC9ub3JtYXNfeV9wcm9jZWRpbWllbnRvcy9ncmFsL21hbnVhbGVzL2xhdmFkb19kZV9hY3Rpdm9zL21hbnVhbF9sYXZhZG9fZGVfYWN0aXZvcy5wZGY=', 'winIntranet', 800, 600, '', '');
	}

	function guardar() {
		if (!document.getElementById('notificado').checked) {
			alert('Antes de guardar debe tildar el cuadro "ME NOTIFICO".');
			return;
		}

		window.location.href = '<?= $_SERVER["REQUEST_URI"]?>&noti=s';
	}

	function ocultarMensaje() {
		document.getElementById('tableMensaje').style.display = 'none';
	}
</script>
<div style="margin-right:8px;">
	<div align="right">Ciudad de Buenos Aires, Enero 2014</div>
	<div style="margin-top:12px;">Tengo el agrado de dirigirme a Ud. a fin de notificarle que en Diciembre de 2013, el Directorio de Provincia ART aprobó la revisión 03 del Manual de Control y Prevención del Lavado de Activos, Prevención del Financiamiento del Terrorismo y Reporte de Operaciones Sospechosas (disponible para consultas en la Intranet: Útiles / Normativa interna / Corporativa / Manuales).</div>
	<div style="margin-top:12px;">Dicho Manual es de conocimiento y cumplimiento obligatorio para todos los integrantes de la Compañía. Se requiere estricta observancia y cumplimiento de la totalidad de las disposiciones y procedimientos contenidos en el Manual, como así también de la normativa vigente reseñada en el mismo.</div>
	<div style="margin-top:12px;">Finalmente, le requerimos el máximo compromiso con la prevención del lavado de activos y el financiamiento de actividades terroristas, único mecanismo para dar cabal cumplimiento a las políticas en materia de prevención instituidas por Provincia ART.</div>
	<div style="margin-top:12px;">Saludos cordiales.</div>
	<div align="right" style="font-size:10px; font-weight:bold;">
		<img border="0" src="/modules/normas_y_manuales/corporativa/manuales/firma.jpg" />
		<div>María Fernanda VELAZQUEZ</div>
		<div>Directora y Oficial de Cumplimiento de Prevención </div>
		<div>del Lavado de Activos y Financiamiento de Actividades Terroristas</div>
	</div>
	<div style="background-color:#6FB43F; <?= ($fechaAceptacion != "")?"height:36px;":""?> margin-top:8px; padding:4px;">
		<div><span style="margin-left:5px; color:#FFFFFF">Nombre y apellido: </span> <b><?= strtoupper(GetUserName())?></b></div>
		<div style="margin-left:37px; margin-top:4px;">
			<span style="color:#FFFFFF">Me notifico</span>
			<input <?= ($fechaAceptacion == "")?"":"checked disabled"?> id="notificado" name="notificado" style="margin-left:2px; vertical-align:-3px;" type="checkbox" />
<?
if ($fechaAceptacion != "") {
?>
	<span style="margin-left:8px;">(<?= $fechaAceptacion?>)</span>
	<div style="position:relative; left:360px; top:-24px; width:80px;">
		<input class="BotonBlanco" type="button" value="VER MANUAL DE PREVENCIÓN DE LAVADO DE ACTIVOS" onClick="descargar()" />
	</div>
<?
}
?>
		</div>
		<div style="margin-left:94px; margin-top:4px;"">
<?
if ($fechaAceptacion == "") {
?>
	<input class="BotonBlanco" type="button" value="GUARDAR" onClick="guardar()" />
<?
}
?>
		</div>
	</div>
</div>
<script>
	setTimeout('ocultarMensaje()', 100);
</script>