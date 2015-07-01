<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosConcursosQuiebras.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/CargaComboDatos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/Struct.php");


@session_start(); 
/*	
	Clase con diseño basico de paginas web
	incluir este archivo para usarlo:
	require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Clases/PageBase.php");
	Ejemplo :
		$PageBase = new PageBase(true);
		$PageBase->AgregarEncabezadoJS(true,true,true,true, true, true);
		$PageBase->AgregarArchivoJS("/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/js/AdminWebForm.js");
		$PageBase->AgregarEncabezadoCSS(true,true,true);
		$PageBase->AgregarDivProcesando();
		$PageBase->ActivarGifProcesando();
		$PageBase->DesactivarGifProcesando();
*/

class PageBase{ 
		
	public function __construct($GifProcesando = false) {		
		if($GifProcesando){
			//ActivarGifProcesando();
		}		
	}

	public function AgregarEncabezadoCSS($legales=true, $textos=false, $style=false){
		if($legales)
		 echo "<link href='/modules/usuarios_registrados/estudios_juridicos/Estilos/legales.css' rel='stylesheet' type='text/css' />";
		if($textos)
		 echo "<link href='/modules/usuarios_registrados/estudios_juridicos/Estilos/textos.css' rel='stylesheet' type='text/css' />";		
		if($style)
		 echo "<link href='/styles/style.css' rel='stylesheet' type='text/css' />";

	}
	
	public function AgregarArchivoJS($ArchivoJS){
		echo "<script type='text/javascript' src='".$ArchivoJS."'></script>";
	}
	
	function DetectaVersion()
	{
		$browser=array("MSIE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI","CHROME", "RV" );
		$os=array("WIN","MAC","LINUX","UBUNTU");
		
		# definimos unos valores por defecto para el navegador y el sistema operativo
		$info['browser'] = "OTHER";
		$info['os'] = "OTHER";
		$info['version'] = "";
		
		# buscamos el navegador con su sistema operativo
		foreach($browser as $parent)
		{
			$s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
			$f = $s + strlen($parent);
			$version = substr($_SERVER['HTTP_USER_AGENT'], $f, 15);
			$version = preg_replace('/[^0-9,.]/','',$version);
			if ($s)
			{
				if(strtoupper($parent) == 'RV')
					$info['browser'] = "MSIE";
				else
					$info['browser'] = $parent;
					
				$info['version'] = $version;
			}
		}
		
		# obtenemos el sistema operativo
		foreach($os as $val)
		{
			if (strpos(strtoupper($_SERVER['HTTP_USER_AGENT']),$val)!==false)
				$info['os'] = $val;
		}
			
		return $info;
	}

	public function AgregarEncabezadoJQUERYUI(){
		
		echo " <link href='/styles/rar/jquery-ui-custom.css' rel='stylesheet' type='text/css'> ";
		echo JSjqueryUIVersion();
		
	}
	
	public function AgregarEncabezadoJS($jquery=true, $Comunes=false, $EstudiosJuridicos=false, $GrabaDatos=false, $validations=false, $Autocompletar=false){
		
		echo " <script type='text/javascript' src='/js/rar/Comunes.js?rnd=".RandomNumber()."'></script> ";				
		echo " <script type='text/javascript' src='/js/rar/ComunesJQ.js'></script>  
				<script type='text/javascript' src='/modules/usuarios_registrados/clientes/RAR/js/comunesRAR.js'></script> ";
		//".RandonNumberParameter()."
		
		if($jquery){
			$info = $this->DetectaVersion();
			$version = intval($info["version"]);
			
			if($info["browser"] == 'MSIE' &&  $version < 9)				
				echo "<script type='text/javascript' src='/js/jquery-1.7.2.min.js'></script>";
			else
				echo "<script type='text/javascript' src='/js/jquery.js'></script>";		
		}
		 
		if($validations)
		 echo "<script type='text/javascript' src='/js/validations.js'></script>";
			
		if($Comunes)
		 echo "<script src='/modules/usuarios_registrados/estudios_juridicos/js/Comunes.js' type='text/javascript'></script>";
		 
		if($EstudiosJuridicos)
		 echo "<script src='/modules/usuarios_registrados/estudios_juridicos/js/EstudiosJuridicos.js' type='text/javascript'></script>";
		 
		if($GrabaDatos)
		 echo "<script src='/modules/usuarios_registrados/estudios_juridicos/js/GrabaDatos.js' type='text/javascript'></script>";		

		if($Autocompletar)
		 echo "<script src='/modules/usuarios_registrados/estudios_juridicos/js/Autocompletar.js' type='text/javascript'></script>";
		 
	}
	
