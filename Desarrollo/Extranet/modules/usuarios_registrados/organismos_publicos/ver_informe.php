<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


$params = array(":transaccion" => $_REQUEST["id"]);
$sql =
	"SELECT '".DATA_ORGANISMOS_PUBLICOS_RESUMEN."\' || LPAD(SUBSTR(no_contrato, -3), 3, '0') || '\' || np_periodo || '\' || em_cuit || '_' || no_contrato || '_' || no_secuencia || '.pdf'
		 FROM emi.iwe_usuariowebemision, afi.aem_empresa, afi.aco_contrato, emi.ino_nota, emi.inp_notacontratoperiodo
		WHERE no_id = np_idnota
			AND we_contrato = no_contrato
			AND em_id = co_idempresa
			AND co_contrato = no_contrato
			AND np_fechabaja IS NULL
			AND no_fechabaja IS NULL
			AND no_transaccion = :transaccion";
$file = ValorSql($sql, "", $params);
$existeArchivo = file_exists($file);
?>
<script type="text/javascript">
<?
if ($existeArchivo) {
?>
	window.open('<?= getFile($file)?>', 'intranetWindow');
<?
}
else {
?>
	alert('Archivo disponible en 48hs.');
<?
}
?>
</script>