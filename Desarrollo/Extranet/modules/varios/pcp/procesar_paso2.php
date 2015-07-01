<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


function getValorCheck($nombreCampo) {
	$result = NULL;
	if (isset($_POST[$nombreCampo]))
		$result = $_POST[$nombreCampo];

	return $result;
}

function isReadonly() {
	if (!isset($_SESSION["pcpId"]))
		return true;

	$params = array(":id" => $_SESSION["pcpId"]);
	$sql =
		"SELECT 1
			 FROM afi.avp_valida_pcp
			WHERE vp_fechaimpresion IS NOT NULL
				AND vp_id = :id";
	return (existeSql($sql, $params));
}

function validar() {
	global $campoError;

	if (!isset($_SESSION["pcpId"]))
		throw new Exception("La sesión ha expirado.");

	// Bloque 7..
	if ($_POST["breveDescripcionTareas"] == "") {
		$campoError = "breveDescripcionTareas";
		throw new Exception("Debe ingresar una Breve descripción de tareas (máximo 250 caracteres).");
	}

	$elementosSinChequear = false;
	$arrElementos = array("electrico", "incendio", "extintor", "insecticida", "bencina", "raticida", "desinfectantes", "detergentes", "sodaCaustica", "desengrasante",
															"hipocloritoDeSodio", "amoniaco", "acidoMuriatico", "proteccionBalcones", "interiorAltura", "exteriorAltura", "escaleraBaranda", "indumentaria",
															"proteccionPersonal");
	foreach ($arrElementos as $valor) {
		if (getValorCheck($valor) == NULL) {
			if (($valor == "extintor") and (getValorCheck("incendio") == "N") and ($_POST["extintorCual"] != ""))
				continue;

			$elementosSinChequear = true;
			break;
		}
	}

	if ($elementosSinChequear	) {
		$campoError = $valor;
		throw new Exception("Debe completar todos los campos.");
	}

	if (($_POST["incendio"] == "S") and (getValorCheck("extintor") == NULL) and ($_POST["extintorCual"] != "")) {
		$campoError = "incendioS";
		throw new Exception("Hay inconsistencias en las respuestas de Riesgo de Incendio No y selecciono un item en \"Indique cual\"");
	}

	if ((getValorCheck("insecticida") == "N") and ($_POST["insecticidaCual"] != "")) {
		$campoError = "insecticidaN";
		throw new Exception("Hay inconsistencias en las respuestas de Riesgo Químico Insecticidas No y completó \"¿Cuáles?\"");
	}

	if ((getValorCheck("raticida") == "N") and ($_POST["raticidaCual"] != "")) {
		$campoError = "raticidaN";
		throw new Exception("Hay inconsistencias en las respuestas de Riesgo Químico Raticidas No y completó \"¿Cuáles?\"");
	}

	if ((getValorCheck("interiorAltura") == "N") and ($_POST["interiorAlturaCual"] != "")) {
		$campoError = "interiorAlturaN";
		throw new Exception("Hay inconsistencias en las respuestas de Instalaciones Edilicias, Realizan tareas interiores No y completó \"¿Cuáles?\"");
	}

	if ((getValorCheck("exteriorAltura") == "N") and ($_POST["exteriorAlturaCual"] != "")) {
		$campoError = "exteriorAlturaN";
		throw new Exception("Hay inconsistencias en las respuestas de Instalaciones Edilicias, Realizan tareas exteriores No y completó \"¿ Cuáles ?\"");
	}

	if ((getValorCheck("indumentaria") == "N") and ($_POST["indumentariaCual"] != "")) {
		$campoError = "indumentariaN";
		throw new Exception("Hay inconsistencias en las respuestas de Ropa y elementos de trabajo, Entrega indumentaria de trabajo No y completó \"¿Cuáles?\"");
	}

	if ((getValorCheck("proteccionPersonal") == "N") and ($_POST["proteccionPersonalCual"] != "")) {
		$campoError = "proteccionPersonalN";
		throw new Exception("Hay inconsistencias en las respuestas de Ropa y elementos de trabajo, Entrega de Elementos de protección personal No y completó \"¿Cuáles?\"");
	}
	
	return true;
}


