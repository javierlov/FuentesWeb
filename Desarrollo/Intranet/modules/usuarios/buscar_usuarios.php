<?
$params = array(":id" => getUserId());
$sql = 
	"SELECT se_iddelegacionsede
		 FROM art.use_usuarios
		WHERE se_id = :id";
$sede = valorSql($sql, "", $params);
if ($sede == 2)
	$sede = "_2";
else
	$sede = "";

if (isset($_REQUEST["sector"])) {
	$buscar = true;
	$_SESSION["BUSQUEDA_EMPLEADO"] = array("nombre" => "",
																				 "ob" => "2",
																				 "pagina" => 1,
																				 "sector" => str_replace("_", " ", $_REQUEST["sector"]));
}
else {
	$buscar = isset($_SESSION["BUSQUEDA_EMPLEADO"]);
	if (!$buscar)
		$_SESSION["BUSQUEDA_EMPLEADO"] = array("nombre" => "",
																					 "ob" => "2",
																					 "pagina" => 1,
																					 "sector" => "");
}
?>
<link href="/modules/usuarios/css/usuarios.css" rel="stylesheet" type="text/css" />
<script src="/modules/usuarios/js/usuarios.js" type="text/javascript"></script>
<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
<form action="/modules/usuarios/buscar_usuarios_busqueda.php" id="formBuscarUsuario" method="post" name="formBuscarUsuario" target="iframeProcesando">
	<div id="divIconosUsuarios">
		<a href="/modules/usuarios/instructivo_telefonico.php" target="_blank"><img src="/modules/usuarios/images/instructivo_telefonico.jpg" /></a>
		<a href="mailto:mesadeayuda@provart.com.ar"><img src="/modules/usuarios/images/mesa_de_ayuda<?= $sede?>.jpg" /></a>
		<img src="/modules/usuarios/images/emergencias.jpg" />
		<img src="/modules/usuarios/images/vigilancia<?= $sede?>.jpg" />
		<a href="mailto:mantenimiento@provart.com.ar"><img src="/modules/usuarios/images/mantenimiento<?= $sede?>.jpg" /></a>
	</div>

	<div id="divCampos">
		<div class="fila" id="fila1">
			<label for="nombre" id="labelNombre">NOMBRE O APELLIDO</label>
			<input autofocus id="nombre" maxlength="128" name="nombre" type="text" value="<?= $_SESSION["BUSQUEDA_EMPLEADO"]["nombre"]?>">
		</div>
		<div class="fila" id="fila2">
			<label for="sector" id="labelSector">SECTOR O GERENCIA</label>
			<input id="sector" maxlength="128" name="sector" type="text" value="<?= $_SESSION["BUSQUEDA_EMPLEADO"]["sector"]?>">
		</div>
	</div>
	<div id="divBotones">
		<input id="btnBuscar" name="btnBuscar" type="submit" value="" onClick="submitFormBusqueda()" />
	</div>

	<div align="center" id="divContentGrid" name="divContentGrid" style="height:100%; margin-top:8px;"></div>
	<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img id="imgGrillaCargando" src="/images/grilla/grid_cargando.gif" title="Espere por favor..." /></div>
</form>
<script type="text/javascript">
<?
if ($buscar) {
?>
	submitFormBusqueda();
<?
}
?>
</script>