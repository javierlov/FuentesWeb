<?php
//if(!isset($_SESSION)) { session_start(); } 
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosJuiciosParteDemandada.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/ObtenerDatosConcursosQuiebras.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/PeritajesABMWebForm.Grid.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/JuiciosParteDemandada/MasDatosJuicioWebForm.Grid.php");

require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/rar_comunes.php");

@session_start(); 
/*
funcion declarada en 
S:\Common\miscellaneous\rar_comunes.php

function RetronaXMLArray($arraymensaje){			
	//toma id valor de un array para armar un xml de resultados
	$xml="<?xml version=\"1.0\"?>\n";
	foreach($arraymensaje as $clave => $valor)
		$xml .= "<".$clave.">".$valor."</".$clave.">";
		
	header('Content-Type: text/xml');
	echo utf8_decode($xml);
}
*/

function RetronaParamsXML($ArrayParams){			

	$xml="<?xml version=\"1.0\"?>\n";
	$xml .= "<resultados>";			

	foreach($ArrayParams as $key  => $value)
		$xml .= "<".$key.">".$value."</".$key.">";		
	
	$xml .= "</resultados>";			
	header('Content-Type: text/xml');
	echo utf8_decode($xml);		
}

function RetornaDatosJSON($ArrayParams){
	$result = '[ {';
	$i = 0;
	
	foreach($ArrayParams as $key  => $value){
		if($i == 0)
			$result .= '"'.$key.'": "'.$value.'" ';
		else
			$result .= ', "'.$key.'": "'.$value.'" ';
		$i++;
	}
	$result .= '} ]';		

	echo $result;
}

