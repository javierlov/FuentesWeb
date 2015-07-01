<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/status_srt.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/cuit.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/net.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


class SolicitudCotizacion {
	private $datosSolicitud = array();
	private $datosUsuario = array();
	private $huboErrores = false;
	private $motivoNoAutocotizacion = "";		// Indica el motivo por el cual la solicitud no se autocotizó..
	private $verificaTecnica = false;		// Indica si la solicitud va para técnica si o si..


	/* completeData: Completa datos que no vienen o no carga el usuario que luego se usan para validar y/o dar de alta.. */
	private function completeData() {
		global $conn;

		// Verifico si la llamada se hace desde la web de Provincia ART o es externa..
		if (substr($this->datosSolicitud["token"], 0, 24) == "ORIGEN:WEB_PROVINCIA_ART") {
			$this->datosSolicitud["token"] = substr($this->datosSolicitud["token"], 24);		// Saco la cadena de origen para que solo quede el token..
			$this->datosSolicitud["origen"] = "W";		// Web Provincia ART..
		}
		else
			$this->datosSolicitud["origen"] = "E";		// Externa..

		// Leo los datos básicos del usuario..
		$params = array(":token" => $this->datosSolicitud["token"]);
		$sql =
			"SELECT uw_autocotizacion autocotizacion, uw_idcanal canal, uw_mail email, uw_mailavisoart emailavisoart, uw_identidad entidad, uw_id idusuario, uw_idsucursal sucursal,
							uw_usuario usuario, uw_idvendedor vendedor
				 FROM afi.auw_usuarioweb
				WHERE uw_token = :token";
		$stmt = DBExecSql($conn, $sql, $params);
		$this->datosUsuario = DBGetQuery($stmt);

		// Obtengo el status ante la SRT..
		$this->datosSolicitud["statusSrtAutomatico"] = new StatusSRT($this->datosSolicitud["cuit"], true, 3);
		if ($this->datosSolicitud["statusSrtAutomatico"]->getStatus() != -1)		// Si obtuve el status automaticamente, tomo ese como válido..
			$this->datosSolicitud["statusSrt"] = $this->datosSolicitud["statusSrtAutomatico"]->getStatus();

		// Seteo el código de vendedor..
		$codigoVendedor = NULL;
		if (($this->datosUsuario["ENTIDAD"] == 9003) and ($this->datosUsuario["VENDEDOR"] == "")) {
			$params = array(":identidad" => $this->datosUsuario["ENTIDAD"], ":vendedor" => $this->datosSolicitud["codigoVendedor"]);
			$sql =
				"SELECT 1
					 FROM xve_vendedor, xev_entidadvendedor
					WHERE ve_id = ev_idvendedor
						AND ev_identidad = :identidad
						AND ve_vendedor = :vendedor
						AND ev_fechabaja IS NULL
						AND ve_fechabaja IS NULL";
			if (ExisteSql($sql, $params, 0))
				$codigoVendedor = IIF(($this->datosSolicitud["codigoVendedor"] == ""), 0, $this->datosSolicitud["codigoVendedor"]);
		}
		$this->datosSolicitud["codigoVendedor"] = $codigoVendedor;

		// Guardo el id del vendedor..
		$this->datosSolicitud["idVendedor"] = NULL;
		if ($this->datosSolicitud["codigoVendedor"] != NULL) {
			$params = array("entidad" => $this->datosUsuario["ENTIDAD"], ":vendedor" => $this->datosSolicitud["codigoVendedor"]);
			$sql =
				"SELECT MAX(ve_id)
					 FROM xve_vendedor, xev_entidadvendedor
					WHERE ve_id = ev_idvendedor
						AND ev_identidad = :entidad
						AND ve_vendedor = :vendedor
						AND ev_fechabaja IS NULL
						AND ve_fechabaja IS NULL";
			$this->datosSolicitud["idVendedor"] = ValorSql($sql, "", $params, 0);
		}
		elseif ($this->datosUsuario["VENDEDOR"] != "")
			$this->datosSolicitud["idVendedor"] = $this->datosUsuario["VENDEDOR"];

		// Guardo el total de trabajadores..
		$this->datosSolicitud["totalTrabajadores"] = intval($this->datosSolicitud["totalTrabajadores1"]) + intval($this->datosSolicitud["totalTrabajadores2"]) + intval($this->datosSolicitud["totalTrabajadores3"]);

		// Guardo el total de la masa salarial..
		$this->datosSolicitud["totalMasaSalarial"] = floatval($this->datosSolicitud["masaSalarial1"]) + floatval($this->datosSolicitud["masaSalarial2"]) + floatval($this->datosSolicitud["masaSalarial3"]);

		// Formateo el período..
		if (!is_int(substr($this->datosSolicitud["periodo"], 4, 1)))
			$this->datosSolicitud["periodo"] = substr_replace($this->datosSolicitud["periodo"], "", 4, 1);

		// Calculo la suma fija y la variable..
		$this->datosSolicitud["calculoSumaFija"] = 0;
		$this->datosSolicitud["calculoVariable"] = 0;
		if ($this->datosSolicitud["datosCompetencia"] == "A") {
			$this->datosSolicitud["calculoSumaFija"] = 0.60;
			$this->datosSolicitud["calculoVariable"] = round(($this->datosSolicitud["soloPagoTotalMensual"] - ($this->datosSolicitud["totalTrabajadores"] * 0.6)) / $this->getMasaSalarialSinSac() * 100, 3);
		}

		if ($this->datosSolicitud["datosCompetencia"] == "S") {
			$this->datosSolicitud["calculoSumaFija"] = round($this->datosSolicitud["formulario931CostoFijo"] / $this->datosSolicitud["totalTrabajadores"], 2);

			if ($this->isMesConSAC())
				$this->datosSolicitud["calculoVariable"] = round($this->datosSolicitud["formulario931CostoVariable"] / 1.5 / $this->getMasaSalarialSinSac() * 100, 3);
			else
				$this->datosSolicitud["calculoVariable"] = round($this->datosSolicitud["formulario931CostoVariable"] / $this->getMasaSalarialSinSac() * 100, 3);
		}

		if ($this->datosSolicitud["datosCompetencia"] == "N") {
			$this->datosSolicitud["calculoSumaFija"] = $this->datosSolicitud["alicuotaCompetenciaSumaFija"];
			$this->datosSolicitud["calculoVariable"] = $this->datosSolicitud["alicuotaCompetenciaVariable"];
		}

		// Agrego una observacion si es una prestación especial..
		if ($this->datosSolicitud["prestacionesEspeciales"] == "S")
			$this->datosSolicitud["observaciones"] = "Cotizar con Otras Erogaciones, pasar a Comite II.  ".$this->datosSolicitud["observaciones"];

		// Guardo el tipo de solicitud que se está cargando..
		if ((($this->datosSolicitud["statusSrt"] == 5) or ($this->datosSolicitud["statusSrt"] == 6) or ($this->datosSolicitud["statusSrt"] == 7)) and ($this->datosSolicitud["artAnterior"] == 51))
			$this->datosSolicitud["tipoSolicitud"] = "R";		// Revisión de precio..
		else
			$this->datosSolicitud["tipoSolicitud"] = "C";		// Solicitud de cotización..
	}


