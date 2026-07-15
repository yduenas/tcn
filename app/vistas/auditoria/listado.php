<div class="app-main">
	<h4 class="mb-4">Auditoría de acciones sensibles</h4>

	<?php if(empty($datos['registros'])): ?>
		<p class="text-muted">Todavía no hay acciones registradas.</p>
	<?php else: ?>
	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>Fecha</th>
				<th>Usuario</th>
				<th>Acción</th>
				<th>Entidad</th>
				<th>Detalle</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['registros'] as $r): ?>
			<tr>
				<td><?= htmlspecialchars(fechaLocal($r->fecha)) ?></td>
				<td><?= $r->nombres ? htmlspecialchars($r->nombres.' '.$r->apellidos) : '-' ?></td>
				<td><span class="badge badge-info"><?= htmlspecialchars($r->accion) ?></span></td>
				<td><?= htmlspecialchars($r->entidad) ?> #<?= $r->entidad_id ?></td>
				<td><?= htmlspecialchars($r->detalle) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>
