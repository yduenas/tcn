<?php
	$badgesEstado = [
		'recibida' => 'secondary', 'en_revision' => 'secondary',
		'preseleccionado' => 'info', 'entrevista' => 'info',
		'terna_final' => 'primary', 'pre_contratado' => 'primary', 'contratado' => 'success',
		'descartado' => 'dark', 'desertado' => 'warning'
	];
?>
<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Postulantes</h4>
	</div>

	<?php if(empty($datos['filas'])): ?>
		<p class="text-muted">Todavía no hay candidatos registrados.</p>
	<?php else: ?>
	<table class="table table-striped table-bordered align-middle data-table" id="tablaPostulantesGlobal" data-export-name="Postulantes">
		<thead>
			<tr>
				<th>Candidato</th>
				<th>DNI</th>
				<th>Correo</th>
				<th>Teléfono</th>
				<th>Empresa</th>
				<th>Puesto</th>
				<th>Estado</th>
				<th>Fecha del último estado</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['filas'] as $fila): ?>
			<tr>
				<td><?= htmlspecialchars($fila->nombres.' '.$fila->apellidos) ?></td>
				<td><?= htmlspecialchars($fila->dni ?: '-') ?></td>
				<td><?= htmlspecialchars($fila->email) ?></td>
				<td><?= htmlspecialchars($fila->telefono ?: '-') ?></td>
				<td><?= htmlspecialchars($fila->empresa_nombre ?? '-') ?></td>
				<td><?= htmlspecialchars($fila->vacante_titulo ?? '-') ?></td>
				<td>
					<?php if($fila->estado_codigo): ?>
						<span class="badge badge-<?= $badgesEstado[$fila->estado_codigo] ?? 'secondary' ?>"><?= htmlspecialchars($fila->estado_nombre) ?></span>
					<?php else: ?>
						<span class="text-muted">Sin postulaciones</span>
					<?php endif; ?>
				</td>
				<td><?= htmlspecialchars(fechaLocal($fila->fecha_ultimo_cambio) ?? '-') ?></td>
				<td class="text-nowrap">
					<?php if($fila->postulacion_id): ?>
						<a href="<?= RUTA_URL ?>postulaciones/perfil/<?= $fila->postulacion_id ?>" class="btn btn-sm btn-outline-secondary">Ver perfil</a>
					<?php else: ?>
						<span class="text-muted">-</span>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>
