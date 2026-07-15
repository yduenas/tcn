<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Empresas</h4>
		<?php if(tienePermiso('crear_empresa')): ?>
			<a href="<?= RUTA_URL ?>empresas/nuevo" class="btn btn-primary btn-sm">Nueva empresa</a>
		<?php endif; ?>
	</div>

	<table class="table table-striped table-bordered align-middle">
		<thead>
			<tr>
				<th>Logo</th>
				<th>Nombre</th>
				<th>RUC</th>
				<th>Estado</th>
				<th>Fecha de creación</th>
				<th>Fecha de baja</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['empresas'] as $empresa): ?>
			<tr>
				<td><img src="<?= RUTA_URL.htmlspecialchars($empresa->logo_path) ?>" alt="Logo" style="height:36px;"></td>
				<td>
					<a href="javascript:void(0)" data-toggle="modal" data-target="#empresaModal<?= $empresa->id ?>">
						<?= htmlspecialchars($empresa->nombre) ?>
					</a>
				</td>
				<td><?= htmlspecialchars($empresa->ruc ?? '-') ?></td>
				<td>
					<span class="badge badge-<?= $empresa->estado === 'activa' ? 'success' : 'secondary' ?>">
						<?= htmlspecialchars($empresa->estado) ?>
					</span>
				</td>
				<td><?= htmlspecialchars(fechaLocal($empresa->fecha_creacion)) ?></td>
				<td><?= htmlspecialchars(fechaLocal($empresa->fecha_baja) ?? '-') ?></td>
				<td>
					<?php if(tienePermiso('editar_empresa')): ?>
						<a href="<?= RUTA_URL ?>empresas/editar/<?= $empresa->id ?>" class="btn btn-sm btn-outline-primary">Editar</a>
					<?php endif; ?>
					<?php if($empresa->estado === 'activa' && tienePermiso('dar_baja_empresa')): ?>
						<a href="<?= RUTA_URL ?>empresas/baja/<?= $empresa->id ?>" class="btn btn-sm btn-outline-danger"
						   onclick="return confirm('¿Dar de baja esta empresa? Esta acción es una baja lógica, no se borra el histórico.');">Dar de baja</a>
					<?php endif; ?>
					<?php if($empresa->estado === 'inactiva' && tienePermiso('dar_baja_empresa')): ?>
						<a href="<?= RUTA_URL ?>empresas/reactivar/<?= $empresa->id ?>" class="btn btn-sm btn-outline-success"
						   onclick="return confirm('¿Reactivar esta empresa? Sus vacantes seguirán despublicadas hasta que las republiques manualmente.');">Reactivar</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<?php foreach($datos['empresas'] as $empresa): ?>
	<div class="modal fade" id="empresaModal<?= $empresa->id ?>" tabindex="-1" role="dialog">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title"><?= htmlspecialchars($empresa->nombre) ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Cerrar"><span aria-hidden="true">&times;</span></button>
				</div>
				<div class="modal-body">
					<div class="text-center mb-3">
						<img src="<?= RUTA_URL.htmlspecialchars($empresa->logo_path) ?>" alt="Logo" style="height:64px;">
					</div>
					<table class="table table-sm table-borderless mb-0">
						<tr><th class="text-muted" style="width:40%;">RUC</th><td><?= htmlspecialchars($empresa->ruc ?? '-') ?></td></tr>
						<tr><th class="text-muted">Estado</th><td>
							<span class="badge badge-<?= $empresa->estado === 'activa' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($empresa->estado) ?></span>
						</td></tr>
						<tr><th class="text-muted">Fecha de creación</th><td><?= htmlspecialchars(fechaLocal($empresa->fecha_creacion)) ?></td></tr>
						<tr><th class="text-muted">Fecha de baja</th><td><?= htmlspecialchars(fechaLocal($empresa->fecha_baja) ?? '-') ?></td></tr>
						<tr><td colspan="2"><hr class="my-1"></td></tr>
						<tr><th class="text-muted">Contacto</th><td><?= htmlspecialchars($empresa->contacto_nombre ?? '-') ?></td></tr>
						<tr><th class="text-muted">Teléfono</th><td><?= htmlspecialchars($empresa->contacto_telefono ?? '-') ?></td></tr>
						<tr><th class="text-muted">Correo</th><td><?= htmlspecialchars($empresa->contacto_email ?? '-') ?></td></tr>
						<tr><th class="text-muted">Dirección</th><td><?= htmlspecialchars($empresa->direccion ?? '-') ?></td></tr>
						<tr><th class="text-muted">Página web</th><td>
							<?php if(!empty($empresa->sitio_web)): ?>
								<a href="<?= htmlspecialchars($empresa->sitio_web) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($empresa->sitio_web) ?></a>
							<?php else: ?>-<?php endif; ?>
						</td></tr>
						<tr><th class="text-muted">LinkedIn</th><td>
							<?php if(!empty($empresa->linkedin)): ?>
								<a href="<?= htmlspecialchars($empresa->linkedin) ?>" target="_blank" rel="noopener"><?= htmlspecialchars($empresa->linkedin) ?></a>
							<?php else: ?>-<?php endif; ?>
						</td></tr>
					</table>
				</div>
				<div class="modal-footer">
					<?php if(tienePermiso('editar_empresa')): ?>
						<a href="<?= RUTA_URL ?>empresas/editar/<?= $empresa->id ?>" class="btn btn-sm btn-outline-primary">Editar</a>
					<?php endif; ?>
					<button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Cerrar</button>
				</div>
			</div>
		</div>
	</div>
	<?php endforeach; ?>
</div>
