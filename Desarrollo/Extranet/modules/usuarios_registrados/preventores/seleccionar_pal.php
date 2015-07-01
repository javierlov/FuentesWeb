<?

session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));

if ($_REQUEST["origen"]=="c")
{
	$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["pal"]["cumplimiento"]);
	
	if (($ind) or ($ind === 0))
		unset($_SESSION["verificacionTarea"]["pal"]["cumplimiento"][$ind]);
	else
	{
		if (valorSql("SELECT 1 
					    FROM hys.hps_palseguimiento
					   WHERE ps_fechabaja IS NULL
						 AND ps_idpal = ".$_REQUEST["id"]."
						 AND ps_fechavisita = ".$_SESSION["fechaVisita"])) == "")
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
						  alert('Ya existe una visita con esta fecha cargada.');</script>";
		}
		else
		{
			$_SESSION["verificacionTarea"]["pal"]["cumplimiento"][] = $_REQUEST["id"];
			$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["pal"]["incumplimiento"]);
			if (($ind) or ($ind === 0))
			{
				unset($_SESSION["verificacionTarea"]["pal"]["incumplimiento"][$ind]);
				echo "<script>window.parent.document.getElementById('grid_col2_".$_REQUEST["id"]."').checked = false;</script>";
			}
		}
	}
}
else if($_REQUEST["origen"]=="i") 
{
	$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["pal"]["incumplimiento"]);
	if (($ind) or ($ind === 0))
		unset($_SESSION["verificacionTarea"]["pal"]["incumplimiento"][$ind]);
	else
	{
		if (valorSql("SELECT 1 
					    FROM hys.hps_palseguimiento
					   WHERE ps_fechabaja IS NULL
						 AND ps_idpal = ".$_REQUEST["id"]."
						 AND ps_fechavisita = ".$_SESSION["fechaVisita"])) == "")
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
						  alert('Ya existe una visita con esta fecha cargada.');</script>";
		}
		else
		{
			$_SESSION["verificacionTarea"]["pal"]["incumplimiento"][] = $_REQUEST["id"];
			$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["pal"]["cumplimiento"]);
			if (($ind) or ($ind === 0))
			{
				unset($_SESSION["verificacionTarea"]["pal"]["cumplimiento"][$ind]);
				echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;</script>";
			}
		}
	}
}
?>
