function showTitle(value, text) {
	if (value) {
		document.getElementById('titleText').innerHTML = text;
		document.getElementById('title').style.display = 'block';
		document.getElementById('title').style.height = '44px';
		document.getElementById('tope').style.height = '148px';
	}
	else {
		document.getElementById('title').style.display = 'none';
		document.getElementById('title').style.height = '0';
		document.getElementById('tope').style.height = '108px';
	}
}