	/* debug: Devuelve el mensaje pasado como parámetro en formato xml.. */
	private function debug($msg) {
		return '<?xml version="1.0" encoding="utf-8"?><msg>'.$msg.'</msg>';
	}


	/* getCodigoActividad: Dado un id de actividad devuelvo el código.. */
	private function getCodigoActividad($id) {
		if ($id == "")
			return NULL;
		else {
			$params = array(":id" => intval($id));
			$sql =
				"SELECT ac_codigo
					 FROM cac_actividad
					WHERE ac_id = TO_NUMBER(:id)
						AND ac_fechabaja IS NULL";
			return ValorSql($sql, 0, $params, 0);
		}
	}


	/* getMasaSalarialSinSac: Devuelve la masa salarial sin SAC.. */
	private function getMasaSalarialSinSac() {
		if ($this->isMesConSAC())
			return round(($this->datosSolicitud["totalMasaSalarial"] / 1.5), 2);
		else
			return round($this->datosSolicitud["totalMasaSalarial"], 2);
	}


	/* getResultadoMensualPorTrabajador: Devuelve el resultado mensual por trabajador.. */
	private function getResultadoMensualPorTrabajador() {
		if ($this->datosSolicitud["datosCompetencia"] == "") {
			return 0;
		}

		if ($this->datosSolicitud["datosCompetencia"] == "A") {
			if ($this->isMesConSAC())
				return round($this->datosSolicitud["soloPagoTotalMensual"] / 1.5 / $this->datosSolicitud["totalTrabajadores"], 2);
			else
				return round($this->datosSolicitud["soloPagoTotalMensual"] / $this->datosSolicitud["totalTrabajadores"], 2);
		}

		if ($this->datosSolicitud["datosCompetencia"] == "S") {
			if ($this->isMesConSAC())
				return round(($this->datosSolicitud["formulario931CostoFijo"] + ($this->datosSolicitud["formulario931CostoVariable"] / 1.5)) / $this->datosSolicitud["totalTrabajadores"], 2);
			else
				return round(($this->datosSolicitud["formulario931CostoFijo"] + $this->datosSolicitud["formulario931CostoVariable"]) / $this->datosSolicitud["totalTrabajadores"], 2);
		}

		if ($this->datosSolicitud["datosCompetencia"] == "N") {
			return round((($this->datosSolicitud["totalTrabajadores"] * $this->datosSolicitud["alicuotaCompetenciaSumaFija"]) + ($this->getMasaSalarialSinSac() * $this->datosSolicitud["alicuotaCompetenciaVariable"] / 100)) / $this->datosSolicitud["totalTrabajadores"], 2);
		}
	}


	/* insertEstablecimientos: Inserto los establecimientos.. */
	private function insertEstablecimientos() {
		global $conn;

		// Elimino los establecimientos temporales de el usuario actual..
		$params = array(":usualta" => substr("W_".$this->datosUsuario["USUARIO"], 0, 20));
		$sql =
			"DELETE FROM afi.aeu_establecimientos
						 WHERE eu_idsolicitud = -1
							 AND eu_usualta = :usualta
							 AND eu_usuarioweb = 'T'";
		DBExecSql($conn, $sql, $params, OCI_DEFAULT);

		// Guardo los establecimientos con id solicitud = -1, el id definitivo se pone desde el SP webart.set_solicitud_cotizacion ..
		foreach ($this->datosSolicitud["establecimientos"] as $value) {
			$params = array(":idactividad" => nullIfCero($value["idCiiu"]),
											":idlocalidad" => $value["idLocalidad"],
											":idsolicitud" => -1,
											":idtipoactividad" => nullIfCero($value["idActividad"]),
											":idzonageografica" => nullIfCero($value["idProvincia"]),
											":trabajadores" => nullIfCero($value["cantidadTrabajadores"]),
											":usualta" => substr("W_".$this->datosUsuario["USUARIO"], 0, 20));
			$sql =
				"INSERT INTO afi.aeu_establecimientos (eu_fechaalta, eu_id, eu_idactividad, eu_idlocalidad, eu_idsolicitud, eu_idtipoactividad, eu_idzonageografica, eu_trabajadores, eu_usualta, eu_usuarioweb)
																			 VALUES (SYSDATE, -1, :idactividad, :idlocalidad, :idsolicitud, :idtipoactividad, :idzonageografica, :trabajadores, :usualta, 'T')";
			DBExecSql($conn, $sql, $params, OCI_DEFAULT);
		}
	}


	/* isMesConSAC: Devuelve si el mes pasado como parámetro tiene SAC o no.. */
	private function isMesConSAC() {
		$mes = substr($this->datosSolicitud["periodo"], 5, 2);

		return (($mes == 6) or ($mes == 12));
	}


