<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT rs_id id, rs_descripcion detalle
		 FROM hys.hrs_relevrepresentacion
		WHERE rs_fechabaja IS NULL
 ORDER BY 2";
if (isset($idRepresentacion1))
	$comboRepresentacion1 = new Combo($sql, "representacion1", $idRepresentacion1);
if (isset($idRepresentacion2))
	$comboRepresentacion2 = new Combo($sql, "representacion2", $idRepresentacion2);
if (isset($idRepresentacion3))
	$comboRepresentacion3 = new Combo($sql, "representacion3", $idRepresentacion3);

$sql =
	"SELECT 'C' id, 'Contratado' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'P' id, 'Propio' detalle
		 FROM DUAL
 ORDER BY 2";
if (isset($tipo2))
	$comboTipo2 = new Combo($sql, "tipo2", $tipo2);
if (isset($tipo3))
	$comboTipo3 = new Combo($sql, "tipo3", $tipo3);
?>