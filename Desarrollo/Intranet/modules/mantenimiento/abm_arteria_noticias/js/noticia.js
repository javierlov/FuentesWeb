function addImagen(imgName) {
	iframeNoticia.location = '/modules/mantenimiento/abm_arteria_noticias/guardar_imagen_noticia.php?tipoop=a&idboletin=' + document.getElementById('idboletin').value + '&num=' + document.getElementById('num').value + '&imgName=' + imgName;
}

function editImagen(imgName) {
	parent.iframeNoticia.location = '/modules/mantenimiento/abm_arteria_noticias/guardar_imagen_noticia.php?tipoop=m&id=' + document.getElementById('tmpId').value + '&idnoticia=' + document.getElementById('idnoticia').value + '&imgName=' + imgName;
}

function guardarNoticia() {
	if (ValidarForm(formNoticia)) {
		result = '';

		if (document.getElementById('iframeImagenes') != null) {
			for (i = 0; i < iframeImagenes.document.getElementById("formImagenes").elements.length; i++)
				if (iframeImagenes.document.getElementById("formImagenes").elements[i].type == 'text') {
					obj = iframeImagenes.document.getElementById("formImagenes").elements[i];
					if (obj.name.substr(0, 19) == "descripcion_imagen_")
						result+= obj.name.substr(19) + '=_=' + obj.value + '@_@';
				}
		}

		document.getElementById('descripcion_imagenes').value = result;

		formNoticia.submit();
	}
}