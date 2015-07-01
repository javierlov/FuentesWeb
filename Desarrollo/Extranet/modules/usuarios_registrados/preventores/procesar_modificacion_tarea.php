<script src="/js/popup/dhtmlwindow.js" type="text/javascript"></script>
<?

session_start();
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/date_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/send_email.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/telefonos/funciones.php");



function validar() {
	global $conn;
	$errores = false;
	
	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	
	

	if ($_POST["fechaVisita"] == "") {
		echo "errores+= '- El campo fecha visita es obligatorio.<br />';";
		$errores = true;
	}
	
	if ($_POST["fechaViatico"] == "") {
		echo "errores+= '- El campo fecha viatico es obligatorio.<br />';";
		$errores = true;
	}
	
	if ($_POST["horaDesde"] == "") {
		echo "errores+= '- El campo hora desde es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["cantVisitas"] == "") {
		echo "errores+= '- El campo cantidad de visitas es obligatorio.<br />';";
		$errores = true;
	}

	if ($_POST["kms"] == "") {
		echo "errores+= '- El campo kms es obligatorio.<br />';";
		$errores = true;
	}
	
	$sql =
		"SELECT * 
		   FROM HYS.HTA_TAREA 
		  WHERE TA_VISIBLE = 'S' AND TA_FECHABAJA IS NULL 
	   ORDER BY 2";
	$stmt = DBExecSql($conn, $sql);		
	$tareaSeleccionada = false;
	while ($row = DBGetQuery($stmt))
	{
		if (isset($_POST['item_'.$row["TA_ID"]]))
		{
			$tareaSeleccionada = true;
			if ($_POST['detalleTarea_'.$row["TA_ID"]]== -1)
			{
				echo "errores+= '- esta seleccionado:".$row["TA_DESCRIPCION"]." debe seleccionar un detalle de tarea.<br />';";
				$errores = true;
			}
		}
	}
	
	if (!$tareaSeleccionada) {
		echo "errores+= '- Debe seleccionar una tarea.<br />';";
		$errores = true;
	}
	
	if ($errores) {
		echo "body.style.cursor = 'default';";
		echo "getElementById('btnGuardar').style.display = 'inline';";
		echo "getElementById('divProcesando').style.display = 'none';";
		echo "getElementById('errores').innerHTML = errores;";
		echo "getElementById('divErroresForm').style.display = 'block';";
		echo "getElementById('foco').style.display = 'block';";
		echo "getElementById('foco').focus();";
		echo "getElementById('foco').style.display = 'none';";
	}
	else {
		echo "getElementById('divErroresForm').style.display = 'none';";
	}

	echo "}";
	echo "</script>";

	return !$errores;
}

	//print_r ($_POST);
	if (!validar())
		exit;
	
	$idvisita = $_POST["idTarea"];	
	
	$curs = null;
	
	$params = array(":fechavisita" => $_POST["fechaVisita"],":fechaviatico" => $_POST["fechaViatico"],
					":horadesde" => $_POST["horaDesde"],":kms" => $_POST["kms"], ":idpreventor" => $_SESSION["idUsuario"],
					":observaciones" => $_POST["observaciones"], ":usuario" => $_SESSION["usuario"], ":idvisita" => $idvisita, ":cantvisitas" => $_POST["cantVisitas"],
					":idpreventorconsultora" => $_SESSION["idTercerizado"]);
	$sql = "BEGIN  art.hys_prevencionweb.do_guardartarea ( 	NULL, NULL, TO_DATE(:fechavisita,'DD/MM/YYYY'),
															TO_DATE(:fechaviatico,'DD/MM/YYYY'), :horadesde, :kms, 
															:idpreventor, :idpreventorconsultora, :observaciones,
															:usuario, :idvisita,  :cantvisitas, 'M'); END;";
	$_SESSION["fechaVisita"] = $_POST["fechaVisita"];
	DBExecSP($conn, $curs, $sql, $params, false);

	$curs = null;
	$params = array(":idvisita" => $idvisita);
	$sql = "BEGIN art.hys_prevencionweb.do_eliminardetalletarea (:idvisita); END;";
	DBExecSP($conn, $curs, $sql, $params, false);
	
	$sql =
		"SELECT * 
		   FROM HYS.HTA_TAREA 
		  WHERE TA_VISIBLE = 'S' AND TA_FECHABAJA IS NULL 
	   ORDER BY 2";
	$stmt = DBExecSql($conn, $sql);		
	$_SESSION['tabVisible'] ="";
	while ($row = DBGetQuery($stmt))
	{
		if (isset($_POST['item_'.$row["TA_ID"]]))
		{
			$curs = null;
			if($row["TA_VERIFICASEGUIMIENTO"] != "")
			{	
				$_SESSION['tabVisible'] .= $row["TA_VERIFICASEGUIMIENTO"];
			}
			
			$params = array(":idvisita" => $idvisita,":idtarea" => $row["TA_ID"], ":idmotivo" => $_POST['detalleTarea_'.$row["TA_ID"]], ":usuario" => $_SESSION["usuario"]);
			$sqlDetalle = "BEGIN art.hys_prevencionweb.do_guardardetalletarea ( :idvisita, :idtarea, :idmotivo, :usuario); END;";
			DBExecSP($conn, $curs, $sqlDetalle, $params, false);	
		};
	}
	if ($_SESSION['tabVisible']=="")
	{
		$paginaSiguiente = "/prevencion/Carga-Tareas";
	}
	else
	{	
		//$paginaSiguiente = "/prevencion/Verificaciones-Tareas";
		$paginaSiguiente = "";
	}
	$_SESSION['idTarea'] = $idvisita;
	echo $paginaSiguiente;
	DBCommit($conn);
?>

<script type="text/javascript">
	with (window.parent.document) {
		getElementById('btnGuardar').style.display = 'inline';
		getElementById('divProcesando').style.display = 'none';
	}
		<? if($paginaSiguiente == ""){?>
			var height = 600;
			var width = 1024;
			var left = ((screen.width - width) / 2) + 52;
			var top = ((screen.height - height) / 2) - window.screenTop;
			window.parent.document.getElementById('btnGuardar').style.display = 'none';
			window.parent.document.getElementById('btnCancelar').style.display = 'none';
			window.parent.document.getElementById('btnFinalizar').style.display = 'inline';
			window.parent.divWin = null;
			window.parent.divWin = window.parent.dhtmlwindow.open('divBoxEstablecimiento', 'iframe', '/test.php', 'Aviso', 'width=' + width + 'px,height=' + height + 'px,left=' + left + 'px,top=' + top + 'px,resize=1,scrolling=1');
			window.parent.divWin.load('iframe', '/modules/usuarios_registrados/preventores/verificaciones_tareas.php', 'Verificaciones Tareas');
			window.parent.divWin.show();	
		<? }else {?>
		window.parent.location.href = "<?echo $paginaSiguiente;?>";
		<?}?>
</script>