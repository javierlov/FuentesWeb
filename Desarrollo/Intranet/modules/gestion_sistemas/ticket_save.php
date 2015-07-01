<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/file_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/modules/gestion_sistemas/ticket_funciones.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/CrearLog.php");

$usuario = getWindowsLoginName();
$TicketDetalle = $_REQUEST["DetallePedido"];

if( !TienePermisoTicket($usuario, $TicketDetalle) ) {	
	echo '<script type="text/javascript">  alert("NO TIENE PERMISOS PARA SOLICITAR ESTE TIPO DE TICKET..."); </script> ';
	echo '<script type="text/javascript">  history.back(); </script> ';
	
	// header("Location: ".$_SERVER['REQUEST_URI']);
	return true;
}

/* Implementación de múltiples sistemas dentro del sistema de tickets */
if (isset($_REQUEST["sistema"]))
  $sistema = $_REQUEST["sistema"];
else
  $sistema = 1;

$txtBody = "";

// Me fijo el nro. de PC...
$sSQL =
	"SELECT MAX(TO_CHAR(eq_id))
		FROM computos.ceq_equipo
	 WHERE eq_descripcion = UPPER(:descripcion)";
$params = array(":descripcion" => GetPCName());
$idEquipo = ValorSQL($sSQL, "", $params);

// Traigo el sector por default del motivo cargado...
$sSQL =
	"SELECT NVL(MAX(ms_idsectordefault), NULL)
		FROM computos.cms_motivosolicitud
	 WHERE ms_id = :id";
$params = array(":id" => $_REQUEST['DetallePedido']);
$idSectorAsignado = ValorSQL($sSQL, "", $params);

if (isset($_REQUEST["Ejecutable"])) {
  $campoEjecutable = ', ss_idejecutable';
  $valorEjecutable = ', '.$_REQUEST["Ejecutable"];
} else {
  $campoEjecutable = '';
  $valorEjecutable = '';
}

// Id del nuevo ticket...
$sSQL = "SELECT NVL(max(ss_id),0) + 1
           FROM computos.css_solicitudsistemas";
$id = ValorSQL($sSQL);

// Próximo número de ticket...
$sSQL = "SELECT NVL(max(st_ultimoticket),0) + 1
           FROM computos.cst_sistematicket
          WHERE st_id = ".$sistema;
$nroTicket = ValorSQL($sSQL);

// Estado según el motivo...
$sql =
	"SELECT 'A la espera de la autorización de ' || InitCap(SE_NOMBRE)
		FROM ART.USE_USUARIOS, COMPUTOS.CMS_MOTIVOSOLICITUD
	 WHERE MS_ID = :id
		  AND SE_ID = COMPUTOS.GENERAL.GET_USUARIORESPONSABLE(:userid, MS_NIVEL)";
$params = array(":id" => $_REQUEST['DetallePedido'], ":userid" => GetUserID());
$stmt = DBExecSql($conn, $sql, $params);
$user = ValorSql($sql, "", $params);

if ($user == "") {
  $id_estado = 1;
}
else {
  $id_estado = 2;
}

if (GetUserID() != $_REQUEST["UsuarioSolicitud"]) {
  if ($user == "") {
    $user = "Ticket cargado por ".GetUserName();

    // Estado según el motivo para el usuario original...
    $sql = "SELECT 'A la espera de la autorización de ' || InitCap(SE_NOMBRE)
              FROM ART.USE_USUARIOS, COMPUTOS.CMS_MOTIVOSOLICITUD
             WHERE MS_ID = :id
               AND SE_ID = COMPUTOS.GENERAL.GET_USUARIORESPONSABLE(:usuariosolicitud, MS_NIVEL)";
	$params = array(":id" => $_REQUEST['DetallePedido'], ":usuariosolicitud" => $_REQUEST["UsuarioSolicitud"]);
    $stmt = DBExecSql($conn, $sql, $params);
    $useraux = ValorSql($sql, "", $params);

    if (($useraux != "") and ($id_estado == 1)) {
      $id_estado = 10;
    }
  }
  else {
    $user = $user."\r\n"."Ticket cargado por ".GetUserName();
  }
}

// Se setea esta variable que se utiliza en el trigger trg_css_permisosolicitud de la tabla computos.css_solicitudsistemas..
$curs = null;
$sql = "BEGIN COMPUTOS.GENERAL.v_nombreusuario := UPPER(:usuario); END;";
$params = array(":usuario" => GetWindowsLoginName());
$stmt = DBExecSP($conn, $curs, $sql, $params, false);

