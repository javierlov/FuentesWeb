<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");


if (!isset($_SESSION["idUsuario"])) {
?>
	<script type="text/javascript">
		window.location.href = '/modules/admin_users_web/login.php';
	</script>
<?
	exit;
}

$body =
	"Usuario: ".$_REQUEST["usuario"]."\n".
	"e-Mail: ".$_REQUEST["email"];
$subject = "Error en la carga de usuarios de Adecco";

sendEmail($body, "Web", $subject, array("alapaco@provart.com.ar,evila@provart.com.ar"), array(), array());
?>
<script type="text/javascript">
	alert('Momentaneamente el sistema no puede guardar los datos, pero se envió un e-mail a Provincia ART \n para que los datos sean cargados manualmente. Los mismos estarán cargados dentro de la próxima hora.');
	history.go(-3);
</script>