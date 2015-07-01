function mostrarDetallaTarea(id) {
	if (document.getElementById('divDetalleTarea' + id).style.display == 'block')
	{
		document.getElementById('divDetalleTarea' + id).style.display = 'none';
		document.getElementById('detalleTarea_' + id).options[0].selected = true;
	}
	else
	{
		document.getElementById('divDetalleTarea' + id).style.display = 'block';
		
	}
}