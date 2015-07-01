function agregar() {
	window.location.href = '/articulos-abm/0';
}

function cambiarTipo(valor) {
	with (document) {
		getElementById('divEmbebido').style.display = (valor == "M")?'block':'none';
		getElementById('divExterno').style.display = (valor == "X")?'block':'none';
	}
}

function cancelar() {
	window.location.href = '/articulos-abm-busqueda/0';
}

function cerrarVentanaCuerpo() {
	with (document) {
		getElementById('divCuerpo').style.display = 'none';
		getElementById('divFondo').style.display = 'none';
	}
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja este artículo ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_articulos/dar_baja_articulo.php?id=' + id;
}

function editarCuerpo(url) {
	with (document) {
		getElementById('imagen').value = '';
		CKEDITOR.instances.html.setData(getElementById('cuerpo').value);
		CKEDITOR.on('dialogDefinition', function(ev) {
			var dialogName = ev.data.name;
			var dialogDefinition = ev.data.definition;

			if (dialogName == 'image') {
				var infoTab = dialogDefinition.getContents('info');
				var urlField = infoTab.get('txtUrl');
				urlField['default'] = url;
			}
		});

		getElementById('divFondo').style.display = 'block';
		getElementById('btnGuardar').style.display = 'inline';
		getElementById('divCuerpo').style.display = 'block';

		// Ajusto el alto del campo editable..
		getElementById('cke_1_contents').style.height = (window.innerHeight - getElementById('divCuerpoImagenes').offsetHeight - getElementById('cke_1_top').offsetHeight - getElementById('cke_1_bottom').offsetHeight - getElementById('divCuerpoPlantilla').offsetHeight - 72) + 'px';
	}
}

function enviar() {
	with (document) {
		getElementById('btnEnviar').style.display = 'none';
		getElementById('imgSubiendoImagen').style.display = 'inline';
	}
}

function guardar() {
	with (document) {
		var continuar = true;

		if (getElementById('baja').value == 't')
			if (!confirm('Este artículo está dado de baja. ¿ Realmente desea reactivarlo ?'))
				continuar = false;

		if (continuar) {
			body.style.cursor = 'wait';
			getElementById('btnGuardar').style.display = 'none';
			getElementById('imgProcesando').style.display = 'inline';
			getElementById('formAbmArticulo').submit();
		}
	}
}

function mostrarEnPortadaClic(checked) {
	document.getElementById('divMostrarEnPortada').style.display = (checked)?'block':'none';
}

function setImagenChica(imgName) {
	with (document) {
		getElementById('fileImgChica').value = imgName;
		iframeProcesando.location.href = '/modules/mantenimiento/abm_articulos/mostrar_imagen.php?t=imgChica&img=' + imgName;
	}
}

function setImagenGrande(imgName) {
	with (document) {
		getElementById('fileImgGrande').value = imgName;
		iframeProcesando.location.href = '/modules/mantenimiento/abm_articulos/mostrar_imagen.php?t=imgGrande&img=' + imgName;
	}
}