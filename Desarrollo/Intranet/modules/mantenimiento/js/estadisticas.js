function aplicar() {
	with (document) {
		getElementById('btnAplicar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
	}
}

function busquedaRapida(tipo) {
	with (document) {
		switch (tipo) {
			case 1:
				getElementById('tipo').value = 'd';
				getElementById('fechaDesdeDiaria').value = getElementById('fechaDesdeDefault').value;
				getElementById('fechaHastaDiaria').value = getElementById('fechaHastaDefault').value;
				break;
			case 2:
				getElementById('tipo').value = 'm';
				getElementById('mesDesdeMensual').value = getElementById('mesDesde2Default').value;
				getElementById('anoDesdeMensual').value = getElementById('anoDesde2Default').value;
				getElementById('mesHastaMensual').value = getElementById('mesHastaDefault').value;
				getElementById('anoHastaMensual').value = getElementById('anoHastaDefault').value;
				break;
			case 3:
				getElementById('tipo').value = 'm';
				getElementById('mesDesdeMensual').value = getElementById('mesDesde3Default').value;
				getElementById('anoDesdeMensual').value = getElementById('anoDesde3Default').value;
				getElementById('mesHastaMensual').value = getElementById('mesHastaDefault').value;
				getElementById('anoHastaMensual').value = getElementById('anoHastaDefault').value;
				break;
			case 4:
				getElementById('tipo').value = 'm';
				getElementById('mesDesdeMensual').value = getElementById('mesDesde4Default').value;
				getElementById('anoDesdeMensual').value = getElementById('anoDesde4Default').value;
				getElementById('mesHastaMensual').value = getElementById('mesHastaDefault').value;
				getElementById('anoHastaMensual').value = getElementById('anoHastaDefault').value;
				break;
		}

		aplicar();
		cambiarTipo(getElementById('tipo').value);
		getElementById('formVerEstadisticas').submit();
	}
}

function cambiarTipo(valor) {
	with (document) {
		getElementById('divAnual').style.display = 'none';
		getElementById('divDiaria').style.display = 'none';
		getElementById('divHoraria').style.display = 'none';
		getElementById('divMensual').style.display = 'none';
		getElementById('divDias').style.display = 'none';

		switch (valor) {
			case 'a':
//				getElementById('anoDesdeAnual').value = '';
//				getElementById('anoHastaAnual').value = '';
				getElementById('divAnual').style.display = 'block';
				break;
			case 'd':
//				getElementById('fechaDesdeDiaria').value = '';
//				getElementById('fechaHastaDiaria').value = '';
				getElementById('divDiaria').style.display = 'block';
				getElementById('divDias').style.display = 'block';
				break;
			case 'h':
//				getElementById('fechaDesdeDiaria').value = '';
//				getElementById('fechaHastaDiaria').value = '';
				getElementById('divHoraria').style.display = 'block';
				break;
			case 'm':
//				getElementById('mesDesdeMensual').value = '';
//				getElementById('anoDesdeMensual').value = '';
//				getElementById('mesHastaMensual').value = '';
//				getElementById('anoHastaMensual').value = '';
				getElementById('divMensual').style.display = 'block';
				break;
		}
	}
}

function mostrarArteria() {
	with (document) {
		getElementById('divResultados').style.display = 'none';
		getElementById('divResultadosArteria').style.display = 'block';
	}
}

function mostrarArticulos() {
	with (document) {
		getElementById('divResultados').style.display = 'none';
		getElementById('divResultadosArticulos').style.display = 'block';
	}
}

function mostrarBeneficios() {
	with (document) {
		getElementById('divResultados').style.display = 'none';
		getElementById('divResultadosBeneficios').style.display = 'block';
	}
}

function mostrarComentarios() {
	with (document) {
		getElementById('divResultados').style.display = 'none';
		getElementById('divResultadosComentarios').style.display = 'block';
	}
}

function ocultarArteria() {
	with (document) {
		getElementById('divResultadosArteria').style.display = 'none';
		getElementById('divResultados').style.display = 'block';
	}
}

function ocultarArticulos() {
	with (document) {
		getElementById('divResultadosArticulos').style.display = 'none';
		getElementById('divResultados').style.display = 'block';
	}
}

function ocultarBeneficios() {
	with (document) {
		getElementById('divResultadosBeneficios').style.display = 'none';
		getElementById('divResultados').style.display = 'block';
	}
}

function ocultarComentarios() {
	with (document) {
		getElementById('divResultadosComentarios').style.display = 'none';
		getElementById('divResultados').style.display = 'block';
	}
}