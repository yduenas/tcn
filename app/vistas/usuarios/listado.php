<div class="app-main">
	<div class="d-flex justify-content-between align-items-center mb-3">
		<h4>Usuarios</h4>
		<a href="<?= RUTA_URL ?>usuarios/nuevo" class="btn btn-primary btn-sm">Nuevo usuario</a>
	</div>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>

	<table class="table table-striped table-bordered">
		<thead>
			<tr>
				<th>#</th>
				<th>Nombres</th>
				<th>Email</th>
				<th>Perfil</th>
				<th>Empresa</th>
				<th>Estado</th>
				<th>Fecha de creación</th>
				<th>Acciones</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($datos['usuarios'] as $usuario): ?>
			<tr>
				<td><?= $usuario->id ?></td>
				<td><?= htmlspecialchars($usuario->nombres.' '.$usuario->apellidos) ?></td>
				<td><?= htmlspecialchars($usuario->email) ?></td>
				<td>
					<?php if($_SESSION['perfil_nombre'] === 'Empresa'): ?>
						<?php // Autoservicio (2026-07-17): nunca editable por una Empresa -- si se
						      // permitiera aunque sea sobre su propio Seleccionador, podria auto-
						      // promoverlo a Administrador. Ver Usuarios::cambiarPerfil() (bloqueada
						      // por completo para este perfil, no solo oculta aquí). ?>
						<?= htmlspecialchars($usuario->perfil_nombre) ?>
					<?php else: ?>
						<form method="post" action="<?= RUTA_URL ?>usuarios/cambiarPerfil/<?= $usuario->id ?>" class="form-inline">
							<select name="perfil_id" class="form-control form-control-sm mr-2" onchange="this.form.submit()">
								<?php foreach($datos['perfiles'] as $perfil): ?>
									<option value="<?= $perfil->id ?>" <?= $perfil->id == $usuario->perfil_id ? 'selected' : '' ?>>
										<?= htmlspecialchars($perfil->nombre) ?>
									</option>
								<?php endforeach; ?>
							</select>
						</form>
					<?php endif; ?>
				</td>
				<td><?= $usuario->empresa_nombre ? htmlspecialchars($usuario->empresa_nombre) : '<span class="text-muted">-</span>' ?></td>
				<td><span class="badge badge-<?= $usuario->estado === 'activo' ? 'success' : 'secondary' ?>"><?= htmlspecialchars($usuario->estado) ?></span></td>
				<td><?= htmlspecialchars(fechaLocal($usuario->fecha_creacion)) ?></td>
				<td>
					<a href="<?= RUTA_URL ?>usuarios/editar/<?= $usuario->id ?>" class="btn btn-sm btn-outline-secondary">Editar</a>
					<a href="<?= RUTA_URL ?>usuarios/nuevaContrasena/<?= $usuario->id ?>" class="btn btn-sm btn-outline-secondary">Nueva contraseña</a>
					<?php if($usuario->estado === 'activo'): ?>
						<?php if($usuario->id != $_SESSION['usuario_id']): ?>
							<a href="<?= RUTA_URL ?>usuarios/desactivar/<?= $usuario->id ?>" class="btn btn-sm btn-outline-danger"
							   onclick="return confirm('¿Desactivar a <?= htmlspecialchars($usuario->nombres) ?>? No podrá iniciar sesión.');">Desactivar</a>
						<?php endif; ?>
					<?php else: ?>
						<a href="<?= RUTA_URL ?>usuarios/activar/<?= $usuario->id ?>" class="btn btn-sm btn-outline-success">Activar</a>
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
