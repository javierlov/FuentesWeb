<?

session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));

if ($_REQUEST["origen"]=="c")
{
	$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["enfermedad"]["cumplimiento"]);
	
	if (($ind) or ($ind === 0))
		unset($_SESSION["verificacionTarea"]["enfermedad"]["cumplimiento"][$ind]);
	else
	{	
		if (valorSql("  SELECT 1
                      FROM hys.psv_seguimientovisitaenf
					 WHERE sv_fechabaja IS NULL AND sv_idmedidacorrectiva = ".$_REQUEST["id"]) == "")
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
						  alert('La primer visita debe ser Visita o Incumplimiento.');</script>";
		}
		else 
		if(valorSql("SELECT 1
                 		    FROM hys.psv_seguimientovisitaenf
						   WHERE sv_fechabaja IS NULL
						 	 AND sv_idmedidacorrectiva = ".$_REQUEST["id"].
							"AND sv_fecha = TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')") == 1)
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
						  alert('Ya existe una visita con esta fecha.');</script>";
		}
		else
		{
			$_SESSION["verificacionTarea"]["enfermedad"]["cumplimiento"][] = $_REQUEST["id"];
			$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["enfermedad"]["incumplimiento"]);
			if (($ind) or ($ind === 0))
			{
				unset($_SESSION["verificacionTarea"]["enfermedad"]["incumplimiento"][$ind]);
				echo "<script>window.parent.document.getElementById('grid_col2_".$_REQUEST["id"]."').checked = false;</script>";
			}
		}
	}
}
else if($_REQUEST["origen"]=="i") 
{
	$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["enfermedad"]["incumplimiento"]);
	if (($ind) or ($ind === 0))
		unset($_SESSION["verificacionTarea"]["enfermedad"]["incumplimiento"][$ind]);
	else
	{
		if(valorSql("SELECT 1
                 		    FROM hys.psv_seguimientovisitaenf
						   WHERE sv_fechabaja IS NULL
						 	 AND sv_idmedidacorrectiva = ".$_REQUEST["id"].
							"AND sv_fecha = TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')") == 1)
		{
			echo "<script>window.parent.document.getElementById('grid_col2_".$_REQUEST["id"]."').checked = false;
						  alert('Ya existe una visita con esta fecha.');</script>";
		}
		else
		{
			$_SESSION["verificacionTarea"]["enfermedad"]["incumplimiento"][] = $_REQUEST["id"];
			$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["enfermedad"]["cumplimiento"]);
			if (($ind) or ($ind === 0))
			{
				unset($_SESSION["verificacionTarea"]["enfermedad"]["cumplimiento"][$ind]);
				echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;</script>";
			}
		}
	}
}
?>