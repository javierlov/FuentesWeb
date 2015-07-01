<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT id, detalle
		 FROM (SELECT DISTINCT ii_periodo id, art.utiles.nombredemes(SUBSTR(ii_periodo, 5, 2)) || ' ' || SUBSTR(ii_periodo, 1, 4) detalle
											FROM web.wii_informesiys
										 WHERE ii_contrato = :contrato
											 AND ii_fechabaja IS NULL
									ORDER BY ii_periodo DESC)";
$comboPeriodo = new Combo($sql, "periodo");
$comboPeriodo->addParam(":contrato", $_SESSION["contrato"]);
$comboPeriodo->setFocus(true);
$comboPeriodo->setOnChange("submitForm()");
?>