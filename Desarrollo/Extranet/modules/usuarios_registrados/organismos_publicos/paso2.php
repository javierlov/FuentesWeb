<?
set_time_limit(120);

$amw = "f";
if (isset($_REQUEST["amw"]))
	$amw = $_REQUEST["amw"];

$params = array(":transaccion" => $_REQUEST["id"]);
$sql =
	"SELECT op_periodo
		 FROM emi.iop_organismopublico
		WHERE op_transaccion = :transaccion
			AND op_estado = -1";
$periodoProcesado = valorSql($sql, "", $params);

$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT MIN(cr_periodo)
		 FROM emi.icr_conceptoremunerativo
		WHERE cr_contrato = :contrato";
$periodoConceptos = valorSql($sql, "", $params);


if ((int)$periodoProcesado < (int)$periodoConceptos) {
	$mostrarConceptos = false;
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/organismos_publicos/paso3.php");
}
else {
	$esAlta = "F";
	$esModificacion = "F";
	$mostrarConceptos = true;
	$soloLectura = "";

	// El query de abajo es para ponerle valores por defecto a la variable $row..
	$params = array();
	$sql =
		"SELECT NULL cr_antiguedad, NULL cr_bonus, NULL cr_otros, NULL cr_premios, NULL cr_presentismo, NULL cr_refrigerio, NULL cr_viaticos
			 FROM DUAL";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	$params = array(":contrato" => $_SESSION["contrato"], ":periodo" => $periodoProcesado);
	$sql =
		"SELECT 1
			 FROM emi.icr_conceptoremunerativo
			WHERE cr_contrato = :contrato
				AND cr_periodo = :periodo";
	if (existeSql($sql, $params)) {
		$mostrarDefault = false;

		$params = array(":contrato" => $_SESSION["contrato"], ":periodo" => $periodoProcesado);
		$sql =
			"SELECT 1
				 FROM emi.icr_conceptoremunerativo
				WHERE cr_contrato = :contrato
					AND cr_periodo = :periodo
					AND cr_presentismo IS NULL
					AND cr_premios IS NULL
					AND cr_antiguedad IS NULL
					AND cr_viaticos IS NULL
					AND cr_refrigerio IS NULL
					AND cr_otros IS NULL
					AND cr_bonus IS NULL";
		if (existeSql($sql, $params))
			$esModificacion = "T";
		else {
			$soloLectura = "disabled";

			$params = array(":contrato" => $_SESSION["contrato"], ":periodo" => $periodoProcesado);
			$sql =
				"SELECT cr_antiguedad, cr_bonus, cr_otros, cr_premios, cr_presentismo, cr_refrigerio, cr_viaticos
					 FROM emi.icr_conceptoremunerativo
					WHERE cr_contrato = :contrato
						AND cr_periodo = :periodo";
			$stmt = DBExecSql($conn, $sql, $params);
			$row = DBGetQuery($stmt);
		}
	}
	else {
		$esAlta = "T";
		$mostrarDefault = true;

		$params = array(":contrato" => $_SESSION["contrato"]);
		$sql =
			"SELECT cr_antiguedad, cr_bonus, cr_otros, cr_premios, cr_presentismo, cr_refrigerio, cr_viaticos
				 FROM emi.icr_conceptoremunerativo
				WHERE cr_contrato = :contrato
		 ORDER BY cr_periodo DESC";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);
	}
}

