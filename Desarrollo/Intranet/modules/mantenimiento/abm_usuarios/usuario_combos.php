<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'USCAR'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboCargo = new Combo($sql, "cargo", $row["SE_CARGO"]);

$sql =
	"SELECT el_id id, el_nombre detalle
		 FROM del_delegacion
		WHERE el_fechabaja IS NULL
 ORDER BY 2";
$comboDelegacion = new Combo($sql, "delegacion", $row["SE_DELEGACION"]);
$comboDelegacion->setOnChange("cambiarDelegacion(this.value)");

$sql =
	"SELECT es_id id, es_descripcion || ' - ' || es_calle || ' ' || es_numero detalle
		 FROM art.des_delegacionsede
 ORDER BY 2";
$comboEdificio = new Combo($sql, "edificio", $row["SE_IDDELEGACIONSEDE"]);

$sql =
	"SELECT ru_id id, ru_detalle detalle
		 FROM comunes.cru_relacionlaboralusuario
		WHERE ru_fechabaja IS NULL
 ORDER BY 2";
$comboRelacionLaboral = new Combo($sql, "relacionLaboral", ($row["SE_CONTRATO"] == 0)?-1:$row["SE_CONTRATO"]);

$sql =
	"SELECT se_usuario id, se_nombre detalle
		 FROM use_usuarios
		WHERE se_fechabaja IS NULL
			AND se_usuariogenerico = 'N'
 ORDER BY 2";
$comboRespondeA = new Combo($sql, "respondeA", $row["SE_RESPONDEA"]);

$sql =
	"SELECT se1.se_id id, se1.se_descripcion || (SELECT DECODE(se1.se_descripcion, se2.se_descripcion, NULL, ' (' || se2.se_descripcion || (SELECT DECODE(se2.se_descripcion, se3.se_descripcion, NULL, ' - ' || se3.se_descripcion)
																																																																						FROM computos.cse_sector se3
																																																																					 WHERE se3.se_nivel = 2
																																																																						 AND se3.se_id = se2.se_idsectorpadre) || ')')
																								 FROM computos.cse_sector se2
																								WHERE se2.se_nivel = 3
																									AND se2.se_fechabaja IS NULL
																									AND se2.se_id = se1.se_idsectorpadre) || DECODE(se1.se_fechabaja, NULL, '', ' -BAJA- ') detalle
		 FROM computos.cse_sector se1
		WHERE se1.se_nivel = 4
 ORDER BY 2";
$comboSector = new Combo($sql, "sector", $row["SE_IDSECTOR"]);
?>