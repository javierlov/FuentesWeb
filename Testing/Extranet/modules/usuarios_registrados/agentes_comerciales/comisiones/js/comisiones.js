function cambiarSolapa(solapa) {
	with (parent.document) {
		getElementById('solapa').value = solapa;

		getElementById('labelLiquidaciones').style.backgroundColor = '';
		getElementById('labelMovimientos').style.backgroundColor = '';
		getElementById('labelPendientes').style.backgroundColor = '';
		getElementById('labelRetenciones').style.backgroundColor = '';
		getElementById('labelVendedores').style.backgroundColor = '';

		getElementById('labelLiquidaciones').style.color = '#000';
		getElementById('labelMovimientos').style.color = '#000';
		getElementById('labelPendientes').style.color = '#000';
		getElementById('labelRetenciones').style.color = '#000';
		getElementById('labelVendedores').style.color = '#000';

		getElementById('divLiquidaciones').style.display = 'none';
		getElementById('divMovimientos').style.display = 'none';
		getElementById('divPendientes').style.display = 'none';
		getElementById('divRetenciones').style.display = 'none';
		getElementById('divVendedores').style.display = 'none';

		switch (solapa) {
			case 'l':
				getElementById('labelLiquidaciones').style.backgroundColor = '#0f539c';
				getElementById('labelLiquidaciones').style.color = '#fff';
				getElementById('labelMovimientos').style.display = 'none';
				getElementById('labelRetenciones').style.display = 'none';
				getElementById('labelVendedores').style.display = 'none';
				getElementById('divLiquidaciones').style.display = 'block';
				getElementById('spanLeyenda').style.display = 'block';
				break;
			case 'm':
				getElementById('labelMovimientos').style.backgroundColor = '#0f539c';
				getElementById('labelMovimientos').style.color = '#fff';
				getElementById('labelMovimientos').style.display = 'inline';
				getElementById('divMovimientos').style.display = 'block';
				getElementById('spanLeyenda').style.display = 'none';
				break;
			case 'p':
				getElementById('labelPendientes').style.backgroundColor = '#0f539c';
				getElementById('labelPendientes').style.color = '#fff';
				getElementById('divPendientes').style.display = 'block';
				getElementById('spanLeyenda').style.display = 'none';
				break;
			case 'r':
				getElementById('labelRetenciones').style.backgroundColor = '#0f539c';
				getElementById('labelRetenciones').style.color = '#fff';
				getElementById('labelRetenciones').style.display = 'inline';
				getElementById('divRetenciones').style.display = 'block';
				getElementById('spanLeyenda').style.display = 'none';
				break;
			case 'v':
				getElementById('labelVendedores').style.backgroundColor = '#0f539c';
				getElementById('labelVendedores').style.color = '#fff';
				getElementById('labelVendedores').style.display = 'inline';
				getElementById('divVendedores').style.display = 'block';
				getElementById('spanLeyenda').style.display = 'none';
				break;
		}
	}
}

function exportarGrilla() {
	iframeProcesando.location.href = '/modules/usuarios_registrados/agentes_comerciales/comisiones/exportar_grilla_a_excel.php?s=' + document.getElementById('solapa').value;
}

function mouseOver(obj) {
	with (document) {
		if (getElementById('solapa').value == 'l')
			objSel = getElementById('labelLiquidaciones');
		else
			getElementById('labelLiquidaciones').style.backgroundColor = '';

		if (getElementById('solapa').value == 'm')
			objSel = getElementById('labelMovimientos');
		else
			getElementById('labelMovimientos').style.backgroundColor = '';

		if (getElementById('solapa').value == 'p')
			objSel = getElementById('labelPendientes');
		else
			getElementById('labelPendientes').style.backgroundColor = '';

		if (getElementById('solapa').value == 'v')
			objSel = getElementById('labelVendedores');
		else
			getElementById('labelVendedores').style.backgroundColor = '';

		if (getElementById('solapa').value == 'r')
			objSel = getElementById('labelRetenciones');
		else
			getElementById('labelRetenciones').style.backgroundColor = '';

		if (objSel != obj)
			obj.style.backgroundColor = '#aaa';
	}
}

function mouseOut(obj) {
	with (document) {
		if (getElementById('solapa').value == 'l')
			objSel = getElementById('labelLiquidaciones');

		if (getElementById('solapa').value == 'm')
			objSel = getElementById('labelMovimientos');

		if (getElementById('solapa').value == 'p')
			objSel = getElementById('labelPendientes');

		if (getElementById('solapa').value == 'v')
			objSel = getElementById('labelVendedores');

		if (getElementById('solapa').value == 'r')
			objSel = getElementById('labelRetenciones');

		if (objSel != obj)
			obj.style.backgroundColor = '';
	}
}