try{
	// Doy de alta el ticket...	
	$sql =
		"INSERT INTO computos.css_solicitudsistemas (ss_id, ss_idusuario_carga, ss_fecha_solicitud, ss_idusuario_solicitud, ss_fecha_carga,
						 ss_idsector_asignado, ss_idequipo, ss_idestadoactual, ss_idmotivosolicitud, ss_notas,
						 ss_observaciones, ss_prioridad ".$campoEjecutable.", ss_presencial, ss_indicaciones, 
						 ss_idsistematicket, ss_nro_ticket)
				 VALUES (:id, :idusuario, ART.ACTUALDATE , :idusuariosolicitud, SYSDATE, :idsectorasignado, :idequipo,
						 :idestadoactual, :idmotivosolicitud, :notas, :observaciones, :prioridad".$valorEjecutable.",
						 :presencial, :indicaciones, :idsistematicket, :nro_ticket)";
	$params = array(":id" => $id,
					":idusuario" => GetUserID(),
					":idusuariosolicitud" => $_REQUEST["UsuarioSolicitud"],
			":idsectorasignado" => $idSectorAsignado,
			":idequipo" => $idEquipo,
			":idestadoactual" => $id_estado,
			":idmotivosolicitud" => $_REQUEST["DetallePedido"],
			":notas" => $_REQUEST["notas"],
			":observaciones" => $user,
			":prioridad" => $_REQUEST["Prioridad"],
			":presencial" => "N",
			":indicaciones" => NULL,
					":idsistematicket" => $sistema,
					":nro_ticket" => $nroTicket);
	DBExecSql($conn, $sql, $params);

	if (count($_FILES) > 0) {
	  MakeDirectory(ATTACHMENTS_PATH.$id);
	}

	// Subo los adjuntos...
	while(list($key,$value) = each($_FILES["attachments"]["name"]))
	{
	  if(!empty($value)){                                                    // this will check if any blank field is entered
		$filename = $value;                                                  // filename stores the value
		$tempfile = $_FILES["attachments"]["tmp_name"][$key];
		$sfilename = basename($_FILES["attachments"]["name"][$key]);
		$txtBody.= "Adjuntando el archivo ".$sfilename;
		$txtBody.= "<br>";                                                   // Display a line break
		$txtBody.= "Tipo de archivo: ".$_FILES["attachments"]["type"][$key];  // uncomment this line if you want to display the file type
		$txtBody.= "<br>";                                                   // Display a line break
		$sfilename = ATTACHMENTS_PATH.$id."/".$sfilename;

		$uploadOk = false;
		if (is_uploaded_file($tempfile))
			if (move_uploaded_file($tempfile, $sfilename)) {
				$uploadOk = true;
			}

		if (!$uploadOk) {

?>
<SCRIPT>
	alert('Ocurrió un error al adjuntar uno de los archivos. Inténtelo nuevamente.');
	history.go(-1); 
</SCRIPT>
<?
} else {
// Doy de alta el attach del ticket...
$sql = "INSERT INTO computos.cas_adjuntosolicitud
(as_idsolicitud, as_rutaarchivo, as_usualta, as_fechaalta)
VALUES
(:idsolicitud, :rutaarchivo, UPPER(:usualta), ART.ACTUALDATE)";
$params = array(":idsolicitud" => $id, ":rutaarchivo" => $sfilename, ":usualta" => GetWindowsLoginName());
DBExecSql($conn, $sql, $params);
}
}
}
}catch (Exception $e){
EscribirLogTxt1("Error insert cas_adjuntosolicitud", $e->getMessage());
}
?>

<script type="text/javascript"><?
if ($dbError["offset"]) {
?>
alert('<?= $dbError["message"] ?>');<?
}
else {
/*
if ($imgFotoPath != "") {
SendEmail("Se ha cargado la foto del usuario ".$_REQUEST["UserName"].".", "Contacto Web", "Nueva foto cargada desde la intranet", array("aangiolillo@provart.com.ar"), array(), array());
echo "window.parent.document.getElementById('NombreFoto').value = '<?= $imgFotoPath?>';";
}
echo "window.parent.document.getElementById('spanMensaje').style.display = 'block';";
*/
null;
}
?></script>

<?php
/*
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
*/
?>

<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Provincia ART | Sistemas</title>
		<meta content="Mon, 06 Jan 1990 00:00:01 GMT" http-equiv="Expires" />
		<link href="/styles/style_sistemas.css?sid=<?= date('YmdHis'); ?>" rel="stylesheet" type="text/css" />

		<meta http-equiv="Refresh" content="0; url=index.php?sistema=<?echo $sistema; ?>&ticket_detail=yes&id=<?= $id ?>&MNU=2" />
	</head>
	<body>
		Procesando su pedido...
		<br>
		<?= $txtBody ?>
	</body>
</html>