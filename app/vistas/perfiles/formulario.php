<?php $perfil = $datos['perfil']; ?>
<div class="app-main" style="max-width:760px;">
	<h4 class="mb-4"><?= $perfil ? 'Editar perfil' : 'Nuevo perfil' ?></h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL.($perfil ? 'perfiles/actualizar/'.$perfil->id : 'perfiles/guardar') ?>">
		<div class="form-row">
			<div class="form-group col-md-5">
				<label>Nombre del perfil</label>
				<input type="text" class="form-control" name="nombre" required
				       value="<?= htmlspecialchars($perfil->nombre ?? '') ?>">
			</div>
			<div class="form-group col-md-7">
				<label>Descripción</label>
				<input type="text" class="form-control" name="descripcion"
				       value="<?= htmlspecialchars($perfil->descripcion ?? '') ?>">
			</div>
		</div>

		<label>Permisos</label>
		<div class="row">
			<?php foreach($datos['permisosPorCategoria'] as $categoria => $permisos): ?>
			<div class="col-md-6 mb-3">
				<div class="card">
					<div class="card-body">
						<h6 class="text-uppercase text-muted mb-2" style="font-size:.78rem;"><?= htmlspecialchars($categoria) ?></h6>
						<?php foreach($permisos as $permiso): ?>
						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="permiso_<?= $permiso->id ?>"
							       name="permisos[]" value="<?= $permiso->id ?>"
							       <?= in_array($permiso->id, $datos['asignados']) ? 'checked' : '' ?>>
							<label class="form-check-label" for="permiso_<?= $permiso->id ?>" style="font-size:.88rem;">
								<?= htmlspecialchars($permiso->descripcion) ?>
							</label>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>

		<button type="submit" class="btn btn-primary">Guardar</button>
		<a href="<?= RUTA_URL ?>perfiles/index" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>
