<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/oracle_funcs.php");


function agregarEstablecimientos($idUsuario) {
	global $conn;

	$params = array(":idusuarioextranet" => $idUsuario);
	$sql = "SELECT uc_id FROM web.wuc_usuariosclientes WHERE uc_idusuarioextranet = :idusuarioextranet";
	$idCliente = valorSql($sql, 0, $params);

	$params = array(":idcliente" => $idCliente);
	$sql = "DELETE FROM web.wel_establecimientoscliente WHERE el_idcliente = :idcliente";
	DBExecSql($conn, $sql, $params);

	$params = array(":idcliente" => $idCliente, ":usualta" => substr($_SESSION["usuario"], 0, 20));
	$sql =
		"INSERT INTO web.wel_establecimientoscliente (el_fechaalta, el_idcliente, el_idestablecimiento, el_usualta)
					SELECT SYSDATE, :idcliente, es_id, :usualta
						FROM aes_establecimiento
					 WHERE es_contrato IN(".$_SESSION["contratos"].")
						 AND es_fechabaja IS NULL";
	DBExecSql($conn, $sql, $params);
}

function validar() {
	if ($_POST["usuario"] == "")
		throw new Exception("Debe ingresar el Usuario.");

	if ($_POST["email"] == "")
		throw new Exception("Debe ingresar el e-Mail.");

	if ($_POST["email"] != "") {
		$params = array(":email" => $_POST["email"]);
		$sql = "SELECT art.varios.is_validaemail(:email) FROM DUAL";
		if (valorSql($sql, "", $params) != "S") {
			$campoError = "email";
			throw new Exception("El e-Mail debe tener un formato válido.");
		}
	}
}


if (!isset($_SESSION["idUsuario"])) {
?>
	<script type="text/javascript">
		window.location.href = '/modules/admin_users_web/login.php';
	</script>
<?
	exit;
}


try {
	validar();

	if ($_POST["accion"] == "A") {
		$_POST["email"] = strtolower($_POST["email"]);


		// Valido que no exista el e-mail..
		$params = array(":email" => $_POST["email"], ":id" => $_POST["id"]);
		$sql =
			"SELECT 1
				 FROM web.wue_usuariosextranet
				WHERE ue_idmodulo = 49
					AND ue_usuario = :email
					AND ue_id <> :id";
		if (valorSql($sql, -1, $params) == 1) {
?>
			<script type="text/javascript">
				alert('Ya existe un usuario con esa dirección de e-mail.');
				history.go(-1);
			</script>
<?
			exit;
		}

		$pass = "12345678";

		$curs = null;
		$params = array(":cavisoobra" => "S",
										":ccartilla" => "S",
										":ccertificadocobertura" => "S",
										":cconsultasiniestros" => "S",
										":cdenunciasiniestros" => "S",
										":cesadmin" => "S",
										":cesadmintotal" => "N",
										":cestado" => "A",
										":cestadosituacionpagos" => "S",
										":cforzarclave" => "S",
										":chabilitarestablecimientos" => "S",
										":cinformesiniestrado" => "S",
										":clegales" => "S",
										":cnominatrabajadores" => "S",
										":cprevencion" => "S",
										":nid" => 0,
										":scargo" => NULL,
										":sclave" => $pass,
										":scontratos" => $_SESSION["contratos"],
										":semail" => $_POST["email"],
										":sidsestablecimientos" => "",
										":snombre" => $_POST["usuario"],
										":stelefonos" => NULL,":susualta" => substr($_SESSION["usuario"], 0, 20));
		$sql ="BEGIN webart.set_usuario_cliente(:data, :cavisoobra, :ccartilla, :ccertificadocobertura, :cconsultasiniestros, :cdenunciasiniestros, :cesadmin, :cesadmintotal, :cestado, :cestadosituacionpagos, :cforzarclave, :chabilitarestablecimientos, :cinformesiniestrado, :clegales, :cnominatrabajadores, :cprevencion, :nid, :scargo, :sclave, :scontratos, :semail, :sidsestablecimientos, :snombre, :stelefonos, :susualta); END;";
		$stmt = DBExecSP($conn, $curs, $sql, $params);
		$row = DBGetSP($curs);

		// Pongo la clave como provisoria..
		$params = array(":claveprovisoria" => $pass, ":id" => $row["ID"]);
		$sql =
			"UPDATE web.wue_usuariosextranet
					SET ue_clave = art.utiles.md5(:claveprovisoria),
							ue_claveprovisoria = art.utiles.md5(:claveprovisoria),
							ue_fechavencclaveprovisoria = SYSDATE + 3
				WHERE ue_id = :id";
		DBExecSql($conn, $sql, $params);

		agregarEstablecimientos($row["ID"]);
?>
		<script type="text/javascript">
			window.location.href = '/modules/admin_users_web/index.php?pageid=2&id=-1&g=o';
		</script>
<?
	}

	if ($_POST["accion"] == "M") {
		$params = array(":idusuarioextranet" => $_POST["id"], ":nombre" => $_POST["usuario"], ":usumodif" => substr($_SESSION["usuario"], 0, 20));
		$sql =
			"UPDATE web.wuc_usuariosclientes
					SET uc_fechamodif = SYSDATE,
							uc_nombre = :nombre,
							uc_usumodif = :usumodif
			  WHERE uc_idusuarioextranet = :idusuarioextranet";
		DBExecSql($conn, $sql, $params);

		agregarEstablecimientos($_POST["id"]);
?>
		<script type="text/javascript">
			window.location.href = '/modules/admin_users_web/index.php?buscar=yes&id=<?= $_POST["id"]?>';
		</script>
<?
	}

	if ($_POST["accion"] == "B") {
		$params = array(":id" => $_POST["id"], ":usubaja" => substr($_SESSION["usuario"], 0, 20));
		$sql =
			"UPDATE web.wue_usuariosextranet
					SET ue_fechabaja = SYSDATE,
							ue_usubaja = :usubaja
			  WHERE ue_id = :id";
		DBExecSql($conn, $sql, $params);

		$params = array(":idusuarioextranet" => $_POST["id"], ":usubaja" => substr($_SESSION["usuario"], 0, 20));
		$sql =
			"UPDATE web.wuc_usuariosclientes
					SET uc_fechabaja = SYSDATE,
							uc_usubaja = :usubaja
			  WHERE uc_idusuarioextranet = :idusuarioextranet";
		DBExecSql($conn, $sql, $params);
?>
		<script type="text/javascript">
			history.go(-2);
		</script>
<?
	}
}
catch (Exception $e) {
?>
	<script type="text/javascript">
		history.go(-1);
		alert(unescape('<?= rawurlencode($e->getMessage())?>'));
	</script>
<?
	exit;
}
?>