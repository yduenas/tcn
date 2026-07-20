<?php
	$vacante = $datos['vacante'];
	$candidatoDraft = $datos['candidatoDraft'] ?? null;
	$cvDraftDatos = $datos['cvDraft']['datos'] ?? null;
	$archivoDraft = $datos['cvDraft']['nombre_archivo_original'] ?? null;

	// Un candidato que ya postulo antes (correo+DNI verificados, Portal::cargarDatos())
	// prevalece sobre lo detectado del CV -- son datos reales guardados, no una heuristica.
	// Los valores se recortan al mismo maxlength de cada input de abajo: un value=""
	// precargado mas largo que el maxlength NO se trunca solo por tener el atributo
	// (el navegador solo bloquea escribir MAS alla del limite, no un valor ya largo
	// puesto por PHP) -- sin este recorte aqui, un candidato_draft con datos viejos
	// (guardados antes de este fix) o un cv_draft mal detectado seguirian mostrando
	// mas texto del esperado en el campo, aunque el input ya tenga maxlength.
	$valNombres = limitarLongitud($candidatoDraft['nombres'] ?? ($cvDraftDatos['nombre'] ?? ''), 100);
	$valApellidos = limitarLongitud($candidatoDraft['apellidos'] ?? '', 100);
	$valEmail = limitarLongitud($candidatoDraft['email'] ?? ($cvDraftDatos['email'] ?? ''), 150);
	$valTelefono = limitarLongitud($candidatoDraft['telefono'] ?? ($cvDraftDatos['telefono'] ?? ''), 20);
	$valDni = limitarLongitud($candidatoDraft['dni'] ?? '', 15);
	$valPretension = $candidatoDraft['pretension_salarial'] ?? '';
	$valDisponibilidad = limitarLongitud($candidatoDraft['disponibilidad'] ?? '', 100);
	$valHabilidades = $candidatoDraft['habilidades'] ?? ($cvDraftDatos['habilidades'] ?? []);

	$experienciaFilas = $candidatoDraft['experiencia_filas'] ?? [];
	if(empty($experienciaFilas)){
		$experienciaFilas = [['empresa' => '', 'cargo' => '', 'fecha_inicio' => '', 'fecha_fin' => '', 'actualidad' => false, 'descripcion' => '']];
	}
	$educacionFilas = $candidatoDraft['educacion_filas'] ?? [];
	if(empty($educacionFilas)){
		$educacionFilas = [['institucion' => '', 'grado' => '', 'campo_estudio' => '', 'fecha_inicio' => '', 'fecha_fin' => '']];
	}
