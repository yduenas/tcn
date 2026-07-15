<div class="app-main" style="max-width:760px;">
	<h4 class="mb-4">Plantillas de correo</h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>
	<?php if(!empty($datos['mensaje'])): ?>
		<div class="alert alert-success"><?= htmlspecialchars($datos['mensaje']) ?></div>
	<?php endif; ?>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Variables disponibles</h6>
			<p class="small text-muted mb-2">Se reemplazan automáticamente al enviar el correo. No todas aplican a todas las plantillas — cada sección de abajo indica cuáles puede usar.</p>
			<table class="table table-sm mb-0">
				<tbody>
					<tr><td><code>{nombre}</code></td><td class="text-muted">Nombre del candidato</td></tr>
					<tr><td><code>{vacante}</code></td><td class="text-muted">Título de la vacante a la que postuló</td></tr>
					<tr><td><code>{empresa}</code></td><td class="text-muted">Nombre de la empresa (si la vacante es anónima, se reemplaza por "una empresa confidencial" en vez de revelarla)</td></tr>
					<tr><td><code>{seleccionador_nombre}</code></td><td class="text-muted">Nombre del Seleccionador responsable de la vacante</td></tr>
					<tr><td><code>{seleccionador_correo}</code></td><td class="text-muted">Correo del Seleccionador responsable de la vacante</td></tr>
					<tr><td><code>{etapa}</code></td><td class="text-muted">Nombre de la nueva etapa del proceso (solo en las plantillas por etapa)</td></tr>
					<tr><td><code>{link_evaluaciones}</code></td><td class="text-muted">Bloque con el link a las evaluaciones pendientes (solo en "Postulación recibida"). Si la vacante no pide ninguna evaluación, se reemplaza por texto vacío — no requiere envolverlo en su propio párrafo.</td></tr>
					<tr><td><code>{link_estado}</code></td><td class="text-muted">Link para que el candidato consulte el estado de su postulación</td></tr>
					<tr><td><code>{link}</code></td><td class="text-muted">Link de restablecimiento de contraseña (solo en la plantilla de recuperación de contraseña)</td></tr>
				</tbody>
			</table>
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Remitente</h6>
			<p class="small text-muted">Nombre que verá el destinatario como remitente. La cuenta de correo real que envía sigue siendo la configurada en el sistema.</p>
			<form method="post" action="<?= RUTA_URL ?>plantillasCorreo/actualizarRemitente" class="form-inline">
				<input type="text" class="form-control mr-2" name="remitente_nombre" value="<?= htmlspecialchars($datos['configuracion']->remitente_nombre) ?>" required style="max-width:320px;">
				<button type="submit" class="btn btn-outline-primary btn-sm">Guardar</button>
			</form>
		</div>
	</div>

	<?php foreach($datos['plantillas'] as $plantilla): if($plantilla->tipo !== 'recuperacion_password') continue; ?>
	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-1">Recuperación de contraseña</h6>
			<p class="small text-muted mb-3">Se envía siempre que un usuario pide restablecer su contraseña. Placeholders disponibles: <code>{nombre}</code>, <code>{link}</code>.</p>
			<form method="post" action="<?= RUTA_URL ?>plantillasCorreo/actualizar/<?= $plantilla->id ?>">
				<div class="form-group">
					<label class="small text-muted mb-1">Asunto</label>
					<input type="text" class="form-control mb-2" name="asunto" value="<?= htmlspecialchars($plantilla->asunto) ?>" required>
				</div>
				<div class="form-group">
					<label class="small text-muted mb-1">Cuerpo</label>
					<textarea class="form-control rich-editor" name="cuerpo_html"><?= $plantilla->cuerpo_html ?></textarea>
				</div>
				<button type="submit" class="btn btn-outline-secondary btn-sm mt-2">Guardar</button>
				<a href="<?= RUTA_URL ?>plantillasCorreo/probar/<?= $plantilla->id ?>" class="btn btn-outline-primary btn-sm mt-2">Enviar prueba</a>
			</form>
		</div>
	</div>
	<?php endforeach; ?>

	<?php foreach($datos['plantillas'] as $plantilla): if($plantilla->tipo !== 'postulacion_recibida') continue; ?>
	<div class="card mb-4">
		<div class="card-body">
			<div class="d-flex align-items-center justify-content-between mb-1">
				<h6 class="text-uppercase text-muted mb-0">Postulación recibida</h6>
				<?php if($plantilla->activo): ?>
					<a href="<?= RUTA_URL ?>plantillasCorreo/desactivar/<?= $plantilla->id ?>" class="btn btn-sm btn-outline-danger">Deshabilitar envío</a>
				<?php else: ?>
					<a href="<?= RUTA_URL ?>plantillasCorreo/activar/<?= $plantilla->id ?>" class="btn btn-sm btn-outline-success">Habilitar envío</a>
				<?php endif; ?>
			</div>
			<p class="small text-muted mb-3">Se envía apenas un candidato postula a una vacante. Placeholders disponibles: <code>{nombre}</code>, <code>{vacante}</code>, <code>{empresa}</code>, <code>{seleccionador_nombre}</code>, <code>{seleccionador_correo}</code>, <code>{link_evaluaciones}</code>, <code>{link_estado}</code>.
				<?php if(!$plantilla->activo): ?><span class="badge badge-secondary">Envío deshabilitado</span><?php endif; ?>
			</p>
			<form method="post" action="<?= RUTA_URL ?>plantillasCorreo/actualizar/<?= $plantilla->id ?>">
				<div class="form-group">
					<label class="small text-muted mb-1">Asunto</label>
					<input type="text" class="form-control mb-2" name="asunto" value="<?= htmlspecialchars($plantilla->asunto) ?>" required>
				</div>
				<div class="form-group">
					<label class="small text-muted mb-1">Cuerpo</label>
					<textarea class="form-control rich-editor" name="cuerpo_html"><?= $plantilla->cuerpo_html ?></textarea>
				</div>
				<div class="form-group form-check mb-2">
					<input type="checkbox" class="form-check-input" id="cc_<?= $plantilla->id ?>" name="cc_seleccionador" value="1" <?= $plantilla->cc_seleccionador ? 'checked' : '' ?>>
					<label class="form-check-label small" for="cc_<?= $plantilla->id ?>">Copiar (CC) al Seleccionador responsable de la vacante</label>
				</div>
				<button type="submit" class="btn btn-outline-secondary btn-sm mt-2">Guardar</button>
				<a href="<?= RUTA_URL ?>plantillasCorreo/probar/<?= $plantilla->id ?>" class="btn btn-outline-primary btn-sm mt-2">Enviar prueba</a>
			</form>
		</div>
	</div>
	<?php endforeach; ?>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Por cada etapa del proceso</h6>
			<p class="small text-muted mb-3">Se envía cuando la postulación cambia a esta etapa. Placeholders disponibles: <code>{nombre}</code>, <code>{vacante}</code>, <code>{empresa}</code>, <code>{seleccionador_nombre}</code>, <code>{seleccionador_correo}</code>, <code>{etapa}</code>, <code>{link_estado}</code>. Podés deshabilitar el envío de cualquier etapa sin afectar el resto del proceso.</p>

			<?php foreach($datos['plantillas'] as $plantilla): if($plantilla->tipo !== 'cambio_estado') continue; ?>
			<div class="mb-4 pb-4 plantilla-etapa-separador">
				<div class="d-flex align-items-center justify-content-between mb-2">
					<strong><?= htmlspecialchars($plantilla->estado_nombre) ?></strong>
					<?php if($plantilla->activo): ?>
						<a href="<?= RUTA_URL ?>plantillasCorreo/desactivar/<?= $plantilla->id ?>" class="btn btn-sm btn-outline-danger">Deshabilitar envío</a>
					<?php else: ?>
						<a href="<?= RUTA_URL ?>plantillasCorreo/activar/<?= $plantilla->id ?>" class="btn btn-sm btn-outline-success">Habilitar envío</a>
					<?php endif; ?>
				</div>
				<?php if(!$plantilla->activo): ?><span class="badge badge-secondary mb-2">Envío deshabilitado</span><?php endif; ?>
				<form method="post" action="<?= RUTA_URL ?>plantillasCorreo/actualizar/<?= $plantilla->id ?>">
					<div class="form-group">
						<label class="small text-muted mb-1">Asunto</label>
						<input type="text" class="form-control mb-2" name="asunto" value="<?= htmlspecialchars($plantilla->asunto) ?>" required>
					</div>
					<div class="form-group">
						<label class="small text-muted mb-1">Cuerpo</label>
						<textarea class="form-control rich-editor" name="cuerpo_html"><?= $plantilla->cuerpo_html ?></textarea>
					</div>
					<div class="form-group form-check mb-2">
						<input type="checkbox" class="form-check-input" id="cc_<?= $plantilla->id ?>" name="cc_seleccionador" value="1" <?= $plantilla->cc_seleccionador ? 'checked' : '' ?>>
						<label class="form-check-label small" for="cc_<?= $plantilla->id ?>">Copiar (CC) al Seleccionador responsable de la vacante</label>
					</div>
					<button type="submit" class="btn btn-outline-secondary btn-sm mt-2">Guardar</button>
					<a href="<?= RUTA_URL ?>plantillasCorreo/probar/<?= $plantilla->id ?>" class="btn btn-outline-primary btn-sm mt-2">Enviar prueba</a>
				</form>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</div>
