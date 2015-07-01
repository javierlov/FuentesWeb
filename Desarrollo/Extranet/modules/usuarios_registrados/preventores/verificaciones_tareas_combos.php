<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/provart/combo.php");
	$sql =
		"SELECT GD_id id, GD_CODIGO || ' - '||GD_DESCRIPCION detalle
	       FROM HYS.HGD_GRUPODENUNCIA, afi.aes_establecimiento 
		  WHERE gd_fechabaja IS NULL
		    AND (gd_tipo = art.hys.get_tipo_estab_prev( :cuit ,es_nroestableci )) 
            AND (gd_codigo NOT IN ('TE', 'PE', 'CG', 'BG', 'PG'))		  
			AND es_id = :estableci
 	   ORDER BY gd_codigo";

	$comboGrupoDenuncia = new Combo($sql, "grupoDenuncia");
	$comboGrupoDenuncia->addParam(":cuit", $_SESSION["CARGA_TAREA"]["cuit"]);
	$comboGrupoDenuncia->addParam(":estableci", $_SESSION["CARGA_TAREA"]["establecimiento"]);
	$comboGrupoDenuncia->setClass("combo");
	$comboGrupoDenuncia->setOnChange("cambiarGrupoDenuncia(this.value)");
?> 