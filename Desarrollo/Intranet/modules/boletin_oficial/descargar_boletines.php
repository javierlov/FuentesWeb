<html>
	<body>
		<iframe height="200" id="iframeProceso" name="iframeProceso" src="" width="500"></iframe>
		<form action="procesar_descarga_boletines.php" enctype="multipart/form-data" id="formProceso" method="post" name="formProceso" target="iframeProceso">
			<table cellpadding="0" cellspacing="0" width="100%">
				<tr>
					<td>Período Desde:</td>
					<td><input id="PeriodoDesde" name="PeriodoDesde" type="text" /></td>
				</tr>
				<tr>
					<td>Período Hasta:</td>
					<td><input id="PeriodoHasta" name="PeriodoHasta" type="text" /></td>
				</tr>
				<tr>
					<td colspan="2"><hr></td>
				</tr>
				<tr>
					<td colspan="2"><input type="submit" /></td>
				</tr>
			</table>
		<div id="resultado" name="resultado">nada..</div>
		</form>
	</body>
</html>