if (isset($_REQUEST['FUNCION'])){    
		
	if ($_REQUEST['FUNCION']=="UpdatePerito"){
		try{			
			$id = utf8_decode(ValorParametroRequest('id'));  
			$nombre = utf8_decode(ValorParametroRequest('nombre'));  
			$apellido = utf8_decode(ValorParametroRequest('apellido'));  
			$cuil = utf8_decode(ValorParametroRequest('cuil'));  
			$tipoperito = utf8_decode(ValorParametroRequest('tipoperito'));  
			$parteoficio = utf8_decode(ValorParametroRequest('parteoficio'));  
			$usuario = utf8_decode(ValorParametroRequest('usuario'));  
			$direccion = utf8_decode(ValorParametroRequest('direccion')); 
			$email  = utf8_decode(ValorParametroRequest('email'));  
			$telefono  = utf8_decode(ValorParametroRequest('telefono')); 
			
			$resultado = UpdatePerito($nombre, $apellido, $cuil, $tipoperito, $parteoficio, $usuario, $direccion, $email, $telefono, $id);
			
			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){			
			RetronaXML($e->getMessage());						
		}
	}
	
	if ($_REQUEST['FUNCION']=="InsertarPeritoNuevo"){
		try{			
			$nombre = utf8_decode(ValorParametroRequest('nombre'));  
			$apellido = utf8_decode(ValorParametroRequest('apellido'));  
			$cuil = utf8_decode(ValorParametroRequest('cuil'));  
			$tipoperito = utf8_decode(ValorParametroRequest('tipoperito'));  
			$parteoficio = utf8_decode(ValorParametroRequest('parteoficio'));  
			$usuario = utf8_decode(ValorParametroRequest('usuario'));  
			$direccion = utf8_decode(ValorParametroRequest('direccion')); 
			$email  = utf8_decode(ValorParametroRequest('email'));  
			$telefono  = utf8_decode(ValorParametroRequest('telefono')); 
			
			$resultado = @InsertarPeritoNuevo($nombre, $apellido, $cuil, $tipoperito, $parteoficio, $usuario, $direccion, $email, $telefono);
			
			if($resultado > 0) {
				RetornaDatosJSON( array("result" => "OK", "ID" => $resultado));						
			}
			else  RetronaXML("FALLO");		
			
		}catch (Exception $e){			
			RetronaXML($e->getMessage());								
		}
	}
		
			$txtsindico = utf8_decode(ValorParametroRequest('txtsindico')); 
	if ($_REQUEST['FUNCION']=="UpdateConcursoyquiebras"){
		try{
		
			$txtsindico = utf8_decode(ValorParametroRequest('txtsindico')); 
			$txtdireccion = utf8_decode(ValorParametroRequest('txtdireccion'));
			$txtlocaclidad = utf8_decode(ValorParametroRequest('txtlocaclidad'));
			$txtfuero = utf8_decode(ValorParametroRequest('txtfuero'));
			$txttelefono = utf8_decode(ValorParametroRequest('txttelefono'));
			$txtjurisdiccion = utf8_decode(ValorParametroRequest('txtjurisdiccion'));
			$txtjuzgado = utf8_decode(ValorParametroRequest('txtjuzgado'));
			$txtsecretaria = utf8_decode(ValorParametroRequest('txtsecretaria'));
			$fechaconcurso = utf8_decode(ValorParametroRequest('fechaconcurso'));
			$fechaquiebra = utf8_decode(ValorParametroRequest('fechaquiebra'));
			$fechaart32 = utf8_decode(ValorParametroRequest('fechaart32'));
			$fechaart200 = utf8_decode(ValorParametroRequest('fechaart200'));
			$fverificacioncredito = utf8_decode(ValorParametroRequest('fverificacioncredito'));
			$usuario = utf8_decode(ValorParametroRequest('usuario'));
			$nroorden = utf8_decode(ValorParametroRequest('nroorden'));
			$montoprivilegio = utf8_decode(ValorParametroRequest('montoprivilegio'));
			$montoquirografario = utf8_decode(ValorParametroRequest('montoquirografario'));
							
			$resultado=UpdateConcursoyquiebras($txtsindico, $txtdireccion, $txtlocaclidad, $txtfuero, 
							$txttelefono, $txtjurisdiccion, $txtjuzgado, 
							$txtsecretaria, $fechaconcurso, $fechaquiebra, 
							$fechaart32, $fechaart200, $fverificacioncredito, 
							$usuario, $nroorden, $montoprivilegio, $montoquirografario);
			
			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){			
			RetronaXML($e->getMessage());								
		}
	}
	
	if ($_REQUEST['FUNCION']=="InsertarCuotas"){
		try{
			$txtFecha = utf8_decode(ValorParametroRequest('txtFecha')); 
			$cantcuota = utf8_decode(ValorParametroRequest('cantcuota')); 
			$periodicidadCuotas = utf8_decode(ValorParametroRequest('periodicidadCuotas')); 
			$txtMonto = utf8_decode(ValorParametroRequest('txtMonto')); 
			$usuario = utf8_decode(ValorParametroRequest('usuario')); 
			$nroorden = utf8_decode(ValorParametroRequest('nroorden')); 
			$cmbTipo = utf8_decode(ValorParametroRequest('cmbTipo')); 
						
			$resultado = @InsertarCuotas($txtFecha, $cantcuota, $periodicidadCuotas, $txtMonto, $usuario, $nroorden, $cmbTipo);
			
			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){			
			RetronaXML($e->getMessage());				
		}
	}
	
	if ($_REQUEST['FUNCION']=="InsertarAcuerdoNuevo"){
		try{
			$txtfechavenc = utf8_decode(ValorParametroRequest('txtfechavenc')); 
			$txtmonto = utf8_decode(ValorParametroRequest('txtMonto')); 
			$txtfechapago = utf8_decode(ValorParametroRequest('txtfechapago')); 
			$txtobservaciones = utf8_decode(ValorParametroRequest('txtobservaciones')); 
			$usuario = utf8_decode(ValorParametroRequest('usuario')); 
			$nroorden = utf8_decode(ValorParametroRequest('nroorden')); 
			$NroPago = utf8_decode(ValorParametroRequest('NroPago')); 
			$txtFechaExtincion = utf8_decode(ValorParametroRequest('txtFechaExtincion')); 
			$cmbTipo = utf8_decode(ValorParametroRequest('cmbTipo')); 
			
			$resultado = @InsertarAcuerdoNuevo($txtfechavenc, $txtmonto, $txtfechapago, 
						$txtobservaciones, $usuario, $nroorden, $NroPago, $txtFechaExtincion, $cmbTipo);
			
			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){			
			RetronaXML($e->getMessage());								
		}
	}
	
	
	if ($_REQUEST['FUNCION']=="UpdateAcuerdosABM"){
		try{
			$txtfechavenc = utf8_decode(ValorParametroRequest('txtfechavenc')); 
			$txtmonto = utf8_decode(ValorParametroRequest('txtMonto')); 
			$txtfechapago = utf8_decode(ValorParametroRequest('txtfechapago')); 
			$txtobservaciones = utf8_decode(ValorParametroRequest('txtobservaciones')); 
			$usuario = utf8_decode(ValorParametroRequest('usuario')); 
			$nroorden = utf8_decode(ValorParametroRequest('nroorden')); 
			$NroPago = utf8_decode(ValorParametroRequest('NroPago')); 
			$txtFechaExtincion = utf8_decode(ValorParametroRequest('txtFechaExtincion')); 
			$cmbTipo = utf8_decode(ValorParametroRequest('cmbTipo')); 

			$resultado = UpdateAcuerdosABM($txtfechavenc, $txtmonto, $txtfechapago, 
						$txtobservaciones, $usuario, $nroorden, $NroPago, $txtFechaExtincion, $cmbTipo);
			
			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){			
			RetronaXML($e->getMessage());											
		}
	}
	
	if ($_REQUEST['FUNCION']=="InsertarEventoCYQNuevo"){
		try{
			$txtfecha = utf8_decode(ValorParametroRequest('txtfecha')); 
			$txtobservaciones = utf8_decode(ValorParametroRequest('txtobservaciones')); 
			$usuario = utf8_decode(ValorParametroRequest('usuario')); 
			$cmbEventos = utf8_decode(ValorParametroRequest('cmbEventos')); 
			$nroorden = utf8_decode(ValorParametroRequest('nroorden')); 
						
			$resultado = InsertarEventoCYQNuevo($txtfecha, $txtobservaciones, $usuario, $cmbEventos, $nroorden);
			
			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){
			RetronaXML($e->getMessage());								
		}
	}
	
	if ($_REQUEST['FUNCION']=="UpdateEventosCYQABM"){
				
		$txtfecha = utf8_decode(ValorParametroRequest('txtfecha')); 
		$txtobservaciones = utf8_decode(ValorParametroRequest('txtobservaciones')); 
		$usuario = utf8_decode(ValorParametroRequest('usuario')); 
		$cmbEventos = utf8_decode(ValorParametroRequest('cmbEventos')); 
		$nroorden = utf8_decode(ValorParametroRequest('nroorden')); 
		$nroevento = utf8_decode(ValorParametroRequest('nroevento')); 
		
		try{
			$resultado = UpdateEventosCYQABM($txtfecha, $txtobservaciones, $usuario, $cmbEventos, $nroorden, $nroevento);
			
			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){
			RetronaXML($e->getMessage());								
		}	
	}
	
	if ($_REQUEST['FUNCION']=="UpdateMasDatosJuicios"){
	
		$Domicilio = utf8_decode(ValorParametroRequest('Domicilio')); 
		$Telefonos = utf8_decode(ValorParametroRequest('Telefonos')); 
		$Fax = utf8_decode(ValorParametroRequest('Fax')); 
		$Email = utf8_decode(ValorParametroRequest('Email')); 
		$usuario = utf8_decode(ValorParametroRequest('usuario')); 
		$idJuicio = utf8_decode(ValorParametroRequest('idJuicio')); 
		
		try{
			$resultado = UpdateMasDatosJuicios($Domicilio, $Telefonos, $Fax, $Email, $usuario, $idJuicio);						
			
			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){
			RetronaXML($e->getMessage());								
		}
	}	
	
    if ($_REQUEST['FUNCION']=="UpdateAdmin"){

		$NroJuicio 		  = utf8_decode(ValorParametroRequest('NroJuicio'));
		$cmbJurisdiccion  = utf8_decode(ValorParametroRequest('cmbJurisdiccion'));
		$cmbFuero 		  = utf8_decode(ValorParametroRequest('cmbFuero'));
		$cmbJuzgadoNro 	  = utf8_decode(ValorParametroRequest('cmbJuzgadoNro'));
		$cmbSecretaria 	  = utf8_decode(ValorParametroRequest('cmbSecretaria'));
		$txtNroExp 		  = utf8_decode(ValorParametroRequest('txtNroExp'));
		$txtAnioExp 	  = utf8_decode(ValorParametroRequest('txtAnioExp'));

		$txtResProbable = utf8_decode(ValorParametroRequest("txtResProbable")); 
		$cmbEstado = utf8_decode(ValorParametroRequest("cmbEstado")); 
		$usuario = utf8_decode(ValorParametroRequest('usuario'));
		
		try{	
			$resultado_update = false;
			
			$result = UpdateResultado($NroJuicio, $txtResProbable, $cmbEstado, $usuario);		
	
			if ($result){
				if (GuardarInstancia($NroJuicio, $cmbJurisdiccion, $cmbFuero, $cmbJuzgadoNro, $cmbSecretaria, $txtNroExp, $txtAnioExp)){	
					$resultado_update = true;
				}
			}
			
			if($resultado_update) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e) {
			RetronaXML($e->getMessage());								
		}		
	}
	
    if ($_REQUEST['FUNCION']=="UpdateSentencia"){
		$cmbEventos = utf8_decode(ValorParametroRequest('cmbEventos')); 
		$txtfechasentencia = utf8_decode(ValorParametroRequest('txtfechasentencia')); 
		$txtfecharecep = utf8_decode(ValorParametroRequest('txtfecharecep')); 
		$jtsentencia = utf8_decode(ValorParametroRequest('jt_sentencia')); 
		$cmbsentencia = utf8_decode(ValorParametroRequest('cmbsentencia')); 
		$usuario = utf8_decode(ValorParametroRequest('usuario')); 
		$jt_id = utf8_decode(ValorParametroRequest('jt_id')); 
		$txtimportehonorarios = utf8_decode(ValorParametroRequest('txtimportehonorarios')); 
		$txtimporteintereses = utf8_decode(ValorParametroRequest('txtimporteintereses')); 
		$txtimportetasajusticia = utf8_decode(ValorParametroRequest('txtimportetasajusticia')); 
		$instancia = utf8_decode(ValorParametroRequest('instancia')); 
		$txtMontoCondena = utf8_decode(ValorParametroRequest('txtMontoCondena')); 
		$txtPorcentajeIncapacidad = utf8_decode(ValorParametroRequest('txtPorcentajeIncapacidad')); 
		
		try{
						
			$resultado = @UpdateSentencia($txtfechasentencia, $txtfecharecep, $jtsentencia, 
								$cmbsentencia,  $usuario, $jt_id, $txtimportehonorarios, 
								$txtimporteintereses, $txtimportetasajusticia, $instancia, 
								$txtMontoCondena, $txtPorcentajeIncapacidad);
			
			if($resultado)  {
				RetronaXML("OK");		
			}
			else   RetronaXML("FALLO");		
			
		}catch (Exception $e) {
			RetronaXML($e->getMessage());								
		}
	}
	
	if ($_REQUEST['FUNCION']=="InsertarEventoNuevo"){
			 
		$txtfecha = utf8_decode(ValorParametroRequest('txtfecha')); 
		$txtfechavencimiento = utf8_decode(ValorParametroRequest('txtfechavencimiento')); 
		$nrojuicio = utf8_decode(ValorParametroRequest('nrojuicio')); 
		$txtobservaciones = utf8_decode(ValorParametroRequest('txtobservaciones')); 
		$usuario = utf8_decode(ValorParametroRequest('usuario')); 
		$cmbEventos = utf8_decode(ValorParametroRequest('cmbEventos')); 
		
		try{
			$resultado = InsertarEventoNuevo($txtfecha, $txtfechavencimiento, $txtobservaciones , $nrojuicio, $usuario, $cmbEventos);
			$_SESSION["PeritajesABMWebForm"]["id"] = $resultado; 
			
			if($resultado > 0) {
				RetronaXML('OK');
				//RetronaParamsXML( resultado );						
			}
			else  RetronaXML("FALLO");		
			
		}catch (Exception $e) {
			RetronaXML($e->getMessage());								
		}
	}
	
    if ($_REQUEST['FUNCION']=="UpdateEventosABM"){
		 
		$txtfecha = utf8_decode(ValorParametroRequest('txtfecha')); 
		$txtfechavencimiento = utf8_decode(ValorParametroRequest('txtfechavencimiento')); 
		$EventoID = utf8_decode(ValorParametroRequest('EventoID')); 
		$txtobservaciones = utf8_decode(ValorParametroRequest('txtobservaciones')); 
		$usuario = utf8_decode(ValorParametroRequest('usuario')); 
		$cmbEventos = utf8_decode(ValorParametroRequest('cmbEventos')); 
		
		try{
			$resultado = UpdateEventosABM($txtfecha, $txtfechavencimiento, $EventoID, $txtobservaciones, $usuario, $cmbEventos);

			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e) {
			RetronaXML($e->getMessage());								
		}
	}
	
    if ($_REQUEST['FUNCION']=="InsertarPeritajeNuevo"){
				
		$txtFechaAsignacion = utf8_decode(ValorParametroRequest('txtFechaAsignacion')); 
		$txtFechaPericia = utf8_decode(ValorParametroRequest('txtFechaPericia')); 
		$txtFVencImpug = utf8_decode(ValorParametroRequest('txtFVencImpug')); 
		$cmbPericia = utf8_decode(ValorParametroRequest('cmbPericia')); 
		$txtResultados = utf8_decode(ValorParametroRequest('txtResultados')); 
		$nrojuicio = utf8_decode(ValorParametroRequest('nrojuicio')); 
		$usuario = utf8_decode(ValorParametroRequest('usuario')); 
		$incapacidadDemanda = utf8_decode(ValorParametroRequest('incapacidadDemanda')); 
		$incapacidadPeritoMedico = utf8_decode(ValorParametroRequest('incapacidadPeritoMedico')); 
		$ibmArt = utf8_decode(ValorParametroRequest('ibmArt')); 
		$ibmPericial = utf8_decode(ValorParametroRequest('ibmPericial')); 
		$impugnacion = utf8_decode(ValorParametroRequest('impugnacion')); 
		$idperito = utf8_decode(ValorParametroRequest('idperito')); 
		
		try{
			$resultado = InsertarPeritajeNuevo($txtFechaAsignacion, $txtFechaPericia, $txtFVencImpug, 
				$cmbPericia, $txtResultados, $nrojuicio, $usuario,
				$incapacidadDemanda, $incapacidadPeritoMedico, 
				$ibmArt, $ibmPericial, $impugnacion, $idperito);
				

			if($resultado) RetronaXML($resultado);		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e) {
			RetronaXML($e->getMessage());								
		}
			
	}
	
    if ($_REQUEST['FUNCION']=="UpdatePeritajesABM"){
		
		$txtFechaAsignacion = utf8_decode(ValorParametroRequest('txtFechaAsignacion')); 
		$txtFechaPericia = utf8_decode(ValorParametroRequest('txtFechaPericia')); 
		$txtFVencImpug = utf8_decode(ValorParametroRequest('txtFVencImpug')); 
		$cmbPericia = utf8_decode(ValorParametroRequest('cmbPericia')); 
		$txtResultados = utf8_decode(ValorParametroRequest('txtResultados')); 
		$nrojuicio = utf8_decode(ValorParametroRequest('nrojuicio')); 
		$usuario = utf8_decode(ValorParametroRequest('usuario')); 
		$incapacidadDemanda = utf8_decode(ValorParametroRequest('incapacidadDemanda')); 
		$incapacidadPeritoMedico = utf8_decode(ValorParametroRequest('incapacidadPeritoMedico')); 
		$ibmArt = utf8_decode(ValorParametroRequest('ibmArt')); 
		$ibmPericial = utf8_decode(ValorParametroRequest('ibmPericial')); 
		$impugnacion = utf8_decode(ValorParametroRequest('impugnacion')); 
		$idperito = utf8_decode(ValorParametroRequest('idperito')); 
		
		if($impugnacion == 'X') $impugnacion = '';
		
		try{	
			$resultado = @UpdatePeritajesABM($txtFechaAsignacion, $txtFechaPericia,$txtFVencImpug, $cmbPericia, 
				$txtResultados, $nrojuicio, $usuario,$incapacidadDemanda, 
				$incapacidadPeritoMedico, $ibmArt, $ibmPericial, $impugnacion, $idperito);
			
			if($resultado) 
				RetronaXML($nrojuicio);		
				// RetronaParamsXML( array("result"=>"OK","ID"=>$nrojuicio) );						
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e){
			RetronaXML($e->getMessage());											
			//RetronaXML("fallo VariablesSinSeteo");											
		}		
	}
	
	
    if ($_REQUEST['FUNCION']=="UpdateInstanciaABMAlta"){

		$JuicioEnTramite = utf8_decode(ValorParametroRequest('NroJuicio')); 
		$Jurisdiccion = utf8_decode(ValorParametroRequest('cmbJurisdiccion')); 
		$Fuero = utf8_decode(ValorParametroRequest('cmbFuero')); 
		$Juzgado = utf8_decode(ValorParametroRequest('cmbJuzgadoNro')); 
		$Secretaria = utf8_decode(ValorParametroRequest('cmbSecretaria')); 
		
		$Instancia = ''; 
		$NroExpediente = utf8_decode(ValorParametroRequest('txtNroExp')); 
		$AnioExpediente = utf8_decode(ValorParametroRequest('txtAnioExp')); 
		$Motivo = utf8_decode(ValorParametroRequest('cmbMotivo')); 
		$Detalle = utf8_decode(ValorParametroRequest('txtDetalle')); 
		$LoginName = utf8_decode(ValorParametroRequest('usuario')); 
		
		$nroInstancia = utf8_decode(ValorParametroRequest('nroInstancia')); 
		$EstadoMediacion = ''; 
		$FechaIngreso = utf8_decode(ValorParametroRequest('txtFecha')); 
		
		try{
			$resultado = UpdateInstanciaABMAlta($JuicioEnTramite, $Jurisdiccion, $Fuero, $Juzgado, 
				$Secretaria, $Instancia, $NroExpediente, $AnioExpediente, $Motivo, $Detalle, $LoginName, $EstadoMediacion, $FechaIngreso);	

			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e) {
			RetronaXML($e->getMessage());								
		}
	}
	
    if ($_REQUEST['FUNCION']=="UpdateInstanciaAbmMod"){

		$JuicioEnTramite = utf8_decode(ValorParametroRequest('NroJuicio')); 
		$Jurisdiccion = utf8_decode(ValorParametroRequest('cmbJurisdiccion')); 
		$Fuero = utf8_decode(ValorParametroRequest('cmbFuero')); 
		$Juzgado = utf8_decode(ValorParametroRequest('cmbJuzgadoNro')); 
		$Secretaria = utf8_decode(ValorParametroRequest('cmbSecretaria')); 
		
		$Instancia = ''; 
		$NroExpediente = utf8_decode(ValorParametroRequest('txtNroExp')); 
		$AnioExpediente = utf8_decode(ValorParametroRequest('txtAnioExp')); 
		$Motivo = utf8_decode(ValorParametroRequest('cmbMotivo')); 
		$Detalle = utf8_decode(ValorParametroRequest('txtDetalle')); 
		$LoginName = utf8_decode(ValorParametroRequest('usuario')); 
		
		$nroInstancia = utf8_decode(ValorParametroRequest('nroInstancia')); 
		$EstadoMediacion = ''; 
		$FechaIngreso = utf8_decode(ValorParametroRequest('txtFecha')); 
		
		try{
			$resultado = UpdateInstanciaAbmMod($JuicioEnTramite, $Jurisdiccion, $Fuero, $Juzgado, $Secretaria, 
				$Instancia, $NroExpediente, $AnioExpediente, $Motivo, 
				$Detalle, $LoginName, $nroInstancia, $EstadoMediacion, $FechaIngreso);

			if($resultado) RetronaXML("OK");		
			else    	   RetronaXML("FALLO");		
			
		}catch (Exception $e) {
			RetronaXML($e->getMessage());								
		}
	}
}
