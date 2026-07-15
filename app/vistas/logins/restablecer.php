<div style="min-height:calc(100vh - 40px); display:flex; align-items:center; justify-content:center; padding:40px 20px;">
	<div style="width:100%; max-width:400px;">
		<div class="text-center mb-4">
			<div style="font-family:'Space Grotesk',sans-serif; font-weight:700; color:var(--blue-dark); letter-spacing:.01em;">COMPLEMENT</div>
			<span class="eyebrow">Restablecer contraseña</span>
		</div>

		<div class="card">
			<div class="card-body p-4">
				<?php if(!$datos['valido']): ?>
					<div class="alert alert-danger">Este enlace no es válido o ya venció. Solicita uno nuevo.</div>
					<a href="<?= RUTA_URL ?>logins/recuperar" class="btn btn-outline-secondary btn-block">Solicitar nuevo enlace</a>
				<?php else: ?>
					<?php if(!empty($datos['error'])): ?>
						<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
					<?php endif; ?>
					<form method="post" action="<?= RUTA_URL ?>logins/actualizarPassword/<?= htmlspecialchars($datos['token']) ?>">
						<div class="form-group">
							<label>Nueva contraseña</label>
							<input type="password" class="form-control" name="password" minlength="8" required autofocus>
						</div>
						<div class="form-group">
							<label>Confirmar contraseña</label>
							<input type="password" class="form-control" name="confirmar_password" minlength="8" required>
						</div>
						<button type="submit" class="btn btn-primary btn-block">Guardar nueva contraseña</button>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
