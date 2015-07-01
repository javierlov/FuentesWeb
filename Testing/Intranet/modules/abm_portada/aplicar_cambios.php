<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/general.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/miscellaneous/string_utils.php");


$params = array(":usubaja" => GetWindowsLoginName());
$sql =
	"UPDATE web.wai_articulosintranet
			SET ai_fechabaja = SYSDATE,
					ai_usubaja = UPPER(:usubaja)
	  WHERE ai_fechabaja IS NULL";
DBExecSql($conn, $sql, $params, OCI_DEFAULT);

$params = array(":usualta" => GetWindowsLoginName());
$sql =
	"INSERT INTO web.wai_articulosintranet (ai_id, ai_titulo, ai_volanta, ai_cuerpo, ai_rutaimagen, ai_link, ai_destino, ai_fechaalta, ai_usualta, ai_posicion)
																	 SELECT -1, ai_titulo, ai_volanta, ai_cuerpo, ai_rutaimagen, ai_link, ai_destino, SYSDATE, UPPER(:usualta), ai_posicion
																		 FROM tmp.tai_articulosintranet";
DBExecSql($conn, $sql, $params, OCI_DEFAULT);

DBCommit($conn);
?>
<html>
	<head>
		<title>Actualizar Portal</title>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<meta name="Subject" content="Intranet" />
		<link href="/styles/style.css" rel="stylesheet" type="text/css" />
	</head>
	<body bgcolor="#D9DADC" leftmargin="3" topmargin="3">
		<div align="center">
			<table border="0" width="100%" height="100%">
				<tr>
					<td align="center">
						<table border="0">
							<tr>
								<td align="center"><img border="0" src="/modules/abm_portada/images/LogoProvart.jpg"></td>
							</tr>
							<tr>
								<td height="4px"></td>							
							</tr>
							<tr>
								<td align="center"><font style="font-size: 10pt">La Portada de la Intranet de Provincia ART ha sido Actualizada.</font></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
		<script language="javascript">
			setTimeout("window.close();", 2500);
		</script>
	</body>
</html>