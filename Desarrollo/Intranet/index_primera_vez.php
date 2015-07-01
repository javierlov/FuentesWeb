<?
require_once($_SERVER["DOCUMENT_ROOT"]."/constants.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/../Common/database/db.php");


// Valido de nuevo acá, porque no anda bien la redirección en IE..
$params = array(":id" => getUserId());
$sql =
	"SELECT 1
		 FROM tmp.tip_intranetprimeravez
		WHERE ip_id = :id";
if (existeSql($sql, $params)) {
	header("Location: http://".$_SERVER["HTTP_HOST"]."/index.php?rnd=".date("YmdHis"));
	exit;
}


try{
	$params = array(":id" => getUserId());
	$sql =
		"INSERT INTO tmp.tip_intranetprimeravez(ip_id)
					VALUES (:id)";
	DBExecSql($conn, $sql, $params);
}
catch (Exception $e) {
	echo "<h2 style=\"color:red; margin-top:80px; text-align:center;\">Usuario inválido, ante cualquier duda comuníquese al interno 2929.</h2>";
	exit;
}
?>
<html>
	<head>
		<style>
			* {margin:0; padding:0;}
			html, body {height:100%; text-align:center; width:100%;}
			img {padding-top:40px;}
		</style>
	</head>
	<body>
		<img src="/images/lanzamiento_intranet_2015_2.jpg" />
	</body>
</html>