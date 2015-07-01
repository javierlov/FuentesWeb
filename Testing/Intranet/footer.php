<script>
	function envioAvisoGarrahan() {
		if (confirm('Está a punto de informar a R.R.H.H. que el tacho del Garrahan está lleno.\n\n¿ Confirma el envío ?'))
			OpenWindow('/modules/garrahan/enviar_aviso.php', 'ProvartPopup', 280, 80, 'no', 'no');
	}
</script>
<?
$params = array(":usuario" => GetWindowsLoginName());
$sql =
	"SELECT 1
		 FROM use_usuarios
		WHERE se_usuario IN ('ALAPACO', 'FPEREZ')
			AND UPPER(se_usuario) = UPPER(:usuario)";
$isComputos = ExisteSql($sql, $params);

$params = array(":idusuario" => GetUserID());
$sql =
	"SELECT en_id
		 FROM rrhh.ren_encuestas, rrhh.rue_usuariosxencuestas
		WHERE en_id = ue_idencuesta
			AND en_activa = 'T'
			AND en_fechabaja IS NULL
			AND ue_idusuario = :idusuario";
$encuestaActiva = ValorSql($sql, -1, $params);

if ($isComputos) {
?>
	<img alt="Ir al Módulo de Permisos" border="0" id="botonPermisos" src="images/mostrar_permisos.png" onClick="showPermisosWindow()" />
<?
}
?>
<a href="/index.php?pageid=37"><img border="0" src="images/footer/descargables.jpg" style="margin-right:55px;" /></a>
<?
if ($encuestaActiva > -1) {
	$params = array(":id" => $encuestaActiva);
	$sql =
		"SELECT en_titulo
			 FROM rrhh.ren_encuestas
			WHERE en_id = :id";
	$titulo = ValorSql($sql, "", $params);

	$params = array(":idencuesta" => $encuestaActiva, ":usuario" => GetUserID());
	$sql =
		"SELECT 1
			 FROM rrhh.rrp_respuestaspreguntas
			WHERE rp_idencuesta = :idencuesta
				AND rp_usuario = :usuario";
	if (ExisteSql($sql, $params))
		$img = "encuesta.jpg";
	else
		$img = "encuesta_sin_votar.jpg";
?>
	<a href="/index.php?pageid=50"><img alt="Encuesta <?= $titulo?>" border="0" src="images/footer/<?= $img?>" style="margin-right:35px;" /></a>
<?
}
?>
<a href="/index.php?pageid=38"><img border="0" src="/images/footer/sistemas.jpg" style="margin-right:65px;" /></a>
<a href="http://www.provinciart.com.ar" target="_blank"><img border="0" src="/images/footer/www_intra.jpg" style="margin-right:35px;" /></a>
<a href="http://intranetgb/gbapro/" target="_blank"><img border="0" src="/images/footer/logo_grupo.gif" /></a>