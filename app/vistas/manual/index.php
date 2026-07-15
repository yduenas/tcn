<?php
	/**
	 * Cada bloque se agrega al arreglo $secciones solo si el usuario tiene el/los
	 * permiso(s) relacionados -- igual criterio que app/vistas/inc/appnav.php --
	 * asi el manual muestra exactamente lo que ese usuario puede hacer, sea cual
	 * sea el nombre de su perfil.
	 */
	$secciones = [];

	$secciones[] = [
		'id' => 'primeros-pasos',
		'titulo' => 'Primeros pasos',
		'visible' => true,
	];

	$secciones[] = [
		'id' => 'vacantes',
		'titulo' => 'Gestión de vacantes',
		'visible' => tienePermiso('crear_vacante') || tienePermiso('editar_vacante') || tienePermiso('publicar_vacante'),
	];

	$secciones[] = [
		'id' => 'postulantes',
		'titulo' => 'Postulantes y pipeline',
		'visible' => tienePermiso('ver_postulantes'),
	];

	$secciones[] = [
		'id' => 'entrevistas',
		'titulo' => 'Entrevistas',
		'visible' => tienePermiso('agendar_entrevista') || tienePermiso('calificar_entrevista'),
	];

	$secciones[] = [
		'id' => 'evaluaciones',
		'titulo' => 'Catálogo de evaluaciones',
		'visible' => tienePermiso('configurar_evaluaciones'),
	];

	$secciones[] = [
		'id' => 'reportes',
		'titulo' => 'Reportes y PDF',
		'visible' => tienePermiso('exportar_pdf') || tienePermiso('ver_reportes_globales'),
	];

	$secciones[] = [
		'id' => 'dashboard-empresa',
		'titulo' => 'Tu dashboard como empresa cliente',
		'visible' => !empty($_SESSION['empresa_id']),
	];

	$secciones[] = [
		'id' => 'empresas',
		'titulo' => 'Empresas clientes',
		'visible' => tienePermiso('crear_empresa') || tienePermiso('dar_baja_empresa'),
	];

	$secciones[] = [
		'id' => 'usuarios',
		'titulo' => 'Usuarios',
		'visible' => tienePermiso('crear_usuario') || tienePermiso('editar_usuario'),
	];

	$secciones[] = [
		'id' => 'perfiles',
		'titulo' => 'Perfiles y permisos',
		'visible' => tienePermiso('configurar_perfiles'),
	];

	$secciones[] = [
		'id' => 'configuracion',
		'titulo' => 'Configuración general',
		'visible' => tienePermiso('configurar_catalogos'),
	];

	$secciones[] = [
		'id' => 'auditoria',
		'titulo' => 'Auditoría',
		'visible' => tienePermiso('ver_auditoria'),
	];

	$secciones[] = [
		'id' => 'base-datos',
		'titulo' => 'Base de datos',
		'visible' => tienePermiso('administrar_bd'),
	];
