<?php
	/** Rediseño 2026-07-14: Administrador mueve postulantes de cualquier vacante;
	 * Seleccionador solo de las que tiene asignadas (mismo criterio server-side
	 * en Postulaciones::moverEstado()) -- se oculta el control "Mover a" en vez
	 * de mostrar un botón que fallaría al hacer clic. **/
	$puedeMover = $_SESSION['perfil_nombre'] === 'Administrador' || $datos['vacante']->seleccionador_id == $_SESSION['usuario_id'];
?>
<div class="app-main">
	<a href="<?= RUTA_URL ?>vacantes/index" class="btn btn-link px-0">&larr; Volver a vacantes</a>

	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Postulantes: <?= htmlspecialchars($datos['vacante']->titulo) ?></h4>
		<div>
			<span class="text-muted mr-2"><?= htmlspecialchars($datos['vacante']->empresa_nombre) ?></span>
		</div>
	</div>

	<?php if(empty($datos['postulaciones'])): ?>
		<p class="text-muted">Todavía no hay postulantes para esta vacante.</p>
	<?php else: ?>
	<table class="table table-striped table-bordered align-middle data-table" id="tablaPostulantes" data-export-name="Postulantes - <?= htmlspecialchars($datos['vacante']->titulo) ?>" data-hidden-columns="8,9">
		<thead>
			<tr>
				<th>Candidato</th>
				<th>DNI</th>
				<th>Correo</th>
				<th>Teléfono</th>
				<th>Empresa</th>
				<th>Puesto</th>
				<th>Pretensión</th>
				<th>Disponibilidad</th>
				<th>Postulado</th>
				<th>Fecha del último estado</th>
				<th>Evaluaciones</th>
				<th>Entrevista</th>
				<th>Estado</th>
				<th>Mover a</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['postulaciones'] as $p): ?>
			<?php
				$totalEval = count($p->evaluaciones);
				$completadas = count(array_filter($p->evaluaciones, function($e){ return in_array($e->estado, ['completada', 'reutilizada']); }));
			?>
			<tr>
				<td>
					<a href="<?= RUTA_URL ?>postulaciones/perfil/<?= $p->id ?>"><?= htmlspecialchars($p->nombres.' '.$p->apellidos) ?></a>
				</td>
				<td><?= htmlspecialchars($p->dni ?: '-') ?></td>
				<td><?= htmlspecialchars($p->email) ?></td>
				<td><?= htmlspecialchars($p->telefono ?: '-') ?></td>
				<td><?= htmlspecialchars($datos['vacante']->empresa_nombre) ?></td>
				<td><?= htmlspecialchars($datos['vacante']->titulo) ?></td>
				<td><?= htmlspecialchars(formatearSoles($p->pretension_salarial) ?? '-') ?></td>
				<td><?= htmlspecialchars($p->disponibilidad ?: '-') ?></td>
				<td><?= htmlspecialchars(fechaLocal($p->fecha_postulacion)) ?></td>
				<td><?= htmlspecialchars(fechaLocal($p->fecha_ultimo_cambio) ?: '-') ?></td>
				<td>
					<?php if($totalEval === 0): ?>
						<span class="text-muted">-</span>
					<?php else: ?>
						<span class="badge badge-<?= $completadas == $totalEval ? 'success' : 'secondary' ?>"><?= $completadas ?>/<?= $totalEval ?></span>
						<?php if(tienePermiso('ver_datos_sensibles_evaluacion')): ?>
							<a href="<?= RUTA_URL ?>postulaciones/resultados/<?= $p->id ?>">Ver</a>
						<?php endif; ?>
						<?php if(tienePermiso('configurar_evaluaciones')): ?>
							<?php foreach($p->evaluaciones as $ev): ?>
								<?php if($ev->estado === 'reutilizada'): ?>
									<form method="post" action="<?= RUTA_URL ?>postulaciones/forzarEvaluacion/<?= $ev->id ?>" class="d-inline">
										<button type="submit" class="btn btn-sm btn-link p-0" style="font-size:.72rem;"
										        onclick="return confirm('¿Forzar que rinda de nuevo la evaluación <?= htmlspecialchars($ev->evaluacion_nombre) ?> aunque tenga una vigente?');">
											Forzar <?= htmlspecialchars($ev->evaluacion_nombre) ?>
										</button>
									</form>
								<?php elseif($ev->candidato_evaluacion_estado === 'vencida'): ?>
									<form method="post" action="<?= RUTA_URL ?>postulaciones/forzarEvaluacion/<?= $ev->id ?>" class="d-inline">
										<button type="submit" class="btn btn-sm btn-link p-0" style="font-size:.72rem;"
										        onclick="return confirm('Se le agotó el tiempo en \"<?= htmlspecialchars($ev->evaluacion_nombre) ?>\" sin terminarla. ¿Darle un nuevo intento?');">
											Reintentar <?= htmlspecialchars($ev->evaluacion_nombre) ?>
										</button>
									</form>
								<?php endif; ?>
							<?php endforeach; ?>
						<?php endif; ?>
					<?php endif; ?>
				</td>
				<td>
					<?php if(!$p->entrevista): ?>
						<?php if(tienePermiso('agendar_entrevista')): ?>
							<a href="<?= RUTA_URL ?>entrevistas/agendar/<?= $p->id ?>" class="btn btn-sm btn-outline-secondary">Agendar</a>
						<?php else: ?>
							<span class="text-muted">-</span>
						<?php endif; ?>
					<?php else: ?>
						<span class="badge badge-<?= $p->entrevista->fecha_realizada ? 'success' : 'secondary' ?>">
							<?= $p->entrevista->fecha_realizada ? 'Realizada' : 'Agendada' ?>
						</span>
						<a href="<?= RUTA_URL ?>entrevistas/detalle/<?= $p->entrevista->id ?>">Ver</a>
					<?php endif; ?>
				</td>
				<td><span class="badge badge-<?= $p->es_final ? 'dark' : 'info' ?>"><?= htmlspecialchars($p->estado_nombre) ?></span></td>
				<td>
					<?php if($puedeMover): ?>
					<form method="post" action="<?= RUTA_URL ?>postulaciones/moverEstado/<?= $p->id ?>" class="form-inline">
						<select name="estado_id" class="form-control form-control-sm mr-2">
							<?php foreach($datos['estados'] as $estado): ?>
								<option value="<?= $estado->id ?>" <?= $estado->id == $p->estado_id ? 'selected' : '' ?>>
									<?= htmlspecialchars($estado->nombre) ?>
								</option>
							<?php endforeach; ?>
						</select>
						<button type="submit" class="btn btn-sm btn-outline-primary">Mover</button>
					</form>
					<?php else: ?>
						<span class="text-muted" style="font-size:.8rem;">Solo el Seleccionador asignado o Administrador</span>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php endif; ?>
</div>
