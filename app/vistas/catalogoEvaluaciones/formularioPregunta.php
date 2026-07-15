<?php
	$evaluacion = $datos['evaluacion'];
	$pregunta = $datos['pregunta'];
	$opciones = $pregunta->opciones ?? [];
	while(count($opciones) < 4){ $opciones[] = (object) ['texto' => '', 'puntaje' => 0, 'etiqueta' => null]; }
	$letrasDisc = ['D', 'I', 'S', 'C'];
?>
<div class="app-main" style="max-width:720px;">
	<a href="<?= RUTA_URL ?>catalogoEvaluaciones/preguntas/<?= $evaluacion->id ?>" class="btn btn-link px-0">&larr; Volver a preguntas</a>

	<h4 class="mb-4"><?= $pregunta ? 'Editar pregunta' : 'Nueva pregunta' ?>: <?= htmlspecialchars($evaluacion->nombre) ?></h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL.($pregunta ? 'catalogoEvaluaciones/actualizarPregunta/'.$pregunta->id : 'catalogoEvaluaciones/guardarPregunta/'.$evaluacion->id) ?>">

		<div class="form-group">
			<label>Enunciado</label>
			<textarea class="form-control" name="enunciado" rows="2" required><?= htmlspecialchars($pregunta->enunciado ?? '') ?></textarea>
		</div>

		<?php if($evaluacion->tipo === 'sjt'): ?>
		<div class="form-group">
			<label>Competencia</label>
			<select class="form-control" name="competencia_nombre" required>
				<option value="">Selecciona</option>
				<?php foreach($datos['competencias'] as $c): ?>
					<option value="<?= htmlspecialchars($c->nombre) ?>" <?= ($pregunta && $pregunta->competencia_nombre === $c->nombre) ? 'selected' : '' ?>>
						<?= htmlspecialchars($c->nombre) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<?php endif; ?>

		<label>Opciones</label>
		<?php if($evaluacion->tipo === 'opcion_unica'): ?>
			<p class="text-muted" style="font-size:.82rem;">Marca puntaje 1 en la opción correcta, 0 en las demás.</p>
		<?php elseif($evaluacion->tipo === 'sjt'): ?>
			<p class="text-muted" style="font-size:.82rem;">Puntaje 0 a 3: 3 = más efectiva, 0 = menos efectiva.</p>
		<?php else: ?>
			<p class="text-muted" style="font-size:.82rem;">Asigna la dimensión DISC de cada opción.</p>
		<?php endif; ?>

		<div id="opciones-filas">
			<?php foreach($opciones as $i => $opcion): ?>
			<div class="form-row opcion-fila align-items-center mb-2">
				<div class="col-md-7">
					<input type="text" class="form-control" name="opcion_texto[]" placeholder="Texto de la opción" value="<?= htmlspecialchars($opcion->texto) ?>">
				</div>
				<?php if($evaluacion->tipo === 'forzada'): ?>
					<div class="col-md-3">
						<select class="form-control" name="opcion_etiqueta[]">
							<?php foreach($letrasDisc as $letra): ?>
								<option value="<?= $letra ?>" <?= ($opcion->etiqueta === $letra) || (!$pregunta && $letrasDisc[$i % 4] === $letra) ? 'selected' : '' ?>><?= $letra ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<input type="hidden" name="opcion_puntaje[]" value="0">
				<?php else: ?>
					<div class="col-md-3">
						<input type="number" min="0" max="<?= $evaluacion->tipo === 'sjt' ? 3 : 1 ?>" class="form-control" name="opcion_puntaje[]" value="<?= (int) $opcion->puntaje ?>">
					</div>
					<input type="hidden" name="opcion_etiqueta[]" value="">
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="btn btn-sm btn-outline-secondary mb-4" onclick="agregarOpcion()">+ Agregar opción</button>
		<br>

		<button type="submit" class="btn btn-primary">Guardar</button>
		<a href="<?= RUTA_URL ?>catalogoEvaluaciones/preguntas/<?= $evaluacion->id ?>" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>

<script>
function agregarOpcion(){
	var contenedor = document.getElementById('opciones-filas');
	var primeraFila = contenedor.querySelector('.opcion-fila');
	var nuevaFila = primeraFila.cloneNode(true);
	nuevaFila.querySelectorAll('input[type="text"], input[type="number"]').forEach(function(campo){ campo.value = campo.type === 'number' ? 0 : ''; });
	contenedor.appendChild(nuevaFila);
}
</script>
