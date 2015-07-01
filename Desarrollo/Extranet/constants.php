<?php
// Base de Datos - Conexión - PRUEBA..
define("DB_ENGINE", "oracle");
define("DB_USER", "art");
define("DB_PASS", "laKm");
define("DB_SERV", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.1.1.56)(PORT = 1521))(CONNECT_DATA = (SID = PARTD)))");

// Base de Datos - Conexión - CONTINGENCIA..
define("DB_ENGINE_CONTINGENCIA", "oracle");
define("DB_USER_CONTINGENCIA", "art");
define("DB_PASS_CONTINGENCIA", "t7yzs7pzh4SC=");
define("DB_SERV_CONTINGENCIA", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.1.1.54)(PORT = 1522))(CONNECT_DATA = (SID = PART)))");

// Base de Datos - Conexión - RHPRO..
define("DB_USER_RHPRO", "postulantes");
define("DB_PASS_RHPRO", "xL/Fw3pzgoWC");
define("DB_SERV_RHPRO", "(DESCRIPTION =(ADDRESS = (PROTOCOL = TCP)(HOST = 10.60.1.3)(PORT = 1526))(CONNECT_DATA = (SID = RHPROCP)))");

// Base de datos - Conexión - SQL Server..
define("DB_USER_MSSQL", "webadmin");
define("DB_PASS_MSSQL", "provartwww");
define("DB_DATABASENAME", "provinciaart");
define("DB_SERV_MSSQL", "ntweb");

// Base de Datos - Varios..
define("CHARSET", "ISO-8859-1");
define("DB_QUOTE", "'");

// Web..
define("MAX_FILE_UPLOAD", 3145728); //TAMAÑO MAXIMO PARA SUBIR ARCHIVO ATRAVEZ DE LA WEB 1048576 bytes=1 MB  (3145728 = 3 mb)

define("STORAGE_DATA_RAIZ", "\\\\ntwebart3\Storage_Data");
define("STORAGE_DATA_PATH", "D:/Storage_Data/");
define("STORAGE_EXTRANET", "D:/Storage_Extranet/");
define("STORAGE_INTRANET_PATH", "D:/Storage_Intranet/");

define("DATA_CARGA_MASIVA_TRABAJADORES", STORAGE_DATA_PATH."Web/Carga_Masiva_Trabajadores/");
define("DATA_CARGA_MASIVA_TRABAJADORES_EXTERNAL", "\\\\ntwebart3\Storage_Data\Web\Carga_Masiva_Trabajadores\\");
define("DATA_CARTA_COTIZACION", STORAGE_DATA_PATH."Web/Carta_Cotizacion/");
define("DATA_CARTA_COTIZACION_EXTERNAL", "\\\\ntwebart3\Storage_Data\Web\Carta_Cotizacion\\");
define("DATA_CERTIFICADOS_COBERTURA", STORAGE_DATA_PATH."Web/Certificados_Cobertura/");
define("DATA_CERTIFICADOS_COBERTURA_EXTERNAL", "\\\\ntwebart3\Storage_Data\Web\Certificados_Cobertura\\");
define("DATA_CHAT_ARCHIVOS_PATH", STORAGE_EXTRANET."chat/archivos/");
define("DATA_CV_PATH", STORAGE_DATA_PATH."Web/CVs/");
define("DATA_FORMULARIO_ESTABLECIMIENTOS", STORAGE_DATA_PATH."Web/Formulario_Establecimientos/");
define("DATA_FORMULARIO_ESTABLECIMIENTOS_EXTERNAL", "\\\\ntwebart3\Storage_Data\Web\Formulario_Establecimientos\\");
define("DATA_IMAGE_PATH", STORAGE_DATA_PATH."Legales/archivosasociados/");
define("DATA_INFORMES_INGENIERIA_SINIESTRALIDAD", STORAGE_DATA_PATH."Web/Informes_Ingenieria_Siniestralidad/");
define("DATA_ORGANISMOS_PUBLICOS_RESUMEN", STORAGE_DATA_PATH."Emision/OrganismosPublicos/");
define("DATA_PDF_SERVER", STORAGE_DATA_PATH."Prevencion/Resolucion463/");
define("DATA_PREVENCION", STORAGE_DATA_PATH."Prevencion/");
define("DATA_REPORTE_RESPONSABILIDAD_CIVIL", STORAGE_DATA_PATH."Suscripciones/Responsabilidad_Civil/");
define("DATA_SISTEMA_GESTION_RRHH", STORAGE_DATA_PATH."Web/Sistema_Gestion_RRHH/");
define("DATA_SISTEMA_GESTION_RRHH_EXTERNAL", "\\\\ntwebart3\Storage_Data\Web\Sistema_Gestion_RRHH\\");
define("GRAFICO_CARTA_COTIZACION", STORAGE_DATA_PATH."Suscripciones/Carta_Solicitud_Cotizacion/");
define("IMAGENES_ARTICULOS", STORAGE_EXTRANET."articulos/imagenes/");
define("IMAGENES_STATUS_BCRA", STORAGE_EXTRANET."status_bcra/");
define("IMAGES_USUARIOS_PATH", STORAGE_INTRANET_PATH."fotos_personales/");
define("LOCAL_PATH_DESCRIPCION_PUESTO", "/modules/evaluacion_puesto/");
define("LOCAL_PATH_FORMULARIO_ESTABLECIMIENTOS", "/modules/formulario_establecimientos/");
define("LOCAL_PATH_PAGO_TRANSFERENCIA", "/modules/pago_transferencia/");
define("LOCAL_PATH_PROGRAMA_INCENTIVOS", "/modules/programa_incentivos_2012_2013/");
define("LOCAL_PATH_USERS_WEB", "/modules/admin_users_web/");
?>