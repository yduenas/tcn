<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4><span class="mono"><?= htmlspecialchars($datos['nombre']) ?></span></h4>
		<a href="<?= RUTA_URL ?>baseDatos/index" class="btn btn-outline-secondary btn-sm">&larr; Volver</a>
	</div>

	<div class="d-flex justify-content-between align-items-center mb-2">
		<p class="text-muted mb-0"><?= (int) $datos['total'] ?> fila(s) en total.</p>
		<div>
			<button type="button" id="btn-copiar-tabla" class="btn btn-sm btn-outline-secondary"
			        onclick="copiarTablaAlPortapapeles('tabla-datos', 'btn-copiar-tabla')">Copiar página visible</button>
			<a href="<?= RUTA_URL ?>baseDatos/exportarTabla/<?= urlencode($datos['nombre']) ?>" class="btn btn-sm btn-outline-success">Descargar Excel (todas las filas)</a>
		</div>
	</div>

	<div style="overflow-x:auto;">
		<table id="tabla-datos" class="table table-sm table-striped table-bordered">
			<thead>
				<tr>
					<?php foreach($datos['columnas'] as $col): ?>
						<th class="text-nowrap"><?= htmlspecialchars($col['name']) ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php if(empty($datos['filas'])): ?>
					<tr><td colspan="<?= count($datos['columnas']) ?>" class="text-muted text-center">Sin filas.</td></tr>
				<?php endif; ?>
				<?php foreach($datos['filas'] as $fila): ?>
				<tr>
					<?php foreach($datos['columnas'] as $col): ?>
						<td class="text-nowrap"><?= htmlspecialchars($fila[$col['name']] ?? '') ?></td>
					<?php endforeach; ?>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

	<?php $totalPaginas = max(1, (int) ceil($datos['total'] / $datos['porPagina'])); ?>
	<?php if($totalPaginas > 1): ?>
	<nav class="d-flex justify-content-between align-items-center">
		<?php if($datos['pagina'] > 1): ?>
			<a href="<?= RUTA_URL ?>baseDatos/tabla/<?= urlencode($datos['nombre']) ?>?pagina=<?= $datos['pagina'] - 1 ?>" class="btn btn-sm btn-outline-secondary">&larr; Anterior</a>
		<?php else: ?><span></span><?php endif; ?>
		<span class="text-muted">Página <?= $datos['pagina'] ?> de <?= $totalPaginas ?></span>
		<?php if($datos['pagina'] < $totalPaginas): ?>
			<a href="<?= RUTA_URL ?>baseDatos/tabla/<?= urlencode($datos['nombre']) ?>?pagina=<?= $datos['pagina'] + 1 ?>" class="btn btn-sm btn-outline-secondary">Siguiente &rarr;</a>
		<?php else: ?><span></span><?php endif; ?>
	</nav>
	<?php endif; ?>
</div>
