<?php
// Base de Datos - Conexión - PRUEBA..
define("DB_ENGINE", "oracle");
define("DB_USER", "art");
define("DB_PASS", "laKm");
define("DB_SERV", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.1.1.56)(PORT = 1521))(CONNECT_DATA = (SID = PARTD)))");

// Base de Datos - Varios..
define("DB_QUOTE", "'");

// General..
define("CHARSET", "ISO-8859-1");
define("DELEGACION_CAPITAL", 840);

// Rutas..
define("STORAGE_DATA_PATH", "F:/Storage_Data/");

define("DATA_SUSCRIPCIONES_CARTA_SOLICITUD_COTIZACION", STORAGE_DATA_PATH."Suscripciones/Carta_Solicitud_Cotizacion/Desarrollo/");

// Sitio..
define("SITE_TITLE", "Provincia ART");
?>