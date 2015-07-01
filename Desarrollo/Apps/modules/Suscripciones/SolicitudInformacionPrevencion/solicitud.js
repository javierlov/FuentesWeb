function aceptar() {
	if (validar())
		document.getElementById('builtForm').submit();
}

function calcular() {
	with (document) {
		reemplazarPuntoXComa(getElementById('PORCENTAJEEXPUESTOS1'));
		reemplazarPuntoXComa(getElementById('PORCENTAJEEXPUESTOS2'));
		reemplazarPuntoXComa(getElementById('PORCENTAJEEXPUESTOS3'));
		reemplazarPuntoXComa(getElementById('PORCENTAJEEXPUESTOS4'));
		reemplazarPuntoXComa(getElementById('COSTOPROMEDIOVISITA'));
		reemplazarPuntoXComa(getElementById('OTRASEROGACIONES'));

		getElementById('TRABAJADORESEXPUESTOS1').value = Math.round(getElementById('TRABAJADORES').value * getElementById('PORCENTAJEEXPUESTOS1').value / 100);
		getElementById('COSTOPERIODICOS1').value = Number(getElementById('COSTOEXAMEN1').value * getElementById('TRABAJADORESEXPUESTOS1').value).toFixed(2);

		getElementById('TRABAJADORESEXPUESTOS2').value = Math.round(getElementById('TRABAJADORES').value * getElementById('PORCENTAJEEXPUESTOS2').value / 100);
		getElementById('COSTOPERIODICOS2').value = Number(getElementById('COSTOEXAMEN2').value * getElementById('TRABAJADORESEXPUESTOS2').value).toFixed(2);

		getElementById('TRABAJADORESEXPUESTOS3').value = Math.round(getElementById('TRABAJADORES').value * getElementById('PORCENTAJEEXPUESTOS3').value / 100);
		getElementById('COSTOPERIODICOS3').value = Number(getElementById('COSTOEXAMEN3').value * getElementById('TRABAJADORESEXPUESTOS3').value).toFixed(2);

		getElementById('TRABAJADORESEXPUESTOS4').value = Math.round(getElementById('TRABAJADORES').value * getElementById('PORCENTAJEEXPUESTOS4').value / 100);
		getElementById('COSTOPERIODICOS4').value = Number(getElementById('COSTOEXAMEN4').value * getElementById('TRABAJADORESEXPUESTOS4').value).toFixed(2);

		getElementById('COSTOTOTALPERIODICOS').value = (Number(getElementById('COSTOPERIODICOS1').value) + Number(getElementById('COSTOPERIODICOS2').value) + Number(getElementById('COSTOPERIODICOS3').value) + Number(getElementById('COSTOPERIODICOS4').value)).toFixed(2);
		getElementById('TOTALVISITAS').value = Number(getElementById('COSTOPROMEDIOVISITA').value * getElementById('CANTIDADVISITASTOTALES').value).toFixed(2);
		getElementById('COSTOTOTALPREVENCION').value = (Number(getElementById('COSTOTOTALPERIODICOS').value) + Number(getElementById('TOTALVISITAS').value) + Number(getElementById('OTRASEROGACIONES').value)).toFixed(2);
	}
}