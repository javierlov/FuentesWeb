function SetOtros() {
	with (document)
		getElementById('Otros').disabled =
			((getElementById('Tema1').options[getElementById('Tema1').selectedIndex].text.substr(0, 5) != "Otros") &&
			 (getElementById('Tema2').options[getElementById('Tema2').selectedIndex].text.substr(0, 5) != "Otros") &&
			 (getElementById('Tema3').options[getElementById('Tema3').selectedIndex].text.substr(0, 5) != "Otros"));
}