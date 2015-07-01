var specifyimage = new Array(); //Your images
specifyimage[0] = "images/logo_provart.jpg";
specifyimage[1] = "images/logo_provart.jpg";

var count = 1;		// Counter for array
var delay = 5000;		// 5 seconds

var cubeimage = new Array();
for (i=0;i<specifyimage.length;i++){
	cubeimage[i] = new Image();
	cubeimage[i].src = specifyimage[i];
}

function movecube() {
	try {
		if (window.createPopup)
			cube.filters[0].apply();
		document.images.cube.src = cubeimage[count].src;
		if (window.createPopup)
			cube.filters[0].play();
		count++;
		if (count == cubeimage.length)
			count = 0;
		setTimeout("movecube()", delay);
		}
	catch (err) {
		//
	}
}