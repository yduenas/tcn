<?php $vacante = $datos['vacante']; ?>
<div class="app-main" style="max-width:720px;">
	<a href="<?= RUTA_URL ?>portal/index" class="btn btn-link px-0">&larr; Volver a las vacantes</a>

	<div class="d-flex align-items-center mb-3">
		<?php if(!$vacante->es_anonima): ?>
			<img src="<?= RUTA_URL.htmlspecialchars($vacante->empresa_logo) ?>" alt="" style="height:36px; margin-right:12px;">
			<span class="text-muted"><?= htmlspecialchars($vacante->empresa_nombre) ?></span>
		<?php else: ?>
			<span class="text-muted">Empresa confidencial</span>
		<?php endif; ?>
	</div>

	<div class="d-flex justify-content-between align-items-start flex-wrap">
		<h3 class="mb-0"><?= htmlspecialchars($vacante->titulo) ?></h3>
		<a href="<?= RUTA_URL ?>portal/postular/<?= $vacante->id ?>" class="btn btn-primary btn-sm">Postular a esta vacante</a>
	</div>
	<p class="text-muted">
		<?= htmlspecialchars($vacante->cargo_nombre) ?> · <?= htmlspecialchars($vacante->modalidad_nombre) ?>
		<?php if($vacante->ubicacion): ?> · <?= htmlspecialchars($vacante->ubicacion) ?><?php endif; ?>
	</p>
	<p>
		<strong>Salario: </strong>
		<?php if($vacante->ocultar_salario): ?>
			A convenir
		<?php elseif($vacante->salario_min || $vacante->salario_max): ?>
			<?= htmlspecialchars($vacante->salario_min ?? '?') ?> - <?= htmlspecialchars($vacante->salario_max ?? '?') ?>
		<?php else: ?>
			No especificado
		<?php endif; ?>
	</p>

	<?php /* HTML ya sanitizado server-side al guardar (Vacantes::sanitizarHtmlBasico()) -- se
	         renderiza tal cual, sin htmlspecialchars(), para que el formato del editor se vea.
	         Envuelto en .card (fondo blanco, seccion 5 del CLAUDE.md) para diferenciarse del fondo
	         --bg de toda la pagina, con el titulo como .eyebrow (mono, teal, mayusculas) para que
	         no pase desapercibido -- pedido de Ytalo, 2026-07-15. **/ ?>
	<?php if(!empty($vacante->objetivo_puesto)): ?>
		<div class="card p-4 mt-4">
			<span class="section-title">Objetivo del puesto</span>
			<div><?= $vacante->objetivo_puesto ?></div>
		</div>
	<?php endif; ?>

	<?php if(!empty($vacante->funciones)): ?>
		<div class="card p-4 mt-3">
			<span class="section-title">Funciones</span>
			<div><?= $vacante->funciones ?></div>
		</div>
	<?php endif; ?>

	<a href="<?= RUTA_URL ?>portal/postular/<?= $vacante->id ?>" class="btn btn-primary mt-3">Postular a esta vacante</a>
</div>
