<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");


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

	if ($_POST["nombre"] == "") {
		$campoError = "nombre";
		throw new Exception("Debe ingresar el Nombre y Apellido.");
	}

	if ($_POST["calle"] == "") {
		$campoError = "calle";
		throw new Exception("Debe ingresar la Calle.");
	}

	if ($_POST["codigoPostal"] == "") {
		$campoError = "codigoPostal";
		throw new Exception("Debe ingresar el Código Postal.");
	}

	if ($_POST["provincia"] == -1) {
		$campoError = "provincia";
		throw new Exception("Debe seleccionar la Provincia.");
	}

	if ($_POST["localidad"] == "") {
		$campoError = "localidad";
		throw new Exception("Debe seleccionar la Localidad.");
	}

	if ($_POST["email"] != "") {
		$params = array(":email" => $_POST["email"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "email";
			throw new Exception("El e-Mail debe tener un formato válido.");
		}
	}


	if ($_POST["calle_1"] == "") {
		$campoError = "calle_1";
		throw new Exception("Debe ingresar la Calle del Lugar de Trabajo 1.");
	}

	if ($_POST["codigoPostal_1"] == "") {
		$campoError = "codigoPostal_1";
		throw new Exception("Debe ingresar el Código Postal del Lugar de Trabajo 1.");
	}

	if ($_POST["provincia_1"] == -1) {
		$campoError = "provincia_1";
		throw new Exception("Debe seleccionar la Provincia del Lugar de Trabajo 1.");
	}

	if ($_POST["localidad_1"] == "") {
		$campoError = "localidad_1";
		throw new Exception("Debe seleccionar la Localidad del Lugar de Trabajo 1.");
	}

	for ($i=1; $i<=5; $i++)
		if ($_POST["email_".$i] != "") {
			$params = array(":email" => $_POST["email_".$i]);
			$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
			if (valorSql($sql, "", $params) != "S") {
				$campoError = "email_".$i;
				throw new Exception("El e-Mail debe tener un formato válido.");
			}
		}

	return true;
}


try {
	$campoError = "";

	if (!isReadonly()) {
		if (!validar())
			exit;


		if ($_POST["numero"] == "")
			$_POST["numero"] = "S\N";

		$params = array(":calle" => $_POST["calle"],
										":cpostal" => $_POST["codigoPostal"],
										":departamento" => $_POST["departamento"],
										":email" => $_POST["email"],
										":id" => $_SESSION["pcpId"],
										":localidad" => $_POST["localidad"],
										":nombreapellido" => strtoupper($_POST["nombre"]),
										":numero" => $_POST["numero"],
										":piso" => $_POST["piso"],
										":provincia" => $_POST["provincia"]);
			$sql =
				"UPDATE afi.avp_valida_pcp
						SET vp_calle = :calle,
								vp_cpostal = :cpostal,
								vp_departamento = :departamento,
								vp_email = :email,
								vp_fechamodif = SYSDATE,
								vp_localidad = :localidad,
								vp_nombreapellido = :nombreapellido,
								vp_numero = :numero,
								vp_piso = :piso,
								vp_provincia = :provincia,
								vp_usuarioweb = 'T'
					WHERE vp_id = :id";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		// Actualizo los teléfonos del empleador..
		$dataTel = inicializarTelefonos(OCI_DEFAULT, "vt_id_valida_pcp", $_SESSION["pcpId"], "vt", "afi.avt_valida_telefono_pcp", $_SESSION["usuario"]);
		copiarTempATelefonos($dataTel);



		// Guardo los lugares de trabajo..
		for ($i=1; $i<=5; $i++) {
			if ($_POST["lugarTrabajoVisible_".$i] == "t") {
				if ($_POST["idLugarTrabajo_".$i] < 0) {		// Alta..
					$params = array(":calle" => $_POST["calle_".$i],
													":cpostal" => $_POST["codigoPostal_".$i],
													":departamento" => $_POST["departamento_".$i],
													":email" => $_POST["email_".$i],
													":id_valida_pcp" => $_SESSION["pcpId"],
													":localidad" => $_POST["localidad_".$i],
													":numero" => $_POST["numero_".$i],
													":piso" => $_POST["piso_".$i],
													":provincia" => $_POST["provincia_".$i],
													":usualta" => $_SESSION["usuario"]);
					$sql =
						"INSERT INTO afi.avl_valida_lugartrabajo_pcp (vl_calle, vl_cpostal, vl_departamento, vl_email, vl_fechaalta, vl_id_valida_pcp, vl_localidad, vl_numero, vl_piso, vl_provincia,
																													vl_usualta, vl_usuarioweb)
																									VALUES (:calle, :cpostal, :departamento, :email, SYSDATE, :id_valida_pcp, :localidad, :numero, :piso, :provincia,
																													:usualta, 'T')";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}
				else {		// Modificación..
					$params = array(":calle" => $_POST["calle_".$i],
													":cpostal" => $_POST["codigoPostal_".$i],
													":departamento" => $_POST["departamento_".$i],
													":email" => $_POST["email_".$i],
													":id" => $_POST["idLugarTrabajo_".$i],
													":localidad" => $_POST["localidad_".$i],
													":numero" => $_POST["numero_".$i],
													":piso" => $_POST["piso_".$i],
													":provincia" => $_POST["provincia_".$i],
													":usumodif" => $_SESSION["usuario"]);
					$sql =
						"UPDATE afi.avl_valida_lugartrabajo_pcp
								SET vl_calle = :calle,
										vl_cpostal = :cpostal,
										vl_departamento = :departamento,
										vl_email = :email,
										vl_fechamodif = SYSDATE,
										vl_localidad = :localidad,
										vl_numero = :numero,
										vl_piso = :piso,
										vl_provincia = :provincia,
										vl_usumodif = :usumodif,
										vl_usuarioweb = 'T'
							WHERE vl_id = :id";
					DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				}

				// Teléfonos..
				$dataTel = inicializarTelefonos(OCI_DEFAULT, "vt_id_valida_lugartrabajo_pcp", $_POST["idLugarTrabajo_".$i], "vt", "afi.avt_valida_telefono_lt_pcp", $_SESSION["usuario"]);

				// Hago esto porque tengo 5 teléfonos apuntando a la misma tabla..
				if ($_POST["idLugarTrabajo_".$i] < 0) {
					$params = array(":usualta" => substr($_SESSION["usuario"], 0, 20));
					$sql = "SELECT MAX(vl_id) FROM afi.avl_valida_lugartrabajo_pcp WHERE vl_usualta = :usualta";
					$dataTel["gIdTablaPadre"] = valorSql($sql, $_POST["idLugarTrabajo_".$i], $params, 0);
				}

				$params = array(":tablapadreid" => $_POST["idLugarTrabajo_".$i],
												":tablatel" => "afi.avt_valida_telefono_lt_pcp",
												":usuarioweb" => $_SESSION["usuario"]);
				$sql =
					"SELECT mp_id
						 FROM tmp.tmp_telefonos
						WHERE mp_usuarioweb = :usuarioweb
							AND mp_tablatel = :tablatel
							AND (mp_tablapadreid = :tablapadreid OR mp_tablapadreid = -".$i.")";
				$stmt = DBExecSql($conn, $sql, $params);
				while ($row = DBGetQuery($stmt))
					copiarTempATelefonos($dataTel, $row["MP_ID"]);
			}
		}


		DBCommit($conn);
	}

	$_SESSION["paso"] = 2;
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
	window.parent.location.href = '/pcp-2';
</script>