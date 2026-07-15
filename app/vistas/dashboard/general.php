<?php $k = $datos['kpis']; ?>
<div class="app-main">
	<span class="eyebrow">Panel general</span>
	<h4 class="mb-4">Dashboard</h4>

	<div class="row mb-4">
		<div class="col-md-3 col-6 mb-3">
			<div class="stat-card">
				<span class="stat-num"><?= $k->empresas_activas ?></span>
				<span class="stat-label">Empresas activas</span>
			</div>
		</div>
		<div class="col-md-3 col-6 mb-3">
			<div class="stat-card">
				<span class="stat-num"><?= $k->vacantes_publicadas ?></span>
				<span class="stat-label">Vacantes publicadas</span>
			</div>
		</div>
		<div class="col-md-3 col-6 mb-3">
			<div class="stat-card">
				<span class="stat-num"><?= $k->postulaciones_totales ?></span>
				<span class="stat-label">Postulaciones totales</span>
			</div>
		</div>
		<div class="col-md-3 col-6 mb-3">
			<div class="stat-card">
				<span class="stat-num"><?= $k->usuarios_activos ?></span>
				<span class="stat-label">Usuarios activos</span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-5 mb-4">
			<h6 class="text-uppercase text-muted mb-3">Postulaciones por etapa</h6>
			<table class="table table-sm table-striped">
				<tbody>
					<?php foreach($datos['postulacionesPorEstado'] as $fila): ?>
					<tr>
						<td><?= htmlspecialchars($fila->estado_nombre) ?></td>
						<td class="text-right font-weight-bold"><?= $fila->cantidad ?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<div class="col-md-7 mb-4">
			<div class="d-flex justify-content-between align-items-center mb-3">
				<h6 class="text-uppercase text-muted mb-0">Vacantes recientes</h6>
				<a href="<?= RUTA_URL ?>vacantes/index" class="btn btn-sm btn-outline-primary">Ver todas</a>
			</div>
			<table class="table table-sm table-striped">
				<thead><tr><th>Título</th><th>Empresa</th><th>Estado</th></tr></thead>
				<tbody>
					<?php foreach($datos['vacantesRecientes'] as $v): ?>
					<tr>
						<td><?= htmlspecialchars($v->titulo) ?></td>
						<td><?= htmlspecialchars($v->empresa_nombre) ?></td>
						<td><span class="badge badge-<?= $v->estado === 'publicada' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($v->estado) ?></span></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
