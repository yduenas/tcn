<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Perfiles y permisos</h4>
		<a href="<?= RUTA_URL ?>perfiles/nuevo" class="btn btn-primary btn-sm">Nuevo perfil</a>
	</div>

	<table class="table table-striped table-bordered align-middle">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Descripción</th>
				<th>Permisos</th>
				<th>Usuarios</th>
				<th>Origen</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['perfiles'] as $perfil): ?>
			<tr>
				<td><?= htmlspecialchars($perfil->nombre) ?></td>
				<td><?= htmlspecialchars($perfil->descripcion ?: '-') ?></td>
				<td><span class="badge badge-info"><?= $perfil->total_permisos ?></span></td>
				<td><?= $perfil->total_usuarios ?></td>
				<td>
					<span class="badge badge-<?= $perfil->es_predefinido ? 'dark' : 'secondary' ?>">
						<?= $perfil->es_predefinido ? 'De fábrica' : 'Personalizado' ?>
					</span>
				</td>
				<td>
					<a href="<?= RUTA_URL ?>perfiles/editar/<?= $perfil->id ?>" class="btn btn-sm btn-outline-primary">Editar permisos</a>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
