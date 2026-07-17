<?php
	$c = $datos['candidato'];
	$p = $datos['postulacion'];
?>
<div class="app-main" style="max-width:760px;">
	<?php if($_SESSION['perfil_nombre'] === 'Empresa' && !tienePermiso('ver_postulantes')): ?>
		<?php // Empresa sin autoservicio (solo ve terna final/contratado, sin acceso al pipeline) -- 2026-07-17 ?>
		<a href="<?= RUTA_URL ?>dashboard/index" class="btn btn-link px-0">&larr; Volver al dashboard</a>
	<?php else: ?>
		<a href="<?= RUTA_URL ?>postulaciones/vacante/<?= $p->vacante_id ?>" class="btn btn-link px-0">&larr; Volver al pipeline</a>
	<?php endif; ?>

	<div class="d-flex justify-content-between align-items-start mb-1 mt-2">
		<h4 class="mb-0"><?= htmlspecialchars($c->nombres.' '.$c->apellidos) ?></h4>
		<?php if($datos['cv']): ?>
			<a href="<?= RUTA_URL.htmlspecialchars($datos['cv']->archivo_path) ?>" target="_blank" class="btn btn-primary btn-sm">Descargar CV</a>
		<?php endif; ?>
	</div>
	<p class="text-muted">Postuló a <?= htmlspecialchars($p->vacante_titulo) ?> · <?= htmlspecialchars($p->empresa_nombre) ?> · <?= htmlspecialchars(fechaLocal($p->fecha_postulacion)) ?></p>

	<div class="card mb-3">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3" style="font-size:.78rem;">Datos personales</h6>
			<div class="row">
				<div class="col-md-6"><b>Correo:</b> <?= htmlspecialchars($c->email) ?></div>
				<div class="col-md-6"><b>Teléfono:</b> <?= htmlspecialchars($c->telefono ?: '-') ?></div>
				<div class="col-md-6 mt-2"><b>DNI:</b> <?= htmlspecialchars($c->dni ?: '-') ?></div>
				<div class="col-md-6 mt-2"><b>Pretensión salarial:</b> <?= htmlspecialchars(formatearSoles($c->pretension_salarial) ?? '-') ?></div>
				<div class="col-md-6 mt-2"><b>Disponibilidad:</b> <?= htmlspecialchars($c->disponibilidad ?: '-') ?></div>
				<div class="col-md-6 mt-2"><b>Perfil registrado desde:</b> <?= htmlspecialchars(fechaLocal($c->fecha_registro)) ?></div>
			</div>
		</div>
	</div>

	<?php if(!empty($datos['habilidades'])): ?>
	<div class="card mb-3">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-2" style="font-size:.78rem;">Habilidades</h6>
			<?php foreach($datos['habilidades'] as $h): ?>
				<span class="badge badge-info mr-1"><?= htmlspecialchars($h->nombre) ?></span>
			<?php endforeach; ?>
		</div>
	</div>
	<?php endif; ?>

	<div class="card mb-3">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-2" style="font-size:.78rem;">Experiencia laboral</h6>
			<?php if(empty($datos['experiencia'])): ?>
				<p class="text-muted mb-0">Sin experiencia registrada.</p>
			<?php else: ?>
				<?php foreach($datos['experiencia'] as $exp): ?>
				<div class="mb-2 pb-2" style="border-bottom:1px solid rgba(15,33,56,0.06);">
					<div class="d-flex justify-content-between">
						<b><?= htmlspecialchars($exp->cargo) ?> · <?= htmlspecialchars($exp->empresa) ?></b>
						<span class="text-muted" style="font-size:.82rem;">
							<?= htmlspecialchars($exp->fecha_inicio ?: '') ?> — <?= $exp->actualidad ? 'Actualidad' : htmlspecialchars($exp->fecha_fin ?: '') ?>
						</span>
					</div>
					<?php if($exp->descripcion): ?><p class="mb-0 mt-1" style="font-size:.88rem;"><?= nl2br(htmlspecialchars($exp->descripcion)) ?></p><?php endif; ?>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>

	<div class="card mb-3">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-2" style="font-size:.78rem;">Educación</h6>
			<?php if(empty($datos['educacion'])): ?>
				<p class="text-muted mb-0">Sin educación registrada.</p>
			<?php else: ?>
				<?php foreach($datos['educacion'] as $edu): ?>
				<div class="mb-2 pb-2" style="border-bottom:1px solid rgba(15,33,56,0.06);">
					<div class="d-flex justify-content-between">
						<b><?= htmlspecialchars($edu->grado ?: 'Estudios') ?> · <?= htmlspecialchars($edu->institucion) ?></b>
						<span class="text-muted" style="font-size:.82rem;"><?= htmlspecialchars($edu->fecha_inicio ?: '') ?> — <?= htmlspecialchars($edu->fecha_fin ?: '') ?></span>
					</div>
					<?php if($edu->campo_estudio): ?><p class="mb-0 mt-1" style="font-size:.88rem;"><?= htmlspecialchars($edu->campo_estudio) ?></p><?php endif; ?>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>

	<?php if(!$datos['cv']): ?>
		<p class="text-muted">Este candidato no adjuntó CV.</p>
	<?php endif; ?>

	<div class="mt-3">
		<?php if(tienePermiso('ver_datos_sensibles_evaluacion')): ?>
			<a href="<?= RUTA_URL ?>postulaciones/resultados/<?= $p->id ?>" class="btn btn-sm btn-outline-primary">Ver resultados de evaluación</a>
		<?php endif; ?>
	</div>
</div>
