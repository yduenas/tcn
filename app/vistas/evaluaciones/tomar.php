<?php $pe = $datos['postulacion_evaluacion']; ?>
<div class="app-main" style="max-width:720px;">
	<div class="d-flex justify-content-between align-items-start">
		<div>
			<h4 class="mb-1"><?= htmlspecialchars($pe->evaluacion_nombre) ?></h4>
			<p class="text-muted mb-4"><?= htmlspecialchars($pe->instrucciones) ?></p>
		</div>
		<?php if($pe->tiempo_limite_min): ?>
			<div class="stat-card text-center" style="min-width:120px;">
				<span class="stat-num" id="tiempo-restante" style="font-size:1.4rem;">--:--</span>
				<span class="stat-label">Tiempo restante</span>
			</div>
		<?php endif; ?>
	</div>

	<form method="post" action="<?= RUTA_URL ?>evaluaciones/enviarRespuestas/<?= $datos['token'] ?>/<?= $pe->id ?>" id="form-evaluacion">
		<?php foreach($datos['preguntas'] as $i => $pregunta): ?>
		<div class="card mb-3">
			<div class="card-body">
				<h6 class="card-title">
					<?= ($i + 1) ?>. <?= htmlspecialchars($pregunta->enunciado) ?>
					<?php if($pregunta->competencia_nombre): ?>
						<span class="badge badge-light text-muted"><?= htmlspecialchars($pregunta->competencia_nombre) ?></span>
					<?php endif; ?>
				</h6>

				<?php if($pe->tipo === 'opcion_unica'): ?>
					<?php foreach($pregunta->opciones as $opcion): ?>
						<div class="form-check">
							<input type="radio" class="form-check-input" name="respuesta[<?= $pregunta->id ?>]" value="<?= $opcion->id ?>" required>
							<label class="form-check-label"><?= htmlspecialchars($opcion->texto) ?></label>
						</div>
					<?php endforeach; ?>
				<?php else: ?>
					<table class="table table-sm table-borderless mb-0">
						<thead>
							<tr><th></th><th class="text-center" style="width:90px;">Más efectiva</th><th class="text-center" style="width:90px;">Menos efectiva</th></tr>
						</thead>
						<tbody>
							<?php foreach($pregunta->opciones as $opcion): ?>
							<tr>
								<td><?= htmlspecialchars($opcion->texto) ?></td>
								<td class="text-center">
									<input type="radio" name="mas[<?= $pregunta->id ?>]" value="<?= $opcion->id ?>" required>
								</td>
								<td class="text-center">
									<input type="radio" name="menos[<?= $pregunta->id ?>]" value="<?= $opcion->id ?>" required>
								</td>
							</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>
			</div>
		</div>
		<?php endforeach; ?>

		<button type="submit" class="btn btn-primary">Enviar respuestas</button>
	</form>
</div>

<script>
// Evita elegir la misma opcion como "mas" y "menos" en la misma pregunta
document.querySelectorAll('input[name^="mas["], input[name^="menos["]').forEach(function(radio){
	radio.addEventListener('change', function(){
		var preguntaId = this.name.match(/\[(\d+)\]/)[1];
		var opuesto = this.name.startsWith('mas') ? 'menos' : 'mas';
		var campo = document.querySelector('input[name="' + opuesto + '[' + preguntaId + ']"][value="' + this.value + '"]');
		if(campo && campo.checked){ campo.checked = false; }
	});
});

<?php if($pe->tiempo_limite_min): ?>
// Cuenta regresiva; el corte real lo hace el servidor, esto solo mejora la experiencia
var segundosRestantes = <?= (int) $datos['segundosRestantes'] ?>;
var elTiempo = document.getElementById('tiempo-restante');
function actualizarTiempo(){
	if(segundosRestantes <= 0){
		elTiempo.textContent = '00:00';
		document.getElementById('form-evaluacion').submit();
		return;
	}
	var min = Math.floor(segundosRestantes / 60);
	var seg = segundosRestantes % 60;
	elTiempo.textContent = (min < 10 ? '0' : '') + min + ':' + (seg < 10 ? '0' : '') + seg;
	segundosRestantes--;
}
actualizarTiempo();
setInterval(actualizarTiempo, 1000);
<?php endif; ?>
</script>
