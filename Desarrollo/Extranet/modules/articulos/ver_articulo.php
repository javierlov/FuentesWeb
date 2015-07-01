<?
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/numbers_utils.php");

validarParametro((isset($_REQUEST["id"])) and (validarEntero($_REQUEST["id"])));

$params = array(":id" => $_REQUEST["id"]);
$sql =
	"SELECT ae_cuerpofull, ae_titulo, ae_volanta
		 FROM web.wae_articulosextranet
		WHERE ae_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<table cellspacing="0" cellpadding="0">
	<tr>
		<td class="VolantaNotaPortada"><?= $row["AE_VOLANTA"]?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="TituloNotaPortada"><?= $row["AE_TITULO"]?></td>
	</tr>
	<tr>
		<td height="5"></td>
	</tr>
	<tr>
		<td class="ContenidoSeccion"><?= $row["AE_CUERPOFULL"]->load()?></td>
	</tr>
	<tr>
		<td width="2%"><input class="btnVolver" type="button" value="" onClick="history.back(-1);" /></td>
	</tr>
</table>