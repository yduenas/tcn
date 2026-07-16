<div class="app-main">
	<h4 class="mb-4">Catálogo de evaluaciones</h4>

	<table class="table table-striped table-bordered align-middle">
		<thead>
			<tr>
				<th>Nombre</th>
				<th>Tipo</th>
				<th>Tiempo límite</th>
				<th>Vigencia (meses)</th>
				<th>Preguntas</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['evaluaciones'] as $ev): ?>
			<tr>
				<td><?= htmlspecialchars($ev->nombre) ?></td>
				<td><?= htmlspecialchars($ev->tipo) ?></td>
				<td>
					<form method="post" action="<?= RUTA_URL ?>catalogoEvaluaciones/actualizarTiempoLimite/<?= $ev->id ?>" class="form-inline">
						<input type="number" min="1" required class="form-control form-control-sm mr-2" style="width:70px;"
						       name="tiempo_limite_min" value="<?= (int) $ev->tiempo_limite_min ?>"> min
						<button type="submit" class="btn btn-sm btn-outline-secondary ml-2">Guardar</button>
					</form>
				</td>
				<td>
					<form method="post" action="<?= RUTA_URL ?>catalogoEvaluaciones/actualizarVigencia/<?= $ev->id ?>" class="form-inline">
						<input type="number" min="0" class="form-control form-control-sm mr-2" style="width:80px;"
						       name="vigencia_meses" value="<?= htmlspecialchars($ev->vigencia_meses ?? '') ?>" placeholder="Sin vencer">
						<button type="submit" class="btn btn-sm btn-outline-secondary">Guardar</button>
					</form>
				</td>
				<td><span class="badge badge-info"><?= $ev->total_preguntas ?></span></td>
				<td><a href="<?= RUTA_URL ?>catalogoEvaluaciones/preguntas/<?= $ev->id ?>" class="btn btn-sm btn-outline-primary">Ver preguntas</a></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