	/* saveSolicitud: Guarda la solicitud de cotización.. */
	public function saveSolicitud($datosSolicitud) {
		global $conn;

		try {
			$curs = null;
			$params = array(":actividadreal" => $this->datosSolicitud["actividadReal"],
											":art" => nullIfCero($this->datosSolicitud["artAnterior"]),
											":autocotizacion" => $this->datosUsuario["AUTOCOTIZACION"],
											":bajapordeuda" => "F",
											":calculosumafija" => floatval($this->datosSolicitud["calculoSumaFija"]),
											":calculovariable" => floatval($this->datosSolicitud["calculoVariable"]),
											":canal" => $this->datosUsuario["CANAL"],
											":chuboerrores" => IIF($this->huboErrores, "S", "N"),
											":ciiu1" => $this->datosSolicitud["ciiu1"],
											":ciiu2" => $this->datosSolicitud["ciiu2"],
											":ciiu3" => $this->datosSolicitud["ciiu3"],
											":contacto" => $this->datosSolicitud["contacto"],
											":costofijoform931" => floatval($this->datosSolicitud["formulario931CostoFijo"]),
											":costovariableform931" => floatval($this->datosSolicitud["formulario931CostoVariable"]),
											":cprestacionesespeciales" => $this->datosSolicitud["prestacionesEspeciales"],
											":cstatussrtautomatico" => $this->datosSolicitud["statusSrtAutomatico"]->getStatus(),
											":csuscribepolizarc" => $this->datosSolicitud["suscribePolizaRC"],
											":ctiposolicitud" => $this->datosSolicitud["tipoSolicitud"],
											":cuit" => $this->datosSolicitud["cuit"],
											":cverificatecnica" => IIF($this->verificaTecnica, "S", "N"),
											":datoscompetencia" => $this->datosSolicitud["datosCompetencia"],
											":edadpromedio" => intval($this->datosSolicitud["edadPromedio"]),
											":email" => $this->datosSolicitud["email"],
											":entidad" => $this->datosUsuario["ENTIDAD"],
											":establecimientos" => intval($this->datosSolicitud["cantidadEstablecimientos"]),
											":holding" => nullIfCero($this->datosSolicitud["holding"]),
											":idusuario" => $this->datosUsuario["IDUSUARIO"],
											":masasalarial1" => floatval($this->datosSolicitud["masaSalarial1"]),
											":masasalarial2" => floatval($this->datosSolicitud["masaSalarial2"]),
											":masasalarial3" => floatval($this->datosSolicitud["masaSalarial3"]),
											":masasalarialsinsac" => floatval($this->getMasaSalarialSinSac()),
											":naumento" => 0,
											":naumentotope" => NULL,
											":ndescuento" => 0,
											":ndescuentotope" => NULL,
											":nidzonageografica" => $this->datosSolicitud["zonaGeografica"],
											":nsumaaseguradarc" => $this->datosSolicitud["sumaAseguradaRC"],
											":observaciones" => substr($this->datosSolicitud["observaciones"], 0, 2048),
											":periodo" => $this->datosSolicitud["periodo"],
											":razonsocial" => $this->datosSolicitud["razonSocial"],
											":resultadomensualportrabajador" => floatval($this->getResultadoMensualPorTrabajador()),
											":sector" => nullIfCero($this->datosSolicitud["sector"]),
											":smotivonoautocotizacion" => $this->motivoNoAutocotizacion,
											":solopagototalmensual" => floatval($this->datosSolicitud["soloPagoTotalMensual"]),
											":statusbcra" => $this->datosSolicitud["statusBcra"],
											":statussrt" => nullIsEmpty($this->datosSolicitud["statusSrt"]),
											":sucursal" => nullIfCero($this->datosUsuario["SUCURSAL"]),
											":sumafijacompetencia" => floatval($this->datosSolicitud["alicuotaCompetenciaSumaFija"]),
											":telefono" => $this->datosSolicitud["telefono"],
											":totaltrabajadores" => intval($this->datosSolicitud["totalTrabajadores"]),
											":totaltrabajadores1" => intval($this->datosSolicitud["totalTrabajadores1"]),
											":totaltrabajadores2" => intval($this->datosSolicitud["totalTrabajadores2"]),
											":totaltrabajadores3" => intval($this->datosSolicitud["totalTrabajadores3"]),
											":usuario" => $this->datosUsuario["USUARIO"],
											":variablecompetencia" => floatval($this->datosSolicitud["alicuotaCompetenciaVariable"]),
											":vendedor" => $this->datosSolicitud["idVendedor"]);
			$sql = "BEGIN webart.set_solicitud_cotizacion(:chuboerrores, :cprestacionesespeciales, :csuscribepolizarc, :cstatussrtautomatico, :ctiposolicitud, :cverificatecnica, :ciiu1, :ciiu2, :ciiu3, :sumafijacompetencia, :variablecompetencia, :naumento, :naumentotope, :calculosumafija, :calculovariable, :totaltrabajadores, :ndescuento, :ndescuentotope, :establecimientos, :entidad, :costofijoform931, :costovariableform931, :art, :holding, :nidzonageografica, :masasalarialsinsac, :resultadomensualportrabajador, :solopagototalmensual, :totaltrabajadores1, :totaltrabajadores2, :totaltrabajadores3, :edadpromedio, :masasalarial1, :masasalarial2, :masasalarial3, :nsumaaseguradarc, :autocotizacion, :idusuario, :canal, :sucursal, :vendedor, :bajapordeuda, :cuit, :smotivonoautocotizacion, :razonsocial, :sector, :actividadreal, :contacto, :email, :observaciones, :periodo, :telefono, :statusbcra, :statussrt, :datoscompetencia, :usuario); END;";
			$stmt = DBExecSP($conn, $curs, $sql, $params, false, 0);

			if ($this->datosSolicitud["tipoSolicitud"] == "C") {		// Solicitud de cotización..
				$params = array(":cuit" => $this->datosSolicitud["cuit"]);
				$sql =
					"SELECT sc_id, sc_nrosolicitud
						 FROM asc_solicitudcotizacion
						WHERE sc_cuit = :cuit
				 ORDER BY 1 DESC";
				$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				$row = DBGetQuery($stmt);

				$id = $row["SC_ID"];
				$nroSol = $row["SC_NROSOLICITUD"];

				$params = array(":id" => $id);
				$sql =
					"SELECT sc_finalportrabajador
						 FROM asc_solicitudcotizacion
						WHERE sc_id = :id";
				$autoCotizacion = (ValorSql($sql, "", $params, 0) != "");
			
				$modulo = "C";
				$txtRevision = " ";
			}
			else {		// Solicitud de revisión..
				$params = array(":cuit" => $this->datosSolicitud["cuit"]);
				$sql =
					"SELECT sr_id, sr_nrosolicitud
						 FROM asr_solicitudreafiliacion
						WHERE sr_cuit = :cuit
				 ORDER BY 1 DESC";
				$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);
				$row = DBGetQuery($stmt);

				$id = $row["SR_ID"];
				$nroSol = $row["SR_NROSOLICITUD"];

				$autoCotizacion = ($this->datosUsuario["AUTOCOTIZACION"] == 1);
				$modulo = "R";
				$txtRevision = " (Revisión de precio) ";
			}

			// Guardo el alta en la tabla de auditoría..
			$params = array(":idsolicitud" => $id, ":idusualta" => $this->datosUsuario["IDUSUARIO"], ":tiposolicitud" => $this->datosSolicitud["tipoSolicitud"]);
			$sql =
				"INSERT INTO web.wau_auditoriawebservice
										 (au_fechaalta, au_idsolicitud, au_idusualta, au_tiposolicitud)
							VALUES (SYSDATE, :idsolicitud, :idusualta, :tiposolicitud)";
			$stmt = DBExecSql($conn, $sql, $params, OCI_DEFAULT);

			// Obtengo el estado de la solicitud..
			if ($this->datosSolicitud["tipoSolicitud"] == "C") {		// Solicitud de cotización..
				$params = array(":id" => $id, ":tipo" => $this->datosSolicitud["tipoSolicitud"]);
				$sql = "SELECT art.webart.get_estado_solicitud(:id) FROM DUAL";
				$estado = ValorSql($sql, "", $params, 0);
			}
			else {		// Revisión de precio..
				$params = array(":id" => $id, ":tipo" => $this->datosSolicitud["tipoSolicitud"]);
				$sql =
					"SELECT tb_descripcion
						 FROM asr_solicitudreafiliacion, ctb_tablas
						WHERE est.tb_codigo(+) = sr_estadosolicitud
							AND est.tb_clave(+) = 'ACOES'
							AND sr_id = :id";
				$estado = ValorSql($sql, "", $params, 0);
			}

			// Devuelvo los valores..
			$curs = null;
			$params = array(":cdatosenformatostring" => "F", ":nrosolicitud" => $nroSol, ":ssumar_ffep" => "T");
			$sql = "BEGIN art.cotizacion.get_valor_carta(:nrosolicitud, :data, :ssumar_ffep, :cdatosenformatostring); END;";
			$stmt = DBExecSP($conn, $curs, $sql, $params);
			$rowValorFinal = DBGetSP($curs);
			$xml = "<resultado><estado>".$estado."</estado>";
			if ($rowValorFinal["PORCVARIABLE"] != "")
				$xml.= "<porcentajeVariable>".$rowValorFinal["PORCVARIABLE"]."</porcentajeVariable><sumaFija>0".$rowValorFinal["SUMAFIJA"]."</sumaFija><cuotaInicialResultante>".$rowValorFinal["COSTOMENSUAL"]."</cuotaInicialResultante>";
			$xml.= "</resultado>";

			// Preparo el envío del e-mail..
			$params = array(":idsolicitud" => $id, ":tipo" => $this->datosSolicitud["tipoSolicitud"]);
			$sql = "SELECT art.cotizacion.get_mailnotificacomercial(:tipo, :idsolicitud) FROM DUAL";
			$emailTo = ValorSql($sql, "", $params, 0);
			if ($emailTo == "") {
				$emailTo = "evila@provart.com.ar";
				$subject = "[Error] - Cotización WEB Nº ".$nroSol.$txtRevision;
			}
			else {
				if ($autoCotizacion)
					$subject = "Cotización WEB Nº ".$nroSol.$txtRevision;
				elseif (($this->datosSolicitud["statusSrt"] == 6) or ($this->datosSolicitud["statusSrt"] == 7) or
								($this->datosSolicitud["statusBcra"] == 4) or ($this->datosSolicitud["statusBcra"] == 5) or ($this->datosSolicitud["statusBcra"] == 6))
					$subject = "Revisar solicitud de cotización Nº ".$nroSol.$txtRevision;
				else
					$subject = "Aviso: Solicitud de cotización Nº ".$nroSol.$txtRevision." pasa a Suscripción";
			}

			$params = array(":id" => $this->datosUsuario["CANAL"]);
			$sql = "SELECT ca_codigo || ' - ' || ca_descripcion FROM aca_canal WHERE ca_id = :id";
			$canal = ValorSql($sql, "", $params, 0);

			$params = array(":id" => $this->datosUsuario["ENTIDAD"]);
			$sql = "SELECT en_codbanco || ' - ' || en_nombre FROM xen_entidad WHERE en_id = :id";
			$entidad = ValorSql($sql, "", $params, 0);

			if ($autoCotizacion) {
				$body = "<html><body><p>Se ha cargado una cotización desde la Web".$txtRevision."</p>";
				$body.= "<p>Nº de Solicitud: <b>".$nroSol."</b></p>";
				$body.= "<p>Canal: <b>".$canal."</b></p>";
				$body.= "<p>Entidad: <b>".$entidad."</b></p>";
				$body.= "<p>e-Mail de contacto: <b>".$this->datosUsuario["EMAIL"]."</b></p>";
				$body.= "</body></html>";
			}
			else {
				$body = "<html><body><p>Tiene una solicitud de cotización".$txtRevision."del Canal ".$canal.", Entidad ".$entidad.", usuario: ".$this->datosUsuario["USUARIO"]."</p>";

				if (($this->datosSolicitud["statusSrt"] == 6) or ($this->datosSolicitud["statusSrt"] == 7) or
						($this->datosSolicitud["statusBcra"] == 4) or ($this->datosSolicitud["statusBcra"] == 5) or ($this->datosSolicitud["statusBcra"] == 6))
					$body.= "<p>Para ser revisada.</p>";
				else
					$body.= "<p>Para ser revisada directamente por Suscripción.</p>";

				$body.= "</body></html>";
			}

			SendEmail($body, "Web", $subject, array($emailTo), array(), array(), "H", (($this->datosSolicitud["tipoSolicitud"] = "R")?"ASR":"ASC"), $id, $this->datosUsuario["EMAIL"]);
			DBCommit($conn);
		}
		catch (Exception $e) {
			DBRollback($conn);
//			$xml = "<error>".$e->getMessage()."</error>";
			$xml = "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función saveSolicitud.</mensaje></error>";
		}

