<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/net.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


class LoginAgenteComercial {
	/* getLoginResult: Esta función valida si el logueo es ok, o manda un mensaje de error en caso contrario.. */
	function getLoginResult($usuario, $clave, $loginParaCambiarClave = false) {
		global $conn;

		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml.= "<loginResult><error>";

		try {
			// Validación 1..
			if ($usuario == "") {
				$xml.= "<codigo>1</codigo>";
				$xml.= "<mensaje>Usuario vacío.</mensaje>";
			}

			$controlBcra = 0;
			$error = false;
			if ($usuario == "") {
				$xml.= "<codigo>1</codigo>";
				$xml.= "<mensaje>Usuario vacío.</mensaje>";
				$error = true;
			}
			else {
				$params = array(":usuario" => $usuario);
				$sql =
					"SELECT uw_ctrbcra
						 FROM afi.auw_usuarioweb
						WHERE uw_fechabaja IS NULL
							AND uw_usuario = :usuario";
				$controlBcra = ValorSql($sql, "", $params);
				if ($controlBcra == 1) {		// Si hay que hacerle controles especiales al usuario..
					$params = array(":usuario" => $usuario);
					$sql =
						"SELECT uw_id
							 FROM afi.auw_usuarioweb
							WHERE uw_fechabaja IS NULL
								AND uw_usuario = :usuario";
					$id = ValorSql($sql, "", $params);

					// Validación 2..
					$curs = null;
					$params = array(":id" => $id, ":password" => $clave, ":ip" => $_SERVER["REMOTE_ADDR"]);
					$sql = "BEGIN art.cotizacion.get_ctrlpassword(:id, :password, :ip, :data); END;";
					$stmt = DBExecSP($conn, $curs, $sql, $params);
					$rowCtrl = DBGetSP($curs);
					switch ($rowCtrl["NERROR"]) {
						case 1:
						case 2:
						case 4:
							$xml.= "<codigo>2</codigo>";
							$xml.= "<mensaje>".$rowCtrl["SERROR"]."</mensaje>";
							$error = true;
							break;
					}
					$cambiarPassword = ($rowCtrl["NERROR"] == 3);

					if (($cambiarPassword) and (!$loginParaCambiarClave)) {
						$xml.= "<codigo>3</codigo>";
						$xml.= "<mensaje>".$rowCtrl["SERROR"]."</mensaje>";
						$error = true;
					}
					elseif ($clave == "") {
						$xml.= "<codigo>4</codigo>";
						$xml.= "<mensaje>Contraseña vacía.</mensaje>";
						$error = true;
					}
				}
				else {		// Validaciones a usuarios comunes..
					$params = array(":usuario" => $usuario);
					$sql =
						"SELECT uw_forzarclave
							 FROM afi.auw_usuarioweb
							WHERE uw_fechabaja IS NULL
								AND uw_usuario = :usuario";
					$cambiarPassword = (ValorSql($sql, "", $params) == 1);
					if (($cambiarPassword) and (!$loginParaCambiarClave)) {
						$xml.= "<codigo>8</codigo>";
						$xml.= "<mensaje>La contraseña está vencida, debe actualizarla.</mensaje>";
						$error = true;
					}
					elseif ($clave == "") {
						$xml.= "<codigo>9</codigo>";
						$xml.= "<mensaje>Contraseña vacía.</mensaje>";
						$error = true;
					}
					else {
						$params = array(":usuario" => $usuario);
						$sql =
							"SELECT uw_password
								 FROM afi.auw_usuarioweb
								WHERE uw_fechabaja IS NULL
									AND uw_usuario = :usuario";
						$pass = ValorSql($sql, "", $params);
						if ($pass == "") {
							$xml.= "<codigo>10</codigo>";
							$xml.= "<mensaje>Usuario o contraseña inválido.</mensaje>";
							$error = true;
						}
						elseif ($pass != $clave) {
							$xml.= "<codigo>11</codigo>";
							$xml.= "<mensaje>Usuario o contraseña inválido.</mensaje>";
							$error = true;
						}
					}
				}
			}

			if ((!$error) and ($controlBcra == 1)) {
				// Si ingresó correctamente y si hay que hacerle controles especiales al usuario, valido que se haya conectado desde una IP permitida..
				$params = array(":usuario" => $usuario);
				$sql = 
					"SELECT uw_idcanal, uw_identidad, uw_nivel
						 FROM afi.auw_usuarioweb
						WHERE uw_usuario = :usuario";
				$stmt = DBExecSql($conn, $sql, $params);
				$row = DBGetQuery($stmt);

				if ($row["UW_NIVEL"] != 99) {		// Si tiene nivel 99 no se chequea desde que IP se conecta..
					$params = array(":idcanal" => $row["UW_IDCANAL"], ":identidad" => $row["UW_IDENTIDAD"]);
					$sql =
						"SELECT ip_rangodesde, ip_rangohasta
							 FROM web.wip_ipspermitidas
							WHERE ip_idpagina = 25
								AND ip_idcanal = :idcanal
								AND ip_identidad = :identidad
								AND ip_fechabaja IS NULL";
					$stmt = DBExecSql($conn, $sql, $params);

					$enRango = true;
					while ($row = DBGetQuery($stmt)) {
						$enRango = ipEnRango($_SERVER["REMOTE_ADDR"], $row["IP_RANGODESDE"], $row["IP_RANGOHASTA"]);
						if ($enRango)
							break;
					}

					if (!$enRango) {
						$xml.= "<codigo>15</codigo>";
						$xml.= "<mensaje>Usted no tiene permiso para conectarse desde esa ubicación.</mensaje>";
						$error = true;
					}
				}
			}

			$xml.= "</error></loginResult>";

			if ($error)
				return new soapval("return", "xsd:string", $xml);
			else {
				$params = array(":usuario" => $usuario);
				$sql = 
					"SELECT uw_id
						 FROM afi.auw_usuarioweb
						WHERE uw_fechabaja IS NULL
							AND uw_usuario = :usuario";
				$id = ValorSql($sql, "", $params);

				LogAccess($id, 4, gethostbyaddr($_SERVER['REMOTE_ADDR']), $_SERVER["REMOTE_ADDR"], 25);

				// Registro el último login..
				$params = array(":id" => $id);
				$sql =
					"UPDATE auw_usuarioweb
							SET uw_ultimologin = SYSDATE
						WHERE uw_id = :id";
				DBExecSql($conn, $sql, $params);

				return "";
			}
		}
		catch (Exception $e) {
			$xml = '<?xml version="1.0" encoding="utf-8"?>';
			//$xml.= "<error>".$e->getMessage()."</error>";
			$xml.= "<loginResult><error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getLoginResult.</mensaje></error></loginResult>";
			return new soapval("return", "xsd:string", $xml);
		}
	}


