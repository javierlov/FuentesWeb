function cambiaProvincia(provincia) {
	iframeProcesando.location.href = '/modules/usuarios_registrados/clientes/cartilla_de_prestadores/cambia_provincia.php?provincia=' + provincia;
}

function exportarGrilla() {
	iframeProcesando.location.href = '/modules/usuarios_registrados/clientes/cartilla_de_prestadores/exportar_grilla_a_excel.php';
}