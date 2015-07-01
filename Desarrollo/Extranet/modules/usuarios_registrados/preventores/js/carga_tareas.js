function cambiarContrato(valor) {
	with (document)
	{
		getElementById('iframe2').src = '/modules/usuarios_registrados/preventores/cambia_contrato.php?contrato=' + valor;
	}
}

function cambiarCuit(valor) {
	with (document)
	{
		getElementById('iframe2').src = '/modules/usuarios_registrados/preventores/cambia_cuit.php?cuit=' + valor;
	}
}