?>
<div class="app-main" style="max-width:720px;">
	<h4 class="mb-1">Postular a: <?= htmlspecialchars($vacante->titulo) ?></h4>
	<p class="text-muted mb-4"><?= $vacante->es_anonima ? 'Empresa confidencial' : htmlspecialchars($vacante->empresa_nombre) ?></p>

	<?php if(!empty($datos['error'])): ?>
		<div class="alert alert-danger"><?= htmlspecialchars($datos['error']) ?></div>
	<?php endif; ?>
	<?php if(!empty($datos['aviso'])): ?>
		<div class="alert alert-info"><?= htmlspecialchars($datos['aviso']) ?></div>
	<?php endif; ?>

	<?php if(empty($datos['fase2'])): ?>
		<div class="card mb-4">
			<div class="card-body">
				<h6 class="text-uppercase text-muted mb-2">¿Ya postulaste antes?</h6>
				<p style="font-size:.88rem; color:var(--slate);">Ingresa el correo y DNI con los que postulaste para cargar tus datos automáticamente y no llenar todo el formulario de nuevo.</p>
				<form method="post" action="<?= RUTA_URL ?>portal/cargarDatos/<?= $vacante->id ?>" class="form-row align-items-end">
					<div class="form-group col-md-5 mb-2">
						<label>Correo</label>
						<input type="email" class="form-control" name="correo_previo" required>
					</div>
					<div class="form-group col-md-4 mb-2">
						<label>DNI</label>
						<input type="text" class="form-control" name="dni_previo" required>
					</div>
					<div class="col-md-3 mb-2">
						<button type="submit" class="btn btn-primary btn-block">Continuar</button>
					</div>
				</form>
				<p class="mb-0 mt-2" style="font-size:.85rem;">
					<a href="<?= RUTA_URL ?>portal/continuarSinDatos/<?= $vacante->id ?>">Es mi primera vez postulando, o no tengo estos datos a mano</a>
				</p>
			</div>
		</div>
	<?php else: ?>

	<?php if($candidatoDraft): ?>
		<div class="alert alert-success">
			Encontramos tu postulación anterior — completamos el formulario con tus datos. Revísalos antes de enviar.
		</div>
	<?php endif; ?>

	<h6 class="text-uppercase text-muted mb-2">Curriculum vitae</h6>
	<p style="font-size:.88rem; color:var(--slate);">Sube tu CV en formato PDF, reducido y de una sola hoja (máximo 300 KB). No adjuntes un CV documentado (certificados, diplomas escaneados, etc.) — cuanto más simple el documento, mejor podremos leer tu información.</p>

	<?php if($archivoDraft): ?>
		<div class="alert alert-success">
			CV analizado: <strong><?= htmlspecialchars($archivoDraft) ?></strong>. Revisa y completa los campos detectados automáticamente antes de enviar.
		</div>
	<?php endif; ?>

	<form method="post" enctype="multipart/form-data" action="<?= RUTA_URL ?>portal/extraerCV/<?= $vacante->id ?>" class="form-row mb-4">
		<div class="col-md-8 mb-2">
			<input type="file" class="form-control-file" name="cv" accept=".pdf" <?= $archivoDraft ? '' : 'required' ?>>
		</div>
		<div class="col-md-4 mb-2">
			<button type="submit" class="btn btn-outline-primary btn-block">Autocompletar desde mi CV</button>
		</div>
	</form>

	<form method="post" enctype="multipart/form-data" action="<?= RUTA_URL ?>portal/enviar/<?= $vacante->id ?>" id="form-postular">

		<h6 class="text-uppercase text-muted mt-4 mb-2">Datos personales</h6>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label>Nombres</label>
				<input type="text" class="form-control" name="nombres" value="<?= htmlspecialchars($valNombres) ?>" maxlength="100" required>
				<?php if($candidatoDraft): ?><small class="form-text text-muted">Cargado desde tu postulación anterior.</small>
				<?php elseif(!empty($cvDraftDatos['nombre'])): ?><small class="form-text text-muted">Detectado automáticamente — verifica que sea correcto.</small><?php endif; ?>
			</div>
			<div class="form-group col-md-6">
				<label>Apellidos</label>
				<input type="text" class="form-control" name="apellidos" value="<?= htmlspecialchars($valApellidos) ?>" maxlength="100" required>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label>Correo</label>
				<input type="email" class="form-control" name="email" value="<?= htmlspecialchars($valEmail) ?>" maxlength="150" required>
				<?php if($candidatoDraft): ?><small class="form-text text-muted">Cargado desde tu postulación anterior.</small>
				<?php elseif(!empty($cvDraftDatos['email'])): ?><small class="form-text text-muted">Detectado automáticamente — verifica que sea correcto.</small><?php endif; ?>
			</div>
			<div class="form-group col-md-3">
				<label>Teléfono</label>
				<input type="text" class="form-control" name="telefono" value="<?= htmlspecialchars($valTelefono) ?>" maxlength="20">
				<?php if($candidatoDraft): ?><small class="form-text text-muted">Cargado.</small>
				<?php elseif(!empty($cvDraftDatos['telefono'])): ?><small class="form-text text-muted">Detectado automáticamente.</small><?php endif; ?>
			</div>
			<div class="form-group col-md-3">
				<label>DNI</label>
				<input type="text" class="form-control" name="dni" value="<?= htmlspecialchars($valDni) ?>" maxlength="15" required>
			</div>
		</div>
		<div class="form-row">
			<div class="form-group col-md-6">
				<label>Pretensión salarial</label>
				<input type="number" step="0.01" class="form-control" name="pretension_salarial" value="<?= htmlspecialchars($valPretension) ?>">
			</div>
			<div class="form-group col-md-6">
				<label>Disponibilidad</label>
				<input type="text" class="form-control" name="disponibilidad" value="<?= htmlspecialchars($valDisponibilidad) ?>" maxlength="100" placeholder="Ej. Inmediata">
			</div>
		</div>
		<div class="form-group">
			<label>Habilidades (separadas por coma)</label>
			<input type="text" class="form-control" name="habilidades" value="<?= htmlspecialchars(limitarLongitud(!empty($valHabilidades) ? implode(', ', $valHabilidades) : '', 300)) ?>" maxlength="300">
			<?php if($candidatoDraft && !empty($valHabilidades)): ?><small class="form-text text-muted">Cargadas desde tu postulación anterior.</small>
			<?php elseif(!empty($cvDraftDatos['habilidades'])): ?><small class="form-text text-muted">Detectadas automáticamente — verifica que sean correctas.</small><?php endif; ?>
		</div>

		<h6 class="text-uppercase text-muted mt-4 mb-2">Experiencia laboral</h6>
		<?php if(!empty($cvDraftDatos['experiencia'])): ?>
			<p class="text-muted" style="font-size:.82rem;">Detectamos posibles bloques de experiencia en tu CV — cópialos a los campos correspondientes:</p>
			<ul style="font-size:.82rem; color:var(--slate);">
				<?php foreach($cvDraftDatos['experiencia'] as $bloque): ?><li><?= htmlspecialchars($bloque) ?></li><?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<div id="experiencia-filas">
			<?php foreach($experienciaFilas as $fila): ?>
			<div class="experiencia-fila border rounded p-3 mb-2">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>Empresa</label>
						<input type="text" class="form-control" name="experiencia_empresa[]" value="<?= htmlspecialchars(limitarLongitud($fila['empresa'], 150)) ?>" maxlength="150">
					</div>
					<div class="form-group col-md-6">
						<label>Cargo</label>
						<input type="text" class="form-control" name="experiencia_cargo[]" value="<?= htmlspecialchars(limitarLongitud($fila['cargo'], 150)) ?>" maxlength="150">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label>Desde</label>
						<input type="month" class="form-control" name="experiencia_inicio[]" value="<?= htmlspecialchars($fila['fecha_inicio']) ?>">
					</div>
					<div class="form-group col-md-4">
						<label>Hasta</label>
						<input type="month" class="form-control" name="experiencia_fin[]" value="<?= htmlspecialchars($fila['fecha_fin']) ?>">
					</div>
					<div class="form-group col-md-4 form-check" style="margin-top:2rem;">
						<input type="checkbox" class="form-check-input" name="experiencia_actual[]" value="1" <?= !empty($fila['actualidad']) ? 'checked' : '' ?>>
						<label class="form-check-label">Actualmente trabajo aquí</label>
					</div>
				</div>
				<div class="form-group">
					<label>Descripción</label>
					<textarea class="form-control" name="experiencia_descripcion[]" rows="2" maxlength="1000"><?= htmlspecialchars(limitarLongitud($fila['descripcion'], 1000)) ?></textarea>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="btn btn-sm btn-outline-secondary mb-4" onclick="agregarFila('experiencia-filas')">+ Agregar otra experiencia</button>

		<h6 class="text-uppercase text-muted mt-2 mb-2">Educación</h6>
		<?php if(!empty($cvDraftDatos['educacion'])): ?>
			<p class="text-muted" style="font-size:.82rem;">Detectamos posibles estudios en tu CV — cópialos a los campos correspondientes:</p>
			<ul style="font-size:.82rem; color:var(--slate);">
				<?php foreach($cvDraftDatos['educacion'] as $bloque): ?><li><?= htmlspecialchars($bloque) ?></li><?php endforeach; ?>
			</ul>
		<?php endif; ?>
		<div id="educacion-filas">
			<?php foreach($educacionFilas as $fila): ?>
			<div class="educacion-fila border rounded p-3 mb-2">
				<div class="form-row">
					<div class="form-group col-md-6">
						<label>Institución</label>
						<input type="text" class="form-control" name="educacion_institucion[]" value="<?= htmlspecialchars(limitarLongitud($fila['institucion'], 150)) ?>" maxlength="150">
					</div>
					<div class="form-group col-md-6">
						<label>Grado</label>
						<input type="text" class="form-control" name="educacion_grado[]" value="<?= htmlspecialchars(limitarLongitud($fila['grado'], 100)) ?>" maxlength="100" placeholder="Ej. Bachiller, Licenciado">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-md-4">
						<label>Campo de estudio</label>
						<input type="text" class="form-control" name="educacion_campo[]" value="<?= htmlspecialchars(limitarLongitud($fila['campo_estudio'], 150)) ?>" maxlength="150">
					</div>
					<div class="form-group col-md-4">
						<label>Desde</label>
						<input type="month" class="form-control" name="educacion_inicio[]" value="<?= htmlspecialchars($fila['fecha_inicio']) ?>">
					</div>
					<div class="form-group col-md-4">
						<label>Hasta</label>
						<input type="month" class="form-control" name="educacion_fin[]" value="<?= htmlspecialchars($fila['fecha_fin']) ?>">
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
		<button type="button" class="btn btn-sm btn-outline-secondary mb-4" onclick="agregarFila('educacion-filas')">+ Agregar otra educación</button>

		<?php if($archivoDraft): ?>
			<div class="alert alert-light border" style="font-size:.85rem;">
				Ya subiste <strong><?= htmlspecialchars($archivoDraft) ?></strong> como tu CV.
				<a href="#" id="link-reemplazar-cv">¿Quieres reemplazarlo?</a>
			</div>
			<div class="form-group" id="campo-reemplazar-cv" style="display:none;">
				<label>Reemplazar CV</label>
				<input type="file" class="form-control-file" name="cv" accept=".pdf">
			</div>
		<?php else: ?>
			<p style="font-size:.85rem;">
				<a href="#" id="link-adjuntar-cv">¿Prefieres adjuntar tu CV directamente, sin autocompletar el formulario?</a>
			</p>
			<div class="form-group" id="campo-adjuntar-cv" style="display:none;">
				<label>Adjunta tu CV</label>
				<input type="file" class="form-control-file" name="cv" accept=".pdf">
				<small class="form-text text-muted">Si ya postulaste antes con este mismo correo, no hace falta que lo subas de nuevo — se reutiliza el que ya tenemos registrado. Solo adjunta uno si es tu primera postulación o quieres reemplazarlo.</small>
			</div>
		<?php endif; ?>

		<div class="form-group form-check mt-4">
			<input type="checkbox" class="form-check-input campo-obligatorio" id="consentimiento" name="consentimiento" value="1" required>
			<label class="form-check-label" for="consentimiento">
				Autorizo el tratamiento de mis datos personales para fines de selección de personal.
			</label>
		</div>
		<div class="form-group form-check">
			<input type="checkbox" class="form-check-input campo-obligatorio" id="declaracion_veracidad" name="declaracion_veracidad" value="1" required>
			<label class="form-check-label" for="declaracion_veracidad">
				Declaro que la información en este formulario es correcta y soy responsable de su exactitud.
			</label>
		</div>

		<button type="submit" class="btn btn-primary" id="btn-enviar-postulacion" disabled>Enviar postulación</button>
		<a href="<?= RUTA_URL ?>portal/vacante/<?= $vacante->id ?>" class="btn btn-outline-secondary">Cancelar</a>
	</form>

	<?php endif; ?>
