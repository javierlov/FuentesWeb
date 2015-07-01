function ValidarCampoTexto(field) {
  if (field.value == '') {
    alert('Por favor complete el campo ' + field.title + '.');
    field.focus();
    return false;
  }

  return true;
}

function ValidarCaracteresComunes(value) {
  for (i=0; i < value.length; i++)
    if ((value.substr(i, 1).toUpperCase() < "A") || (value.substr(i, 1).toUpperCase() > "Z"))
      if ((value.substr(i, 1).toUpperCase() < "0") || (value.substr(i, 1).toUpperCase() > "9"))
        return false;
        
  return true;        
}

function ValidarCombo(field) {
  if (field.value == -1) {
    alert('El campo ' + field.title + ' es obligatorio!');
    field.focus();
    return false;
  }
  return true;
}

function ValidarCuit(cuit) {
	var vec = new Array(10);
	esCuit = false;
	cuit_rearmado = '';
	errors = '';
    
	for (i=0; i < cuit.length; i++) {
		caracter = cuit.charAt(i);
		if (caracter.charCodeAt(0) >= 48 && caracter.charCodeAt(0) <= 57)
			cuit_rearmado += caracter;
	}
	cuit = cuit_rearmado;
	
	if (cuit.length != 11) {  // si no estan todos los digitos
		esCuit = false;
		errors = 'Cuit <11 ';
		return false;
	}
	else {
		x = i = dv = 0;
        
		// Multiplico los dígitos.
		vec[0] = cuit.charAt(0) * 5;
		vec[1] = cuit.charAt(1) * 4;
		vec[2] = cuit.charAt(2) * 3;
		vec[3] = cuit.charAt(3) * 2;
		vec[4] = cuit.charAt(4) * 7;
		vec[5] = cuit.charAt(5) * 6;
		vec[6] = cuit.charAt(6) * 5;
		vec[7] = cuit.charAt(7) * 4;
		vec[8] = cuit.charAt(8) * 3;
		vec[9] = cuit.charAt(9) * 2;
                    
		// Suma cada uno de los resultado.
		for(i = 0; i<=9; i++)
			x += vec[i];

		dv = (11 - (x % 11)) % 11;
		if (dv == cuit.charAt(10))
			esCuit = true;
	}
  
  return esCuit;
}

function ValidarEmail(value) {
  if (value == "") {
   return false;
  }
  if (value.indexOf("@") == -1) {
    return false;
  }
  if (value.indexOf("@") == (value.length-1)) {
    return false;
  }
  if (value.indexOf(".") == -1) {
    return false;
  }
  if (value.indexOf(".") == (value.length-1)) {
    return false;
  }
  return true;
}

function ValidarEntero(value) {
  var i;

  if (value.length == 0)
    return false;
    
  for (i=0; i<value.length; i++) {   
    var c = value.charAt(i);
    if (i != 0) {
      if (!isDigit(c)) 
        return false;
    } 
    else { 
      if (!isDigit(c) && (c != "-") || (c == "+")) 
        return false;
    }
  }
  
  return true;
}


function ValidarFecha(fecha) {
  if (fecha == "  /  /")
    return true;
    
  if (fecha) {
    borrar = fecha;
    if ((fecha.substr(2, 1) == "/") && (fecha.substr(5, 1) == "/")) {
      for (i = 0; i < 10; i++) {
        if (((fecha.substr(i, 1) < "0") || (fecha.substr(i, 1) > "9")) && (i != 2) && (i != 5)) {
          borrar = '';
          break;
        }
      }
      if (borrar) {
        a = fecha.substr(6, 4);
        m = fecha.substr(3, 2);
        d = fecha.substr(0, 2);
        if((a < 1900) || (a > 2050) || (m < 1) || (m > 12) || (d < 1) || (d > 31))
          borrar = '';
        else {
          if((a%4 != 0) && (m == 2) && (d > 28))
            borrar = ''; // Año no biciesto y es febrero y el dia es mayor a 28
          else {
            if ((((m == 4) || (m == 6) || (m == 9) || (m==11)) && (d>30)) || ((m==2) && (d>29)))
              borrar = '';
          }
        }
      }
    }
    else
      borrar = '';

    return (borrar != '');
  }
  return false;
}

