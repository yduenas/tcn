<div style="min-height:calc(100vh - 40px); display:flex; align-items:center; justify-content:center; padding:40px 20px;">
	<div style="width:100%; max-width:400px;">
		<div class="text-center mb-4">
			<div style="font-family:'Space Grotesk',sans-serif; font-weight:700; color:var(--blue-dark); letter-spacing:.01em;">COMPLEMENT</div>
			<span class="eyebrow">Recuperar contraseña</span>
		</div>

		<div class="card">
			<div class="card-body p-4">
				<?php if(!empty($datos['mensaje'])): ?>
					<div class="alert alert-success"><?= htmlspecialchars($datos['mensaje']) ?></div>
				<?php else: ?>
					<p class="text-muted" style="font-size:.9rem;">Ingresa tu correo y te enviaremos un enlace para restablecer tu contraseña.</p>
				<?php endif; ?>

				<form method="post" action="<?= RUTA_URL ?>logins/enviarRecuperacion">
					<div class="form-group">
						<label for="email">Correo</label>
						<input type="email" class="form-control" id="email" name="email" required autofocus>
					</div>
					<button type="submit" class="btn btn-primary btn-block">Enviar enlace</button>
				</form>
				<div class="text-center mt-3">
					<a href="<?= RUTA_URL ?>logins/index" style="font-size:.88rem;">&larr; Volver a ingresar</a>
				</div>
			</div>
		</div>
	</div>
</div>
