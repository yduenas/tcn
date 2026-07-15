<?php $usuario = $datos['usuario']; ?>
<div class="app-main" style="max-width:480px;">
	<a href="<?= RUTA_URL ?>usuarios/index" class="btn btn-link px-0">&larr; Volver a usuarios</a>

	<h4 class="mb-1 mt-2">Nueva contraseña</h4>
	<p class="text-muted mb-4"><?= htmlspecialchars($usuario->nombres.' '.$usuario->apellidos) ?> (<?= htmlspecialchars($usuario->email) ?>)</p>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL ?>usuarios/guardarContrasena/<?= $usuario->id ?>">
		<div class="form-group">
			<label>Nueva contraseña</label>
			<input type="password" class="form-control" name="password" minlength="8" required autofocus autocomplete="new-password">
		</div>
		<div class="form-group">
			<label>Confirmar contraseña</label>
			<input type="password" class="form-control" name="confirmar_password" minlength="8" required autocomplete="new-password">
		</div>
		<button type="submit" class="btn btn-primary">Guardar nueva contraseña</button>
		<a href="<?= RUTA_URL ?>usuarios/index" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>
