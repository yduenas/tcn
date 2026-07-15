<div class="app-main" style="max-width:560px;">
	<h4 class="mb-4"><?= $datos['empresa'] ? 'Editar empresa' : 'Nueva empresa' ?></h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<form method="post" enctype="multipart/form-data"
	      action="<?= RUTA_URL.($datos['empresa'] ? 'empresas/actualizar/'.$datos['empresa']->id : 'empresas/guardar') ?>">
		<div class="form-group">
			<label for="nombre">Nombre</label>
			<input type="text" class="form-control" id="nombre" name="nombre" required
			       value="<?= htmlspecialchars($datos['empresa']->nombre ?? '') ?>">
		</div>
		<div class="form-group">
			<label for="ruc">RUC</label>
			<input type="text" class="form-control" id="ruc" name="ruc"
			       value="<?= htmlspecialchars($datos['empresa']->ruc ?? '') ?>">
		</div>

		<hr>
		<h6 class="text-uppercase text-muted mb-3">Datos de contacto (opcional)</h6>

		<div class="form-group">
			<label for="contacto_nombre">Nombre de contacto</label>
			<input type="text" class="form-control" id="contacto_nombre" name="contacto_nombre"
			       value="<?= htmlspecialchars($datos['empresa']->contacto_nombre ?? '') ?>">
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="contacto_telefono">Teléfono</label>
				<input type="text" class="form-control" id="contacto_telefono" name="contacto_telefono"
				       value="<?= htmlspecialchars($datos['empresa']->contacto_telefono ?? '') ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="contacto_email">Correo</label>
				<input type="email" class="form-control" id="contacto_email" name="contacto_email"
				       value="<?= htmlspecialchars($datos['empresa']->contacto_email ?? '') ?>">
			</div>
		</div>
		<div class="form-group">
			<label for="direccion">Dirección</label>
			<input type="text" class="form-control" id="direccion" name="direccion"
			       value="<?= htmlspecialchars($datos['empresa']->direccion ?? '') ?>">
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label for="sitio_web">Página web</label>
				<input type="text" class="form-control" id="sitio_web" name="sitio_web" placeholder="https://"
				       value="<?= htmlspecialchars($datos['empresa']->sitio_web ?? '') ?>">
			</div>
			<div class="form-group col-md-6">
				<label for="linkedin">LinkedIn</label>
				<input type="text" class="form-control" id="linkedin" name="linkedin" placeholder="https://linkedin.com/company/..."
				       value="<?= htmlspecialchars($datos['empresa']->linkedin ?? '') ?>">
			</div>
		</div>

		<hr>
		<div class="form-group">
			<label for="logo">Logo (JPG o PNG, máximo 1 MB)</label>
			<?php if($datos['empresa']): ?>
				<div class="mb-2">
					<img src="<?= RUTA_URL.htmlspecialchars($datos['empresa']->logo_path) ?>" alt="Logo actual" style="height:48px;">
				</div>
			<?php endif; ?>
			<input type="file" class="form-control-file" id="logo" name="logo" accept=".jpg,.jpeg,.png" <?= $datos['empresa'] ? '' : 'required' ?>>
			<?php if($datos['empresa']): ?>
				<small class="form-text text-muted">Déjalo vacío para conservar el logo actual.</small>
			<?php endif; ?>
		</div>
		<button type="submit" class="btn btn-primary">Guardar</button>
		<a href="<?= RUTA_URL ?>empresas/index" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>
