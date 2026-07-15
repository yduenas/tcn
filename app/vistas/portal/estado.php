<div class="app-main" style="max-width:640px;">
	<a href="<?= RUTA_URL ?>portal/index" class="btn btn-link px-0">&larr; Volver a las vacantes</a>

	<h4 class="mb-4">Consultar mi postulación</h4>

	<?php if(!empty($datos['mensaje'])): ?>
		<div class="alert alert-success"><?= htmlspecialchars($datos['mensaje']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL ?>portal/consultarEstado" class="form-row mb-4">
		<div class="col-md-5 mb-2">
			<input type="email" class="form-control" name="email" placeholder="Tu correo" required
			       value="<?= htmlspecialchars($datos['email_consultado'] ?? '') ?>">
		</div>
		<div class="col-md-4 mb-2">
			<input type="text" class="form-control" name="dni" placeholder="Tu DNI" required
			       value="<?= htmlspecialchars($datos['dni_consultado'] ?? '') ?>">
		</div>
		<div class="col-md-3 mb-2">
			<button type="submit" class="btn btn-primary btn-block">Consultar</button>
		</div>
	</form>

	<?php if(is_array($datos['postulaciones'])): ?>
		<?php if(empty($datos['postulaciones'])): ?>
			<p class="text-muted">No encontramos postulaciones registradas con esos datos.</p>
		<?php else: ?>
			<table class="table table-striped table-bordered">
				<thead>
					<tr><th>Vacante</th><th>Estado</th><th>Fecha</th><th>Evaluaciones</th></tr>
				</thead>
				<tbody>
					<?php foreach($datos['postulaciones'] as $p): ?>
					<tr>
						<td><?= htmlspecialchars($p->vacante_titulo) ?></td>
						<td><span class="badge badge-<?= $p->es_final ? 'dark' : 'info' ?>"><?= htmlspecialchars($p->estado_nombre) ?></span></td>
						<td><?= htmlspecialchars(fechaLocal($p->fecha_postulacion)) ?></td>
						<td>
							<?php if($p->token_evaluacion): ?>
								<a href="<?= RUTA_URL ?>evaluaciones/<?= $p->token_evaluacion ?>">Ver mis evaluaciones</a>
							<?php else: ?>
								<span class="text-muted">-</span>
							<?php endif; ?>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	<?php endif; ?>
</div>
