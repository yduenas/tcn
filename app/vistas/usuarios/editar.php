<div class="app-main" style="max-width:560px;">
	<h4 class="mb-4">Editar usuario</h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL ?>usuarios/actualizar/<?= $datos['usuario']->id ?>">
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="nombres">Nombres</label>
				<input type="text" class="form-control" id="nombres" name="nombres" value="<?= htmlspecialchars($datos['usuario']->nombres) ?>" required>
			</div>
			<div class="form-group col-md-6">
				<label for="apellidos">Apellidos</label>
				<input type="text" class="form-control" id="apellidos" name="apellidos" value="<?= htmlspecialchars($datos['usuario']->apellidos) ?>" required>
			</div>
		</div>
		<div class="form-group">
			<label for="email">Correo</label>
			<input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($datos['usuario']->email) ?>" required>
		</div>
		<button type="submit" class="btn btn-primary">Guardar</button>
		<a href="<?= RUTA_URL ?>usuarios/index" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>
