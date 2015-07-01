
<?php 
	//validarSesion(isset($_SESSION["isCliente"]) or isset($_SESSION["isAgenteComercial"]));
	//validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 52));
	//if (!isset($_SESSION["CARGA_TAREA"]))
	validarSesion(isset($_SESSION["isPreventor"]));
	$_SESSION["CARGA_TAREA"] = array("buscar" => "N",
									 "cuit" => "",
									 "establecimiento" => -1,
									 "contrato" =>0,
									 "nombre" => "",
									 "visitaDesde" => "",
									 "visitaHasta" => "",
									 "estado"=>"");
									 
	if (!isset($_SESSION["BUSQUEDA_CARGA_TAREAS"]))
		$_SESSION["BUSQUEDA_CARGA_TAREAS"] = array("buscar" => "N",
	            								   "cuit" => "",
	            								   "establecimiento" => -1,
	            								   "nombre" => "",
	            								   "contrato" => 0,
	            								   "visitaDesde" => "",
	            								   "visitaHasta" => "",
	            								   "estado" => "",
	            								   "ob" => "3",
	            								   "pagina" => 1);
	
	require_once("carga_tareas_combos.php");
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/design.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css?rnd=20141202" type="text/css" />
		<script src="/js/functions.js?rnd=20130802" type="text/javascript"></script>

		<script src="/js/grid.js" type="text/javascript"></script>

		<!-- INICIO CALENDARIO.. -->
		<style type="text/css">@import url(/js/calendario/calendar-system.css);</style>
		<script type="text/javascript" src="/js/calendario/calendar.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-es.js"></script>
		<script type="text/javascript" src="/js/calendario/calendar-setup.js"></script>
		<!-- FIN CALENDARIO.. -->
	</head>
	<body>


	<script src="/modules/usuarios_registrados/preventores/js/carga_tareas.js" type="text/javascript"></script>
	<iframe id="iframe2" name="iframe2" src="" style="display:none;"></iframe>
	<div class="TituloSeccion" style="display:block; width:73	0px;">Carga Tareas</div>
	<script type="text/javascript">
		function submitForm() {
		resultado = ValidarForm(formCargaTareas);
			if (resultado) {
				document.getElementById('divContentGrid').style.display = 'none';
				document.getElementById('divProcesando').style.display = 'block';
			}
			return resultado;
		}
	</script>
	<div class="ContenidoSeccion" style="margin-top:15px;text-align:left" > 	
		<iframe id="iframeProcesando" name="iframeProcesando" src="" style="display:none;"></iframe>
		
		<form action="/modules/usuarios_registrados/preventores/carga_tareas_busqueda.php" id="formCargaTareas" method="post" name="formCargaTareas" target="iframeProcesando" onSubmit="return submitForm(true)">
			<div>
				<label style="margin-left:0px;">CUIT</label>
				<input id="cuit" name="cuit" style="cursor:default; width:76px;" type="text" value="" onblur="cambiarCuit(this.value)" />
				<label style="margin-left:3px;">Raz&oacuten Social</label>
				<input id="razonSocial" name="razonSocial" style="cursor:default;  width:400px;" type="text" value="" readonly="true" />
				<label style="margin-left:3px;">Contrato</label>
				<input id="contrato" name="contrato" style="cursor:default; width:40px;" type="text" value="" onblur="cambiarContrato(this.value)" />
			</div>
			<div style="margin-top:10px;">
				<label>Establecimiento</label>
				<?php $comboEstablecimiento->draw();?>
				<input id="excluirBajas" name="excluirBajas" style="vertical-align:-2px; border:0" type="checkbox" value="" />
				<label>Excluir bajas</label>

			</div>
			<div style="margin-top:10px;">
				<label style="margin-left:0px;">Visita desde</label>
				<input id="fechaDesde" maxlength="10" name="fechaDesde" style="width:65px;" title="Fecha Desde" type="text" validarFecha="true" value="<? echo valorSql("SELECT art.actualdate- (TO_CHAR (art.actualdate, 'D') -2 ) FROM DUAL");?>">
				<input class="botonFecha" id="btnFechaDesde" name="btnFechaDesde" style="vertical-align:-5px;" type="button" value="">
				<label style="margin-left:16px;">Hasta</label>
				<input id="fechaHasta" maxlength="10" name="fechaHasta" style="width:65px;" title="Fecha Hasta" type="text" validarFecha="true" value="">
				<input class="botonFecha" id="btnFechaHasta" name="btnFechaHasta" style="vertical-align:-5px;" type="button" value="">

				<label style="margin-left:20px">Estado >></label>
				<label style="margin-left:8px;">Pendiente</label>
				<input  id="estado" name="estado" style="vertical-align:-2px; border:0" type="radio" value="P" />
				<label>Aprobado</label>
				<input id="estado" name="estado" style="vertical-align:-2px; border:0" type="radio" value="A" />		
			</div>
			
			<div style="margin-bottom:8px; margin-top:10px;">
				<img border="0" src="/modules/usuarios_registrados/images/alta_de_tarea.jpg" style="cursor:pointer;" onClick="window.location.href='/prevencion/alta-tarea'">
				<input class="btnBuscar" id="btnBuscar" name="btnBuscar" type="submit" style=  "margin-left:520px"value="" />
			</div>
			
		</form>
		<form id="form" name="form">
			<div align="center" id="divContentGrid" name="divContentGrid"></div>
		</form>
		<div align="center" id="divProcesando" name="divProcesando" style="display:none; margin-top:32px;"><img border="0" src="/images/waiting.gif" title="Espere por favor..."></div>
	</div> 
	</body>
	<script type="text/javascript">
		Calendar.setup ({
			inputField: "fechaDesde",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaDesde"
		});
		Calendar.setup ({
			inputField: "fechaHasta",
			ifFormat  : "%d/%m/%Y",
			button    : "btnFechaHasta"
		});

	</script>
</html>