<script type="text/javascript">
	divWin = null;

	function mostrarVentanaCambioEntidad() {
		divWin = dhtmlwindow.open('divBox', 'iframe', '/test.php', 'Aviso', 'width=680px,height=120px,left=240px,top=240px,resize=1,scrolling=1');
		divWin.load('iframe', '/functions/cambiar_entidad.php', 'Cambiar Entidad en la navegación');
		divWin.show();
	}
</script>
<map name="mapTop">
	<area coords="670, 74, 681, 86" href="/" shape="rect" title="Inicio" />
	<area coords="690, 73, 704, 87" href="/mapa-sitio" shape="rect" title="Mapa del Sitio" />
	<area coords="710, 75, 723, 84" href="/contacto" shape="rect" title="Contacto" />
	<area coords="41, 24, 206, 78" href="/" shape="rect" />
</map>
<img border="0" id="barraCelesteHomePage" src="/images/barraCeleste.jpg" />
<img border="0" id="encabezadoHomePage" src="/images/top.jpg" usemap="#mapTop" />
<a href="/acceso-exclusivo-usuarios-registrados"><img border="0" id="btnLogin" src="/images/login.jpg" /></a>
<?
if ($servidorContingenciaActivo) {
?>
	<img border="0" id="imgContingencia" src="/images/contingencia.png" />
<?
}

if (isset($_SESSION["login"])) {
	$data = "";
	$totalEntidades = 0;

	if (isset($_SESSION["isAgenteComercial"])) {
		$params = array(":identidad" => $_SESSION["entidadReal"]);
		$sql =
			"SELECT COUNT(*)
				 FROM (SELECT en_id
								 FROM xen_entidad
								WHERE en_id = :identidad
								UNION
							 SELECT en_id
								 FROM xen_entidad, xgo_granorganizador
								WHERE en_id = go_identidad
									AND go_identorganizador = :identidad)";
		$totalEntidades = valorSql($sql, "0", $params);

		$sql = "SELECT ca_codigo || ' - ' || ca_descripcion || ' (' || ca_id || ')' FROM aca_canal WHERE ca_id = :id";
		$params = array(":id" => $_SESSION["canal"]);
		$data = "CANAL: ".valorSql($sql, "", $params);

		$sql = "SELECT en_codbanco || ' - ' || en_nombre || ' (' || en_id || ')' FROM xen_entidad WHERE en_id = :id";
		$params = array(":id" => $_SESSION["entidad"]);
		$data.= "&#13;ENTIDAD: ".valorSql($sql, "", $params);

		if ($_SESSION["sucursal"] != "") {
			$sql = "SELECT su_codsucursal || ' - ' || su_descripcion || ' (' || su_id || ')' FROM asu_sucursal WHERE su_id = :id";
			$params = array(":id" => $_SESSION["sucursal"]);
			$data.= "&#13;SUCURSAL: ".valorSql($sql, "", $params);
		}

		if ($_SESSION["vendedor"] != "") {
			$sql = "SELECT ve_vendedor || ' - ' || ve_nombre || ' (' || ve_id || ')' FROM xve_vendedor WHERE ve_id = :id";
			$params = array(":id" => $_SESSION["vendedor"]);
			$data.= "&#13;VENDEDOR: ".valorSql($sql, "", $params);
		}
	}

	if (isset($_SESSION["isCliente"])) {
		$data = "";

		if ($_SESSION["empresa"] != "")
			$data.= "EMPRESA: ".$_SESSION["empresa"]."&#13;";
		if ($_SESSION["contrato"] != "")
			$data.= "CONTRATO: ".$_SESSION["contrato"]."&#13;";

		if ($_SESSION["isAdminTotal"])
			$data.= "Usted está logueado como ADMINISTRADOR de Provincia ART.";
		elseif ($_SESSION["isAdmin"])
			$data.= "Usted está logueado como ADMINISTRADOR.";
	}

	if (isset($_SESSION["isOrganismoPublico"])) {
		$sql = "SELECT we_mail FROM emi.iwe_usuariowebemision WHERE we_id = :id";
		$params = array(":id" => $_SESSION["idUsuario"]);
		$data = "E-MAIL: ".valorSql($sql, "", $params);
	}

	if (isset($_SESSION["isPreventor"])) {
		$sql = "SELECT it_usuario FROM pit_firmantes WHERE it_id = :id";
		$params = array(":id" => $_SESSION["idUsuario"]);
		$data = "Usted está logueado como ".valorSql($sql, "", $params);
	}

	if ($totalEntidades > 1)
		$data = "Haga clic sobre su nombre de usuario para cambiar la entidad con la que quiere navegar el sitio.&#13;&#13;".$data;
?>
<span id="headerSesion">
	&nbsp;[<span id="cerrarSesion" onClick="window.location.href = '/logout.php'">Cerrar sesión</span>]
	<span id="usuarioSesion" style="<?= (($totalEntidades > 1)?"cursor:hand;":"")?>" title="<?= $data?>" onClick="<?= (($totalEntidades > 1)?"mostrarVentanaCambioEntidad();":"")?>"><?= $_SESSION["usuario"];?></span>
</span>
<?
}
?>
<img border="0" id="barraHomePage" src="/images/barra.jpg" />
<map name="MapBottom">
	<area href="https://www.facebook.com/GrupoProvincia" target="_blank" shape="rect" coords="6, 5, 23, 21" />
	<area href="https://twitter.com/Grupoprovincia" target="_blank" shape="rect" coords="30, 6, 47, 20" />
</map>
<img border="0" id="logos1Header" src="/images/logosFyT.jpg" usemap="#MapBottom" />
<a href="http://www.grupoprovincia.com.ar" target="_blank"><img border="0" id="logos2Header" src="/images/logoGBcoProvincia.jpg" /></a>
<img border="0" id="tituloBarraHomePage" src="/images/central_servicios_titulo.jpg" style="display:<?= (isset($_SESSION["isCliente"])?"block":"none")?>;" />