?>
<div class="app-main" style="max-width:860px;">

	<span class="eyebrow">Guía del sistema</span>
	<h4 class="mb-2">Manual de usuario</h4>
	<p class="text-muted mb-4">
		Perfil actual: <strong><?= htmlspecialchars($_SESSION['perfil_nombre'] ?? '') ?></strong>.
		Las secciones que ves a continuación dependen de los permisos asignados a tu perfil,
		así que si tu perfil cambia (o es uno personalizado creado por un administrador), esta
		misma página se ajusta sola.
	</p>

	<div class="card mb-4">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">Índice</h6>
			<ul class="list-unstyled mb-0" style="column-count:2;">
				<?php foreach($secciones as $s): if(!$s['visible']) continue; ?>
					<li class="mb-2"><a href="#<?= $s['id'] ?>"><?= htmlspecialchars($s['titulo']) ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<div class="card mb-4" id="primeros-pasos">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">1. Primeros pasos</h6>
			<ul>
				<li>Ingresa con tu correo y contraseña desde la pantalla de inicio de sesión. Si la olvidaste, usa "Recuperar contraseña": se te enviará un enlace por correo para crear una nueva.</li>
				<li>El menú superior solo muestra los módulos para los que tienes permiso — si no ves algo que crees que deberías ver, pídele a un Administrador que revise tu perfil en <a href="<?= RUTA_URL ?>perfiles/index">Perfiles</a>.</li>
				<li>Tu nombre y perfil aparecen arriba a la derecha, junto al botón "Salir" para cerrar sesión.</li>
			</ul>
		</div>
	</div>

	<?php if(tienePermiso('crear_vacante') || tienePermiso('editar_vacante') || tienePermiso('publicar_vacante')): ?>
	<div class="card mb-4" id="vacantes">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">2. Gestión de vacantes</h6>
			<ul>
				<li>Crea una vacante desde <a href="<?= RUTA_URL ?>vacantes/index">Vacantes</a>: cargo, categoría, modalidad, objetivo del puesto y funciones.</li>
				<li>Al crearla eliges qué evaluaciones se le pedirán al postulante (DISC, Matemática, Verbal, Valores/Competencias) — puedes marcar una, varias o ninguna.</li>
				<li>Opciones de publicación por vacante: <strong>modo anónimo</strong> (oculta el logo y nombre de la empresa en el portal público, nunca internamente) y <strong>ocultar salario</strong> (muestra "A convenir"). Ambas son independientes y se definen por cada vacante, no por la empresa.</li>
				<li>Publica o despublica la vacante cuando quieras. Una vacante cerrada puede reabrirse con el botón "Reabrir" (queda despublicada hasta que decidas republicarla).</li>
				<li>Cada vacante tiene un código único (ej. <span class="mono">VAC-0001</span>) visible en el listado, útil cuando hay varias con el mismo título.</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('ver_postulantes')): ?>
	<div class="card mb-4" id="postulantes">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">3. Postulantes y pipeline</h6>
			<ul>
				<li>Cada vacante tiene un funnel de estados: <span class="badge badge-info">Recibida</span> → <span class="badge badge-info">En revisión</span> → <span class="badge badge-info">Preseleccionado</span> → <span class="badge badge-info">Entrevista</span> → <span class="badge badge-dark">Terna final</span> → <span class="badge badge-primary">Pre-contratado</span> → <span class="badge badge-secondary">Contratado / Descartado / Desertó</span>. Se mueve con el selector "Mover a" de cada fila, sin ningún paso de aprobación aparte.</li>
				<li><strong>Importante:</strong> apenas mueves a un candidato a <span class="badge badge-dark">Terna final</span>, <span class="badge badge-primary">Pre-contratado</span> o <span class="badge badge-secondary">Contratado</span>, la empresa cliente lo ve automáticamente en su propio dashboard (con reporte PDF y perfil completo) — no hace falta ningún envío ni aprobación por separado. Descartado y Desertó nunca se muestran a la empresa.</li>
				<li>Administrador puede mover postulantes de cualquier vacante; Seleccionador solo de las vacantes donde figura como responsable asignado.</li>
				<li>Haz clic en el nombre de un postulante para ver su perfil completo: experiencia, educación, habilidades, DNI y descarga directa del CV.</li>
				<li>Los datos de contacto y experiencia que ves precargados pueden venir de la extracción automática del CV del candidato — siempre pasaron antes por su propia revisión y confirmación, nunca se guardan sin que el candidato los valide.</li>
				<?php if(tienePermiso('configurar_evaluaciones')): ?>
					<li>Si necesitas que un candidato repita una evaluación aunque ya tenga una vigente, puedes forzarla desde la columna "Evaluaciones" del pipeline — queda registrado en auditoría.</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('agendar_entrevista') || tienePermiso('calificar_entrevista')): ?>
	<div class="card mb-4" id="entrevistas">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">4. Entrevistas</h6>
			<ul>
				<li>Agenda una entrevista para un candidato que avanzó en el pipeline.</li>
				<li>Registra notas y una calificación cualitativa por competencia — esta calificación es la que después aparece en el reporte PDF del candidato, junto a la recomendación final (Recomendado / Recomendado con reservas / No recomendado).</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('configurar_evaluaciones')): ?>
	<div class="card mb-4" id="evaluaciones">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">5. Catálogo de evaluaciones</h6>
			<ul>
				<li>Desde <a href="<?= RUTA_URL ?>catalogoEvaluaciones/index">Evaluaciones</a> administras las preguntas y opciones de cada evaluación (opción única, elección forzada DISC, SJT de Valores/Competencias) sin tocar código.</li>
				<li>La vigencia de cada evaluación (en meses) se edita directamente en el listado del catálogo — determina cuánto tiempo un candidato no necesita repetirla al postular a otra vacante.</li>
				<li>El temporizador de cada evaluación corta el tiempo del lado del servidor: aunque el candidato cierre la pestaña, el tiempo restante se sigue calculando contra la hora real de inicio.</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('exportar_pdf') || tienePermiso('ver_reportes_globales')): ?>
	<div class="card mb-4" id="reportes">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">6. Reportes y PDF</h6>
			<ul>
				<?php if(tienePermiso('exportar_pdf')): ?>
					<li>Descarga el reporte PDF de un candidato desde "Resultados de evaluaciones" o desde el pipeline de la vacante. Incluye portada, competencias (radar aproximado con barras + DISC si aplica), capacidad (Matemática/Verbal) y la recomendación de entrevista.</li>
				<?php endif; ?>
				<?php if(tienePermiso('ver_reportes_globales')): ?>
					<li>El dashboard principal muestra métricas del negocio: empresas activas/inactivas, vacantes abiertas vs. cerradas, postulantes en proceso y productividad por seleccionador.</li>
				<?php endif; ?>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(!empty($_SESSION['empresa_id'])): ?>
	<div class="card mb-4" id="dashboard-empresa">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">7. Tu dashboard como empresa cliente</h6>
			<ul>
				<li>Tu <a href="<?= RUTA_URL ?>dashboard/index">Dashboard</a> muestra, por cada vacante tuya, la cantidad de postulantes y el avance por etapa, además del histórico de vacantes ya cerradas.</li>
				<li>Apenas un candidato llega a "Terna final", "Pre-contratado" o "Contratado" en el proceso, aparece automáticamente en la sección "Terna final" de tu dashboard, con el reporte PDF y un botón "Ver perfil y CV" con el detalle completo (experiencia, educación, habilidades, DNI, pretensión salarial, disponibilidad). No necesitas esperar ningún envío por parte del seleccionador. Eres solo visualizador del proceso: no puedes mover candidatos entre etapas.</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('crear_empresa') || tienePermiso('dar_baja_empresa')): ?>
	<div class="card mb-4" id="empresas">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">8. Empresas clientes</h6>
			<ul>
				<li>Al crear una empresa se sube su logo (obligatorio, JPG/PNG) y opcionalmente sus datos de contacto (nombre, teléfono, correo, dirección, web, LinkedIn).</li>
				<li>Haz clic en el nombre de una empresa en el listado para ver su detalle completo sin entrar a "Editar".</li>
				<li>"Dar de baja" es una baja lógica: sus vacantes activas se despublican automáticamente y el histórico se conserva íntegro. "Reactivar" no vuelve a publicar nada por sí solo — el seleccionador decide manualmente qué vacantes republicar.</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('crear_usuario') || tienePermiso('editar_usuario')): ?>
	<div class="card mb-4" id="usuarios">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">9. Usuarios</h6>
			<ul>
				<li>Al crear un usuario le asignas un perfil; si el perfil es "Empresa" (o cualquier perfil ligado a empresa), también eliges a qué empresa pertenece.</li>
				<li>Puedes activar o desactivar un usuario desde el listado — uno desactivado no puede iniciar sesión. Un administrador no puede autodesactivarse. Ambas acciones quedan en auditoría.</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('configurar_perfiles')): ?>
	<div class="card mb-4" id="perfiles">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">10. Perfiles y permisos</h6>
			<ul>
				<li>Administrador, Seleccionador y Empresa son los perfiles de fábrica, pero desde <a href="<?= RUTA_URL ?>perfiles/index">Perfiles</a> puedes crear perfiles nuevos combinando libremente los permisos del catálogo (por ejemplo "Seleccionador Junior" o "Empresa - solo terna final").</li>
				<li>Cada permiso es una acción atómica (crear vacante, ver postulantes, aprobar terna, etc.); un usuario nunca se valida comparando el nombre de su perfil, sino revisando si tiene el permiso puntual — por eso este mismo manual cambia de contenido según los permisos que tengas, no según el nombre de tu perfil.</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('configurar_catalogos')): ?>
	<div class="card mb-4" id="configuracion">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">11. Configuración general</h6>
			<ul>
				<li>Administra las categorías de cargo y modalidades de trabajo desde <a href="<?= RUTA_URL ?>configuracionGeneral/index">Configuración</a>.</li>
				<li>"Anular" es una baja lógica: la categoría/modalidad deja de aparecer para vacantes nuevas pero no afecta a las que ya la usan, y puede reactivarse en cualquier momento.</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('ver_auditoria')): ?>
	<div class="card mb-4" id="auditoria">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">12. Auditoría</h6>
			<ul>
				<li>El registro de <a href="<?= RUTA_URL ?>auditorias/index">Auditoría</a> guarda las acciones sensibles del sistema: cambios de perfil, bajas/reactivaciones de empresa, activación/desactivación de usuarios, evaluaciones forzadas y sentencias ejecutadas desde el visor de base de datos.</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

	<?php if(tienePermiso('administrar_bd')): ?>
	<div class="card mb-4" id="base-datos">
		<div class="card-body">
			<h6 class="text-uppercase text-muted mb-3">13. Base de datos</h6>
			<ul>
				<li>El visor de <a href="<?= RUTA_URL ?>baseDatos/index">Base de datos</a> es una herramienta de mantenimiento pensada para cuando el sitio está en hosting compartido y no hay un cliente SQLite externo a mano: lista tablas, muestra filas paginadas y trae una consola para ejecutar SQL directo.</li>
				<li>Cada sentencia ejecutada (exitosa o con error) queda registrada en auditoría con el SQL completo — úsala con cuidado, especialmente sentencias de escritura (UPDATE/DELETE/ALTER).</li>
				<li>Puedes copiar o descargar en Excel el contenido de una tabla o el resultado de una consulta de solo lectura (SELECT/PRAGMA/EXPLAIN).</li>
			</ul>
		</div>
	</div>
	<?php endif; ?>

</div>
