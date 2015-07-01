<?php
// Base de Datos - Conexión - PRUEBA..
define("DB_ENGINE", "oracle");
define("DB_USER", "art");
define("DB_PASS", "laKm");
define("DB_SERV", "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (COMMUNITY = tcp) (PROTOCOL = TCP) (Host = 10.1.1.3) (Port = 1523))) (CONNECT_DATA = (SERVICE_NAME = PARTTEST) (SERVER = DEDICATED)))");

// Base de Datos - Conexión - RHPRO..
//define("DB_USER_RHPRO", "REGISART");
//define("DB_PASS_RHPRO", "ub7Gwret");
//define("DB_SERV_RHPRO", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.60.1.3)(PORT = 1526))(CONNECT_DATA = (SID = RHPROCP)))");

// Base de Datos - Varios..
define("DB_QUOTE", "'");

// General..
define("DELEGACION_CAPITAL", 840);		// Definida en js tambien..

// Sitio..
define("SITE_PATH", "E:/WebServer/Development/Intranet/");
define("STORAGE_DATA_PATH", "F:/Storage_Data/");
define("STORAGE_PATH", "F:/Storage_Intranet/");
define("STORAGE_PATH_EXTRANET", "F:/Storage_Extranet/");

define("ATTACHMENTS_PATH", STORAGE_PATH."/sistemas/gestion_sistemas/adjuntos_pruebas/");
define("DATA_ARTERIA_PATH", STORAGE_PATH."arteria_noticias/envios/");
define("DATA_AVISO_OBRA_PATH", STORAGE_PATH."aviso_de_obra/desarrollo/");
define("DATA_BOLETIN_OFICIAL_PATH", STORAGE_DATA_PATH."Web/Boletines/Boletin_Oficial/");
define("DATA_BUSQUEDAS_CORPORATIVAS_PATH", STORAGE_PATH."desarrollo_busquedas_corporativas/");
define("DATA_BUSQUEDAS_INTERNAS_PATH", STORAGE_PATH."desarrollo_busquedas_internas/");
define("DATA_CELEBRACIONES_PATH", STORAGE_PATH."desarrollo_celebraciones/");
define("DATA_FOTOS_PATH", STORAGE_PATH."fotos/");
define("DATA_INFORMES_GESTION", STORAGE_PATH."control_gestion/informes_gestion/");
define("DATA_PORTADA_PATH", STORAGE_PATH."/desarrollo_portada/");
define("DATA_PORTADA_RELATIVE_PATH", "/desarrollo_portada/");
define("DATA_SEGURIDAD_INFORMATICA_PATH", $_SERVER["DOCUMENT_ROOT"]."/modules/sistemas/boletines_seguridad_informatica/");
define("DATA_SEGURIDAD_INFORMATICA_RELATIVE_PATH", "/modules/sistemas/boletines_seguridad_informatica/");
define("IMAGES_ARTERIA_PATH", STORAGE_PATH."desarrollo_arteria_noticias/imagenes/");
define("IMAGES_ARTICULOS_PATH", $_SERVER["DOCUMENT_ROOT"]."/images/articulos/");
define("IMAGES_ARTICULOS_RELATIVE_PATH", "/images/articulos/");
define("IMAGES_EDICION_PATH", STORAGE_PATH."edicion_imagenes/");
define("IMAGES_ENCUESTAS_CABECERA_PATH", STORAGE_PATH."encuestas/desarrollo_imagenes_cabecera/");
define("IMAGES_ENCUESTAS_OPCIONES_PATH", STORAGE_PATH."encuestas/desarrollo_imagenes_opciones/");
define("IMAGES_FOTOS_PATH", STORAGE_PATH."desarrollo_fotos_personales/");
define("IMAGES_MAPAS_PATH", $_SERVER["DOCUMENT_ROOT"]."/images/mapas/");
define("IMAGES_MAPAS_RELATIVE_PATH", "/images/mapas/");
define("IMAGES_NOVEDADES_EXTRANET_PATH", STORAGE_PATH_EXTRANET."novedades/");
define("SITE_TITLE", "Intranet de Provincia ART");
?>