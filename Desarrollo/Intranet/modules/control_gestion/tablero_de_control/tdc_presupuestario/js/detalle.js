var imagen = new Array;
imagen[0] = new Image;
imagen[0].no = new Image();
imagen[0].pincha = new Image();
imagen[0].si = new Image();

imagen[1] = new Image;
imagen[1].no = new Image();
imagen[1].pincha = new Image();
imagen[1].si = new Image();

imagen[2] = new Image;
imagen[2].no = new Image();
imagen[2].pincha = new Image();
imagen[2].si = new Image();

imagen[3] = new Image;
imagen[3].no = new Image();
imagen[3].pincha = new Image();
imagen[3].si = new Image();

imagen[4] = new Image;
imagen[4].no = new Image();
imagen[4].pincha = new Image();
imagen[4].si = new Image();

imagenAnterior = new Image;

var numeroAnterior = 0;

//--------- RUTA DE LAS IMÁGENES (NO TIENEN QUE SER ABSOLUTAS)---------------
imagen[0].no.src = "./images/detalle/det_1.gif";
imagen[0].pincha.src = "./images/detalle/det_1_pincha.gif";
imagen[0].si.src = "./images/detalle/det_1_si.gif";

imagen[1].no.src = "./images/detalle/det_2.gif";
imagen[1].pincha.src = "./images/detalle/det_2_pincha.gif";
imagen[1].si.src = "./images/detalle/det_2_si.gif";

imagen[2].no.src = "./images/detalle/det_3.gif";
imagen[2].pincha.src = "./images/detalle/det_3_pincha.gif";
imagen[2].si.src = "./images/detalle/det_3_si.gif";

imagen[3].no.src = "./images/detalle/det_4.gif";
imagen[3].pincha.src = "./images/detalle/det_4_pincha.gif";
imagen[3].si.src = "./images/detalle/det_4_si.gif";

imagen[4].no.src = "./images/detalle/det_5.gif";
imagen[4].pincha.src = "./images/detalle/det_5_pincha.gif";
imagen[4].si.src = "./images/detalle/det_5_si.gif";
//------------------------------------------------------------------------------------

function cliqueo(esto,numero) {
	imagenAnterior.src = imagen[numeroAnterior].no.src;
	imagenAnterior = esto;
	numeroAnterior = numero;
	esto.src = (esto.src != imagen[numero].si.src)?imagen[numero].si.src:imagen[numero].no.src;
}

function mostrarMenu(menu) {
	with (document) {
		if (menu =='m') {
			getElementById('trMes1').style.display = 'inline';
			getElementById('trMes2').style.display = 'inline';
			getElementById('trTrimestre').style.display = 'none';
		}

		if (menu =='t') {
			getElementById('trMes1').style.display = 'none';
			getElementById('trMes2').style.display = 'none';
			getElementById('trTrimestre').style.display = 'inline';
		}
	}
}

function recargar(params) {
	document.body.style.cursor = 'wait';
	document.getElementById('divProcesando').style.display = 'block';
	document.getElementById('iframeProcesando').src = 'recargar.php?' + params;
}