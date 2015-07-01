<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); // always modified
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0
session_start();

require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db_funcs.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/functions/general.php");


validarSesion(isset($_SESSION["isAgenteComercial"]));

$params = array(":usuario" => $_SESSION["usuario"]);
$sql =
	"SELECT df_contrato, tb_descripcion AS forma_juridica, df_contacto, df_firmante AS caracter_firmante, df_documentofalta AS documento
		 FROM tmp.tdf_documentacion_faltante
LEFT JOIN art.ctb_tablas ON tb_clave = 'FJURI' AND tb_codigo = df_formajuridica
		WHERE df_usuario = :usuario
 ORDER BY df_documentofalta, df_firmante";
$stmt = DBExecSql($conn, $sql, $params);
?>
<html>
	<head>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
		<link rel="stylesheet" href="/styles/style2.css" type="text/css" />
		<link rel="stylesheet" href="/js/popup/dhtmlwindow.css" type="text/css" />
		<style type="text/css"> 
			* {
				margin: 0;
				padding: 0;
			}

			html, body {
				background-color: #FFF;
				overflow: hidden;
			}
		</style>
		<script src="/js/functions.js" type="text/javascript"></script>
		<script src="/js/validations.js" type="text/javascript"></script>
	</head>
	<body style="margin:0; padding:0;">
		<div class="ContenidoSeccion" style="margin-left:8px; margin-top:8px;">
<?
$documentoAnterior = "";
$texto = "";
while ($row = DBGetQuery($stmt)) {
	if (($documentoAnterior != $row["DOCUMENTO"]) and ($documentoAnterior != "")) {
		if (strpos($texto, "("))
			$texto.= ")";
		$texto.= "</div>";

		echo $texto;

		$texto = "";
	}

	if ($texto == "") {
		$texto = "<div>- ".$row["DOCUMENTO"];
		if ($row["CARACTER_FIRMANTE"] != "")
			$texto.= "(".$row["CARACTER_FIRMANTE"];
	}
	else
		if ($row["CARACTER_FIRMANTE"] != "") {
			if (strpos($texto, "("))
				$texto.= ", ".$row["CARACTER_FIRMANTE"];
			else
				$texto.= "(".$row["CARACTER_FIRMANTE"];
		}

	$documentoAnterior = $row["DOCUMENTO"];
}

if (strpos($texto, "("))
	$texto.= ")";
$texto.= "</div>";
echo $texto;
?>
		</div>
	</body>
</html>