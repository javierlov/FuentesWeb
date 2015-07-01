<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM (SELECT *
						 FROM art.ctb_tablas
						WHERE tb_clave = 'E_DEU'
							AND tb_codigo > '0'
				 ORDER BY tb_codigo DESC)
		WHERE tb_codigo >= (SELECT MIN(sc_periododist)
													FROM osc_saldocontable
												 WHERE sc_contrato = :contrato)
			 OR :contrato IS NULL";
$comboPeriodo = new Combo($sql, "periodo");
$comboPeriodo->addParam(":contrato", $_SESSION["contrato"]);
$comboPeriodo->setFocus(true);
?>