	public function CrearVentanaDialogJQUI($titulo, $mensaje){
		/*
<div id="dialogMensajesAdjuntosPericias" title="Info">
	<b class="txt-msj-Aviso-Titulo" id='tituloInfo'>Info Adjuntos:</b>		
	<p>
		<div align="center" id="divInfo" name="divInfo" style="display:none">
		<i id="motivoInfo" >Info</i>
		</div>
		
		<div align="center" id="divSubiendoImg" name="divSubiendoImg" style="display:none">
			<img border="0" src="/images/loading.gif" title="Espere por favor...">
		</div>
	<p>	
</div>
*/
		$txtDialog = '<div id="dialogMensaje" title="Info" style="display:none;">';
		$txtDialog .='<b class="txt-msj-Aviso-Titulo" id="dialogTitulo" >'.$titulo.'</b>';
		$txtDialog .='<p>';		
		$txtDialog .='<div align="center" id="dialogInfoTitulo" name="dialogInfoTitulo" style="display:block">';
		$txtDialog .='<i id="dialogInfoMotivo" >'.$mensaje.'</i>';
		$txtDialog .='</div>  ';
		$txtDialog .='<div align="center" id="divLoading" name="divLoading" style="display:none">';
		$txtDialog .='<img border="0" src="/images/loading.gif" title="Espere por favor...">';
		$txtDialog .='</div>';
		$txtDialog .='<p>';
		$txtDialog .='</div>';
		
	    echo $txtDialog;
	}
	
	
	public function AgregarDivProcesando(){
		echo "<div align='center' id='divProcesando' name='divProcesando' style='display:none'>";
		echo "<img border='0' src='/images/waiting.gif' title='Espere por favor...'></div>";
	}
	
	public function ActivarGifProcesando(){ echo "<script> BuscarWGTrue(); </script>"; }
	
	public function DesactivarGifProcesando(){ echo "<script> BuscarWGFalseInterval(); </script>"; }
	
	public function CrearVentanaMensajeResultado($titulo, $mensaje){
		$resultado = '<div id="VentanaFondoResultado" class="VentanaOverlay" style="display:none; "></div>';
		$resultado .='<div id="VentanaMensajeResultado" class="VentanaModal" style="display:none" >';
		$resultado .='<div class="VentanaModalTitulo" ><b>'.$titulo.'</b></div>';
		$resultado .='<div class="VentanaModalContenido"><p>';
		$resultado .='<div id="idmensajeResultado">'.$mensaje.'</div></p></div>';
		$resultado .='<div class="VentanaModalUNBoton" >';
		
		$resultado .='<input type="button" value="" id="idbtnAceptarVentanaResultado" onclick="OcultarVentana();" class="btnAceptarEJ"></div></div>';
	   echo $resultado;
	}
	
	public function CrearVentanaMensajeOKCancel($titulo, $mensaje){
		$OKCancel = '<div id="VentanaFondoOKCancel" class="VentanaOverlay" style="display:none; "></div>';
		$OKCancel .='<div id="VentanaMensajeOKCancel" class="VentanaModal" style="display:none;" >';
		$OKCancel .='<div class="VentanaModalTitulo" ><b>'.$titulo.'</b></div>';
		$OKCancel .='<div class="VentanaModalContenido"><p>';
		$OKCancel .='<div id="idmensajeOKCancel">'.$mensaje.'</div></p></div>';
		$OKCancel .='<div class="VentanaModalUNBoton" >';
		$OKCancel .='<input type="button" value="" id="idbtnAceptarOKCancel" onclick="OcultarVentana();" class="btnAceptarEJ">   ';
		$OKCancel .='<input type="button" value="" id="idbtnCancelarOKCancel" onclick="OcultarVentana();" class="btnCancelarEJ">';
		$OKCancel .='</div></div>';
	   echo $OKCancel;
	}
	
