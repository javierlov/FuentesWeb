<?
/*
Por ahora dejo esto sin seguridad, cuando se necesite seguridad, hay dos formas que me parecieron potables:
A) Usar un token. El cliente se loguea, el servidor devuelve un token y luego el cliente en cada llamada debe pasar
	 ese token..
B) El cliente al momento de llamar a algún método debe pasar siempre un usuario y contraseña..
*/
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Classes/nusoap/lib/nusoap.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");

/*
class Documento {
	var $lote;
	var $secuenciaLote;
	var $fechaImpresionLote;
	var $cajaLote;
	var $codigoTipoArchivo;
	var $descripcionTipoArchivo;
	var $clave;
	var $subclave1;
	var $subclave2;
	var $subclave3;
	var $subclave4;
	var $codigoDocumento;
	var $descripcionDocumento;
	var $cantidadHojas;
	var $remito;
	var $fechaImpresionRemito;
}
*/
class LoteDAO {
/**
	* getLote: XML..
	* Esta funcion retorna un listado de los documentos que componen el lote pasado como parámetro..
	* @return
	*/
	function getLote($lote) {
		global $conn;

		$xml = '<?xml version="1.0" encoding="utf-8"?>';
		$xml.= "<lote>";

		$params = array(":id" => $lote);
		$sql =
			"SELECT da_lote lote, da_seq_lote secuencia_lote,
							TO_CHAR(TRUNC(lo_fechaalta), 'YYYY-MM-DD') fechaimpresion_lote, lo_caja caja_lote,
							ta_codigo coddigo_tipoarchivo, ta_descripcion descr_tipoarchivo, ar_clave clave,
							art.archivo.getsubclave(ar_clave, 1) subclave1, art.archivo.getsubclave(ar_clave, 2) subclave2,
							art.archivo.getsubclave(ar_clave, 3) subclave3, art.archivo.getsubclave(ar_clave, 4) subclave4,
							td_codigo codigo_documento, td_descripcion descr_documento, da_hojas cantidadhojas, re_id remito,
							TO_CHAR(TRUNC(re_fechaalta), 'YYYY-MM-DD') fechaimpresion_remito
				 FROM archivo.rre_remito, archivo.rta_tipoarchivo, archivo.rtd_tipodocumento, archivo.rar_archivo,
							archivo.rda_detallearchivo, archivo.rlo_lote
				WHERE da_lote = lo_id
					AND da_fechabaja IS NULL
					AND da_idarchivo = ar_id
					AND ar_tipo = ta_id
					AND da_idtipodocumento = td_id
					AND lo_idremito = re_id(+)
					AND lo_id = :id
		 ORDER BY da_lote, da_seq_lote";
		$stmt = DBExecSql($conn, $sql, $params);
		while ($row = DBGetQuery($stmt)) {
			$xml.= "<documento>";
			foreach ($row as $clave => $valor)
				$xml.= "<".strtolower($clave).">".$valor."</".strtolower($clave).">";
			$xml.= "</documento>";
		}

		$xml.= "</lote>";
//		return new soapval("return", "xsd:string", base64_encode($xml));
		return new soapval("return", "xsd:string", $xml);
	}
}


function obtenerLote($lote) {
	$dao = new LoteDAO();
	return $dao->getLote($lote);
}


$url = '/ws/ws_lotes.php';
$server = new soap_server();
$server->configureWSDL('wsLotes', $url);
$server->wsdl->schemaTargetNamespace = $url;
/*
$server->wsdl->addComplexType("Documento",
															"complexType",
															"struct",
															"all",
															"",
															array("Lote" => array("name" => "Lote", "type" => "xsd:int"),
																		"SecuenciaLote" => array("name" => "SecuenciaLote", "type" => "xsd:int"),
																		"FechaImpresionLote" => array("name" => "FechaImpresionLote", "type" => "xsd:date"),
																		"CajaLote" => array("name" => "CajaLote", "type" => "xsd:string"),
																		"CodigoTipoArchivo" => array("name" => "CodigoTipoArchivo", "type" => "xsd:string"),
																		"DescripcionTipoArchivo" => array("name" => "DescripcionTipoArchivo", "type" => "xsd:string"),
																		"Clave" => array("name" => "Clave", "type" => "xsd:string"),
																		"Subclave1" => array("name" => "Subclave1", "type" => "xsd:string"),
																		"Subclave2" => array("name" => "Subclave2", "type" => "xsd:string"),
																		"Subclave3" => array("name" => "Subclave3", "type" => "xsd:string"),
																		"Subclave4" => array("name" => "Subclave4", "type" => "xsd:string"),
																		"CodigoDocumento" => array("name" => "CodigoDocumento", "type" => "xsd:string"),
																		"DescripcionDocumento" => array("name" => "DescripcionDocumento", "type" => "xsd:string"),
																		"CantidadHojas" => array("name" => "CantidadHojas", "type" => "xsd:int"),
																		"Remito" => array("name" => "Remito", "type" => "xsd:int"),
																		"FechaImpresionRemito" => array("name" => "FechaImpresionRemito", "type" => "xsd:date"))
);
*/
$server->register("obtenerLote",		// Nombre de la funcion
									array("lote" => "xsd:int"),		// Parametros de entrada
									array("return" => "xsd:string"),		// Parametros de salida
									$url
);

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);
?>