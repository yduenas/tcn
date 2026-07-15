<div class="app-main" style="max-width:560px;">
	<h4 class="mb-4">Nuevo usuario</h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL ?>usuarios/guardar">
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="nombres">Nombres</label>
				<input type="text" class="form-control" id="nombres" name="nombres" required>
			</div>
			<div class="form-group col-md-6">
				<label for="apellidos">Apellidos</label>
				<input type="text" class="form-control" id="apellidos" name="apellidos" required>
			</div>
		</div>
		<div class="form-group">
			<label for="email">Correo</label>
			<input type="email" class="form-control" id="email" name="email" required>
		</div>
		<div class="form-group">
			<label for="password">Contraseña</label>
			<input type="password" class="form-control" id="password" name="password" required>
		</div>
		<div class="form-group">
			<label for="perfil_id">Perfil</label>
			<select class="form-control" id="perfil_id" name="perfil_id" required>
				<option value="">Selecciona un perfil</option>
				<?php foreach($datos['perfiles'] as $perfil): ?>
					<option value="<?= $perfil->id ?>"><?= htmlspecialchars($perfil->nombre) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="form-group">
			<label for="empresa_id">Empresa (solo si el perfil es "Empresa")</label>
			<select class="form-control" id="empresa_id" name="empresa_id">
				<option value="">Sin empresa (uso interno: Administrador / Seleccionador)</option>
				<?php foreach($datos['empresas'] as $empresa): ?>
					<option value="<?= $empresa->id ?>"><?= htmlspecialchars($empresa->nombre) ?></option>
				<?php endforeach; ?>
			</select>
		</div>
		<button type="submit" class="btn btn-primary">Guardar</button>
		<a href="<?= RUTA_URL ?>usuarios/index" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>
