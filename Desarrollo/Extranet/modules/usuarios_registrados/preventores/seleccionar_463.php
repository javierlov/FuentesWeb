<?

session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isPreventor"]));

if ($_REQUEST["origen"]=="c")
{
	$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["463"]["cumplimiento"]);
	print_r($_SESSION);
	if (($ind) or ($ind === 0))
		unset($_SESSION["verificacionTarea"]["463"]["cumplimiento"][$ind]);
	else
	{	
		
		if (valorSql("SELECT rv_id
           				FROM art.prv_resvisitas
					   WHERE rv_cuit = ".$_SESSION["CARGA_TAREA"]["cuit"].
					 "   AND rv_estableci = (SELECT es_nroestableci FROM afi.aes_establecimiento WHERE es_id = ".$_SESSION["CARGA_TAREA"]["establecimiento"].")
					 	 AND rv_fecha = TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')
					 	 AND rv_fechabaja IS NULL ") == "")
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
						  alert('No existe una visita para la fecha indicada.');</script>";
		}
		else
		if(valorSql(" SELECT 1
					    FROM hys.hrt_relevseguimiento, hys.hil_itemsriesgolaboral
					   WHERE rt_fechabaja IS NULL
						 AND rt_idrelevriesgolaboral = il_idrelevriesgolaboral
						 AND rt_iditem = il_iditemanexo
						 AND il_id = ".$_REQUEST["id"]."
						 AND rt_fechaseguimiento = TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')")== 1)
		{
			echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;
						  alert('Ya existe seguimiento para esa fecha.');</script>";
		}
		else 
		{
			$_SESSION["verificacionTarea"]["463"]["cumplimiento"][] = $_REQUEST["id"];
			$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["463"]["incumplimiento"]);
			if (($ind) or ($ind === 0))
			{
				unset($_SESSION["verificacionTarea"]["463"]["incumplimiento"][$ind]);
				echo "<script>window.parent.document.getElementById('grid_col2_".$_REQUEST["id"]."').checked = false;</script>";
			}
		}
	}
}
else if($_REQUEST["origen"]=="i") 
{
	$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["463"]["incumplimiento"]);
	if (($ind) or ($ind === 0))
		unset($_SESSION["verificacionTarea"]["463"]["incumplimiento"][$ind]);
	else
	{	
		if (valorSql("SELECT rv_id
           				FROM art.prv_resvisitas
					   WHERE rv_cuit = ".$_SESSION["CARGA_TAREA"]["cuit"].
					 "   AND rv_estableci = (SELECT es_nroestableci FROM afi.aes_establecimiento WHERE es_id = ".$_SESSION["CARGA_TAREA"]["establecimiento"].")
					 	 AND rv_fecha = TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')
					 	 AND rv_fechabaja IS NULL ") == "")
		{
			echo "<script>window.parent.document.getElementById('grid_col2_".$_REQUEST["id"]."').checked = false;
						  alert('No existe una visita para la fecha indicada.');</script>";
		}
		else
		if(valorSql(" SELECT 1
					    FROM hys.hrt_relevseguimiento, hys.hil_itemsriesgolaboral
					   WHERE rt_fechabaja IS NULL
						 AND rt_idrelevriesgolaboral = il_idrelevriesgolaboral
						 AND rt_iditem = il_iditemanexo
						 AND il_id = ".$_REQUEST["id"]."
						 AND rt_fechaseguimiento = TO_DATE('".$_SESSION["fechaVisita"]."','DD/MM/YYYY')")== 1)
		{
			echo "<script>window.parent.document.getElementById('grid_col2_".$_REQUEST["id"]."').checked = false;
						  alert('Ya existe seguimiento para esa fecha.');</script>";
		}
		else
		{
			$_SESSION["verificacionTarea"]["463"]["incumplimiento"][] = $_REQUEST["id"];
			$ind = array_search($_REQUEST["id"], $_SESSION["verificacionTarea"]["463"]["cumplimiento"]);
			if (($ind) or ($ind === 0))
			{
				unset($_SESSION["verificacionTarea"]["463"]["cumplimiento"][$ind]);
				echo "<script>window.parent.document.getElementById('grid_col1_".$_REQUEST["id"]."').checked = false;</script>";
			}
		}
	}
}
?>
