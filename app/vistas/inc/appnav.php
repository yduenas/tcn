<?php $seccionActual = parametroEspecifico(0); ?>
<header class="app-header">
	<div class="app-header-inner">
		<div class="app-header-left">
			<a href="<?= RUTA_URL ?>dashboard/index" class="logo-mark">
				<svg viewBox="0 0 100 100" fill="none">
					<circle cx="50" cy="50" r="22" stroke="#1B4C91" stroke-width="6"/>
					<circle cx="72" cy="34" r="4.5" fill="#1B4C91"/>
					<circle cx="24" cy="42" r="4.5" fill="#3ECAB5"/>
					<circle cx="30" cy="72" r="4.5" fill="#1B4C91"/>
					<circle cx="76" cy="66" r="4.5" fill="#3ECAB5"/>
					<path d="M68 35 C58 20, 40 20, 28 38" stroke="#1B4C91" stroke-width="3" fill="none" stroke-linecap="round"/>
					<path d="M25 46 C18 58, 20 66, 28 70" stroke="#3ECAB5" stroke-width="3" fill="none" stroke-linecap="round"/>
					<path d="M35 76 C48 84, 64 80, 74 68" stroke="#1B4C91" stroke-width="3" fill="none" stroke-linecap="round"/>
					<circle cx="66" cy="52" r="2.6" fill="#1B4C91"/>
				</svg>
				<span class="logo-word">COMPLEMENT</span>
			</a>

			<div class="dropdown app-user-dropdown">
				<button class="app-user-toggle dropdown-toggle" type="button" id="menuUsuario" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<span class="app-user-nombre"><?= htmlspecialchars($_SESSION['usuario_nombre'] ?? '') ?></span>
					<span class="app-user-perfil"><?= htmlspecialchars($_SESSION['perfil_nombre'] ?? '') ?></span>
				</button>
				<div class="dropdown-menu" aria-labelledby="menuUsuario">
					<span class="dropdown-item-text app-user-correo"><?= htmlspecialchars($_SESSION['usuario_email'] ?? '') ?></span>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="<?= RUTA_URL ?>logins/cambiarPassword">Cambiar contraseña</a>
					<a class="dropdown-item" href="<?= RUTA_URL ?>logins/logout">Cerrar sesión</a>
				</div>
			</div>
		</div>

		<nav class="app-nav-links">
			<a href="<?= RUTA_URL ?>dashboard/index" class="<?= $seccionActual === 'dashboard' ? 'activo' : '' ?>">Dashboard</a>
			<?php if(tienePermiso('crear_vacante')): ?>
				<a href="<?= RUTA_URL ?>vacantes/index" class="<?= $seccionActual === 'vacantes' ? 'activo' : '' ?>">Vacantes</a>
			<?php endif; ?>
			<?php if(tienePermiso('ver_postulantes')): ?>
				<a href="<?= RUTA_URL ?>postulantes/index" class="<?= $seccionActual === 'postulantes' ? 'activo' : '' ?>">Postulantes</a>
			<?php endif; ?>
			<?php if(tienePermiso('ver_reportes_globales')): ?>
				<a href="<?= RUTA_URL ?>reportes/index" class="<?= $seccionActual === 'reportes' ? 'activo' : '' ?>">Reportes</a>
			<?php endif; ?>
			<?php if(tienePermiso('crear_empresa')): ?>
				<a href="<?= RUTA_URL ?>empresas/index" class="<?= $seccionActual === 'empresas' ? 'activo' : '' ?>">Empresas</a>
			<?php endif; ?>
			<?php if(tienePermiso('crear_usuario')): ?>
				<a href="<?= RUTA_URL ?>usuarios/index" class="<?= $seccionActual === 'usuarios' ? 'activo' : '' ?>">Usuarios</a>
			<?php endif; ?>
			<?php if(tienePermiso('configurar_perfiles')): ?>
				<a href="<?= RUTA_URL ?>perfiles/index" class="<?= $seccionActual === 'perfiles' ? 'activo' : '' ?>">Perfiles</a>
			<?php endif; ?>
			<?php if(tienePermiso('ver_auditoria')): ?>
				<a href="<?= RUTA_URL ?>auditorias/index" class="<?= $seccionActual === 'auditorias' ? 'activo' : '' ?>">Auditoría</a>
			<?php endif; ?>
			<?php if(tienePermiso('configurar_catalogos')): ?>
				<a href="<?= RUTA_URL ?>configuracionGeneral/index" class="<?= strtolower($seccionActual) === 'configuraciongeneral' ? 'activo' : '' ?>">Configuración</a>
			<?php endif; ?>
			<?php if(tienePermiso('configurar_plantillas_correo')): ?>
				<a href="<?= RUTA_URL ?>plantillasCorreo/index" class="<?= strtolower($seccionActual) === 'plantillascorreo' ? 'activo' : '' ?>">Plantillas de correo</a>
			<?php endif; ?>
			<?php if(tienePermiso('configurar_evaluaciones')): ?>
				<a href="<?= RUTA_URL ?>catalogoEvaluaciones/index" class="<?= strtolower($seccionActual) === 'catalogoevaluaciones' ? 'activo' : '' ?>">Evaluaciones</a>
			<?php endif; ?>
			<?php if(tienePermiso('administrar_bd')): ?>
				<a href="<?= RUTA_URL ?>baseDatos/index" class="<?= strtolower($seccionActual) === 'basedatos' ? 'activo' : '' ?>">Base de datos</a>
			<?php endif; ?>
			<?php if(tienePermiso('administrar_migraciones')): ?>
				<a href="<?= RUTA_URL ?>migraciones/index" class="<?= strtolower($seccionActual) === 'migraciones' ? 'activo' : '' ?>">Migraciones</a>
			<?php endif; ?>
			<?php if(tienePermiso('administrar_cron')): ?>
				<a href="<?= RUTA_URL ?>cron/index" class="<?= strtolower($seccionActual) === 'cron' ? 'activo' : '' ?>">Cron</a>
			<?php endif; ?>
			<a href="<?= RUTA_URL ?>manual/index" class="<?= strtolower($seccionActual) === 'manual' ? 'activo' : '' ?>">Manual de usuario</a>
			<a href="<?= RUTA_URL ?>portal/index" target="_blank">Portal público</a>
		</nav>
	</div>
</header>