function ValidarFloat(value) {
  aFloat = parseFloat(value);
  
  return (!isNaN(aFloat));
}

function ValidarForm(form) {
  for (j=0; j<form.elements.length; j++) {
    if (form.elements[j].getAttribute('validar') == 'true') {
      if ((form.elements[j].type == 'file') || (form.elements[j].type == 'text') || 
      		(form.elements[j].type == 'textarea') || (form.elements[j].type == 'password')) {
        if (!ValidarCampoTexto(form.elements[j]))
          return false;
      }
      else
        if (!ValidarCombo(form.elements[j]))
          return false;
    }

    if (form.elements[j].value != '') {
      if (form.elements[j].getAttribute('validarCaracteresComunes') == 'true')
        if (!ValidarCaracteresComunes(form.elements[j].value)) {
          alert('Solo puede ingresar caracteres entre A-Z y 0-9!');
          form.elements[j].focus();
          return false;
        }

      if (form.elements[j].getAttribute('validarEmail') == 'true')
        if (!ValidarEmail(form.elements[j].value)) {
          alert('Por favor, ingrese una dirección de e-mail válida!');
          form.elements[j].focus();
          return false;
        }

      if (form.elements[j].getAttribute('validarEntero') == 'true')
        if (!ValidarEntero(form.elements[j].value)) {
          alert('Por favor, ingrese un valor válido!');
          form.elements[j].focus();
          return false;
        }

      if (form.elements[j].getAttribute('validarFecha') == 'true')
        if (!ValidarFecha(form.elements[j].value)) {
          alert('Por favor, ingrese una fecha válida!');
          form.elements[j].focus();
          return false;
        }

      if (form.elements[j].getAttribute('validarHora') == 'true')
        if (!ValidarHora(form.elements[j].value)) {
          alert('Por favor, ingrese una hora válida!');
          form.elements[j].focus();
          return false;
        }

      if (form.elements[j].getAttribute('validarFloat') == 'true')
        if (!ValidarFloat(form.elements[j].value)) {
          alert('Por favor, ingrese un valor válido!');
          form.elements[j].focus();
          return false;
        }

      if (form.elements[j].getAttribute('validarImagen') == 'true')
        if (!ValidarImagen(form.elements[j].value)) {
          alert('El archivo seleccionado no es un archivo de imagen válido!');
          form.elements[j].focus();
          return false;
        }

      if (form.elements[j].getAttribute('validarCuit') == 'true')
        if (!ValidarCuit(form.elements[j].value)) {
          alert('Por favor, ingrese un cuil/cuit válido!');
          form.elements[j].focus();
          return false;
        }

    }
  }

  return true;
}

function ValidarHora(value) {
  if (ValidarEntero(value)) {
    if ((parseInt(value) >= 0) && (parseInt(value) <= 23))
      return true;
  }
  else {
    if (value.indexOf(":") == -1)
      return false;
    else {
      $arrHora = value.split(':');
      if ((ValidarEntero($arrHora[0])) && (ValidarEntero($arrHora[1]))) {
        if ((parseInt($arrHora[0]) >= 0) && (parseInt($arrHora[0]) <= 23) && (parseInt($arrHora[1]) >= 0) && (parseInt($arrHora[1]) <= 59))
          return true;
      }
      else
        return false;
    }
  }
}

function ValidarImagen(value) {
	value = value.toLowerCase();

	return ((value.substr(value.lastIndexOf('.'), 4) == '.bmp') ||
					(value.substr(value.lastIndexOf('.'), 4) == '.gif') ||
					(value.substr(value.lastIndexOf('.'), 4) == '.jpg') ||
					(value.substr(value.lastIndexOf('.'), 4) == '.png'));
}