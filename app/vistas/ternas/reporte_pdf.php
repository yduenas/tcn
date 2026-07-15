<?php
	$p = $datos['postulacion'];
	$recomendaciones = [
		'recomendado' => 'Recomendado',
		'recomendado_con_reservas' => 'Recomendado con reservas',
		'no_recomendado' => 'No recomendado',
	];
	$rutaLogo = RUTA_DOCUMENTO.$p->empresa_logo;

	$discNombres = [
		'D' => 'Dominancia',
		'I' => 'Influencia',
		'S' => 'Estabilidad',
		'C' => 'Cumplimiento',
	];
	$descripcionesDisc = [
		'D' => 'Perfil orientado a resultados: decidido, directo y con iniciativa para asumir retos. Prefiere tomar el control y avanzar rápido, aunque puede pasar por alto detalles o mostrarse impaciente.',
		'I' => 'Perfil sociable y persuasivo: se relaciona con facilidad, comunica con entusiasmo e influye positivamente en el equipo, aunque puede priorizar la interacción social sobre el detalle técnico.',
		'S' => 'Perfil paciente y colaborador: valora la estabilidad, el trabajo en equipo y la constancia, prefiriendo procesos predecibles antes que cambios abruptos.',
		'C' => 'Perfil analítico y meticuloso: se apoya en datos, normas y procedimientos, priorizando la precisión y la calidad por sobre la velocidad.',
	];
?>
<?php
	/* IMPORTANTE: html2pdf busca el texto "menor-que body" en este HTML de forma literal (no es
	   un parser real) y lo reemplaza por una etiqueta de pagina, copiando los atributos del body.
	   Por eso backtop/backbottom van en la etiqueta body (no en una etiqueta de pagina aparte, eso
	   duplica la hoja) -- y por eso este comentario NO puede mencionar esas palabras clave entre
	   corchetes angulares, o rompe el reemplazo (ya paso una vez). Si el reporte vuelve a salir con
	   texto superpuesto o una hoja de mas: backtop mal calibrado (chico = superposicion, grande =
	   empuja una tabla real al salto de pagina automatico) -- reverifica con pdftotext -f/-l. */
