<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	 "SELECT en_id id, en_codbanco || ' - ' || en_nombre detalle, TO_NUMBER(en_codbanco)
			FROM xen_entidad
		 WHERE en_id = :identidad
		 UNION
		SELECT en_id id, en_codbanco || ' - ' || en_nombre detalle, TO_NUMBER(en_codbanco)
			FROM xgo_granorganizador, xen_entidad
		 WHERE go_fechabaja IS NULL
			 AND go_identidad = en_id
START WITH go_identorganizador = :identidad
CONNECT BY NOCYCLE PRIOR go_identidad = go_identorganizador
  ORDER BY 3";
$comboEntidad = new Combo($sql, "entidad", $_SESSION["entidad"]);
$comboEntidad->addParam(":identidad", $_SESSION["entidadReal"]);
$comboEntidad->setAddFirstItem(false);
$comboEntidad->setFocus(true);
?>