	/* getToken: Esta función retorna el token. La alerta WEB_1 es la encargada de borrar los tokens inválidos.. */
	function getToken($usuario) {
		global $conn;

		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml.= "<token>";

		try {
			$params = array(":usuario" => $usuario);
			$sql =
				"SELECT uw_token, uw_tokenvalidodesde, uw_tokenvalidohasta
					 FROM afi.auw_usuarioweb
					WHERE uw_usuario = :usuario
						AND SYSDATE BETWEEN uw_tokenvalidodesde AND uw_tokenvalidohasta";
			$stmt = DBExecSql($conn, $sql, $params);
			$row = DBGetQuery($stmt);

			if ($row["UW_TOKEN"] != "")		// Si ya existe un token válido para ese usuario, lo tomo..
				$token = $row["UW_TOKEN"];
			else {		// Sino, creo el token y las fechas de validez..
				// Loopeo hasta encontrar un token que no exista, sino devuelvo un error..
				$i = 0;
				$token = md5(md5($usuario.mt_rand()));
				$params = array(":token" => $token);
				$sql =
					"SELECT 1
						 FROM afi.auw_usuarioweb
						WHERE uw_token = :token";
				while (ExisteSql($sql, $params)) {
					if ($i > 30) {		// Intento 30 veces, sino corto por error..
						throw new Exception("No se pudo generar el token.");
					}
					else {
						$token = md5(md5($usuario.mt_rand()));
						$params = array(":token" => $token);
						$i++;
						usleep(500000);
					}
				}

				$params = array(":token" => $token, ":usuario" => $usuario);
				$sql =
					"UPDATE afi.auw_usuarioweb
							SET uw_token = :token,
									uw_tokenvalidodesde = SYSDATE,
									uw_tokenvalidohasta = SYSDATE + 0.5
						WHERE uw_usuario = :usuario";
				DBExecSql($conn, $sql, $params);

				$params = array(":usuario" => $usuario);
				$sql =
					"SELECT uw_tokenvalidodesde, uw_tokenvalidohasta
						 FROM afi.auw_usuarioweb
						WHERE uw_usuario = :usuario";
				$stmt = DBExecSql($conn, $sql, $params);
				$row = DBGetQuery($stmt);
			}

			$xml.= "<validezDesde>".$row["UW_TOKENVALIDODESDE"]."</validezDesde>";
			$xml.= "<validezHasta>".$row["UW_TOKENVALIDOHASTA"]."</validezHasta>";
			$xml.= "<token>".$token."</token>";
		}
		catch (Exception $e) {
			//$xml.= "<error>".$e->getMessage()."</error>";
			$xml.= "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getToken.</mensaje></error>";
		}

		$xml.= "</token>";
		return new soapval("return", "xsd:string", $xml);
	}