		$xml = '<?xml version="1.0" encoding="utf-8"?><solicitudCotizacion>'.$xml."</solicitudCotizacion>";
		return new soapval("return", "xsd:string", $xml);
	}


	/* sendEmailSituacionAfiliatoria: Envía un e-mail al agente comercial asociado a la entidad que cargó la solicitud de cotización.. */
	private function sendEmailSituacionAfiliatoria($msgError) {
		global $conn;

		$emailTo = $this->datosUsuario["EMAILAVISOART"];
		$subject = "Empresa con situación afiliatoria complicada";
		$body = getFileContent($_SERVER["DOCUMENT_ROOT"]."/modules/solicitud_cotizacion/plantillas/email_situacion_afiliatoria.html");

		$params = array(":id" => $this->datosUsuario["CANAL"]);
		$sql = "SELECT ca_codigo || ' - ' || ca_descripcion FROM aca_canal WHERE ca_id = :id";
		$body = str_replace("@canal@", ValorSql($sql, "", $params, 0), $body);

		$params = array(":id" => $this->datosUsuario["ENTIDAD"]);
		$sql = "SELECT en_codbanco || ' - ' || en_nombre FROM xen_entidad WHERE en_id = :id";
		$body = str_replace("@entidad@", ValorSql($sql, "", $params, 0), $body);

		if ($_SESSION["sucursal"] != "") {
			$params = array(":id" => $this->datosUsuario["SUCURSAL"]);
			$sql = "SELECT su_codsucursal || ' - ' || su_descripcion FROM asu_sucursal WHERE su_id = :id";
			$body = str_replace("@sucursal@", ValorSql($sql, "", $params, 0), $body);
		}
		else
			$body = str_replace("@sucursal@", "", $body);

		$params = array(":id" => $this->datosSolicitud["artAnterior"]);
		$sql = "SELECT ar_nombre FROM aar_art WHERE ar_id = :id";
		$body = str_replace("@artactual@", ValorSql($sql, "", $params, 0), $body);

		$vendedor = "";
		if ($this->datosSolicitud["codigoVendedor"] != NULL) {
			$params = array(":vendedor" => IIF(($this->datosSolicitud["codigoVendedor"] == ""), "0", $this->datosSolicitud["codigoVendedor"]));
			$sql = "SELECT ve_vendedor || ' - ' || ve_nombre FROM xve_vendedor WHERE ve_vendedor = :vendedor";
			$vendedor = ValorSql($sql, "", $params, 0);
		}
		elseif ($this->datosUsuario["VENDEDOR"] == "") {
			$params = array(":identidad" => $this->datosUsuario["ENTIDAD"]);
			$sql =
				"SELECT ve_vendedor || ' - ' || ve_nombre
					 FROM xev_entidadvendedor, xve_vendedor
					WHERE ve_id = ev_idvendedor
						AND ev_fechabaja IS NULL
						AND ve_fechabaja IS NULL
						AND ve_vendedor = '0'
						AND ev_identidad = :identidad";
			$vendedor = ValorSql($sql, "", $params, 0);
		}
		$body = str_replace("@codigovendedor@", $vendedor, $body);

		$params = array(":codigo" => $this->datosSolicitud["statusBcra"]);
		$sql = "SELECT tb_descripcion FROM ctb_tablas WHERE tb_clave = 'STBCR' AND tb_codigo = :codigo";
		$body = str_replace("@statusbcra@", ValorSql($sql, "", $params, 0), $body);

		$params = array(":codigo" => $this->datosSolicitud["statusSrt"]);
		$sql = "SELECT tb_descripcion FROM ctb_tablas WHERE tb_clave = 'STSRT' AND tb_codigo = :codigo";
		$body = str_replace("@statussrt@", ValorSql($sql, "", $params, 0), $body);

		$body = str_replace("@cantidadestablecimientos@", $this->datosSolicitud["cantidadEstablecimientos"], $body);
		$body = str_replace("@ciiu1@", $this->getCodigoActividad($this->datosSolicitud["ciiu1"]), $body);
		$body = str_replace("@contacto@", $this->datosSolicitud["contacto"], $body);
		$body = str_replace("@cuit@", $this->datosSolicitud["cuit"], $body);
		$body = str_replace("@edadpromedio@", $this->datosSolicitud["edadPromedio"], $body);
		$body = str_replace("@email@", $this->datosSolicitud["email"], $body);
		$body = str_replace("@emailComercializador@", $this->datosUsuario["EMAIL"], $body);
		$body = str_replace("@error@", $msgError, $body);
		$body = str_replace("@masasalarial1@", $this->datosSolicitud["masaSalarial1"], $body);
		$body = str_replace("@periodo@", $this->datosSolicitud["periodo"], $body);
		$body = str_replace("@razonsocial@", $this->datosSolicitud["razonSocial"], $body);
		$body = str_replace("@resultadomensualtrabajador@", $this->getResultadoMensualPorTrabajador(), $body);
		$body = str_replace("@sector@", $this->datosSolicitud["sector"], $body);
		$body = str_replace("@sumafija@", $this->datosSolicitud["calculoSumaFija"], $body);
		$body = str_replace("@telefono@", $this->datosSolicitud["telefono"], $body);
		$body = str_replace("@trabajadores1@", $this->datosSolicitud["totalTrabajadores1"], $body);
		$body = str_replace("@usuario@", $this->datosUsuario["USUARIO"], $body);
		$body = str_replace("@variable@", $this->datosSolicitud["calculoVariable"], $body);

		// Agrego los datos del CIIU 2..
		$str = "";
		if ($this->datosSolicitud["ciiu2"] != "") {
			$str.= "Cod. CIIU (2): ".$this->getCodigoActividad($this->datosSolicitud["ciiu2"])."<br />";
			$str.= "Cant. Trabajadores (2): ".$this->datosSolicitud["totalTrabajadores2"]."<br />";
			$str.= "Masa Salarial (2): ".$this->datosSolicitud["masaSalarial2"]."<br />";
		}
		$body = str_replace("@ciiu2@", $str, $body);

		// Agrego los datos del CIIU 3..
		$str = "";
		if ($this->datosSolicitud["ciiu3"] != "") {
			$str.= "Cod. CIIU (3): ".$this->getCodigoActividad($this->datosSolicitud["ciiu3"])."<br />";
			$str.= "Cant. Trabajadores (3): ".$this->datosSolicitud["totalTrabajadores3"]."<br />";
			$str.= "Masa Salarial (3): ".$this->datosSolicitud["masaSalarial3"]."<br />";
		}
		$body = str_replace("@ciiu3@", $str, $body);

		// Agrego los datos de la competencia..
		$str = "";
		switch ($this->datosSolicitud["datosCompetencia"]) {
			case "":
				$str = "Sin Dato<br />";
				break;
			case "A":
				$str = "Solo pago total mensual: ".$this->datosSolicitud["soloPagoTotalMensual"]."<br />";
				break;
			case "N":
				$str = "Formulario 931 Costo Fijo: ".$this->datosSolicitud["alicuotaCompetenciaSumaFija"]."<br />";
				$str.= "Formulario 931 Costo Variable: ".$this->datosSolicitud["alicuotaCompetenciaVariable"]."<br />";
				break;
			case "S":
				$str = "Alícuota Competencia Costo Fijo: ".$this->datosSolicitud["formulario931CostoFijo"]."<br />";
				$str.= "Alícuota Competencia Costo Variable: ".$this->datosSolicitud["formulario931CostoVariable"]."<br />";
				break;
		}
		$body = str_replace("@datoscompetencia@", $str, $body);

		// Agrego los datos de los establecimientos..
		$i = 1;
		$str = "";
		foreach ($this->datosSolicitud["establecimientos"] as $value) {
			$params = array(":id" => $value["idActividad"]);
			$sql =
				"SELECT ta_detalle
					 FROM afi.ata_tipoactividad
					WHERE ta_id = :id";
			$actividad = ValorSql($sql, "", $params, 0);

			$params = array(":id" => $value["idCiiu"]);
			$sql =
				"SELECT ac_codigo || ' - ' || ac_descripcion
					 FROM cac_actividad
					WHERE ac_id = :id";
			$ciiu = ValorSql($sql, "", $params, 0);

			$params = array(":id" => $value["idLocalidad"]);
			$sql =
				"SELECT cp_localidad
					 FROM art.ccp_codigopostal
					WHERE cp_id = :id";
			$localidad = ValorSql($sql, "", $params, 0);

			$params = array(":id" => $value["idProvincia"]);
			$sql =
				"SELECT zg_descripcion
					 FROM afi.azg_zonasgeograficas
					WHERE zg_id = :id";
			$provincia = ValorSql($sql, "", $params, 0);

			$str.= $i."= ".$provincia.", " .$localidad.", ".$actividad.", ".$ciiu.", ".$value["cantidadTrabajadores"]."<br />";
			$i++;
		}
		$body = str_replace("@establecimientos@", $str, $body);

		SendEmail($body, "Web", $subject, array($emailTo), array(), array(), "H");
	}


	/* SPValidation: Valida los datos contra la base de datos.. */
	private function SPValidation(&$advertencias, &$errores) {
		global $conn;

		try {
			$curs = null;
			$params = array(":cbajapordeuda" => "F",
											":ccampanaF931" => "S",
											":cdatoscompetencia" => $this->datosSolicitud["datosCompetencia"],
											":cprestacionesespeciales" => $this->datosSolicitud["prestacionesEspeciales"],
											":csuscribepolizarc" => $this->datosSolicitud["suscribePolizaRC"],
											":naumento" => -1,
											":ncantidadtrabajadores" => $this->datosSolicitud["totalTrabajadores"],
											":ndescuento" => -1,
											":nedadpromedio" => IIF(($this->datosSolicitud["edadPromedio"] == ""), -1, intval($this->datosSolicitud["edadPromedio"])),
											":nestablecimientos" => intval($this->datosSolicitud["cantidadEstablecimientos"]),
											":nidartanterior" => nullIsEmpty($this->datosSolicitud["artAnterior"]),
											":nidcanal" => $this->datosUsuario["CANAL"],
											":nidciiu" => nullIfCero($this->datosSolicitud["ciiu1"]),
											":nidciiu2" => nullIfCero($this->datosSolicitud["ciiu2"]),
											":nidciiu3" => nullIfCero($this->datosSolicitud["ciiu3"]),
											":nidentidad" => $this->datosUsuario["ENTIDAD"],
											":nidholding" => nullIfCero($this->datosSolicitud["holding"]),
											":nidsector" => nullIfCero($this->datosSolicitud["sector"]),
											":nidstatusbcra" => nullIsEmpty($this->datosSolicitud["statusBcra"]),
											":nidstatussrt" => nullIsEmpty($this->datosSolicitud["statusSrt"]),
											":nidvendedor" => $this->datosSolicitud["idVendedor"],
											":nidzonageografica" => $this->datosSolicitud["zonaGeografica"],
											":nmasasalarial" => $this->getMasaSalarialSinSac(),
											":nresultadomensualtrabajador" => $this->getResultadoMensualPorTrabajador(),
											":nsumaaseguradarc" => nullIfCero($this->datosSolicitud["sumaAseguradaRC"]),
											":sactividadreal" => $this->datosSolicitud["actividadReal"],
											":scontacto" => $this->datosSolicitud["contacto"],
											":scuit" => $this->datosSolicitud["cuit"],
											":semail" => $this->datosSolicitud["email"],
											":speriodo" => $this->datosSolicitud["periodo"],
											":srazonsocial" => $this->datosSolicitud["razonSocial"],
											":susualta" => $this->datosUsuario["USUARIO"]);
			$sql = "BEGIN webart.get_validacion_solicitud(:data, :cbajapordeuda, :ccampanaF931, :cdatoscompetencia, :cprestacionesespeciales, :csuscribepolizarc, :naumento, :ncantidadtrabajadores, :ndescuento, :nedadpromedio, :nestablecimientos, :nidartanterior, :nidcanal, :nidciiu, :nidciiu2, :nidciiu3, :nidentidad, :nidholding, :nidsector, :nidstatusbcra, :nidstatussrt, :nidvendedor, :nidzonageografica, :nmasasalarial, :nresultadomensualtrabajador, :nsumaaseguradarc, :sactividadreal, :scontacto, :scuit, :semail, :speriodo, :srazonsocial, :susualta); END;";
			$stmt = DBExecSP($conn, $curs, $sql, $params);
			$row = DBGetSP($curs);

			$this->huboErrores = ((intval($row["NUMEROERROR"]) != 0) and ($row["ADVERTENCIA"] != "A"));
			$this->motivoNoAutocotizacion = $row["MOTIVONOAUTOCOTIZACION"];
			$this->verificaTecnica = (($row["VERIFICATECNICA"] == "S") or ($this->datosUsuario["AUTOCOTIZACION"] == 0));		// Si el query devuelve "S" o si no autocotiza, la mando a técnica..

			if (trim($row["ADVERTENCIA"]) != "") {
				$advertencias.= "<advertencia><mensaje>".$row["ERROR"]."</mensaje></advertencia>";
			}

			if ($this->datosSolicitud["origen"] == "W") {		// Si el origen es la Web de Provincia ART..
				if ($row["NUMEROERROR"] == -3) {		// Si el error es -3 indica que se tienen que mostrar los datos de la campaña F931 del año 2012..
					$errores.= "<error><codigo>-3</codigo>";
					$errores.= "<mensaje>setCampanaF931</mensaje></error>";
				}
				elseif ($row["NUMEROERROR"] == -1) {		// Si el error es -1 indica que se le tiene que permitir al usuario cargar un descuento..
					$errores.= "<error><codigo>-1</codigo>";
					$errores.= "<mensaje>mostrarDescuento</mensaje></error>";
				}
				elseif ($row["NUMEROERROR"] == -2) {		// Si el error es -2 indica que se le tiene que permitir al usuario cargar un aumento..
					$errores.= "<error><codigo>-2</codigo>";
					$errores.= "<mensaje>mostrarAumento</mensaje></error>";
				}
				elseif ($row["NUMEROERROR"] == -12) {		// Si el error es -12 indica que se le tiene que permitir al usuario cargar un aumento y un descuento..
					$errores.= "<error><codigo>-12</codigo>";
					$errores.= "<mensaje>mostrarAumentoYDescuento</mensaje></error>";
				}
				elseif (intval($row["NUMEROERROR"]) != "0") {
					if (($row["NUMEROERROR"] >= 1) and ($row["NUMEROERROR"] <= 13))		// Son los números de error de la vieja función get_validacion..
						sendEmailSituacionAfiliatoria($row["NUMEROERROR"]." - ".$row["ERROR"]);
					$errores.= "<error><codigo>".$row["NUMEROERROR"]."</codigo>";
					$errores.= "<mensaje>".$row["ERROR"]."</mensaje></error>";
				}
			}
		}
		catch (Exception $e) {
//			$errores.= "<error>".$e->getMessage()."</error>";
			$errores.= "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función SPValidation.</mensaje></error>";
		}
	}


	/* validatePeriodo: Valida que el período sea del tipo AAAAMM.. */
	private function validatePeriodo() {
		return ((strlen($this->datosSolicitud["periodo"]) == 6) and (validarEntero($this->datosSolicitud["periodo"])));
	}


	/* validateSolicitud: Valida que los datos pasados para cargar la solicitud sean correctos o no.. */
	public function validateSolicitud($datosSolicitud) {
		global $conn;

		$this->datosSolicitud = $datosSolicitud;

		$advertencias = "";
		$errores = "";

		try {
			$this->completeData();
			$this->insertEstablecimientos();

			// Validación 1..
			if (!validarCuit($datosSolicitud["cuit"])) {
				$errores.= "<error><codigo>1</codigo>";
				$errores.= "<mensaje>La C.U.I.T. es inválida.</mensaje></error>";
				throw new Exception("ERROR FATAL: La C.U.I.T. es inválida.");		// Este error lo lanzo porque con una CUIT inválida no puedo ni validar nada..
			}

			// Validación 2 - Control de CUIT reservado..
			$params = array(":cuit" => $datosSolicitud["cuit"]);
			$sql =
				"SELECT ac_descripcion actividad, ca_descripcion canal, en_nombre entidad, ru_idcanal, ru_identidad, ru_idvendedor, ru_observaciones, ve_nombre vendedor
					 FROM aru_reservacuit ru, aca_canal, cac_actividad, xen_entidad, xve_vendedor
					WHERE ru_idcanal = ca_id(+)
						AND ru_idactividad = ac_id(+)
						AND ru_identidad = en_id(+)
						AND ru_idvendedor = ve_id(+)
						AND ru_cuit = :cuit
						AND ru_fechabaja IS NULL
						AND actualdate BETWEEN ru_fechadesde AND ru_fechahasta";
			$stmt = DBExecSql($conn, $sql, $params);
			if (DBGetRecordCount($stmt) > 0) {
				$row = DBGetQuery($stmt);

				$distintoVendedor = (($row["VENDEDOR"] != "") and ($row["RU_IDVENDEDOR"] != $this->datosUsuario["VENDEDOR"]));
				if (($row["RU_IDCANAL"] != $this->datosUsuario["CANAL"]) or ($row["RU_IDENTIDAD"] != $this->datosUsuario["ENTIDAD"]) or ($distintoVendedor)) {
					$errores.= "<error><codigo>2</codigo>";
					$errores.= "<mensaje>Esta C.U.I.T. se encuentra reservada por otro usuario, por favor comuníquese con su Ejecutivo de Provincia ART.</mensaje>";
					$errores.= "<observaciones>".$row["RU_OBSERVACIONES"]."</observaciones></error>";
				}
			}

			// Validación 3 - Control de vigencia de la Solicitud de Cotización..
			$params = array(":cuit" => $datosSolicitud["cuit"]);
			$sql =
				"SELECT ca_descripcion
					 FROM asc_solicitudcotizacion, aca_canal
					WHERE ca_id = sc_canal
						AND (actualdate - TRUNC(sc_fechasolicitud)) < 30
						AND sc_estado NOT IN('05', '07', '08', '09', '18.0', '18.1', '18.2', '18.3')
						AND sc_cuit = :cuit";
			$stmt = DBExecSql($conn, $sql, $params);
			if (DBGetRecordCount($stmt) > 0) {
				$errores.= "<error><codigo>3</codigo>";
				$errores.= "<mensaje>Ya existe una solicitud para esta C.U.I.T., por favor comuníquese con su Ejecutivo de Provincia ART.</mensaje></error>";
			}

			// Validación 4 - Control de vigencia de la Revision de Precio..
			$params = array(":cuit" => $datosSolicitud["cuit"]);
			$sql =
				"SELECT ca_descripcion
					 FROM art.asr_solicitudreafiliacion 
					 JOIN aca_canal ON ca_id = sr_idcanal
					WHERE (art.actualdate - TRUNC(sr_fechaalta)) < 30
						AND sr_estadosolicitud NOT IN('05', '18.0', '18.1', '18.2', '18.3')
						AND sr_cuit = :cuit";
			$stmt = DBExecSql($conn, $sql, $params);
			if (DBGetRecordCount($stmt) > 0) {
				$errores.= "<error><codigo>4</codigo>";
				$errores.= "<mensaje>Ya existe una solicitud para esta C.U.I.T., por favor comuníquese con su Ejecutivo de Provincia ART.</mensaje></error>";
			}

			// Validación 5..
			$params = array(":cuit" => $datosSolicitud["cuit"]);
			$sql = "SELECT afiliacion.check_cobertura(:cuit) FROM DUAL";
			if (ValorSql($sql, "", $params) == 1) {
				$errores.= "<error><codigo>5</codigo>";
				$errores.= "<mensaje>Esta empresa ya tiene un contrato activo con esta aseguradora.</mensaje></error>";
			}

			// Validación 6 - Que no puedan colocar la CUIT de la Provincia ART..
			if ($datosSolicitud["cuit"] == "30688254090") {
				$errores.= "<error><codigo>6</codigo>";
				$errores.= "<mensaje>Debe registrarse la C.U.I.T. del empleador (si la C.U.I.T. se registra erróneamente la solicitud no tiene validez).</mensaje></error>";
			}

			// Validación 7 - Valida el status ante la SRT..
			$params = array(":cuit" => $datosSolicitud["cuit"]);
			$sql =
				"SELECT 1
					 FROM srt.sem_empresas e JOIN srt.shv_historialvigencias v ON v.hv_id = art.cotizacion.get_idultimavigencia(em_cuit)
					WHERE CASE
								WHEN v.hv_idoperaciondesde = 10888 THEN ADD_MONTHS(v.hv_vigenciadesde, 10) + 11
								ELSE ADD_MONTHS(v.hv_vigenciadesde, 6)
								END <= SYSDATE
						AND e.em_cuit = :cuit";
			if (($this->datosSolicitud["statusSrtAutomatico"]->getStatus() != -1) and ($this->datosSolicitud["statusSrtAutomatico"]->getStatus() != 1) and (!ExisteSql($sql, $params, 0))) {
				$errores.= "<error><codigo>7</codigo>";
				$errores.= "<mensaje>Esta C.U.I.T. no puede ser cotizada por la vigencia en la actual ART, por favor comuníquese con su Ejecutivo de Provincia ART.</mensaje></error>";
			}

			// Validación 8..
			if ($datosSolicitud["statusSrt"] < 1) {
				$errores.= "<error><codigo>8</codigo>";
				$errores.= "<mensaje>El status ante la SRT debe ser mayor a cero (0).</mensaje></error>";
			}

			// Validación 9..
			$codigoVendedor = NULL;
			if (($this->datosUsuario["ENTIDAD"] == 9003) and ($this->datosUsuario["VENDEDOR"] == "")) {
				if ($this->datosSolicitud["codigoVendedor"] == NULL) {
					$errores.= "<error><codigo>9</codigo>";
					$errores.= "<mensaje>El código de vendedor es inválido.</mensaje></error>";
				}
			}

			// Validación 10..
			if ($this->datosSolicitud["totalTrabajadores"] == 0) {
				$errores.= "<error><codigo>10</codigo>";
				$errores.= "<mensaje>El total de trabajadores debe ser mayor a cero (0).</mensaje></error>";
			}

			// Validación 11..
			if ($this->datosSolicitud["totalMasaSalarial"] == 0) {
				$errores.= "<error><codigo>11</codigo>";
				$errores.= "<mensaje>El total de la masa salarial debe ser mayor a cero (0).</mensaje></error>";
			}

			// Validación 12..
			if (!$this->validatePeriodo()) {
				$errores.= "<error><codigo>12</codigo>";
				$errores.= "<mensaje>El período es inválido, el formato debe ser AAAA/MM.</mensaje></error>";
			}

			$this->SPValidation($advertencias, $errores);
		}
		catch (Exception $e) {
//			$errores.= "<error>".$e->getMessage()."</error>";
			$errores.= "<error><fecha>".date("d/m/Y")."</fecha><hora>".date("H:i:s")."</hora><mensaje>Ocurrió un error inesperado en la función validarSolicitud.</mensaje></error>";
		}

		if (($advertencias == "") and ($errores == ""))
			return "";
		else {
			$xml = '<?xml version="1.0" encoding="utf-8"?><solicitudCotizacion>';
			if ($advertencias != "")
				$xml.= "<advertencias>".$advertencias."</advertencias>";
			if ($errores != "")
				$xml.= "<errores>".$errores."</errores>";
			$xml.= "</solicitudCotizacion>";
			return new soapval("return", "xsd:string", $xml);
		}
	}
}
?>