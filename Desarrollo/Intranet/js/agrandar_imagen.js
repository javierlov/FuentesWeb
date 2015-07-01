//<![CDATA[
/* Expando Image Script 2008 John Davenport Scheuer
as first seen in http://www.dynamicdrive.com/forums/
username: jscheuer1 - This Notice Must Remain for Legal Use
*/

if (document.images) {(
	function() {
		var cos, a = /Apple/.test(navigator.vendor), times = a?20:40, speed = a?40:20;
		var expConIm = function(im) {
			im = im || window.event;
			if (!expConIm.r.test (im.className))
				im = im.target || im.srcElement || null;

			if (!im || !expConIm.r.test (im.className))
				return;

			var e = expConIm, widthHeight = function(dim) {
				return dim[0] * cos + dim[1] + 'px';
			},

			resize = function() {
				cos = (1 - Math.cos((e.ims[i].jump / times) * Math.PI)) / 2;
				im.style.width = widthHeight (e.ims[i].w);
				im.style.height = widthHeight (e.ims[i].h);

				if (e.ims[i].d && times > e.ims[i].jump) {
					++e.ims[i].jump;
					im.style.top = (parseInt(im.style.top) - 5) + 'px';
					e.ims[i].timer = setTimeout(resize, speed);
				}
				else if (!e.ims[i].d && e.ims[i].jump > 0) {
					--e.ims[i].jump;
					im.style.top = (parseInt(im.style.top) + 5) + 'px';
					e.ims[i].timer = setTimeout(resize, speed);

//					if (e.ims[i].jump < 1)
//						im.style.display = 'none';
//						im.parentNode.removeChild(im);
				}
			},

			d = document.images, i = d.length - 1;

			for (i; i > -1; --i)
				if (d[i] == im)
					break;

			i = i + im.src;
			if (!e.ims[i]) {
				im.title = '';
				e.ims[i] = {im : new Image(), jump : 0};
				e.ims[i].im.onload = function() {
					e.ims[i].w = [e.ims[i].im.width - im.width, im.width];
					e.ims[i].h = [e.ims[i].im.height - im.height, im.height];
					e(im);
				};

				e.ims[i].im.src = im.src;
				return;
			}

			if (e.ims[i].timer)
				clearTimeout(e.ims[i].timer);
			e.ims[i].d = !e.ims[i].d;

			resize ();
		};

		expConIm.ims = {};

		expConIm.r = new RegExp('\\bexpando\\b');

		if (document.addEventListener) {
			document.addEventListener('mouseover', expConIm, false);
			document.addEventListener('mouseout', expConIm, false);
		}
		else if (document.attachEvent) {
			document.attachEvent('onmouseover', expConIm);
			document.attachEvent('onmouseout', expConIm);
		}
	}
) ();
}
//]]>

function crearImgExpandible(id) {
	var oImg = document.createElement('img');
	oImg.setAttribute('class', 'expando');
	oImg.setAttribute('id', 'imgExpandible_' + id);
	oImg.setAttribute('src', '');

	oImg.style.display = 'none';
	oImg.style.height = '1px';
	oImg.style.position = 'absolute';
	oImg.style.width = '1px';
	oImg.style.zIndex = '200';

	document.getElementsByTagName('body')[0].appendChild(oImg);

	return oImg;
}

function expandirImagen(obj) {
//	if (document.getElementById('imgExpandible_' + obj.id) != null)
//		return;

//	if ((document.getElementById('imgExpandible_' + obj.id) != null) && (document.getElementById('imgExpandible_' + obj.id).style.display != 'none'))
//		return;
/*
	var img = crearImgExpandible(obj.id);
	var pos = getAbsoluteElementPosition(obj);

	img.style.height = obj.offsetHeight + 'px';
	img.style.left = pos.left + 'px';
	img.style.top = pos.top + 'px';
	img.style.width = obj.offsetWidth + 'px';
	img.src = obj.src;
	img.style.display = 'block';*/
	obj.class = 'expando';
	obj.style.display = 'block';
}

//crearImgExpandible();