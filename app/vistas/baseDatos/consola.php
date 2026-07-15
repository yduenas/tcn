<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Consola SQL</h4>
		<a href="<?= RUTA_URL ?>baseDatos/index" class="btn btn-outline-secondary btn-sm">&larr; Volver a tablas</a>
	</div>

	<div class="alert alert-warning">
		Cualquier sentencia que escribas aquí (SELECT, UPDATE, DELETE, ALTER, etc.) se ejecuta directamente sobre la
		base de datos en producción, sin confirmación adicional ni posibilidad de deshacer. Queda registrada en la
		auditoría del sistema. Si no estás seguro de una sentencia, pruébala primero como SELECT.
	</div>

	<?php if($datos['error']): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>
	<?php if($datos['mensaje']): ?>
		<div class="alert alert-success"><?= htmlspecialchars($datos['mensaje']) ?></div>
	<?php endif; ?>

	<form method="post" action="<?= RUTA_URL ?>baseDatos/consola" class="mb-4">
		<div class="form-group">
			<textarea class="form-control" name="sql" rows="6" style="font-family:'IBM Plex Mono',monospace; font-size:13px;"
			          placeholder="SELECT * FROM vacantes WHERE estado = 'publicada';" required><?= htmlspecialchars($datos['sql']) ?></textarea>
		</div>
		<button type="submit" class="btn btn-primary">Ejecutar</button>
		<button type="submit" formaction="<?= RUTA_URL ?>baseDatos/exportarConsulta" class="btn btn-outline-success">Descargar Excel (CSV)</button>
	</form>

	<?php if(is_array($datos['filas'])): ?>
		<div class="d-flex justify-content-end mb-2">
			<button type="button" id="btn-copiar-consulta" class="btn btn-sm btn-outline-secondary"
			        onclick="copiarTablaAlPortapapeles('tabla-resultado-consulta', 'btn-copiar-consulta')">Copiar resultado</button>
		</div>
		<div style="overflow-x:auto;">
			<table id="tabla-resultado-consulta" class="table table-sm table-striped table-bordered">
				<thead>
					<tr>
						<?php foreach($datos['columnas'] as $col): ?>
							<th class="text-nowrap"><?= htmlspecialchars($col) ?></th>
						<?php endforeach; ?>
					</tr>
				</thead>
				<tbody>
					<?php if(empty($datos['filas'])): ?>
						<tr><td colspan="<?= max(1, count($datos['columnas'])) ?>" class="text-muted text-center">Sin filas.</td></tr>
					<?php endif; ?>
					<?php foreach($datos['filas'] as $fila): ?>
					<tr>
						<?php foreach($datos['columnas'] as $col): ?>
							<td class="text-nowrap"><?= htmlspecialchars($fila[$col] ?? '') ?></td>
						<?php endforeach; ?>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	<?php endif; ?>
</div>
