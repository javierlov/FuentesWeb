<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosConcursosQuiebras.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ValidacionesDB.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
//PATRON RPG

@session_start(); 


if(isset($_REQUEST['archivodescarga'])){
		//$archivo=$_REQUEST['archivodescarga'];		
		// foreach($_REQUEST as $k=>$v)			EscribirLogTxt1('archivodescarga', "$k = $v");			
		$archivo = base64_decode($_REQUEST['archivodescarga']);				
		
		if(isset($_REQUEST['evento']))
			DescargarArchivoAdjunto($archivo, 'EVENTO');
		
		if(isset($_REQUEST['pericia']))
			DescargarArchivoAdjunto($archivo,'PERICIA');
}

if(isset($_REQUEST["pageid"])){
	//pageid 99 = 106
	if($_REQUEST["pageid"] == "106" ){
		AsignarNroJuicioSession();		
		header("Location: /AdminWebForm");
	}
	
	//pageid 101 = 108
	if($_REQUEST["pageid"] == "108" ){
		AsignarNroJuicioSession();
		header("Location: /EventosWebForm");
	}
	
	//pageid 104 = 111
	if($_REQUEST["pageid"] == "111" ){
		AsignarNroJuicioSession();		
		if(isset($_REQUEST["DELETE"]) ) {			
			$PeritajeID = $_REQUEST["id"];
			$usuario = $_SESSION["usuario"];	   
			
			if(UpdatePeritajes($PeritajeID, $usuario))
				$_SESSION["PeritajesABM"]["resultado"] = "El peritaje fue eliminado.";
			else
				$_SESSION["PeritajesABM"]["resultado"] = "Error eliminando peritaje.";
		}		
		header("Location: /PeritajesWebForm");
	}		
	
	//pageid 105 = 112
	if($_REQUEST["pageid"] == "112" ){
		AsignarNroJuicioSession();
		$RedirectPageAnt = '/PeritajesABMWebForm/0';
		
		if(isset($_REQUEST["cuit"])) $_SESSION["PeritajesABMWebForm"]["cuit"] = $_REQUEST["cuit"];
		if(isset($_REQUEST["Apellido"])) $_SESSION["PeritajesABMWebForm"]["Apellido"] = $_REQUEST["Apellido"];
		if(isset($_REQUEST["Nombre"])) $_SESSION["PeritajesABMWebForm"]["Nombre"] = $_REQUEST["Nombre"];
		if(isset($_REQUEST["idperito"])) $_SESSION["PeritajesABMWebForm"]["idperito"] = $_REQUEST["idperito"];		
		if(isset($_REQUEST["cmbTipoPericia"])) $_SESSION["PeritajesABMWebForm"]["cmbTipoPericia"] = $_REQUEST["cmbTipoPericia"];
		if(isset($_REQUEST["RedirectPageAnt"])) $RedirectPageAnt = $_REQUEST["RedirectPageAnt"];
		
		if(isset($_REQUEST["htxtcuil"])) $_SESSION["PeritajesABMWebForm"]["htxtcuil"] = $_REQUEST["htxtcuil"];
		
		/*
		if(isset($_REQUEST["FechaAsignacion"])) $_SESSION["PeritajesABMWebForm"]["FechaAsignacion"] = $_REQUEST["FechaAsignacion"];
		if(isset($_REQUEST["FechaPericia"])) $_SESSION["PeritajesABMWebForm"]["FechaPericia"] = $_REQUEST["FechaPericia"];
		if(isset($_REQUEST["FVencImpugnacion"])) $_SESSION["PeritajesABMWebForm"]["FVencImpugnacion"] = $_REQUEST["FVencImpugnacion"];
		
		if(isset($_REQUEST["IncapacidadDemandada"])) $_SESSION["PeritajesABMWebForm"]["IncapacidadDemandada"] = $_REQUEST["IncapacidadDemandada"];
		if(isset($_REQUEST["IncapacidadPerMedico"])) $_SESSION["PeritajesABMWebForm"]["IncapacidadPerMedico"] = $_REQUEST["IncapacidadPerMedico"];
		
		if(isset($_REQUEST["IBMArt"])) $_SESSION["PeritajesABMWebForm"]["IBMArt"] = $_REQUEST["IBMArt"];
		if(isset($_REQUEST["IBMPericial"])) $_SESSION["PeritajesABMWebForm"]["IBMPericial"] = $_REQUEST["IBMPericial"];
		
		if(isset($_REQUEST["chkImpugnacion"])) $_SESSION["PeritajesABMWebForm"]["chkImpugnacion"] = $_REQUEST["chkImpugnacion"];
		if(isset($_REQUEST["txtResultados"])) $_SESSION["PeritajesABMWebForm"]["txtResultados"] = $_REQUEST["txtResultados"];
		*/	
		header("Location: ".$RedirectPageAnt);				
	}		
	
	//pageid 106 = 113
	if($_REQUEST["pageid"] == "113" ){
		if(isset($_REQUEST["DELETE"]) ) {
			
			$EventoID = $_REQUEST["id"];
			$usuario = $_SESSION["usuario"];			
			$respuesta = '';
			
			if($_SESSION["JUICIOTERMINADO"] ) {				
				$_SESSION["EventosEliminar"]["mensaje"] = "No se puede Eliminar el evento. El contrato esta Terminado.";
				$_SESSION["EventosEliminar"]["resultado"]='false'; 
			}
			
			if (UpdateEvento($EventoID, $usuario) == 0){
				$_SESSION["EventosEliminar"]["mensaje"] = "No se puede Eliminar el evento. ya que usted no dio origen al registro."; 
				$_SESSION["EventosEliminar"]["resultado"]='false'; 
			} 			
			else{
				$_SESSION["EventosEliminar"]["mensaje"] =  "El evento fue eliminado.";
				$_SESSION["EventosEliminar"]["resultado"]='true'; 
			}
		}		
		$respuesta .= '<script> window.location.href="/EventosWebForm"; </script> ';				
		
		echo $respuesta;			
		header("Location: /EventosWebForm");		
	}
	
	//pageid 107 = 114
	if($_REQUEST["pageid"] == "114" ){
		AsignarNroJuicioSession();
		header("Location: /SentenciaWebForm");
	}	
	
	//pageid 108 = 115
	if($_REQUEST["pageid"] == "115" ){
		AsignarNroJuicioSession();		
		LimpiarConstPeritajes();		
				
		if(isset($_REQUEST["ResultadoIdPerito"])) $_SESSION["PeritajesABMWebForm"]["IdPeritoEdit"] = $_REQUEST["ResultadoIdPerito"];		
		
		if(isset($_REQUEST["idperito"])) $_SESSION["PeritajesABMWebForm"]["IdPeritoEdit"] = $_REQUEST["idperito"];		
		if(isset($_REQUEST["idperito"])) $_SESSION["PeritajesABMWebForm"]["idperito"] = $_REQUEST["idperito"];		
		
		
		if(isset($_REQUEST["Apellido"])) $_SESSION["PeritajesABMWebForm"]["Apellido"] = $_REQUEST["Apellido"];
		if(isset($_REQUEST["Nombre"])) $_SESSION["PeritajesABMWebForm"]["Nombre"] = $_REQUEST["Nombre"];
		
				
		if(isset($_REQUEST["cmbTipoPericia"])) $_SESSION["PeritajesABMWebForm"]["cmbTipoPericia"] = $_REQUEST["cmbTipoPericia"];
		if(isset($_REQUEST["cmbTipoPericiaValor"])) $_SESSION["PeritajesABMWebForm"]["cmbTipoPericiaValor"] = $_REQUEST["cmbTipoPericiaValor"];
		
		if(isset($_REQUEST["FechaAsignacion"])) $_SESSION["PeritajesABMWebForm"]["FechaAsignacion"] = $_REQUEST["FechaAsignacion"];
		if(isset($_REQUEST["FechaPericia"])) $_SESSION["PeritajesABMWebForm"]["FechaPericia"] = $_REQUEST["FechaPericia"];
		if(isset($_REQUEST["FVencImpugnacion"])) $_SESSION["PeritajesABMWebForm"]["FVencImpugnacion"] = $_REQUEST["FVencImpugnacion"];
		
		if(isset($_REQUEST["IncapacidadDemandada"])) $_SESSION["PeritajesABMWebForm"]["IncapacidadDemandada"] = $_REQUEST["IncapacidadDemandada"];
		if(isset($_REQUEST["IncapacidadPerMedico"])) $_SESSION["PeritajesABMWebForm"]["IncapacidadPerMedico"] = $_REQUEST["IncapacidadPerMedico"];
		
		if(isset($_REQUEST["IBMArt"])) $_SESSION["PeritajesABMWebForm"]["IBMArt"] = $_REQUEST["IBMArt"];
		if(isset($_REQUEST["IBMPericial"])) $_SESSION["PeritajesABMWebForm"]["IBMPericial"] = $_REQUEST["IBMPericial"];
		
		if(isset($_REQUEST["chkImpugnacion"])) $_SESSION["PeritajesABMWebForm"]["chkImpugnacion"] = $_REQUEST["chkImpugnacion"];
		if(isset($_REQUEST["txtResultados"])) $_SESSION["PeritajesABMWebForm"]["txtResultados"] = $_REQUEST["txtResultados"];
		
		if(isset($_REQUEST["Accion"])) $_SESSION["PeritajesABMWebForm"]["Accion"] = $_REQUEST["Accion"];
		header("Location: /PeritoABMWebForm");
		
	}	
	
	//pageid 110 = 117
	if($_REQUEST["pageid"] == "117" ){
		unset($_SESSION["ModificacionCYQ"]);
		AsignarNroJuicioSession();
		$_SESSION["ModificacionCYQ"]["nroorden"] = '';
		
		if(isset($_REQUEST["id"])){
			$_SESSION["ModificacionCYQ"]["nroorden"] = $_REQUEST["id"];
			$_SESSION["nroorden"] = $_REQUEST["id"];
		}
			
		header("Location: /ModificacionCYQ");
	}
	//----Concuros y Quiebras------------------------------
	//pageid 111 = 118
	if($_REQUEST["pageid"] == "118" ){		
		if(isset($_REQUEST["DELETECONFIRM"]) ) {
		
			$nroorden = $_REQUEST["nroorden"];
			$nroevento = $_REQUEST["id"];
			$respuesta = '';		
			
			$_SESSION["CYQEliminar"]["nroorden"]= $nroorden; 
			$_SESSION["CYQEliminar"]["id"]= $id; 
			
			$_SESSION["ACCION"] = "DELETE";
			
			if (UpdateEventosCYQ($nroorden, $nroevento, $usuario)){
				$_SESSION["CYQEliminar"]["mensaje"] = "El registro fue eliminado.";
				$_SESSION["CYQEliminar"]["resultado"]='true'; 
			}
			else{
				$_SESSION["CYQEliminar"]["mensaje"] = "Error: No se pudo eliminar el registro.";
				$_SESSION["CYQEliminar"]["resultado"]='false'; 				
			}	
			
			header("Location: /EventosCYQWebForm");
		}
		else{		
			unset($_SESSION["ArrayEventosCYQWebForm"]);
			$_SESSION["ArrayEventosCYQWebForm"]["nroorden"] = 0;
		
			if(isset($_REQUEST["nroorden"]))
				$_SESSION["ArrayEventosCYQWebForm"]["nroorden"] = $_REQUEST["nroorden"];
				
			header("Location: /EventosCYQWebForm");
		}
	}
	
	//pageid 113 = 120
	if($_REQUEST["pageid"] == "120" ){				
		unset($_SESSION["ArrayAcuerdosWeb"]);
		
		$_SESSION["ArrayAcuerdosWeb"]["nroorden"] = 0;
		if(isset($_REQUEST["nroorden"]))
			$_SESSION["ArrayAcuerdosWeb"]["nroorden"] = $_REQUEST["nroorden"];
		
		if(isset($_REQUEST["cmbTipo"]))
			$_SESSION["ArrayAcuerdosWeb"]["cmbTipo"] = $_REQUEST["cmbTipo"];			
	
		header("Location: /AcuerdosWebForm");
	}
	
	//pageid 114 = 121
	if($_REQUEST["pageid"] == "121" ){				
		unset($_SESSION["ArrayAcuerdosABMWeb"]);
		
		if(isset($_REQUEST["nroorden"]))
			$_SESSION["ArrayAcuerdosABMWeb"]["nroorden"] = $_REQUEST["nroorden"];
		
		if(isset($_REQUEST["NroPago"]))
			$_SESSION["ArrayAcuerdosABMWeb"]["NroPago"] = $_REQUEST["NroPago"];		
	
		header("Location: /AcuerdosABMWebForm");
	}
	
	//pageid 115 = 122
	if($_REQUEST["pageid"] == "122" ){				
		//Estudios Jurídicos - CuotasWebForm
		unset($_SESSION["ArrayCuotasWebForm"]);
		if(isset($_REQUEST["nroorden"]))
			$_SESSION["ArrayCuotasWebForm"]["nroorden"] = $_REQUEST["nroorden"];
		
		if(isset($_REQUEST["NroPago"]))
			$_SESSION["ArrayCuotasWebForm"]["NroPago"] = $_REQUEST["NroPago"];		
			
		header("Location: /CuotasWebForm");
	}	
	
	if($_REQUEST["pageid"] == "134" ){				
		
		$resultado = true;
		if ( isset($_REQUEST['DELETE']) ){    
			$EventoID = $_REQUEST["EventoID"];		
			$eaID = $_REQUEST["eaid"];		
			DeleteArchivoEventoJuicioTramite($EventoID, $eaID);
		}
		
		if($resultado){
			header("Location: /index.php?pageid=134&id=".$EventoID);
		}
	}	
	
	if($_REQUEST["pageid"] == "135" ){				
		
		$resultado = true;
		if ( isset($_REQUEST['DELETE']) ){    
			$PericiaID = $_REQUEST["PericiaID"];		
			$eaID = $_REQUEST["eaid"];		
			DeleteArchivoPericia($PericiaID, $eaID);
		}
		
		if($resultado){
			header("Location: /index.php?pageid=135&id=".$PericiaID);
		}
	}			
		
}
else{
/*
	header('Content-Type: text/html; charset=utf-8');
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT"); // always modified
	header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
	header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
	header("Pragma: no-cache"); // HTTP/1.0	
*/
}

