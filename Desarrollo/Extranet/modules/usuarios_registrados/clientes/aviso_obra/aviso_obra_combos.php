<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'CARGO'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboCargoHYS = new Combo($sql, "cargoHYS", $rowHYS["CARGO"]);
$comboCargoHYS->setDisabled(!$camposEnabled);

$sql =
	"SELECT 'F' id, 'Femenino' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'M', 'Masculino'
		 FROM DUAL";
$comboSexo = new Combo($sql, "sexo", $row["SEXORESP"]);
$comboSexo->setDisabled(!$camposDatosResponsableEnabled);

$sql =
	"SELECT 'F' id, 'Femenino' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'M', 'Masculino'
		 FROM DUAL";
$comboSexoHYS = new Combo($sql, "sexoHYS", $rowHYS["SEXO"]);
$comboSexoHYS->setDisabled(!$camposEnabled);

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'TDOC'
			AND tb_codigo <> '0'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboTipoDocumento = new Combo($sql, "tipoDocumento", $row["TIPODOCUMENTORESP"]);
$comboTipoDocumento->setDisabled(!$camposDatosResponsableEnabled);

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'TDOC'
			AND tb_codigo <> '0'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboTipoDocumentoHYS = new Combo($sql, "tipoDocumentoHYS", $rowHYS["TIPODOCUMENTO"]);
$comboTipoDocumentoHYS->setDisabled(!$camposEnabled);

$sql =
	"SELECT tt_id id, tt_descripcion detalle
		 FROM att_tipotelefono
 ORDER BY 2";
$comboTipoTelefono = new Combo($sql, "tipoTelefono", $row["TIPOTELEFONORESP"]);
$comboTipoTelefono->setDisabled(!$camposDatosResponsableEnabled);
?>