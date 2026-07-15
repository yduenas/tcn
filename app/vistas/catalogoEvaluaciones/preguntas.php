<div class="app-main">
	<a href="<?= RUTA_URL ?>catalogoEvaluaciones/index" class="btn btn-link px-0">&larr; Volver al catálogo</a>

	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4><?= htmlspecialchars($datos['evaluacion']->nombre) ?></h4>
		<a href="<?= RUTA_URL ?>catalogoEvaluaciones/nuevaPregunta/<?= $datos['evaluacion']->id ?>" class="btn btn-primary btn-sm">Nueva pregunta</a>
	</div>

	<table class="table table-striped table-bordered align-middle">
		<thead>
			<tr>
				<th>#</th>
				<th>Enunciado</th>
				<?php if($datos['evaluacion']->tipo === 'sjt'): ?><th>Competencia</th><?php endif; ?>
				<th>Opciones</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['preguntas'] as $i => $pregunta): ?>
			<tr>
				<td><?= $i + 1 ?></td>
				<td><?= htmlspecialchars($pregunta->enunciado) ?></td>
				<?php if($datos['evaluacion']->tipo === 'sjt'): ?>
					<td><span class="badge badge-light"><?= htmlspecialchars($pregunta->competencia_nombre ?? '-') ?></span></td>
				<?php endif; ?>
				<td><?= count($pregunta->opciones) ?></td>
				<td><a href="<?= RUTA_URL ?>catalogoEvaluaciones/editarPregunta/<?= $pregunta->id ?>" class="btn btn-sm btn-outline-primary">Editar</a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