	/* setClave: Esta función cambia la clave del usuario pasado como parámetro.. */
	function setClave($usuario, $clave) {
		global $conn;

		try {
			$params = array(":usuario" => $usuario);
			$sql =
				"SELECT uw_id
					 FROM afi.auw_usuarioweb
					WHERE uw_fechabaja IS NULL
						AND uw_usuario = :usuario";
			$id = ValorSql($sql, "", $params);

			$params = array(":usuario" => $usuario);
			$sql =
				"SELECT uw_ctrbcra
					 FROM afi.auw_usuarioweb
					WHERE uw_fechabaja IS NULL
						AND uw_usuario = :usuario";
			$controlBcra = ValorSql($sql, "", $params);
			if ($controlBcra == 1) {		// Si hay que hacerle controles especiales al usuario, se setea la clave desde un SP..
				$curs = null;
				$params = array(":id" => $id, ":password" => $clave);
				$sql = "BEGIN art.cotizacion.set_cambiopassword(:id, :password, :data); END;";
				$stmt = DBExecSP($conn, $curs, $sql, $params);
				$rowCtrl = DBGetSP($curs);
				if ($rowCtrl["NERROR"] == 1) {
					$_SESSION["fieldError"] = "psn";
					$_SESSION["msgError"] = $rowCtrl["SERROR"]." (7)";
					$error = true;
				}
			}

			// Actualizo la clave..
			$params = array(":id" => $id, ":password" => $clave);
			$sql =
				"UPDATE afi.auw_usuarioweb
						SET uw_password = :password,
								uw_forzarclave = 0
					WHERE uw_id = :id";
			DBExecSql($conn, $sql, $params);

			$xml = '<?xml version="1.0" encoding="utf-8"?>';
			$xml.= "<clave><mensaje>El cambio de clave se realizó correctamente.</mensaje><status>OK</status></clave>";
		}
		catch (Exception $e) {
			$xml = '<?xml version="1.0" encoding="utf-8"?>';
			//$xml.= "<error>".$e->getMessage()."</error>";
			$xml.= "<clave><error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función setClave.</mensaje></error></clave>";
		}

		return new soapval("return", "xsd:string", $xml);
	}


	/* validarToken: Esta función valida que un token pasado como parámetro exista y tenga validez.. */
	function validarToken($token) {
		global $conn;

		try {
			if (substr($token, 0, 24) == "ORIGEN:WEB_PROVINCIA_ART")		// El token puede venir con esa leyenda para poder saber si la llamada es desde la Web de Provincia ART o externa..
				$params = array(":token" => substr($token, 24));
			else
				$params = array(":token" => $token);

			$sql =
				"SELECT 1
					 FROM afi.auw_usuarioweb
					WHERE uw_token = :token
						AND SYSDATE BETWEEN uw_tokenvalidodesde AND uw_tokenvalidohasta";
			if (ExisteSql($sql, $params))
				return "";
			else {
				$xml = '<?xml version="1.0" encoding="utf-8"?>';
				$xml.= "<token><error>El token es inválido o está vencido.</error></token>";
				return new soapval("return", "xsd:string", $xml);
			}
		}
		catch (Exception $e) {
			$xml = '<?xml version="1.0" encoding="utf-8"?>';
			//$xml.= "<error>".$e->getMessage()."</error>";
			$xml.= "<clave><error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función validarToken.</mensaje></error></clave>";
			return new soapval("return", "xsd:string", $xml);
		}
	}
}
?>