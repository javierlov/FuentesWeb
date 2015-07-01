var index = -1;
var recArray = new Array();

function fillArray(formulario, numero) {
	document.getElementById('iframeHistorico').src = 'cargar_objetivo_historico.php?action=FA&formulario=' + formulario + '&numero=' + numero;
}

function mostrar(valor) {
	if (valor == 'A') {		// Anterior..
		index--;
	}
	if (valor == 'P') {		// Posterior..
		index++;
	}
	if (valor == 'U') {		// Último..
		index = recArray.length - 1;
	}
	document.getElementById('iframeHistorico').src = 'cargar_objetivo_historico.php?action=M&id=' + recArray[index];
}