<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


function actualizarRankingBNA($idSolicitudCotizacion, $commit = 1) {
	global $conn;

	$curs = null;
	$params = array(":id" => $idSolicitudCotizacion);
	$sql = "BEGIN art.afiliacion.do_rankingbna('S', :id); END;";
	DBExecSP($conn, $curs, $sql, $params, false, $commit);
}

function getFile($file, $mode = "i") {
	// Inserto una pseudoencriptación ademas del base64..
	$str = base64_encode($file);
	$str = substr_replace($str, strrev(substr($str, 7, 7)), 7, 7);

	$nums = date("His");
	if (($nums[0] % 2) == 0)
		$extraNum = 0;
	else
		$extraNum = 9;
	for ($i=1; $i<6; $i++)
		if (($nums[0] % 2) == 0) {
			if ($nums[$i] > $extraNum)
				$extraNum = $nums[$i];
		}
		else {
			if ($nums[$i] < $extraNum)
				$extraNum = $nums[$i];
		}
	$str = substr_replace($str, substr($str, 16, 1).$extraNum, 16, 1);

	return "/functions/get_file.php?fl=".$str."&md=".$mode."&rnd=".date("Ymdhisu");
}

function getPagePath($id) {
	global $conn;
	$result = $_SERVER["DOCUMENT_ROOT"]."/main.php";

	if ($id > 0) {
		$params = array(":id" => $id);
		$sql =
			"SELECT pa_ruta
				 FROM web.wpa_paginasextranet
				WHERE pa_id = :id";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);

		if ($row["PA_RUTA"] != "")
			$result = $_SERVER["DOCUMENT_ROOT"]."/".$row["PA_RUTA"];
	}

	return $result;
}

function hasPermiso($idPagina, $idUsuario) {
	$params = array(":id" => $idUsuario, ":idpagina" => $idPagina);
	$sql =
		"SELECT 1
			 FROM web.wpx_permisosextranet, web.wue_usuariosextranet
			WHERE px_idusuario = ue_id
				AND ue_id = :id
				AND px_idpagina = :idpagina";
	return ExisteSql($sql, $params);
}

function limpiarXss() {
	if (isset($_GET))
		foreach ($_GET as $key => $value)
			$_GET[$key] = htmlspecialchars($value, ENT_QUOTES);

	if (isset($_POST))
		foreach ($_POST as $key => $value)
			$_POST[$key] = htmlspecialchars($value, ENT_QUOTES);

	if (isset($_REQUEST))
		foreach ($_REQUEST as $key => $value)
			$_REQUEST[$key] = htmlspecialchars($value, ENT_QUOTES);
}

function logAccess($usuario, $idTipoUsuario, $remoteHost, $remoteIp, $idPagina) {
	global $conn;
/*
	$params = array(":usuario" => $usuario, ":idtipousuario" => $idTipoUsuario, ":remotehost" => $remoteHost, ":remoteip" => $remoteIp, ":idpagina" => $idPagina);
	$sql =
		"INSERT INTO web.wle_logextranet
								 (le_usuario, le_idtipousuario, le_remotehost, le_remoteip, le_fechaacceso, le_idpagina)
					VALUES (:usuario, :idtipousuario, :remotehost, :remoteip, SYSDATE, :idpagina)";
	DBExecSql($conn, $sql, $params);
*/	
}

function shutdown($pageid, $params = "", $resultInParentWindow = false) {
	$parent = "";
	if ($resultInParentWindow)
		$parent = "parent.";
	echo "<script type='text/javascript'>window.".$parent."location.href = '/index.php?pageid=".$pageid.$params."';</script>";
}

function validarAccesoCotizacion($id_modulo) {
// Se valida que el que quiera ver una cotización, afiliación o algún dato relativo a esos dos, sea del mismo canal - entidad - sucursal - vendedor..

	global $conn;

	$id = substr($id_modulo, 1);
	$modulo = substr($id_modulo, 0, 1);

	if ($modulo == "R")		// Si es una revisión de precio..
		$sql =
			"SELECT sr_idcanal idcanal, sr_identidad identidad, sr_idsucursal idsucursal, sr_idvendedor idvendedor
				 FROM asr_solicitudreafiliacion
				WHERE sr_id = :id";
	else
		$sql =
			"SELECT sc_canal idcanal, sc_identidad identidad, sc_idsucursal idsucursal, sc_idvendedor idvendedor
				 FROM asc_solicitudcotizacion
				WHERE sc_id = :id";
	$params = array(":id" => $id);
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);

	if (($_SESSION["canal"] != $row["IDCANAL"]) or
			($_SESSION["entidad"] != $row["IDENTIDAD"]) or 
		 (($_SESSION["sucursal"] != $row["IDSUCURSAL"]) and ($_SESSION["sucursal"] != "")) or 
		 (($_SESSION["vendedor"] != $row["IDVENDEDOR"]) and ($_SESSION["vendedor"] != ""))) {
		echo '<span id="sesionInvalidData">'.$_SERVER["REMOTE_ADDR"].' ('.gethostbyaddr($_SERVER['REMOTE_ADDR']).')</span><br />';
		echo '<span id="sesionInvalidMsg">Usted no tiene permiso para acceder a los datos de esa cotización/afiliación.';
		exit;
	}
}

