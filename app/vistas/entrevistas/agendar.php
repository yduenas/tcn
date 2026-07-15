<div class="app-main" style="max-width:480px;">
	<h4 class="mb-4">Agendar entrevista</h4>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL ?>entrevistas/guardarAgenda/<?= $datos['postulacion_id'] ?>">
		<div class="form-group">
			<label>Fecha y hora</label>
			<input type="datetime-local" class="form-control" name="fecha_agendada" required>
		</div>
		<button type="submit" class="btn btn-primary">Agendar</button>
		<a href="javascript:history.back()" class="btn btn-outline-secondary">Cancelar</a>
	</form>
</div>
