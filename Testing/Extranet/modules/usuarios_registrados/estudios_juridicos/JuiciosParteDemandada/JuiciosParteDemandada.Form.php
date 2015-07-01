<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/Common/Clases/Tabla.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");

function Get_Formulario($usuario, $CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio){
	TablaDatosUsuario($usuario);
	SegundaTabla($CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio);
}


function SegundaTabla($CodCaratula, $NroExpediente, $NroCarpeta, $tipoJuicio){
		
	$CodCaratula=htmlspecialchars($CodCaratula);
	$NroExpediente=htmlspecialchars($NroExpediente);
	$NroCarpeta=htmlspecialchars($NroCarpeta);
	$tipoJuicio=htmlspecialchars($tipoJuicio);
	
	$tab = new Tabla(" cellspacing='0' cellpadding='0' width='98%' border='0' align='center' ");
	$tab->TR(" height='2px' colspan='4' ","");	
		$tab->TD(" colspan='5' class='item_grisClaroFndBlanco' "," Busqueda de Juicios ");
	$tab->TR("","");
		$tab->TD(" class='item_grisClaroFndBlanco' "," Caratula: ");
		$tab->TD("","<input name='codigoCaratula' type='text' id='idCaratula' class='numerico' value='$CodCaratula' />");
		$tab->TD(" class='item_grisClaroFndBlanco' "," Numero de expediente: ");
		$tab->TD(""," <input name='NroExpediente' type='text' id='txtNroExpediente' class='numerico' value='$NroExpediente' /> ");		
		$tab->TD(""," <div style='margin-left:8px; margin-top:8px;'>
					<div class='btnLimpiar'  id='botonLimpiar' /> </div> ");
	$tab->TR("","");
		$tab->TD(" class='item_grisClaroFndBlanco' "," Nro Carpeta: ");
		$tab->TD(""," <input name='NroCarpeta' type='text' id='txtNroCarpeta' class='numerico' style='Z-INDEX: 8' value='$NroCarpeta' />
						<font face='Verdana' style='FONT-SIZE: 8pt; FONT-WEIGHT: 700'> </font> ");
		$tab->TD("class='item_grisClaroFndBlanco' "," Tipo Juicio: ");
		$tab->TD("", SelectArrayOptions($tipoJuicio));	
		$tab->TD(""," <div style='margin-left:8px; margin-top:8px;'>								
					<div class='btnImprimir' id='botonImprimir'/>								
					</div> ");
	$tab->TR("","");
		$tab->TD("","");
		$tab->TD("","");
		$tab->TD("","");
		$tab->TD("","");
		$tab->TD(""," <div style='margin-left:8px; margin-top:8px;'>															
					<input class='btnBuscar' type='submit' value=''/> ");
	
	$tab->DibujarTabla();	
}

