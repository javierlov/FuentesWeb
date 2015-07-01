<style type="text/css">
	.itemMapaDelSitio {border-bottom-style:dotted; border-bottom-width:1px; color:#676767; font-size:8pt; font-weight:700; padding-left:4px;}
</style>
<?
if ((isset($_GET["404"])) and ($_GET["404"] == "t")) {
?>
	<div align="center">
		<div style="margin-top:16px;">
			<img src="/images/404.jpg" style="height:219px; width:211px;" /></td>
		</div>
		<div style="margin-top:16px;">
			<span class="itemMapaDelSitio" style="border-bottom-style:none; color:#666; font-size:15px;">La página que ha solicitado no existe, puede tratar de encontrarla en el <a href="/mapa-sitio">Mapa del Sitio</a>.</span>
		</div>
	</div>
<?
}
else {
	$arr = array(
		"Inicio" => "",
		"Acerca de Provincia ART" => "acerca-provincia-art",
		"Servicio" => "servicio",
		"Prevención" => "prevencion",
		"Central de Servicios en Línea" => "central-servicios-linea",
		"Rol Social" => "rol-social-provincia-art",
		"Sucursales" => "sucursales",		
		"Contacto" => "contacto");

	echo "<div class='TituloSeccion' style='margin-bottom:24px;'>Mapa del Sitio</div>";

	foreach ($arr as $key => $val) {
?>
		<div class="itemMapaDelSitio" style="margin-left:12px; margin-top:12px;">&gt; <a href="/<?= $val?>"><?= $key?></a></div>
<?
	}
}
?>