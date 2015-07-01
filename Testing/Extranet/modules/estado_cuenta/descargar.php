<?
validarSesion(isset($_SESSION["isAgenteComercial"]));

// Chequeo que tenga permiso para entrar a ver el estado de cuenta..
$params = array(":id" => $_SESSION["idUsuario"]);
$sql =
	"SELECT uw_estadocuenta
		 FROM auw_usuarioweb
		WHERE uw_id = :id";
if (ValorSql($sql, "", $params) != 1) {
	echo "Usted no tiene habilitada esta opción.";
	exit;
}
?>
<script type="text/javascript">
	window.open('<?= getFile($_REQUEST["id"])?>', 'extranetWindow', 'location=0,resizable=1,scrollbars=yes');
	history.back();
</script>