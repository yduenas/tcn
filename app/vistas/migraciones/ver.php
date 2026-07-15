<div class="app-main" style="max-width:760px;">
	<h4 class="mb-4">Migración: <?= htmlspecialchars($datos['nombre_archivo']) ?></h4>

	<?php if($datos['contenido'] === null): ?>
		<div class="alert alert-danger">El archivo no existe.</div>
	<?php else: ?>
		<div class="card">
			<div class="card-body">
				<pre style="white-space:pre-wrap; font-size:0.85rem;"><?= htmlspecialchars($datos['contenido']) ?></pre>
			</div>
		</div>
	<?php endif; ?>

	<a href="<?= RUTA_URL ?>migraciones/index" class="btn btn-outline-secondary btn-sm mt-3">Volver</a>
</div>
