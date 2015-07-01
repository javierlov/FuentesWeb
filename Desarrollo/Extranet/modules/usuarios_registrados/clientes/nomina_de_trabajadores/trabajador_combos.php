<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'ESTAD'
			AND tb_codigo <> '0'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboEstadoCivil = new Combo($sql, "estadoCivil", ((!$isAlta)?$row["ESTADOCIVILID"]:-1));

$sql =
	"SELECT na_id id, na_descripcion detalle
		 FROM cna_nacionalidad
		WHERE na_fechabaja IS NULL
 ORDER BY 2";
$comboNacionalidad = new Combo($sql, "nacionalidad", ((!$isAlta)?$row["NACIONALIDADID"]:-1));
$comboNacionalidad->setOnChange("cambiaNacionalidad(this.value, document)");

$sql =
	"SELECT 'F' id, 'Femenino' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'M', 'Masculino'
		 FROM DUAL";
$comboSexo = new Combo($sql, "sexo", ((!$isAlta)?$row["SEXO"]:-1));

$sql =
	"SELECT mc_id id, mc_descripcion detalle
		 FROM cmc_modalidadcontratacion
		WHERE mc_fechabaja IS NULL
 ORDER BY 2";
$comboTipoContrato = new Combo($sql, "tipoContrato", ((!$isAlta)?$row["MODALIDADCONTRATACIONID"]:-1));
?>