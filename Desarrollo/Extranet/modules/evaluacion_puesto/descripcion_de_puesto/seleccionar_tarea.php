<?php
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT pd_comolohizo, pd_paraquelohace, pd_quehace
		 FROM rrhh.dpd_descripcion
		WHERE pd_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<html>
	<head>
		<script type="text/javascript">
			with (window.parent.parent.document) {
				getElementById('queHace').value = unescape('<?= rawurlencode($row["PD_QUEHACE"])?>');
				getElementById('paraQueLoHace').value = unescape('<?= rawurlencode($row["PD_PARAQUELOHACE"])?>');
				getElementById('comoSeSabeLoQueHizo').value = unescape('<?= rawurlencode($row["PD_COMOLOHIZO"])?>');
				getElementById('btnAgregarAccion').style.display = 'none';
				getElementById('btnModificarAccion').style.display = 'inline';
				getElementById('btnCancelarModificacion').style.display = 'inline';
				getElementById('btnEliminarRegistro').style.display = 'inline';
				getElementById('tmpId').value = <?= $_REQUEST["id"]?>;
			}
		</script>
	</head>
</html>