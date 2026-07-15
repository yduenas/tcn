function copiarTablaAlPortapapeles(idTabla, idBoton) {
	var tabla = document.getElementById(idTabla);
	if (!tabla) return;

	var filas = tabla.querySelectorAll('tr');
	var lineas = [];
	filas.forEach(function (fila) {
		var celdas = fila.querySelectorAll('th, td');
		var valores = [];
		celdas.forEach(function (celda) {
			valores.push(celda.textContent.trim());
		});
		lineas.push(valores.join('\t'));
	});
	var texto = lineas.join('\n');

	function marcarCopiado() {
		var boton = document.getElementById(idBoton);
		if (!boton) return;
		var textoOriginal = boton.textContent;
		boton.textContent = '¡Copiado!';
		setTimeout(function () { boton.textContent = textoOriginal; }, 1500);
	}

	if (navigator.clipboard && window.isSecureContext) {
		navigator.clipboard.writeText(texto).then(marcarCopiado);
	} else {
		var areaTemporal = document.createElement('textarea');
		areaTemporal.value = texto;
		areaTemporal.style.position = 'fixed';
		areaTemporal.style.opacity = '0';
		document.body.appendChild(areaTemporal);
		areaTemporal.select();
		document.execCommand('copy');
		document.body.removeChild(areaTemporal);
		marcarCopiado();
	}
}
