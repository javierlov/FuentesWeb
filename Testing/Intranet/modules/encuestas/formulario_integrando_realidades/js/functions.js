function seleccionaPrimerPregunta() {
	formIntegrando.check1.disabled = !formIntegrando.p1[0].checked;
	formIntegrando.check2.disabled = formIntegrando.check1.disabled;
	formIntegrando.check3.disabled = formIntegrando.check1.disabled;
	formIntegrando.check4.disabled = formIntegrando.check1.disabled;
	formIntegrando.check5.disabled = formIntegrando.check1.disabled;
	formIntegrando.check6.disabled = formIntegrando.check1.disabled;
	formIntegrando.Otras.disabled = formIntegrando.check1.disabled;
	
	if (!formIntegrando.p1[0].checked) {
		formIntegrando.check1.checked = false;
		formIntegrando.check2.checked = false;
		formIntegrando.check3.checked = false;
		formIntegrando.check4.checked = false;
		formIntegrando.check5.checked = false;
		formIntegrando.check6.checked = false;
	}
}

function validarForm() {
	if ((!formIntegrando.p1[0].checked) && (!formIntegrando.p1[1].checked)) {
    alert('Por favor conteste la primer pregunta.');
    return false;
	}

	if ((!formIntegrando.p2[0].checked) && (!formIntegrando.p2[1].checked)) {
    alert('Por favor conteste la segunda pregunta.');
    return false;
	}

	if ((formIntegrando.p1[0].checked) && (!formIntegrando.check1.checked) && (!formIntegrando.check2.checked) && 
			(!formIntegrando.check3.checked) && (!formIntegrando.check4.checked) && (!formIntegrando.check5.checked) && 
			(!formIntegrando.check6.checked) && (formIntegrando.Otras.value == '')) {
    alert('Por favor indique que modalidad de participación prefiere.');
    return false;
	}

	return true;
}