?>
<html>
<body backtop="34mm" backbottom="14mm" style="font-family: helvetica, sans-serif; color:#0F2138; font-size:11px;">
	<page_header>
	<table style="width:100%; border-bottom:2px solid #3ECAB5; margin-bottom:10px;">
		<tr>
			<td style="width:60%; vertical-align:middle;">
				<span style="font-size:20px; font-weight:bold; color:#123564;">COMPLEMENT</span><br>
				<span style="font-size:11px; color:#5B6B7C;">Reporte de candidato</span>
			</td>
			<td style="width:40%; text-align:right; vertical-align:middle;">
				<img src="<?= $rutaLogo ?>" style="height:32px;">
			</td>
		</tr>
	</table>
	<table style="width:100%; margin-bottom:12px;">
		<tr>
			<td style="width:50%;">
				<b>Candidato:</b> <?= htmlspecialchars($p->nombres.' '.$p->apellidos) ?><br>
				<b>Correo:</b> <?= htmlspecialchars($p->email) ?><br>
				<b>Teléfono:</b> <?= htmlspecialchars($p->telefono ?: '-') ?>
			</td>
			<td style="width:50%;">
				<b>Vacante:</b> <?= htmlspecialchars($p->vacante_titulo) ?><br>
				<b>Empresa:</b> <?= htmlspecialchars($p->empresa_nombre) ?><br>
				<b>Fecha:</b> <?= date('d/m/Y') ?>
			</td>
		</tr>
	</table>
	</page_header>
	<page_footer>
	<table style="width:100%; border-top:1px solid #ccc;">
		<tr>
			<td style="padding-top:4px; font-size:9px; color:#5B6B7C;">
				Complement · Reporte generado el <?= date('d/m/Y H:i') ?> · Documento confidencial, uso exclusivo del proceso de selección.
			</td>
			<td style="padding-top:4px; font-size:9px; color:#5B6B7C; text-align:right; width:80px;">
				Página [[page_cu]] de [[page_nb]]
			</td>
		</tr>
	</table>
	</page_footer>

	<?php
		$sjt = null; $disc = null; $capacidades = [];
		foreach($datos['evaluaciones'] as $ev){
			if(!$ev->resultado_json) continue;
			$r = json_decode($ev->resultado_json, true);
			if($r['tipo'] === 'sjt') $sjt = $r;
			if($r['tipo'] === 'disc') $disc = $r;
			if($r['tipo'] === 'opcion_unica') $capacidades[$ev->evaluacion_nombre] = $r;
		}
	?>

	<?php if($sjt): ?>
	<h3 style="color:#123564; border-bottom:1px solid #eee;">Competencias</h3>
	<table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
		<tr style="background-color:#F6F8FA;">
			<td style="padding:4px; border:1px solid #eee; width:38%;"><b>Competencia</b></td>
			<td style="padding:4px; border:1px solid #eee; width:34%;"><b>Avance</b></td>
			<td style="padding:4px; border:1px solid #eee; width:10%;"><b>%</b></td>
			<td style="padding:4px; border:1px solid #eee; width:18%;"><b>Nivel</b></td>
		</tr>
		<?php foreach($sjt['competencias'] as $nombre => $c): ?>
		<tr>
			<td style="padding:4px; border:1px solid #eee; width:38%;"><?= htmlspecialchars($nombre) ?></td>
			<td style="padding:4px; border:1px solid #eee; width:34%;">
				<table style="width:100%;"><tr>
					<td style="background-color:#3ECAB5; width:<?= (int) $c['porcentaje'] ?>%; height:8px;"></td>
					<td style="background-color:#F6F8FA; width:<?= 100 - (int) $c['porcentaje'] ?>%; height:8px;"></td>
				</tr></table>
			</td>
			<td style="padding:4px; border:1px solid #eee; width:10%;"><?= $c['porcentaje'] ?>%</td>
			<td style="padding:4px; border:1px solid #eee; width:18%;"><?= htmlspecialchars($c['nivel']) ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>

	<?php if($disc): ?>
	<h3 style="color:#123564; border-bottom:1px solid #eee;">Perfil DISC</h3>
	<table style="width:100%; border-collapse:collapse; margin-bottom:6px;">
		<tr style="background-color:#F6F8FA;">
			<td style="padding:4px; border:1px solid #eee; width:28%;"><b>Dimensión</b></td>
			<td style="padding:4px; border:1px solid #eee; width:57%;"><b>Avance</b></td>
			<td style="padding:4px; border:1px solid #eee; width:15%;"><b>%</b></td>
		</tr>
		<?php foreach($disc['dimensiones'] as $letra => $d): ?>
		<tr>
			<td style="padding:4px; border:1px solid #eee; width:28%;"><?= htmlspecialchars($letra) ?></td>
			<td style="padding:4px; border:1px solid #eee; width:57%;">
				<table style="width:100%;"><tr>
					<td style="background-color:#1B4C91; width:<?= (int) $d['porcentaje'] ?>%; height:8px;"></td>
					<td style="background-color:#F6F8FA; width:<?= 100 - (int) $d['porcentaje'] ?>%; height:8px;"></td>
				</tr></table>
			</td>
			<td style="padding:4px; border:1px solid #eee; width:15%;"><?= $d['porcentaje'] ?>%</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<p style="margin-bottom:8px;"><b>Perfil dominante:</b> <?= htmlspecialchars($discNombres[$disc['perfil_dominante']] ?? $disc['perfil_dominante']) ?> (<?= htmlspecialchars($disc['perfil_dominante']) ?>)</p>

	<?php foreach($disc['dimensiones'] as $letra => $d): ?>
	<p style="margin-bottom:8px; <?= $letra === $disc['perfil_dominante'] ? 'background-color:#F6F8FA; padding:4px;' : '' ?>">
		<b><?= htmlspecialchars($discNombres[$letra] ?? $letra) ?> (<?= htmlspecialchars($letra) ?>)<?= $letra === $disc['perfil_dominante'] ? ' — dominante' : '' ?>:</b><br>
		<span style="color:#5B6B7C;"><?= htmlspecialchars($descripcionesDisc[$letra] ?? '') ?></span>
	</p>
	<?php endforeach; ?>
	<?php endif; ?>

	<?php if(!empty($capacidades)): ?>
	<h3 style="color:#123564; border-bottom:1px solid #eee;">Capacidad</h3>
	<table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
		<tr style="background-color:#F6F8FA;">
			<td style="padding:4px; border:1px solid #eee; width:38%;"><b>Prueba</b></td>
			<td style="padding:4px; border:1px solid #eee; width:34%;"><b>Avance</b></td>
			<td style="padding:4px; border:1px solid #eee; width:10%;"><b>%</b></td>
			<td style="padding:4px; border:1px solid #eee; width:18%;"><b>Nivel</b></td>
		</tr>
		<?php foreach($capacidades as $nombre => $c): ?>
		<tr>
			<td style="padding:4px; border:1px solid #eee; width:38%;"><?= htmlspecialchars($nombre) ?></td>
			<td style="padding:4px; border:1px solid #eee; width:34%;">
				<table style="width:100%;"><tr>
					<td style="background-color:#3ECAB5; width:<?= (int) $c['porcentaje'] ?>%; height:8px;"></td>
					<td style="background-color:#F6F8FA; width:<?= 100 - (int) $c['porcentaje'] ?>%; height:8px;"></td>
				</tr></table>
			</td>
			<td style="padding:4px; border:1px solid #eee; width:10%;"><?= $c['porcentaje'] ?>%</td>
			<td style="padding:4px; border:1px solid #eee; width:18%;"><?= htmlspecialchars($c['nivel']) ?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>

	<page pageset="old">
	<h3 style="color:#123564; border-bottom:1px solid #eee;">Entrevista y recomendación</h3>
	<?php if(!$datos['entrevista']): ?>
		<p>No se registró entrevista para este candidato.</p>
	<?php else: ?>
		<?php
			$escalaEntrevista = [1 => 'Insuficiente', 2 => 'Bajo', 3 => 'Aceptable', 4 => 'Bueno', 5 => 'Excelente'];
		?>
		<?php if(!empty($datos['calificacionesEntrevista'])): ?>
		<table style="width:100%; margin-bottom:10px;">
			<tr>
				<?php if($datos['rutaRadarEntrevista']): ?>
				<td style="width:38%; vertical-align:top; text-align:center;">
					<img src="<?= $datos['rutaRadarEntrevista'] ?>" style="width:62mm;"><br>
					<span style="font-size:9px; color:#5B6B7C;">Escala de 1 (insuficiente) a 5 (excelente).</span>
				</td>
				<?php endif; ?>
				<td style="<?= $datos['rutaRadarEntrevista'] ? 'width:62%;' : 'width:100%;' ?> vertical-align:top;">
					<table style="width:100%; border-collapse:collapse;">
						<tr style="background-color:#F6F8FA;">
							<td style="padding:4px; border:1px solid #eee;"><b>Competencia</b></td>
							<td style="padding:4px; border:1px solid #eee; width:70px;"><b>Calificación</b></td>
							<td style="padding:4px; border:1px solid #eee;"><b>Comentario</b></td>
						</tr>
						<?php foreach($datos['calificacionesEntrevista'] as $c): ?>
						<tr>
							<td style="padding:4px; border:1px solid #eee;"><?= htmlspecialchars($c->competencia_nombre) ?></td>
							<td style="padding:4px; border:1px solid #eee;"><?= (int) $c->calificacion ?>/5 — <?= htmlspecialchars($escalaEntrevista[(int) $c->calificacion] ?? '') ?></td>
							<td style="padding:4px; border:1px solid #eee;"><?= htmlspecialchars($c->comentario) ?></td>
						</tr>
						<?php endforeach; ?>
					</table>
				</td>
			</tr>
		</table>
		<?php endif; ?>
		<?php
			$totalCalif = count($datos['calificacionesEntrevista']);
			$promedioGeneral = $totalCalif > 0
				? round(array_sum(array_column($datos['calificacionesEntrevista'], 'calificacion')) / $totalCalif, 1)
				: null;

			$calificacionesObligatorias = array_filter($datos['calificacionesEntrevista'], function($c) use ($datos){
				return in_array($c->competencia_id, $datos['obligatorias']);
			});
			$totalOblig = count($calificacionesObligatorias);
			$promedioObligatorias = $totalOblig > 0
				? round(array_sum(array_column($calificacionesObligatorias, 'calificacion')) / $totalOblig, 1)
				: null;
		?>
		<p>
			<b>Promedio general (<?= $totalCalif ?> competencia<?= $totalCalif == 1 ? '' : 's' ?> evaluada<?= $totalCalif == 1 ? '' : 's' ?>):</b>
			<?= $promedioGeneral !== null ? $promedioGeneral.'/5' : 'Sin calificar' ?><br>
			<b>Promedio competencias obligatorias (<?= $totalOblig ?>):</b>
			<?= $promedioObligatorias !== null ? $promedioObligatorias.'/5' : 'N/A' ?>
		</p>
		<p><b>Comentarios:</b><br><?= nl2br(htmlspecialchars($datos['entrevista']->notas ?: '-')) ?></p>

		<h3 style="color:#123564; border-bottom:1px solid #eee;">Recomendación final</h3>
		<p><?= htmlspecialchars($recomendaciones[$datos['entrevista']->recomendacion] ?? 'Sin definir') ?></p>

		<h3 style="color:#123564; border-bottom:1px solid #eee;">Glosa de la escala de calificación</h3>
		<table style="width:100%; border-collapse:collapse; margin-bottom:14px;">
			<tr style="background-color:#F6F8FA;">
				<td style="padding:4px; border:1px solid #eee; width:15%;"><b>Nivel</b></td>
				<td style="padding:4px; border:1px solid #eee;"><b>Descripción</b></td>
			</tr>
			<?php foreach($escalaEntrevista as $nivel => $etiqueta): ?>
			<tr>
				<td style="padding:4px; border:1px solid #eee;"><?= $nivel ?></td>
				<td style="padding:4px; border:1px solid #eee;"><?= htmlspecialchars($etiqueta) ?></td>
			</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
	</page>

</body>
</html>
