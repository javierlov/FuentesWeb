function agregarCurso() {
	with (document) {
		if (getElementById('curso2').style.display == 'none') {
			getElementById('curso2').style.display = 'inline';
			getElementById('quitarCurso').style.display = 'inline';
			getElementById('curso2visible').value = 't';
		}
		else if (getElementById('curso3').style.display == 'none') {
			getElementById('curso3').style.display = 'inline';
			getElementById('curso3visible').value = 't';
		}
		else if (getElementById('curso4').style.display == 'none') {
			getElementById('curso4').style.display = 'inline';
			getElementById('agregarCurso').style.visibility = 'hidden';
			getElementById('curso4visible').value = 't';
		}
	}
}

function agregarEspecializacion() {
	with (document) {
		if (getElementById('especializacion2').style.display == 'none') {
			getElementById('especializacion2').style.display = 'inline';
			getElementById('quitarEspecializacion').style.display = 'inline';
			getElementById('especializacion2visible').value = 't';
		}
		else if (getElementById('especializacion3').style.display == 'none') {
			getElementById('especializacion3').style.display = 'inline';
			getElementById('especializacion3visible').value = 't';
		}
		else if (getElementById('especializacion4').style.display == 'none') {
			getElementById('especializacion4').style.display = 'inline';
			getElementById('agregarEspecializacion').style.visibility = 'hidden';
			getElementById('especializacion4visible').value = 't';
		}
	}
}

function agregarEstudio() {
	with (document) {
		if (getElementById('formacion2').style.display == 'none') {
			getElementById('formacion2').style.display = 'inline';
			getElementById('quitarEstudio').style.display = 'inline';
			getElementById('formacion2visible').value = 't';
		}
		else if (getElementById('formacion3').style.display == 'none') {
			getElementById('formacion3').style.display = 'inline';
			getElementById('formacion3visible').value = 't';
		}
		else if (getElementById('formacion4').style.display == 'none') {
			getElementById('formacion4').style.display = 'inline';
			getElementById('agregarEstudio').style.visibility = 'hidden';
			getElementById('formacion4visible').value = 't';
		}
	}
}

function agregarExperienciaLaboral() {
	with (document) {
		if (getElementById('experienciaLaboral2').style.display == 'none') {
			getElementById('experienciaLaboral2').style.display = 'inline';
			getElementById('quitarExperienciaLaboral').style.display = 'inline';
			getElementById('experienciaLaboral2visible').value = 't';
		}
		else if (getElementById('experienciaLaboral3').style.display == 'none') {
			getElementById('experienciaLaboral3').style.display = 'inline';
			getElementById('experienciaLaboral3visible').value = 't';
		}
		else if (getElementById('experienciaLaboral4').style.display == 'none') {
			getElementById('experienciaLaboral4').style.display = 'inline';
			getElementById('agregarExperienciaLaboral').style.visibility = 'hidden';
			getElementById('experienciaLaboral4visible').value = 't';
		}
	}
}

function agregarIdioma() {
	with (document) {
		if (getElementById('divIdioma2').style.display == 'none') {
			getElementById('divIdioma2').style.display = 'inline';
			getElementById('quitarIdioma').style.display = 'inline';
			getElementById('idioma2visible').value = 't';
		}
		else if (getElementById('divIdioma3').style.display == 'none') {
			getElementById('divIdioma3').style.display = 'inline';
			getElementById('idioma3visible').value = 't';
		}
		else if (getElementById('divIdioma4').style.display == 'none') {
			getElementById('divIdioma4').style.display = 'inline';
			getElementById('agregarIdioma').style.visibility = 'hidden';
			getElementById('idioma4visible').value = 't';
		}
	}
}

function enviar() {
	with (document) {
		getElementById('imgEnviar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formEnviarCV').submit();
	}
}

function quitarCurso() {
	with (document) {
		if (getElementById('curso4').style.display == 'inline') {
			getElementById('tipoCurso4').value = -1;
			getElementById('nombreCurso4').value = '';
			getElementById('fechaCurso4').value = '';
			getElementById('instituto4').value = -1;
			getElementById('curso4visible').value = 'f';

			getElementById('curso4').style.display = 'none';
			getElementById('agregarCurso').style.visibility = 'visible';
		}
		else if (getElementById('curso3').style.display == 'inline') {
			getElementById('tipoCurso3').value = -1;
			getElementById('nombreCurso3').value = '';
			getElementById('fechaCurso3').value = '';
			getElementById('instituto3').value = -1;
			getElementById('curso3visible').value = 'f';

			getElementById('curso3').style.display = 'none';
		}
		else if (getElementById('curso2').style.display == 'inline') {
			getElementById('tipoCurso2').value = -1;
			getElementById('nombreCurso2').value = '';
			getElementById('fechaCurso2').value = '';
			getElementById('instituto2').value = -1;
			getElementById('curso2visible').value = 'f';

			getElementById('curso2').style.display = 'none';
			getElementById('quitarCurso').style.display = 'none';
		}
	}
}