</div>

<script>
function agregarFila(contenedorId){
	var contenedor = document.getElementById(contenedorId);
	var primeraFila = contenedor.children[0];
	var nuevaFila = primeraFila.cloneNode(true);
	nuevaFila.querySelectorAll('input, textarea').forEach(function(campo){
		if(campo.type === 'checkbox'){ campo.checked = false; } else { campo.value = ''; }
	});
	contenedor.appendChild(nuevaFila);
}

// Campo de CV colapsado por defecto cuando ya se subio uno via "Autocompletar desde mi
// CV" (evita tener 2 campos de adjuntar CV visibles a la vez en la misma pantalla,
// reportado por Ytalo como redundante, 2026-07-16) -- se muestra solo si el candidato
// pide explicitamente reemplazarlo.
var linkReemplazarCv = document.getElementById('link-reemplazar-cv');
if(linkReemplazarCv){
	linkReemplazarCv.addEventListener('click', function(e){
		e.preventDefault();
		document.getElementById('campo-reemplazar-cv').style.display = 'block';
		linkReemplazarCv.style.display = 'none';
	});
}
var linkAdjuntarCv = document.getElementById('link-adjuntar-cv');
if(linkAdjuntarCv){
	linkAdjuntarCv.addEventListener('click', function(e){
		e.preventDefault();
		document.getElementById('campo-adjuntar-cv').style.display = 'block';
		linkAdjuntarCv.style.display = 'none';
	});
}
// El boton de enviar permanece deshabilitado hasta marcar ambos checkboxes obligatorios
// (seccion 6.4) -- el formulario (y por lo tanto este boton) solo existe en el DOM en la
// fase 2 (ver portal/postular.php), asi que se guarda todo detras de un chequeo null.
var checksObligatorios = document.querySelectorAll('#form-postular .campo-obligatorio');
var btnEnviar = document.getElementById('btn-enviar-postulacion');
if(btnEnviar){
	function actualizarBotonEnviar(){
		var todosMarcados = true;
		checksObligatorios.forEach(function(c){ if(!c.checked){ todosMarcados = false; } });
		btnEnviar.disabled = !todosMarcados;
	}
	checksObligatorios.forEach(function(c){ c.addEventListener('change', actualizarBotonEnviar); });
	actualizarBotonEnviar();
}
</script>