if ($mostrarConceptos) {
?>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<div class="TituloSeccion" style="display:block; width:720px;">Acceso exclusivo organismos públicos</div>
<div class="SubtituloSeccion" style="margin-top:8px;">Declaración Jurada de personal</div>
<div class="ContenidoSeccion" id="divPaso2" style="margin-top:16px;">
	<form action="/modules/usuarios_registrados/organismos_publicos/procesar_conceptos.php" id="formConceptos" method="post" name="formConceptos" target="iframeProcesando">
		<input id="amw" name="amw" type="hidden" value="<?= $amw?>" />
		<input id="esAlta" name="esAlta" type="hidden" value="<?= $esAlta?>" />
		<input id="esModificacion" name="esModificacion" type="hidden" value="<?= $esModificacion?>" />
		<input id="idTmp" name="idTmp" type="hidden" value="<?= $_REQUEST["id"]?>" />
		<input id="periodoProcesado" name="periodoProcesado" type="hidden" value="<?= $periodoProcesado?>" />
		<span class="SubtituloSeccion" style="margin-left:-8px;">Conceptos</span>
		<div style="border:1px solid; padding:8px; width:240px;">
			<p>
				<input <?= ($row["CR_PRESENTISMO"] == "T")?"checked":""?> id="presentismo" name="presentismo" <?= $soloLectura?> type="checkbox" />
				<label for="presentismo">Presentismo</label>
				<input <?= ($row["CR_REFRIGERIO"] == "T")?"checked":""?> id="refrigerio" name="refrigerio" style="margin-left:16px;" <?= $soloLectura?> type="checkbox" />
				<label for="refrigerio">Refrigerio</label>
			</p>
			<p>
				<input <?= ($row["CR_PREMIOS"] == "T")?"checked":""?> id="premios" name="premios" <?= $soloLectura?> type="checkbox" />
				<label for="premios">Premios</label>
				<input <?= ($row["CR_OTROS"] == "T")?"checked":""?> id="otrosConceptos" name="otrosConceptos" <?= $soloLectura?> style="margin-left:40px;" type="checkbox" />
				<label for="otrosConceptos">Otros Conceptos</label>
			</p>
			<p>
				<input <?= ($row["CR_ANTIGUEDAD"] == "T")?"checked":""?> id="antiguedad" name="antiguedad" <?= $soloLectura?> type="checkbox" />
				<label for="antiguedad">Antigüedad</label>
				<input <?= ($row["CR_BONUS"] == "T")?"checked":""?> id="bonus" name="bonus" <?= $soloLectura?> style="margin-left:22px;" type="checkbox" />
				<label for="bonus">Bonus</label>
			</p>
			<p>
				<input <?= ($row["CR_VIATICOS"] == "T")?"checked":""?> id="viaticos" name="viaticos" <?= $soloLectura?> type="checkbox" />
				<label for="viaticos">Viáticos</label>
			</p>
		</div>
	</form>
	<div style="border:1px solid; left:280px; padding:8px; position:relative; top:-77px; width:420px;">A continuación Ud. podrá determinar cuales son los conceptos de su liquidación de sueldos que deben considerarse como remunerativos para el cálculo del seguro de Ley de Riesgos del Trabajo.</div>
	<div style="left:280px; position:relative; top:-72px; width:464px;">Por favor, presione el botón <span style="color:#f00; cursor:hand; text-decoration:underline;" onClick="document.getElementById('formConceptos').submit();">Continuar</span> para finalizar la carga correctamente.</div>
	<img border="0" src="/modules/usuarios_registrados/images/continuar.jpg" style="cursor:pointer; left:640px; position:relative; top:-40px;" onClick="document.getElementById('formConceptos').submit();">
	<div style="margin-bottom:20px;">Si Usted desea modificar períodos ya presentados o anteriores a los listados, deberá comunicarse por mail a <a class="linkSubrayado" href="mailto:emision@provart.com.ar">emision@provart.com.ar</a> indicando en el asunto el número de C.U.I.T. de la empresa, y comentándonos en el cuerpo del mensaje la operación que desea efectuar.</div>
	<p align="center"><img border="0" src="/modules/usuarios_registrados/images/paso3.gif" /></p>
</div>
<?
}
else {
	// Actualizo el estado en la tabla de organismos públicos..
	$params = array(":transaccion" => $_REQUEST["id"]);
	$sql =
		"UPDATE emi.iop_organismopublico
				SET op_estado = 1
		  WHERE op_transaccion = :transaccion";
	DBExecSql($conn, $sql, $params);
}
?>