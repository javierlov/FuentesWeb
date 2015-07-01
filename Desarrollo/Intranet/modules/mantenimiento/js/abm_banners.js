function agregar() {
	window.location.href = '/banners-abm/0';
}

function agregarGrupo() {
	with (document) {
		for (var i=1; i<10; i++)
			if ((getElementById('divGrupo' + i).style.display == 'none') && (getElementById('idGrupo_' + i).value == -1)) {		// Si esta invisible y nunca se uso..
				agregarTodos(document.getElementById('usuariosGrupo' + i + '[]'), document.getElementById('usuariosSinGrupo' + i))
				getElementById('bajaGrupo' + i).value = 'f';
				getElementById('divGrupo' + i).style.display = 'block';
				break;
			}

		if (i == 9)
			getElementById('imgAgregarGrupo').style.display = 'none';
	}
}

function agregarTodos(objOrigen, objDestino) {
	while (objOrigen.options.length > 0) {
		AddItemToDropDown(objDestino.id, objOrigen.options[0].value, objOrigen.options[0].text);
		objOrigen.remove(0);
	}
	OrdenarCombo(objDestino);
}

function agregarUsuarios(objOrigen, objDestino) {
	var i = 0;
	while (i < objOrigen.options.length)
		if (objOrigen.options[i].selected) {
			AddItemToDropDown(objDestino.id, objOrigen.options[i].value, objOrigen.options[i].text);
			objOrigen.remove(objOrigen.selectedIndex);
		}
		else
			i++;

	OrdenarCombo(objDestino);
}

function cancelar() {
	window.location.href = '/banners-abm-busqueda/0';
}

function clicMultiLink(obj) {
	with (document) {
		getElementById('link').readOnly = obj.checked;
		if (obj.checked) {
			getElementById('divGrupos').style.display = 'block';
			getElementById('link').style.backgroundColor = '#ccc';
		}
		else {
			getElementById('divGrupos').style.display = 'none';
			getElementById('link').style.backgroundColor = '';
		}
	}
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja este banner ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/abm_banners/dar_baja_banner.php?id=' + id;
}

function eliminarGrupo(numGrupo) {
	with(document) {
		getElementById('bajaGrupo' + numGrupo).value = 't';
		getElementById('divGrupo' + numGrupo).style.display = 'none';
		getElementById('imgAgregarGrupo').style.display = 'block';
	}
}

function guardar() {
	with (document) {
		var continuar = true;

		if (getElementById('baja').value == 't')
			if (!confirm('Este banner está dado de baja. ¿ Realmente desea reactivarlo ?'))
				continuar = false;

		if (continuar) {
			body.style.cursor = 'wait';

			// Selecciono los usuarios seleccionados de cada grupo..
			for (var i=1; i<10; i++) {
				obj = document.getElementById('usuariosGrupo' + i + '[]');
				if (obj != null)
					for (var j=0; j<obj.options.length; j++)
						obj.options[j].selected = true;
			}


			getElementById('btnGuardar').style.display = 'none';
			getElementById('imgProcesando').style.display = 'inline';
			getElementById('formAbmBanner').submit();
		}
	}
}

function setImagen(imgName) {
	with (document) {
		getElementById('fileImg').value = imgName;
		iframeProcesando.location.href = '/modules/mantenimiento/abm_banners/mostrar_imagen.php?img=' + imgName;
	}
}