function validarContrato($contrato) {
	$params = array(":contrato" => intval($contrato), ":idcanal" => $_SESSION["canal"], ":identidad" => $_SESSION["entidad"]);
	$where = "";

	if ($_SESSION["entidad"] != 9003) {
		if ($_SESSION["sucursal"] != "") {
			$params[":idsucursal"] = $_SESSION["sucursal"];
			$where.= " AND vc_idsucursal = :idsucursal";
		}
		if ($_SESSION["vendedor"] != "") {
			$params[":idvendedor"] = $_SESSION["vendedor"];
			$where.= " AND ev_idvendedor = :idvendedor";
		}
	}

	if ($_SESSION["entidad"] == 10) {		// Venta Directa..
		$params[":entidad2"] = 400;		// Banco Nación..
		$where.= ") OR (en_id = :entidad2";
	}

	$sql =
		"SELECT 1
			 FROM avc_vendedorcontrato, xev_entidadvendedor, xen_entidad
			WHERE vc_identidadvend = ev_id
				AND ev_identidad = en_id
				AND TO_CHAR (SYSDATE, 'YYYYMM') BETWEEN vc_vigenciadesde AND NVL (vc_vigenciahasta, '299999')
				AND vc_fechabaja IS NULL
				AND vc_contrato = :contrato
				AND (en_idcanal = :idcanal
				AND en_id = :identidad";

	return ExisteSql($sql.$where.")", $params);
}

function validarParametro($isValid) {
	if (!$isValid) {
		echo '<span id="sesionInvalidData">'.$_SERVER["REMOTE_ADDR"].' ('.gethostbyaddr($_SERVER['REMOTE_ADDR']).')</span><br />';
		echo '<span id="sesionInvalidMsg">Modo incorrecto de acceso al sistema.';
		exit;
	}
}

function validarPermisoClienteXModulo($idCliente, $modulo) {
	if ((isset($_SESSION["isCliente"])) and (($_SESSION["isAdmin"]) or ($_SESSION["isAdminTotal"]))) {		// Si es Admin o Admin Total tiene permiso para todo..
		if ($modulo == 94) {		// Clientes - Informe de Ingeniería y Siniestralidad..
			$params = array(":contrato" => $_SESSION["contrato"]);
			$sql =
				"SELECT 1
					 FROM web.wii_informesiys
					WHERE ii_contrato = :contrato
						AND ii_fechabaja IS NULL";
			return ExisteSql($sql, $params);
		}
		else
			return true;
	}

	if (isset($_SESSION["isCliente"])) {
		switch ($modulo) {
			case 33:
				$campo = "uc_legales";
				break;
			case 52:
				$campo = "uc_nominatrabajadores";
				break;
			case 55:
				return ($_SESSION["suss"] == 2);
				break;
			case 58:
				$campo = "uc_estadosituacionpagos";
				break;
			case 60:
				$campo = "uc_cartilla";
				break;
			case 61:
				$campo = "uc_denunciasiniestros";
				break;
			case 64:
				$campo = "uc_consultasiniestros";
				break;
			case 66:
				return false;
				break;
			case 68:
				$campo = "uc_prevencion";
				break;
			case 69:
				return true;
				break;
			case 70:
				$campo = "uc_certificadocobertura";
				break;
			case 71:
				$campo = "uc_certificadocobertura";
				break;
			case 72:
				$campo = "uc_certificadocobertura";
				break;
			case 94:
				$campo = "uc_informes";
				break;
			case 95:
				$campo = "uc_avisoobra";
				break;
		}
		$params = array(":idusuarioextranet" => $idCliente);
		$sql = "SELECT ".$campo." FROM web.wuc_usuariosclientes WHERE uc_idusuarioextranet = :idusuarioextranet";

		return (ValorSql($sql, "", $params) == "S");
	}

	if (isset($_SESSION["isAgenteComercial"])) {
		return (($modulo == 33) or ($modulo == 52) or ($modulo == 60) or ($modulo == 61) or ($modulo == 64) or ($modulo == 70) or ($modulo == 71) or ($modulo == 72));
	}
}

function validarSesion($isValid) {
	if (!$isValid) {
		echo '<span id="sesionInvalidData">'.$_SERVER["REMOTE_ADDR"].' ('.gethostbyaddr($_SERVER['REMOTE_ADDR']).')</span><br />';
		echo '<span id="sesionInvalidMsg">Usted no tiene permiso para acceder a este módulo.';
		exit;
	}
}


limpiarXss();
?>