try {
	$campoError = "";

	if (!isReadonly()) {
		if (!validar())
			exit;

		$params = array(":id_valida_pcp" => $_SESSION["pcpId"]);
		$sql =
			"SELECT 1
				 FROM afi.avr_valida_riesgo_pcp
				WHERE vr_id_valida_pcp = :id_valida_pcp";
		if (!existeSql($sql, $params)) {		// Alta..
			$params = array(":acidomuriatico" => getValorCheck("acidoMuriatico"),
											":amoniaco" => getValorCheck("amoniaco"),
											":bencina" => getValorCheck("bencina"),
											":descripcion" => substr($_POST["breveDescripcionTareas"], 0, 250),
											":desengrasante" => getValorCheck("desengrasante"),
											":desinfectantes" => getValorCheck("desinfectantes"),
											":detergentes" => getValorCheck("detergentes"),
											":electrico" => getValorCheck("electrico"),
											":escalerabaranda" => getValorCheck("escaleraBaranda"),
											":exterioraltura" => getValorCheck("exteriorAltura"),
											":exterioraltura_cual" => substr($_POST["exteriorAlturaCual"], 0, 100),
											":extintor" => getValorCheck("extintor"),
											":extintor_cual" => substr($_POST["extintorCual"], 0, 100),
											":hipocloritodesodio" => getValorCheck("hipocloritoDeSodio"),
											":id_valida_pcp" => $_SESSION["pcpId"],
											":incendio" => getValorCheck("incendio"),
											":indumentaria" => getValorCheck("indumentaria"),
											":indumentaria_cual" => substr($_POST["indumentariaCual"], 0, 100),
											":insecticida" => getValorCheck("insecticida"),
											":insecticida_cual" => substr($_POST["insecticidaCual"], 0, 100),
											":interioraltura" => getValorCheck("interiorAltura"),
											":interioraltura_cual" => substr($_POST["interiorAlturaCual"], 0, 100),
											":otroriesgoquimico" => substr($_POST["otroRiesgoQuimico"], 0, 100),
											":proteccionbalcones" => getValorCheck("proteccionBalcones"),
											":proteccionpersonal" => getValorCheck("proteccionPersonal"),
											":proteccionpersonal_cual" => substr($_POST["proteccionPersonalCual"], 0, 100),
											":raticida" => getValorCheck("raticida"),
											":raticida_cual" => substr($_POST["raticidaCual"], 0, 100),
											":sodacaustica" => getValorCheck("sodaCaustica"),
											":usualta" => $_SESSION["usuario"]);
			$sql =
				"INSERT INTO afi.avr_valida_riesgo_pcp (vr_acidomuriatico, vr_amoniaco, vr_bencina, vr_descripcion, vr_desengrasante, vr_desinfectantes, vr_detergentes, vr_electrico,
																								vr_escalerabaranda, vr_exterioraltura, vr_exterioraltura_cual, vr_extintor, vr_extintor_cual, vr_fechaalta, vr_hipocloritodesodio,
																								vr_id_valida_pcp, vr_incendio, vr_indumentaria, vr_indumentaria_cual, vr_insecticida, vr_insecticida_cual, vr_interioraltura,
																								vr_interioraltura_cual, vr_otroriesgoquimico, vr_proteccionbalcones, vr_proteccionpersonal, vr_proteccionpersonal_cual, vr_raticida,
																								vr_raticida_cual, vr_sodacaustica, vr_usualta)
																				VALUES (:acidomuriatico, :amoniaco, :bencina, :descripcion, :desengrasante, :desinfectantes, :detergentes, :electrico,
																								:escalerabaranda, :exterioraltura, :exterioraltura_cual, :extintor, :extintor_cual, SYSDATE, :hipocloritodesodio,
																								:id_valida_pcp, :incendio, :indumentaria, :indumentaria_cual, :insecticida, :insecticida_cual, :interioraltura,
																								:interioraltura_cual, :otroriesgoquimico, :proteccionbalcones, :proteccionpersonal, :proteccionpersonal_cual, :raticida,
																								:raticida_cual, :sodacaustica, :usualta)";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
		else {		// Modificación..
			$params = array(":acidomuriatico" => getValorCheck("acidoMuriatico"),
											":amoniaco" => getValorCheck("amoniaco"),
											":bencina" => getValorCheck("bencina"),
											":descripcion" => substr($_POST["breveDescripcionTareas"], 0, 250),
											":desengrasante" => getValorCheck("desengrasante"),
											":desinfectantes" => getValorCheck("desinfectantes"),
											":detergentes" => getValorCheck("detergentes"),
											":electrico" => getValorCheck("electrico"),
											":escalerabaranda" => getValorCheck("escaleraBaranda"),
											":exterioraltura" => getValorCheck("exteriorAltura"),
											":exterioraltura_cual" => substr($_POST["exteriorAlturaCual"], 0, 100),
											":extintor" => getValorCheck("extintor"),
											":extintor_cual" => substr($_POST["extintorCual"], 0, 100),
											":hipocloritodesodio" => getValorCheck("hipocloritoDeSodio"),
											":id_valida_pcp" => $_SESSION["pcpId"],
											":incendio" => getValorCheck("incendio"),
											":indumentaria" => getValorCheck("indumentaria"),
											":indumentaria_cual" => substr($_POST["indumentariaCual"], 0, 100),
											":insecticida" => getValorCheck("insecticida"),
											":insecticida_cual" => substr($_POST["insecticidaCual"], 0, 100),
											":interioraltura" => getValorCheck("interiorAltura"),
											":interioraltura_cual" => substr($_POST["interiorAlturaCual"], 0, 100),
											":otroriesgoquimico" => substr($_POST["otroRiesgoQuimico"], 0, 100),
											":proteccionbalcones" => getValorCheck("proteccionBalcones"),
											":proteccionpersonal" => getValorCheck("proteccionPersonal"),
											":proteccionpersonal_cual" => substr($_POST["proteccionPersonalCual"], 0, 100),
											":raticida" => getValorCheck("raticida"),
											":raticida_cual" => substr($_POST["raticidaCual"], 0, 100),
											":sodacaustica" => getValorCheck("sodaCaustica"),
											":usumodif" => $_SESSION["usuario"]);
			$sql =
				"UPDATE afi.avr_valida_riesgo_pcp
						SET vr_acidomuriatico = :acidomuriatico,
								vr_amoniaco = :amoniaco,
								vr_bencina = :bencina,
								vr_descripcion = :descripcion,
								vr_desengrasante = :desengrasante,
								vr_desinfectantes = :desinfectantes,
								vr_detergentes = :detergentes,
								vr_electrico = :electrico,
								vr_escalerabaranda = :escalerabaranda,
								vr_exterioraltura = :exterioraltura,
								vr_exterioraltura_cual = :exterioraltura_cual,
								vr_extintor = :extintor,
								vr_extintor_cual = :extintor_cual,
								vr_fechamodif = SYSDATE,
								vr_hipocloritodesodio = :hipocloritodesodio,
								vr_incendio = :incendio,
								vr_indumentaria = :indumentaria,
								vr_indumentaria_cual = :indumentaria_cual,
								vr_insecticida = :insecticida,
								vr_insecticida_cual = :insecticida_cual,
								vr_interioraltura = :interioraltura,
								vr_interioraltura_cual = :interioraltura_cual,
								vr_otroriesgoquimico = :otroriesgoquimico,
								vr_proteccionbalcones = :proteccionbalcones,
								vr_proteccionpersonal = :proteccionpersonal,
								vr_proteccionpersonal_cual = :proteccionpersonal_cual,
								vr_raticida = :raticida,
								vr_raticida_cual = :raticida_cual,
								vr_sodacaustica = :sodacaustica,
								vr_usumodif = :usumodif
					WHERE vr_id_valida_pcp = :id_valida_pcp";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}


		DBCommit($conn);
	}

	$_SESSION["paso"] = 3;
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script type="text/javascript">
		with (window.parent.document) {
			alert(unescape('<?= rawurlencode($e->getMessage())?>'));
			getElementById('btnGuardar').style.display = 'inline';
			getElementById('imgProcesando').style.display = 'none';
			if (getElementById('<?= $campoError?>') != null) {
				getElementById('<?= $campoError?>').style.backgroundColor = '#f00';
				getElementById('<?= $campoError?>').style.color = '#fff';
				getElementById('<?= $campoError?>').focus();
			}
			setTimeout("window.parent.document.getElementById('<?= $campoError?>').style.backgroundColor = ''; window.parent.document.getElementById('<?= $campoError?>').style.color = '';", 2000);
		}
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('btnGuardar').style.display = 'inline';
		getElementById('imgProcesando').style.display = 'none';
	}
	window.parent.location.href = '/pcp-3';
</script>