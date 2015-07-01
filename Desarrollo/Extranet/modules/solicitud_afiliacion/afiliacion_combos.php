<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'CARGO'
			AND tb_especial2(+) = 'SOLO_FIRMANTE'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboCargoEmpleador = new Combo($sql, "cargoEmpleador", ($alta)?-1:$row["SA_CARGO_TITULAR"]);
$comboCargoEmpleador->setClass("select2");

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'CARGO'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboCargoResponsable = new Combo($sql, "cargoResponsable", ($alta)?-1:$row["SA_CARGO"]);
$comboCargoResponsable->setClass("select2");

if (formaJuridicaFija($row["CUIT"]))
	$sql =
		"SELECT tb_codigo id, tb_descripcion detalle
			 FROM ctb_tablas
			WHERE tb_clave = 'FJURI'
				AND tb_especial1 = 'SOLAFI'
				AND tb_fechabaja IS NULL
	 ORDER BY 2";
else
	$sql =
		"SELECT tb_codigo id, tb_descripcion detalle
			 FROM ctb_tablas
			WHERE tb_clave = 'FJURI'
				AND tb_especial1 = 'SOLAFI'
				AND tb_codigo NOT IN ('009')
				AND tb_fechabaja IS NULL
	 ORDER BY 2";
$comboFormaJuridica = new Combo($sql, "formaJuridica", (formaJuridicaFija($row["CUIT"]))?"009":(($alta)?-1:$row["SA_FORMAJ"]));
$comboFormaJuridica->setClass("select2");
$comboFormaJuridica->setDisabled(formaJuridicaFija($row["CUIT"]));
$comboFormaJuridica->setOnChange("document.getElementById('formaJuridicaTmp').value = this.value;");

$sql =
	"SELECT 1
		 FROM DUAL
		WHERE 1 = 2";
$comboLocalidadCombo = new Combo($sql, "localidadCombo", ($alta)?-1:$row["SA_PROVINCIA"]);
$comboLocalidadCombo->setClass("select2");
$comboLocalidadCombo->setFirstItem("- INGRESE EL CÓDIGO POSTAL Y LA PROVINCIA -");
$comboLocalidadCombo->setOnChange("cambiarLocalidad(this.value)");

$sql =
	"SELECT pv_codigo id, pv_descripcion detalle
		 FROM cpv_provincias
		WHERE pv_fechabaja IS NULL
 ORDER BY 2";
$comboProvincia = new Combo($sql, "provincia", ($alta)?-1:$row["SA_PROVINCIA"]);
$comboProvincia->setClass("select2");
$comboProvincia->setOnChange("cargarComboLocalidad()");

$sql =
	"SELECT 'F' id, 'Femenino' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'M', 'Masculino'
		 FROM DUAL
 ORDER BY 2";
$comboSexoEmpleador = new Combo($sql, "sexoEmpleador", ($alta)?-1:$row["SA_SEXO_TITULAR"]);
$comboSexoEmpleador->setClass("select2");

$sql =
	"SELECT 'F' id, 'Femenino' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'M', 'Masculino'
		 FROM DUAL
 ORDER BY 2";
$comboSexoResponsable = new Combo($sql, "sexoResponsable", ($alta)?-1:$row["SA_SEXO_CONT"]);
$comboSexoResponsable->setClass("select2");

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'TARJE'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboTarjetaCredito = new Combo($sql, "tarjetaCredito", (isset($rowRC["PR_ORIGENPAGO"]))?($alta)?-1:$rowRC["PR_ORIGENPAGO"]:-1);
$comboTarjetaCredito->setClass("select2");

$sql =
	"SELECT 1
		 FROM DUAL
		WHERE 1 = 2";
$comboTarjetaCreditoFalso = new Combo($sql, "tarjetaCreditoFalso");
$comboTarjetaCreditoFalso->setClass("select2");
$comboTarjetaCreditoFalso->setDisabled(true);
?>