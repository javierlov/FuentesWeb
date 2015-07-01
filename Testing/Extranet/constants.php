<?php
// Base de Datos - Conexión - PRUEBA..
define("DB_ENGINE", "oracle");
define("DB_USER", "art");
define("DB_PASS", "laKm");
define("DB_SERV", "(DESCRIPTION = (ADDRESS_LIST = (ADDRESS = (COMMUNITY = tcp) (PROTOCOL = TCP) (Host = 10.1.1.3) (Port = 1523))) (CONNECT_DATA = (SERVICE_NAME = PARTTEST) (SERVER = DEDICATED)))");

// Base de Datos - Conexión - CONTINGENCIA..
//define("DB_ENGINE_CONTINGENCIA", "oracle");
//define("DB_USER_CONTINGENCIA", "art");
//define("DB_PASS_CONTINGENCIA", "t7yzs7pzh4SC=");
//define("DB_SERV_CONTINGENCIA", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.1.1.54)(PORT = 1522))(CONNECT_DATA = (SID = PART)))");

// Base de Datos - Conexión - RHPRO..
//define("DB_USER_RHPRO", "postulantes");
//define("DB_PASS_RHPRO", "xL/Fw3pzgoWC");
//define("DB_SERV_RHPRO", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.60.1.3)(PORT = 1526))(CONNECT_DATA = (SID = RHPROCP)))");

// Base de datos - Conexión - SQL Server..
//define("DB_USER_MSSQL", "webadmin");
//define("DB_PASS_MSSQL", "provartwww");
//define("DB_DATABASENAME", "provinciaart");
//define("DB_SERV_MSSQL", "ntweb");

// Base de Datos - Varios..
define("DB_QUOTE", "'");

// Web..
define("STORAGE_DATA_PATH", "F:/Storage_Data/");
define("STORAGE_EXTRANET", "F:/Storage_Extranet/");

define("DATA_CARGA_MASIVA_TRABAJADORES", STORAGE_DATA_PATH."Web/Carga_Masiva_Trabajadores_Desarrollo/");
define("DATA_CARGA_MASIVA_TRABAJADORES_EXTERNAL", "\\\\ntintraweb\Storage_Data\Web\Carga_Masiva_Trabajadores_Desarrollo\\");
define("DATA_CARTA_COTIZACION", STORAGE_DATA_PATH."Web/Carta_Cotizacion_Desarrollo/");
define("DATA_CARTA_COTIZACION_EXTERNAL", "\\\\ntintraweb\Storage_Data\Web\Carta_Cotizacion_Desarrollo\\");
define("DATA_CERTIFICADOS_COBERTURA", STORAGE_DATA_PATH."Web/Certificados_Cobertura_Desarrollo/");
define("DATA_CERTIFICADOS_COBERTURA_EXTERNAL", "\\\\ntintraweb\Storage_Data\Web\Certificados_Cobertura_Desarrollo\\");
define("DATA_CV_PATH", STORAGE_DATA_PATH."Web/CVs_Desarrollo/");
define("DATA_FORMULARIO_ESTABLECIMIENTOS", STORAGE_DATA_PATH."Web/Formulario_Establecimientos_Desarrollo/");
define("DATA_FORMULARIO_ESTABLECIMIENTOS_EXTERNAL", "\\\\ntintraweb\Storage_Data\Web\Formulario_Establecimientos_Desarrollo\\");
define("DATA_INFORMES_INGENIERIA_SINIESTRALIDAD", STORAGE_DATA_PATH."Web/Informes_Ingenieria_Siniestralidad_Desarrollo/");
define("DATA_ORGANISMOS_PUBLICOS_RESUMEN", STORAGE_DATA_PATH."Emision/Organismos_Publicos_Desarrollo/");
define("DATA_PDF_SERVER", STORAGE_DATA_PATH."Prevencion/Desarrollo/Resolucion463/");
define("DATA_PREVENCION", STORAGE_DATA_PATH."Prevencion/Desarrollo/");
define("DATA_REPORTE_RESPONSABILIDAD_CIVIL", STORAGE_DATA_PATH."Suscripciones/Responsabilidad_Civil/Desarrollo/");
define("DATA_SISTEMA_GESTION_RRHH", STORAGE_DATA_PATH."Web/Sistema_Gestion_RRHH_Desarrollo/");
define("DATA_SISTEMA_GESTION_RRHH_EXTERNAL", "\\\\ntintraweb\Storage_Data\Web\Sistema_Gestion_RRHH_Desarrollo\\");
define("GRAFICO_CARTA_COTIZACION", STORAGE_DATA_PATH."Suscripciones/Carta_Solicitud_Cotizacion/Desarrollo/");
define("IMAGENES_ARTICULOS", STORAGE_EXTRANET."articulos_DESARROLLO/imagenes/");
define("IMAGENES_STATUS_BCRA", STORAGE_EXTRANET."status_bcra/");
define("LOCAL_PATH_DESCRIPCION_PUESTO", "/modules/evaluacion_puesto/");
define("LOCAL_PATH_FORMULARIO_ESTABLECIMIENTOS", "/modules/formulario_establecimientos/");
define("LOCAL_PATH_PAGO_TRANSFERENCIA", "/modules/pago_transferencia/");
define("LOCAL_PATH_PROGRAMA_INCENTIVOS", "/modules/programa_incentivos_2012_2013/");
define("LOCAL_PATH_USERS_WEB", "/modules/admin_users_web/");
?>