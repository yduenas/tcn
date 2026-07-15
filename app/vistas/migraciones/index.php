<div class="app-main" style="max-width:760px;">
	<h4 class="mb-4">Migraciones</h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>
	<?php if(!empty($datos['mensaje'])): ?>
		<div class="alert alert-success"><?= htmlspecialchars($datos['mensaje']) ?></div>
	<?php endif; ?>

	<div class="card">
		<div class="card-body">
			<p class="small text-muted mb-3">Archivos de <code>app/migraciones/</code>. Las pendientes no están registradas todavía en <code>migracionDB.json</code> -- ejecutarlas corre el SQL completo del archivo contra la base de datos real.</p>
			<table class="table table-sm">
				<thead>
					<tr>
						<th>Archivo</th>
						<th>Estado</th>
						<th>Fecha</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($datos['migraciones'] as $migracion): ?>
						<tr>
							<td><?= htmlspecialchars($migracion->nombre_archivo) ?></td>
							<td>
								<?php if($migracion->ejecutada): ?>
									<span class="badge badge-success">Ejecutada</span>
								<?php else: ?>
									<span class="badge badge-warning">Pendiente</span>
								<?php endif; ?>
							</td>
							<td><?= $migracion->fecha ? htmlspecialchars($migracion->fecha) : '—' ?></td>
							<td class="text-right">
								<a href="<?= RUTA_URL ?>migraciones/ver/<?= urlencode($migracion->nombre_archivo) ?>" class="btn btn-sm btn-outline-secondary">Ver SQL</a>
								<?php if(!$migracion->ejecutada): ?>
									<a href="<?= RUTA_URL ?>migraciones/ejecutar/<?= urlencode($migracion->nombre_archivo) ?>" class="btn btn-sm btn-outline-primary confirmar-swal"
									   data-mensaje="¿Ejecutar <?= htmlspecialchars($migracion->nombre_archivo) ?> contra la base de datos real? Esta acción no se puede deshacer.">Ejecutar</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
