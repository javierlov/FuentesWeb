<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT es_id id, es_nombre detalle
		 FROM (SELECT DISTINCT es_id, es_nombre
											FROM ctj_trabajador, aes_establecimiento, cre_relacionestablecimiento, crl_relacionlaboral
										 WHERE tj_id = rl_idtrabajador
											 AND rl_id = re_idrelacionlaboral
											 AND re_idestablecimiento = es_id
											 AND rl_contrato = art.afiliacion.get_contratovigente((SELECT em_cuit
																																							 FROM aem_empresa
																																							WHERE em_id = :idempresa), SYSDATE))
 ORDER BY 2";
$comboEstablecimiento = new Combo($sql, "establecimiento", $establecimiento);
$comboEstablecimiento->addParam(":idempresa", $_SESSION["idEmpresa"]);
?>