	public function CrearVentanaMensajeSoloOK($titulo, $mensaje){
		$SoloOK = '<div id="VentanaFondoSoloOK" class="VentanaOverlay" style="display:none; "></div>';
		$SoloOK .='<div id="VentanaMensajeSoloOK" class="VentanaModal" style="display:none;" >';
		$SoloOK .='<div class="VentanaModalTitulo" ><b>'.$titulo.'</b></div>';
		$SoloOK .='<div class="VentanaModalContenido"><p>';
		$SoloOK .='<div id="idmensajeSoloOK">'.$mensaje.'</div></p></div>';
		$SoloOK .='<div class="VentanaModalUNBoton" >';
		$SoloOK .='<input type="button" value="" id="idbtnAceptarSoloOK" onclick="OcultarVentana();" class="btnAceptarEJ">   ';		
		$SoloOK .='</div></div>';
	   echo $SoloOK;
	}
	
	public function CrearVentanaMensajeOculta($titulo, $mensaje, $botones){
		/*Crea una ventana oculta para mostrar en forma modal al registrar los datos.*/
		$resultado = "<div id='VentanaFondo' class='VentanaOverlay' style='display:none; '></div>			  
			  <div id='VentanaMensaje' class='VentanaModal' style='display:none' >
				 <div class='VentanaModalTitulo' >
					<b>".trim($titulo)."</b>
				 </div>						 
				 <div class='VentanaModalContenido'>
					<p>
					<div id='idmensaje'>".trim($mensaje)."</div>
					</p>
				 </div>";
				 
		if($botones == 'ACEPTARCANCELAR')
			$resultado .=" <div class='VentanaModalBoton' >
					<input type='button' value='' id='idbtnAceptarVentana' onclick='OcultarVentana();' class='btnAceptarEJ'>
					<input type='button' value='' id='idbtnCancelarVentana' onclick='OcultarVentana();' class='btnCancelarEJ'>
				 </div>";
				 
		if($botones == 'ACEPTAR')
			$resultado .=" <div class='VentanaModalUNBoton' >
					<input type='button' value='' id='idbtnAceptarVentana' onclick='OcultarVentana();' class='btnAceptarEJ'>
				 </div>";
				 
	    $resultado .="</div>";
			  
		echo $resultado;
	}
	
	private function RetrurnValue($array, $name){
		
		if(isset($array[$name])) 
			return $array[$name]; 
		else 
			return $name;
	}
	
	public function DialogJqueryUI($paramsDialog){
		/* Ejemplo parametros:
			DialogJqueryUI(array('idDialog' => 'nnnn',
								 'idTitulo' => 'nnnn',
								 'idDivInfo' => 'nnnn',
								 'idMotivo' => 'nnnn',
								 'idDivLoading' => 'nnnn',
								 'txtDialog' => 'nnnn',
								 'txtTitulo' => 'nnnn',
								 'txtDivInfo' => 'nnnn', 
								 'displayLoading' => false
								 ) );
		*/
		$idDialog = $this::RetrurnValue($paramsDialog, 'idDialog');
		$idTitulo = $this::RetrurnValue($paramsDialog, 'idTitulo');
		$idDivInfo = $this::RetrurnValue($paramsDialog, 'idDivInfo');
		$idMotivo = $this::RetrurnValue($paramsDialog, 'idMotivo');
		$idDivLoading = $this::RetrurnValue($paramsDialog, 'idDivLoading');		
		$txtDialog = $this::RetrurnValue($paramsDialog, 'txtDialog');
		$txtTitulo = $this::RetrurnValue($paramsDialog, 'txtTitulo');
		$txtDivInfo = $this::RetrurnValue($paramsDialog, 'txtDivInfo');
		$valActivaLoading = $this::RetrurnValue($paramsDialog, false);
		
		$displayLoading = "display:none";
		if($valActivaLoading) $displayLoading = "display:block";
		
				
		echo "
			<div id='".$idDialog."' title='".$txtDialog." style='display:none; '>
				<b class='txt-msj-Aviso-Titulo' id='".$idTitulo."' >".$txtTitulo."</b>		
				<p>
					<div align='center' id='".$idDivInfo."' name='divInfo' style='display:none'>
						<i id='".$idMotivo."' >".$txtDivInfo."</i>
					</div>
					
					<div align='center' id='".$idDivLoading."' name='divSubiendoImg' style='".$displayLoading."'>
						<img border='0' src='/images/loading.gif' title='Espere por favor...'>
					</div>
				<p>	
			</div>	";
		
	}
}
