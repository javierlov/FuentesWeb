<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


class TablasReferencia {
	/* getActividad: Devuelve los datos de la tabla afi.ata_tipoactividad.. */
	private function getActividad($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT ta_id id, ta_detalle detalle
					 FROM afi.ata_tipoactividad
					WHERE ta_fechabaja IS NULL
						AND UPPER(ta_detalle) LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<actividad><id>".$row["ID"]."</id><detalle>".$row["DETALLE"]."</detalle></actividad>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getActividad.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* getArt: Devuelve los datos de la tabla aar_art.. */
	private function getArt($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT ar_id id, ar_nombre detalle
					 FROM aar_art
					WHERE ar_fechabaja IS NULL
						AND UPPER(ar_nombre) LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<art><id>".$row["ID"]."</id><detalle>".$row["DETALLE"]."</detalle></art>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getArt.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* getCiiu: Devuelve los datos de la tabla cac_actividad.. */
	private function getCiiu($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT ac_id id, UPPER(ac_descripcion) detalle
					 FROM cac_actividad
					WHERE LENGTH(ac_codigo) = 6
						AND ac_fechabaja IS NULL
						AND UPPER(ac_descripcion) LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<ciiu><id>".$row["ID"]."</id><detalle>".$row["DETALLE"]."</detalle></ciiu>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getCiiu.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* getLocalidad: Devuelve los datos de la tabla art.ccp_codigopostal.. */
	private function getLocalidad($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT cp_id id, cp_provincia idprovincia, cp_localidadcap detalle
					 FROM art.ccp_codigopostal
					WHERE cp_fechabaja IS NULL
						AND UPPER(cp_localidadcap) LIKE UPPER(:detalle)
			UNION ALL
				 SELECT 0 id, '2' idprovincia, 'Capital Federal' detalle
					 FROM DUAL
					WHERE UPPER('Capital Federal') LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<localidad><id>".$row["ID"]."</id><idProvincia>".$row["IDPROVINCIA"]."</idProvincia><detalle>".$row["DETALLE"]."</detalle></localidad>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getLocalidad.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* getNombresTablasReferencia: Devuelve el nombre de las tablas de referencia que se usan al cargar una solicitud de cotización.. */
	public function getNombresTablasReferencia() {
		$tablas = array("ACTIVIDAD", "ART", "CIIU", "LOCALIDAD", "PROVINCIA", "SECTOR", "STATUS_BCRA", "STATUS_SRT", "ZONA_GEOGRAFICA");

		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml.= "<tablas>";

		foreach ($tablas as $value)
			$xml.= "<tabla>".$value."</tabla>";

		$xml.= "</tablas>";

		return new soapval("return", "xsd:string", $xml);
	}


	/* getProvincia: Devuelve los datos de la tabla afi.azg_zonasgeograficas.. */
	private function getProvincia($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT zg_id id, zg_descripcion detalle
					 FROM afi.azg_zonasgeograficas
					WHERE zg_fechabaja IS NULL
						AND UPPER(zg_descripcion) LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<provincia><id>".$row["ID"]."</id><detalle>".$row["DETALLE"]."</detalle></provincia>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getProvincia.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* getSector: Devuelve los datos de la tabla afi.azg_zonasgeograficas.. */
	private function getSector($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT tb_codigo id, tb_descripcion detalle
					 FROM ctb_tablas
					WHERE tb_clave = 'SECT'
						AND tb_codigo IN(2, 3, 4)
						AND tb_fechabaja IS NULL
						AND UPPER(tb_descripcion) LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<sector><id>".$row["ID"]."</id><detalle>".$row["DETALLE"]."</detalle></sector>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getSector.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* getStatusBcra: Devuelve los datos de la tabla ctb_tablas, clave STBCR.. */
	private function getStatusBcra($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT DECODE(tb_codigo, -1, 0, tb_codigo) id, tb_descripcion detalle
					 FROM ctb_tablas
					WHERE tb_clave = 'STBCR'
						AND tb_codigo <> '0'
						AND tb_fechabaja IS NULL
						AND UPPER(tb_descripcion) LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<statusBcra><id>".$row["ID"]."</id><detalle>".$row["DETALLE"]."</detalle></statusBcra>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getStatusBcra.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* getStatusSrt: Devuelve los datos de la tabla ctb_tablas, clave STSRT.. */
	private function getStatusSrt($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT tb_codigo id, tb_descripcion detalle
					 FROM ctb_tablas
					WHERE tb_clave = 'STSRT'
						AND tb_codigo <> '0'
						AND tb_fechabaja IS NULL
						AND UPPER(tb_descripcion) LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<statusSrt><id>".$row["ID"]."</id><detalle>".$row["DETALLE"]."</detalle></statusSrt>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getStatusSrt.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* getTablasReferencia: Devuelve los datos de la tabla pasada como parámetro filtrada por el detalle pasado como parámetro.. */
	public function getTablasReferencia($tabla, $detalle) {
		switch ($tabla) {
			case "ACTIVIDAD":
				return $this->getActividad($tabla, $detalle);
				break;
			case "ART":
				return $this->getArt($tabla, $detalle);
				break;
			case "CIIU":
				return $this->getCiiu($tabla, $detalle);
				break;
			case "LOCALIDAD":
				return $this->getLocalidad($tabla, $detalle);
				break;
			case "PROVINCIA":
				return $this->getProvincia($tabla, $detalle);
				break;
			case "SECTOR":
				return $this->getSector($tabla, $detalle);
				break;
			case "STATUS_BCRA":
				return $this->getStatusBcra($tabla, $detalle);
				break;
			case "STATUS_SRT":
				return $this->getStatusSrt($tabla, $detalle);
				break;
			case "ZONA_GEOGRAFICA":
				return $this->getZonaGeografica($tabla, $detalle);
				break;
			default:
				$xml = '<?xml version="1.0" encoding="utf-8"?>';
				$xml.= "<tablasReferencia><error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>El nombre de la tabla es inválido.</mensaje></error></tablasReferencia>";
				return new soapval("return", "xsd:string", $xml);
		}
	}


	/* getZonaGeografica: Devuelve los datos de la tabla afi.azg_zonasgeograficas.. */
	private function getZonaGeografica($tabla, $detalle) {
		global $conn;

		try {
			$xml = "<tabla>".$tabla."</tabla><datos>";

			$params = array("detalle" => "%");
			if ($detalle != "")
				$params["detalle"] = "%".$detalle."%";

			$sql =
				"SELECT zg_id id, zg_descripcion detalle
					 FROM afi.azg_zonasgeograficas
					WHERE zg_fechabaja IS NULL
						AND UPPER(zg_descripcion) LIKE UPPER(:detalle)
			 ORDER BY 1";
			$stmt = DBExecSql($conn, $sql, $params);
			while ($row = DBGetQuery($stmt))
				$xml.= "<zonaGeografica><id>".$row["ID"]."</id><detalle>".$row["DETALLE"]."</detalle></zonaGeografica>";
			$xml.= "</datos>";
		}
		catch (Exception $e) {
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función getZonaGeografica.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><tablasReferencia>'.$xml."</tablasReferencia>";
		return new soapval("return", "xsd:string", $xml);
	}
}
?>