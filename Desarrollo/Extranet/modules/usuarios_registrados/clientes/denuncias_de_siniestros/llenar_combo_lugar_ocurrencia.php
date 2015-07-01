<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");
session_start();


$params = array(":contrato" => $_SESSION["contrato"]);
$sql =
	"SELECT 1
		 FROM SIN.see_empresaestableci3ro
		WHERE ee_fechabaja IS NULL
			AND ee_contrato = :contrato";
$tieneEstablecimientosDeTercero = existeSql($sql, $params);

switch ($_REQUEST["v"]) {
	case 1:
		$sql =
			"SELECT id, detalle
				FROM (SELECT 1 id, 'En el puesto de trabajo' detalle
							  FROM DUAL
					  UNION ALL
							SELECT 2, 'Desplazamiento en día laboral'
							  FROM DUAL
					  UNION ALL
							SELECT 4, 'Otro puesto de trabajo'
							  FROM DUAL
					  UNION ALL
							SELECT 5, 'Otros (detallar)'
							  FROM DUAL)
		 ORDER BY 1";
		break;
	case 2:
		$sql =
			"SELECT id, detalle
				FROM (SELECT 3 id, 'Al ir/volver del trabajo' detalle
							  FROM DUAL
				  UNION ALL
							SELECT 5, 'Otros (detallar)'
							  FROM DUAL)
		 ORDER BY 1";
		break;
	case 3:
		$sql =
			"SELECT id, detalle
				FROM (SELECT 1 id, 'En el puesto de trabajo' detalle
							  FROM DUAL
					  UNION ALL
							SELECT 4, 'Otro puesto de trabajo'
							  FROM DUAL
					  UNION ALL
							SELECT 5, 'Otros (detallar)'
							  FROM DUAL)
		 ORDER BY 1";
		break;
	default:
		$sql =
			"SELECT id, detalle
				 FROM (SELECT 1 id, 'En el puesto de trabajo' detalle
								 FROM DUAL)
		 ORDER BY 1";
}
$comboLugarOcurrencia = new Combo($sql, "lugarOcurrencia");
$comboLugarOcurrencia->setFirstItem("- SIN DEFINIR -");
$comboLugarOcurrencia->setOnBlur("copiarLugarOcurrencia()");
$comboLugarOcurrencia->setOnChange("cambiaLugarOcurrencia(".(($tieneEstablecimientosDeTercero)?"true":"false").", this.value)");
?>
<script type="text/javascript">
	window.parent.document.getElementById('lugarOcurrencia').parentNode.innerHTML = '<?= $comboLugarOcurrencia->draw();?>';
</script>