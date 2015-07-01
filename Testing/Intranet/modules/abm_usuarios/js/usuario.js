function CambiaDelegacion(objDocument) {
	with (objDocument) {
		if (getElementById('Delegacion').value == DELEGACION_CAPITAL) {
			getElementById('divEdificio').style.display = 'block';
			getElementById('divPiso').style.display = 'block';
			getElementById('divHorarioAtencion').style.display = 'none';
			getElementById('HorarioAtencion').value = '';
		}
		else {
			getElementById('divEdificio').style.display = 'none';
			getElementById('divPiso').style.display = 'none';
			getElementById('Edificio').value = -1;
			getElementById('Piso').value = '';
			getElementById('divHorarioAtencion').style.display = 'block';
		}
		getElementById('divMapa').style.display = getElementById('divPiso').style.display;
		getElementById('divSeparadorPiso').style.display = getElementById('divPiso').style.display;
		getElementById('divSeparadorHorarioAtencion').style.display = getElementById('divHorarioAtencion').style.display;
	}
}

function cambiaEdificio(objDocument) {
	with (objDocument) {
		if (getElementById('Edificio').value == -1)
			getElementById('divMapa').style.display = 'none';
		else {
			getElementById('divMapa').style.display = 'block';
			getElementById('iframeUsuario').src = '/modules/abm_usuarios/cargar_imagen_edificio.php?e=' + getElementById('Edificio').value;
		}
	}
}

function CambiaPiso(objDocument) {
	with (objDocument) {
		if (getElementById('Piso').value == '')
			getElementById('divMapa').style.display = 'none';
		else {
			getElementById('divMapa').style.display = 'block';
			getElementById('Mapa').src = '/Images/Mapas/piso' + getElementById('Piso').value + '.gif';
		}
	}
}

function GetCoordenadasMapa() {
	with (document) {
		getElementById('EjeX').value = event.offsetX;
		getElementById('EjeY').value = event.offsetY;
//		SetCoordenadaPuesto(document, getElementById('EjeX').value, getElementById('EjeY').value);
	}
}

function MostrarFoto() {
	if (document.getElementById('NombreFoto').value == '') {
		if (document.getElementById('Nombre').innerText == '')
			alert('Debe seleccionar un usuario primero.')
		else
			alert(document.getElementById('Nombre').innerText + ' no tiene ninguna foto cargada.');
	}
	else
		OpenWindow('Modules/ABM_Usuarios/mostrar_foto.php?nombrefoto=' + document.getElementById('NombreFoto').value, 'ProvartPopup', 640, 480, 'no', 'no');
}

function NoExisteMapa() {
	document.getElementById('divMapa').style.display = 'none';
}

function OcultarMensajeOk() {
	document.getElementById('spanMensaje').style.display = 'none';
}

function SeleccionarUsuario(id) {
	with (document) {
		getElementById('datos').style.display = 'none';
		getElementById('divMapa').style.display = 'none';
		getElementById('divProcesando').style.display = 'block';
	}

	frames['iframeUsuario'].location.href = 'Modules/ABM_Usuarios/cargar_usuario.php?id=' + id;
	OcultarMensajeOk();
}

function SetCoordenadaPuesto(objDocument, ejeX, ejeY) {
	with (objDocument) {
		var obj = getElementById('Mapa');

		getElementById('Coordenada').style.left = (ejeX - obj.width - 6) + 'px';
		getElementById('Coordenada').style.top = (ejeY - obj.height + 4) + 'px';
	}
}

function ValidarFormUsuario() {
	if (document.getElementById('Id').value == -1) {
		alert('Por favor seleccione un usuario.');
    document.getElementById('Usuario').focus();
    return false;
  }

	if (document.getElementById('Usuario').value == document.getElementById('RespondeA').value) {
		alert('Un usuario no puede responder a si mismo.');
    document.getElementById('RespondeA').focus();
    return false;
  }
	
	return ValidarForm(formUsuario);
}