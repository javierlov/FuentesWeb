<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function getIdActividad($codigo) {
	global $conn;

	if ($codigo == "")
		return -1;
	else {
		$params = array(":codigo" => intval($codigo));
		$sql =
			"SELECT ac_id
				 FROM cac_actividad
				WHERE ac_codigo = TO_NUMBER(:codigo)";
		return ValorSql($sql, 0, $params, 0);
	}
}


validarSesion(isset($_SESSION["isAgenteComercial"]));
SetNumberFormatOracle();

$curs = null;
$params = array(":naumento" => zeroIfEmpty(str_replace(",", ".", $_REQUEST["a"])),
								":ncanttrabajador" => $_REQUEST["ct"],
								":ndescuento" => 0,
								":nidciiu" => getIdActividad($_REQUEST["c"]),
								":nmasasalarial" => str_replace(",", ".", $_REQUEST["ms"]));
$sql = "BEGIN webart.get_valor_online(:nidciiu, :nmasasalarial, :ncanttrabajador, :ndescuento, :naumento, :data); END;";
$stmt = DBExecSP($conn, $curs, $sql, $params);
$row = DBGetSP($curs);
?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('alicuotasFijo').value = '<?= str_replace(",", ".", $row["SUMAFIJA"])?>';
		getElementById('alicuotasMasaSalarial').value = '<?= str_replace(",", ".", $row["PORCVARIABLE"])?>';
		getElementById('aumento').value = '<?= $_REQUEST["a"]?>';
		getElementById('trabajadoresCantidad').value = '<?= $_REQUEST["ct"]?>';
		getElementById('trabajadoresMasaSalarial').value = '<?= $_REQUEST["ms"]?>';
		getElementById('trabajadoresMesAno').value = getElementById('periodo').value.substr(5, 2) + '/' + getElementById('periodo').value.substr(0, 4);
		getElementById('alicuotasCuotaInicial').value = ((getElementById('trabajadoresMasaSalarial').value * getElementById('alicuotasMasaSalarial').value / 100) + (getElementById('trabajadoresCantidad').value * getElementById('alicuotasFijo').value) + (getElementById('trabajadoresCantidad').value * 0.6)).toFixed(2);

		getElementById('alicuotasCuotaInicial').value = '$ ' + getElementById('alicuotasCuotaInicial').value;
		getElementById('alicuotasFijo').value = '$ ' + getElementById('alicuotasFijo').value;
		getElementById('alicuotasMasaSalarial').value = getElementById('alicuotasMasaSalarial').value + ' %';
		getElementById('trabajadoresMasaSalarial').value = '$ ' + getElementById('trabajadoresMasaSalarial').value;

		getElementById('spanAumento').innerHTML = '<?= $_REQUEST["a"]?>';
	}
</script>