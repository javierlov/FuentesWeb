function addEvents() {
	// Agrego los eventos a los items padres..
	var cols = document.querySelectorAll('#divMantenimientoItems .divMantenimientoPadreTexto');
	[].forEach.call(cols, function(col) {
		col.addEventListener('dragstart', handleDragStart, false);
		col.addEventListener('dragenter', handleDragEnter, false);
		col.addEventListener('dragover', 	handleDragOver,  false);
		col.addEventListener('dragleave', handleDragLeave, false);
		col.addEventListener('drop', 			handleDrop, 		 false);
		col.addEventListener('dragend', 	handleDragEnd, 	 false);
	});

	// Agrego los eventos a los items hijos..
	var cols = document.querySelectorAll('#divMantenimientoItems .divMantenimientoItem');
	[].forEach.call(cols, function(col) {
		col.addEventListener('dragstart', handleDragStartHijo, false);
		col.addEventListener('dragenter', handleDragEnterHijo, false);
		col.addEventListener('dragover', 	handleDragOverHijo,  false);
		col.addEventListener('dragleave', handleDragLeaveHijo, false);
		col.addEventListener('drop', 			handleDropHijo, 		 false);
		col.addEventListener('dragend', 	handleDragEndHijo, 	 false);
	});
}

function agregarItem() {
	window.location.href = '/mantenimiento-descargables-item/0';
}

function cambiarItemPadre(valor) {
	if (valor == -1)
		document.getElementById('divArchivo').style.display = 'none';
	else
		document.getElementById('divArchivo').style.display = 'block';
}

function cancelar() {
	window.location.href = '/mantenimiento-intranet';
}

function cancelarItem() {
	window.location.href = '/mantenimiento-descargables';
}

function darBaja(id) {
	if (confirm('¿ Realmente desea dar de baja este item ?'))
		iframeProcesando.location.href = '/modules/mantenimiento/descargables/dar_baja_item.php?id=' + id;
}

function editarItem(id) {
	window.location.href = '/mantenimiento-descargables-item/' + id;
}

function guardar() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formDescargablePadre').submit();
	}
}

function guardarItem() {
	with (document) {
		body.style.cursor = 'wait';
		getElementById('btnGuardar').style.display = 'none';
		getElementById('imgProcesando').style.display = 'inline';
		getElementById('formAbmItem').submit();
	}
}

function handleDragEnd(e) {
	// this/e.target is the source node.

	var cols = document.querySelectorAll('#divMantenimientoItems .divMantenimientoPadreTexto');
	[].forEach.call(cols, function (col) {
//		col.classList.remove('over');
		col.style.color = '';
	});
}

function handleDragEndHijo(e) {
	// this/e.target is the source node.

	var cols = document.querySelectorAll('#divMantenimientoItems .divMantenimientoItem');
	[].forEach.call(cols, function (col) {
//		col.classList.remove('over');
		col.style.color = '';
	});
}

function handleDragEnter(e) {
	// this / e.target is the current hover target.
//	this.classList.add('over');
}

function handleDragEnterHijo(e) {
	// this / e.target is the current hover target.
//	this.classList.add('over');
}

function handleDragLeave(e) {
//	this.classList.remove('over');  // this / e.target is previous target element.
	if (this != dragSrcEl)
		this.style.color = '';
}

function handleDragLeaveHijo(e) {
//	this.classList.remove('over');  // this / e.target is previous target element.
	if (this != dragSrcEl)
		this.style.color = '';
}

function handleDragOver(e) {
	if (e.preventDefault)
		e.preventDefault(); // Necessary. Allows us to drop.

	if (dragSrcEl.className == 'divMantenimientoPadreTexto') {
		e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
		this.style.color = '#f00';
	}

	return false;
}

function handleDragOverHijo(e) {
	if (e.preventDefault)
		e.preventDefault(); // Necessary. Allows us to drop.

	if (dragSrcEl.className == 'divMantenimientoItem') {
		e.dataTransfer.dropEffect = 'move';  // See the section on the DataTransfer object.
		this.style.color = '#00f';
	}

	return false;
}

function handleDragStart(e) {
	// Target (this) element is the source node.
	this.style.color = '#f00';

	dragSrcEl = this;

	e.dataTransfer.effectAllowed = 'move';
	e.dataTransfer.setData('text/html', this.parentNode.innerHTML);
}

function handleDragStartHijo(e) {
	// Target (this) element is the source node.
	this.style.color = '#00f';

	dragSrcEl = this;

	e.dataTransfer.effectAllowed = 'move';
	e.dataTransfer.setData('text/html', this.innerHTML);
}

function handleDrop(e) {
	// this/e.target is current target element.

	if (e.stopPropagation)
		e.stopPropagation(); // Stops some browsers from redirecting.

	// Don't do anything if dropping the same column we're dragging.
	if ((dragSrcEl != this) && (dragSrcEl.className == 'divMantenimientoPadreTexto')) {
		// Set the source column's HTML to the HTML of the columnwe dropped on.
		dragSrcEl.parentNode.innerHTML = this.parentNode.innerHTML;
		this.parentNode.innerHTML = e.dataTransfer.getData('text/html');

		// Cambio el campo posición..
		tmpOrden = document.getElementById('posicionPadre_' + dragSrcEl.id.substr(11)).value;
		document.getElementById('posicionPadre_' + dragSrcEl.id.substr(11)).value = document.getElementById('posicionPadre_' + this.id.substr(11)).value;
		document.getElementById('posicionPadre_' + this.id.substr(11)).value = tmpOrden;

		addEvents();
	}

	return false;
}

function handleDropHijo(e) {
	// this/e.target is current target element.

	if (e.stopPropagation)
		e.stopPropagation(); // Stops some browsers from redirecting.

	// Don't do anything if dropping the same column we're dragging.
	if ((dragSrcEl != this) && (dragSrcEl.className == 'divMantenimientoItem')) {
		// Set the source column's HTML to the HTML of the columnwe dropped on.
		dragSrcEl.innerHTML = this.innerHTML;
		this.innerHTML = e.dataTransfer.getData('text/html');

		// Cambio el campo posición..
		tmpOrden = document.getElementById('posicionItem_' + dragSrcEl.id.substr(5)).value;
		document.getElementById('posicionItem_' + dragSrcEl.id.substr(5)).value = document.getElementById('posicionItem_' + this.id.substr(5)).value;
		document.getElementById('posicionItem_' + this.id.substr(5)).value = tmpOrden;

		// Cambio el campo padre..
		tmpPadre = document.getElementById('padreItem_' + dragSrcEl.id.substr(5)).value;
		document.getElementById('padreItem_' + dragSrcEl.id.substr(5)).value = document.getElementById('padreItem_' + this.id.substr(5)).value;
		document.getElementById('padreItem_' + this.id.substr(5)).value = tmpPadre;

		addEvents();
	}

	return false;
}