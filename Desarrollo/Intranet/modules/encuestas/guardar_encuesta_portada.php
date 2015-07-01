<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();


require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


function validar() {
	$errores = false;

	if (!isset($_POST["itemEncuesta"])) {
		echo "<script type='text/javascript'>";
		echo "with (window.parent.document) {";
		echo "getElementById('divEncuestasContenidoValidacion').style.display = 'block';";
		echo "getElementById('divEncuestasFondoValidacion').style.display = 'block';";
		echo "}";
		echo "</script>";
		$errores = true;
	}

	return !$errores;
}


try {
	if (!validar())
		exit;


	$params = array(":idencuesta" => $_POST["idEncuesta"],
									":idopcion" => $_POST["itemEncuesta"],
									":idpregunta" => $_POST["idPregunta"],
									":usuario" => getUserId());
	$sql =
		"INSERT INTO rrhh.rrp_respuestaspreguntas (rp_fechaalta, rp_idencuesta, rp_idopcion, rp_idpregunta, rp_usuario)
																			 VALUES (SYSDATE, :idencuesta, :idopcion, :idpregunta, :usuario)";
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
	DBRollback($conn);
?>
	<script language="JavaScript" src="/js/functions.js"></script>
	<script type='text/javascript'>
		showError(unescape('<?= rawurlencode($e->getMessage())?>'), window.parent);
	</script>
<?
	exit;
}
?>
<script type="text/javascript">
	with (window.parent.document) {
		for (i=0; i<getElementById('formEncuesta').elements.length; i++)
			if (getElementById('formEncuesta').elements[i].type == 'radio')
				getElementById('formEncuesta').elements[i].disabled = true;

		with (getElementById('divEncuestaCantidadVotos')) {
			if (innerHTML == 'Sin votos')
				innerHTML = '1 voto';
			else if (innerHTML.indexOf('votos') == -1)
				innerHTML = '2 votos';
			else {
				arr = innerHTML.split(' ');
				innerHTML = (parseInt(arr[0]) + 1) + ' votos';
			}
		}

		getElementById('divEncuestaBottomRight').innerHTML = '<b>Gracias por participar!</b>';
	}
</script>