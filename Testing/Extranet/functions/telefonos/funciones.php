<?
function actualizarTelefono($data, $row) {
	global $conn;

	$params = array(":area" => $row["MP_AREA"],
									":id" => $row["MP_REGISTROTELID"],
									":idtablapadre" => $data["gIdTablaPadre"],
									":idtipotelefono" => $row["MP_IDTIPOTELEFONO"],
									":interno" => $row["MP_INTERNO"],
									":numero" => $row["MP_NUMERO"],
									":observacion" => $row["MP_OBSERVACION"],
									":principal" => $row["MP_PRINCIPAL"],
									":usumodif" => substr($data["gUsuario"], 0, 20));
	$sql =
		"UPDATE #TABLA#
				SET #CAMPO_CLAVE# = :idtablapadre,
						#PREFIJO#_AREA = :area,
						#PREFIJO#_FECHAMODIF = SYSDATE,
						#PREFIJO#_IDTIPOTELEFONO = :idtipotelefono,
						#PREFIJO#_INTERNO = :interno,
						#PREFIJO#_NUMERO = :numero,
						#PREFIJO#_OBSERVACION = :observacion,
						#PREFIJO#_PRINCIPAL = :principal,
						#PREFIJO#_USUMODIF = :usumodif
		  WHERE #PREFIJO#_ID = :id";

	$sql = str_replace("#CAMPO_CLAVE#", $data["gCampoClave"], $sql);
	$sql = str_replace("#PREFIJO#", $data["gPrefijo"], $sql);
	$sql = str_replace("#TABLA#", $data["gTabla"], $sql);

	DBExecSql($conn, $sql, $params, $data["gAutoCommit"]);
}

function copiarTelefonosATemp($data) {
	global $conn;

	// Si el id de la tabla padre es menor a 1 es porque se está dando un alta y no existen teléfonos a copiar..
	if ($data["gIdTablaPadre"] < 1)
		return;


	$params = array(":tablapadreid" => $data["gIdTablaPadre"],
									":tablatel" => $data["gTabla"],
									":tipo" => $data["gTipo"],
									":usuarioweb" => $data["gUsuario"]);
	$sql =
		"INSERT INTO tmp.tmp_telefonos
								(mp_area, mp_estado, mp_historico, mp_id, mp_idtipotelefono, mp_interno, mp_numero, mp_observacion, mp_principal, mp_registrotelid, mp_tablapadreid,
								 mp_tablatel, mp_tipo, mp_usuarioid, mp_usuarioweb)
				  SELECT #PREFIJO#_area, 'X', 'F', 1, #PREFIJO#_idtipotelefono, #PREFIJO#_interno, #PREFIJO#_numero, #PREFIJO#_observacion, #PREFIJO#_principal, #PREFIJO#_id, :tablapadreid,
								 :tablatel, :tipo, -1, :usuarioweb
						FROM #TABLA#
					 WHERE #CAMPO_CLAVE# = #ID_TABLA_PADRE#
						 AND #PREFIJO#_fechabaja IS NULL
						 AND #PREFIJO#_tipo = :tipo";
	$sql = str_replace("#CAMPO_CLAVE#", $data["gCampoClave"], $sql);
	$sql = str_replace("#ID_TABLA_PADRE#", $data["gIdTablaPadre"], $sql);
	$sql = str_replace("#PREFIJO#", $data["gPrefijo"], $sql);
	$sql = str_replace("#TABLA#", $data["gTabla"], $sql);

	DBExecSql($conn, $sql, $params);
}

function copiarTempATelefonos($data, $idTelefono = 0) {
	global $conn;

	$sql = "SELECT * FROM tmp.tmp_telefonos";
	if ($idTelefono == 0) {
		$params = array(":tablapadreid" => $data["gIdTablaPadre"],
										":tablatel" => $data["gTabla"],
										":tipo" => $data["gTipo"],
										":usuarioweb" => $data["gUsuario"]);
		$sql.=
			" WHERE mp_usuarioweb = :usuarioweb
					AND mp_tablatel = :tablatel
					AND (mp_tablapadreid = :tablapadreid OR mp_tablapadreid = -1)
					AND mp_tipo = :tipo";
	}
	else {
		$params = array(":id" => $idTelefono);
		$sql.= " WHERE mp_id = :id";
	}

	$stmt = DBExecSql($conn, $sql, $params);
	while ($row = DBGetQuery($stmt)) {
		if ($row["MP_ESTADO"] == "A")
			insertarTelefono($data, $row);

		if ($row["MP_ESTADO"] == "M")
			if ($row["MP_REGISTROTELID"] <= 0)
				insertarTelefono($data, $row);
			else
				actualizarTelefono($data, $row);

		if (($row["MP_ESTADO"] == "B") and ($row["MP_REGISTROTELID"] > 0))
			eliminarTelefono($data, $row);
	}
}

