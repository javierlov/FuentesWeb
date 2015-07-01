<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


$params = array(":id" => $_REQUEST["NoticiaId"]);
$sql = 
	"SELECT ap_fuente, ap_titulo, ap_contenido, TO_CHAR(ap_fecha, 'dd/mm/yyyy') fecha
		 FROM rrhh.rap_articulosprensa
		WHERE ap_id = :id";
$stmt = DBExecSql($conn, $sql, $params);
$row = DBGetQuery($stmt);
?>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<meta name="Author" content="Gerencia de Sistemas" />
		<meta name="Subject" content="Intranet" />
		<meta name="Description" content="Intranet de Provincia ART" />
		<meta name="Language" content="Spanish" />
		<title>Noticias relacionadas con el Sector</title>
		<style type="text/css"> 
			body {
				scrollbar-face-color: #aaa;
				scrollbar-highlight-color: #aaa;
				scrollbar-shadow-color: #aaa;
				scrollbar-3dlight-color: #eee;
				scrollbar-arrow-color: #eee;
				scrollbar-track-color: #e3e3e3;
				scrollbar-darkshadow-color: #fff;
			}
		</style>
	</head>
	<body bgcolor="#00539B">
		<div style="border:1 solid #c0c0c0;">
			<div style="background-color:#ccc; border-bottom:1 solid #808080; border-left:1 solid #808080; border-right:1 solid #808080; border-top:1 solid #808080;">
				<span style="float:left; font-color:#000; font-family:Neo Sans; font-size:16px; font-weight:700; margin-left:4px;"><?= htmlentities($row["AP_TITULO"]) ?></span>
				<span style="background-color:#ccc; color:#808080; float:right; font-family:Neo Sans; font-size:11px; margin-right:4px; margin-top:4px;"><i><?= $row["FECHA"] ?></i></span>
				<hr style="clear:left; margin:-top:8px;" />
				<span style="font-family:Neo Sans; font-size:11px; margin-left:4px; margin-top:8px;"><i><?= htmlentities($row["AP_FUENTE"]) ?></i></span>
			</div>
			<div style="background-color:#fff;">
				<p align="justify" style="font-family:Neo Sans; font-size:13px; margin:0; padding:4px; padding-top:8px; word-spacing:0;"><?= htmlentities($row["AP_CONTENIDO"]->load()) ?></p>
			</div>
		</div>
	</body>
</html>