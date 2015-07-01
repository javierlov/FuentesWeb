<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT se_id id, se_nombre detalle
		 FROM use_usuarios
		WHERE (se_fechabaja IS NULL OR se_fechabaja >=(SYSDATE - 90))
			AND se_usuariogenerico = 'N'
 ORDER BY se_buscanombre";
$comboUsuario = new Combo($sql, "usuario", ($isAlta)?-1:$row["HN_IDUSUARIO"]);
$comboUsuario->setFocus(true);

$sql =
	"SELECT 'A' id, 'Ingreso' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'B', 'Egreso' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'M', 'Pase de Sector' detalle
		 FROM DUAL
 ORDER BY 2";
$comboTipoMovimiento = new Combo($sql, "tipoMovimiento", ($isAlta)?-1:$row["HN_TIPOMOVIMIENTO"]);
$comboTipoMovimiento->setOnChange("cambiarTipoMovimiento()");

$sql =
	"SELECT se1.se_id id,
					se1.se_descripcion || (SELECT DECODE(se1.se_descripcion, se2.se_descripcion, NULL, ' (' || se2.se_descripcion
														 || (SELECT DECODE(se2.se_descripcion, se3.se_descripcion, NULL, ' - ' || se3.se_descripcion)
																	 FROM computos.cse_sector se3
																	WHERE se3.se_nivel = 2
																		AND se3.se_id = se2.se_idsectorpadre) || ')')
																	 FROM computos.cse_sector se2
																	WHERE se2.se_nivel = 3
																		AND se2.se_fechabaja IS NULL
																		AND se2.se_id = se1.se_idsectorpadre) detalle
		 FROM computos.cse_sector se1
		WHERE se1.se_nivel = 4
 ORDER BY 2";
$comboSectorDesde = new Combo($sql, "sectorDesde", ($isAlta)?-1:$row["HN_IDSECTORDESDE"]);

$sql =
	"SELECT se1.se_id id,
					se1.se_descripcion || (SELECT DECODE(se1.se_descripcion, se2.se_descripcion, NULL, ' (' || se2.se_descripcion
														 || (SELECT DECODE(se2.se_descripcion, se3.se_descripcion, NULL, ' - ' || se3.se_descripcion)
																	 FROM computos.cse_sector se3
																	WHERE se3.se_nivel = 2
																		AND se3.se_id = se2.se_idsectorpadre) || ')')
																	 FROM computos.cse_sector se2
																	WHERE se2.se_nivel = 3
																		AND se2.se_fechabaja IS NULL
																		AND se2.se_id = se1.se_idsectorpadre) detalle
		 FROM computos.cse_sector se1
		WHERE se1.se_nivel = 4
 ORDER BY 2";
$comboSectorHasta = new Combo($sql, "sectorHasta", ($isAlta)?-1:$row["HN_IDSECTORHASTA"]);
?>