function eliminarTelefono($data, $row){
	global $conn;

	$params = array(":id" => $row["MP_REGISTROTELID"], ":usubaja" => substr($data["gUsuario"], 0, 20));
	$sql =
		"UPDATE #TABLA#
				SET #PREFIJO#_FECHABAJA = SYSDATE,
						#PREFIJO#_USUBAJA = :usubaja
		  WHERE #PREFIJO#_ID = :id";

	$sql = str_replace("#PREFIJO#", $data["gPrefijo"], $sql);
	$sql = str_replace("#TABLA#", $data["gTabla"], $sql);

	DBExecSql($conn, $sql, $params, $data["gAutoCommit"]);
}

function inicializarTelefonos($autoCommit, $campoClave, $idTablaPadre, $prefijo, $tabla, $usuario, $tipo = "L") {
	$result = array();

	$result["gAutoCommit"] = $autoCommit;
	$result["gCampoClave"] = $campoClave;
	$result["gIdTablaPadre"] = -1;
	$result["gPrefijo"] = $prefijo;
	$result["gTabla"] = $tabla;
	$result["gTipo"] = $tipo;
	$result["gUsuario"] = $usuario;


	if (intval($idTablaPadre) > 0)
		$result["gIdTablaPadre"] = $idTablaPadre;

	return $result;
}

function insertarTelefono($data, $row) {
	global $conn;

	$params = array(":area" => $row["MP_AREA"],
									":idtablapadre" => $data["gIdTablaPadre"],
									":idtipotelefono" => $row["MP_IDTIPOTELEFONO"],
									":interno" => $row["MP_INTERNO"],
									":numero" => $row["MP_NUMERO"],
									":observacion" => $row["MP_OBSERVACION"],
									":principal" => $row["MP_PRINCIPAL"],
									":tipo" => $row["MP_TIPO"],
									":usualta" => substr($data["gUsuario"], 0, 20));
	$sql =
		"INSERT INTO #TABLA# (#PREFIJO#_ID, #CAMPO_CLAVE#, #PREFIJO#_TIPO, #PREFIJO#_IDTIPOTELEFONO, #PREFIJO#_AREA, #PREFIJO#_NUMERO, #PREFIJO#_INTERNO, #PREFIJO#_PRINCIPAL,
													#PREFIJO#_OBSERVACION, #PREFIJO#_FECHAALTA, #PREFIJO#_USUALTA)
								  VALUES (1, :idtablapadre, :tipo, :idtipotelefono, :area, :numero, :interno, :principal,
													:observacion, SYSDATE, :usualta)";

	$sql = str_replace("#CAMPO_CLAVE#", $data["gCampoClave"], $sql);
	$sql = str_replace("#PREFIJO#", $data["gPrefijo"], $sql);
	$sql = str_replace("#TABLA#", $data["gTabla"], $sql);

	DBExecSql($conn, $sql, $params, $data["gAutoCommit"]);

	// Actualizo la referencia hacia el nuevo registro creado en la tabla temporal..
	$params = array(":usualta" => substr($_SESSION["usuario"], 0, 20));
	$sql = "SELECT MAX(#PREFIJO#_id) FROM #TABLA# WHERE #PREFIJO#_usualta = :usualta";
	$sql = str_replace("#PREFIJO#", $data["gPrefijo"], $sql);
	$sql = str_replace("#TABLA#", $data["gTabla"], $sql);
	$id = ValorSql($sql, "", $params, 0);

	$params = array(":id" => $row["MP_ID"], ":registrotelid" => $id);
	$sql = "UPDATE tmp.tmp_telefonos SET mp_estado = 'M', mp_registrotelid = :registrotelid WHERE mp_id = :id";
	DBExecSql($conn, $sql, $params, $data["gAutoCommit"]);
}

function quitarTelefonosTemporales($data) {
	global $conn;

	$params = array(":idtablapadre" => $data["gIdTablaPadre"],
									":tablatel" => $data["gTabla"],
									":tipo" => $data["gTipo"],
									":usuarioweb" => $data["gUsuario"]);
	$sql =
		"DELETE FROM tmp.tmp_telefonos
					 WHERE mp_usuarioweb = :usuarioweb
						 AND mp_tablatel = :tablatel
						 AND mp_tablapadreid = :idtablapadre
						 AND mp_tipo = :tipo";
	DBExecSql($conn, $sql, $params);
}
?>