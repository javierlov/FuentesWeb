<?
session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");


function shutdown2() {
	echo "<script type='text/javascript'>alert('El servicio \"ART - Generador de PDFs del Sistema Web de Gestión de RRHH\" probablemente este caído, el reporte no pudo ser generado. Por favor informe a la Mesa de Ayuda de este error.');</script>";
}

function solicitarArchivo($id, $file) {
	global $conn;

	$params = array(":idtabla" => $id,
									":rutasalida" => $file,
									":usuariopedido" => substr($_SESSION["email"], 0, 20));
	$sql =
		"INSERT INTO web.wag_archivosgenerados (ag_fechahorainicio, ag_idmodulo, ag_idtabla, ag_rutasalida, ag_usuariopedido)
																		VALUES (SYSDATE, 7, :idtabla, :rutasalida, :usuariopedido)";
	DBExecSql($conn, $sql, $params);

	$sql = "SELECT MAX(ag_id) FROM web.wag_archivosgenerados";
	return ValorSql($sql);
}


// Valido que se haya logueado o que sea administrador..
if ((!isset($_SESSION["idUsuario"])) or (!$_SESSION["esAdministrador"])) {
	header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."login.php");
	exit;
}


$_SESSION["pageLoadOk"] = false;
register_shutdown_function("shutdown2");


$fileE = DATA_SISTEMA_GESTION_RRHH_EXTERNAL."%s.pdf";
$idArchivo = solicitarArchivo($_REQUEST["id"], $fileE);

$params = array(":id" => $idArchivo);
$sql =
	"SELECT ag_cantidadhojas, ag_rutasalida
		 FROM web.wag_archivosgenerados
		WHERE ag_id = :id
			AND ag_generar = 'F'";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);

set_time_limit(120);
while ($row["AG_RUTASALIDA"] == "") {		// Queda loopeando hasta que se genere el archivo o salga por timeout..
	sleep(2);

	$params = array(":id" => $idArchivo);
	$sql =
		"SELECT ag_cantidadhojas, ag_rutasalida
			 FROM web.wag_archivosgenerados
			WHERE ag_id = :id
				AND ag_generar = 'F'";
	$stmt = DBExecSql($conn, $sql, $params);
	$row = DBGetQuery($stmt);
}

$_SESSION["pageLoadOk"] = true;

header("Location: ".LOCAL_PATH_DESCRIPCION_PUESTO."resultados/mostrar_pdf.php?a=".$row["AG_RUTASALIDA"]."&rnd=".date("Ymdhisu"));
?>