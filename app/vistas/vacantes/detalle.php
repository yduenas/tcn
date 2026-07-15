<?php $v = $datos['vacante']; ?>
<div class="app-main" style="max-width:720px;">
	<a href="<?= RUTA_URL ?>vacantes/index" class="btn btn-link px-0">&larr; Volver a vacantes</a>

	<div class="d-flex justify-content-between align-items-start mb-1 mt-2">
		<h4 class="mb-0"><?= htmlspecialchars($v->titulo) ?></h4>
		<?php if(tienePermiso('editar_vacante')): ?>
			<a href="<?= RUTA_URL ?>vacantes/editar/<?= $v->id ?>" class="btn btn-sm btn-outline-primary">Editar</a>
		<?php endif; ?>
	</div>
	<p class="text-muted">
		<?= htmlspecialchars($v->empresa_nombre) ?> ·
		<?= htmlspecialchars($v->cargo_nombre) ?> · <?= htmlspecialchars($v->modalidad_nombre) ?>
		<?php if($v->ubicacion): ?> · <?= htmlspecialchars($v->ubicacion) ?><?php endif; ?>
	</p>
	<p class="text-muted mb-4">
		<strong>Seleccionador:</strong> <?= htmlspecialchars(trim($v->seleccionador_nombres.' '.$v->seleccionador_apellidos)) ?>
	</p>

	<?php /* objetivo_puesto/funciones vienen de un editor de texto enriquecido (Summernote) ya
	         sanitizado server-side al guardar (Vacantes::sanitizarHtmlBasico()) -- se renderiza
	         el HTML tal cual, sin htmlspecialchars(), para que el formato se vea.
	         Envuelto en .card (fondo blanco) + titulo .eyebrow (mono, teal), mismo tratamiento que
	         portal/detalle.php -- pedido de Ytalo, 2026-07-15, para que la seccion no se pierda
	         contra el fondo --bg de la pagina y el titulo no pase desapercibido. **/ ?>
	<?php if(!empty($v->objetivo_puesto)): ?>
		<div class="card p-4 mt-4">
			<span class="section-title">Objetivo del puesto</span>
			<div><?= $v->objetivo_puesto ?></div>
		</div>
	<?php endif; ?>

	<?php if(!empty($v->funciones)): ?>
		<div class="card p-4 mt-3">
			<span class="section-title">Funciones</span>
			<div><?= $v->funciones ?></div>
		</div>
	<?php endif; ?>

	<?php if(empty($v->objetivo_puesto) && empty($v->funciones)): ?>
		<p class="text-muted mt-4">Esta vacante todavía no tiene contenido cargado. Edítala para agregar objetivo del puesto y funciones.</p>
	<?php endif; ?>
</div>
