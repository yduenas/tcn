<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Reporte de publicaciones</h4>
	</div>

	<?php if(empty($datos['vacantes'])): ?>
		<p class="text-muted">Todavía no hay vacantes registradas.</p>
	<?php else: ?>
	<div class="table-responsive">
	<table class="table table-striped table-bordered align-middle data-table" id="tablaReportePublicaciones" data-export-name="Reporte de publicaciones">
		<thead>
			<tr>
				<th>Código</th>
				<th>Título</th>
				<th>Empresa</th>
				<th>Seleccionador</th>
				<th>Estado</th>
				<th>Publicada el</th>
				<?php foreach($datos['estados'] as $estado): ?>
					<th><?= htmlspecialchars($estado->nombre) ?></th>
				<?php endforeach; ?>
				<th>Total postulantes</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['vacantes'] as $vacante): ?>
			<?php
				$badgeEstado = [
					'borrador' => 'secondary', 'publicada' => 'success',
					'despublicada' => 'warning', 'cerrada' => 'dark'
				][$vacante->estado] ?? 'secondary';
			?>
			<tr>
				<td><span class="mono text-muted">VAC-<?= str_pad($vacante->id, 4, '0', STR_PAD_LEFT) ?></span></td>
				<td><?= htmlspecialchars($vacante->titulo) ?></td>
				<td><?= htmlspecialchars($vacante->empresa_nombre) ?></td>
				<td><?= htmlspecialchars($vacante->seleccionador_nombres.' '.$vacante->seleccionador_apellidos) ?></td>
				<td><span class="badge badge-<?= $badgeEstado ?>"><?= htmlspecialchars($vacante->estado) ?></span></td>
				<td><?= htmlspecialchars(fechaLocal($vacante->fecha_publicacion) ?? '-') ?></td>
				<?php foreach($datos['estados'] as $estado): ?>
					<td class="text-center"><?= $vacante->conteo_por_estado[$estado->codigo] ?></td>
				<?php endforeach; ?>
				<td class="text-center"><strong><?= $vacante->conteo_total ?></strong></td>
				<td class="text-nowrap">
					<?php if(tienePermiso('ver_postulantes')): ?>
						<a href="<?= RUTA_URL ?>postulaciones/vacante/<?= $vacante->id ?>" class="btn btn-sm btn-outline-info">Ver postulantes</a>
					<?php else: ?>
						<span class="text-muted">-</span>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	</div>
	<?php endif; ?>
</div>
