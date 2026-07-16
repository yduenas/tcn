<?php $pe = $datos['postulacion_evaluacion']; ?>
<div class="app-main" style="max-width:640px;">
	<h4 class="mb-1"><?= htmlspecialchars($pe->evaluacion_nombre) ?></h4>
	<p class="text-muted mb-4"><?= htmlspecialchars($pe->instrucciones) ?></p>

	<div class="alert alert-warning">
		<strong>Antes de empezar:</strong> esta evaluación tiene un límite de
		<strong><?= (int) $pe->tiempo_limite_min ?> minutos</strong>. El cronómetro arranca
		en el momento en que hagas clic en "Comenzar" — no antes. Una vez iniciada, no se
		puede pausar ni reiniciar; si se agota el tiempo sin enviar tus respuestas, la
		evaluación queda sin completar.
	</div>

	<form method="post" action="<?= RUTA_URL ?>evaluaciones/iniciar/<?= $datos['token'] ?>/<?= $pe->id ?>">
		<button type="submit" class="btn btn-primary">Comenzar ahora</button>
		<a href="<?= RUTA_URL ?>evaluaciones/<?= $datos['token'] ?>" class="btn btn-link">Volver, todavía no</a>
	</form>
</div>
