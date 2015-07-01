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
	global $tareaSeleccionada;
	echo "<script type='text/javascript'>";
	echo "with (window.parent.document) {";
	echo "var errores = '';";

	$tareaSeleccionada = false;
	if ($_POST["grupoDenuncia"] != -1 ) {
		$param = array(":id" => $_POST["grupoDenuncia"],":cuit" => $_SESSION["CARGA_TAREA"]["cuit"]); 
		$sql =
			"SELECT RD_DESCRIPCIONRUBRO,RD_ID
			   FROM hys.hrd_rubrodenuncia 
			  WHERE rd_fechabaja IS NULL 
				AND RD_IDGRUPO = :id
				AND (rd_vigenciadesde <= art.hys.get_operativovigente_empresa(:cuit,sysdate) OR rd_vigenciadesde IS NULL) 
				AND (rd_vigenciahasta > art.hys.get_operativovigente_empresa(:cuit,sysdate) OR rd_vigenciahasta IS NULL)
		   ORDER BY rd_codigorubro";
		$stmt = DBExecSql($conn, $sql, $param);	
		
		while ($row = DBGetQuery($stmt))
		{
			if (isset($_POST['item_'.$row["RD_ID"]]))
			{
				$tareaSeleccionada = true;
			}
		}
		if (!	$tareaSeleccionada ){
			echo "errores+= '- Debe Seleccionar un rubro si tiene seleccionado un grupo.<br />';";
			$errores = true;
		}
	}


	
	if ($errores) {
		echo "body.style.cursor = 'default';";
		echo "getElementById('btnGuardar').style.display = 'inline';";
		echo "getElementById('btnCancelar').style.display = 'inline';";	
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
	foreach($_SESSION["verificacionTarea"]["accidente"]["cumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":idmedida" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "C",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardaraccidente ( 	:idmedida, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["accidente"]["incumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":idmedida" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "I",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardaraccidente ( 	:idmedida, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["enfermedad"]["cumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":idmedida" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "C",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardarenfermedad ( 	:idmedida, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["enfermedad"]["incumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":idmedida" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "I",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardarenfermedad ( 	:idmedida, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["pal"]["cumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":idpal" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "C",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardarpal ( 	:idpal, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["pal"]["incumplimiento"] as $key) 
	{
		$curs = null;
		$params = array(":idpal" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "I",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardarpal ( 	:idpal, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["prs"]["cumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":idrecomendacion" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "C",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardarprs ( 	:idrecomendacion, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["prs"]["incumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":idrecomendacion" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "I",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardarprs ( 	:idrecomendacion, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["463"]["cumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":iditemriesgo" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "C",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardar463 ( 	:iditemriesgo, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	foreach($_SESSION["verificacionTarea"]["463"]["incumplimiento"] as $key)
	{
		$curs = null;
		$params = array(":iditemriesgo" => $key,":fechavisita" => $_SESSION["fechaVisita"],
						":tipo" => "I",":usuario" => $_SESSION["usuario"]);
		$sql = "BEGIN  art.hys_prevencionweb.do_guardar463 ( 	:iditemriesgo, TO_DATE(:fechavisita,'DD/MM/YYYY'),:tipo ,:usuario); END;";
		DBExecSP($conn, $curs, $sql, $params, false);
	}
	if ($tareaSeleccionada) 
	{
		if ($_POST["grupoDenuncia"] != -1 ) {
			$param = array(":id" => $_POST["grupoDenuncia"],":cuit" => $_SESSION["CARGA_TAREA"]["cuit"]); 
			$sql =
				"SELECT RD_DESCRIPCIONRUBRO,RD_ID
				   FROM hys.hrd_rubrodenuncia 
				  WHERE rd_fechabaja IS NULL 
					AND RD_IDGRUPO = :id
					AND (rd_vigenciadesde <= art.hys.get_operativovigente_empresa(:cuit,sysdate) OR rd_vigenciadesde IS NULL) 
					AND (rd_vigenciahasta > art.hys.get_operativovigente_empresa(:cuit,sysdate) OR rd_vigenciahasta IS NULL)
			   ORDER BY rd_codigorubro";
			$stmt = DBExecSql($conn, $sql, $param);	
			
			while ($row = DBGetQuery($stmt))
			if (isset($_POST['item_'.$row["RD_ID"]]))
			{
				$curs = null;
				$params = array(":cuit" => $_SESSION["CARGA_TAREA"]["cuit"],":estableci" => $_SESSION["CARGA_TAREA"]["establecimiento"], 
								":usuario" => $_SESSION["usuario"],":fecha" => $_SESSION["fechaVisita"],":idpreventor" => $_SESSION["idUsuario"],
								":idtarea" => $_SESSION['idTarea'],
								":grupo" => $_POST["grupoDenuncia"],":rubro" => $_POST['item_'.$row["RD_ID"]] );
				$sqlDetalle = "BEGIN art.hys_prevencionweb.do_guardardenunciabasica ( :cuit, :estableci, :usuario,
																					  :fecha, :idpreventor , :idtarea, :grupo,
																					  :rubro); END;";			
				DBExecSP($conn, $curs, $sqlDetalle, $params, false);	
			}
		}
	}
	
?>

<script type="text/javascript">
	with (window.parent.document) {
		getElementById('btnGuardar').style.display = 'inline';
		getElementById('divProcesando').style.display = 'none';
	}
		
	window.parent.parent.divWin.close();
</script>