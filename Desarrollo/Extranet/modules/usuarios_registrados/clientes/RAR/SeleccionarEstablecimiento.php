<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/SeleccionarEstablecimiento.Grid.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/clientes/RAR/RAR_funciones.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

@session_start(); 

validarSesion(isset($_SESSION["isCliente"])); 

unset($_SESSION["FormulariosNomina"]);

/*Esta funcion valida si se tiene permiso sobre un modulo
para ello hay que agregar un nuevo campo en la tabla web.wuc_usuariosclientes S/N		
Ejemplo permiso para RGLR
validarSesion(validarPermisoClienteXModulo($_SESSION["idUsuario"], 100));
*/

if( isset($_REQUEST["NominaActualdeNominaAnterior"])){			
	
	$idEstablecimiento = GetParametroDecode("idEstablecimiento", '0');						 			
	$cuitEmpresa = GetParametroDecode("cuitEmpresa", '');												
	$usualta = GetParametroDecode("usualta", '');
	
	//Insert_NominaActualdeNominaAnterior($idEstablecimiento, $cuitEmpresa, $usualta);
}

	$showDialogResponsable = 'NO';
	if( isset($_SESSION['Responsable']) ){
			$showDialogResponsable = 'SI';
	}	
echo "
	<script  type='text/javascript'> 
		var showDialogResponsable = '".$showDialogResponsable."';
		var contratoSession = ".$_SESSION['contrato'].";
	</script>
	";
	

?>

<head>
	<meta charset="utf-8">
	<title>Seleccionar Establecimieto</title>
	<link href="/styles/style.css" rel="stylesheet" type="text/css">
	<link href="/styles/style2.css" rel="stylesheet" type="text/css">
	<link href="/styles/gridAjax.css" rel="stylesheet" type="text/css">
	<link href="/styles/rar/jquery-ui-custom.css" rel="stylesheet" type="text/css">	
	<link href="/modules/usuarios_registrados/clientes/RAR/css/rar.css<?php echo RandonNumberParameter(); ?>" rel="stylesheet">	
	
	<?php
		// <script src="/js/jquery.js"></script>
		echo JSjqueryVersion();
		// <script src="/js/rar/jquery-ui-custom.js"></script>
		echo JSjqueryUIVersion();
	?>
	
	<script src="/js/rar/Comunes.js<?php echo RandonNumberParameter(); ?>"></script>	
	<script src="/js/rar/ComunesJQ.js<?php echo RandonNumberParameter(); ?>"></script>	
	<script src="/js/rar/ProcesarDatos.js"></script>	
	<script src="/modules/usuarios_registrados/clientes/RAR/js/SeleccionarEstablecimiento.js<?php echo RandonNumberParameter(); ?>"></script>
	<script src="/modules/usuarios_registrados/clientes/RAR/js/comunesRAR.js<?php echo RandonNumberParameter(); ?>" ></script>

</head>
<body>

<div style="width:auto; vertical-align: center;" >

<?php
/*
 
 Nombre del servidor
 
 echo 'print screen ';
 
echo "<br> -- ".gethostname(); //nombre del servidor

echo "<br>".gethostbyname(gethostname());
echo "<br>".gethostbyaddr($_SERVER['REMOTE_ADDR']);
echo "<br>".gethostbyaddr( gethostbyname(gethostname()) );
echo "<br>".$_SERVER['REMOTE_ADDR'];
echo "<br>".php_uname("a");
echo "<br>".php_uname("s");
echo "<br> -- ".php_uname("n"); // nombre del sevidor
echo "<br>".php_uname("r");
echo "<br>".php_uname("v");
echo "<br>".php_uname("m");
echo PHP_OS; // sistema operativo
echo 'PHP: ' . phpversion(); // version php
echo "Zend: " . zend_version(); // version motor zend
*/