if(isset($_REQUEST["SentenciaWebFormCompletar"])){	
	header("Location: /SentenciaWebForm");
}


if(isset($_REQUEST["ReportesSiniestros"])){	
	//llamada al modulo reporte siniestros
	$_SESSION["ReportesSiniestros"]["ID"] = $_REQUEST["id"];
	$_SESSION["ReportesSiniestros"]["ORDEN"] = $_REQUEST["ORDEN"];
	header("Location: /ReportesSiniestrosWebForm");
}

if(isset($_REQUEST["PrintReportesSiniestros"])){	
	$_SESSION['ReportesSiniestros']["ReporteResumenSiniestro"] = 'REPORTE';
	header("Location: /ReportesSiniestros");
	//header("Location: /modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/Reportes/ReporteResumenSiniestro.php");
}

if(isset($_REQUEST["PrintSeguimientodeIncapacidad"])){	
	$_SESSION['ReportesSiniestros']["ReporteSeguimientodeIncapacidad"] = 'REPORTE';
	header("Location: /ReporteSeguimientodeIncapacidad");
}

if(isset($_REQUEST["PrintEvolutivodeSiniestro"])){	
	$_SESSION['ReportesSiniestros']["ReporteEvolutivodeSiniestro"] = 'REPORTE';	
	header("Location: /ReporteEvolutivodeSiniestro");
}

