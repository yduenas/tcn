<div class="app-main" style="max-width:640px;">
	<h4 class="mb-4">Configuración general</h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Categorías de cargo</h6>
			<ul class="list-unstyled mb-3">
				<?php foreach($datos['cargos'] as $cargo): ?>
					<li class="py-2 d-flex align-items-center" style="border-top:1px solid rgba(15,33,56,0.06);">
						<form method="post" action="<?= RUTA_URL ?>configuracionGeneral/editarCargo/<?= $cargo->id ?>" class="form-inline flex-grow-1 mr-2">
							<input type="text" class="form-control form-control-sm mr-2" name="nombre" value="<?= htmlspecialchars($cargo->nombre) ?>" required style="max-width:220px;">
							<button type="submit" class="btn btn-sm btn-outline-secondary mr-2">Guardar</button>
							<?php if(!$cargo->activo): ?><span class="badge badge-secondary">Anulada</span><?php endif; ?>
						</form>
						<?php if($cargo->activo): ?>
							<a href="<?= RUTA_URL ?>configuracionGeneral/anularCargo/<?= $cargo->id ?>" class="btn btn-sm btn-outline-danger"
							   onclick="return confirm('¿Anular esta categoría de cargo? Las vacantes que ya la usan no se ven afectadas, pero dejará de estar disponible para nuevas vacantes.');">Anular</a>
						<?php else: ?>
							<a href="<?= RUTA_URL ?>configuracionGeneral/reactivarCargo/<?= $cargo->id ?>" class="btn btn-sm btn-outline-success">Reactivar</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<form method="post" action="<?= RUTA_URL ?>configuracionGeneral/agregarCargo" class="form-inline">
				<input type="text" class="form-control mr-2" name="nombre" placeholder="Nueva categoría de cargo" required>
				<button type="submit" class="btn btn-outline-primary btn-sm">Agregar</button>
			</form>
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Modalidades de trabajo</h6>
			<ul class="list-unstyled mb-3">
				<?php foreach($datos['modalidades'] as $modalidad): ?>
					<li class="py-2 d-flex align-items-center" style="border-top:1px solid rgba(15,33,56,0.06);">
						<form method="post" action="<?= RUTA_URL ?>configuracionGeneral/editarModalidad/<?= $modalidad->id ?>" class="form-inline flex-grow-1 mr-2">
							<input type="text" class="form-control form-control-sm mr-2" name="nombre" value="<?= htmlspecialchars($modalidad->nombre) ?>" required style="max-width:220px;">
							<button type="submit" class="btn btn-sm btn-outline-secondary mr-2">Guardar</button>
							<?php if(!$modalidad->activo): ?><span class="badge badge-secondary">Anulada</span><?php endif; ?>
						</form>
						<?php if($modalidad->activo): ?>
							<a href="<?= RUTA_URL ?>configuracionGeneral/anularModalidad/<?= $modalidad->id ?>" class="btn btn-sm btn-outline-danger"
							   onclick="return confirm('¿Anular esta modalidad? Las vacantes que ya la usan no se ven afectadas, pero dejará de estar disponible para nuevas vacantes.');">Anular</a>
						<?php else: ?>
							<a href="<?= RUTA_URL ?>configuracionGeneral/reactivarModalidad/<?= $modalidad->id ?>" class="btn btn-sm btn-outline-success">Reactivar</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<form method="post" action="<?= RUTA_URL ?>configuracionGeneral/agregarModalidad" class="form-inline">
				<input type="text" class="form-control mr-2" name="nombre" placeholder="Nueva modalidad" required>
				<button type="submit" class="btn btn-outline-primary btn-sm">Agregar</button>
			</form>
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Competencias (calificación de entrevista)</h6>
			<ul class="list-unstyled mb-3">
				<?php foreach($datos['competencias'] as $competencia): ?>
					<li class="py-2 d-flex align-items-center" style="border-top:1px solid rgba(15,33,56,0.06);">
						<form method="post" action="<?= RUTA_URL ?>configuracionGeneral/editarCompetencia/<?= $competencia->id ?>" class="form-inline flex-grow-1 mr-2">
							<input type="text" class="form-control form-control-sm mr-2" name="nombre" value="<?= htmlspecialchars($competencia->nombre) ?>" required style="max-width:220px;">
							<button type="submit" class="btn btn-sm btn-outline-secondary mr-2">Guardar</button>
							<?php if(!$competencia->activo): ?><span class="badge badge-secondary">Anulada</span><?php endif; ?>
						</form>
						<?php if($competencia->activo): ?>
							<a href="<?= RUTA_URL ?>configuracionGeneral/anularCompetencia/<?= $competencia->id ?>" class="btn btn-sm btn-outline-danger"
							   onclick="return confirm('¿Anular esta competencia? Las vacantes y entrevistas que ya la usan no se ven afectadas, pero dejará de estar disponible por defecto para nuevas entrevistas.');">Anular</a>
						<?php else: ?>
							<a href="<?= RUTA_URL ?>configuracionGeneral/reactivarCompetencia/<?= $competencia->id ?>" class="btn btn-sm btn-outline-success">Reactivar</a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<form method="post" action="<?= RUTA_URL ?>configuracionGeneral/agregarCompetencia" class="form-inline">
				<input type="text" class="form-control mr-2" name="nombre" placeholder="Nueva competencia" required>
				<button type="submit" class="btn btn-outline-primary btn-sm">Agregar</button>
			</form>
		</div>
	</div>
</div>