?>
	<div class="TituloSeccion" style="width: 400px; padding-top:10px;">Seleccionar Establecimiento</div>
	<div class="ui-TitulosEtiquetasP10" style="width:99%"><I>Ingrese código/nombre de establecimiento o los datos del domicilio para filtrar los mismos:</I></div>

	<p></p>
		<div style="width:100%; float:left;"> 
			<div class="ui-TitulosEtiquetasP10" style="width:120px; text-align:right;" ><b>Establecimiento:</b></div>
			<div class="ui-TitulosEtiquetasP10" style="width:100px;" ><input type="text" id="idEstablecimiento" style="width:80%;" /></div>	
			<div class="ui-TitulosEtiquetasP10" style="width:300px;" ><input type="text" id="EstablecimientoNombre" style="width:100%;" /></div>	
			
		</div>	
	<p></p>
		<div style="width:100%;  float:left;"> 
			<div class="ui-TitulosEtiquetasP10" style="width:120px; text-align:right; " ><b>Calle:</b></div>
			<div class="ui-TitulosEtiquetasP10" style="width:280px;" ><input type="text" id="calle" style="width:98%;" /></div>	
			<div class="ui-TitulosEtiquetasP10" style="width:50px; text-align:right; " ><b>CP.:</b></div>
			<div class="ui-TitulosEtiquetasP10" style="width:68px;" ><input type="text" id="CPostal" style="width:100%;" /></div>
		</div>	
	<p></p>
		<div style="width:100%;  float:left;"> 
			<div class="ui-TitulosEtiquetasP10" style="width:120px; text-align:right; " ><b>Localidad:</b></div>
			<div class="ui-TitulosEtiquetasP10" style="width:160px;" ><input type="text" id="Localidad" style="width:98%;" /></div>	
			<div class="ui-TitulosEtiquetasP10" style="width:80px; text-align:right; " ><b>Provincia.:</b></div>
			<div class="ui-TitulosEtiquetasP10" style="width:168px;" >
				<?php echo GetSelectProvincias(); ?>				
			</div>
		</div>	
	<p></p>
		<div id="divlistaerrores" class="ui-TitulosEtiquetas" style="width:100%;  float:left; display:none;"> 
				<div id="listaerrores" style="color:red; width:100%; height:auto;"></div>			
		</div>	
	<p>	</p>
		<div style="width:100%;  float:left;"> 			
		<div class="ui-TitulosEtiquetasP10" style="width:536px; text-align:right; " >								
			<input type="button" style="height:15px; width:53px;" class="btnBuscar" id="btnBuscar"  /></div>
		</div>				
	
	<div style="width:100%;  float:left;"> 			
		<div class="ui-TitulosEtiquetasP10" style="width:99%;" >			
			<div class="ui-TitulosEtiquetas" style="width: auto; float:right; padding-left:20px;" >
				<img src="/images/btn_ok.png" alt="icono" height="16" width="16"> Pendiente de Aceptación</div>
			<div class="ui-TitulosEtiquetas" style="width: auto; float:right; " > 
				<img src="/images/btn_rgrl.png" alt="icono" height="16" width="16"> Nómina no generada</div>			
		</div>	
	</div>	
	
	<div style="width:100%;  float:left;"> 			
		<div align="center" id="divProcesando" name="divProcesando" style="display:none;">
			<img style="padding-top:10px;" border="0" src="/images/waiting.gif" title="Espere por favor...">
		</div>
		<div id="idGridSeleccionaEstablecimieto" style="width:100%;"></div>
		<div id="msgError" class="txt-msj-Aviso" style="width:100%;"></div>
	</div>
	
	</div>

<div id="dialogRechazado" title="Rechazado">
	<b class="txt-msj-Aviso" >Motivo de Rechazo:</b>		
	<p>
	<div id="motivoRechazo" style='padding:3px 0 0 0; text-align:left; font-style:italic;' >	</div>
	<p>
	<b class="txt-msj-Aviso" >Observación:</b>		
	<p>	
	<div id="observacionRechazo" style='padding:3px 0 0 0; text-align:left; font-style:italic;' >	</div>
	<p>
</div>

<div id="dialogYaPresentada" title="Nomina">
	<b class="txt-msj-Aviso" id='YaPresentadaTitulo' >Nomina ya Presentada:</b>		
	<p>
	<div id="motivoYaPresentada" style='padding:3px 0 0 0; text-align:left; font-style:italic;' >"Usted ya presentó una Nómina de expuestos en el año. Por favor comuníquese al teléfono….". </div>
	<p>	
</div>

<div id="dialogSeleccionMensajes" title="Mensaje">
	<div id="TituloSMensaje" >Titulo</div>		
	<p><div id="TextoSMensaje" >Texto</div>		
</div>


</body>