if(isset($_REQUEST["PrintFichaTrabajador"])){	
	$_SESSION['ReportesSiniestros']["ReporteFichaTrabajador"] = 'REPORTE';	
	header("Location: /ReporteFichaTrabajador");
}

if(isset($_REQUEST["PrintReporteDatosdelaEmpresa"])){	
	$_SESSION['ReportesSiniestros']["ReporteDatosdelaEmpresa"] = 'REPORTE';	
	header("Location: /ReporteDatosdelaEmpresa");
}

if(isset($_REQUEST["InstanciasABMWebForm"])){	

	$accion = "";
	$accion = $_REQUEST["InstanciasABMWebForm"];
	
	$_SESSION["InstanciasABM"]["ACCION"] = $_REQUEST["InstanciasABMWebForm"];
	
	if(isset($_REQUEST["id"]))
		$_SESSION["InstanciasABM"]["ID"] = $_REQUEST["id"];
	
	header("Location: /InstanciasABMWebForm");
}

if(isset($_REQUEST["PeritajesABMWebForm"])){	

	$accion = "";
	$accion = $_REQUEST["PeritajesABMWebForm"];
	
	$_SESSION["PeritajesABM"]["ACCION"] = $_REQUEST["PeritajesABMWebForm"];
	$_SESSION["PeritajesABM"]["id"] = 0;

	if(isset($_REQUEST["id"]))
		$_SESSION["PeritajesABM"]["id"] = $_REQUEST["id"];
	
	header("Location: /PeritajesABMWebForm");
	
}

