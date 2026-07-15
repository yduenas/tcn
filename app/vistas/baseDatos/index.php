<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Base de datos</h4>
		<a href="<?= RUTA_URL ?>baseDatos/consola" class="btn btn-primary btn-sm">Consola SQL</a>
	</div>

	<div class="alert alert-warning">
		Herramienta de mantenimiento para revisar y modificar la base de datos directamente, útil cuando el sitio
		está en un hosting (cPanel) donde no tienes un cliente SQLite externo a mano. Úsala con cuidado: los cambios
		desde la consola SQL se aplican de inmediato y no se pueden deshacer.
	</div>

	<table class="table table-striped table-bordered align-middle">
		<thead>
			<tr>
				<th>Tabla</th>
				<th>Filas</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['tablas'] as $tabla): ?>
			<tr>
				<td><span class="mono"><?= htmlspecialchars($tabla['nombre']) ?></span></td>
				<td><?= (int) $tabla['filas'] ?></td>
				<td><a href="<?= RUTA_URL ?>baseDatos/tabla/<?= urlencode($tabla['nombre']) ?>" class="btn btn-sm btn-outline-primary">Ver</a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
