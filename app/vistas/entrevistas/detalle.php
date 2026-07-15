<?php $e = $datos['entrevista']; ?>
<div class="app-main" style="max-width:720px;">
	<h4 class="mb-1">Entrevista: <?= htmlspecialchars($e->candidato_nombres.' '.$e->candidato_apellidos) ?></h4>
	<p class="text-muted mb-4">
		<?= htmlspecialchars($e->vacante_titulo) ?> ·
		Agendada: <?= htmlspecialchars($e->fecha_agendada) ?> ·
		Entrevistador: <?= htmlspecialchars($e->entrevistador_nombres.' '.$e->entrevistador_apellidos) ?>
		<?php if($e->fecha_realizada): ?> · Realizada: <?= htmlspecialchars(fechaLocal($e->fecha_realizada)) ?><?php endif; ?>
	</p>

	<?php if($datos['bloqueada']): ?>
		<div class="alert alert-secondary">
			Esta postulación ya llegó a un estado final (<?= htmlspecialchars($e->estado_nombre) ?>), así que la entrevista quedó de solo lectura.
			Solo el Administrador puede seguir editándola.
		</div>
	<?php endif; ?>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<?php $hayObligatorias = !empty($datos['obligatorias']); ?>
	<?php if($hayObligatorias && !$datos['bloqueada']): ?>
		<div class="alert alert-info">
			Esta vacante marcó competencias obligatorias (resaltadas abajo). Calificarlas todas, y completar Notas y Recomendación final, es obligatorio para guardar.
		</div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL ?>entrevistas/guardarCalificacion/<?= $e->id ?>">
	<fieldset <?= $datos['bloqueada'] ? 'disabled' : '' ?>>

		<h6 class="text-uppercase text-muted mb-2">Calificación por competencia</h6>
		<?php
			$escala = [1 => 'Insuficiente', 2 => 'Bajo', 3 => 'Aceptable', 4 => 'Bueno', 5 => 'Excelente'];
		?>
		<table class="table table-sm table-bordered align-middle mb-4">
			<thead>
				<tr><th>Competencia</th><th style="width:180px;">Calificación</th><th>Comentario</th></tr>
			</thead>
			<tbody>
				<?php foreach($datos['competencias'] as $c): ?>
				<?php $actual = $datos['calificaciones'][$c->id] ?? null; ?>
				<?php $esObligatoria = in_array($c->id, $datos['obligatorias']); ?>
				<tr>
					<td>
						<?= htmlspecialchars($c->nombre) ?>
						<?php if($esObligatoria): ?><span class="badge badge-warning">Obligatoria</span><?php endif; ?>
					</td>
					<td>
						<select class="form-control form-control-sm" name="calificacion[<?= $c->id ?>]" <?= $esObligatoria ? 'required' : '' ?>>
							<option value="">-</option>
							<?php foreach($escala as $valor => $etiqueta): ?>
								<option value="<?= $valor ?>" <?= ($actual && $actual->calificacion == $valor) ? 'selected' : '' ?>>
									<?= $valor ?> - <?= $etiqueta ?>
								</option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<input type="text" class="form-control form-control-sm" name="comentario[<?= $c->id ?>]"
						       value="<?= htmlspecialchars($actual->comentario ?? '') ?>">
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>

		<div class="form-group">
			<label>Notas de la entrevista <?= $hayObligatorias ? '*' : '' ?></label>
			<textarea class="form-control" name="notas" rows="4" <?= $hayObligatorias ? 'required' : '' ?>><?= htmlspecialchars($e->notas ?? '') ?></textarea>
		</div>

		<div class="form-group">
			<label>Recomendación final <?= $hayObligatorias ? '*' : '' ?></label>
			<select class="form-control" name="recomendacion" <?= $hayObligatorias ? 'required' : '' ?>>
				<option value="">Selecciona</option>
				<option value="recomendado" <?= $e->recomendacion === 'recomendado' ? 'selected' : '' ?>>Recomendado</option>
				<option value="recomendado_con_reservas" <?= $e->recomendacion === 'recomendado_con_reservas' ? 'selected' : '' ?>>Recomendado con reservas</option>
				<option value="no_recomendado" <?= $e->recomendacion === 'no_recomendado' ? 'selected' : '' ?>>No recomendado</option>
			</select>
		</div>

		<?php if(!$datos['bloqueada']): ?>
			<button type="submit" class="btn btn-primary">Guardar</button>
		<?php endif; ?>
		<a href="<?= RUTA_URL ?>postulaciones/vacante/<?= $e->vacante_id ?>" class="btn btn-outline-secondary">Volver</a>
	</fieldset>
	</form>
</div>