if(isset($_REQUEST["CertificadoRetencionReport"])){	
	$idCheque = "0";
	if(isset($_REQUEST["idCheque"])) $idCheque = $_REQUEST["idCheque"];	
	
	echo"<form name=t id=t action=/certificado-retencion method=post >";
	echo"<input type=hidden name=op value='".$idCheque."' >";	
	echo"</FORM>";
	echo"<script>t.submit();</script>";

	
}

if(isset($_REQUEST["OrdenPagoReport"])){	
	
	ValidarUserSession();
	
	$idCheque = "0";
	if(isset($_REQUEST["idCheque"])) $idCheque = $_REQUEST["idCheque"];
	
	$ChequeNum = ValidaChequeReimpreso($idCheque);
	
	if(  $ChequeNum > 0 ){		
		//$ChequeReimpresoNum = RetornaChequeNum($idCheque);
		
		echo"<form name=t id=t action=/reporte-reemplazo method=post >";
		echo"<input type=hidden name=op value='".$idCheque."' >";
		echo"</FORM>";
		echo"<script>t.submit();</script>";
		
	}else{
		echo"<form name=t id=t action=/orden-pago method=post >";
		echo"<input type=hidden name=op value='".$idCheque."' >";
		echo"</FORM>";
		echo"<script>t.submit();</script>";
	}
}

