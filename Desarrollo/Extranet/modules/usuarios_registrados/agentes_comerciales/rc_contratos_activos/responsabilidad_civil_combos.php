<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");


$sql =
	"SELECT fa_id id, fa_firmante detalle
		 FROM afa_firmanteafi
		WHERE fa_fechabaja IS NULL
 ORDER BY 2";
$comboCargo = new Combo($sql, "cargo", $rowPoliza["PR_IDCARACTERFIRMA"]);
$comboCargo->setDisabled($existe);

$sql =
	"SELECT 'F' id, 'Femenino' detalle
		 FROM DUAL
UNION ALL
	 SELECT 'M', 'Masculino'
		 FROM DUAL
 ORDER BY 2";
$comboSexo = new Combo($sql, "sexo", $rowPoliza["PR_SEXO"]);
$comboSexo->setDisabled($existe);

$sql =
	"SELECT tb_codigo id, tb_descripcion detalle
		 FROM ctb_tablas
		WHERE tb_clave = 'TARJE'
			AND tb_fechabaja IS NULL
 ORDER BY 2";
$comboTarjetaCredito = new Combo($sql, "tarjetaCredito", $rowPoliza["PR_ORIGENPAGO"]);

$sql =
	"SELECT 1
		 FROM DUAL
		WHERE 1 = 2";
$comboTarjetaCreditoFalso = new Combo($sql, "tarjetaCreditoFalso");
$comboTarjetaCreditoFalso->setDisabled(true);
?>