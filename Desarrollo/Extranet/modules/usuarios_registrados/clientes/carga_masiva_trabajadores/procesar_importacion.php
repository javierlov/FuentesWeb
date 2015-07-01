<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getEstablecimiento($nombre) {
	global $conn;

	$params = array(":idempresa" => $_SESSION["idEmpresa"], ":nombre" => $nombre);
	$sql =
		"SELECT es_id
			 FROM aes_establecimiento, aco_contrato
			WHERE es_contrato = co_contrato
				AND co_idempresa = :idempresa
				AND UPPER(es_nombre) = UPPER(:nombre)";
	return ValorSql($sql, 0, $params, 0);
}


validarSesion(isset($_SESSION["isCliente"]));
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 55));

try {
	$params = array(":idusuario" => $_SESSION["idUsuario"], ":ipusuario" => $_SERVER["REMOTE_ADDR"]);
	$sql =
		"SELECT cm_calle, cm_ciuo, cm_codigopostal, cm_confirmapuesto, cm_cuil, cm_departamento, cm_establecimiento, cm_fechabaja, cm_fechaingreso, cm_fechanacimiento, cm_fila, cm_idusuario,
						cm_localidad, cm_nacionalidad, cm_nombre, cm_numero, cm_otranacionalidad, cm_piso, cm_sector, cm_sexo, cm_sueldo, cm_tarea, tb_codigo codigoestadocivil,
						pv_codigo codigoprovincia, na_id idnacionalidad, mc_id idtipocontrato
			 FROM tmp.tcm_cargamasivatrabajadoresweb, cmc_modalidadcontratacion, cna_nacionalidad, cpv_provincias, ctb_tablas estadocivil
			WHERE UPPER(cm_tipocontrato) = UPPER(mc_descripcion(+))
				AND UPPER(cm_nacionalidad) = UPPER(na_descripcion(+))
				AND UPPER(cm_provincia) = UPPER(pv_descripcion(+))
				AND UPPER(cm_estadocivil) = UPPER(estadocivil.tb_descripcion(+))
				AND estadocivil.tb_clave(+) = 'ESTAD'
				AND INSTR(cm_errores, '0') = 0
				AND cm_idusuario = :idusuario
				AND cm_ipusuario = :ipusuario";
	$stmt = DBExecSql($conn, $sql, $params);

	while ($row = DBGetQuery($stmt)) {
		$curs = null;
		$params = array(":cconfirmapuesto" => "S" /*$row["CM_CONFIRMAPUESTO"]*/,
										":dfechabaja" => NULL,
										":dfechaingreso" => $row["CM_FECHAINGRESO"],
										":dfechanacimiento" => $row["CM_FECHANACIMIENTO"],
										":ncontrato" => $_SESSION["contrato"],
										":nidmodalidadcontratacion" => nullIfCero($row["IDTIPOCONTRATO"]),
										":nidnacionalidad" => nullIfCero($row["IDNACIONALIDAD"]),
										":nidrelacionlaboral" => NULL,
										":nidtrabajador" => NULL,
										":nidusuario" => $_SESSION["idUsuario"],
										":nsueldo" => formatFloat(nullIfCero($row["CM_SUELDO"])),
										":scalle" => $row["CM_CALLE"],
										":scategoria" => NULL,
										":sciuo" => nullIfCero($row["CM_CIUO"]),
										":scodaltatemprana" => NULL,
										":scodareatelefono" => NULL,
										":scpostal" => $row["CM_CODIGOPOSTAL"],
										":scpostala" => NULL,
										":scuil" => $row["CM_CUIL"],
										":sdepartamento" => $row["CM_DEPARTAMENTO"],
										":sdocumento" => NULL,
										":sdomicilio" => NULL,
										":semail" => NULL,
										":sestablecimientos" => getEstablecimiento($row["CM_ESTABLECIMIENTO"]),
										":sestadocivil" => $row["CODIGOESTADOCIVIL"],
										":slateralidad" => NULL,
										":slocalidad" => $row["CM_LOCALIDAD"],
										":snombre" => $row["CM_NOMBRE"],
										":snumero" => $row["CM_NUMERO"],
										":sotranacionalidad" => $row["CM_OTRANACIONALIDAD"],
										":spiso" => $row["CM_PISO"],
										":sprovincia" => $row["CODIGOPROVINCIA"],
										":ssector" => $row["CM_SECTOR"],
										":ssexo" => $row["CM_SEXO"],
										":starea" => $row["CM_TAREA"],
										":stelefono" => NULL);
		$sql = "BEGIN webart.set_trabajador(:data, :cconfirmapuesto, TO_DATE(:dfechabaja, 'dd/mm/yyyy'), TO_DATE(:dfechaingreso, 'dd/mm/yyyy'), TO_DATE(:dfechanacimiento, 'dd/mm/yyyy'), :ncontrato, :nidmodalidadcontratacion, :nidnacionalidad, :nidrelacionlaboral, :nidtrabajador, :nidusuario, :nsueldo, :scalle, :scategoria, :sciuo, :scodaltatemprana, :scodareatelefono, :scpostal, :scpostala, :scuil, :sdepartamento, :sdocumento, :sdomicilio, :semail, :sestablecimientos, :sestadocivil, :slateralidad, :slocalidad, :snombre, :snumero, :sotranacionalidad, :spiso, :sprovincia, :ssector, :ssexo, :starea, :stelefono); END;";
		$stmt2 = DBExecSP($conn, $curs, $sql, $params);
		$row2 = DBGetSP($curs);

		if (($row2["NUMEROERROR"] != "0") and ($row2["NUMEROERROR"] != ""))
			throw new Exception($row2["NUMEROERROR"]." - ".$row2["DESCRIPCIONERROR"]);
	}
}
catch (Exception $e) {
	echo "<script type='text/javascript'>alert(unescape('".rawurlencode($e->getMessage())."'));</script>";
	exit;
}
?>
<script type="text/javascript">
	with (window.parent.document) {
//		getElementById('btnImportar').style.display = 'inline';
		getElementById('btnVolver').style.display = 'inline';
		getElementById('guardadoOk').style.visibility = 'visible';
		getElementById('spanMsgEspera').style.display = 'none';
	}
</script>