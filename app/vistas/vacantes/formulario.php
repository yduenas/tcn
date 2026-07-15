<?php $vacante = $datos['vacante']; ?>
<div class="app-main" style="max-width:640px;">
	<h4 class="mb-4"><?= $vacante ? 'Editar vacante' : 'Nueva vacante' ?></h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL.($vacante ? 'vacantes/actualizar/'.$vacante->id : 'vacantes/guardar') ?>">
		<div class="form-group">
			<label for="empresa_id">Empresa</label>
			<select class="form-control" id="empresa_id" name="empresa_id" required>
				<option value="">Selecciona una empresa</option>
				<?php foreach($datos['empresas'] as $empresa): ?>
					<option value="<?= $empresa->id ?>" <?= ($vacante && $vacante->empresa_id == $empresa->id) ? 'selected' : '' ?>>
						<?= htmlspecialchars($empresa->nombre) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="seleccionador_id">Seleccionador asignado</label>
			<select class="form-control" id="seleccionador_id" name="seleccionador_id" required>
				<option value="">Selecciona</option>
				<?php foreach($datos['seleccionadores'] as $seleccionador): ?>
					<option value="<?= $seleccionador->id ?>" <?= ($vacante && $vacante->seleccionador_id == $seleccionador->id) ? 'selected' : '' ?>>
						<?= htmlspecialchars($seleccionador->nombres.' '.$seleccionador->apellidos) ?>
					</option>
				<?php endforeach; ?>
			</select>
			<?php if(empty($datos['seleccionadores'])): ?>
				<small class="form-text text-danger">No hay ningún usuario activo con perfil Seleccionador todavía — crea uno en Usuarios antes de poder guardar la vacante.</small>
			<?php endif; ?>
		</div>
		<div class="form-group">
			<label for="titulo">Título</label>
			<input type="text" class="form-control" id="titulo" name="titulo" required
			       value="<?= htmlspecialchars($vacante->titulo ?? '') ?>">
		</div>
		<div class="form-group">
			<label for="objetivo_puesto">Objetivo del puesto</label>
			<textarea class="form-control rich-editor" id="objetivo_puesto" name="objetivo_puesto"><?= htmlspecialchars($vacante->objetivo_puesto ?? '') ?></textarea>
		</div>
		<div class="form-group">
			<label for="funciones">Funciones</label>
			<textarea class="form-control rich-editor" id="funciones" name="funciones"><?= htmlspecialchars($vacante->funciones ?? '') ?></textarea>
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="cargo_categoria_id">Categoría de cargo</label>
				<select class="form-control" id="cargo_categoria_id" name="cargo_categoria_id" required>
					<option value="">Selecciona</option>
					<?php foreach($datos['cargos'] as $cargo): ?>
						<option value="<?= $cargo->id ?>" <?= ($vacante && $vacante->cargo_categoria_id == $cargo->id) ? 'selected' : '' ?>>
							<?= htmlspecialchars($cargo->nombre) ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="form-group col-md-6">
				<label for="modalidad_id">Modalidad</label>
				<select class="form-control" id="modalidad_id" name="modalidad_id" required>
					<option value="">Selecciona</option>
					<?php foreach($datos['modalidades'] as $modalidad): ?>
						<option value="<?= $modalidad->id ?>" <?= ($vacante && $vacante->modalidad_id == $modalidad->id) ? 'selected' : '' ?>>
							<?= htmlspecialchars($modalidad->nombre) ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label for="ubicacion">Ubicación</label>
			<input type="text" class="form-control" id="ubicacion" name="ubicacion"
			       value="<?= htmlspecialchars($vacante->ubicacion ?? '') ?>">
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="salario_min">Salario mínimo</label>
				<input type="number" step="0.01" class="form-control" id="salario_min" name="salario_min"
				       value="<?= htmlspecialchars($vacante->salario_min ?? '') ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="salario_max">Salario máximo</label>
				<input type="number" step="0.01" class="form-control" id="salario_max" name="salario_max"
				       value="<?= htmlspecialchars($vacante->salario_max ?? '') ?>">
			</div>
		</div>
		<div class="form-group form-check">
			<input type="checkbox" class="form-check-input" id="es_anonima" name="es_anonima"
			       <?= ($vacante && $vacante->es_anonima) ? 'checked' : '' ?>>
			<label class="form-check-label" for="es_anonima">Modo anónimo (no mostrar logo ni nombre de la empresa al postulante)</label>
		</div>
		<div class="form-group form-check">
			<input type="checkbox" class="form-check-input" id="ocultar_salario" name="ocultar_salario"
			       <?= ($vacante && $vacante->ocultar_salario) ? 'checked' : '' ?>>
			<label class="form-check-label" for="ocultar_salario">Ocultar rango salarial en la publicación pública</label>
		</div>
		<div class="form-group">
			<label>Evaluaciones a aplicar</label>
			<?php foreach($datos['evaluaciones'] as $evaluacion): ?>
				<div class="form-check">
					<input type="checkbox" class="form-check-input" id="evaluacion_<?= $evaluacion->id ?>"
					       name="evaluaciones[]" value="<?= $evaluacion->id ?>"
					       <?= in_array($evaluacion->id, $datos['evaluaciones_asignadas']) ? 'checked' : '' ?>>
					<label class="form-check-label" for="evaluacion_<?= $evaluacion->id ?>"><?= htmlspecialchars($evaluacion->nombre) ?></label>
				</div>
			<?php endforeach; ?>
		</div>
		<div class="form-group">
			<label>Competencias a evaluar en la entrevista</label>
			<small class="form-text text-muted mt-0 mb-2">Si no seleccionas ninguna, la entrevista mostrará las 10 competencias del catálogo por defecto.</small>
			<?php foreach($datos['competencias'] as $competencia): ?>
				<div class="form-check">
					<input type="checkbox" class="form-check-input" id="competencia_<?= $competencia->id ?>"
					       name="competencias[]" value="<?= $competencia->id ?>"
					       <?= in_array($competencia->id, $datos['competencias_asignadas']) ? 'checked' : '' ?>>
					<label class="form-check-label" for="competencia_<?= $competencia->id ?>">
						<?= htmlspecialchars($competencia->nombre) ?>
						<?php if(!$competencia->activo): ?><span class="badge badge-secondary">Anulada</span><?php endif; ?>
					</label>
				</div>
			<?php endforeach; ?>
		</div>
		<button type="submit" class="btn btn-primary">Guardar</button>
		<a href="<?= RUTA_URL ?>vacantes/index" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>
