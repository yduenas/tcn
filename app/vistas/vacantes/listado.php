<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Vacantes</h4>
		<?php if(tienePermiso('crear_vacante')): ?>
			<a href="<?= RUTA_URL ?>vacantes/nuevo" class="btn btn-primary btn-sm">Nueva vacante</a>
		<?php endif; ?>
	</div>

	<table class="table table-striped table-bordered align-middle">
		<thead>
			<tr>
				<th>Código</th>
				<th>Título</th>
				<th>Empresa</th>
				<th>Cargo</th>
				<th>Modalidad</th>
				<th>Salario</th>
				<th>Marcas</th>
				<th>Estado</th>
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
				<td><?= htmlspecialchars($vacante->cargo_nombre) ?></td>
				<td><?= htmlspecialchars($vacante->modalidad_nombre) ?></td>
				<td>
					<?php if($vacante->ocultar_salario): ?>
						<span class="text-muted">Oculto al público</span>
					<?php elseif($vacante->salario_min || $vacante->salario_max): ?>
						<?= htmlspecialchars($vacante->salario_min ?? '?') ?> - <?= htmlspecialchars($vacante->salario_max ?? '?') ?>
					<?php else: ?>
						<span class="text-muted">-</span>
					<?php endif; ?>
				</td>
				<td>
					<?php if($vacante->es_anonima): ?><span class="badge badge-info">Anónima</span><?php endif; ?>
				</td>
				<td><span class="badge badge-<?= $badgeEstado ?>"><?= htmlspecialchars($vacante->estado) ?></span></td>
				<td class="text-nowrap">
					<a href="<?= RUTA_URL ?>vacantes/ver/<?= $vacante->id ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
					<?php if(tienePermiso('ver_postulantes')): ?>
						<a href="<?= RUTA_URL ?>postulaciones/vacante/<?= $vacante->id ?>" class="btn btn-sm btn-outline-info">Postulantes</a>
					<?php endif; ?>
					<?php if(tienePermiso('editar_vacante')): ?>
						<a href="<?= RUTA_URL ?>vacantes/editar/<?= $vacante->id ?>" class="btn btn-sm btn-outline-primary">Editar</a>
					<?php endif; ?>
					<?php if(tienePermiso('publicar_vacante') && $vacante->estado !== 'cerrada'): ?>
						<?php if($vacante->estado === 'borrador' || $vacante->estado === 'despublicada'): ?>
							<a href="<?= RUTA_URL ?>vacantes/publicar/<?= $vacante->id ?>" class="btn btn-sm btn-outline-success">Publicar</a>
						<?php elseif($vacante->estado === 'publicada'): ?>
							<a href="<?= RUTA_URL ?>vacantes/despublicar/<?= $vacante->id ?>" class="btn btn-sm btn-outline-warning">Despublicar</a>
						<?php endif; ?>
						<a href="<?= RUTA_URL ?>vacantes/cerrar/<?= $vacante->id ?>" class="btn btn-sm btn-outline-dark confirmar-swal"
						   data-mensaje="Esta vacante pasará al histórico.">Cerrar</a>
					<?php endif; ?>
					<?php if(tienePermiso('publicar_vacante') && $vacante->estado === 'cerrada'): ?>
						<a href="<?= RUTA_URL ?>vacantes/reabrir/<?= $vacante->id ?>" class="btn btn-sm btn-outline-secondary"
						   onclick="return confirm('¿Reabrir esta vacante? Quedará despublicada hasta que la publiques de nuevo.');">Reabrir</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
