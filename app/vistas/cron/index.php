<div class="app-main" style="max-width:680px;">
	<h4 class="mb-4">Cron</h4>
	<p class="small text-muted mb-4">El servidor no permite crear cron jobs reales, así que estas tareas de mantenimiento periódico se ejecutan a mano desde acá.</p>

	<?php if(!empty($datos['mensaje'])): ?>
		<div class="alert alert-success"><?= htmlspecialchars($datos['mensaje']) ?></div>
	<?php endif; ?>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Limpiar CVs huérfanos</h6>
			<p class="small text-muted">Cuando alguien usa "Autocompletar desde mi CV" en el portal público pero nunca termina de enviar su postulación, el archivo queda guardado en el servidor sin ningún candidato asociado. Esta tarea borra esos archivos, pero solo los que ya tienen <?= (int) $datos['resumenCv']['horas'] ?> horas o más (para no borrar el de alguien que todavía está llenando el formulario).</p>

			<table class="table table-sm mb-3">
				<tbody>
					<tr>
						<td>Huérfanos en total</td>
						<td class="text-right"><b><?= (int) $datos['resumenCv']['total'] ?></b></td>
					</tr>
					<tr>
						<td>Huérfanos con <?= (int) $datos['resumenCv']['horas'] ?>h o más (se borrarán)</td>
						<td class="text-right"><b><?= (int) $datos['resumenCv']['vencidos'] ?></b></td>
					</tr>
				</tbody>
			</table>

			<a href="<?= RUTA_URL ?>cron/limpiarCvHuerfanos" class="btn btn-sm btn-outline-primary confirmar-swal"
			   data-mensaje="¿Borrar <?= (int) $datos['resumenCv']['vencidos'] ?> CV(s) huérfano(s) con <?= (int) $datos['resumenCv']['horas'] ?> horas o más? Esta acción no se puede deshacer.">Ejecutar limpieza</a>
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Limpiar CVs de postulantes sin proceso activo</h6>
			<p class="small text-muted">Candidatos cuyas postulaciones están TODAS en Descartado o Desertó (ningún proceso en curso en ninguna vacante) desde hace <?= (int) $datos['resumenCvDescartados']['dias'] ?> días o más. Esta tarea borra su CV guardado — si vuelven a postular más adelante y ya no tienen un CV en archivo, el sistema se lo vuelve a pedir con normalidad.</p>

			<table class="table table-sm mb-3">
				<tbody>
					<tr>
						<td>Candidatos elegibles (se les borrará el CV)</td>
						<td class="text-right"><b><?= (int) $datos['resumenCvDescartados']['total'] ?></b></td>
					</tr>
				</tbody>
			</table>

			<a href="<?= RUTA_URL ?>cron/limpiarCvDescartados" class="btn btn-sm btn-outline-primary confirmar-swal"
			   data-mensaje="¿Borrar el CV de <?= (int) $datos['resumenCvDescartados']['total'] ?> candidato(s) sin proceso activo? Esta acción no se puede deshacer.">Ejecutar limpieza</a>
		</div>
	</div>
</div>