if(isset($_REQUEST["JuiciosParteDemandadaPRG"])){
	
	$return =  "?BuscarActivo";
	if( isset($_REQUEST["codigoCaratula"])) $return =  "&codigoCaratula=".$_REQUEST["codigoCaratula"];		
	if( isset($_REQUEST["NroExpediente"])) $return =  "&NroExpediente=".$_REQUEST["NroExpediente"];		
	if( isset($_REQUEST["NroCarpeta"])) $return =  "&NroCarpeta=".$_REQUEST["NroCarpeta"];		
	if( isset($_REQUEST["cmbTipoJuicio"])) $return =  "&cmbTipoJuicio=".$_REQUEST["cmbTipoJuicio"];		
	
	$redirectpage = "/modules/usuarios_registrados/estudios_juridicos/Redirect.php".$return;		
	header("Location: http://extranet-test.artprov.com.ar/".$redirectpage);


}

if(isset($_REQUEST["JuiciosParteDemandada"])){
	echo "entro..<br>";
	$_SESSION["ArrayJuiciosParteDemandada"] = array();
	
	if((isset($_REQUEST["idUsuario"])) && ( !empty($_REQUEST["idUsuario"])) ) 
		$_SESSION["ArrayJuiciosParteDemandada"]["idUsuario"] = $_REQUEST["idUsuario"];
		
	if((isset($_REQUEST["codigoCaratula"])) && ( !empty($_REQUEST["codigoCaratula"])) ) 
		$_SESSION["ArrayJuiciosParteDemandada"]["codigoCaratula"] = urlencode($_REQUEST["codigoCaratula"]);
		
	if((isset($_REQUEST["NroExpediente"])) && ( !empty($_REQUEST["NroExpediente"])) ) 
		$_SESSION["ArrayJuiciosParteDemandada"]["NroExpediente"] = urlencode($_REQUEST["NroExpediente"]);
		
	if((isset($_REQUEST["NroCarpeta"])) && ( !empty($_REQUEST["NroCarpeta"])) ) 
		$_SESSION["ArrayJuiciosParteDemandada"]["NroCarpeta"] = urlencode($_REQUEST["NroCarpeta"]);
		
	if((isset($_REQUEST["cmbTipoJuicio"])) && ( !empty($_REQUEST["cmbTipoJuicio"])) ) 
		$_SESSION["ArrayJuiciosParteDemandada"]["cmbTipoJuicio"] = urlencode($_REQUEST["cmbTipoJuicio"]);
		
	echo ($_SESSION["ArrayJuiciosParteDemandada"]);
	
	header("Location: ".$_REQUEST["Redirect"]);
}

