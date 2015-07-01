<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


class StatusSRT {
	private $cuit = "";
	private $delay;
	private $idArtAnterior = -1;
	private $idZonaGeografica = -1;
	private $provincia = "";
	private $status = -1;
	private $processCompleted = false;


	/* __construct: Contructor.. */
	public function __construct($cuit, $automatic = true, $delay = 60) {
		$this->cuit = $cuit;
		$this->delay = $delay;

		set_time_limit($this->delay + 15);

		if ($automatic)
			$this->getStatusSrt($cuit);
	}


	/* checkGeneration: Comprueba si el servicio guardó el status en la tabla tmp.tss_statussrt.. */
	private function checkGeneration() {
		$params = array(":cuit" => $this->cuit);
		$sql =
			"SELECT ss_generar
				 FROM tmp.tss_statussrt
				WHERE ss_cuit = :cuit";
		return (valorSql($sql, "", $params) == "F");
	}


	/* clearResults: Blanquea los resultados.. */
	private function clearResults() {
		$this->idArtAnterior = -1;
		$this->idZonaGeografica = -1;
		$this->provincia = "";
		$this->status = -1;
	}


	/* getIdArtAnterior: Devuelve el ID de la ART anterior.. */
	public function getIdArtAnterior() {
		return $this->idArtAnterior;
	}


	/* getidZonaGeografica: Devuelve el ID de la zona geográfica.. */
	public function getidZonaGeografica() {
		return $this->idZonaGeografica;
	}


	/* getProcessCompleted: Devuelve si el proceso de búsqueda del status terminó o no.. */
	public function getProcessCompleted() {
		return $this->processCompleted;
	}


	/* getProvincia: Devuelve la provincia.. */
	public function getProvincia() {
		return $this->provincia;
	}


	/* getStatus: Devuelve el status ante la SRT.. */
	public function getStatus() {
		return $this->status;
	}


	/* getStatusSrt: Devuelve el status ante la SRT.. */
	public function getStatusSrt($cuit) {
		$this->cuit = $cuit;
		$this->processCompleted = false;

		$this->requestStatusSrt();

		// Loopeo a la espera de que el servicio obtenga el status y lo guarde en la tabla tmp.tss_statussrt..
		$i = 1;
		$statusObtenido = true;
		while (!$this->checkGeneration()) {
			if ($i >= $this->delay) {
				$statusObtenido = false;
				break;
			}

			$i++;
			sleep(1);
		}

		$this->processCompleted = true;

		if ($statusObtenido)
			$this->setResults();
		else
			$this->clearResults();
	}


	/* requestStatusSrt: Guarda en la tabla tmp.tss_statussrt el cuit para que el servicio que busca el status ante la SRT lo busque.. */
	private function requestStatusSrt() {
		global $conn;

		$params = array(":cuit" => $this->cuit);
		$sql =
			"SELECT 1
				 FROM tmp.tss_statussrt
				WHERE ss_cuit = :cuit";
		if (!existeSql($sql, $params)) {
			$params = array(":cuit" => $this->cuit);
			$sql =
				"INSERT INTO tmp.tss_statussrt (ss_cuit, ss_fechahorainicio)
																VALUES (:cuit, SYSDATE)";
			DBExecSql($conn, $sql, $params);
		}
		else {
			$params = array(":cuit" => $this->cuit);
			$sql =
				"UPDATE tmp.tss_statussrt
						SET ss_fechahorainicio = SYSDATE,
								ss_fechahorafin = NULL,
								ss_generar = 'T',
								ss_idartanterior = NULL,
								ss_provincia = NULL,
								ss_status = NULL
					WHERE ss_cuit = :cuit";
			DBExecSql($conn, $sql, $params);
		}
	}


	/* setDelay: Setea el tiempo (en segundos) que se debe esperar hasta que el servicio obtenga el status.. */
	public function setDelay($value) {
		$this->delay = $value;
	}


	/* setResults: Guarda los resultados en las propiedades del objeto.. */
	private function setResults() {
		global $conn;

		$params = array(":cuit" => $this->cuit);
		$sql =
			"SELECT ss_idartanterior, ss_provincia, ss_status
				 FROM tmp.tss_statussrt
				WHERE ss_cuit = :cuit";
		$stmt = DBExecSql($conn, $sql, $params);
		$row = DBGetQuery($stmt);

		$this->idArtAnterior = $row["SS_IDARTANTERIOR"];
		$this->provincia = $row["SS_PROVINCIA"];
		$this->status = $row["SS_STATUS"];

		$params = array(":idprovincia" => $this->provincia);
		$sql =
			"SELECT zg_id
				 FROM afi.azg_zonasgeograficas
				WHERE zg_idprovincia = :idprovincia";
		$this->idZonaGeografica = valorSql($sql, "-1", $params);
	}
}
?>