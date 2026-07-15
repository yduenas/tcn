<?php $k = $datos['kpis']; ?>
<div class="app-main">
	<span class="eyebrow">Panel de empresa</span>
	<h4 class="mb-4"><?= htmlspecialchars($_SESSION['empresa_nombre'] ?? 'Mi empresa') ?></h4>

	<div class="row mb-4">
		<div class="col-md-4 col-6 mb-3">
			<div class="stat-card">
				<span class="stat-num"><?= $k->vacantes_publicadas ?></span>
				<span class="stat-label">Vacantes publicadas</span>
			</div>
		</div>
		<div class="col-md-4 col-6 mb-3">
			<div class="stat-card">
				<span class="stat-num"><?= $k->postulantes_totales ?></span>
				<span class="stat-label">Postulantes totales</span>
			</div>
		</div>
		<div class="col-md-4 col-6 mb-3">
			<div class="stat-card">
				<span class="stat-num"><?= $k->vacantes_cerradas ?></span>
				<span class="stat-label">Vacantes cerradas</span>
			</div>
		</div>
	</div>

	<h6 class="text-uppercase text-muted mb-3">Terna final</h6>
	<?php if(empty($datos['grupos'])): ?>
		<p class="text-muted">Todavía no tienes candidatos en Terna final o Contratados.</p>
	<?php else: ?>
		<?php foreach($datos['grupos'] as $grupo): ?>
		<div class="card mb-3">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="mb-0"><?= htmlspecialchars($grupo->vacante_titulo) ?></h6>
				</div>
				<ul class="list-unstyled mb-0">
					<?php foreach($grupo->candidatos as $candidato): ?>
					<li class="d-flex justify-content-between align-items-center py-1" style="border-top:1px solid rgba(15,33,56,0.06);">
						<span>
							<?= htmlspecialchars($candidato->nombres.' '.$candidato->apellidos) ?>
							<span class="badge badge-<?= $candidato->estado_codigo === 'contratado' ? 'success' : 'info' ?> ml-1"><?= htmlspecialchars($candidato->estado_nombre) ?></span>
						</span>
						<span>
							<a href="<?= RUTA_URL ?>postulaciones/perfil/<?= $candidato->postulacion_id ?>" class="btn btn-sm btn-outline-secondary">Ver perfil y CV</a>
							<a href="<?= RUTA_URL ?>ternas/reporte/<?= $candidato->postulacion_id ?>" class="btn btn-sm btn-outline-primary" target="_blank">Ver reporte PDF</a>
						</span>
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<h6 class="text-uppercase text-muted mt-4 mb-3">Avance por vacante</h6>
	<?php if(empty($datos['vacantes'])): ?>
		<p class="text-muted">No tienes vacantes activas en este momento.</p>
	<?php else: ?>
		<?php foreach($datos['vacantes'] as $vacante): ?>
		<div class="card mb-3">
			<div class="card-body">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<h6 class="mb-0"><?= htmlspecialchars($vacante->titulo) ?></h6>
					<span class="text-muted"><?= $vacante->total_postulantes ?> postulante<?= $vacante->total_postulantes == 1 ? '' : 's' ?></span>
				</div>
				<div>
					<?php foreach($vacante->avance as $etapa): ?>
						<span class="badge badge-<?= $etapa->cantidad > 0 ? 'info' : 'light' ?> mr-1">
							<?= htmlspecialchars($etapa->estado_nombre) ?>: <?= $etapa->cantidad ?>
						</span>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	<?php endif; ?>

	<h6 class="text-uppercase text-muted mt-4 mb-3">Histórico de vacantes cerradas</h6>
	<?php if(empty($datos['cerradas'])): ?>
		<p class="text-muted">Todavía no tienes vacantes cerradas.</p>
	<?php else: ?>
		<table class="table table-striped">
			<thead><tr><th>Título</th><th>Fecha de cierre</th><th>Postulantes</th></tr></thead>
			<tbody>
				<?php foreach($datos['cerradas'] as $v): ?>
				<tr>
					<td><?= htmlspecialchars($v->titulo) ?></td>
					<td><?= htmlspecialchars(fechaLocal($v->fecha_cierre)) ?></td>
					<td><?= $v->total_postulantes ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
