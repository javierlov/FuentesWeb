function cambiarSolapa(solapa) {
	with (document) {
		getElementById('solapa').value = solapa;
		if  (getElementById('labelAccidente') != null)
		{
			getElementById('labelAccidente').style.backgroundColor = '';
			getElementById('labelAccidente').style.color = '#FFF';
		}
		
		if  (getElementById('labelEnfermedades') != null)
		{
			getElementById('labelEnfermedades').style.backgroundColor = '';
			getElementById('labelEnfermedades').style.color = '#FFF';
		}
		if  (getElementById('labelPRS') != null)
		{
			getElementById('labelPRS').style.backgroundColor = '';
			getElementById('labelPRS').style.color = '#FFF';
		}
		
		if  (getElementById('labelPAL') != null)
		{
			getElementById('labelPAL').style.backgroundColor = '';
			getElementById('labelPAL').style.color = '#FFF';
		}
		
		if  (getElementById('labelVerosimilitud') != null)
		{
			getElementById('labelVerosimilitud').style.backgroundColor = '';
			getElementById('labelVerosimilitud').style.color = '#FFF';
		}
		
		if  (getElementById('labelBasica') != null)
		{
			getElementById('labelBasica').style.backgroundColor = '';
			getElementById('labelBasica').style.color = '#FFF';
		}
			
		
		getElementById('divAccidente').style.display = 'none';
		getElementById('divEnfermedades').style.display = 'none';
		getElementById('divPRS').style.display = 'none';
		getElementById('divPAL').style.display = 'none';
		getElementById('div463').style.display = 'none';
		getElementById('divBasica').style.display = 'none';

		switch (solapa) {
			case 'tsAccidente':
				getElementById('labelAccidente').style.backgroundColor = '#0f539c';
				getElementById('labelAccidente').style.color = '#fff';
				getElementById('divAccidente').style.display = 'block';
				break;
			case 'tsEnfermedades':
				getElementById('labelEnfermedades').style.backgroundColor = '#0f539c';
				getElementById('labelEnfermedades').style.color = '#fff';
				getElementById('divEnfermedades').style.display = 'block';
				break;
			case 'tsPRS':
				getElementById('labelPRS').style.backgroundColor = '#0f539c';
				getElementById('labelPRS').style.color = '#fff';
				getElementById('divPRS').style.display = 'block';
				break;
			case 'tsPAL':
				getElementById('labelPAL').style.backgroundColor = '#0f539c';
				getElementById('labelPAL').style.color = '#fff';
				getElementById('divPAL').style.display = 'block';
				break;
			case 'ts463':
				getElementById('labelVerosimilitud').style.backgroundColor = '#0f539c';
				getElementById('labelVerosimilitud').style.color = '#fff';
				getElementById('div463').style.display = 'block';
				break;
			case 'tsBasica':
				getElementById('labelBasica').style.backgroundColor = '#0f539c';
				getElementById('labelBasica').style.color = '#fff';
				getElementById('divBasica').style.display = 'block';
				break;
		}
	}
}

function mouseOver(obj) {
	with (document) {
		objSel = "";
		if  (getElementById('labelAccidente') != null)
		{
			if (getElementById('solapa').value == 'tsAccidente')
				objSel = getElementById('labelAccidente');
			else
				getElementById('labelAccidente').style.backgroundColor = '';
		}
		
		if  (getElementById('labelEnfermedades') != null)
		{
			if (getElementById('solapa').value == 'tsEnfermedades')
				objSel = getElementById('labelEnfermedades');
			else
				getElementById('labelEnfermedades').style.backgroundColor = '';
		}
		
		if  (getElementById('labelPRS') != null)
		{
			if (getElementById('solapa').value == 'tsPRS')
				objSel = getElementById('labelPRS');
			else
				getElementById('labelPRS').style.backgroundColor = '';
		}

		if  (getElementById('labelPAL') != null)
		{		
			if (getElementById('solapa').value == 'tsPAL')
				objSel = getElementById('labelPAL');
			else
				getElementById('labelPAL').style.backgroundColor = '';	
		}

		if  (getElementById('labelVerosimilitud') != null)
		{
			if (getElementById('solapa').value == 'ts463')
				objSel = getElementById('labelVerosimilitud');
			else
				getElementById('labelVerosimilitud').style.backgroundColor = '';
		}
		
		if  (getElementById('labelBasica') != null)
		{
			if (getElementById('solapa').value == 'tsBasica')
				objSel = getElementById('labelBasica');
			else
				getElementById('labelBasica').style.backgroundColor = '';
		}

		if (objSel != obj)
			obj.style.backgroundColor = '#2E70AA';
	}
}

function mouseOut(obj) {
	with (document) {
		objSel = "";
		if  (getElementById('labelAccidente') != null)
		{
			if (getElementById('solapa').value == 'tsAccidente')
				objSel = getElementById('labelAccidente');
		}

		if  (getElementById('labelEnfermedades') != null)
		{
			if (getElementById('solapa').value == 'tsEnfermedades')
				objSel = getElementById('labelEnfermedades');
		}
		
		if  (getElementById('labelPRS') != null)
		{
			if (getElementById('solapa').value == 'tsPRS')
				objSel = getElementById('labelPRS');
		}

		if  (getElementById('labelPAL') != null)
		{
			if (getElementById('solapa').value == 'tsPAL')
				objSel = getElementById('labelPAL');
		}

		if  (getElementById('labelVerosimilitud') != null)
		{
			if (getElementById('solapa').value == 'ts463')
				objSel = getElementById('labelVerosimilitud');
		}

		if  (getElementById('labelBasica') != null)
		{		
			if (getElementById('solapa').value == 'tsBasica')
				objSel = getElementById('labelBasica');
		}

		if (objSel != obj)
			obj.style.backgroundColor = '';
	}
}

function cambiarGrupoDenuncia(id) {
	with (document)
	{
		getElementById('divProcesandoBasica').style.display = 'block';
		if ((getElementById('grupoDenuncia').value != '') || (getElementById('grupoDenuncia').value != -1))
			getElementById('iframeProcesando').src = '/modules/usuarios_registrados/preventores/cambiar_grupo_denuncia.php?id=' + getElementById('grupoDenuncia').value;
	}
}
