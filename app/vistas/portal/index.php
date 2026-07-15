<section style="padding:64px 0 20px; background:radial-gradient(circle at 85% 15%, rgba(62,202,181,0.10), transparent 40%), radial-gradient(circle at 10% 90%, rgba(27,76,145,0.06), transparent 45%);">
	<div class="app-main" style="padding-top:0; padding-bottom:0;">
		<span class="eyebrow"><span style="display:inline-block; width:6px; height:6px; border-radius:50%; background:var(--teal); margin-right:6px;"></span>Trabaja con nosotros</span>
		<h1 style="font-size:clamp(1.8rem, 4vw, 2.6rem); color:var(--blue-dark); max-width:680px;">
			Encuentra tu próximo reto profesional en las empresas que confían en Complement.
		</h1>
		<p style="font-size:1.05rem; color:var(--slate); max-width:560px; margin-bottom:0;">
			Publicamos las vacantes de nuestras empresas cliente. Postula en minutos y sigue el estado de tu proceso cuando quieras.
		</p>
	</div>
</section>

<div class="app-main">
	<form method="get" action="<?= RUTA_URL ?>portal/index" class="form-row mb-4 mt-2">
		<div class="col-md-3 mb-2">
			<select class="form-control" name="cargo_categoria_id">
				<option value="">Todos los cargos</option>
				<?php foreach($datos['cargos'] as $cargo): ?>
					<option value="<?= $cargo->id ?>" <?= $datos['filtros']['cargo_categoria_id'] == $cargo->id ? 'selected' : '' ?>>
						<?= htmlspecialchars($cargo->nombre) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-3 mb-2">
			<select class="form-control" name="modalidad_id">
				<option value="">Todas las modalidades</option>
				<?php foreach($datos['modalidades'] as $modalidad): ?>
					<option value="<?= $modalidad->id ?>" <?= $datos['filtros']['modalidad_id'] == $modalidad->id ? 'selected' : '' ?>>
						<?= htmlspecialchars($modalidad->nombre) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-4 mb-2">
			<select class="form-control select2" name="ubicacion" data-placeholder="Todas las ubicaciones" style="width:100%;">
				<option value=""></option>
				<?php foreach($datos['ubicaciones'] as $u): ?>
					<option value="<?= htmlspecialchars($u->ubicacion) ?>" <?= $datos['filtros']['ubicacion'] === $u->ubicacion ? 'selected' : '' ?>>
						<?= htmlspecialchars($u->ubicacion) ?>
					</option>
				<?php endforeach; ?>
			</select>
		</div>
		<div class="col-md-2 mb-2">
			<button type="submit" class="btn btn-primary btn-block">Filtrar</button>
		</div>
	</form>

	<?php if(empty($datos['vacantes'])): ?>
		<p class="text-muted">No hay vacantes publicadas que coincidan con tu búsqueda.</p>
	<?php endif; ?>

	<div class="row">
		<?php foreach($datos['vacantes'] as $vacante): ?>
		<div class="col-md-6 mb-4">
			<div class="card h-100" style="background:var(--white);">
				<div class="card-body">
					<div class="d-flex align-items-center mb-3">
						<?php if(!$vacante->es_anonima): ?>
							<img src="<?= RUTA_URL.htmlspecialchars($vacante->empresa_logo) ?>" alt="" style="height:28px; margin-right:10px;">
							<span class="text-muted" style="font-size:.85rem;"><?= htmlspecialchars($vacante->empresa_nombre) ?></span>
						<?php else: ?>
							<span class="text-muted" style="font-size:.85rem;">Empresa confidencial</span>
						<?php endif; ?>
					</div>
					<h5 class="card-title" style="color:var(--blue-dark);"><?= htmlspecialchars($vacante->titulo) ?></h5>
					<p class="card-text mb-2" style="font-size:.88rem; color:var(--slate);">
						<?= htmlspecialchars($vacante->cargo_nombre) ?> · <?= htmlspecialchars($vacante->modalidad_nombre) ?>
						<?php if($vacante->ubicacion): ?> · <?= htmlspecialchars($vacante->ubicacion) ?><?php endif; ?>
					</p>
					<p class="card-text mb-3">
						<?php if($vacante->ocultar_salario): ?>
							<span class="badge badge-light">A convenir</span>
						<?php elseif($vacante->salario_min || $vacante->salario_max): ?>
							<span class="badge badge-info"><?= htmlspecialchars($vacante->salario_min ?? '?') ?> - <?= htmlspecialchars($vacante->salario_max ?? '?') ?></span>
						<?php endif; ?>
					</p>
					<a href="<?= RUTA_URL ?>portal/vacante/<?= $vacante->id ?>" class="btn btn-primary btn-sm">Ver detalle</a>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