function quitarEspecializacion() {
	with (document) {
		if (getElementById('especializacion4').style.display == 'inline') {
			getElementById('tipo4').value = -1;
			getElementById('elemento4').value = -1;
			getElementById('nivelEspecializacion4').value = -1;
			getElementById('especializacion4visible').value = 'f';

			getElementById('especializacion4').style.display = 'none';
			getElementById('agregarEspecializacion').style.visibility = 'visible';
		}
		else if (getElementById('especializacion3').style.display == 'inline') {
			getElementById('tipo3').value = -1;
			getElementById('elemento3').value = -1;
			getElementById('nivelEspecializacion3').value = -1;
			getElementById('especializacion3visible').value = 'f';

			getElementById('especializacion3').style.display = 'none';
		}
		else if (getElementById('especializacion2').style.display == 'inline') {
			getElementById('tipo2').value = -1;
			getElementById('elemento2').value = -1;
			getElementById('nivelEspecializacion2').value = -1;
			getElementById('especializacion2visible').value = 'f';

			getElementById('especializacion2').style.display = 'none';
			getElementById('quitarEspecializacion').style.display = 'none';
		}
	}
}

function quitarEstudio() {
	with (document) {
		if (getElementById('formacion4').style.display == 'inline') {
			for (i=0; ele = document.getElementsByName('completo4')[i]; i++)
				ele.checked = false;

			getElementById('nivelFormacion4').value = -1;
			getElementById('titulo4').value = -1;
			getElementById('institucion4').value = -1;
			getElementById('carrera4').value = -1;
			getElementById('formacion4visible').value = 'f';

			getElementById('formacion4').style.display = 'none';
			getElementById('agregarEstudio').style.visibility = 'visible';
		}
		else if (getElementById('formacion3').style.display == 'inline') {
			for (i=0; ele = document.getElementsByName('completo3')[i]; i++)
				ele.checked = false;

			getElementById('nivelFormacion3').value = -1;
			getElementById('titulo3').value = -1;
			getElementById('institucion3').value = -1;
			getElementById('carrera3').value = -1;
			getElementById('formacion3visible').value = 'f';

			getElementById('formacion3').style.display = 'none';
		}
		else if (getElementById('formacion2').style.display == 'inline') {
			for (i=0; ele = document.getElementsByName('completo2')[i]; i++)
				ele.checked = false;

			getElementById('nivelFormacion2').value = -1;
			getElementById('titulo2').value = -1;
			getElementById('institucion2').value = -1;
			getElementById('carrera2').value = -1;
			getElementById('formacion2visible').value = 'f';

			getElementById('formacion2').style.display = 'none';
			getElementById('quitarEstudio').style.display = 'none';
		}
	}
}

function quitarExperienciaLaboral() {
	with (document) {
		if (getElementById('experienciaLaboral4').style.display == 'inline') {
			getElementById('fechaDesde4').value = '';
			getElementById('fechaHasta4').value = '';
			getElementById('empresa4').value = '';
			getElementById('cargoAnterior4').value = '';
			getElementById('tareas4').value = '';
			getElementById('experienciaLaboral4visible').value = 'f';

			getElementById('experienciaLaboral4').style.display = 'none';
			getElementById('agregarExperienciaLaboral').style.visibility = 'visible';
		}
		else if (getElementById('experienciaLaboral3').style.display == 'inline') {
			getElementById('fechaDesde3').value = '';
			getElementById('fechaHasta3').value = '';
			getElementById('empresa3').value = '';
			getElementById('cargoAnterior3').value = '';
			getElementById('tareas3').value = '';
			getElementById('experienciaLaboral3visible').value = 'f';

			getElementById('experienciaLaboral3').style.display = 'none';
		}
		else if (getElementById('experienciaLaboral2').style.display == 'inline') {
			getElementById('fechaDesde2').value = '';
			getElementById('fechaHasta2').value = '';
			getElementById('empresa2').value = '';
			getElementById('cargoAnterior2').value = '';
			getElementById('tareas2').value = '';
			getElementById('experienciaLaboral2visible').value = 'f';

			getElementById('experienciaLaboral2').style.display = 'none';
			getElementById('quitarExperienciaLaboral').style.display = 'none';
		}
	}
}

function quitarIdioma() {
	with (document) {
		if (getElementById('divIdioma4').style.display == 'inline') {
			getElementById('idioma4').value = -1;
			getElementById('hablaNivel4').value = -1;
			getElementById('leeNivel4').value = -1;
			getElementById('escribeNivel4').value = -1;
			getElementById('idioma4visible').value = 'f';

			getElementById('divIdioma4').style.display = 'none';
			getElementById('agregarIdioma').style.visibility = 'visible';
		}
		else if (getElementById('divIdioma3').style.display == 'inline') {
			getElementById('idioma3').value = -1;
			getElementById('hablaNivel3').value = -1;
			getElementById('leeNivel3').value = -1;
			getElementById('escribeNivel3').value = -1;
			getElementById('idioma3visible').value = 'f';

			getElementById('divIdioma3').style.display = 'none';
		}
		else if (getElementById('divIdioma2').style.display == 'inline') {
			getElementById('idioma2').value = -1;
			getElementById('hablaNivel2').value = -1;
			getElementById('leeNivel2').value = -1;
			getElementById('escribeNivel2').value = -1;
			getElementById('idioma2visible').value = 'f';

			getElementById('divIdioma2').style.display = 'none';
			getElementById('quitarIdioma').style.display = 'none';
		}
	}
}

function recargarCaptcha(par) {
	par.getElementById('imgCaptcha').src = '/functions/captcha.php?rnd=' + Math.random();
}