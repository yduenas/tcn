<div class="app-main" style="max-width:480px;">
	<h4 class="mb-4">Cambiar contraseña</h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>
	<?php if(!empty($datos['mensaje'])): ?>
		<div class="alert alert-success"><?= htmlspecialchars($datos['mensaje']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL ?>logins/actualizarPasswordPropia">
		<div class="form-group">
			<label for="password_actual">Contraseña actual</label>
			<input type="password" class="form-control" id="password_actual" name="password_actual" required>
		</div>
		<div class="form-group">
			<label for="password_nueva">Contraseña nueva</label>
			<input type="password" class="form-control" id="password_nueva" name="password_nueva" minlength="8" required>
		</div>
		<div class="form-group">
			<label for="confirmar_password">Confirmar contraseña nueva</label>
			<input type="password" class="form-control" id="confirmar_password" name="confirmar_password" minlength="8" required>
		</div>
		<button type="submit" class="btn btn-primary">Guardar</button>
		<a href="<?= RUTA_URL ?>dashboard/index" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>
