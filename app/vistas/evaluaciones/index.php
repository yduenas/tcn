<?php
	$todasResueltas = true;
	foreach($datos['pendientes'] as $p){
		if(!in_array($p->estado, ['completada', 'reutilizada'])){ $todasResueltas = false; }
	}
?>
<div class="app-main" style="max-width:640px;">
	<h4 class="mb-1">Tus evaluaciones</h4>
	<p class="text-muted mb-4">Puedes resolverlas en cualquier orden.</p>

	<?php if(!empty($datos['mensaje'])): ?>
		<div class="alert alert-warning"><?= htmlspecialchars($datos['mensaje']) ?></div>
	<?php endif; ?>

	<?php if($todasResueltas): ?>
		<div class="alert alert-success">No tienes evaluaciones pendientes.</div>
	<?php endif; ?>

	<table class="table table-striped table-bordered align-middle">
		<thead>
			<tr><th>Evaluación</th><th>Tiempo límite</th><th>Estado</th><th></th></tr>
		</thead>
		<tbody>
			<?php foreach($datos['pendientes'] as $p): ?>
			<tr>
				<td><?= htmlspecialchars($p->evaluacion_nombre) ?></td>
				<td><?= (int) $p->tiempo_limite_min ?> min</td>
				<td>
					<?php
						$etiquetas = [
							'pendiente' => ['secondary', 'Pendiente'],
							'en_progreso' => ['warning', 'En progreso'],
							'completada' => ['success', 'Completada'],
							'reutilizada' => ['info', 'Reutilizada de un proceso anterior'],
						];
						if($p->estado === 'completada' && $p->candidato_evaluacion_estado === 'vencida'){
							[$color, $texto] = ['secondary', 'Tiempo agotado'];
						}else{
							[$color, $texto] = $etiquetas[$p->estado] ?? ['secondary', $p->estado];
						}
					?>
					<span class="badge badge-<?= $color ?>"><?= htmlspecialchars($texto) ?></span>
				</td>
				<td>
					<?php if(in_array($p->estado, ['pendiente', 'en_progreso'])): ?>
						<a href="<?= RUTA_URL ?>evaluaciones/tomar/<?= $datos['token'] ?>/<?= $p->id ?>" class="btn btn-sm btn-primary">Resolver</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
