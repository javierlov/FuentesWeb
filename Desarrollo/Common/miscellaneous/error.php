<?
  function ShowError($title, $msg) {
?>
<html>
	<head>
		<title>IntraWEB | <?= $title ?></title>
		<link rel="stylesheet" href="/styles/style.css" type="text/css" />
	</head>
	<body>
		<table width="100%">
			<tr>
				<td>
					<h1>
						<img src="/images/logo_provart.jpg">
						<br>
						<?= $title ?>
					</h1>
				</td>
			</tr>
		</table>
		<table>
			<tr>
				<td>
					<br>
					<b>
					<?= $msg; ?>
					</b>
					<br>
				</td>
			</tr>
			<tr>
				<td><br><?= 'Usted ha sido identificado como ' . gethostbyaddr($_SERVER["REMOTE_ADDR"]); ?></td>
			</tr>
		</table>
	</body>
</html>
<?
}
?>