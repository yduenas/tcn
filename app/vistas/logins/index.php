<div style="min-height:calc(100vh - 40px); display:flex; align-items:center; justify-content:center; padding:40px 20px;">
	<div style="width:100%; max-width:400px;">
		<div class="text-center mb-4">
			<svg viewBox="0 0 100 100" fill="none" style="width:44px; height:44px; margin:0 auto 10px;">
				<circle cx="50" cy="50" r="22" stroke="#1B4C91" stroke-width="6"/>
				<circle cx="72" cy="34" r="4.5" fill="#1B4C91"/>
				<circle cx="24" cy="42" r="4.5" fill="#3ECAB5"/>
				<circle cx="30" cy="72" r="4.5" fill="#1B4C91"/>
				<circle cx="76" cy="66" r="4.5" fill="#3ECAB5"/>
				<path d="M68 35 C58 20, 40 20, 28 38" stroke="#1B4C91" stroke-width="3" fill="none" stroke-linecap="round"/>
				<path d="M25 46 C18 58, 20 66, 28 70" stroke="#3ECAB5" stroke-width="3" fill="none" stroke-linecap="round"/>
				<path d="M35 76 C48 84, 64 80, 74 68" stroke="#1B4C91" stroke-width="3" fill="none" stroke-linecap="round"/>
				<circle cx="66" cy="52" r="2.6" fill="#1B4C91"/>
			</svg>
			<div style="font-family:'Space Grotesk',sans-serif; font-weight:700; color:var(--blue-dark); letter-spacing:.01em;">COMPLEMENT</div>
			<span class="eyebrow">Trabaja con nosotros · Panel interno</span>
		</div>

		<div class="card">
			<div class="card-body p-4">
				<?php if(!empty($datos['error'])): ?>
					<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
				<?php endif; ?>

				<form method="post" action="<?= RUTA_URL ?>logins/autenticar">
					<div class="form-group">
						<label for="email">Correo</label>
						<input type="email" class="form-control" id="email" name="email" required autofocus>
					</div>
					<div class="form-group">
						<label for="password">Contraseña</label>
						<input type="password" class="form-control" id="password" name="password" required>
					</div>
					<button type="submit" class="btn btn-primary btn-block">Ingresar</button>
				</form>
				<div class="text-center mt-3">
					<a href="<?= RUTA_URL ?>logins/recuperar" style="font-size:.88rem;">¿Olvidaste tu contraseña?</a>
				</div>
			</div>
		</div>
	</div>
</div>
