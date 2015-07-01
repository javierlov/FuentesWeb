<?php
// Base de Datos - Conexión - PRUEBA..
define("DB_ENGINE", "oracle");
define("DB_USER", "art");
define("DB_PASS", "laKm");
define("DB_SERV", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.1.1.56)(PORT = 1521))(CONNECT_DATA = (SID = PARTD)))");

// Base de Datos - Conexión - RHPRO..
define("DB_USER_RHPRO", "REGISART");
define("DB_PASS_RHPRO", "ub7Gwret");
define("DB_SERV_RHPRO", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.60.1.3)(PORT = 1526))(CONNECT_DATA = (SID = RHPROCP)))");

// Base de Datos - Varios..
define("DB_QUOTE", "'");

// General..
define("CHARSET", "ISO-8859-1");
define("DEFAULT_IMAGE", "D:/WebServer/www/Intranet/images/img_not_found.gif");
define("DELEGACION_CAPITAL", 840);		// Definida en js tambien..

// Sitio..
define("SITE_PATH", "D:/WebServer/www/Intranet/");
define("SITE_TITLE", "Intranet de Provincia ART");

define("STORAGE_DATA_PATH", "D:/Storage_Data/");
define("STORAGE_PATH", "D:/Storage_Intranet/");
define("STORAGE_PATH_EXTRANET", "D:/Storage_Extranet/");

define("ATTACHMENTS_PATH", STORAGE_PATH."sistemas/gestion_sistemas/adjuntos/");
define("DATA_ARTICULOS_ARCHIVOS_PATH", STORAGE_PATH."articulos_portada/archivos/");		// NO PONERLE DESARROLLO ADELANTE..
define("DATA_AVISO_OBRA_PATH", STORAGE_PATH."aviso_de_obra/");
define("DATA_BOLETIN_OFICIAL_PATH", STORAGE_DATA_PATH."Web/Boletines/Boletin_Oficial/");
define("DATA_BUSQUEDAS_CORPORATIVAS_PATH", STORAGE_PATH."busquedas_corporativas/");
define("DATA_CELEBRACIONES_PATH", STORAGE_PATH."celebraciones/");
define("DATA_DESCARGABLES_PATH", STORAGE_PATH."descargables/");
define("DATA_INFORMES_DIRECTORES", STORAGE_PATH."sistemas/informes_directores/");
define("DATA_INFORMES_GESTION", STORAGE_PATH."control_gestion/informes_gestion/");
define("DATA_SEGURIDAD_INFORMATICA_PATH", $_SERVER["DOCUMENT_ROOT"]."/modules/boletines_seguridad_informatica/");
define("DATA_SEGURIDAD_INFORMATICA_RELATIVE_PATH", "/modules/boletines_seguridad_informatica/");
define("IMAGES_ARTERIA_PATH", STORAGE_PATH."arteria_noticias/imagenes/");
define("IMAGES_ARTICULOS_PATH", STORAGE_PATH."articulos_portada/imagenes/");		// NO PONERLE DESARROLLO ADELANTE..
define("IMAGES_BANNERS_PATH", STORAGE_PATH."banners/imagenes/");
define("IMAGES_BENEFICIOS_PATH", STORAGE_PATH."beneficios/imagenes/");		// NO PONERLE DESARROLLO ADELANTE..
define("IMAGES_EDICION_PATH", STORAGE_PATH."edicion_imagenes/");
define("IMAGES_ENCUESTAS_CABECERA_PATH", STORAGE_PATH."encuestas/imagenes_cabecera/");
define("IMAGES_ENCUESTAS_OPCIONES_PATH", STORAGE_PATH."encuestas/imagenes_opciones/");
define("IMAGES_FOTOS_PATH", STORAGE_PATH."fotos_personales/");
define("IMAGES_MAPAS_RELATIVE_PATH", "/images/mapas/");
define("IMAGES_NOVEDADES_EXTRANET_PATH", STORAGE_PATH_EXTRANET."novedades/");
/*
define("DATA_ARTERIA_PATH", STORAGE_PATH."arteria_noticias/envios/");
define("DATA_BUSQUEDAS_INTERNAS_PATH", STORAGE_PATH."desarrollo_busquedas_internas/");
define("DATA_FOTOS_PATH", STORAGE_PATH."fotos/");
define("DATA_PORTADA_PATH", STORAGE_PATH."/desarrollo_portada/");
define("DATA_PORTADA_RELATIVE_PATH", "/desarrollo_portada/");
define("IMAGES_ARTICULOS_RELATIVE_PATH", "/images/articulos/");
define("IMAGES_MAPAS_PATH", $_SERVER["DOCUMENT_ROOT"]."/images/mapas/");
*/
?>