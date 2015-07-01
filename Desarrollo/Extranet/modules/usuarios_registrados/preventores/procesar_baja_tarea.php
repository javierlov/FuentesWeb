<?

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");

	
	$idvisita = $_POST["idTarea"];	
	
	$curs = null;
	$params = array(":usuario" => $_SESSION["usuario"], ":idvisita" => $idvisita);
	$sql = "BEGIN art.hys_prevencionweb.do_darbajadetalletarea (:usuario, :idvisita); END;";
	
	DBExecSP($conn, $curs, $sql, $params, false);

	DBCommit($conn);

?>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('btnGuardar').style.display = 'inline';
		getElementById('divProcesando').style.display = 'none';
	}
	window.parent.location.href = '/prevencion/Carga-Tareas';
</script>