<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
session_start();
?>
<div id="DetalleDenuncia">
	<table  >
		<?	
			$param = array(":id" => $_GET["id"],":cuit" => $_SESSION["CARGA_TAREA"]["cuit"]); 
			$sql =
				"SELECT RD_DESCRIPCIONRUBRO,RD_CODIGORUBRO, RD_ID 
				   FROM hys.hrd_rubrodenuncia 
				  WHERE rd_fechabaja IS NULL 
					AND RD_IDGRUPO = :id
					AND (rd_vigenciadesde <= art.hys.get_operativovigente_empresa(:cuit,sysdate) OR rd_vigenciadesde IS NULL) 
					AND (rd_vigenciahasta > art.hys.get_operativovigente_empresa(:cuit,sysdate) OR rd_vigenciahasta IS NULL)
			   ORDER BY rd_codigorubro";
			$stmt = DBExecSql($conn, $sql, $param);	
			while ($row = DBGetQuery($stmt))
			{
		?>
			<tr>
				<td>
					<input  id="item_<? echo $row["RD_ID"]; ?>" name="item_<? echo $row["RD_ID"]; ?>" style="vertical-align:-2px; border:0" type="checkbox" value="<? echo $row["RD_ID"]; ?>"/>
					<label style="margin-left:8px;font-family: Neo Sans; cursor:hand; padding-bottom:2px; padding-top:4px;"><? echo $row["RD_CODIGORUBRO"]." - ".$row["RD_DESCRIPCIONRUBRO"]; ?></label>
				</td>
				<td>
			</tr>
		<?
			}
		?>		
		</table>
</div>
<script type="text/javascript">
	with (window.parent.document) {
		getElementById('divProcesandoBasica').style.display = 'none';
		getElementById('divBasicaDetalleDenuncia').innerHTML = document.getElementById('DetalleDenuncia').innerHTML;
		//getElementById('divBasicaDetalleDenuncia').style.display = 'block';
	}
</script>