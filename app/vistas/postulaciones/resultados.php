<div class="app-main" style="max-width:720px;">
	<a href="javascript:history.back()" class="btn btn-link px-0">&larr; Volver</a>
	<div class="d-flex justify-content-between align-items-center mb-4">
		<div>
			<h4 class="mb-0">Resultados de evaluaciones</h4>
			<p class="text-muted mb-0">
				<?= htmlspecialchars($datos['postulacion']->nombres.' '.$datos['postulacion']->apellidos) ?>
				&mdash; <?= htmlspecialchars($datos['postulacion']->vacante_titulo) ?>
			</p>
		</div>
		<div class="text-nowrap">
			<?php if(tienePermiso('agendar_entrevista')): ?>
				<?php if($datos['entrevista']): ?>
					<a href="<?= RUTA_URL ?>entrevistas/detalle/<?= $datos['entrevista']->id ?>" class="btn btn-outline-secondary btn-sm">Ver / calificar entrevista</a>
				<?php else: ?>
					<a href="<?= RUTA_URL ?>entrevistas/agendar/<?= $datos['postulacion_id'] ?>" class="btn btn-outline-secondary btn-sm">Agendar entrevista</a>
				<?php endif; ?>
			<?php endif; ?>
			<?php if(tienePermiso('exportar_pdf')): ?>
				<a href="<?= RUTA_URL ?>ternas/reporte/<?= $datos['postulacion_id'] ?>" class="btn btn-outline-primary btn-sm" target="_blank">Descargar PDF</a>
			<?php endif; ?>
		</div>
	</div>

	<?php if(tienePermiso('agendar_entrevista') && !empty($datos['competencias'])): ?>
	<div class="card mb-3">
		<div class="card-body">
			<h6 class="card-title d-flex justify-content-between align-items-center">
				Competencias de entrevista
				<?php if($datos['entrevista']): ?>
					<a href="<?= RUTA_URL ?>entrevistas/detalle/<?= $datos['entrevista']->id ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
				<?php else: ?>
					<a href="<?= RUTA_URL ?>entrevistas/agendar/<?= $datos['postulacion_id'] ?>" class="btn btn-sm btn-outline-secondary">Agendar</a>
				<?php endif; ?>
			</h6>
			<ul class="list-unstyled mb-0">
				<?php foreach($datos['competencias'] as $c): ?>
				<?php $calificada = isset($datos['calificaciones'][$c->id]); ?>
				<li class="py-1 d-flex align-items-center" style="border-top:1px solid rgba(15,33,56,0.06);">
					<span class="mr-2" style="color:<?= $calificada ? '#0F7A63' : '#5B6B7C' ?>;"><?= $calificada ? '✓' : '—' ?></span>
					<span class="flex-grow-1"><?= htmlspecialchars($c->nombre) ?></span>
					<?php if(in_array($c->id, $datos['obligatorias'])): ?><span class="badge badge-warning mr-2">Obligatoria</span><?php endif; ?>
					<?php if($calificada): ?><span class="text-muted" style="font-size:.85rem;"><?= (int) $datos['calificaciones'][$c->id]->calificacion ?>/5</span><?php endif; ?>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php foreach($datos['evaluaciones'] as $e): ?>
	<div class="card mb-3">
		<div class="card-body">
			<h6 class="card-title d-flex justify-content-between">
				<?= htmlspecialchars($e->evaluacion_nombre) ?>
				<span class="badge badge-<?= in_array($e->estado, ['completada', 'reutilizada']) ? 'success' : 'secondary' ?>">
					<?= htmlspecialchars($e->estado) ?>
				</span>
			</h6>

			<?php if(!$e->resultado_json): ?>
				<p class="text-muted mb-0">Todavía no completa esta evaluación.</p>
			<?php else: ?>
				<?php $r = json_decode($e->resultado_json, true); ?>

				<?php if($r['tipo'] === 'opcion_unica'): ?>
					<p class="mb-0"><?= $r['correctas'] ?>/<?= $r['total'] ?> correctas —
						<strong><?= $r['porcentaje'] ?>% (<?= htmlspecialchars($r['nivel']) ?>)</strong></p>

				<?php elseif($r['tipo'] === 'disc'): ?>
					<table class="table table-sm mb-1">
						<thead><tr><th>Dimensión</th><th>%</th></tr></thead>
						<tbody>
							<?php foreach($r['dimensiones'] as $letra => $d): ?>
							<tr class="<?= $letra === $r['perfil_dominante'] ? 'font-weight-bold' : '' ?>">
								<td><?= htmlspecialchars($letra) ?></td>
								<td><?= $d['porcentaje'] ?>%</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<p class="mb-0 text-muted">Perfil dominante: <strong><?= htmlspecialchars($r['perfil_dominante']) ?></strong></p>

				<?php elseif($r['tipo'] === 'sjt'): ?>
					<table class="table table-sm mb-1">
						<thead><tr><th>Competencia</th><th>%</th><th>Nivel</th></tr></thead>
						<tbody>
							<?php foreach($r['competencias'] as $nombre => $c): ?>
							<tr>
								<td><?= htmlspecialchars($nombre) ?></td>
								<td><?= $c['porcentaje'] ?>%</td>
								<td><?= htmlspecialchars($c['nivel']) ?></td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
					<p class="mb-0"><strong>General: <?= $r['porcentaje_general'] ?>% (<?= htmlspecialchars($r['nivel_general']) ?>)</strong></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>
