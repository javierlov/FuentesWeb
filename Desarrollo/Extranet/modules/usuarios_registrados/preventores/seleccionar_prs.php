<?

session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));

if ($_REQUEST["origen"]=="c")
{
	$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["prs"]["cumplimiento"]);
	
	if (($ind) or ($ind === 0))
		unset($_SESSION["verificacionTarea"]["prs"]["cumplimiento"][$ind]);
	else
	{
		if (valorSql("SELECT 1
                        FROM prv_recvisitas, art.pre_recomendaciones
                       WHERE re_id = ".$_REQUEST["id"]."
					     AND rv_cuit = re_cuit
						 AND rv_estableci = re_estableci
						 AND rv_tipo = re_tipo
						 AND rv_operativo = re_operativo
						 AND rv_recomendacion = re_recomendacion
						 AND rv_fechabaja IS NULL
						 AND (rv_tipo_visita <> 'C' AND rv_fecha > TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')") == "1")
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
						  alert('No se puede seleccionar un tipo cumplido cuando hay visitas posteriores o cuando ya se ha cumplido.');</script>";
		}
		if (valorSql("
			SELECT 1
              FROM prv_recvisitas, art.pre_recomendaciones
             WHERE re_id = ".$_REQUEST["id"]."
               AND rv_cuit = re_cuit
			   AND rv_estableci = re_estableci
			   AND rv_tipo = re_tipo
			   AND rv_operativo = re_operativo
			   AND rv_recomendacion = re_recomendacion
               AND rv_tipo_visita = 'I'
               AND rv_fechabaja IS NULL") == "1")
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
						  alert('No se puede seleccionar un tipo cumplimiento cuando no existe una denuncia de incumplimiento.');</script>";
		}		
		if (valorSql(" SELECT 1
					     FROM prv_recvisitas,art.pre_recomendaciones
					    WHERE  re_id = ".$_REQUEST["id"]."
    					  AND rv_cuit = re_cuit
						  AND rv_estableci = re_estableci
			              AND rv_tipo = re_tipo
						  AND rv_operativo = re_operativo
						  AND rv_recomendacion = re_recomendacion
						  AND rv_tipo_visita = 'C'
						  AND rv_fecha =  TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')
						  AND rv_fechabaja IS NULL ") == "1")
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
					alert('Ya existe una visita en esa fecha.');</script>";
		}		
		else
		{
			$_SESSION["verificacionTarea"]["prs"]["cumplimiento"][] = $_REQUEST["id"];
			$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["prs"]["incumplimiento"]);
			if (($ind) or ($ind === 0))
			{
				unset($_SESSION["verificacionTarea"]["prs"]["incumplimiento"][$ind]);
				echo "<script>window.parent.document.getElementById('grid_col2_".$_REQUEST["id"]."').checked = false;</script>";
			}
		}
	}
}
else if($_REQUEST["origen"]=="i") 
{
	$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["prs"]["incumplimiento"]);
	if (($ind) or ($ind === 0))
		unset($_SESSION["verificacionTarea"]["prs"]["incumplimiento"][$ind]);
	else
	{	
		if (valorSql(" SELECT 1
					     FROM prv_recvisitas,art.pre_recomendaciones
					    WHERE  re_id = ".$_REQUEST["id"]."
    					  AND rv_cuit = re_cuit
						  AND rv_estableci = re_estableci
			              AND rv_tipo = re_tipo
						  AND rv_operativo = re_operativo
						  AND rv_recomendacion = re_recomendacion
						  AND rv_tipo_visita = 'I'
						  AND rv_fecha =  TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')
						  AND rv_fechabaja IS NULL ") == "1")
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
					alert('Ya existe una visita en esa fecha.');</script>";
		}	
		else
		{
			$_SESSION["verificacionTarea"]["prs"]["incumplimiento"][] = $_REQUEST["id"];
			$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["prs"]["cumplimiento"]);
			if (($ind) or ($ind === 0))
			{
				unset($_SESSION["verificacionTarea"]["prs"]["cumplimiento"][$ind]);
				echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;</script>";
			}
		}
	}
}
?>
