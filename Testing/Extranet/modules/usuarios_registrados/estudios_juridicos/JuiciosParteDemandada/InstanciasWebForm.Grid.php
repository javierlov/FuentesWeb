<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/GridDos.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/usuarios_registrados/estudios_juridicos/FuncionesLegales.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/CrearLog.php");

function getGrid($pj_idjuicioentramite) {	
	
	$showProcessMsg = false;	
		
	$pagina = 1;
	if (isset($_REQUEST["pagina"]))
		$pagina = $_REQUEST["pagina"];
	
	$sql = ObtenerInstancias();	
	$params = array();
	$params[":pj_idjuicioentramite"] = $pj_idjuicioentramite;
		
	$grilla = new GridDos(1, 15);
		
	$grilla->addColumn(new Column("Instancia", 	0, true, false,	-1, "", "", "", -1, false));	
	$grilla->addColumn(new Column("Expediente", 0, true, false,	-1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Fuero", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Juzgado", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Secretaria", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Motivo", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("F. Ingreso", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("F. Sentencia", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("F. Notificacion", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Observaciones", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Importe Sentencia", 0, true, false, -1, "", "", "", -1, false));	
	$grilla->addColumn(new Column("Importe Capital", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Importe Honorarios", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Importe Intereses", 0, true, false, -1, "", "", "", -1, false));		
	$grilla->addColumn(new Column("Tasa Justicia", 0, true, false, -1, "", "", "", -1, false));		
	
	//$grilla->addColumn(new Column("CJ", 0, true, false, -1, "", "", "", -1, false));		
	//$grilla->addColumn(new Column("M", 0, true, false, -1, "", "", "", -1, false));			
	$grilla->addColumn(new Column("CJ", 0, true, false, -1, "btnPdf", "/index.php?pageid=103&accion=nuevo", "", -1, true, -1, "Nuevo"));
	$grilla->addColumn(new Column("M", 0, true, false, -1, "btnPdf", "/index.php?pageid=103&accion=modif", "", -1, true, -1, "Modificar"));
    
    $grilla->setColsSeparator(false);
    $grilla->setPageNumber($pagina);
    $grilla->setParams($params);    
    $grilla->setRowsSeparatorColor("#c0c0c0");  
    $grilla->setShowTotalRegistros(true);   
    $grilla->setSql($sql);  
    
    $grilla->setUseTmpIframe(false);
    $grilla->setTableStyle("GridTableCiiu");
    
	return $grilla->Draw(false);
}


function ObtenerInstancias(){
    
        $strqry = "Select 
                Instancia ¿Instancia?, 
                expediente ¿expediente?, 
                fuero ¿fuero?, 
                juzgado ¿juzgado?, 
                secretaria  ¿secretaria?,  
                motivo ¿motivo?, 
                FechaIngreso ¿FechaIngreso?, 
                FechaSentencia ¿FechaSentencia?, 
                FechaNotificacion ¿FechaNotificacion?, 
                Observaciones ¿Observaciones?,          
                ImporteSentencia ¿ImporteSentencia?, 
                ImporteCapital ¿ImporteCapital?,  
                ImporteHonorarios ¿ImporteHonorarios?, 
                ImporteIntereses ¿ImporteIntereses?, 
                TasaJusticia  ¿TasaJusticia?, 
                AccionCambioJuzgado ¿AccionCambioJuzgado?, 
                AccionModificar ¿AccionModificar?
    		
		From (SELECT lin_instancia.in_descripcion  Instancia , 
			nvl2(ij_nroexpediente, ij_nroexpediente || '/'|| ij_anioexpediente,'')  expediente , 
			lfu_fuero.fu_descripcion  fuero , 
			a.ij_idjuzgado  juzgado , 
			lsc_secretaria.sc_descripcion  secretaria ,  
			lmc_motivocambiojuzgado.mc_descripcion  motivo , 
			a.ij_fechatraspaso  FechaIngreso , 
			a.ij_fechasentencia  FechaSentencia , 
			a.ij_fecharecepsentencia  FechaNotificacion , 
			a.ij_observaciones  Observaciones , 			
			NVL ((SELECT SUM (ir_importesentencia) 
					  FROM legales.lir_importesreguladosjuicio 
					 WHERE ir_idjuicioentramite = a.ij_idjuicioentramite 
					   AND ir_idinstancia = a.ij_id 
					   AND ir_fechabaja IS NULL), 0)  ImporteSentencia , 
			NVL ((SELECT SUM (ir_importesentencia) 
				  FROM legales.lir_importesreguladosjuicio 
				 WHERE ir_idjuicioentramite = a.ij_idjuicioentramite 
				   AND ir_idinstancia = a.ij_id 
				   AND ir_aplicacion = 'C' 
				   AND ir_fechabaja IS NULL),0 )  ImporteCapital ,  
			NVL((SELECT SUM (ir_importesentencia) 
				   FROM legales.lir_importesreguladosjuicio 
				  WHERE ir_idjuicioentramite = a.ij_idjuicioentramite 
					AND ir_idinstancia = a.ij_id 
				AND ir_aplicacion = 'H' 
				AND ir_fechabaja IS NULL), 0)  ImporteHonorarios , 
			NVL 
				((SELECT SUM (ir_importesentencia) 
				   FROM legales.lir_importesreguladosjuicio 
				  WHERE ir_idjuicioentramite = a.ij_idjuicioentramite 
					AND ir_idinstancia = a.ij_id 
					AND ir_aplicacion = 'I' 
					AND ir_fechabaja IS NULL), 0)  ImporteIntereses , 
			NVL ((SELECT SUM (ir_importesentencia) 
				   FROM legales.lir_importesreguladosjuicio 
				  WHERE ir_idjuicioentramite = a.ij_idjuicioentramite 
					AND ir_idinstancia = a.ij_id 
					AND ir_aplicacion = 'T' 
					AND ir_fechabaja IS NULL), 0 )  TasaJusticia , 
			1  AccionCambioJuzgado , 
			1  AccionModificar 
				 FROM legales.lij_instanciajuicioentramite a, 
					  legales.lmc_motivocambiojuzgado, 
					  legales.lju_jurisdiccion, 
					  legales.ljz_juzgado, 
					  legales.lfu_fuero, 
					  legales.lin_instancia, 
					  legales.lsc_secretaria 
				WHERE lmc_motivocambiojuzgado.mc_id = a.ij_idmotivocambiojuzgado 
				  AND lju_jurisdiccion.ju_id = a.ij_idjurisdiccion 
				  AND ljz_juzgado.jz_id = a.ij_idjuzgado 
				  AND lfu_fuero.fu_id = a.ij_idfuero 
				  AND lin_instancia.in_id = a.ij_idinstancia 
				  AND lsc_secretaria.sc_id = a.ij_idsecretaria 
				  AND a.ij_idjuicioentramite = :pj_idjuicioentramite
				ORDER BY ij_id DESC )";
			
	return $strqry;  
}