if(isset($_REQUEST["ConcursoyQuiebrasGrid"])){
	//CAMBIO PAG 109=116 -->
	header("Location: /index.php?pageid=116");
}

if(isset($_REQUEST["ConcursosQuiebras"])){
		
	$_SESSION["ArrayConcursosQuiebras"]["BUSCAR"] = "BUSCAR";
	
	if((isset($_REQUEST["txtNroOrden"])) && ( !empty($_REQUEST["txtNroOrden"])) ) 
		$_SESSION["ArrayConcursosQuiebras"]["txtNroOrden"] = $_REQUEST["txtNroOrden"];
	
	if((isset($_REQUEST["txtcuil1"]))&& ( !empty($_REQUEST["txtcuil1"])) ) 
		$_SESSION["ArrayConcursosQuiebras"]["txtcuil1"] = $_REQUEST["txtcuil1"];
	
		
	if((isset($_REQUEST["txtcuil2"]))&& ( !empty($_REQUEST["txtcuil2"])) ) 
		$_SESSION["ArrayConcursosQuiebras"]["txtcuil2"] = $_REQUEST["txtcuil2"];
	
	if((isset($_REQUEST["txtcuil3"]))&& ( !empty($_REQUEST["txtcuil3"])) ) 
		$_SESSION["ArrayConcursosQuiebras"]["txtcuil3"] = $_REQUEST["txtcuil3"];
		
	if((isset($_REQUEST["txtPerito"]))&& ( !empty($_REQUEST["txtPerito"])) ) 
		$_SESSION["ArrayConcursosQuiebras"]["txtPerito"] = $_REQUEST["txtPerito"];
	
	if((isset($_REQUEST["cmbRsocial"]))&& ( !empty($_REQUEST["cmbRsocial"])) ) 
		$_SESSION["ArrayConcursosQuiebras"]["cmbRsocial"] = $_REQUEST["cmbRsocial"];
				
	header("Location: /ConcursosQuiebras");
		
}

if(isset($_REQUEST["AdminWebFormGuardar"])){

//idtxtInstancia
	$NroJuicio 			= $_SESSION["NroJuicio"];
	$cmbJurisdiccion 	= $_REQUEST['cmbJurisdiccion'];
	$cmbFuero 			= $_REQUEST['cmbFuero'];
	$cmbJuzgadoNro 		= $_REQUEST['cmbJuzgadoNro'];
	$cmbSecretaria 		= $_REQUEST['cmbSecretaria'];
	$txtNroExp 			= $_REQUEST['txtNroExp'];
	$txtAnioExp 		= $_REQUEST['txtAnioExp'];
	
	$_SESSION["AdminWebFormGuardarResultado"] = "NO";
	
	$jt_id = $_SESSION["NroJuicio"]; 
	$resultado = $_REQUEST["txtResProbable"]; 
	$cmbEstado = $_REQUEST["cmbEstado"]; 
	$usuario = $_SESSION["usuario"];
	
	$result = UpdateResultado($jt_id, $resultado, $cmbEstado, $usuario);		
	
	if ($result){
		if (GuardarInstancia($NroJuicio, $cmbJurisdiccion, $cmbFuero, $cmbJuzgadoNro, $cmbSecretaria, $txtNroExp, $txtAnioExp)){			
			$_SESSION["AdminWebFormGuardarResultado"] = "YES";
		}
	}
	$prevpage = $_SERVER['HTTP_REFERER'];
	header("Location: ".$_REQUEST["Redirect"]);
	//header("Location: ".$prevpage);
}
